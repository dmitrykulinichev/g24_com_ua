<?php

namespace App\Controllers;

use App\Services\Logger;
use App\Services\SpaApiService;

class RegistrationController
{
    protected $api;

    public function __construct()
    {
        $this->api = new SpaApiService();
    }

    public function getConfig()
    {
        try {
            $response = $this->api->getLandingConfig();
            response()->json($response['body'], $response['status']);
        } catch (\Throwable $e) {
            Logger::error('Error fetching config', ['message' => $e->getMessage()]);
            response()->json(['status' => 'error', 'message' => 'Service Unavailable'], 503);
        }
    }

    public function register()
    {
        try {
            $data = request()->body();
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

            Logger::info('Спроба реєстрації (Proxy)', ['ip' => $ip, 'email' => $data['owner_email'] ?? 'unknown']);

            $payload = [
                'park_name'   => $data['park_name'] ?? '',
                'owner_name'  => $data['owner_name'] ?? '',
                'owner_email' => $data['owner_email'] ?? '',
                'phone'       => $data['phone'] ?? '',
                'plan'        => $data['plan'] ?? 'monthly',
                'g-recaptcha-response' => $data['g-recaptcha-response'] ?? '',
                'ip'          => $ip,
                'user_agent'  => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            ];
            
            $response = $this->api->registerTenant($payload);

            if ($response['status'] >= 500) {
                Logger::error('SPA API Error', ['status' => $response['status'], 'body' => $response['raw_body']]);
            }

            response()->json($response['body'], $response['status']);

        } catch (\Throwable $e) {
            Logger::error('Критична помилка в RegistrationController', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            response()->json(['message' => 'Server Error'], 500);
        }
    }
}