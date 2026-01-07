<?php

namespace App\Console;

// Використовуємо чистий Symfony Command замість Aloe
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Services\MarkdownService;

class GenerateSitemapCommand extends Command
{
    // У Symfony Command ім'я задається через властивість або в configure()
    protected static $defaultName = 'sitemap:generate';

    protected function configure()
    {
        $this
            ->setDescription('Generate the sitemap.xml file')
            ->setHelp('This command generates a sitemap.xml file based on your content.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Використовуємо SymfonyStyle для гарного виводу (як $this->info в Aloe)
        $io = new SymfonyStyle($input, $output);
        $io->comment('Generating sitemap...');

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

        $io->success('Sitemap generated successfully at ' . $path);
        
        return Command::SUCCESS;
    }
}