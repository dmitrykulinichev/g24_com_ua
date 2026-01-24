<?php

namespace App\Controllers;

class TargetController
{
    protected $blade;

    public function __construct()
    {
        $this->blade = new \Jenssegers\Blade\Blade(__DIR__ . '/../../views', __DIR__ . '/../../storage/cache');
    }

    public function index()
    {
        $meta = [
            'title' => 'Кому підійде Garage24',
            'description' => 'Рішення для власників, партнерів та інвесторів автопарків. Дізнайтеся, як ми вирішуємо ваші проблеми.'
        ];

        // Передаємо darkBg = true
        echo $this->blade->make('target', ['meta' => $meta, 'darkBg' => true])->render();
    }
}