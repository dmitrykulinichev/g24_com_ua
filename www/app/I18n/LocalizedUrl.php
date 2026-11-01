<?php

namespace App\I18n;

/** Адреси з мовою: внутрішні посилання, canonical, перемикач і hreflang. */
final class LocalizedUrl
{
    /** @var array<string, callable(string): ?array> Вміст, мови якого відомі лише з даних (блог) — див. registerContent. */
    private static array $content = [];

    /**
     * Розділ сайту, мови сторінок якого залежать від вмісту, а не від конфігу (стаття блогу є лише тими
     * мовами, якими її написано). $resolver(адреса) → мови цієї адреси або null («не моя адреса»).
     * Модуль мов про розділи не знає — вони реєструються самі (index.php).
     */
    public static function registerContent(string $name, callable $resolver): void
    {
        self::$content[$name] = $resolver;
    }

    /** Для тестів. */
    public static function forgetContent(): void
    {
        self::$content = [];
    }

    /** Мови, якими є сторінка: перекладена (config) — усі відкриті; розділ із вмістом — ті, що є в ньому; решта — основна. */
    public static function localesOf(string $path): array
    {
        $config = Locale::config();
        if ($config->isLocalizedPath($path)) {
            return $config->available();
        }
        foreach (self::$content as $resolver) {
            $locales = $resolver($path);
            if ($locales !== null) {
                return array_values(array_intersect($config->available(), $locales)) ?: [$config->default()];
            }
        }

        return [$config->default()];
    }

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
     * Canonical сторінки: є поточною мовою — поточна; немає (неперекладена сторінка, стаття без цієї версії) —
     * українська адреса, навіть якщо відкрито /en/…: вміст той самий, дублів для пошуковиків немає.
     */
    public static function canonical(?string $path = null): string
    {
        $path ??= Locale::path();
        $locale = in_array(Locale::current(), self::localesOf($path), true) ? Locale::current() : Locale::config()->default();

        return self::absolute($path, $locale);
    }

    /** Сторінка всіма мовами, якими вона є: [мова => абсолютна адреса]; лише українською — одна адреса. */
    public static function alternates(?string $path = null): array
    {
        $path ??= Locale::path();
        $urls = [];
        foreach (self::localesOf($path) as $locale) {
            $urls[$locale] = self::absolute($path, $locale);
        }

        return $urls;
    }

    public static function base(): string
    {
        return rtrim($_ENV['APP_URL'] ?? ('https://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')), '/');
    }
}
