<?php

namespace App\Controllers;

use App\Services\MarkdownService;

class SitemapController
{
    public function index()
    {
        // Визначаємо базовий URL (працює і локально, і на проді)
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $baseUrl = "$protocol://$host";

        $urls = [];

        // 1. Статичні сторінки
        $staticPages = [
            '/',
            '/features',
            '/target',
            '/pricing',
            '/contacts',
            '/blog',
            '/docs'
        ];

        foreach ($staticPages as $page) {
            // Для головної сторінки не додаємо слеш, якщо він вже є в baseUrl (але тут baseUrl без слеша)
            $loc = ($page === '/') ? $baseUrl . '/' : $baseUrl . $page;
            
            $urls[] = [
                'loc' => $loc,
                'lastmod' => date('Y-m-d'), // Сьогодні
                'changefreq' => 'weekly',
                'priority' => ($page === '/') ? '1.0' : '0.9'
            ];
        }

        // 2. Блог
        $posts = MarkdownService::getList(__DIR__ . '/../../content/blog');
        foreach ($posts as $post) {
            $urls[] = [
                'loc' => $baseUrl . '/blog/' . $post['slug'],
                'lastmod' => date('Y-m-d', $post['date']),
                'changefreq' => 'monthly',
                'priority' => '0.8'
            ];
        }

        // 3. Документація
        // Скануємо папку, щоб знайти всі файли, навіть ті, що не в меню
        $docs = MarkdownService::getList(__DIR__ . '/../../content/docs');
        foreach ($docs as $doc) {
            $urls[] = [
                'loc' => $baseUrl . '/docs/' . $doc['slug'],
                'lastmod' => date('Y-m-d', $doc['date']),
                'changefreq' => 'monthly',
                'priority' => '0.7'
            ];
        }

        // Формуємо XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($urls as $url) {
            $xml .= '<url>';
            $xml .= '<loc>' . $url['loc'] . '</loc>';
            $xml .= '<lastmod>' . $url['lastmod'] . '</lastmod>';
            $xml .= '<changefreq>' . $url['changefreq'] . '</changefreq>';
            $xml .= '<priority>' . $url['priority'] . '</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        // Віддаємо з правильним заголовком
        header('Content-Type: application/xml');
        echo $xml;
    }
}