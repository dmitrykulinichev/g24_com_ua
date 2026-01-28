<?php

namespace App\Services;

class RateLimiterService
{
    protected $storagePath;
    protected $maxRequests;
    protected $timeWindow;

    public function __construct()
    {
        $this->storagePath = __DIR__ . '/../../storage/ratelimit';
        $this->maxRequests = (int)($_ENV['RATE_LIMIT_REQUESTS'] ?? 10);
        $this->timeWindow = (int)($_ENV['RATE_LIMIT_TIME'] ?? 60); // в секундах
    }

    /**
     * Перевіряє, чи не перевищено ліміт для даного ключа (IP)
     * @param string $key
     * @return bool - true, якщо ліміт перевищено
     */
    public function isLimitExceeded(string $key): bool
    {
        if (!is_dir($this->storagePath)) {
            mkdir($this->storagePath, 0755, true);
        }

        $filePath = $this->storagePath . '/' . md5($key);
        $currentTime = time();
        
        $requests = [];
        if (file_exists($filePath)) {
            $requests = json_decode(file_get_contents($filePath), true) ?? [];
        }

        // Видаляємо старі записи
        $requests = array_filter($requests, function ($timestamp) use ($currentTime) {
            return ($currentTime - $timestamp) < $this->timeWindow;
        });

        if (count($requests) >= $this->maxRequests) {
            return true; // Ліміт перевищено
        }

        // Додаємо поточний запит
        $requests[] = $currentTime;
        file_put_contents($filePath, json_encode($requests));

        return false;
    }
}