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