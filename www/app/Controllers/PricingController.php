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
        $plans = [
            [
                'id' => 2,
                'name' => 'Базовий (Basic)',
                'slug' => 'basic',
                'price_monthly' => 500,
                'price_yearly' => 5000,
                'currency' => 'UAH',
                'description' => 'Для невеликих парків, яким потрібен порядок.',
                'features' => [
                    'Управління автопарком (до 20 авто)',
                    'База водіїв (Driver Management)',
                    'Розумний графік (Smart Scheduling)',
                    'Базові звіти',
                    'Telegram бот для водіїв'
                ],
                'is_popular' => false,
                'button_text' => 'Обрати Базовий',
                'button_link' => '#'
            ],
            [
                'id' => 3,
                'name' => 'Професійний (Pro)',
                'slug' => 'pro',
                'price_monthly' => 1500,
                'price_yearly' => 15000,
                'currency' => 'UAH',
                'description' => 'Повний контроль, фінанси та автоматизація.',
                'features' => [
                    'Управління автопарком (до 100 авто)',
                    'Технічне обслуговування та Ремонти',
                    'Страхування та Документообіг',
                    'Розумний графік (розширений)',
                    'Фінанси та Транзакції',
                    'Інтеграція з Uklon API',
                    'Розширені звіти та Аналітика',
                    'Telegram бот (повний функціонал)',
                    'Аудит дій (Activity Logs)'
                ],
                'is_popular' => true,
                'button_text' => 'Обрати Pro',
                'button_link' => '#'
            ]
        ];

        $meta = [
            'title' => 'Тарифи та ціни',
            'description' => 'Оберіть найкращий тарифний план для вашого автопарку. Прозорі ціни, ніяких прихованих платежів.'
        ];

        echo $this->blade->make('pricing', ['plans' => $plans, 'meta' => $meta])->render();
    }
}