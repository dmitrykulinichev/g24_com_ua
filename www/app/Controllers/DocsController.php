<?php

namespace App\Controllers;

use App\Services\MarkdownService;

class DocsController
{
    protected $blade;
    protected $contentPath;
    protected $menuPath;

    public function __construct()
    {
        $this->blade = new \Jenssegers\Blade\Blade(__DIR__ . '/../../views', __DIR__ . '/../../storage/cache');
        $this->contentPath = __DIR__ . '/../../content/docs';
        $this->menuPath = __DIR__ . '/../../content/docs/menu.json';
    }

    protected function getMenu()
    {
        if (!file_exists($this->menuPath)) return [];
        return json_decode(file_get_contents($this->menuPath), true) ?? [];
    }

    public function index()
    {
        $menu = $this->getMenu();
        echo $this->blade->make('docs.index', ['menu' => $menu])->render();
    }

    public function show($slug)
    {
        $path = $this->contentPath . '/' . $slug . '.md';
        $data = MarkdownService::parseFile($path);

        if (!$data) {
            response()->exit(404);
        }

        $htmlContent = MarkdownService::render($data['content']);
        $menu = $this->getMenu();

        // Навігація
        $flatList = [];
        foreach ($menu as $group) {
            foreach ($group['items'] as $item) {
                $flatList[] = $item;
            }
        }

        $prev = null; $next = null;
        $count = count($flatList);
        for ($i = 0; $i < $count; $i++) {
            if ($flatList[$i]['slug'] === $slug) {
                if ($i > 0) $prev = $flatList[$i - 1];
                if ($i < $count - 1) $next = $flatList[$i + 1];
                break;
            }
        }

        echo $this->blade->make('docs.page', [
            'slug' => $slug,
            'content' => $htmlContent,
            'menu' => $menu,
            'meta' => $data['meta'],
            'prev' => $prev,
            'next' => $next
        ])->render();
    }
}