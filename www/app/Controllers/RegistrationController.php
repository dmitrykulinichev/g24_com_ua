<?php

namespace App\Controllers;

use App\Services\Logger;
use App\Services\SpaApiService;

class RegistrationController
{
    protected $api;

    public function __construct()
    {
        $this->api = new SpaApiService();
    }

    public function getConfig()
    {
        // Використовуємо той самий файл кешу, що і PricingController
        $cacheFile = __DIR__ . '/../../storage/cache/pricing_data.json';
        $cacheTtl = (int)($_ENV['PRICING_CACHE_TTL'] ?? 86400);

        try {
            // 1. Спробуємо віддати з кешу
            if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTtl)) {
                $content = @file_get_contents($cacheFile);
                if ($content) {
                    // Віддаємо JSON з кешу
                    header('Content-Type: application/json');
                    echo $content;
                    return;
                }
            }

            // 2. Якщо кешу немає — йдемо в API
            $response = $this->api->getLandingConfig();
            
            if ($response['status'] === 200 && !empty($response['body'])) {
                // Зберігаємо в кеш
                if (!is_dir(dirname($cacheFile))) {
                    mkdir(dirname($cacheFile), 0755, true);
                }
                // Зберігаємо повну відповідь (plans, recaptcha, forms)
                file_put_contents($cacheFile, json_encode($response['body']));
            }

            // Віддаємо свіжі дані
            response()->json($response['body'], $response['status']);

        } catch (\Throwable $e) {
            Logger::error('Error fetching config', ['message' => $e->getMessage()]);
            
            // Якщо API впало, а кеш є (навіть старий) — спробуємо віддати його
            if (file_exists($cacheFile)) {
                $content = @file_get_contents($cacheFile);
                if ($content) {
                    header('Content-Type: application/json');
                    echo $content;
                    return;
                }
            }

            response()->json(['status' => 'error', 'message' => 'Service Unavailable'], 503);
        }
    }

    public function register()
    {
        try {
            $data = request()->body();
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

            Logger::info('Спроба реєстрації (Proxy)', ['ip' => $ip, 'email' => $data['owner_email'] ?? 'unknown']);

            $payload = [
                'park_name'   => $data['park_name'] ?? '',
                'owner_name'  => $data['owner_name'] ?? '',
                'owner_email' => $data['owner_email'] ?? '',
                'phone'       => $data['phone'] ?? '',
                'plan'        => $data['plan'] ?? 'monthly',
                'g-recaptcha-response' => $data['g-recaptcha-response'] ?? '',
                'ip'          => $ip,
                'user_agent'  => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            ];
            
            $response = $this->api->registerTenant($payload);

            if ($response['status'] >= 500) {
                Logger::error('SPA API Error', ['status' => $response['status'], 'body' => $response['raw_body']]);
            }

            response()->json($response['body'], $response['status']);

        } catch (\Throwable $e) {
            Logger::error('Критична помилка в RegistrationController', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            response()->json(['message' => 'Server Error'], 500);
        }
    }
}