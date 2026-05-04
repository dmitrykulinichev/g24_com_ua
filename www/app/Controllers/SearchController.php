<?php

namespace App\Controllers;

use App\Services\MarkdownService;

class SearchController
{
    // Пошук по документації
    public function search()
    {
        $this->performSearch('/../../content/docs/*.md', 'docs');
    }

    // Пошук по документації v2
    public function searchDocs2()
    {
        $query = $_GET['q'] ?? '';
        $query = mb_strtolower(trim($query));

        if (strlen($query) < 2) {
            response()->json([]);
            return;
        }

        $menuPath    = __DIR__ . '/../../content/docs2/menu.json';
        $contentPath = __DIR__ . '/../../content/docs2';

        if (!file_exists($menuPath)) {
            response()->json([]);
            return;
        }

        $menu    = json_decode(file_get_contents($menuPath), true) ?? [];
        $results = [];

        foreach ($menu as $group) {
            foreach ($group['items'] as $item) {
                $entries = [['slug' => $item['slug'], 'docFile' => $item['docFile']]];
                foreach ($item['tabs'] ?? [] as $tab) {
                    $entries[] = ['slug' => $tab['slug'], 'docFile' => $tab['docFile']];
                }

                foreach ($entries as $entry) {
                    $relative = preg_replace('/^_data\//', '', $entry['docFile']);
                    $filePath = $contentPath . '/' . $relative;

                    if (!file_exists($filePath)) continue;

                    $data      = MarkdownService::parseFile($filePath);
                    $plainText = strip_tags($data['content']);
                    $plainText = preg_replace('/[#*`_\[\]!]/', '', $plainText);
                    $plainText = preg_replace('/\(screenshots[^)]+\)/', '', $plainText);
                    $plainTextLower = mb_strtolower($plainText);
                    $titleLower     = mb_strtolower($data['meta']['title']);

                    if (strpos($titleLower, $query) !== false) {
                        $results[] = [
                            'title'   => $data['meta']['title'],
                            'slug'    => $entry['slug'],
                            'snippet' => mb_substr($plainText, 0, 100) . '...',
                            'score'   => 10,
                        ];
                        continue;
                    }

                    $pos = strpos($plainTextLower, $query);
                    if ($pos !== false) {
                        $start = max(0, $pos - 50);
                        $results[] = [
                            'title'   => $data['meta']['title'],
                            'slug'    => $entry['slug'],
                            'snippet' => '...' . mb_substr($plainText, $start, 100) . '...',
                            'score'   => 5,
                        ];
                    }
                }
            }
        }

        usort($results, fn($a, $b) => $b['score'] - $a['score']);
        response()->json($results);
    }

    // Пошук по блогу
    public function searchBlog()
    {
        $this->performSearch('/../../content/blog/*.md', 'blog');
    }

    // Універсальний метод пошуку
    private function performSearch($pathPattern, $type)
    {
        $query = $_GET['q'] ?? '';
        $query = mb_strtolower(trim($query));

        if (strlen($query) < 2) {
            response()->json([]);
            return;
        }

        $results = [];
        $files = glob(__DIR__ . $pathPattern);

        foreach ($files as $file) {
            $data = MarkdownService::parseFile($file);
            $slug = basename($file, '.md');
            
            // Чистимо контент
            $plainText = strip_tags($data['content']);
            $plainText = preg_replace('/[#*`_\[\]]/', '', $plainText);
            $plainTextLower = mb_strtolower($plainText);
            $titleLower = mb_strtolower($data['meta']['title']);

            // Шукаємо в заголовку (пріоритет)
            if (strpos($titleLower, $query) !== false) {
                $results[] = [
                    'title' => $data['meta']['title'],
                    'slug' => $slug,
                    'snippet' => mb_substr($plainText, 0, 100) . '...',
                    'score' => 10
                ];
                continue;
            }

            // Шукаємо в тексті
            $pos = strpos($plainTextLower, $query);
            if ($pos !== false) {
                $start = max(0, $pos - 50);
                $length = 100;
                $snippet = '...' . mb_substr($plainText, $start, $length) . '...';
                
                $results[] = [
                    'title' => $data['meta']['title'],
                    'slug' => $slug,
                    'snippet' => $snippet,
                    'score' => 5
                ];
            }
        }

        usort($results, function($a, $b) {
            return $b['score'] - $a['score'];
        });

        response()->json($results);
    }
}