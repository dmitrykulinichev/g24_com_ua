<?php

namespace App\Services;

use App\Services\Logger;

class LeadStorageService
{
    protected $baseStoragePath;

    public function __construct()
    {
        $this->baseStoragePath = __DIR__ . '/../../storage/leads';
    }

    public function save(array $data, string $type = 'lead')
    {
        try {
            $targetDir = $this->baseStoragePath . '/' . $type;

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            $date = date('Y-m-d');
            $time = date('H-i-s');
            
            $identifier = $data['email'] ?? ($data['owner_email'] ?? ($data['phone'] ?? 'no-id'));
            $safeId = preg_replace('/[^a-z0-9@\.\-\+]/', '_', $identifier);
            
            $filename = "{$date}_{$time}_{$safeId}.json";
            
            $data['saved_at'] = date('Y-m-d H:i:s');
            $data['source_type'] = $type;
            
            // Зберігаємо ім'я файлу в масиві, щоб потім легко оновити
            $data['_filename'] = $filename;
            
            file_put_contents(
                $targetDir . '/' . $filename,
                json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );
            
            return $filename; // Повертаємо ім'я файлу
        } catch (\Throwable $e) {
            Logger::error('Failed to save lead locally', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function updateWithResponse(string $filename, string $type, array $response)
    {
        try {
            $targetDir = $this->baseStoragePath . '/' . $type;
            $filePath = $targetDir . '/' . $filename;

            if (!file_exists($filePath)) {
                return false;
            }

            $data = json_decode(file_get_contents($filePath), true);
            
            // Додаємо відповідь API
            $data['api_response'] = [
                'received_at' => date('Y-m-d H:i:s'),
                'status' => $response['status'] ?? 'unknown',
                'body' => $response['body'] ?? [],
                'raw_body' => $response['raw_body'] ?? '' // На випадок, якщо JSON не розпарсився
            ];

            file_put_contents(
                $filePath,
                json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );

            return true;
        } catch (\Throwable $e) {
            Logger::error('Failed to update lead with response', ['error' => $e->getMessage()]);
            return false;
        }
    }
}