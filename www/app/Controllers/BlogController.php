<?php

namespace App\Controllers;

use App\Services\MarkdownService;
use Leaf\Blade;

class BlogController
{
    protected $blade;
    protected $contentPath;
    protected $menuPath;

    public function __construct()
    {
        // Ініціалізуємо Blade тут або отримуємо через DI, якщо налаштовано
        // Для простоти створимо новий екземпляр, як в index.php
        $this->blade = new \Jenssegers\Blade\Blade(__DIR__ . '/../../views', __DIR__ . '/../../storage/cache');
        $this->contentPath = __DIR__ . '/../../content/blog';
        $this->menuPath = __DIR__ . '/../../content/blog/menu.json';
    }

    protected function getList()
    {
        if (file_exists($this->menuPath)) {
            $posts = json_decode(file_get_contents($this->menuPath), true);
            // Конвертуємо дати з рядків у timestamp для сумісності з view
            foreach ($posts as &$post) {
                if (is_string($post['date'])) {
                    $post['date'] = strtotime($post['date']);
                }
            }
            return $posts;
        }
        
        // Fallback, якщо menu.json немає
        return MarkdownService::getList($this->contentPath);
    }

    public function index()
    {
        $perPage = 10;
        $posts = $this->getList(); // уже відсортовано новіші зверху (порядок з menu.json)

        $totalPosts = count($posts);
        $totalPages = max(1, (int) ceil($totalPosts / $perPage));

        $page = (int) ($_GET['page'] ?? 1);
        $page = max(1, min($page, $totalPages));

        $pagedPosts = array_slice($posts, ($page - 1) * $perPage, $perPage);

        echo $this->blade->make('blog.index', [
            'posts' => $pagedPosts,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ])->render();
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

        $data['meta']['updated'] = filemtime($path);

        // Використовуємо preview та image з menu.json якщо є — вони написані вручну і краще для SEO
        if (file_exists($this->menuPath)) {
            $menuItems = json_decode(file_get_contents($this->menuPath), true) ?? [];
            foreach ($menuItems as $item) {
                if ($item['slug'] === $slug) {
                    if (!empty($item['preview'])) {
                        $data['meta']['description'] = $item['preview'];
                    }
                    if (!empty($item['image'])) {
                        $data['meta']['image'] = $item['image'];
                    }
                    break;
                }
            }
        }

        // escapeMarkup=true: контент статей редагується через адмінку СААС —
        // сирий HTML у markdown екранується, щоб автор не міг вставити
        // <script>/довільний HTML, який виконається у відвідувачів сайту.
        $htmlContent = MarkdownService::render($data['content'], true);

        // Навігація
        $posts = $this->getList();
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