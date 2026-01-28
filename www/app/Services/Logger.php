<?php

namespace App\Services;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;

class Logger
{
    private static $logger;

    private static function getLogger()
    {
        if (!self::$logger) {
            // Створюємо канал логування 'app'
            self::$logger = new MonologLogger('app');
            
            $logFile = __DIR__ . '/../../storage/logs/app.log';
            $maxFiles = (int)($_ENV['LOG_MAX_FILES'] ?? 14);
            
            // Налаштовуємо формат: [Дата] Канал.Рівень: Повідомлення {контекст} {додатково}
            $dateFormat = "Y-m-d H:i:s";
            $output = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
            $formatter = new LineFormatter($output, $dateFormat);

            // Створюємо хендлер з ротацією
            $stream = new RotatingFileHandler($logFile, $maxFiles, MonologLogger::DEBUG);
            $stream->setFormatter($formatter);

            self::$logger->pushHandler($stream);
        }

        return self::$logger;
    }

    public static function info($message, $context = [])
    {
        self::getLogger()->info($message, $context);
    }

    public static function error($message, $context = [])
    {
        self::getLogger()->error($message, $context);
    }

    public static function warning($message, $context = [])
    {
        self::getLogger()->warning($message, $context);
    }

    public static function debug($message, $context = [])
    {
        self::getLogger()->debug($message, $context);
    }
}