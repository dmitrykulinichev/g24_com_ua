<?php

namespace App\Controllers;

use App\Services\MarkdownService;

class SearchController
{
    public function search()
    {
        $query = $_GET['q'] ?? '';
        $query = mb_strtolower(trim($query));

        if (strlen($query) < 2) {
            response()->json([]);
            return;
        }

        $results = [];
        $files = glob(__DIR__ . '/../../content/docs/*.md');

        foreach ($files as $file) {
            $data = MarkdownService::parseFile($file);
            $slug = basename($file, '.md');
            
            // Чистимо контент від Markdown тегів для пошуку
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
                // Вирізаємо сніпет навколо знайденого слова
                $start = max(0, $pos - 50);
                $length = 100;
                $snippet = '...' . mb_substr($plainText, $start, $length) . '...';
                
                // Підсвічуємо знайдене (опціонально, можна на фронті)
                
                $results[] = [
                    'title' => $data['meta']['title'],
                    'slug' => $slug,
                    'snippet' => $snippet,
                    'score' => 5
                ];
            }
        }

        // Сортуємо за релевантністю
        usort($results, function($a, $b) {
            return $b['score'] - $a['score'];
        });

        response()->json($results);
    }
}