<?php

namespace App\Controllers;

use App\Services\MarkdownService;
use Leaf\Blade;

class BlogController
{
    protected $blade;
    protected $contentPath;

    public function __construct()
    {
        // Ініціалізуємо Blade тут або отримуємо через DI, якщо налаштовано
        // Для простоти створимо новий екземпляр, як в index.php
        $this->blade = new \Jenssegers\Blade\Blade(__DIR__ . '/../../views', __DIR__ . '/../../storage/cache');
        $this->contentPath = __DIR__ . '/../../content/blog';
    }

    public function index()
    {
        $posts = MarkdownService::getList($this->contentPath);
        echo $this->blade->make('blog.index', ['posts' => $posts])->render();
    }

    public function show($slug)
    {
        $path = $this->contentPath . '/' . $slug . '.md';
        
        // Якщо точного файлу немає, шукаємо з префіксом дати
        if (!file_exists($path)) {
            $files = glob($this->contentPath . '/*-' . $slug . '.md');
            if (!empty($files)) {
                $path = $files[0];
            }
        }

        $data = MarkdownService::parseFile($path);

        if (!$data) {
            response()->exit(404);
        }

        $htmlContent = MarkdownService::render($data['content']);

        // Навігація
        $posts = MarkdownService::getList($this->contentPath);
        $newer = null; $older = null;
        $count = count($posts);

        for ($i = 0; $i < $count; $i++) {
            if ($posts[$i]['slug'] === $slug) {
                if ($i > 0) $newer = $posts[$i - 1];
                if ($i < $count - 1) $older = $posts[$i + 1];
                break;
            }
        }

        echo $this->blade->make('blog.post', [
            'slug' => $slug,
            'content' => $htmlContent,
            'meta' => $data['meta'],
            'newer' => $newer,
            'older' => $older
        ])->render();
    }
}