<?php

namespace App\Services;

// TODO: розширити image sitemap на всі зображення сайту:
//  - blog: парсити <img> з відрендереного HTML або шукати markdown-зображення в md-файлах блогу
//  - статичні сторінки (/, /features, /target, /pricing): додати вручну або сканувати views/*.blade.php
//  - assets/img/* (логотипи, ілюстрації): розглянути чи потрібно включати в sitemap
//  Поточна реалізація покриває лише docs ({{screenshot}} shortcode-и з перевіркою файлу).

class DocImageSitemapService
{
    protected string $contentPath;
    protected string $assetsPath;
    protected string $menuPath;

    public function __construct()
    {
        $this->contentPath = dirname(__DIR__, 2) . '/content/docs';
        $this->assetsPath  = dirname(__DIR__, 2) . '/assets/img/docs';
        $this->menuPath    = dirname(__DIR__, 2) . '/content/docs/menu.json';
    }

    /**
     * Повертає масив записів для image sitemap:
     * [['loc' => '/docs/slug', 'images' => [['loc' => '/assets/...', 'title' => '...'], ...]], ...]
     */
    public function getEntries(string $baseUrl): array
    {
        if (!file_exists($this->menuPath)) return [];

        $menu    = json_decode(file_get_contents($this->menuPath), true) ?? [];
        $entries = [];

        foreach ($menu as $group) {
            foreach ($group['items'] as $item) {
                $slugs = [$item['slug']];
                foreach ($item['tabs'] ?? [] as $tab) {
                    $slugs[] = $tab['slug'];
                }

                foreach ($slugs as $slug) {
                    $images = $this->extractImages($slug, $baseUrl);
                    if (!empty($images)) {
                        $entries[] = [
                            'loc'    => $baseUrl . '/docs/' . $slug,
                            'images' => $images,
                        ];
                    }
                }
            }
        }

        return $entries;
    }

    /**
     * Парсить {{screenshot ...}} з md-файлу,
     * підтримує обидва формати — новий (desktop=/mobile=) і старий (file=).
     * Повертає лише ті зображення що фізично існують.
     */
    protected function extractImages(string $slug, string $baseUrl): array
    {
        $mdPath = $this->contentPath . '/' . $slug . '.md';
        if (!file_exists($mdPath)) return [];

        $content = file_get_contents($mdPath);
        $images  = [];
        $baseImgUrl = $baseUrl . '/assets/img/docs/';

        preg_match_all('/\{\{screenshot\s+([^}]+)\}\}/', $content, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $attrs = [];
            preg_match_all('/(\w+)="([^"]*)"/', $match[1], $attrMatches, PREG_SET_ORDER);
            foreach ($attrMatches as $attr) {
                $attrs[$attr[1]] = $attr[2];
            }
            $title = $attrs['title'] ?? $slug;

            if (isset($attrs['desktop']) || isset($attrs['mobile'])) {
                // Новий формат: desktop= та mobile=
                foreach (['desktop', 'mobile'] as $platform) {
                    if (!empty($attrs[$platform]) && file_exists($this->assetsPath . '/' . $attrs[$platform])) {
                        $images[] = [
                            'loc'   => $baseImgUrl . $attrs[$platform],
                            'title' => $title,
                        ];
                    }
                }
            } elseif (!empty($attrs['file'])) {
                // Старий формат: file=
                if (file_exists($this->assetsPath . '/' . $attrs['file'])) {
                    $images[] = [
                        'loc'   => $baseImgUrl . $attrs['file'],
                        'title' => $title,
                    ];
                }
            }
        }

        return $images;
    }
}
