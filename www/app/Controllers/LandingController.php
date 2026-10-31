<?php

namespace App\Controllers;

class LandingController
{
    protected $blade;

    public function __construct()
    {
        $this->blade = new \Jenssegers\Blade\Blade(__DIR__ . '/../../views', __DIR__ . '/../../storage/cache');
    }

    public function index()
    {
        $meta = [
            'title' => t('landing.meta.title'),
            'description' => t('landing.meta.description'),
            'image' => '/assets/img/landing/og-image.jpg'
        ];

        // Передаємо darkBg = true, бо на головній є картинка в Hero
        echo $this->blade->make('landing', ['meta' => $meta, 'darkBg' => true])->render();
    }
}