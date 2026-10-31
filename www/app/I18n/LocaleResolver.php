<?php

namespace App\I18n;

/**
 * Мова з адреси запиту — на вході, до маршрутизації. «/en/features» → мова en, далі Leaf бачить
 * «/features»: маршрути й контролери одні для всіх мов. Основна мова — без префікса; «/uk/…» —
 * 301 на адресу без префікса (без дублів). Закрита мова — префікс не знімаємо, сторінка дасть 404.
 * API (/api/…) мовою не префіксується: мова — із заголовка X-Locale (форми передають мову сторінки).
 */
final class LocaleResolver
{
    public function __construct(private LocaleConfig $config) {}

    /**
     * @return array{locale: string, path: string, uri: string, redirect: ?string}
     *   uri — що віддати маршрутизатору; redirect — куди перенаправити замість обробки.
     */
    public function resolve(string $requestUri, ?string $headerLocale = null): array
    {
        $path = parse_url($requestUri, PHP_URL_PATH) ?: '/';
        $query = parse_url($requestUri, PHP_URL_QUERY);
        $suffix = $query !== null && $query !== '' ? "?{$query}" : '';
        $default = $this->config->default();

        if (str_starts_with($path, '/api/')) {
            $locale = $headerLocale && $this->config->isAvailable($headerLocale) ? $headerLocale : $default;

            return ['locale' => $locale, 'path' => $path, 'uri' => $requestUri, 'redirect' => null];
        }

        if (preg_match('#^/([a-z]{2})(/.*)?$#', $path, $m) && $this->config->isSupported($m[1])) {
            $locale = $m[1];
            $rest = $m[2] ?? '/';

            if ($locale === $default) {
                return ['locale' => $default, 'path' => $rest, 'uri' => $rest . $suffix, 'redirect' => $rest . $suffix];
            }

            if ($this->config->isAvailable($locale)) {
                return ['locale' => $locale, 'path' => $rest, 'uri' => $rest . $suffix, 'redirect' => null];
            }
        }

        return ['locale' => $default, 'path' => $path, 'uri' => $requestUri, 'redirect' => null];
    }
}
