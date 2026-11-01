<?php

use App\Blog\BlogStore;
use App\Blog\BlogSync;
use App\I18n\Locale;
use App\I18n\LocaleConfig;
use App\I18n\LocalizedUrl;

/** Блог за мовами (app/Blog): синхронізація кладе кожну мову окремо, сторінки й мапа сайту — лише наявні мови. */

$config = fn () => LocaleConfig::load([
    'default' => 'uk',
    'locales' => ['uk' => ['hreflang' => 'uk'], 'en' => ['hreflang' => 'en'], 'ru' => ['hreflang' => 'ru']],
    'public' => ['uk', 'en', 'ru'],
    'localized_paths' => ['/'],
    'preview' => false,
]);

// Тимчасовий корінь сайту й фід: «shift» — українською й англійською (свої обкладинки), «uk-only» — лише українською
$setup = function () use ($config): string {
    $root = sys_get_temp_dir() . '/blog-test-' . bin2hex(random_bytes(4));
    mkdir("{$root}/content/blog", 0755, true);
    Locale::useConfig($config());
    BlogStore::useRoot($root);
    LocalizedUrl::forgetContent();
    LocalizedUrl::registerContent('blog', [BlogStore::class, 'localesOfPath']);
    $_ENV['APP_URL'] = 'https://g24.com.ua';

    return $root;
};
$teardown = function (string $root): void {
    exec('rm -rf ' . escapeshellarg($root));
    BlogStore::useRoot(null);
    LocalizedUrl::forgetContent();
    Locale::useConfig(null);
};
$feed = fn (bool $withEnglish = true) => [
    [
        'slug' => 'shift', 'title' => 'Зміна', 'excerpt' => 'Коротко', 'published_at' => '2026-10-01', 'tags' => [],
        'content' => "Текст\n\n![Скрін](https://93.184.216.34/uk-shot)", 'cover_image' => 'https://93.184.216.34/uk-cover',
        'translations' => $withEnglish ? ['en' => [
            'title' => 'Shift', 'excerpt' => 'Short', 'content' => "Text\n\n![Shot](https://93.184.216.34/en-shot)", 'cover_image' => 'https://93.184.216.34/en-cover',
        ]] : [],
    ],
    ['slug' => 'uk-only', 'title' => 'Лише українською', 'published_at' => '2026-09-01', 'content' => 'Текст', 'translations' => []],
];
// Замість мережі: кожна «картинка» — свій вміст
$fetch = fn (string $url) => ['png-' . basename($url), 'image/png'];

return [
    'синхронізація: кожна мова — свої файли й свої картинки' => function () use ($setup, $teardown, $feed, $fetch) {
        $root = $setup();
        try {
            $report = (new BlogSync($fetch))->run($feed());
            same(['uk' => 2, 'en' => 1, 'ru' => 0], $report['synced']);
            same(4, $report['images_downloaded']);

            same(['shift', 'uk-only'], array_column(BlogStore::posts('uk'), 'slug'));
            same(['shift'], array_column(BlogStore::posts('en'), 'slug'));
            same([], BlogStore::posts('ru'));

            $en = BlogStore::post('shift', 'en');
            same('Shift', $en['title']);
            check(str_starts_with($en['image'], '/assets/img/blog/synced/en/shift/cover-'), 'англійська обкладинка — у теці en: ' . $en['image']);
            check(str_starts_with(BlogStore::post('shift', 'uk')['image'], '/assets/img/blog/synced/uk/shift/'), 'українська — у теці uk');
            check(str_contains(file_get_contents(BlogStore::file('shift', 'en')), '](/assets/img/blog/synced/en/shift/img-'), 'картинка тексту en — локальна, у теці en');
            same(['uk', 'en'], BlogStore::localesOf('shift'));
        } finally {
            $teardown($root);
        }
    },

    'синхронізація: у версії мовою немає своєї обкладинки — українська' => function () use ($setup, $teardown, $feed, $fetch) {
        $root = $setup();
        try {
            $posts = $feed();
            unset($posts[0]['translations']['en']['cover_image']);
            (new BlogSync($fetch))->run($posts);

            same(BlogStore::post('shift', 'uk')['image'], BlogStore::post('shift', 'en')['image']);
            check(str_starts_with(BlogStore::post('shift', 'en')['image'], '/assets/img/blog/synced/uk/shift/cover-'), 'файл з теки uk, без копії');
        } finally {
            $teardown($root);
        }
    },

    'синхронізація: прибрали версію мовою — зникли й файл, і її картинки; старе розкладання прибрано' => function () use ($setup, $teardown, $feed, $fetch) {
        $root = $setup();
        try {
            // Старе розкладання без мов
            file_put_contents("{$root}/content/blog/2026-01-01-old.md", '# Old');
            file_put_contents("{$root}/content/blog/menu.json", '[]');
            mkdir("{$root}/assets/img/blog/synced/old", 0755, true);

            (new BlogSync($fetch))->run($feed());
            (new BlogSync($fetch))->run($feed(withEnglish: false));

            same([], BlogStore::posts('en'));
            check(!is_dir("{$root}/assets/img/blog/synced/en/shift"), 'картинки англійської версії мали зникнути');
            check(BlogStore::file('shift', 'uk') !== null, 'українська лишилась');
            check(!is_file("{$root}/content/blog/2026-01-01-old.md") && !is_file("{$root}/content/blog/menu.json"), 'старі файли без мови прибрано');
            check(!is_dir("{$root}/assets/img/blog/synced/old"), 'стара тека картинок без мови прибрана');
        } finally {
            $teardown($root);
        }
    },

    'адреси: стаття — лише своїми мовами; немає версії — canonical українська' => function () use ($setup, $teardown, $feed, $fetch) {
        $root = $setup();
        try {
            (new BlogSync($fetch))->run($feed());

            same(['uk' => 'https://g24.com.ua/blog/shift', 'en' => 'https://g24.com.ua/en/blog/shift'], LocalizedUrl::alternates('/blog/shift'));
            same(['uk' => 'https://g24.com.ua/blog/uk-only'], LocalizedUrl::alternates('/blog/uk-only'));
            same(['uk', 'en'], array_keys(LocalizedUrl::alternates('/blog')), 'список — мовами, де є статті');

            Locale::set('en', '/blog/uk-only');
            same('https://g24.com.ua/blog/uk-only', LocalizedUrl::canonical());
            same('uk', BlogStore::contentLocale('/blog/uk-only'));
            Locale::set('en', '/blog/shift');
            same('https://g24.com.ua/en/blog/shift', LocalizedUrl::canonical());
            same('en', BlogStore::contentLocale('/blog/shift'));
            Locale::set('ru', '/blog');
            same('uk', BlogStore::contentLocale('/blog'), 'російських статей немає — список українською');
        } finally {
            $teardown($root);
        }
    },

    'мапа сайту: стаття кожною своєю мовою з hreflang і обкладинкою цієї мови' => function () use ($setup, $teardown, $feed, $fetch) {
        $root = $setup();
        try {
            (new BlogSync($fetch))->run($feed());
            $controller = (new ReflectionClass(App\Controllers\SitemapController::class))->newInstanceWithoutConstructor();
            $xml = (fn () => $this->buildXml())->call($controller);

            preg_match('#<url>\s*<loc>https://g24.com.ua/en/blog/shift</loc>.*?</url>#s', $xml, $m);
            check(isset($m[0]), 'у мапі немає /en/blog/shift');
            check(str_contains($m[0], 'hreflang="uk" href="https://g24.com.ua/blog/shift"'), 'альтернатива українською');
            check(str_contains($m[0], '/assets/img/blog/synced/en/shift/cover-'), 'обкладинка англійською');
            check(!str_contains($xml, '/en/blog/uk-only'), 'статті без англійської версії англійською в мапі немає');
            check(str_contains($xml, '<loc>https://g24.com.ua/blog/uk-only</loc>'), 'українська — є');
        } finally {
            $teardown($root);
        }
    },
];
