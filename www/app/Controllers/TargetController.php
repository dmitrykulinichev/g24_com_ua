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
            'title' => t('target.meta.title'),
            'description' => t('target.meta.description'),
        ];

        // Передаємо darkBg = true
        echo $this->blade->make('target', ['meta' => $meta, 'darkBg' => true])->render();
    }
}