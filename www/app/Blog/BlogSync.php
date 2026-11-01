<?php

namespace App\Blog;

use App\I18n\Locale;

/**
 * Запис фіду статей суперпарку у файловий кеш блогу (BlogStore) — кожною мовою окремо.
 * Українська — поля самої статті; en/ru — `translations.{мова}`: заголовок, анонс, текст і обкладинка цієї
 * версії, її картинки качаються в теку цієї мови; немає своєї обкладинки — українська. Статті, якої мовою більше немає, разом з картинками
 * прибираються з теки цієї мови. HTTP, блокування й сповіщення — справа BlogSyncController.
 */
final class BlogSync
{
    /** Розширення локального файлу — за типом, який віддав сервер (у посиланнях СААС розширення немає). */
    private const IMAGE_TYPES = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];

    private array $report = ['synced' => [], 'write_errors' => 0, 'deleted' => 0, 'images_downloaded' => 0, 'images_failed' => 0];

    /** @param callable(string): ?array{0: string, 1: string} $fetch завантаження картинки: url → [дані, content-type] або null */
    public function __construct(private $fetch = null)
    {
        $this->fetch ??= self::curlFetch(...);
    }

    /**
     * @param list<array> $posts фід `/public/landing/blog` (новіші зверху)
     * @return array{synced: array<string,int>, write_errors: int, deleted: int, images_downloaded: int, images_failed: int}
     */
    public function run(array $posts): array
    {
        foreach (Locale::config()->supported() as $locale) {
            $this->syncLocale($locale, $posts);
        }
        $this->removeLegacyLayout();

        return $this->report;
    }

    private function syncLocale(string $locale, array $posts): void
    {
        $dir = BlogStore::contentDir($locale);
        $imagesDir = BlogStore::imagesDir($locale);
        foreach ([$dir, $imagesDir] as $path) {
            if (!is_dir($path) && !mkdir($path, 0755, true) && !is_dir($path)) {
                throw new \RuntimeException("Не вдалось створити {$path}");
            }
        }

        $menu = [];
        $keepFiles = [];
        $keepSlugs = [];
        foreach ($posts as $post) {
            $slug = $post['slug'] ?? '';
            // Невалідному slug не довіряємо: він іде в шляхи файлів і тек
            if (!preg_match('/^[a-z0-9]+(-[a-z0-9]+)*$/i', $slug)) {
                $locale === Locale::config()->default() && $this->report['write_errors']++;
                continue;
            }
            $version = $locale === Locale::config()->default() ? $post : ($post['translations'][$locale] ?? null);
            if (!$version || empty($version['title'])) {
                continue;
            }

            $kept = [];
            $cover = $version['cover_image'] ?? null;
            $localCover = $this->syncImage($cover, $locale, $slug, 'cover', $kept);
            $content = $this->syncInlineImages($version['content'] ?? '', $locale, $slug, $kept);
            $this->removeStaleImages($locale, $slug, $kept);

            $date = $post['published_at'] ?? date('Y-m-d');
            $file = "{$date}-{$slug}.md";
            if (file_put_contents("{$dir}/{$file}", "# {$version['title']}\n\n" . rtrim($content) . "\n") === false) {
                // Не записалось — у menu.json не додаємо, щоб не вести на статтю, якої немає
                $this->report['write_errors']++;
                continue;
            }

            $keepFiles[] = $file;
            $keepSlugs[] = $slug;
            $menu[] = [
                'slug' => $slug,
                'title' => $version['title'],
                'date' => $date,
                'preview' => $version['excerpt'] ?? '',
                'image' => $localCover ?? $cover ?? $this->defaultCover($locale, $slug),
                'tags' => $post['tags'] ?? [],
            ];
        }

        // Статті, знятої з публікації чи без версії цією мовою, — більше немає в теці мови
        foreach (glob("{$dir}/*.md") ?: [] as $path) {
            if (!in_array(basename($path), $keepFiles, true)) {
                unlink($path);
                $this->report['deleted']++;
            }
        }
        foreach (glob("{$imagesDir}/*", GLOB_ONLYDIR) ?: [] as $path) {
            if (!in_array(basename($path), $keepSlugs, true)) {
                self::removeDirectory($path);
            }
        }

        file_put_contents(BlogStore::menuPath($locale), json_encode($menu, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n");
        $this->report['synced'][$locale] = count($menu);
    }

    /**
     * Обкладинка, коли у версії мовою своєї немає: українська (вже синхронізована — основна мова йде першою),
     * той самий файл з теки uk, без копії. Своя з'явиться — візьметься своя.
     */
    private function defaultCover(string $locale, string $slug): ?string
    {
        $default = Locale::config()->default();

        return $locale === $default ? null : (BlogStore::post($slug, $default)['image'] ?? null);
    }

    /** Старе розкладання без мов (content/blog/*.md, assets/img/blog/synced/<slug>/) — прибрати після переходу. */
    private function removeLegacyLayout(): void
    {
        $root = BlogStore::root();
        foreach (glob("{$root}/content/blog/*.md") ?: [] as $path) {
            unlink($path);
        }
        is_file("{$root}/content/blog/menu.json") && unlink("{$root}/content/blog/menu.json");
        foreach (glob("{$root}/assets/img/blog/synced/*", GLOB_ONLYDIR) ?: [] as $path) {
            if (!Locale::config()->isSupported(basename($path))) {
                self::removeDirectory($path);
            }
        }
    }

    /** Картинки з тексту (![alt](https://...)) — у теку мови; локальні/відносні посилання лишаються як є. */
    private function syncInlineImages(string $content, string $locale, string $slug, array &$kept): string
    {
        return preg_replace_callback(
            '/!\[([^\]]*)\]\((https?:\/\/[^\)\s]+)\)/',
            // Не стрілкова функція: вона захоплює $kept копією — картинки не потрапили б у «залишити» й одразу
            // видалились би як застарілі
            function ($m) use ($locale, $slug, &$kept) {
                return '![' . $m[1] . '](' . ($this->syncImage($m[2], $locale, $slug, 'img', $kept) ?? $m[2]) . ')';
            },
            $content
        );
    }

    /**
     * Одна картинка за абсолютним URL → локальний шлях у теці мови. Ім'я — від хешу URL: уже завантажену
     * не качаємо вдруге (нова картинка в СААС — нове посилання). Не картинку не зберігаємо. Локальні шляхи
     * (старі статті з ручним `/assets/...`) не чіпаємо — null, і виклик лишає оригінал.
     */
    private function syncImage(?string $url, string $locale, string $slug, string $prefix, array &$kept): ?string
    {
        if (!$url || !preg_match('#^https?://#i', $url)) {
            return null;
        }
        if (!self::isSafeExternalUrl($url)) {
            $this->report['images_failed']++;
            return null;
        }

        $dir = BlogStore::imagesDir($locale) . "/{$slug}";
        $urlPrefix = BlogStore::imagesUrl($locale) . "/{$slug}";
        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            $this->report['images_failed']++;
            return null;
        }

        $name = $prefix . '-' . substr(sha1($url), 0, 16);
        foreach (self::IMAGE_TYPES as $ext) {
            if (is_file("{$dir}/{$name}.{$ext}")) {
                $kept[] = "{$name}.{$ext}";
                return "{$urlPrefix}/{$name}.{$ext}";
            }
        }

        [$data, $type] = ($this->fetch)($url) ?? [null, null];
        $ext = self::IMAGE_TYPES[$type] ?? null;
        if (!$data || !$ext || file_put_contents("{$dir}/{$name}.{$ext}", $data) === false) {
            $this->report['images_failed']++;
            return null;
        }

        $kept[] = "{$name}.{$ext}";
        $this->report['images_downloaded']++;

        return "{$urlPrefix}/{$name}.{$ext}";
    }

    /** Прибирає з теки статті картинки, яких у ній більше немає (замінили обкладинку, прибрали з тексту). */
    private function removeStaleImages(string $locale, string $slug, array $kept): void
    {
        foreach (glob(BlogStore::imagesDir($locale) . "/{$slug}/*") ?: [] as $file) {
            if (is_file($file) && !in_array(basename($file), $kept, true)) {
                unlink($file);
            }
        }
    }

    /** @return ?array{0: string, 1: string} */
    private static function curlFetch(string $url): ?array
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        // Без редіректів: інакше перевірка хоста (isSafeExternalUrl) не захищає від SSRF через 30x
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        $data = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $type = strtolower(trim(explode(';', (string) curl_getinfo($ch, CURLINFO_CONTENT_TYPE))[0]));
        $error = curl_error($ch);
        curl_close($ch);

        return ($error || $code !== 200 || !$data) ? null : [$data, $type];
    }

    /**
     * Захист від SSRF: посилання на внутрішню адресу (cloud metadata, внутрішній сервіс) у статті не
     * повинно змусити сервер лендінгу сходити туди й опублікувати відповідь на сайті.
     */
    public static function isSafeExternalUrl(string $url): bool
    {
        $host = parse_url($url, PHP_URL_HOST);
        if (!$host) {
            return false;
        }
        $ips = filter_var($host, FILTER_VALIDATE_IP) ? [$host] : (@gethostbynamel($host) ?: []);
        if (!$ips) {
            return false;
        }
        foreach ($ips as $ip) {
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
                return false;
            }
        }

        return true;
    }

    private static function removeDirectory(string $dir): void
    {
        foreach (glob($dir . '/*') ?: [] as $item) {
            is_dir($item) ? self::removeDirectory($item) : unlink($item);
        }
        rmdir($dir);
    }
}
