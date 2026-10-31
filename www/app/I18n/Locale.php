<?php

namespace App\I18n;

/**
 * Мова поточного запиту й адреса сторінки без мовного префікса. Ставиться один раз на вході
 * (LocaleResolver), читається шаблонами й контролерами через помічники (app/I18n/helpers.php).
 */
final class Locale
{
    private static ?LocaleConfig $config = null;
    private static ?string $current = null;
    private static string $path = '/';

    public static function config(): LocaleConfig
    {
        return self::$config ??= LocaleConfig::load();
    }

    /** Для тестів — інший конфіг мов. */
    public static function useConfig(?LocaleConfig $config): void
    {
        self::$config = $config;
        self::$current = null;
        self::$path = '/';
    }

    public static function set(string $locale, string $path = '/'): void
    {
        self::$current = $locale;
        self::$path = $path === '' ? '/' : $path;
    }

    public static function current(): string
    {
        return self::$current ?? self::config()->default();
    }

    /** Адреса поточної сторінки без мовного префікса: «/features». */
    public static function path(): string
    {
        return self::$path;
    }

    public static function isDefault(): bool
    {
        return self::current() === self::config()->default();
    }
}
