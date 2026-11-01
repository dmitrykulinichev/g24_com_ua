<?php

namespace App\Controllers;

use App\Blog\BlogSync;
use App\Services\SpaApiService;
use App\Services\TelegramService;

/**
 * Синхронізація опублікованих статей блогу з СААС у локальний файловий кеш за мовами (app/Blog: BlogSync пише,
 * BlogStore читає). Публічні сторінки блогу читають лише ці файли — синхронізація оновлює їх поза запитом
 * користувача, за розкладом (cron), як SitemapController. Тут — лише HTTP: ключ, блокування від паралельних
 * запусків, запит фіду, захист від порожньої відповіді й сповіщення в Telegram.
 *
 * GET /api/blog/sync/{token}
 */
class BlogSyncController
{
    protected $lockPath;
    protected $lockHandle;
    protected $telegram;

    public function __construct()
    {
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

        try {
            $report = (new BlogSync())->run($posts);
        } catch (\RuntimeException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
            $this->telegram->sendMessage("🔴 <b>Синхронізація блогу — помилка</b>\n" . $e->getMessage());
            return;
        }

        echo json_encode(['success' => true] + $report);

        // Клієнт (крон чи кнопка "Синхронізувати" в адмінці) не чекає ще й на Telegram (до 5с)
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }

        $byLocale = implode(', ', array_map(fn ($l, $n) => "{$l}: {$n}", array_keys($report['synced']), $report['synced']));
        $icon = ($report['write_errors'] > 0 || $report['images_failed'] > 0) ? '🟡' : '🟢';
        $summary = "{$icon} <b>Синхронізація блогу</b>\n"
            . "Статей за мовами — {$byLocale}; видалено: {$report['deleted']}\n"
            . "Картинок: {$report['images_downloaded']} завантажено";
        if ($report['images_failed'] > 0) {
            $summary .= ", {$report['images_failed']} не вдалось";
        }
        if ($report['write_errors'] > 0) {
            $summary .= "\nПомилок запису статей: {$report['write_errors']}";
        }
        $this->telegram->sendMessage($summary);
    }
}
