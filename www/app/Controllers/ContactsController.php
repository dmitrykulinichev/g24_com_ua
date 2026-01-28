<?php

namespace App\Controllers;

use App\Services\SpaApiService;
use App\Services\Logger;

class ContactsController
{
    protected $blade;
    protected $api;

    public function __construct()
    {
        $this->blade = new \Jenssegers\Blade\Blade(__DIR__ . '/../../views', __DIR__ . '/../../storage/cache');
        $this->api = new SpaApiService();
    }

    public function index()
    {
        // Логіка отримання конфігу (для ключа капчі)
        $cacheFile = __DIR__ . '/../../storage/cache/pricing_data.json';
        $cacheTtl = (int)($_ENV['API_CONFIG_CACHE_TTL'] ?? 86400); 
        
        $fullConfig = null;

        if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTtl)) {
            $content = @file_get_contents($cacheFile);
            if ($content) {
                $fullConfig = json_decode($content, true);
            }
        }

        if (!$fullConfig) {
            try {
                $response = $this->api->getLandingConfig();
                if ($response['status'] === 200 && !empty($response['body'])) {
                    $fullConfig = $response['body'];
                    if (!is_dir(dirname($cacheFile))) {
                        mkdir(dirname($cacheFile), 0755, true);
                    }
                    file_put_contents($cacheFile, json_encode($fullConfig));
                }
            } catch (\Throwable $e) {
                Logger::error('Failed to fetch config in Contacts', ['error' => $e->getMessage()]);
            }
        }

        // Фільтруємо конфіг для контактів
        $contactConfig = [];
        if ($fullConfig) {
            $contactConfig['recaptcha_site_key'] = $fullConfig['recaptcha_site_key'] ?? null;
            if (isset($fullConfig['forms']['lead'])) {
                $contactConfig['forms']['lead'] = $fullConfig['forms']['lead'];
            }
        }

        $meta = [
            'title' => 'Контакти',
            'description' => 'Зв\'яжіться з нами для консультації або технічної підтримки. Ми завжди на зв\'язку.'
        ];
        
        $recaptchaEnabled = filter_var($_ENV['RECAPTCHA_ENABLED'] ?? true, FILTER_VALIDATE_BOOLEAN);

        echo $this->blade->make('contacts', [
            'meta' => $meta, 
            'darkBg' => true,
            'apiConfig' => $contactConfig,
            'recaptchaEnabled' => $recaptchaEnabled
        ])->render();
    }
}