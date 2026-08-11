<?php

namespace App\Controllers;

use App\Services\SpaApiService;
use App\Services\TelegramService;

/**
 * Синхронізація опублікованих статей блогу з СААС у локальний файловий кеш
 * (content/blog/*.md + menu.json + assets/img/blog/synced/). Публічні сторінки
 * блогу (BlogController) як читали ці файли, так і читають — синхронізація
 * лише оновлює їх поза запитом користувача, за розкладом (cron), за аналогією
 * з SitemapController.
 *
 * Фаза 2 (картинки): обкладинка та вбудовані в markdown-контент зображення,
 * якщо вони абсолютні URL (завантажені через адмінку СААС), скачуються
 * локально й посилання переписуються на локальний шлях — щоб публічні
 * сторінки блогу не залежали від доступності СААС для показу картинок.
 * Зображення, вказані як локальний шлях вручну (старі статті), не чіпаються.
 *
 * GET /api/blog/sync?key=...
 */
class BlogSyncController
{
    protected $contentPath;
    protected $menuPath;
    protected $imagesPath;
    protected $lockPath;
    protected $imagesUrlPrefix = '/assets/img/blog/synced';
    protected $lockHandle;
    protected $telegram;

    public function __construct()
    {
        $root = dirname(__DIR__, 2);
        $this->contentPath = $root . '/content/blog';
        $this->menuPath = $this->contentPath . '/menu.json';
        $this->imagesPath = $root . '/assets/img/blog/synced';
        $this->lockPath = sys_get_temp_dir() . '/g24_blog_sync.lock';
        $this->telegram = new TelegramService();
    }

    /**
     * @param string $token Секрет — частина самого шляху (не query-параметр),
     *   довгий випадковий рядок (BLOG_SYNC_KEY). Той самий роут одночасно і
     *   продакшн-крон, і "тестовий" — можна смикнути вручну будь-коли, звіт
     *   синхронізації повертається одразу в відповіді (JSON).
     */
    public function sync($token = null)
    {
        $expectedKey = $_ENV['BLOG_SYNC_KEY'] ?? null;

        if (!$expectedKey || !hash_equals($expectedKey, $token ?? '')) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Forbidden']);
            return;
        }

        header('Content-Type: application/json');

        // Захист від накладання паралельних запусків (довгий цикл — качання картинок
        // по 15с кожна — теоретично може не вкластись у інтервал крону).
        $this->lockHandle = fopen($this->lockPath, 'c');
        if (!$this->lockHandle || !flock($this->lockHandle, LOCK_EX | LOCK_NB)) {
            http_response_code(423);
            echo json_encode(['error' => 'Sync already running']);
            $this->telegram->sendMessage("⏳ <b>Синхронізація блогу</b>\nПопередній запуск ще триває — цей пропущено.");
            return;
        }

        try {
            $this->runSync();
        } finally {
            flock($this->lockHandle, LOCK_UN);
            fclose($this->lockHandle);
        }
    }

    private function runSync(): void
    {
        try {
            $api = new SpaApiService();
            $response = $api->getBlogFeed();
        } catch (\Exception $e) {
            http_response_code(502);
            echo json_encode(['error' => 'SPA API connection failed: ' . $e->getMessage()]);
            $this->telegram->sendMessage(
                "🔴 <b>Синхронізація блогу — помилка</b>\nНе вдалось з'єднатись із СААС: " . $e->getMessage()
            );
            return;
        }

        if (($response['status'] ?? 0) !== 200) {
            // Не чіпаємо локальний кеш, якщо СААС недоступний/помилка —
            // лендінг і далі роздає те, що вже синхронізовано раніше.
            http_response_code(502);
            echo json_encode([
                'error' => 'SPA API returned non-200 status',
                'status' => $response['status'] ?? null,
            ]);
            $this->telegram->sendMessage(
                "🔴 <b>Синхронізація блогу — помилка</b>\nСААС повернув статус " . ($response['status'] ?? '?') . ". Локальний кеш не змінено."
            );
            return;
        }

        $posts = $response['body']['data'] ?? [];

        if (empty($posts)) {
            // Порожній список при статусі 200 — швидше ознака бага/збою на СААС,
            // ніж реального "видалили геть усі статті". Не чіпаємо локальний
            // кеш і не видаляємо все — краще застаріла версія, ніж пустий блог.
            echo json_encode([
                'success' => false,
                'warning' => 'Empty post list from SPA API — local cache left untouched',
            ]);
            $this->telegram->sendMessage(
                "🟡 <b>Синхронізація блогу — попередження</b>\nСААС повернув порожній список статей при статусі 200. Локальний кеш НЕ чіпали (захист від випадкового спорожнення блогу)."
            );
            return;
        }

        if (!is_dir($this->contentPath) && !mkdir($this->contentPath, 0755, true) && !is_dir($this->contentPath)) {
            http_response_code(500);
            echo json_encode(['error' => 'Cannot create content/blog directory']);
            $this->telegram->sendMessage("🔴 <b>Синхронізація блогу — помилка</b>\nНе вдалось створити директорію content/blog.");
            return;
        }
        if (!is_dir($this->imagesPath) && !mkdir($this->imagesPath, 0755, true) && !is_dir($this->imagesPath)) {
            http_response_code(500);
            echo json_encode(['error' => 'Cannot create assets/img/blog/synced directory']);
            $this->telegram->sendMessage("🔴 <b>Синхронізація блогу — помилка</b>\nНе вдалось створити директорію assets/img/blog/synced.");
            return;
        }

        $keepFiles = [];
        $keepSlugs = [];
        $menu = [];
        $written = 0;
        $writeErrors = 0;
        $imagesDownloaded = 0;
        $imagesFailed = 0;

        foreach ($posts as $post) {
            $slug = $post['slug'] ?? null;
            $title = $post['title'] ?? null;
            if (!$slug || !$title || !preg_match('/^[a-z0-9]+(-[a-z0-9]+)*$/i', $slug)) {
                // Невалідний slug не довіряємо взагалі — він потім іде у шляхи
                // файлів/директорій (mkdir/file_put_contents), тож приймаємо
                // лише "безпечний" алфавіт замість довіряти validaції на СААС.
                $writeErrors++;
                continue;
            }

            $keepSlugs[] = $slug;

            $coverImage = $post['cover_image'] ?? null;
            $localCover = $this->syncImage($coverImage, $slug, 'cover', $imagesDownloaded, $imagesFailed);

            $content = $post['content'] ?? '';
            $content = $this->syncInlineImages($content, $slug, $imagesDownloaded, $imagesFailed);

            $date = $post['published_at'] ?? date('Y-m-d');
            $filename = $date . '-' . $slug . '.md';

            $body = "# {$title}\n\n" . rtrim($content) . "\n";
            $bytesWritten = file_put_contents($this->contentPath . '/' . $filename, $body);

            if ($bytesWritten === false) {
                // Не записалось (диск повний, права тощо) — НЕ додаємо в keepFiles
                // і НЕ додаємо в menu.json, щоб не показувати посилання на статтю,
                // якої фактично немає на диску.
                $writeErrors++;
                continue;
            }

            $keepFiles[] = $filename;
            $written++;

            $menu[] = [
                'slug' => $slug,
                'title' => $title,
                'date' => $date,
                'preview' => $post['excerpt'] ?? '',
                'image' => $localCover ?? $coverImage,
                'tags' => $post['tags'] ?? [],
            ];
        }

        // Видаляємо файли статей, знятих з публікації/видалених у СААС.
        $deleted = 0;
        $existing = glob($this->contentPath . '/*.md') ?: [];
        foreach ($existing as $file) {
            if (!in_array(basename($file), $keepFiles, true)) {
                unlink($file);
                $deleted++;
            }
        }

        // Видаляємо локальні картинки статей, знятих з публікації.
        $existingImageDirs = glob($this->imagesPath . '/*', GLOB_ONLYDIR) ?: [];
        foreach ($existingImageDirs as $dir) {
            if (!in_array(basename($dir), $keepSlugs, true)) {
                $this->removeDirectory($dir);
            }
        }

        file_put_contents($this->menuPath, json_encode($menu, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n");

        echo json_encode([
            'success' => true,
            'synced' => $written,
            'write_errors' => $writeErrors,
            'deleted' => $deleted,
            'images_downloaded' => $imagesDownloaded,
            'images_failed' => $imagesFailed,
        ]);

        // Клієнт (крон чи кнопка "Синхронізувати" в адмінці) не повинен чекати
        // ще й на Telegram (до 5с) — якщо середовище дозволяє (PHP-FPM),
        // завершуємо HTTP-відповідь тут і шлемо повідомлення вже "у фоні"
        // того самого процесу.
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }

        $icon = ($writeErrors > 0 || $imagesFailed > 0) ? '🟡' : '🟢';
        $summary = "{$icon} <b>Синхронізація блогу</b>\n"
            . "Статей: {$written}, видалено: {$deleted}\n"
            . "Картинок: {$imagesDownloaded} завантажено";
        if ($imagesFailed > 0) {
            $summary .= ", {$imagesFailed} не вдалось";
        }
        if ($writeErrors > 0) {
            $summary .= "\nПомилок запису статей: {$writeErrors}";
        }
        $this->telegram->sendMessage($summary);
    }

    /**
     * Завантажує вбудовані в markdown картинки (![alt](https://...)), лишає
     * локальні/відносні посилання як є.
     */
    private function syncInlineImages(string $content, string $slug, int &$downloaded, int &$failed): string
    {
        $index = 0;

        return preg_replace_callback(
            '/!\[([^\]]*)\]\((https?:\/\/[^\)\s]+)\)/',
            function ($matches) use ($slug, &$index, &$downloaded, &$failed) {
                $index++;
                $alt = $matches[1];
                $url = $matches[2];
                $local = $this->syncImage($url, $slug, 'inline-' . $index, $downloaded, $failed);
                $finalUrl = $local ?? $url;
                return "![{$alt}]({$finalUrl})";
            },
            $content
        );
    }

    /**
     * Завантажує одну картинку за абсолютним URL у локальний кеш.
     * Локальні/відносні шляхи (старі статті з ручним `/assets/...`) не чіпає —
     * повертає null, і виклик лишає оригінальне значення.
     */
    private function syncImage(?string $url, string $slug, string $name, int &$downloaded, int &$failed): ?string
    {
        if (!$url || !preg_match('#^https?://#i', $url)) {
            return null;
        }

        if (!$this->isSafeExternalUrl($url)) {
            $failed++;
            return null;
        }

        $ext = pathinfo(parse_url($url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION);
        $ext = $ext && preg_match('/^[a-zA-Z0-9]{2,5}$/', $ext) ? strtolower($ext) : 'jpg';

        $dir = $this->imagesPath . '/' . $slug;
        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            $failed++;
            return null;
        }

        $localFile = $dir . '/' . $name . '.' . $ext;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        // НЕ дозволяємо редіректи: інакше перевірка isSafeExternalUrl() на
        // вихідний хост не захищає від SSRF через 30x на приватну адресу.
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        $data = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error || $httpCode !== 200 || !$data) {
            $failed++;
            return null;
        }

        if (file_put_contents($localFile, $data) === false) {
            $failed++;
            return null;
        }

        $downloaded++;

        return $this->imagesUrlPrefix . '/' . $slug . '/' . $name . '.' . $ext;
    }

    /**
     * Захист від SSRF: якщо хтось (скомпрометований чи помилковий адмінський
     * акаунт у СААС) вставить у контент статті посилання на внутрішню адресу
     * (наприклад, cloud metadata 169.254.169.254 чи внутрішній сервіс) —
     * сервер лендінгу не повинен ходити туди за завданням "синхронізувати
     * картинку" й публікувати відповідь на публічному сайті.
     */
    private function isSafeExternalUrl(string $url): bool
    {
        $host = parse_url($url, PHP_URL_HOST);
        if (!$host) {
            return false;
        }

        $ips = [];
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            $ips[] = $host;
        } else {
            $resolved = @gethostbynamel($host);
            if (!$resolved) {
                return false;
            }
            $ips = $resolved;
        }

        foreach ($ips as $ip) {
            $isPublic = filter_var(
                $ip,
                FILTER_VALIDATE_IP,
                FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
            );
            if ($isPublic === false) {
                return false;
            }
        }

        return true;
    }

    private function removeDirectory(string $dir): void
    {
        $items = glob($dir . '/*') ?: [];
        foreach ($items as $item) {
            is_dir($item) ? $this->removeDirectory($item) : unlink($item);
        }
        rmdir($dir);
    }
}
