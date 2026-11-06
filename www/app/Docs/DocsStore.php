<?php

namespace App\Docs;

use App\I18n\Locale;

/**
 * Документація (`content/docs2`) за мовами. Українська — основна (`driver/start.md`), переклад лежить поруч:
 * `driver/start.en.md`, `driver/start.ru.md`. Сторінка є мовою, лише якщо є її файл; немає — показується
 * українська з canonical на неї (як неперекладені сторінки й блог). Назви в меню іншими мовами — поле `i18n`
 * пункту чи групи в `menu.json`: `"i18n": {"en": {"title": "…", "description": "…"}}`.
 * Модуль мов про документацію не знає: адреси реєструються через `LocalizedUrl::registerContent('docs', …)`.
 */
final class DocsStore
{
    private static ?string $root = null;

    public static function root(): string
    {
        return self::$root ?? dirname(__DIR__, 2) . '/content/docs2';
    }

    /** Для тестів — інша тека з меню й сторінками. */
    public static function useRoot(?string $root): void
    {
        self::$root = $root;
    }

    /** `menu.json` як є (групи → пункти → вкладки). */
    public static function rawMenu(): array
    {
        $path = self::root() . '/menu.json';

        return is_file($path) ? (json_decode(file_get_contents($path), true) ?? []) : [];
    }

    /** Файл сторінки цією мовою: «_data/driver/start.md» → …/driver/start.en.md (основна — без суфікса). */
    public static function file(string $docFile, string $locale): string
    {
        $path = self::root() . '/' . preg_replace('#^_data/#', '', $docFile);

        return $locale === Locale::config()->default() ? $path : preg_replace('/\.md$/', ".{$locale}.md", $path);
    }

    /** Мови, якими є сторінка зі slug; null — такої сторінки немає. */
    public static function pageLocales(string $slug): ?array
    {
        $docFile = self::docFileOf($slug);
        if ($docFile === null || !is_file(self::file($docFile, Locale::config()->default()))) {
            return null;
        }

        return array_values(array_filter(Locale::config()->supported(), fn ($l) => is_file(self::file($docFile, $l))));
    }

    /** Для `LocalizedUrl::registerContent`: мови адреси `/docs` чи `/docs/<slug>`; null — не документація. */
    public static function localesOfPath(string $path): ?array
    {
        if ($path === '/docs') {
            $locales = [Locale::config()->default()];
            foreach (self::slugs() as $slug) {
                $locales = array_merge($locales, self::pageLocales($slug) ?? []);
            }

            return array_values(array_intersect(Locale::config()->supported(), array_unique($locales)));
        }
        if (preg_match('#^/docs/([a-z0-9]+(?:-[a-z0-9]+)*)$#', $path, $m)) {
            return self::pageLocales($m[1]);
        }

        return null;
    }

    /** Якою мовою показати сторінку: поточною, якщо вона є, інакше основною. */
    public static function contentLocale(string $slug): string
    {
        $locales = self::pageLocales($slug) ?? [];

        return in_array(Locale::current(), $locales, true) ? Locale::current() : Locale::config()->default();
    }

    /** Пункт чи група меню з назвою й описом поточною мовою (поле `i18n`), якщо переклад є. */
    public static function localize(array $node): array
    {
        $translated = $node['i18n'][Locale::current()] ?? [];

        return array_merge($node, array_intersect_key($translated, ['title' => 1, 'description' => 1]));
    }

    /** @return list<string> slug усіх сторінок і вкладок меню */
    public static function slugs(): array
    {
        $slugs = [];
        foreach (self::rawMenu() as $group) {
            foreach ($group['items'] ?? [] as $item) {
                $slugs[] = $item['slug'];
                foreach ($item['tabs'] ?? [] as $tab) {
                    $slugs[] = $tab['slug'];
                }
            }
        }

        return $slugs;
    }

    private static function docFileOf(string $slug): ?string
    {
        foreach (self::rawMenu() as $group) {
            foreach ($group['items'] ?? [] as $item) {
                foreach (array_merge([$item], $item['tabs'] ?? []) as $page) {
                    if (($page['slug'] ?? null) === $slug) {
                        return $page['docFile'] ?? null;
                    }
                }
            }
        }

        return null;
    }
}
