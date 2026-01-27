<?php

namespace App\Services;

use App\Services\Logger;

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
            Logger::warning('Telegram credentials missing', [
                'token_exists' => !empty($this->token),
                'chat_id_exists' => !empty($this->chatId)
            ]);
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
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            // Додаємо перевірку SSL (для локалки можна вимкнути, але краще залишити)
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
            
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            
            curl_close($ch);

            if ($error) {
                Logger::error('Telegram Curl Error', ['error' => $error]);
                return false;
            }

            if ($httpCode >= 400) {
                Logger::error('Telegram API Error', ['code' => $httpCode, 'response' => $result]);
                return false;
            }

            Logger::info('Telegram message sent', ['chat_id' => $this->chatId]);
            return $result;

        } catch (\Throwable $e) {
            Logger::error('Telegram send exception', ['msg' => $e->getMessage()]);
            return false;
        }
    }
}