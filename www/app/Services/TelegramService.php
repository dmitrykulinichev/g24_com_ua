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

    public function sendLead($data)
    {
        if (!$this->token || !$this->chatId) {
            Logger::info('Telegram не налаштовано (відсутній токен або chat_id)');
            return false;
        }

        $message = "🚀 *Нова заявка Garage24*\n\n";
        $message .= "👤 Ім'я: " . $data['name'] . "\n";
        $message .= "📧 Email: " . ($data['email'] ?? '-') . "\n";
        $message .= "🏢 Компанія: " . ($data['company'] ?? '-') . "\n";
        $message .= "📞 Телефон: " . $data['phone'] . "\n";
        
        if (!empty($data['plan'])) {
            $message .= "📦 Тариф: *" . $data['plan'] . "*";
        }

        return $this->sendMessage($message);
    }

    protected function sendMessage($text)
    {
        $url = "https://api.telegram.org/bot{$this->token}/sendMessage";
        $params = [
            'chat_id' => $this->chatId,
            'text' => $text,
            'parse_mode' => 'Markdown'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            Logger::error('Помилка з\'єднання з Telegram', ['error' => curl_error($ch)]);
            curl_close($ch);
            return false;
        } 
        
        if ($httpCode >= 400) {
            Logger::error("Помилка API Telegram ($httpCode)", ['response' => $response]);
            curl_close($ch);
            return false;
        }

        Logger::info("Успішно відправлено в Telegram");
        curl_close($ch);
        return true;
    }
}