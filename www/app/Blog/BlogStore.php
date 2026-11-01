<?php

namespace App\Blog;

use App\I18n\Locale;

/**
 * Файловий кеш блогу за мовами (пише BlogSync, читають сторінки, пошук і мапа сайту):
 *   content/blog/{мова}/<дата>-<slug>.md + content/blog/{мова}/menu.json
 *   assets/img/blog/synced/{мова}/<slug>/ — обкладинка й картинки тексту саме цієї мови.
 * Українська є в кожної статті; en/ru — лише в тих, де в суперпарку є версія цією мовою.
 */
final class BlogStore
{
    private static ?string $root = null;

    /** Для тестів — інший корінь сайту. */
    public static function useRoot(?string $root): void
    {
        self::$root = $root;
    }

    public static function root(): string
    {
        return self::$root ?? dirname(__DIR__, 2);
    }

    public static function contentDir(string $locale): string
    {
        return self::root() . "/content/blog/{$locale}";
    }

    public static function menuPath(string $locale): string
    {
        return self::contentDir($locale) . '/menu.json';
    }

    public static function imagesDir(string $locale): string
    {
        return self::root() . "/assets/img/blog/synced/{$locale}";
    }

    public static function imagesUrl(string $locale): string
    {
        return "/assets/img/blog/synced/{$locale}";
    }

    /** Статті мовою — новіші зверху (порядок menu.json). @return list<array{slug, title, date, preview, image, tags}> */
    public static function posts(string $locale): array
    {
        $path = self::menuPath($locale);

        return is_file($path) ? (json_decode(file_get_contents($path), true) ?? []) : [];
    }

    public static function post(string $slug, string $locale): ?array
    {
        foreach (self::posts($locale) as $post) {
            if ($post['slug'] === $slug) {
                return $post;
            }
        }

        return null;
    }

    /** Файл статті мовою або null. */
    public static function file(string $slug, string $locale): ?string
    {
        $post = self::post($slug, $locale);
        $path = $post ? self::contentDir($locale) . "/{$post['date']}-{$slug}.md" : null;

        return $path && is_file($path) ? $path : null;
    }

    /** Мови, якими є стаття (основна — першою). @return list<string> */
    public static function localesOf(string $slug): array
    {
        return array_values(array_filter(Locale::config()->supported(), fn ($l) => self::post($slug, $l) !== null));
    }

    /**
     * Якими мовами є адреса блогу: список — мови, де є хоч одна стаття; стаття — мови, якими вона є.
     * Не адреса блогу — null (вирішує конфіг мов). Реєструється в LocalizedUrl (index.php).
     */
    public static function localesOfPath(string $path): ?array
    {
        if ($path === '/blog') {
            return array_values(array_filter(Locale::config()->supported(), fn ($l) => self::posts($l) !== []));
        }
        if (preg_match('#^/blog/([a-z0-9]+(?:-[a-z0-9]+)*)$#i', $path, $m)) {
            return self::localesOf($m[1]);
        }

        return null;
    }

    /** Якою мовою показати вміст адреси: поточною, якщо вона є, інакше основною. */
    public static function contentLocale(string $path): string
    {
        $locales = self::localesOfPath($path) ?? [];

        return in_array(Locale::current(), $locales, true) ? Locale::current() : Locale::config()->default();
    }
}
