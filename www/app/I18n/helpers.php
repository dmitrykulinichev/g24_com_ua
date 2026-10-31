<?php

use App\I18n\Locale;
use App\I18n\LocalizedUrl;
use App\I18n\Translator;

/*
 * Помічники мов для шаблонів і контролерів (підключаються в index.php).
 *   {{ t('nav.pricing') }}            — текст поточною мовою
 *   href="{{ lurl('/pricing') }}"     — внутрішнє посилання з мовою сторінки
 *   {!! t_json('form') !!}            — частина словника для JS (форми)
 *   x-text="{{ t_js('key') }}"        — текст як JS-рядок в атрибуті Alpine
 *   {!! t('key') !!}                  — лише для ключів, де в словнику свідомо є розмітка (<strong>)
 */

if (!function_exists('t')) {
    function t(string $key, array $replace = []): string
    {
        $value = Translator::get($key, $replace);

        return is_string($value) ? $value : $key;
    }
}

if (!function_exists('t_list')) {
    /** Масив зі словника (списки пунктів, розділи) — або порожній. */
    function t_list(string $key): array
    {
        $value = Translator::get($key);

        return is_array($value) ? $value : [];
    }
}

if (!function_exists('t_json')) {
    function t_json(string $key): string
    {
        return json_encode(t_list($key), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP);
    }
}

if (!function_exists('t_js')) {
    /**
     * Текст як JS-рядок для атрибутів Alpine: x-text="yearly ? {{ t_js('a') }} : {{ t_js('b') }}".
     * {{ }} перетворить лапки на &quot;, браузер поверне їх у JS — апостроф у перекладі нічого не зламає.
     */
    function t_js(string $key, array $replace = []): string
    {
        return json_encode(t($key, $replace), JSON_UNESCAPED_UNICODE);
    }
}

if (!function_exists('lurl')) {
    function lurl(string $path): string
    {
        return LocalizedUrl::to($path);
    }
}

if (!function_exists('locale')) {
    function locale(): string
    {
        return Locale::current();
    }
}
