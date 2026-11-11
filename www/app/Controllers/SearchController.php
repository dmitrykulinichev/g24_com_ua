<?php

namespace App\Controllers;

use App\Services\MarkdownService;

class SearchController
{
    // Пошук по документації (без посібника водія — у нього свій пошук)
    public function searchDocs2()
    {
        $this->searchSection(\App\Docs\DocsStore::docs());
    }

    // Пошук по посібнику водія (/driver)
    public function searchDriver()
    {
        $this->searchSection(\App\Docs\DocsStore::driver());
    }

    // Пошук у межах одного розділу (DocsStore): сторінки мовою запиту (X-Locale), якщо є переклад, інакше українські
    private function searchSection(\App\Docs\DocsStore $store)
    {
        $query = $_GET['q'] ?? '';
        $query = mb_strtolower(trim($query));

        if (strlen($query) < 2) {
            response()->json([]);
            return;
        }

        $results = [];

        foreach ($store->pages() as $entry) {
            $filePath = $store->file($entry['docFile'], $store->contentLocale($entry['slug']));

            $data      = MarkdownService::parseFile($filePath);
            if (!$data) continue;
            $plainText = strip_tags($data['content']);
            $plainText = preg_replace('/[#*`_\[\]!]/', '', $plainText);
            $plainText = preg_replace('/\((?:screenshots|screen:)[^)]+\)/', '', $plainText);
            $plainTextLower = mb_strtolower($plainText);
            $titleLower     = mb_strtolower($data['meta']['title'] ?? '');

            if (strpos($titleLower, $query) !== false) {
                $results[] = [
                    'title'   => $data['meta']['title'],
                    'slug'    => $entry['slug'],
                    'snippet' => mb_substr($plainText, 0, 100) . '...',
                    'score'   => 10,
                ];
                continue;
            }

            $pos = mb_strpos($plainTextLower, $query);
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

        usort($results, fn($a, $b) => $b['score'] - $a['score']);
        response()->json($results);
    }

    // Пошук по блогу
    public function searchBlog()
    {
        // Шукаємо серед статей мови сторінки (немає статей цією мовою — українських)
        $this->performSearch('/../../content/blog/' . \App\Blog\BlogStore::contentLocale('/blog') . '/*.md', 'blog');
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
            // Файл статті — «<дата>-<slug>.md»; адреса статті — без дати
            $slug = preg_replace('/^\d{4}-\d{2}-\d{2}-/', '', basename($file, '.md'));
            
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