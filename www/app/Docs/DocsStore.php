<?php

namespace App\Docs;

use App\I18n\Locale;

/**
 * Розділ сайту зі сторінками в Markdown за мовами: документація (`content/docs2`, адреса `/docs`) і посібник
 * водія (`content/driver`, адреса `/driver`) — той самий код, різні тека, меню й адреса.
 * Українська — основна (`start.md`), переклад лежить поруч: `start.en.md`, `start.ru.md`. Сторінка є мовою,
 * лише якщо є її файл; немає — показується українська з canonical на неї (як неперекладені сторінки й блог).
 * Назви в меню іншими мовами — поле `i18n` пункту чи групи в `menu.json`: `"i18n": {"en": {"title": "…", "description": "…"}}`.
 * Модуль мов про розділи не знає: адреси реєструються через `LocalizedUrl::registerContent('docs', …)` (index.php).
 */
final class DocsStore
{
    /** @var array<string, self> */
    private static array $sections = [];

    /**
     * @param string $root     тека з `menu.json` і сторінками
     * @param string $basePath адреса розділу: «/docs», сторінка — «/docs/<slug>»
     */
    public function __construct(private string $root, private string $basePath) {}

    /** Документація для парку (кабінет). */
    public static function docs(): self
    {
        return self::$sections['docs'] ??= new self(dirname(__DIR__, 2) . '/content/docs2', '/docs');
    }

    /** Посібник водія (застосунок) — окремий розділ сайту. */
    public static function driver(): self
    {
        return self::$sections['driver'] ??= new self(dirname(__DIR__, 2) . '/content/driver', '/driver');
    }

    public function root(): string
    {
        return $this->root;
    }

    public function basePath(): string
    {
        return $this->basePath;
    }

    /** Адреса сторінки розділу без мови: «start» → «/driver/start». */
    public function pagePath(string $slug): string
    {
        return $this->basePath . '/' . $slug;
    }

    /** `menu.json` як є (групи → пункти → вкладки). */
    public function rawMenu(): array
    {
        $path = $this->root . '/menu.json';

        return is_file($path) ? (json_decode(file_get_contents($path), true) ?? []) : [];
    }

    /** Файл сторінки цією мовою: «_data/driver/start.md» → …/driver/start.en.md (основна — без суфікса). */
    public function file(string $docFile, string $locale): string
    {
        $path = $this->root . '/' . preg_replace('#^_data/#', '', $docFile);

        return $locale === Locale::config()->default() ? $path : preg_replace('/\.md$/', ".{$locale}.md", $path);
    }

    /** Мови, якими є сторінка зі slug; null — такої сторінки немає. */
    public function pageLocales(string $slug): ?array
    {
        $docFile = $this->docFileOf($slug);
        if ($docFile === null || !is_file($this->file($docFile, Locale::config()->default()))) {
            return null;
        }

        return array_values(array_filter(Locale::config()->supported(), fn ($l) => is_file($this->file($docFile, $l))));
    }

    /** Для `LocalizedUrl::registerContent`: мови адреси розділу чи його сторінки; null — не цей розділ. */
    public function localesOfPath(string $path): ?array
    {
        if ($path === $this->basePath) {
            $locales = [Locale::config()->default()];
            foreach ($this->slugs() as $slug) {
                $locales = array_merge($locales, $this->pageLocales($slug) ?? []);
            }

            return array_values(array_intersect(Locale::config()->supported(), array_unique($locales)));
        }
        if (preg_match('#^' . preg_quote($this->basePath, '#') . '/([a-z0-9]+(?:-[a-z0-9]+)*)$#', $path, $m)) {
            return $this->pageLocales($m[1]);
        }

        return null;
    }

    /** Якою мовою показати сторінку: поточною, якщо вона є, інакше основною. */
    public function contentLocale(string $slug): string
    {
        $locales = $this->pageLocales($slug) ?? [];

        return in_array(Locale::current(), $locales, true) ? Locale::current() : Locale::config()->default();
    }

    /** Пункт чи група меню з назвою й описом поточною мовою (поле `i18n`), якщо переклад є. */
    public static function localize(array $node): array
    {
        $translated = $node['i18n'][Locale::current()] ?? [];

        return array_merge($node, array_intersect_key($translated, ['title' => 1, 'description' => 1]));
    }

    /** @return list<string> slug усіх сторінок і вкладок меню */
    public function slugs(): array
    {
        $slugs = [];
        foreach ($this->rawMenu() as $group) {
            foreach ($group['items'] ?? [] as $item) {
                $slugs[] = $item['slug'];
                foreach ($item['tabs'] ?? [] as $tab) {
                    $slugs[] = $tab['slug'];
                }
            }
        }

        return $slugs;
    }

    /** @return list<array{slug: string, docFile: string}> сторінки й вкладки меню, що мають український файл */
    public function pages(): array
    {
        $pages = [];
        foreach ($this->rawMenu() as $group) {
            foreach ($group['items'] ?? [] as $item) {
                foreach (array_merge([$item], $item['tabs'] ?? []) as $page) {
                    if (!empty($page['docFile']) && is_file($this->file($page['docFile'], Locale::config()->default()))) {
                        $pages[] = ['slug' => $page['slug'], 'docFile' => $page['docFile']];
                    }
                }
            }
        }

        return $pages;
    }

    private function docFileOf(string $slug): ?string
    {
        foreach ($this->rawMenu() as $group) {
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
