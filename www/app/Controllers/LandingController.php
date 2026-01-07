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
            'title' => 'CRM для автопарків',
            'description' => 'Автоматизуйте виплати, контроль палива та роботу з водіями. Підключайтеся зараз і переходьте на новий рівень ефективності.',
            'image' => '/assets/img/landing/og-image.jpg'
        ];

        echo $this->blade->make('landing', ['meta' => $meta])->render();
    }
}