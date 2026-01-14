<?php

namespace App\Services;

use Parsedown;

class MarkdownService
{
    /**
     * Парсить один файл і повертає мета-дані та контент
     */
    public static function parseFile($path)
    {
        if (!file_exists($path)) return null;

        $rawContent = file_get_contents($path);
        $meta = [];
        $content = $rawContent;

        // Front Matter
        if (preg_match('/^---\s*\r?\n(.*?)\r?\n---\s*\r?\n/s', $rawContent, $matches)) {
            $frontMatter = $matches[1];
            $content = substr($rawContent, strlen($matches[0]));

            $lines = explode("\n", $frontMatter);
            foreach ($lines as $line) {
                $parts = explode(':', $line, 2);
                if (count($parts) == 2) {
                    $meta[trim($parts[0])] = trim($parts[1]);
                }
            }
        }

        // Title fallback
        if (!isset($meta['title'])) {
            if (preg_match('/^#\s*(.+)$/m', $content, $h1Matches)) {
                $meta['title'] = trim($h1Matches[1]);
            } else {
                $meta['title'] = ucfirst(basename($path, '.md'));
            }
        }

        // Date fallback
        if (!isset($meta['date'])) {
            $meta['date'] = filemtime($path);
        } else {
            $meta['date'] = strtotime($meta['date']);
        }

        // Description fallback
        if (!isset($meta['description'])) {
            $cleanText = preg_replace('/^#.*$/m', '', $content);
            $cleanText = preg_replace('/!\[.*?\]\(.*?\)/', '', $cleanText);
            $meta['description'] = mb_substr(trim(strip_tags($cleanText)), 0, 160) . '...';
        }

        return ['meta' => $meta, 'content' => $content];
    }

    /**
     * Отримує список всіх статей з папки
     */
    public static function getList($folder)
    {
        $files = glob($folder . '/*.md');
        $items = [];
        
        foreach ($files as $file) {
            $data = self::parseFile($file);
            $slug = basename($file, '.md');
            
            $items[] = [
                'slug' => $slug,
                'title' => $data['meta']['title'],
                'date' => $data['meta']['date'],
                'preview' => $data['meta']['description'],
                'image' => $data['meta']['image'] ?? null
            ];
        }
        
        // Сортування за датою (нові зверху)
        usort($items, function($a, $b) {
            return $b['date'] - $a['date'];
        });
        
        return $items;
    }

    /**
     * Обробляє шорткоди перед рендерингом
     */
    protected static function processShortcodes($text)
    {
        // Шорткод для скріншотів: {{screenshot file="image.png" title="Caption"}}
        $text = preg_replace_callback('/\{\{screenshot\s+file="([^"]+)"\s*(?:title="([^"]+)")?\}\}/', function ($matches) {
            $file = $matches[1];
            $title = $matches[2] ?? 'Screenshot';
            
            // Формуємо шляхи
            $desktopPath = "/assets/img/docs/{$file}";
            $mobilePath = "/assets/img/docs/mobile/{$file}";
            
            return <<<HTML
<div class="screenshot-container">
    <div class="screenshot-header">
        <div class="screenshot-dots">
            <div class="dot dot-red"></div>
            <div class="dot dot-yellow"></div>
            <div class="dot dot-green"></div>
        </div>
        <div class="screenshot-title">{$title}</div>
    </div>
    <div class="screenshot-content">
        <picture>
            <source media="(max-width: 767px)" srcset="{$mobilePath}">
            <img src="{$desktopPath}" alt="{$title}">
        </picture>
    </div>
</div>
HTML;
        }, $text);

        return $text;
    }

    /**
     * Рендерить Markdown в HTML
     */
    public static function render($content)
    {
        // Спочатку обробляємо шорткоди
        $content = self::processShortcodes($content);

        $Parsedown = new Parsedown();
        return $Parsedown->text($content);
    }
}