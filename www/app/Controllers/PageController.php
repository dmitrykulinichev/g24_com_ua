<?php

namespace App\Controllers;

use App\Services\MarkdownService;

class PageController
{
    protected $blade;

    public function __construct()
    {
        $this->blade = new \Jenssegers\Blade\Blade(__DIR__ . '/../../views', __DIR__ . '/../../storage/cache');
    }

    public function showPrivacy() { $this->show('privacy'); }
    public function showTerms()   { $this->show('terms'); }
    public function showOffer()   { $this->show('offer'); }

    public function show($slug)
    {
        $path = __DIR__ . '/../../content/pages/' . $slug . '.md';
        $data = MarkdownService::parseFile($path);

        if (!$data) {
            http_response_code(404);
            echo $this->blade->make('errors.404')->render();
            return;
        }

        $title = $data['meta']['title'] ?? $slug;
        $content = MarkdownService::render($data['content']);

        $meta = [
            'title' => $title,
            'description' => '',
        ];

        echo $this->blade->make('page', compact('meta', 'title', 'content'))->render();
    }

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
