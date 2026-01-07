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
            'description' => 'Рішення для власників автопарків, інвесторів та керуючих. Дізнайтеся, як ми вирішуємо ваші специфічні проблеми.'
        ];

        echo $this->blade->make('target', ['meta' => $meta])->render();
    }
}