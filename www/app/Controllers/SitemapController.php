<?php

namespace App\Controllers;

use App\Services\MarkdownService;
use App\Services\DocImageSitemapService;
use App\Services\Docs2ImageSitemapService;

class SitemapController
{
    protected $sitemapPath;

    public function __construct()
    {
        $this->sitemapPath = dirname(__DIR__, 2) . '/sitemap.xml';
    }

    public function index()
    {
        // Якщо статичний файл існує — Apache/Nginx роздасть його напряму (через !-f у .htaccess)
        // Цей метод є fallback на випадок, якщо статичного файлу ще немає
        if (file_exists($this->sitemapPath)) {
            header('Content-Type: application/xml; charset=UTF-8');
            readfile($this->sitemapPath);
            return;
        }

        // Генеруємо та повертаємо без збереження
        header('Content-Type: application/xml; charset=UTF-8');
        echo $this->buildXml();
    }

    /**
     * POST/GET /api/sitemap/generate?key=...
     * Генерує та зберігає sitemap.xml як статичний файл
     */
    public function generate()
    {
        $expectedKey = $_ENV['SITEMAP_KEY'] ?? null;

        if (!$expectedKey || ($_GET['key'] ?? '') !== $expectedKey) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Forbidden']);
            return;
        }

        $xml = $this->buildXml();

        if (file_put_contents($this->sitemapPath, $xml) === false) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Failed to write sitemap.xml']);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'urls'    => substr_count($xml, '<url>'),
            'saved'   => $this->sitemapPath,
        ]);
    }

    protected function buildXml(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
        $baseUrl  = rtrim($_ENV['APP_URL'] ?? ("$protocol://" . $_SERVER['HTTP_HOST']), '/');

        $urls = [];

        // 1. Статичні сторінки (дати вказані вручну, оновлювати після змін)
        $staticPages = [
            ['loc' => '/',          'lastmod' => '2025-10-01', 'changefreq' => 'weekly',  'priority' => '1.0'],
            ['loc' => '/features',  'lastmod' => '2025-10-01', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['loc' => '/target',    'lastmod' => '2025-10-01', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['loc' => '/pricing',   'lastmod' => '2025-10-01', 'changefreq' => 'weekly',  'priority' => '0.9'],
            ['loc' => '/contacts',  'lastmod' => '2025-10-01', 'changefreq' => 'yearly',  'priority' => '0.7'],
            ['loc' => '/blog',      'lastmod' => date('Y-m-d'), 'changefreq' => 'daily',  'priority' => '0.8'],
            ['loc' => '/docs',      'lastmod' => '2025-10-01', 'changefreq' => 'monthly', 'priority' => '0.8'],
        ];

        foreach ($staticPages as $page) {
            $urls[] = [
                'loc'        => $baseUrl . $page['loc'],
                'lastmod'    => $page['lastmod'],
                'changefreq' => $page['changefreq'],
                'priority'   => $page['priority'],
            ];
        }

        // 2. Блог
        $menuPath = dirname(__DIR__, 2) . '/content/blog/menu.json';
        if (file_exists($menuPath)) {
            $posts = json_decode(file_get_contents($menuPath), true) ?? [];
            foreach ($posts as $post) {
                $lastmod = is_string($post['date']) ? $post['date'] : date('Y-m-d', $post['date']);
                $urls[] = [
                    'loc'        => $baseUrl . '/blog/' . $post['slug'],
                    'lastmod'    => $lastmod,
                    'changefreq' => 'monthly',
                    'priority'   => '0.8',
                ];
            }
        } else {
            $posts = MarkdownService::getList(dirname(__DIR__, 2) . '/content/blog');
            foreach ($posts as $post) {
                $urls[] = [
                    'loc'        => $baseUrl . '/blog/' . $post['slug'],
                    'lastmod'    => date('Y-m-d', $post['date']),
                    'changefreq' => 'monthly',
                    'priority'   => '0.8',
                ];
            }
        }

        // 3. Документація
        $docsMenuPath = dirname(__DIR__, 2) . '/content/docs/menu.json';
        if (file_exists($docsMenuPath)) {
            $docsMenu = json_decode(file_get_contents($docsMenuPath), true) ?? [];
            foreach ($docsMenu as $group) {
                foreach ($group['items'] as $item) {
                    $urls[] = [
                        'loc'        => $baseUrl . '/docs/' . $item['slug'],
                        'lastmod'    => '2025-10-01',
                        'changefreq' => 'monthly',
                        'priority'   => '0.7',
                    ];
                    foreach ($item['tabs'] ?? [] as $tab) {
                        $urls[] = [
                            'loc'        => $baseUrl . '/docs/' . $tab['slug'],
                            'lastmod'    => '2025-10-01',
                            'changefreq' => 'monthly',
                            'priority'   => '0.6',
                        ];
                    }
                }
            }
        }

        // 4. Документація v2
        $docs2MenuPath = dirname(__DIR__, 2) . '/content/docs2/menu.json';
        if (file_exists($docs2MenuPath)) {
            $docs2Menu = json_decode(file_get_contents($docs2MenuPath), true) ?? [];
            foreach ($docs2Menu as $group) {
                foreach ($group['items'] as $item) {
                    $urls[] = [
                        'loc'        => $baseUrl . '/docs2/' . $item['slug'],
                        'lastmod'    => date('Y-m-d'),
                        'changefreq' => 'monthly',
                        'priority'   => '0.7',
                    ];
                    foreach ($item['tabs'] ?? [] as $tab) {
                        $urls[] = [
                            'loc'        => $baseUrl . '/docs2/' . $tab['slug'],
                            'lastmod'    => date('Y-m-d'),
                            'changefreq' => 'monthly',
                            'priority'   => '0.6',
                        ];
                    }
                }
            }
        }

        $imageEntries = array_merge(
            (new DocImageSitemapService())->getEntries($baseUrl),
            (new Docs2ImageSitemapService())->getEntries($baseUrl)
        );
        $imageIndex   = [];
        foreach ($imageEntries as $entry) {
            $imageIndex[$entry['loc']] = $entry['images'];
        }

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        $xml .= '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($url['loc']) . "</loc>\n";
            $xml .= "    <lastmod>" . $url['lastmod'] . "</lastmod>\n";
            $xml .= "    <changefreq>" . $url['changefreq'] . "</changefreq>\n";
            $xml .= "    <priority>" . $url['priority'] . "</priority>\n";
            foreach ($imageIndex[$url['loc']] ?? [] as $img) {
                $xml .= "    <image:image>\n";
                $xml .= "      <image:loc>" . htmlspecialchars($img['loc']) . "</image:loc>\n";
                $xml .= "      <image:title>" . htmlspecialchars($img['title']) . "</image:title>\n";
                $xml .= "    </image:image>\n";
            }
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
