<?php

namespace App\Controllers;

use App\Services\Logger;
use App\Services\SpaApiService;
use App\Services\LeadStorageService;
use App\Services\TelegramService;

class LeadController
{
    protected $api;
    protected $storage;
    protected $telegram;

    public function __construct()
    {
        $this->api = new SpaApiService();
        $this->storage = new LeadStorageService();
        $this->telegram = new TelegramService();
    }

    public function submit()
    {
        try {
            $data = request()->body();
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            
            Logger::info('Отримано нову заявку (Proxy)', ['ip' => $ip, 'email' => $data['email'] ?? 'unknown']);

            $type = $data['type'] ?? $data['plan'] ?? 'general';

            $payload = [
                'email'       => $data['email'] ?? '',
                'name'        => $data['name'] ?? '',
                'phone'       => $data['phone'] ?? '',
                'type'        => $type,
                'message'     => $this->generateMessage($data, $type),
                'g-recaptcha-response' => $data['g-recaptcha-response'] ?? '',
                'ip'          => $ip,
                'user_agent'  => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            ];

            // 1. Зберігаємо локально
            $this->storage->save($payload, $type);

            // 2. Відправляємо в Telegram (Резерв)
            $this->sendToTelegram($payload);

            // 3. Відправляємо на SPA API
            $response = $this->api->sendLead($payload);

            if ($response['status'] >= 500) {
                Logger::error('SPA API Error (Lead)', ['status' => $response['status'], 'body' => $response['raw_body']]);
            }

            response()->json($response['body'], $response['status']);

        } catch (\Throwable $e) {
            Logger::error('Критична помилка в LeadController', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            response()->json(['message' => 'Server Error'], 500);
        }
    }

    private function generateMessage($data, $type)
    {
        if (!empty($data['message'])) {
            return $data['message'];
        }

        $company = $data['company'] ?? '';
        $msg = "Заявка з лендінгу. Тип: " . ucfirst($type) . ".";
        if ($company) {
            $msg .= " Компанія: " . $company . ".";
        }
        
        return $msg;
    }

    private function sendToTelegram($data)
    {
        $msg = "🔔 <b>Нова заявка ({$data['type']})</b>\n\n";
        $msg .= "👤 Ім'я: " . ($data['name'] ?: '-') . "\n";
        $msg .= "📧 Email: " . ($data['email'] ?: '-') . "\n";
        $msg .= "📱 Телефон: " . ($data['phone'] ?: '-') . "\n";
        
        if (!empty($data['message'])) {
            $msg .= "💬 Повідомлення: " . $data['message'] . "\n";
        }
        
        $msg .= "\n🌍 IP: " . $data['ip'];

        $this->telegram->sendMessage($msg);
    }
}