<?php

use App\Docs\DocsStore;
use App\I18n\Locale;
use App\I18n\LocaleConfig;
use App\I18n\LocalizedUrl;

/** Документація за мовами (app/Docs): переклад — файл поруч (`page.en.md`), меню — поле `i18n`. */

$config = fn () => LocaleConfig::load([
    'default' => 'uk',
    'locales' => ['uk' => ['hreflang' => 'uk'], 'en' => ['hreflang' => 'en'], 'ru' => ['hreflang' => 'ru']],
    'public' => ['uk', 'en', 'ru'],
    'localized_paths' => ['/'],
    'preview' => false,
]);

// «start» — українською й англійською, «old» — лише українською; розділ — /docs (посібник водія /driver — той самий код)
$store = null;
$setup = function () use ($config, &$store): string {
    $root = sys_get_temp_dir() . '/docs-test-' . bin2hex(random_bytes(4));
    mkdir("{$root}/driver", 0755, true);
    file_put_contents("{$root}/menu.json", json_encode([[
        'title' => 'Застосунок для водія',
        'i18n' => ['en' => ['title' => 'Driver app']],
        'items' => [
            ['title' => 'Як почати', 'slug' => 'start', 'docFile' => '_data/driver/start.md',
             'i18n' => ['en' => ['title' => 'Getting started', 'description' => 'Sign in']]],
            ['title' => 'Стара', 'slug' => 'old', 'docFile' => '_data/old.md'],
        ],
    ]]));
    file_put_contents("{$root}/driver/start.md", "# Як почати");
    file_put_contents("{$root}/driver/start.en.md", "# Getting started");
    file_put_contents("{$root}/old.md", "# Стара");
    Locale::useConfig($config());
    $store = new DocsStore($root, '/docs');
    LocalizedUrl::forgetContent();
    LocalizedUrl::registerContent('docs', [$store, 'localesOfPath']);

    return $root;
};
$teardown = function (string $root): void {
    exec('rm -rf ' . escapeshellarg($root));
    LocalizedUrl::forgetContent();
    Locale::useConfig(null);
};

return [
    'документація: сторінка є лише мовами, якими є її файл' => function () use ($setup, $teardown, &$store) {
        $root = $setup();
        try {
            same(['uk', 'en'], $store->localesOfPath('/docs/start'));
            same(['uk'], $store->localesOfPath('/docs/old'));
            same(null, $store->localesOfPath('/docs/missing'));
            same(['uk', 'en'], $store->localesOfPath('/docs'));
            same(null, $store->localesOfPath('/features'));
        } finally {
            $teardown($root);
        }
    },

    'документація: файл мовою сторінки, без перекладу — український' => function () use ($setup, $teardown, &$store) {
        $root = $setup();
        try {
            Locale::set('en', '/docs/start');
            same("{$root}/driver/start.en.md", $store->file('_data/driver/start.md', $store->contentLocale('start')));
            Locale::set('en', '/docs/old');
            same("{$root}/old.md", $store->file('_data/old.md', $store->contentLocale('old')));
            Locale::set('ru', '/docs/start');
            same("{$root}/driver/start.md", $store->file('_data/driver/start.md', $store->contentLocale('start')));
        } finally {
            $teardown($root);
        }
    },

    'документація: назви меню мовою сторінки, без перекладу — українські' => function () use ($setup, $teardown, &$store) {
        $root = $setup();
        try {
            $group = $store->rawMenu()[0];
            Locale::set('en', '/docs');
            same('Driver app', DocsStore::localize($group)['title']);
            same('Getting started', DocsStore::localize($group['items'][0])['title']);
            same('Стара', DocsStore::localize($group['items'][1])['title']);
            Locale::set('uk', '/docs');
            same('Як почати', DocsStore::localize($group['items'][0])['title']);
        } finally {
            $teardown($root);
        }
    },

    'розділ відповідає лише за свої адреси: /driver/start — не документація' => function () use ($setup, $teardown, &$store) {
        $root = $setup();
        try {
            $driver = new DocsStore($root, '/driver');
            same(['uk', 'en'], $driver->localesOfPath('/driver/start'));
            same(['uk', 'en'], $driver->localesOfPath('/driver'));
            same(null, $driver->localesOfPath('/docs/start'));
            same(null, $store->localesOfPath('/driver/start'));
            same('/driver/start', $driver->pagePath('start'));
        } finally {
            $teardown($root);
        }
    },

    'документація: перекладена сторінка — в альтернативах (hreflang) лише своїми мовами' => function () use ($setup, $teardown, &$store) {
        $root = $setup();
        $_ENV['APP_URL'] = 'https://g24.com.ua';
        try {
            same(['uk', 'en'], array_keys(LocalizedUrl::alternates('/docs/start')));
            same(['uk'], array_keys(LocalizedUrl::alternates('/docs/old')));
        } finally {
            $teardown($root);
        }
    },
];
