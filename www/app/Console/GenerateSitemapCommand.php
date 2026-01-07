<?php

namespace App\Console;

// Виправлено namespace для Aloe v4
use Aloe\Command;
use App\Services\MarkdownService;

class GenerateSitemapCommand extends Command
{
    protected static $defaultName = 'sitemap:generate';
    protected static $defaultDescription = 'Generate the sitemap.xml file';

    protected function configure()
    {
        $this->setHelp('This command generates a sitemap.xml file based on your content.');
    }

    protected function handle()
    {
        $this->comment('Generating sitemap...');

        $baseUrl = $_ENV['APP_URL'] ?? 'http://localhost';
        $baseUrl = rtrim($baseUrl, '/');

        $urls = [];

        // 1. Головна
        $urls[] = [
            'loc' => $baseUrl . '/',
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'weekly',
            'priority' => '1.0'
        ];

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
        $docs = MarkdownService::getList(__DIR__ . '/../../content/docs');
        foreach ($docs as $doc) {
            $urls[] = [
                'loc' => $baseUrl . '/docs/' . $doc['slug'],
                'lastmod' => date('Y-m-d', $doc['date']),
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ];
        }

        // Формуємо XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . $url['loc'] . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . PHP_EOL;
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        // Зберігаємо файл
        $path = __DIR__ . '/../../sitemap.xml';
        file_put_contents($path, $xml);

        $this->info('Sitemap generated successfully at ' . $path);
        return 0;
    }
}