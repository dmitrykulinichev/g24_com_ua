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

// Маршрути
require __DIR__ . '/app/routes.php';

$app->run();