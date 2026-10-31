<?php

namespace App\Controllers;

use App\Services\SpaApiService;
use App\Services\Logger;

class PricingController
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
        // 1. Дефолтні значення для відображення (Fallback)
        $pricingModel = [
            'monthly' => [
                'base' => 1000,
                'car' => 200,
            ],
            'yearly' => [
                'base' => 1000, 
                'car' => 100,
                'old_car' => 200
            ],
            // Підписи (валюта, умова активності, перелік «Все включено») — у словнику: pricing.*
            'features' => t_list('pricing.features'),
        ];

        // 2. Логіка кешування
        $cacheFile = __DIR__ . '/../../storage/cache/pricing_data.json';
        // Використовуємо нову змінну. Дефолт 24 години.
        $cacheTtl = (int)($_ENV['API_CONFIG_CACHE_TTL'] ?? 86400); 
        
        $fullConfig = null;

        // Спроба читання з кешу
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTtl)) {
            $content = @file_get_contents($cacheFile);
            if ($content) {
                $fullConfig = json_decode($content, true);
            }
        }

        // Якщо кешу немає або він застарів — йдемо в API
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
                Logger::error('Failed to fetch pricing from API', ['error' => $e->getMessage()]);
            }
        }

        // 3. Оновлення моделі цін даними з конфігу
        if ($fullConfig && !empty($fullConfig['plans'])) {
            $plans = $fullConfig['plans'];
            $monthlyPlan = null;
            $yearlyPlan = null;

            foreach ($plans as $plan) {
                $slug = $plan['slug'] ?? '';
                if (strpos($slug, 'monthly') !== false) {
                    $monthlyPlan = $plan;
                } elseif (strpos($slug, 'yearly') !== false) {
                    $yearlyPlan = $plan;
                }
            }

            if ($monthlyPlan) {
                $pricingModel['monthly']['base'] = (int)($monthlyPlan['price_monthly'] ?? 1000);
                $pricingModel['monthly']['car'] = (int)($monthlyPlan['price_per_car'] ?? 200);
                $pricingModel['yearly']['old_car'] = $pricingModel['monthly']['car'];
            }

            if ($yearlyPlan) {
                $yBase = (float)($yearlyPlan['price_monthly'] ?? 0);
                if ($yBase <= 0) {
                    $yBase = ((float)($yearlyPlan['price_yearly'] ?? 12000)) / 12;
                }
                
                $pricingModel['yearly']['base'] = (int)$yBase;
                $pricingModel['yearly']['car'] = (int)($yearlyPlan['price_per_car'] ?? 100);
            }
        }

        $meta = [
            'title' => t('pricing.meta.title'),
            'description' => t('pricing.meta.description'),
        ];

        echo $this->blade->make('pricing', [
            'model' => $pricingModel, 
            'meta' => $meta,
            'darkBg' => true,
            'apiConfig' => $fullConfig
        ])->render();
    }
}