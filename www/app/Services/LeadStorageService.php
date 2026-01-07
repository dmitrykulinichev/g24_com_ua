<?php

namespace App\Services;

class LeadStorageService
{
    protected $storageDir;

    public function __construct()
    {
        $this->storageDir = __DIR__ . '/../../storage/leads';
    }

    public function save($data)
    {
        if (!is_dir($this->storageDir)) {
            mkdir($this->storageDir, 0755, true);
        }

        $filename = date('Y-m-d_H-i-s') . '_' . uniqid() . '.json';
        $path = $this->storageDir . '/' . $filename;

        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        Logger::info("Заявку збережено в файл: $filename");
        
        return $path;
    }
}