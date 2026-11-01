<?php

namespace App\Screens;

use App\I18n\Locale;

/**
 * Скріни лендінгу (content/screens.json): файл мовою сторінки, а якщо його ще не знято — українська.
 * Файли — assets/img/screens/{мова}/{id}.webp; alt — lang/*.php, ключ screens.{id}.
 */
final class ScreenRegistry
{
    private static ?self $instance = null;

    private function __construct(private array $screens, private string $root) {}

    public static function instance(): self
    {
        return self::$instance ??= self::load();
    }

    /** Для тестів — інший реєстр і тека з картинками. */
    public static function use(?self $registry): void
    {
        self::$instance = $registry;
    }

    public static function load(?string $registryPath = null, ?string $root = null): self
    {
        $root ??= dirname(__DIR__, 2);
        $data = json_decode(file_get_contents($registryPath ?? $root . '/content/screens.json'), true, flags: JSON_THROW_ON_ERROR);

        return new self($data['screens'] ?? [], $root);
    }

    /** @return list<string> */
    public function ids(): array
    {
        return array_keys($this->screens);
    }

    public function has(string $id): bool
    {
        return isset($this->screens[$id]);
    }

    public function get(string $id): array
    {
        return $this->screens[$id] ?? throw new \InvalidArgumentException("Скріна «{$id}» немає в content/screens.json");
    }

    /** Адреса картинки від кореня сайту, без версії: «/assets/img/screens/en/app-home.webp». */
    public static function path(string $id, string $locale): string
    {
        return "/assets/img/screens/{$locale}/{$id}.webp";
    }

    public function exists(string $id, string $locale): bool
    {
        return is_file($this->root . self::path($id, $locale));
    }

    /** Мови, якими скрін уже знято. @return list<string> */
    public function locales(string $id): array
    {
        return array_values(array_filter(Locale::config()->supported(), fn ($l) => $this->exists($id, $l)));
    }

    /** Мова файлу, який побачить сторінка цією мовою: сама мова, якщо знято, інакше основна. */
    public function resolveLocale(string $id, ?string $locale = null): string
    {
        $locale ??= Locale::current();

        return $this->exists($id, $locale) ? $locale : Locale::config()->default();
    }

    /**
     * Що підставити в <img>: src з версією (той самий файл перезнято — браузер і CDN беруть новий),
     * розміри з самого файлу, мова файлу.
     */
    public function image(string $id, ?string $locale = null): array
    {
        $this->get($id);
        $fileLocale = $this->resolveLocale($id, $locale);
        $path = self::path($id, $fileLocale);
        $file = $this->root . $path;
        [$width, $height] = @getimagesize($file) ?: [null, null];

        return [
            'src' => $path . (is_file($file) ? '?v=' . filemtime($file) : ''),
            'path' => $path,
            'width' => $width,
            'height' => $height,
            'locale' => $fileLocale,
        ];
    }

    /** Скріни, що стоять на сторінці (адреса без мовного префікса). @return list<string> */
    public function forPage(string $path): array
    {
        return array_keys(array_filter($this->screens, fn ($s) => in_array($path, $s['pages'] ?? [], true)));
    }
}
