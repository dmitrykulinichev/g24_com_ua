<?php

namespace App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Services\MarkdownService;

class GenerateSitemapCommand extends Command
{
    protected static $defaultName = 'sitemap:generate';

    protected function configure()
    {
        $this
            ->setDescription('Generate the sitemap.xml file')
            ->setHelp('This command generates a sitemap.xml file based on your content.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->comment('Generating sitemap...');

        $baseUrl = $_ENV['APP_URL'] ?? 'http://localhost';
        $baseUrl = rtrim($baseUrl, '/');

        $urls = [];

        // 1. Статичні сторінки (Головна, Тарифи, Контакти тощо)
        $staticPages = [
            '/' => '1.0',
            '/features' => '0.9',
            '/target' => '0.8',
            '/pricing' => '0.9',
            '/contacts' => '0.7',
            '/blog' => '0.8',
            '/docs' => '0.8'
        ];

        foreach ($staticPages as $path => $priority) {
            $urls[] = [
                'loc' => $baseUrl . $path,
                'lastmod' => date('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => $priority
            ];
        }

        // 2. Блог (з menu.json)
        $blogMenuPath = __DIR__ . '/../../content/blog/menu.json';
        if (file_exists($blogMenuPath)) {
            $posts = json_decode(file_get_contents($blogMenuPath), true);
            foreach ($posts as $post) {
                $urls[] = [
                    'loc' => $baseUrl . '/blog/' . $post['slug'],
                    'lastmod' => $post['date'], // У JSON дата вже у форматі YYYY-MM-DD
                    'changefreq' => 'monthly',
                    'priority' => '0.8'
                ];
            }
        } else {
            // Fallback, якщо menu.json немає
            $posts = MarkdownService::getList(__DIR__ . '/../../content/blog');
            foreach ($posts as $post) {
                $urls[] = [
                    'loc' => $baseUrl . '/blog/' . $post['slug'],
                    'lastmod' => date('Y-m-d', $post['date']),
                    'changefreq' => 'monthly',
                    'priority' => '0.8'
                ];
            }
        }

        // 3. Документація (з menu.json)
        $docsMenuPath = __DIR__ . '/../../content/docs/menu.json';
        if (file_exists($docsMenuPath)) {
            $menu = json_decode(file_get_contents($docsMenuPath), true);
            foreach ($menu as $group) {
                foreach ($group['items'] as $item) {
                    $urls[] = [
                        'loc' => $baseUrl . '/docs/' . $item['slug'],
                        'lastmod' => date('Y-m-d'), // Для документації беремо поточну дату, бо в menu.json немає дати
                        'changefreq' => 'monthly',
                        'priority' => '0.9'
                    ];
                }
            }
        } else {
             // Fallback
            $docs = MarkdownService::getList(__DIR__ . '/../../content/docs');
            foreach ($docs as $doc) {
                $urls[] = [
                    'loc' => $baseUrl . '/docs/' . $doc['slug'],
                    'lastmod' => date('Y-m-d', $doc['date']),
                    'changefreq' => 'monthly',
                    'priority' => '0.9'
                ];
            }
        }

        // Формуємо XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . PHP_EOL;
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        // Зберігаємо файл
        $path = __DIR__ . '/../../sitemap.xml';
        file_put_contents($path, $xml);

        $io->success('Sitemap generated successfully at ' . $path);
        
        return Command::SUCCESS;
    }
}