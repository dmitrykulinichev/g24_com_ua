<?php

use App\I18n\Locale;
use App\I18n\LocaleConfig;
use App\I18n\LocaleResolver;
use App\I18n\LocalizedUrl;
use App\I18n\Translator;

/** Мовний модуль лендінгу (app/I18n). */

$config = fn (array $public = ['uk', 'en', 'ru'], bool $preview = false) => LocaleConfig::load([
    'default' => 'uk',
    'locales' => [
        'uk' => ['name' => 'Українська', 'short' => 'UA', 'hreflang' => 'uk', 'og' => 'uk_UA'],
        'en' => ['name' => 'English', 'short' => 'EN', 'hreflang' => 'en', 'og' => 'en_US'],
        'ru' => ['name' => 'Русский', 'short' => 'RU', 'hreflang' => 'ru', 'og' => 'ru_RU'],
    ],
    'public' => $public,
    'localized_paths' => ['/', '/pricing', '/docs/*'],
    'preview' => $preview,
]);

$_ENV['APP_URL'] = 'https://g24.com.ua';

return [
    'основна мова — без префікса' => function () use ($config) {
        $r = (new LocaleResolver($config()))->resolve('/pricing?x=1');
        same('uk', $r['locale']);
        same('/pricing?x=1', $r['uri']);
        same(null, $r['redirect']);
    },

    'префікс мови знімається перед маршрутизацією' => function () use ($config) {
        $r = (new LocaleResolver($config()))->resolve('/en/pricing?x=1');
        same('en', $r['locale']);
        same('/pricing', $r['path']);
        same('/pricing?x=1', $r['uri']);

        same('/', (new LocaleResolver($config()))->resolve('/ru')['uri'], 'корінь мови');
    },

    '/uk/… — 301 на адресу без префікса' => function () use ($config) {
        same('/pricing?a=b', (new LocaleResolver($config()))->resolve('/uk/pricing?a=b')['redirect']);
    },

    'закрита мова — префікс лишається (сторінка дасть 404), з preview — відкрита' => function () use ($config) {
        $closed = (new LocaleResolver($config(['uk'])))->resolve('/en/pricing');
        same('uk', $closed['locale']);
        same('/en/pricing', $closed['uri']);

        same('en', (new LocaleResolver($config(['uk'], preview: true)))->resolve('/en/pricing')['locale']);
    },

    'адреса, що лише починається як мова, — не мова' => function () use ($config) {
        same('/enterprise', (new LocaleResolver($config()))->resolve('/enterprise')['uri']);
    },

    'API — мова з X-Locale, лише відкрита' => function () use ($config) {
        same('en', (new LocaleResolver($config()))->resolve('/api/lead', 'en')['locale']);
        same('uk', (new LocaleResolver($config(['uk'])))->resolve('/api/lead', 'en')['locale']);
        same('/api/lead', (new LocaleResolver($config()))->resolve('/api/lead', 'en')['uri']);
    },

    'переклад: поточна мова, порожнє чи відсутнє — українська, нема й там — ключ' => function () use ($config) {
        Locale::useConfig($config());
        Translator::useDirectory(__DIR__ . '/fixtures/lang');
        Locale::set('en', '/');

        same('Pricing', t('nav.pricing'));
        same('Лише українською', t('nav.only_uk'), 'порожній переклад');
        same('nav.unknown', t('nav.unknown'));
        same('Hi, Олено!', t('hello', ['name' => 'Олено']));
        same(['a', 'b'], t_list('list'), 'список з основної мови');

        Translator::useDirectory(null);
        Locale::useConfig(null);
    },

    'адреси мовою: lurl, canonical, альтернативи' => function () use ($config) {
        Locale::useConfig($config());
        Locale::set('en', '/pricing');

        same('/en/pricing', lurl('/pricing'));
        same('/en', lurl('/'));
        same('/ru/pricing', LocalizedUrl::to('/pricing', 'ru'));
        same('/pricing', LocalizedUrl::to('/pricing', 'uk'));

        same('https://g24.com.ua/en/pricing', LocalizedUrl::canonical());
        same([
            'uk' => 'https://g24.com.ua/pricing',
            'en' => 'https://g24.com.ua/en/pricing',
            'ru' => 'https://g24.com.ua/ru/pricing',
        ], LocalizedUrl::alternates());

        Locale::useConfig(null);
    },

    'неперекладена сторінка: canonical — українська, без альтернатив' => function () use ($config) {
        Locale::useConfig($config());
        Locale::set('en', '/blog/some-post');

        same('https://g24.com.ua/blog/some-post', LocalizedUrl::canonical());
        same(['uk' => 'https://g24.com.ua/blog/some-post'], LocalizedUrl::alternates());

        Locale::set('en', '/docs/start');
        same('https://g24.com.ua/en/docs/start', LocalizedUrl::canonical(), '«/docs/*» — перекладено');

        Locale::useConfig(null);
    },

    'закриті мови не потрапляють в альтернативи' => function () use ($config) {
        Locale::useConfig($config(['uk', 'en']));
        Locale::set('uk', '/pricing');

        same(['uk', 'en'], array_keys(LocalizedUrl::alternates()));

        Locale::useConfig(null);
    },

    'словники: у перекладах немає ключів, яких немає в українському (опечатка в ключі = текст не з’явиться)' => function () {
        $flatten = function (array $a, string $prefix = '') use (&$flatten): array {
            $out = [];
            foreach ($a as $k => $v) {
                $key = $prefix === '' ? (string) $k : "{$prefix}.{$k}";
                if (is_array($v) && !array_is_list($v)) {
                    $out += $flatten($v, $key);
                } else {
                    $out[$key] = $v;
                }
            }

            return $out;
        };
        $dir = __DIR__ . '/../lang';
        $uk = $flatten(require "{$dir}/uk.php");
        foreach (['en', 'ru'] as $locale) {
            $extra = array_diff_key($flatten(require "{$dir}/{$locale}.php"), $uk);
            same([], array_keys($extra), "зайві ключі в {$locale}.php");
        }
    },

    'перекладена сторінка — усі її тексти є в кожній мові (немає — сторінка вийде наполовину українською)' => function () {
        // Адреса з localized_paths (config/i18n.php) => розділи словника, з яких вона бере тексти
        $common = ['meta', 'nav', 'footer', 'cookies', 'language', 'order_form'];
        // Підписи скрінів (screens) — у ScreensTest: потрібні кожною мовою, якою скрін знято
        $sections = [
            '/' => ['landing', ...$common],
            '/features' => ['features', ...$common],
            '/target' => ['target', ...$common],
            '/pricing' => ['pricing', ...$common],
            '/contacts' => ['contacts', ...$common],
        ];
        $flatten = function (array $a, string $prefix = '') use (&$flatten): array {
            $out = [];
            foreach ($a as $k => $v) {
                $key = $prefix === '' ? (string) $k : "{$prefix}.{$k}";
                $out += is_array($v) ? $flatten($v, $key) : [$key => $v];
            }

            return $out;
        };
        $dir = __DIR__ . '/../lang';
        $config = require __DIR__ . '/../config/i18n.php';
        foreach ($config['localized_paths'] as $path) {
            check(isset($sections[$path]), "для {$path} не вказано розділи словника в цьому тесті");
            foreach ($sections[$path] as $section) {
                $uk = $flatten((require "{$dir}/uk.php")[$section] ?? []);
                foreach (['en', 'ru'] as $locale) {
                    $tr = $flatten((require "{$dir}/{$locale}.php")[$section] ?? []);
                    $missing = array_keys(array_filter($uk, fn ($v, $k) => trim((string) $v) !== '' && trim((string) ($tr[$k] ?? '')) === '', ARRAY_FILTER_USE_BOTH));
                    same([], $missing, "{$path}: не перекладено в {$locale}.php ({$section})");
                }
            }
        }
    },
];
