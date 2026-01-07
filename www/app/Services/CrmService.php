<?php

namespace App\Services;

class CrmService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = $_ENV['MAIN_CRM_URL'] ?? null;
        $this->apiKey = $_ENV['MAIN_CRM_KEY'] ?? null;
    }

    public function sendLead($data)
    {
        if (!$this->apiUrl) {
            Logger::info('CRM URL не налаштовано, пропускаємо відправку');
            return false;
        }

        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            Logger::error('Помилка cURL при відправці в CRM', ['error' => curl_error($ch)]);
            curl_close($ch);
            return false;
        } 
        
        Logger::info("Відповідь CRM: $httpCode", ['response' => $response]);
        curl_close($ch);

        return $httpCode >= 200 && $httpCode < 300;
    }
}