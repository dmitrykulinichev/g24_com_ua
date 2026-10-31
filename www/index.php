<?php

// Illuminate/Blade не сумісні з PHP 8.4 deprecation notices — пригнічуємо до оновлення пакетів.
// Має стояти ДО autoload.php: самі попередження виникають ще під час парсингу
// vendor/illuminate/support/helpers.php при автозавантаженні, тож раніше (коли
// error_reporting() йшов після require) вони встигали "прослизнути" у вивід —
// зокрема псуючи JSON-відповіді API-ендпоінтів (/api/blog/sync, /api/sitemap/generate
// тощо), бо перед валідним JSON опинявся сторонній HTML-текст попереджень.
error_reporting(E_ALL & ~E_DEPRECATED);

require __DIR__ . '/vendor/autoload.php';

use Jenssegers\Blade\Blade;

// Завантаження .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Мова сторінки — з адреси, до маршрутизації (app/I18n): «/en/features» → мова en, маршрути бачать «/features»
require __DIR__ . '/app/I18n/helpers.php';
$localeResolution = (new App\I18n\LocaleResolver(App\I18n\Locale::config()))
    ->resolve($_SERVER['REQUEST_URI'] ?? '/', $_SERVER['HTTP_X_LOCALE'] ?? null);
if ($localeResolution['redirect'] !== null) {
    header('Location: ' . $localeResolution['redirect'], true, 301);
    exit;
}
App\I18n\Locale::set($localeResolution['locale'], $localeResolution['path']);
$_SERVER['REQUEST_URI'] = $localeResolution['uri'];

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
            'title' => t('errors.404.title'),
            'description' => t('errors.404.description'),
        ]
    ])->render();
});

// Маршрути
require __DIR__ . '/app/routes.php';

$app->run();