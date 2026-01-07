<?php

use Leaf\App;

// Головна сторінка
$app->get('/', 'App\Controllers\LandingController@index');

// Тарифи
$app->get('/pricing', 'App\Controllers\PricingController@index');

// Документація
$app->get('/docs', 'App\Controllers\DocsController@index');
$app->get('/docs/{slug}', 'App\Controllers\DocsController@show');

// Блог
$app->get('/blog', 'App\Controllers\BlogController@index');
$app->get('/blog/{slug}', 'App\Controllers\BlogController@show');

// Обробка форми
$app->post('/contact', 'App\Controllers\ContactController@submit');