<?php

namespace App\I18n;

/**
 * Тексти лендінгу: lang/{мова}.php — вкладені масиви, ключ через крапку («nav.pricing»).
 * Немає перекладу — основна мова; немає й там — сам ключ (видно на сторінці, що забули текст).
 * Плейсхолдери — «:name».
 */
final class Translator
{
    /** @var array<string, array> */
    private static array $dictionaries = [];

    private static ?string $directory = null;

    /** Для тестів — словники з іншої теки (null — lang/ лендінгу). */
    public static function useDirectory(?string $directory): void
    {
        self::$directory = $directory;
        self::$dictionaries = [];
    }

    public static function get(string $key, array $replace = [], ?string $locale = null): mixed
    {
        $locale ??= Locale::current();
        $value = self::lookup($locale, $key) ?? self::lookup(Locale::config()->default(), $key) ?? $key;

        if (is_string($value) && $replace) {
            foreach ($replace as $name => $replacement) {
                $value = str_replace(":{$name}", (string) $replacement, $value);
            }
        }

        return $value;
    }

    /** Для тестів — скинути завантажені словники. */
    public static function flush(): void
    {
        self::$dictionaries = [];
    }

    private static function lookup(string $locale, string $key): mixed
    {
        $value = self::dictionary($locale);
        foreach (explode('.', $key) as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return null;
            }
            $value = $value[$segment];
        }

        return $value === '' ? null : $value;
    }

    private static function dictionary(string $locale): array
    {
        if (!isset(self::$dictionaries[$locale])) {
            $file = (self::$directory ?? dirname(__DIR__, 2) . '/lang') . "/{$locale}.php";
            self::$dictionaries[$locale] = is_file($file) ? (require $file) : [];
        }

        return self::$dictionaries[$locale];
    }
}
