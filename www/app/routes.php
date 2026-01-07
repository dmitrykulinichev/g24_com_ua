<?php

use Leaf\App;

// Головна сторінка
$app->get('/', 'App\Controllers\LandingController@index');

// Можливості
$app->get('/features', 'App\Controllers\FeaturesController@index');

// Кому підійде
$app->get('/target', 'App\Controllers\TargetController@index');

// Тарифи
$app->get('/pricing', 'App\Controllers\PricingController@index');

// Контакти
$app->get('/contacts', 'App\Controllers\ContactsController@index');

// Документація
$app->get('/docs', 'App\Controllers\DocsController@index');
$app->get('/docs/{slug}', 'App\Controllers\DocsController@show');
$app->get('/api/docs/search', 'App\Controllers\SearchController@search');

// Блог
$app->get('/blog', 'App\Controllers\BlogController@index');
$app->get('/blog/{slug}', 'App\Controllers\BlogController@show');
$app->get('/api/blog/search', 'App\Controllers\SearchController@searchBlog'); // Новий маршрут

// API для статичних сторінок (модалки)
$app->get('/api/page/{slug}', 'App\Controllers\PageController@apiShow');

// Обробка заявки (Lead)
$app->post('/api/lead', 'App\Controllers\LeadController@submit');