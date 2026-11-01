<?php

use App\I18n\Locale;
use App\I18n\LocaleConfig;
use App\Screens\ScreenRegistry;

/** Скріни лендінгу (app/Screens, content/screens.json): реєстр, файли, підписи, шаблони, мапа сайту. */

$root = dirname(__DIR__);
$config = fn () => LocaleConfig::load([
    'default' => 'uk',
    'locales' => [
        'uk' => ['hreflang' => 'uk'],
        'en' => ['hreflang' => 'en'],
        'ru' => ['hreflang' => 'ru'],
    ],
    'public' => ['uk', 'en', 'ru'],
    'localized_paths' => ['/'],
    'preview' => false,
]);

// id з усіх @include('partials.screen', ['id' => '…']) у шаблонах: [id => [файли]]
$usedIds = function () use ($root): array {
    $used = [];
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/views', FilesystemIterator::SKIP_DOTS));
    foreach ($files as $file) {
        preg_match_all("/@include\\(\\s*'partials\\.screen'\\s*,\\s*\\[\\s*'id'\\s*=>\\s*'([^']+)'/", file_get_contents($file), $m);
        foreach ($m[1] as $id) {
            $used[$id][] = substr($file, strlen($root) + 1);
        }
    }

    return $used;
};

$dictionary = fn (string $locale) => require $root . "/lang/{$locale}.php";

return [
    'кожен скрін у шаблонах є в реєстрі' => function () use ($usedIds) {
        $registry = ScreenRegistry::load();
        foreach ($usedIds() as $id => $files) {
            check($registry->has($id), "«{$id}» (" . implode(', ', $files) . ') немає в content/screens.json');
        }
    },

    'кожен скрін з реєстру стоїть хоча б в одному шаблоні' => function () use ($usedIds) {
        $missing = array_diff(ScreenRegistry::load()->ids(), array_keys($usedIds()));
        check(!$missing, 'у шаблонах не використано: ' . implode(', ', $missing));
    },

    'кожен скрін знято українською (на неї падають інші мови)' => function () {
        $registry = ScreenRegistry::load();
        foreach ($registry->ids() as $id) {
            check($registry->exists($id, 'uk'), 'немає ' . ScreenRegistry::path($id, 'uk'));
        }
    },

    'у теках мов лише скріни з реєстру, з тією самою назвою' => function () use ($root) {
        $ids = ScreenRegistry::load()->ids();
        foreach (glob($root . '/assets/img/screens/*/*') as $file) {
            $id = basename($file, '.webp');
            check(str_ends_with($file, '.webp') && in_array($id, $ids, true), 'зайвий файл ' . substr($file, strlen($root)));
        }
    },

    'підпис (alt) кожного скріна є в кожній мові' => function () use ($dictionary) {
        foreach (Locale::config()->supported() as $locale) {
            $alts = $dictionary($locale)['screens'] ?? [];
            foreach (ScreenRegistry::load()->ids() as $id) {
                check(trim($alts[$id] ?? '') !== '', "немає screens.{$id} у lang/{$locale}.php");
            }
        }
    },

    'у шаблонах сторінок немає прямих шляхів до скрінів' => function () use ($root) {
        foreach (glob($root . '/views/*.blade.php') as $file) {
            check(
                !preg_match('#/assets/img/(screens|landing|docs)/#', file_get_contents($file)),
                basename($file) . ': картинку — через @include(\'partials.screen\'), не прямим шляхом'
            );
        }
    },

    'картинка мовою сторінки; не знято — українська; розміри з файлу, версія в адресі' => function () use ($config, $root) {
        Locale::useConfig($config());
        $registry = ScreenRegistry::load($root . '/tests/fixtures/screens/screens.json', $root . '/tests/fixtures/screens');

        $en = $registry->image('both', 'en');
        same('/assets/img/screens/en/both.webp', $en['path']);
        same([4, 2], [$en['width'], $en['height']]);
        check(str_starts_with($en['src'], '/assets/img/screens/en/both.webp?v='), 'версія в src: ' . $en['src']);

        same('uk', $registry->image('only-uk', 'en')['locale'], 'не знято англійською');
        same(['uk', 'en'], $registry->locales('both'));
        same(['both', 'only-uk'], $registry->forPage('/'));
        same(['only-uk'], $registry->forPage('/pricing'));

        Locale::useConfig(null);
    },

    'мапа сайту: скріни сторінки кожною мовою — файл, який вона показує' => function () use ($config, $root) {
        Locale::useConfig($config());
        ScreenRegistry::use(ScreenRegistry::load($root . '/tests/fixtures/screens/screens.json', $root . '/tests/fixtures/screens'));
        $_ENV['APP_URL'] = 'https://g24.com.ua';

        $controller = (new ReflectionClass(App\Controllers\SitemapController::class))->newInstanceWithoutConstructor();
        $xml = (fn () => $this->buildXml())->call($controller);

        $block = function (string $loc) use ($xml): string {
            preg_match('#<url>\s*<loc>' . preg_quote($loc, '#') . '</loc>.*?</url>#s', $xml, $m);
            check(isset($m[0]), "у мапі немає {$loc}");

            return $m[0];
        };
        $images = function (string $loc) use ($block): array {
            preg_match_all('#<image:loc>([^<]+)</image:loc>#', $block($loc), $m);

            return $m[1];
        };

        same(['https://g24.com.ua/assets/img/screens/en/both.webp', 'https://g24.com.ua/assets/img/screens/uk/only-uk.webp'], $images('https://g24.com.ua/en'));
        same(['https://g24.com.ua/assets/img/screens/uk/both.webp', 'https://g24.com.ua/assets/img/screens/uk/only-uk.webp'], $images('https://g24.com.ua/'));
        check(str_contains($block('https://g24.com.ua/en'), 'hreflang="ru"'), 'перекладена сторінка — з hreflang');
        same(['https://g24.com.ua/assets/img/screens/uk/only-uk.webp'], $images('https://g24.com.ua/pricing'), 'неперекладена — лише українська адреса');

        ScreenRegistry::use(null);
        Locale::useConfig(null);
    },
];
