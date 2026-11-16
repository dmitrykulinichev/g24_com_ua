<?php

namespace App\DriverSite;

use App\I18n\Locale;

/**
 * САЙТ ВОДІЯ — посібник водія на своєму домені (README.md поруч; workspace/tasks/2026-11-16-driver-subdomain):
 * `driver.g24.com.ua` — та сама тека сайту (на хостингу — символьне посилання `driver -> www`), а сайт обирається
 * за доменом запиту. Уся логіка — тут; спільний код лише викликає [handleRequest], [link], [url] (README).

 *
 * На домені водія: адреси без `/driver` («/start», «/en/start»), всередині вони стають звичайними адресами розділу
 * («/driver/start»), будь-яка інша сторінка сайту — 404 посібника (SaaS-сайт водієві не показується).
 * На основному домені: `/driver…` назавжди (301) переїхало на домен водія — старі посилання й пошук не губляться.
 * Локально (свій домен, напр. localhost) нічого не змінюється, доки запит не прийде з доменом водія.
 */
final class DriverSite
{
    private const SECTION = '/driver';

    /** Мапа сайту домену водія — окремий файл поруч із sitemap.xml основного сайту (обидва — статичні, спільна тека). */
    public const SITEMAP_FILE = 'sitemap-driver.xml';

    /** Що відкривається напряму, без посібника: файли, API розділу, мапа сайту. */
    private const PASSTHROUGH = ['/assets/', '/api/driver/', '/favicon', '/robots.txt', '/' . self::SITEMAP_FILE];

    /**
     * Вхідна точка (index.php, до мов і маршрутів). Домен водія: адресу запиту перекладено в адресу розділу
     * («/start» → «/driver/start»). Основний домен: `/driver…` → 301 на домен водія (запит на цьому закінчено).
     */
    public static function handleRequest(): void
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        if (self::active()) {
            $_SERVER['REQUEST_URI'] = self::internalUri($uri);

            return;
        }
        $redirect = self::redirectFromMain($uri);
        if ($redirect !== null) {
            header('Location: ' . $redirect, true, 301);
            exit;
        }
    }

    /**
     * Посилання для сторінки на домені водія (LocalizedUrl::to): сторінка посібника — відносна адреса без `/driver`
     * («/en/shift»); будь-яка інша сторінка сайту (політика, умови) — повна адреса основного домену.
     * [$path] — шлях без мови, [$localePrefix] — «» чи «/en».
     */
    public static function link(string $path, string $localePrefix): string
    {
        if (self::isSectionPath($path)) {
            $public = self::publicPath($path);

            return $localePrefix === '' ? $public : ($public === '/' ? $localePrefix : $localePrefix . $public);
        }

        return self::mainUrl() . ($localePrefix === '' ? $path : ($path === '/' ? $localePrefix : $localePrefix . $path));
    }

    /** Основний сайт (APP_URL) — для посилань із сайту водія на політику, умови тощо. */
    public static function mainUrl(): string
    {
        return rtrim($_ENV['APP_URL'] ?? 'https://g24.com.ua', '/');
    }

    public static function host(): string
    {
        return strtolower($_ENV['DRIVER_HOST'] ?? 'driver.g24.com.ua');
    }

    public static function url(): string
    {
        return 'https://' . self::host();
    }

    /** Основні домени, з яких `/driver…` переадресовується (з APP_URL; www. — теж). */
    public static function mainHosts(): array
    {
        $host = strtolower(parse_url($_ENV['APP_URL'] ?? '', PHP_URL_HOST) ?: '');

        return $host === '' ? [] : array_values(array_unique([$host, 'www.' . preg_replace('/^www\./', '', $host)]));
    }

    /** Запит прийшов на домен водія. */
    public static function active(?string $requestHost = null): bool
    {
        return self::normalizeHost($requestHost ?? ($_SERVER['HTTP_HOST'] ?? '')) === self::host();
    }

    /** Адреса домену водія → адреса розділу: «/en/start?x» → «/en/driver/start?x», «/» → «/driver». */
    public static function internalUri(string $uri): string
    {
        [$path, $query] = self::split($uri);
        foreach (self::PASSTHROUGH as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return $uri;
            }
        }
        [$localePrefix, $rest] = self::splitLocale($path);
        if ($rest === self::SECTION || str_starts_with($rest, self::SECTION . '/')) {
            $rest = substr($rest, strlen(self::SECTION)) ?: '/';  // «/driver/start» на домені водія — те саме, що «/start»
        }

        return $localePrefix . self::SECTION . ($rest === '/' ? '' : $rest) . $query;
    }

    /** Шлях розділу для посилань на домені водія: «/driver/start» → «/start», «/driver» → «/»; інше — без змін. */
    public static function publicPath(string $path): string
    {
        if ($path === self::SECTION) {
            return '/';
        }

        return str_starts_with($path, self::SECTION . '/') ? substr($path, strlen(self::SECTION)) : $path;
    }

    /** Посилання на розділ у готовому HTML (тексти сторінок пишуть «/driver/shift», «/en/driver/shift»). */
    public static function rewriteLinks(string $html): string
    {
        return preg_replace_callback(
            '#href="((?:/[a-z]{2})?)/driver(/[^"]*)?"#',
            fn ($m) => 'href="' . (($m[1] . ($m[2] ?? '')) ?: '/') . '"',
            $html,
        );
    }

    /**
     * Куди переадресувати запит основного домену: `/driver…` → домен водія; null — не переадресовувати.
     */
    public static function redirectFromMain(string $uri, ?string $requestHost = null): ?string
    {
        if (!in_array(self::normalizeHost($requestHost ?? ($_SERVER['HTTP_HOST'] ?? '')), self::mainHosts(), true)) {
            return null;
        }

        return self::driverUrl($uri);
    }

    /**
     * Чи це сторінка посібника (абсолютна адреса основного сайту): мапи окремі — такі сторінки йдуть лише в мапу
     * домену водія (SitemapController).
     */
    public static function isSectionUrl(string $url): bool
    {
        if (self::isDriverHostUrl($url)) {
            return true;  // мапу збирають на самому домені водія — адреси вже його
        }

        return self::driverUrl(parse_url($url, PHP_URL_PATH) ?: '/') !== null;
    }

    /**
     * Адреса сторінки посібника для мапи домену водія: «https://g24.com.ua/en/driver/shift» →
     * «https://driver.g24.com.ua/en/shift»; інша адреса — без змін.
     */
    public static function sitemapUrl(string $url): string
    {
        if (self::isDriverHostUrl($url)) {
            return $url;
        }
        $path = parse_url($url, PHP_URL_PATH) ?: '/';

        return self::driverUrl($path) ?? $url;
    }

    /**
     * Адреса сторінки посібника на домені водія — для посилань з основного сайту:
     * «/en/driver/shift?x» → «https://driver.g24.com.ua/en/shift?x»; не сторінка посібника — null.
     */
    public static function driverUrl(string $uri): ?string
    {
        [$path, $query] = self::split($uri);
        [$localePrefix, $rest] = self::splitLocale($path);
        if (!self::isSectionPath($rest)) {
            return null;
        }
        $public = $localePrefix . self::publicPath($rest);

        return self::url() . ($public === '/' ? '/' : rtrim($public, '/')) . $query;
    }

    private static function isDriverHostUrl(string $url): bool
    {
        return self::normalizeHost((string) parse_url($url, PHP_URL_HOST)) === self::host();
    }

    private static function isSectionPath(string $path): bool
    {
        return $path === self::SECTION || str_starts_with($path, self::SECTION . '/');
    }

    private static function normalizeHost(string $host): string
    {
        return strtolower(preg_replace('/:\d+$/', '', $host));
    }

    /** @return array{0: string, 1: string} шлях і «?запит» */
    private static function split(string $uri): array
    {
        $pos = strpos($uri, '?');

        return $pos === false ? [$uri ?: '/', ''] : [substr($uri, 0, $pos) ?: '/', substr($uri, $pos)];
    }

    /** «/en/start» → ['/en', '/start']; без мовного префікса — ['', шлях]. */
    private static function splitLocale(string $path): array
    {
        if (preg_match('#^/([a-z]{2})(/.*)?$#', $path, $m)
            && $m[1] !== Locale::config()->default()
            && Locale::config()->isSupported($m[1])) {
            return ['/' . $m[1], $m[2] ?? '/'];
        }

        return ['', $path];
    }
}
