<?php

namespace App\Controllers;

use App\Services\Logger;
use App\Services\TelegramService;
use App\Services\CrmService;
use App\Services\LeadStorageService;

class LeadController
{
    protected $telegram;
    protected $crm;
    protected $storage;

    public function __construct()
    {
        $this->telegram = new TelegramService();
        $this->crm = new CrmService();
        $this->storage = new LeadStorageService();
    }

    public function submit()
    {
        try {
            $data = request()->body();
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            
            Logger::info('Отримано нову заявку', ['ip' => $ip]);

            // 1. Перевірка reCAPTCHA
            if (!$this->verifyRecaptcha($data['g-recaptcha-response'] ?? null)) {
                Logger::info('Помилка reCAPTCHA', ['ip' => $ip]);
                response()->json(['status' => 'error', 'message' => 'Будь ласка, підтвердіть, що ви не робот.'], 422);
                return;
            }

            // 2. Валідація
            $errors = $this->validate($data);
            if (!empty($errors)) {
                Logger::info('Помилка валідації', $errors);
                response()->json(['status' => 'error', 'errors' => $errors], 422);
                return;
            }

            // Збагачення даних
            $data['ip'] = $ip;
            $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['plan'] = $data['plan'] ?? '-';

            // 3. Обробка заявки
            $this->storage->save($data);
            $this->crm->sendLead($data);
            $this->telegram->sendLead($data);

            response()->json(['status' => 'success', 'message' => 'Заявку прийнято!']);

        } catch (\Throwable $e) {
            Logger::error('Критична помилка в LeadController', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            response()->json(['status' => 'error', 'message' => 'Server Error'], 500);
        }
    }

    private function verifyRecaptcha($token)
    {
        $secret = $_ENV['RECAPTCHA_SECRET_KEY'] ?? null;
        
        if (!$secret || $secret === 'YOUR_SECRET_KEY') {
            return true;
        }

        if (!$token) return false;

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => $secret,
            'response' => $token,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? null
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $json = json_decode($result, true);

        return $json['success'] ?? false;
    }

    private function validate($data)
    {
        $errors = [];
        
        if (empty($data['name']) || strlen($data['name']) < 2) {
            $errors['name'] = 'Ім\'я має містити мінімум 2 символи';
        }
        
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Введіть коректний Email';
        }
        
        if (empty($data['phone']) || strlen($data['phone']) < 10) {
            $errors['phone'] = 'Введіть коректний номер телефону';
        }

        return $errors;
    }
}