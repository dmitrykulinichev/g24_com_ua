<?php

use Leaf\App;

// Головна сторінка
$app->get('/', 'App\Controllers\LandingController@index');

// Документація
$app->get('/docs', 'App\Controllers\DocsController@index');
$app->get('/docs/{slug}', 'App\Controllers\DocsController@show');

// Блог
$app->get('/blog', 'App\Controllers\BlogController@index');
$app->get('/blog/{slug}', 'App\Controllers\BlogController@show');

// Маршрут sitemap.xml видалено, оскільки тепер це статичний файл,
// який генерується командою php leaf sitemap:generate