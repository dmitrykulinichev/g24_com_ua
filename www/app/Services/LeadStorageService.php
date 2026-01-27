<?php

namespace App\Services;

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
            // Визначаємо підпапку на основі типу
            // register, lead, contact
            $targetDir = $this->baseStoragePath . '/' . $type;

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            $date = date('Y-m-d');
            $time = date('H-i-s');
            
            // Визначаємо ідентифікатор (email або телефон)
            $identifier = $data['email'] ?? ($data['owner_email'] ?? ($data['phone'] ?? 'no-id'));
            $safeId = preg_replace('/[^a-z0-9@\.\-\+]/', '_', $identifier);
            
            $filename = "{$date}_{$time}_{$safeId}.json";
            
            $data['saved_at'] = date('Y-m-d H:i:s');
            $data['source_type'] = $type;
            
            file_put_contents(
                $targetDir . '/' . $filename,
                json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );
            
            return true;
        } catch (\Throwable $e) {
            Logger::error('Failed to save lead locally', ['error' => $e->getMessage()]);
            return false;
        }
    }
}