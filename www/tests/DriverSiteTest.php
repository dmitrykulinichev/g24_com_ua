<?php

use App\I18n\Locale;
use App\I18n\LocaleConfig;
use App\DriverSite\DriverSite;

/** САЙТ ВОДІЯ — посібник водія на своєму домені (app/DriverSite). */

$setup = function () {
    Locale::useConfig(LocaleConfig::load([
        'default' => 'uk',
        'locales' => [
            'uk' => ['name' => 'Українська', 'short' => 'UA', 'hreflang' => 'uk', 'og' => 'uk_UA'],
            'en' => ['name' => 'English', 'short' => 'EN', 'hreflang' => 'en', 'og' => 'en_US'],
            'ru' => ['name' => 'Русский', 'short' => 'RU', 'hreflang' => 'ru', 'og' => 'ru_RU'],
        ],
        'public' => ['uk', 'en', 'ru'],
        'localized_paths' => ['/'],
        'preview' => false,
    ]));
    $_ENV['APP_URL'] = 'https://g24.com.ua';
    unset($_ENV['DRIVER_HOST']);
};

return [
    'домен водія — за заголовком Host (порт і регістр не важать)' => function () use ($setup) {
        $setup();
        check(DriverSite::active('driver.g24.com.ua'), 'driver.g24.com.ua');
        check(DriverSite::active('Driver.G24.com.ua:443'), 'з портом і регістром');
        check(!DriverSite::active('g24.com.ua'), 'основний домен');
        check(!DriverSite::active('localhost:8001'), 'локально');
    },

    'адреси домену водія стають адресами розділу' => function () use ($setup) {
        $setup();
        same('/driver', DriverSite::internalUri('/'));
        same('/driver/start', DriverSite::internalUri('/start'));
        same('/en/driver/start?x=1', DriverSite::internalUri('/en/start?x=1'));
        same('/en/driver', DriverSite::internalUri('/en'));
        same('/driver/start', DriverSite::internalUri('/driver/start'), 'стара адреса з /driver на домені водія');
        same('/driver/features', DriverSite::internalUri('/features'), 'сторінка SaaS — 404 посібника');
        same('/assets/img/logo.jpg', DriverSite::internalUri('/assets/img/logo.jpg'), 'файли — напряму');
        same('/api/driver/search?q=a', DriverSite::internalUri('/api/driver/search?q=a'), 'пошук — напряму');
    },

    'посилання на домені водія — без /driver' => function () use ($setup) {
        $setup();
        same('/', DriverSite::publicPath('/driver'));
        same('/shift', DriverSite::publicPath('/driver/shift'));
        same('/features', DriverSite::publicPath('/features'));
        same(
            '<a href="/shift">a</a> <a href="/en/shift">b</a> <a href="/">c</a> <a href="/ru">d</a> <a href="/docs/x">e</a> <a href="https://g24.com.ua/driver/x">f</a>',
            DriverSite::rewriteLinks('<a href="/driver/shift">a</a> <a href="/en/driver/shift">b</a> <a href="/driver">c</a> <a href="/ru/driver">d</a> <a href="/docs/x">e</a> <a href="https://g24.com.ua/driver/x">f</a>'),
        );
    },

    'посилання з сайту водія: посібник — відносні, решта сайту — на основному домені' => function () use ($setup) {
        $setup();
        same('/', DriverSite::link('/driver', ''));
        same('/en', DriverSite::link('/driver', '/en'));
        same('/shift', DriverSite::link('/driver/shift', ''));
        same('/ru/shift', DriverSite::link('/driver/shift', '/ru'));
        same('https://g24.com.ua/privacy', DriverSite::link('/privacy', ''));
        same('https://g24.com.ua/en/privacy', DriverSite::link('/privacy', '/en'));
        same('https://g24.com.ua/en', DriverSite::link('/', '/en'));
    },

    'мапи сайту окремі: сторінки посібника — лише в мапі домену водія і його адресами' => function () use ($setup) {
        $setup();
        check(DriverSite::isSectionUrl('https://g24.com.ua/en/driver/shift'), 'сторінка посібника');
        check(!DriverSite::isSectionUrl('https://g24.com.ua/docs/start'), 'документація парку');
        check(!DriverSite::isSectionUrl('https://g24.com.ua/blog/driver-issues'), 'стаття блогу');
        same('/sitemap-driver.xml', DriverSite::internalUri('/sitemap-driver.xml'), 'мапа — напряму, не в посібник');
        check(DriverSite::isSectionUrl('https://driver.g24.com.ua/start'), 'мапу зібрано на домені водія');
        same('https://driver.g24.com.ua/start', DriverSite::sitemapUrl('https://driver.g24.com.ua/start'));
        same('https://driver.g24.com.ua/', DriverSite::sitemapUrl('https://g24.com.ua/driver'));
        same('https://driver.g24.com.ua/en/shift', DriverSite::sitemapUrl('https://g24.com.ua/en/driver/shift'));
        same('https://g24.com.ua/docs/start', DriverSite::sitemapUrl('https://g24.com.ua/docs/start'));
        same('https://g24.com.ua/blog/driver-issues', DriverSite::sitemapUrl('https://g24.com.ua/blog/driver-issues'));
    },

    'основний домен: /driver… переїхав на домен водія (301)' => function () use ($setup) {
        $setup();
        same('https://driver.g24.com.ua/', DriverSite::redirectFromMain('/driver', 'g24.com.ua'));
        same('https://driver.g24.com.ua/shift', DriverSite::redirectFromMain('/driver/shift', 'g24.com.ua'));
        same('https://driver.g24.com.ua/en/shift?x=1', DriverSite::redirectFromMain('/en/driver/shift?x=1', 'www.g24.com.ua'));
        same('https://driver.g24.com.ua/ru', DriverSite::redirectFromMain('/ru/driver', 'g24.com.ua'));
        same(null, DriverSite::redirectFromMain('/docs/driver-start', 'g24.com.ua'), 'документація парку не чіпається');
        same(null, DriverSite::redirectFromMain('/drivers', 'g24.com.ua'), 'схожий початок адреси');
        same(null, DriverSite::redirectFromMain('/driver/shift', 'localhost:8001'), 'локально — без переадресації');
    },
];
