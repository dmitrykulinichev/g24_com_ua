<?php

namespace App\I18n;

/** Адреси з мовою: внутрішні посилання, canonical, перемикач і hreflang. */
final class LocalizedUrl
{
    /** «/pricing» мовою $locale (за замовчуванням — поточною): «/en/pricing». Основна мова — без префікса. */
    public static function to(string $path, ?string $locale = null): string
    {
        $locale ??= Locale::current();
        $path = '/' . ltrim($path, '/');

        if ($locale === Locale::config()->default()) {
            return $path;
        }

        return $path === '/' ? "/{$locale}" : "/{$locale}{$path}";
    }

    public static function absolute(string $path, ?string $locale = null): string
    {
        return self::base() . self::to($path, $locale);
    }

    /**
     * Canonical сторінки: перекладена — поточною мовою; неперекладена (блог, документація — поки) —
     * українська адреса, навіть якщо відкрито /en/…: вміст той самий, дублів для пошуковиків немає.
     */
    public static function canonical(?string $path = null): string
    {
        $path ??= Locale::path();

        return self::absolute($path, Locale::config()->isLocalizedPath($path) ? Locale::current() : Locale::config()->default());
    }

    /** Сторінка всіма відкритими мовами: [мова => абсолютна адреса]; неперекладена — лише основна мова. */
    public static function alternates(?string $path = null): array
    {
        $path ??= Locale::path();
        if (!Locale::config()->isLocalizedPath($path)) {
            return [Locale::config()->default() => self::absolute($path, Locale::config()->default())];
        }

        $urls = [];
        foreach (Locale::config()->available() as $locale) {
            $urls[$locale] = self::absolute($path, $locale);
        }

        return $urls;
    }

    public static function base(): string
    {
        return rtrim($_ENV['APP_URL'] ?? ('https://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')), '/');
    }
}
