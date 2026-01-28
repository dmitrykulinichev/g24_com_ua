<?php

require __DIR__ . '/vendor/autoload.php';

use Jenssegers\Blade\Blade;

// Завантаження .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Ініціалізація Leaf
$app = new Leaf\App;

// Налаштування Blade
$views = __DIR__ . '/views';
$cache = __DIR__ . '/storage/cache';
$blade = new Blade($views, $cache);

// Налаштування 404
$app->set404(function () use ($blade) {
    // Встановлюємо заголовок 404
    http_response_code(404);
    
    // Рендеримо сторінку
    echo $blade->make('errors.404', [
        'meta' => [
            'title' => '404 - Сторінку не знайдено',
            'description' => 'Сторінка, яку ви шукаєте, не існує.'
        ]
    ])->render();
});

// Маршрути
require __DIR__ . '/app/routes.php';

$app->run();