<?php

namespace App\Controllers;

use App\Blog\BlogStore;
use App\Services\MarkdownService;

/**
 * Сторінки блогу (/blog, /blog/{slug}) мовою сторінки з файлового кешу (app/Blog/BlogStore): є стаття цією
 * мовою — показуємо її, немає — українську (canonical тоді веде на українську адресу, LocalizedUrl).
 */
class BlogController
{
    protected $blade;

    public function __construct()
    {
        $this->blade = new \Jenssegers\Blade\Blade(__DIR__ . '/../../views', __DIR__ . '/../../storage/cache');
    }

    /** Статті мовою; дата — timestamp (так чекають шаблони). */
    protected function getList(string $locale): array
    {
        return array_map(fn ($post) => ['date' => strtotime($post['date'])] + $post, BlogStore::posts($locale));
    }

    public function index()
    {
        $perPage = 10;
        $posts = $this->getList(BlogStore::contentLocale('/blog')); // новіші зверху (порядок menu.json)

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
        $locale = BlogStore::contentLocale("/blog/{$slug}");
        $path = BlogStore::file($slug, $locale);
        $data = $path ? MarkdownService::parseFile($path) : null;

        if (!$data) {
            http_response_code(404);
            echo $this->blade->make('errors.404')->render();
            return;
        }

        $data['meta']['updated'] = filemtime($path);

        // Анонс і обкладинка з menu.json — написані окремо, краще для SEO й прев'ю
        $item = BlogStore::post($slug, $locale);
        if (!empty($item['preview'])) {
            $data['meta']['description'] = $item['preview'];
        }
        if (!empty($item['image'])) {
            $data['meta']['image'] = $item['image'];
        }

        // escapeMarkup=true: контент статей редагується через адмінку СААС — сирий HTML у markdown
        // екранується, щоб автор не міг вставити <script>, який виконається у відвідувачів сайту.
        $htmlContent = MarkdownService::render($data['content'], true);

        // Навігація — серед статей тієї ж мови
        $posts = $this->getList($locale);
        $newer = null; $older = null;
        foreach ($posts as $i => $post) {
            if ($post['slug'] === $slug) {
                $newer = $posts[$i - 1] ?? null;
                $older = $posts[$i + 1] ?? null;
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
