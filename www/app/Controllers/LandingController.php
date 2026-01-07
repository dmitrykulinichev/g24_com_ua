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
        // Тут можна задати SEO налаштування для головної сторінки
        $meta = [
            'title' => 'CRM для автопарків', // Буде: CRM для автопарків | G24
            'description' => 'Автоматизуйте виплати, контроль палива та роботу з водіями. Підключайтеся зараз і переходьте на новий рівень ефективності.',
            'image' => '/assets/img/landing/og-image.jpg'
        ];

        // Якщо захочете вивести останні новини на головній:
        // $latestPosts = array_slice(\App\Services\MarkdownService::getList(__DIR__ . '/../../content/blog'), 0, 3);
        // echo $this->blade->make('landing', ['meta' => $meta, 'posts' => $latestPosts])->render();

        echo $this->blade->make('landing', ['meta' => $meta])->render();
    }
}