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
        // Дані для нової моделі ціноутворення
        $pricingModel = [
            'base_price' => 1000,
            'car_price' => 200,
            'currency' => 'грн',
            'active_condition' => '5 змін',
            'features' => [
                'Повна інтеграція з Uklon Fleet API',
                'Telegram-бот для водіїв та менеджерів',
                'Розумний графік змін (Drag & Drop)',
                'Розрахунок виплат водіям (Зарплата)',
                'Облік ремонтів та склад запчастин',
                'Мобільний додаток (PWA) для власника',
                'Технічна підтримка 24/7',
                'Щоденні бекапи бази даних'
            ]
        ];

        $meta = [
            'title' => 'Тарифи',
            'description' => 'Чесна ціна без прихованих платежів. Оплата по факту використання. Спробуйте безкоштовно.'
        ];

        // Передаємо darkBg = true
        echo $this->blade->make('pricing', [
            'model' => $pricingModel, 
            'meta' => $meta,
            'darkBg' => true
        ])->render();
    }
}