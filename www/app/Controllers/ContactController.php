<?php

namespace App\Controllers;

use App\Services\Logger;
use App\Services\TelegramService;
use App\Services\CrmService;
use App\Services\LeadStorageService;

class ContactController
{
    protected $telegram;
    protected $crm;
    protected $storage;

    public function __construct()
    {
        // У повноцінному Leaf MVC це робиться через Dependency Injection,
        // але тут ми просто створюємо екземпляри вручну.
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

            // 1. Валідація
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

            // 2. Обробка заявки (через сервіси)
            $this->storage->save($data);
            $this->crm->sendLead($data);
            $this->telegram->sendLead($data);

            response()->json(['status' => 'success', 'message' => 'Заявку прийнято!']);

        } catch (\Throwable $e) {
            Logger::error('Критична помилка в ContactController', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            response()->json(['status' => 'error', 'message' => 'Server Error'], 500);
        }
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