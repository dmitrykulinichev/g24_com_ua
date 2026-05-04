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
        }
        unset($group);

        // Прибираємо порожні групи
        $menu = array_values(array_filter($menu, fn($g) => !empty($g['items'])));

        return $menu;
    }

    /**
     * Знайти пункт меню за slug
     */
    protected function findMenuItem($menu, $slug)
    {
        foreach ($menu as $group) {
            foreach ($group['items'] as $item) {
                if ($item['slug'] === $slug) {
                    return $item;
                }
            }
        }
        return null;
    }

    /**
     * Перезаписує відносні шляхи до скріншотів на абсолютні URL
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
        $item = $this->findMenuItem($menu, $slug);

        if (!$item) {
            response()->exit(404);
        }

        $filePath = $this->resolveDocFile($item['docFile']);
        $data = MarkdownService::parseFile($filePath);

        if (!$data) {
            response()->exit(404);
        }

        $htmlContent = $this->rewriteImagePaths(MarkdownService::render($data['content']));

        // Плоский список для навігації prev/next
        $flatList = [];
        foreach ($menu as $group) {
            foreach ($group['items'] as $menuItem) {
                $flatList[] = $menuItem;
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
            'slug'    => $slug,
            'content' => $htmlContent,
            'menu'    => $menu,
            'meta'    => $data['meta'],
            'prev'    => $prev,
            'next'    => $next,
            'item'    => $item,
        ])->render();
    }
}
