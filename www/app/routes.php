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

// Документація v1 (збережено, але не активна)
// $app->get('/docs', 'App\Controllers\DocsController@index');
// $app->get('/docs/{slug}', 'App\Controllers\DocsController@show');
// $app->get('/api/docs/search', 'App\Controllers\SearchController@search');

// Документація v2 → основний шлях /docs
$app->get('/docs', 'App\Controllers\Docs2Controller@index');
$app->get('/docs/{slug}', 'App\Controllers\Docs2Controller@show');
$app->get('/api/docs/search', 'App\Controllers\SearchController@searchDocs2');

// Документація v2 → старий шлях (редірект на /docs)
$app->get('/docs2', function() { response()->redirect('/docs'); });
$app->get('/docs2/{slug}', function($slug) { response()->redirect('/docs/' . $slug); });

// Блог
$app->get('/blog', 'App\Controllers\BlogController@index');
$app->get('/blog/{slug}', 'App\Controllers\BlogController@show');
$app->get('/api/blog/search', 'App\Controllers\SearchController@searchBlog');

// Sitemap
$app->get('/sitemap.xml', 'App\Controllers\SitemapController@index');
$app->get('/api/sitemap/generate', 'App\Controllers\SitemapController@generate');

// API для статичних сторінок (модалки)
$app->get('/api/page/{slug}', 'App\Controllers\PageController@apiShow');

// Обробка заявки (Lead)
$app->post('/api/lead', 'App\Controllers\LeadController@submit');

// Реєстрація нового парку (Proxy to SPA API)
$app->get('/api/config', 'App\Controllers\RegistrationController@getConfig');
$app->post('/api/register', 'App\Controllers\RegistrationController@register');
$app->post('/api/resend', 'App\Controllers\RegistrationController@resend');
$app->post('/api/check-status', 'App\Controllers\RegistrationController@checkStatus');
$app->post('/api/abandoned', 'App\Controllers\RegistrationController@abandoned');