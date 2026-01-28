<?php

namespace App\Services;

class SpaApiService
{
    protected $baseUrl;
    protected $apiKey;
    protected $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim($_ENV['SPA_API_URL'] ?? 'https://app.g24.com.ua', '/');
        $this->apiKey = $_ENV['LANDING_API_KEY'] ?? '';
        $this->timeout = 20;
    }

    /**
     * Отримання конфігурації для лендінгу
     */
    public function getLandingConfig()
    {
        return $this->get('/api/v1/public/landing/config');
    }

    /**
     * Реєстрація нового парку
     */
    public function registerTenant(array $data)
    {
        return $this->post('/api/v1/public/landing/register', $data);
    }

    /**
     * Повторна відправка листа активації
     */
    public function resendActivation(array $data)
    {
        return $this->post('/api/v1/public/landing/resend-activation', $data);
    }

    /**
     * Перевірка статусу користувача
     */
    public function checkStatus(array $data)
    {
        return $this->post('/api/v1/public/landing/check-status', $data);
    }

    /**
     * Відправка ліда (Enterprise, Консультація)
     */
    public function sendLead(array $data)
    {
        return $this->post('/api/v1/public/landing/lead', $data);
    }

    // --- Базові методи ---

    protected function get(string $endpoint, array $params = [])
    {
        $url = $this->buildUrl($endpoint);
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        return $this->request('GET', $url);
    }

    protected function post(string $endpoint, array $data = [])
    {
        return $this->request('POST', $this->buildUrl($endpoint), $data);
    }

    protected function request(string $method, string $url, array $data = [])
    {
        $ch = curl_init($url);
        
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'X-Landing-Api-Key: ' . $this->apiKey
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } elseif ($method === 'GET') {
            curl_setopt($ch, CURLOPT_HTTPGET, true);
        }

        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);

        if ($error) {
            throw new \Exception('SPA API Connection Error: ' . $error);
        }

        return [
            'status' => $httpCode,
            'body' => json_decode($result, true) ?? [],
            'raw_body' => $result
        ];
    }

    protected function buildUrl($endpoint)
    {
        $endpoint = '/' . ltrim($endpoint, '/');
        return $this->baseUrl . $endpoint;
    }
}