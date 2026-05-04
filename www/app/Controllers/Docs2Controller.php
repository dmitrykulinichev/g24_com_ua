<?php

namespace App\Controllers;

use App\Services\MarkdownService;

class Docs2Controller
{
    protected $blade;
    protected $contentPath;
    protected $menuPath;
    protected $screenshotsWebPath;

    public function __construct()
    {
        $this->blade = new \Jenssegers\Blade\Blade(__DIR__ . '/../../views', __DIR__ . '/../../storage/cache');
        $this->contentPath = __DIR__ . '/../../content/docs2';
        $this->menuPath = __DIR__ . '/../../content/docs2/menu.json';
        $this->screenshotsWebPath = '/content/docs2/screenshots';
    }

    /**
     * Перетворює docFile "_data/foo/bar.md" → реальний шлях до файлу
     */
    protected function resolveDocFile($docFile)
    {
        $relative = preg_replace('/^_data\//', '', $docFile);
        return $this->contentPath . '/' . $relative;
    }

    protected function getMenu()
    {
        if (!file_exists($this->menuPath)) return [];
        $menu = json_decode(file_get_contents($this->menuPath), true) ?? [];

        foreach ($menu as &$group) {
            $group['items'] = array_values(array_filter($group['items'], function ($item) {
                return !empty($item['docFile']) && file_exists($this->resolveDocFile($item['docFile']));
            }));

            foreach ($group['items'] as &$item) {
                if (!empty($item['tabs'])) {
                    $item['tabs'] = array_values(array_filter($item['tabs'], function ($tab) {
                        return !empty($tab['docFile']) && file_exists($this->resolveDocFile($tab['docFile']));
                    }));
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
     * Обробляє standalone <img> зі скріншотами: замінює на responsive-блоки.
     * Desktop (md+): desktop + mobile поруч (якщо є мобільний).
     * Mobile (<md): тільки mobile; якщо немає — desktop.
     */
    protected function processScreenshots($html)
    {
        $screenshotsDir = $this->contentPath . '/screenshots/light';
        $screenshotsUrl = $this->screenshotsWebPath . '/light';

        return preg_replace_callback(
            '/<p>\s*<img\s+src="screenshots\/light\/desktop\/([^"]+)"\s+alt="([^"]*)"\s*\/?>\s*<\/p>/',
            function ($m) use ($screenshotsDir, $screenshotsUrl) {
                $filename   = $m[1];
                $alt        = $m[2];
                $desktopSrc = $screenshotsUrl . '/desktop/' . $filename;
                $mobilePath = $screenshotsDir . '/mobile/' . $filename;
                $mobileSrc  = $screenshotsUrl . '/mobile/' . $filename;
                $hasMobile  = file_exists($mobilePath);

                $desktopHtml = $this->buildDesktopHtml($desktopSrc, $alt);
                $mobileHtml  = $hasMobile ? $this->buildMobileHtml($mobileSrc, $alt) : '';

                if ($hasMobile) {
                    // Desktop: показуємо обидва поруч
                    $duoHtml = '<div class="screenshot-desktop-duo">' . $desktopHtml . $mobileHtml . '</div>';
                    // Mobile: тільки телефон
                    $mobileOnlyHtml = '<div class="screenshot-mobile-only">' . $mobileHtml . '</div>';
                    return $duoHtml . $mobileOnlyHtml;
                } else {
                    // Немає мобільного — тільки desktop (видно на всіх розмірах)
                    return '<div class="screenshot-desktop-only">' . $desktopHtml . '</div>' . $desktopHtml;
                }
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
            '/(<img\s[^>]*src=")screenshots\//',
            '$1' . $this->screenshotsWebPath . '/',
            $html
        );
    }

    public function index()
    {
        $menu = $this->getMenu();
        echo $this->blade->make('docs2.index', [
            'menu' => $menu,
            'meta' => [
                'title' => 'Документація v2',
                'description' => 'Інструкції, поради та відповіді на часті запитання про Garage24 — CRM-систему для управління автопарком.',
            ]
        ])->render();
    }

    public function show($slug)
    {
        $menu = $this->getMenu();
        $found = $this->findMenuItem($menu, $slug);

        if (!$found) {
            response()->exit(404);
        }

        $parentItem = $found['item'];
        $activeTab  = $found['tab'];
        $isTab      = $activeTab !== null;
        $parentSlug = $isTab ? $parentItem['slug'] : null;

        $docFile  = $isTab ? $activeTab['docFile'] : $parentItem['docFile'];
        $filePath = $this->resolveDocFile($docFile);
        $data = MarkdownService::parseFile($filePath);

        if (!$data) {
            response()->exit(404);
        }

        $rendered    = MarkdownService::render($data['content']);
        $htmlContent = $this->rewriteImagePaths($this->processScreenshots($rendered));

        // OG image — конвертуємо відносний шлях скріншота в абсолютний web-шлях
        if (!empty($data['meta']['image']) && str_starts_with($data['meta']['image'], 'screenshots/')) {
            $data['meta']['image'] = '/content/docs2/' . $data['meta']['image'];
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

        echo $this->blade->make('docs2.page', [
            'slug'          => $slug,
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
