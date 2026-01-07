<?php

namespace App\Controllers;

use App\Services\MarkdownService;

class PageController
{
    public function apiShow($slug)
    {
        $path = __DIR__ . '/../../content/pages/' . $slug . '.md';
        $data = MarkdownService::parseFile($path);

        if (!$data) {
            response()->json(['error' => 'Page not found'], 404);
            return;
        }

        $htmlContent = MarkdownService::render($data['content']);

        response()->json([
            'title' => $data['meta']['title'],
            'content' => $htmlContent
        ]);
    }
}