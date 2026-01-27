<?php

namespace App\Controllers;

use App\Services\Logger;
use App\Services\SpaApiService;

class LeadController
{
    protected $api;

    public function __construct()
    {
        $this->api = new SpaApiService();
    }

    public function submit()
    {
        try {
            $data = request()->body();
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            
            Logger::info('Отримано нову заявку (Proxy)', ['ip' => $ip, 'email' => $data['email'] ?? 'unknown']);

            // Формуємо payload згідно з новою документацією
            $payload = [
                'email'       => $data['email'] ?? '',
                'name'        => $data['name'] ?? '',
                'phone'       => $data['phone'] ?? '',
                // Згідно з документацією (приклад JS), передаємо 'general'
                // Якщо потрібно 'enterprise', можна змінити тут
                'type'        => 'general', 
                'message'     => $this->generateMessage($data),
                'g-recaptcha-response' => $data['g-recaptcha-response'] ?? '',
                'ip'          => $ip,
                'user_agent'  => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            ];

            // Відправляємо на SPA API
            $response = $this->api->sendLead($payload);

            // Логуємо помилки сервера
            if ($response['status'] >= 500) {
                Logger::error('SPA API Error (Lead)', ['status' => $response['status'], 'body' => $response['raw_body']]);
            }

            // Повертаємо відповідь фронтенду
            response()->json($response['body'], $response['status']);

        } catch (\Throwable $e) {
            Logger::error('Критична помилка в LeadController', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            response()->json(['message' => 'Server Error'], 500);
        }
    }

    private function generateMessage($data)
    {
        // Якщо фронтенд передав повідомлення — використовуємо його
        if (!empty($data['message'])) {
            return $data['message'];
        }

        // Інакше генеруємо на основі типу
        $type = $data['plan'] ?? 'general';
        $company = $data['company'] ?? '';

        $msg = "Заявка з лендінгу. Тип: " . ucfirst($type) . ".";
        if ($company) {
            $msg .= " Компанія: " . $company . ".";
        }
        
        return $msg;
    }
}