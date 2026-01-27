<?php

namespace App\Services;

class TelegramService
{
    protected $token;
    protected $chatId;

    public function __construct()
    {
        $this->token = $_ENV['TELEGRAM_BOT_TOKEN'] ?? null;
        $this->chatId = $_ENV['TELEGRAM_CHAT_ID'] ?? null;
    }

    public function sendMessage(string $message)
    {
        if (empty($this->token) || empty($this->chatId)) {
            return false;
        }

        try {
            $url = "https://api.telegram.org/bot{$this->token}/sendMessage";
            $data = [
                'chat_id' => $this->chatId,
                'text' => $message,
                'parse_mode' => 'HTML'
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Таймаут 5 сек, щоб не гальмувати сайт
            
            $result = curl_exec($ch);
            curl_close($ch);

            return $result;
        } catch (\Throwable $e) {
            Logger::error('Telegram send error', ['msg' => $e->getMessage()]);
            return false;
        }
    }
}