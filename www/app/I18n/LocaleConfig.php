<?php

namespace App\I18n;

/** Які мови є, яка основна, які відкриті відвідувачам (config/i18n.php). */
final class LocaleConfig
{
    private function __construct(private array $config) {}

    public static function load(?array $config = null): self
    {
        return new self($config ?? require dirname(__DIR__, 2) . '/config/i18n.php');
    }

    public function default(): string
    {
        return $this->config['default'];
    }

    /** @return list<string> */
    public function supported(): array
    {
        return array_keys($this->config['locales']);
    }

    public function isSupported(string $locale): bool
    {
        return isset($this->config['locales'][$locale]);
    }

    /** Мова відкрита відвідувачам (або ввімкнено перегляд закритих). */
    public function isAvailable(string $locale): bool
    {
        return $this->isSupported($locale)
            && ($this->config['preview'] || in_array($locale, $this->config['public'], true));
    }

    /** Відкриті мови в порядку з конфігу — для перемикача, hreflang і мапи сайту. @return list<string> */
    public function available(): array
    {
        return array_values(array_filter($this->supported(), fn ($l) => $this->isAvailable($l)));
    }

    /** Вміст сторінки перекладено (config `localized_paths`; «/docs/*» — адреса й усе під нею). */
    public function isLocalizedPath(string $path): bool
    {
        foreach ($this->config['localized_paths'] ?? [] as $pattern) {
            if (str_ends_with($pattern, '/*')) {
                $base = substr($pattern, 0, -2);
                if ($path === $base || str_starts_with($path, $base . '/')) {
                    return true;
                }
            } elseif ($path === $pattern) {
                return true;
            }
        }

        return false;
    }

    public function meta(string $locale, string $key): ?string
    {
        return $this->config['locales'][$locale][$key] ?? null;
    }
}
