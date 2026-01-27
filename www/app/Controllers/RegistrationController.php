<?php

namespace App\Controllers;

use App\Services\Logger;
use App\Services\SpaApiService;
use App\Services\LeadStorageService;
use App\Services\TelegramService;

class RegistrationController
{
    protected $api;
    protected $storage;
    protected $telegram;

    public function __construct()
    {
        $this->api = new SpaApiService();
        $this->storage = new LeadStorageService();
        $this->telegram = new TelegramService();
    }

    public function getConfig()
    {
        $cacheFile = __DIR__ . '/../../storage/cache/pricing_data.json';
        $cacheTtl = (int)($_ENV['API_CONFIG_CACHE_TTL'] ?? 86400);

        try {
            if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTtl)) {
                $content = @file_get_contents($cacheFile);
                if ($content) {
                    header('Content-Type: application/json');
                    echo $content;
                    return;
                }
            }

            $response = $this->api->getLandingConfig();
            
            if ($response['status'] === 200 && !empty($response['body'])) {
                if (!is_dir(dirname($cacheFile))) {
                    mkdir(dirname($cacheFile), 0755, true);
                }
                file_put_contents($cacheFile, json_encode($response['body']));
            }

            response()->json($response['body'], $response['status']);

        } catch (\Throwable $e) {
            Logger::error('Error fetching config', ['message' => $e->getMessage()]);
            
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
            
            // 1. Зберігаємо локально
            $this->storage->save($payload, 'register');

            // 2. Відправляємо в Telegram
            $this->sendToTelegram($payload);

            // 3. Відправляємо на SPA API
            $response = $this->api->registerTenant($payload);

            // Обробка помилок API
            if ($response['status'] >= 400) {
                Logger::error('SPA API Error (Register)', ['status' => $response['status'], 'body' => $response['raw_body']]);
                
                $this->sendApiErrorToTelegram($response, $payload['owner_email']);

                if ($response['status'] >= 500) {
                    response()->json(['status' => 'success', 'message' => 'Парк успішно зареєстровано! Перевірте пошту.'], 200);
                    return;
                }
                
                response()->json($response['body'], $response['status']);
                return;
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

    private function sendToTelegram($data)
    {
        $msg = "🚀 <b>Нова реєстрація парку!</b>\n\n";
        $msg .= "🏢 Парк: " . ($data['park_name'] ?: '-') . "\n";
        $msg .= "👤 Власник: " . ($data['owner_name'] ?: '-') . "\n";
        $msg .= "📧 Email: " . ($data['owner_email'] ?: '-') . "\n";
        $msg .= "📱 Телефон: " . ($data['phone'] ?: '-') . "\n";
        $msg .= "💳 План: " . ($data['plan'] ?: '-') . "\n";
        $msg .= "\n🌍 IP: " . $data['ip'];

        $this->telegram->sendMessage($msg);
    }

    private function sendApiErrorToTelegram($response, $email)
    {
        $msg = "⚠️ <b>Помилка SPA API (Register)!</b>\n";
        $msg .= "Email: {$email}\n\n";
        $msg .= "Status: <b>{$response['status']}</b>\n";
        
        $body = $response['raw_body'];
        if (strlen($body) > 500) {
            $body = substr($body, 0, 500) . '...';
        }
        
        $msg .= "Response: <pre>{$body}</pre>";

        $this->telegram->sendMessage($msg);
    }
}