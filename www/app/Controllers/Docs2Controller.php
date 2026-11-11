<?php

namespace App\Controllers;

use App\Docs\DocsStore;
use App\Services\MarkdownService;

class Docs2Controller
{
    protected $blade;
    protected $contentPath;
    protected $screenshotsWebPath;
    /** Розділ: тека, меню й адреса (DocsStore::docs() чи DocsStore::driver()) */
    protected DocsStore $store;

    public function __construct()
    {
        $this->blade = new \Jenssegers\Blade\Blade(__DIR__ . '/../../views', __DIR__ . '/../../storage/cache');
        $this->store = DocsStore::docs();
        $this->contentPath = $this->store->root();
        $this->screenshotsWebPath = '/content/docs2/screenshots';
    }

    /**
     * Що шаблонам треба знати про розділ: адреси, пошук і тексти (views/docs2). Посібник водія
     * (DriverGuideController) підставляє своє — шаблони й код ті самі.
     */
    protected function section(): array
    {
        return [
            'name'        => 'docs',
            'base'        => $this->store->basePath(),
            'search'      => '/api/docs/search',
            'title'       => t('docs.index.title'),
            'subtitle'    => t('docs.index.subtitle'),
            'nav'         => t('docs.page.nav'),
            'breadcrumb'  => t('docs.page.breadcrumb'),
            'title_suffix' => null,
        ];
    }

    protected function getMenu()
    {
        // Українська — основна: у меню лише сторінки, що мають український файл; назви — мовою сторінки (DocsStore)
        $default = \App\I18n\Locale::config()->default();
        $menu = $this->store->rawMenu();

        foreach ($menu as &$group) {
            $group = DocsStore::localize($group);
            $group['items'] = array_values(array_filter($group['items'], function ($item) use ($default) {
                return !empty($item['docFile']) && file_exists($this->store->file($item['docFile'], $default));
            }));

            foreach ($group['items'] as &$item) {
                $item = DocsStore::localize($item);
                if (!empty($item['tabs'])) {
                    $item['tabs'] = array_values(array_map([DocsStore::class, 'localize'], array_filter($item['tabs'], function ($tab) use ($default) {
                        return !empty($tab['docFile']) && file_exists($this->store->file($tab['docFile'], $default));
                    })));
                }
            }
            unset($item);
        }
        unset($group);

        $menu = array_values(array_filter($menu, fn($g) => !empty($g['items'])));

        return $menu;
    }

    /**
     * Знайти пункт меню за slug (батьківський або таб)
     */
    protected function findMenuItem($menu, $slug)
    {
        foreach ($menu as $group) {
            foreach ($group['items'] as $item) {
                if ($item['slug'] === $slug) {
                    return ['item' => $item, 'tab' => null];
                }
                foreach ($item['tabs'] ?? [] as $tab) {
                    if ($tab['slug'] === $slug) {
                        return ['item' => $item, 'tab' => $tab];
                    }
                }
            }
        }
        return null;
    }

    /**
     * Будує HTML для одного скріншота (десктопний контейнер з заголовком)
     */
    protected function buildDesktopHtml($src, $alt)
    {
        $alt = htmlspecialchars($alt, ENT_QUOTES);
        return <<<HTML
<div class="screenshot-container">
    <div class="screenshot-header">
        <div class="screenshot-dots">
            <div class="dot dot-red"></div>
            <div class="dot dot-yellow"></div>
            <div class="dot dot-green"></div>
        </div>
        <div class="screenshot-title">{$alt}</div>
    </div>
    <div class="screenshot-content"><img src="{$src}" alt="{$alt}"></div>
</div>
HTML;
    }

    /**
     * Будує HTML для мобільного телефону-фрейму
     */
    protected function buildMobileHtml($src, $alt)
    {
        $alt = htmlspecialchars($alt, ENT_QUOTES);
        return <<<HTML
<div class="screenshot-phone">
    <div class="screenshot-phone-notch"><div class="screenshot-phone-notch-bar"></div></div>
    <div class="screenshot-phone-content"><img src="{$src}" alt="{$alt} (mobile)"></div>
    <div class="screenshot-phone-chin"><div class="screenshot-phone-chin-bar"></div></div>
</div>
HTML;
    }

    /**
     * Заглушка для відсутнього скріншота
     */
    protected function buildPlaceholderHtml(string $alt): string
    {
        $label = htmlspecialchars($alt ?: t('docs.placeholder.alt'), ENT_QUOTES);
        $title = htmlspecialchars(t('docs.placeholder.title'), ENT_QUOTES);
        $text = htmlspecialchars(t('docs.placeholder.text'), ENT_QUOTES);
        return <<<HTML
<div class="screenshot-placeholder">
    <div class="screenshot-placeholder-icon">&#128247;</div>
    <div class="screenshot-placeholder-label">
        <strong>{$title}</strong>
        <span>{$text}</span>
    </div>
</div>
HTML;
    }

    /**
     * Скріни з реєстру (content/screens.json): у Markdown — `![](screen:app-login)`, на сторінці — файл мовою
     * сторінки з підписом зі словника (partials.screen), як на решті лендінгу. Кілька через кому —
     * `![](screen:app-signin,app-signin-code,app-home)` — потік кроками: телефони в ряд (на телефоні гортаються вбік),
     * з номером кроку на кадрі й коротким підписом під ним — зі словника (`screen_captions.<id>`, один на скрін для
     * всіх сторінок); сторінка може замінити своїм в alt через `|`: `![Вхід||Головна](screen:a,b,c)` (порожньо — зі словника).
     */
    protected function renderRegistryScreens(string $html): string
    {
        return preg_replace_callback(
            '/(?:<p>)?<img\s+src="screen:([a-z0-9-]+(?:,[a-z0-9-]+)*)"(?:\s+alt="([^"]*)")?[^>]*>(?:<\/p>)?/',
            function ($m) {
                $ids = explode(',', $m[1]);
                $captions = ($m[2] ?? '') === '' ? [] : array_map('trim', explode('|', $m[2]));
                if (count($ids) === 1) {
                    return '<figure class="docs-app-screen">'
                        . $this->blade->make('partials.screen', ['id' => $ids[0], 'class' => 'mx-auto w-full max-w-[18rem]'])->render()
                        . '</figure>';
                }

                // Номер кроку в кружечку — той самий, що в нумерованому списку одразу після ряду (CSS `.docs-app-screens + ol`)
                $items = array_map(
                    fn ($id, $i) => '<div class="relative snap-start shrink-0 w-[70%] sm:w-auto sm:flex-1">'
                        . '<span class="docs-step-badge" aria-hidden="true">' . ($i + 1) . '</span>'
                        . $this->blade->make('partials.screen', ['id' => $id, 'class' => 'w-full'])->render()
                        . $this->stepCaption($id, $captions[$i] ?? '')
                        . '</div>',
                    $ids,
                    array_keys($ids)
                );

                return '<figure class="docs-app-screens flex gap-3 overflow-x-auto snap-x snap-mandatory pb-2">' . implode('', $items) . '</figure>';
            },
            $html
        );
    }

    /** Підпис кроку: свій зі сторінки, інакше зі словника (`screen_captions.<id>`), немає — без підпису. */
    protected function stepCaption(string $id, string $own): string
    {
        $key = 'screen_captions.' . $id;
        $text = $own !== '' ? $own : (t($key) !== $key ? t($key) : '');

        return $text === '' ? '' : '<figcaption class="docs-step-caption">' . htmlspecialchars($text) . '</figcaption>';
    }

    /**
     * Обробляє standalone <img> зі скріншотами: замінює на responsive-блоки.
     * Якщо файл не існує — показує заглушку.
     * Desktop (md+): desktop + mobile поруч (якщо є мобільний).
     * Mobile (<md): тільки mobile; якщо немає — desktop.
     */
    protected function processScreenshots($html)
    {
        $screenshotsDir = $this->contentPath . '/screenshots/light';
        $screenshotsUrl = $this->screenshotsWebPath . '/light';

        return preg_replace_callback(
            '/<img\s+src="(?:\.\.\/)*screenshots\/light\/desktop\/([^"]+)"\s+alt="([^"]*)"\s*\/?>/',
            function ($m) use ($screenshotsDir, $screenshotsUrl) {
                $filename    = $m[1];
                $alt         = $m[2];
                $desktopPath = $screenshotsDir . '/desktop/' . $filename;
                $mobilePath  = $screenshotsDir . '/mobile/' . $filename;
                $hasDesktop  = file_exists($desktopPath);
                $hasMobile   = file_exists($mobilePath);

                if (!$hasDesktop && !$hasMobile) {
                    return $this->buildPlaceholderHtml($alt);
                }

                $desktopSrc  = $screenshotsUrl . '/desktop/' . $filename;
                $mobileSrc   = $screenshotsUrl . '/mobile/' . $filename;
                $desktopHtml = $hasDesktop ? $this->buildDesktopHtml($desktopSrc, $alt) : '';
                $mobileHtml  = $hasMobile  ? $this->buildMobileHtml($mobileSrc, $alt)  : '';

                if ($hasDesktop && $hasMobile) {
                    $duoHtml        = '<div class="screenshot-desktop-duo">' . $desktopHtml . $mobileHtml . '</div>';
                    $mobileOnlyHtml = '<div class="screenshot-mobile-only">' . $mobileHtml . '</div>';
                    return $duoHtml . $mobileOnlyHtml;
                }

                if ($hasDesktop) {
                    return $desktopHtml;
                }

                // Тільки мобільний (рідкісний кейс)
                return '<div class="screenshot-mobile-only">' . $mobileHtml . '</div>';
            },
            $html
        );
    }

    /**
     * Перезаписує залишкові відносні шляхи screenshots/ що не потрапили в processScreenshots
     */
    protected function rewriteImagePaths($html)
    {
        return preg_replace(
            '/(<img\s[^>]*src=")(?:\.\.\/)*screenshots\//',
            '$1' . $this->screenshotsWebPath . '/',
            $html
        );
    }

    public function index()
    {
        $menu = $this->getMenu();
        echo $this->blade->make('docs2.index', [
            'menu'    => $menu,
            'section' => $this->section(),
            'meta'    => $this->indexMeta(),
        ])->render();
    }

    protected function indexMeta(): array
    {
        return [
            'title' => t('docs.meta.title'),
            'description' => t('docs.meta.description'),
        ];
    }

    /**
     * Стара адреса сторінки, що переїхала в інший розділ: /docs/driver-records → /driver/records (301).
     * null — такої старої адреси немає.
     */
    protected function movedTo(string $slug): ?string
    {
        if (preg_match('/^driver-([a-z0-9-]+)$/', $slug, $m) && DocsStore::driver()->pageLocales($m[1]) !== null) {
            return DocsStore::driver()->pagePath($m[1]);
        }

        return null;
    }

    public function show($slug)
    {
        $menu = $this->getMenu();
        $found = $this->findMenuItem($menu, $slug);

        if (!$found && ($moved = $this->movedTo($slug)) !== null) {
            // Мова зберігається: /en/docs/driver-records → /en/driver/records
            header('Location: ' . lurl($moved), true, 301);
            return;
        }

        if (!$found) {
            http_response_code(404);
            echo $this->blade->make('errors.404')->render();
            return;
        }

        $parentItem = $found['item'];
        $activeTab  = $found['tab'];
        $isTab      = $activeTab !== null;
        $parentSlug = $isTab ? $parentItem['slug'] : null;

        $docFile  = $isTab ? $activeTab['docFile'] : $parentItem['docFile'];
        // Мовою сторінки, якщо є переклад; інакше українська (canonical — на неї, LocalizedUrl)
        $filePath = $this->store->file($docFile, $this->store->contentLocale($slug));
        $data = MarkdownService::parseFile($filePath);

        if (!$data) {
            http_response_code(404);
            echo $this->blade->make('errors.404')->render();
            return;
        }

        $rendered    = MarkdownService::render($data['content']);
        $htmlContent = $this->rewriteImagePaths($this->processScreenshots($this->renderRegistryScreens($rendered)));

        // OG image — конвертуємо відносний шлях скріншота в абсолютний web-шлях
        if (!empty($data['meta']['image']) && str_starts_with($data['meta']['image'], 'screenshots/')) {
            $data['meta']['image'] = '/content/docs2/' . $data['meta']['image'];
        }
        // Скрін з реєстру (`screen:id`) — файл, який бачить сторінка
        if (!empty($data['meta']['image']) && str_starts_with($data['meta']['image'], 'screen:')) {
            // Потік `screen:a,b,c` — перший скрін
            $firstScreen = explode(',', substr($data['meta']['image'], 7))[0];
            $data['meta']['image'] = \App\Screens\ScreenRegistry::instance()->image($firstScreen)['path'];
        }

        // Плоский список для навігації prev/next (батьківські + таби після кожного)
        $flatList = [];
        foreach ($menu as $group) {
            foreach ($group['items'] as $item) {
                $flatList[] = $item;
                foreach ($item['tabs'] ?? [] as $tab) {
                    $flatList[] = $tab;
                }
            }
        }

        $prev = null;
        $next = null;
        $count = count($flatList);
        for ($i = 0; $i < $count; $i++) {
            if ($flatList[$i]['slug'] === $slug) {
                if ($i > 0) $prev = $flatList[$i - 1];
                if ($i < $count - 1) $next = $flatList[$i + 1];
                break;
            }
        }

        // Сторінка розділу з власною назвою (посібник водія): «Записи за зміну — Посібник водія | Garage24»
        $section = $this->section();
        $docTitle = $data['meta']['title'] ?? $slug;
        if (!empty($section['title_suffix']) && !empty($data['meta']['title'])) {
            $data['meta']['title'] .= ' — ' . $section['title_suffix'];
        }

        echo $this->blade->make('docs2.page', [
            'section'       => $section,
            'slug'          => $slug,
            'docTitle'      => $docTitle,
            'content'       => $htmlContent,
            'menu'          => $menu,
            'meta'          => $data['meta'],
            'prev'          => $prev,
            'next'          => $next,
            'isTab'         => $isTab,
            'parentSlug'    => $parentSlug,
            'parentItem'    => $parentItem,
            'currentTabs'   => $parentItem['tabs'] ?? [],
            'activeTabSlug' => $slug,
        ])->render();
    }
}
