<?php

namespace App\Services;

class Docs2ImageSitemapService
{
    protected string $contentPath;
    protected string $menuPath;

    public function __construct()
    {
        $this->contentPath = dirname(__DIR__, 2) . '/content/docs2';
        $this->menuPath    = dirname(__DIR__, 2) . '/content/docs2/menu.json';
    }

    protected function resolveDocFile(string $docFile): string
    {
        $relative = preg_replace('/^_data\//', '', $docFile);
        return $this->contentPath . '/' . $relative;
    }

    /**
     * Повертає всі slugs (батьківські + таби) з menu.json
     */
    protected function getAllSlugs(): array
    {
        if (!file_exists($this->menuPath)) return [];
        $menu  = json_decode(file_get_contents($this->menuPath), true) ?? [];
        $slugs = [];

        foreach ($menu as $group) {
            foreach ($group['items'] as $item) {
                if (!empty($item['docFile']) && file_exists($this->resolveDocFile($item['docFile']))) {
                    $slugs[] = ['slug' => $item['slug'], 'docFile' => $item['docFile']];
                }
                foreach ($item['tabs'] ?? [] as $tab) {
                    if (!empty($tab['docFile']) && file_exists($this->resolveDocFile($tab['docFile']))) {
                        $slugs[] = ['slug' => $tab['slug'], 'docFile' => $tab['docFile']];
                    }
                }
            }
        }

        return $slugs;
    }

    /**
     * Парсить markdown-зображення ![alt](screenshots/light/desktop/xxx.png)
     * та повертає записи для image sitemap.
     */
    protected function extractImages(string $docFile, string $baseUrl): array
    {
        $filePath = $this->resolveDocFile($docFile);
        if (!file_exists($filePath)) return [];

        $content = file_get_contents($filePath);
        $images  = [];

        preg_match_all('/!\[([^\]]*)\]\(screenshots\/light\/desktop\/([^)]+)\)/', $content, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $alt      = $match[1];
            $filename = $match[2];

            $desktopPath = $this->contentPath . '/screenshots/light/desktop/' . $filename;
            $mobilePath  = $this->contentPath . '/screenshots/light/mobile/' . $filename;

            if (file_exists($desktopPath)) {
                $images[] = [
                    'loc'   => $baseUrl . '/content/docs2/screenshots/light/desktop/' . $filename,
                    'title' => $alt,
                ];
            }
            if (file_exists($mobilePath)) {
                $images[] = [
                    'loc'   => $baseUrl . '/content/docs2/screenshots/light/mobile/' . $filename,
                    'title' => $alt . ' (mobile)',
                ];
            }
        }

        return $images;
    }

    public function getEntries(string $baseUrl): array
    {
        $entries = [];

        foreach ($this->getAllSlugs() as $entry) {
            $images = $this->extractImages($entry['docFile'], $baseUrl);
            if (!empty($images)) {
                $entries[] = [
                    'loc'    => $baseUrl . '/docs2/' . $entry['slug'],
                    'images' => $images,
                ];
            }
        }

        return $entries;
    }
}
