<?php

namespace App\Controllers;

class PricingController
{
    protected $blade;

    public function __construct()
    {
        $this->blade = new \Jenssegers\Blade\Blade(__DIR__ . '/../../views', __DIR__ . '/../../storage/cache');
    }

    public function index()
    {
        // Мок-дані (пізніше замінимо на API запит)
        $plans = [
            [
                'id' => 1,
                'name' => 'Демо (Trial)',
                'slug' => 'demo',
                'price_monthly' => 0,
                'price_yearly' => 0,
                'currency' => 'UAH',
                'features' => [
                    'max_drivers' => 5,
                    'max_vehicles' => 5,
                    'support' => 'Email',
                    'history' => '7 днів'
                ],
                'is_popular' => false,
                'button_text' => 'Спробувати безкоштовно',
                'button_link' => 'https://app.g24.com.ua/register?plan=demo'
            ],
            [
                'id' => 2,
                'name' => 'Базовий (Basic)',
                'slug' => 'basic',
                'price_monthly' => 500,
                'price_yearly' => 5000, // Економія 1000 грн
                'currency' => 'UAH',
                'features' => [
                    'max_drivers' => 30,
                    'max_vehicles' => 20,
                    'support' => 'Email + Chat',
                    'history' => '30 днів'
                ],
                'is_popular' => true,
                'button_text' => 'Обрати Базовий',
                'button_link' => 'https://app.g24.com.ua/register?plan=basic'
            ],
            [
                'id' => 3,
                'name' => 'Професійний (Pro)',
                'slug' => 'pro',
                'price_monthly' => 1500,
                'price_yearly' => 15000, // Економія 3000 грн
                'currency' => 'UAH',
                'features' => [
                    'max_drivers' => 150,
                    'max_vehicles' => 100,
                    'support' => '24/7 Пріоритетна',
                    'history' => 'Необмежено'
                ],
                'is_popular' => false,
                'button_text' => 'Обрати Pro',
                'button_link' => 'https://app.g24.com.ua/register?plan=pro'
            ]
        ];

        $meta = [
            'title' => 'Тарифи та ціни',
            'description' => 'Оберіть найкращий тарифний план для вашого автопарку. Прозорі ціни, ніяких прихованих платежів.'
        ];

        echo $this->blade->make('pricing', ['plans' => $plans, 'meta' => $meta])->render();
    }
}