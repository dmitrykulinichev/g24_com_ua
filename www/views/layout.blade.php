<!DOCTYPE html>
<html lang="{{ \App\I18n\Locale::config()->meta(locale(), 'hreflang') }}">
<head>
    @include('partials.analytics')

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO Meta Tags -->
    @php
        $pageTitle = $meta['title'] ?? t('meta.site_title');
        if (isset($meta['title'])) {
            $pageTitle .= ' | Garage24';
        }
        $pageDesc = $meta['description'] ?? t('meta.site_description');
        // Прев'ю в соцмережах і месенджерах — скрін кабінету мовою сторінки (content/screens.json)
        $pageImage = $meta['image'] ?? \App\Screens\ScreenRegistry::instance()->image('cabinet-dashboard')['path'];
        $baseUrl = \App\I18n\LocalizedUrl::base();
        // Canonical і альтернативи — адреса сторінки поточною та іншими відкритими мовами (app/I18n)
        $canonicalUrl = \App\I18n\LocalizedUrl::canonical();
        $alternateUrls = \App\I18n\LocalizedUrl::alternates();
        $defaultLocale = \App\I18n\Locale::config()->default();
        $pageImage = str_starts_with($pageImage, 'http') ? $pageImage : $baseUrl . $pageImage;
    @endphp

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDesc }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    @if (count($alternateUrls) > 1)
        @foreach ($alternateUrls as $altLocale => $altUrl)
    <link rel="alternate" hreflang="{{ \App\I18n\Locale::config()->meta($altLocale, 'hreflang') }}" href="{{ $altUrl }}">
        @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ $alternateUrls[$defaultLocale] }}">
    @endif

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:locale" content="{{ \App\I18n\Locale::config()->meta(locale(), 'og') }}">
    @foreach (array_keys($alternateUrls) as $altLocale)
        @if ($altLocale !== locale())
    <meta property="og:locale:alternate" content="{{ \App\I18n\Locale::config()->meta($altLocale, 'og') }}">
        @endif
    @endforeach
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDesc }}">
    <meta property="og:image" content="{{ $pageImage }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="{{ $pageTitle }}">
    <meta property="twitter:description" content="{{ $pageDesc }}">
    <meta property="twitter:image" content="{{ $pageImage }}">

    <!-- Schema.org Organization -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Garage24",
      "url": "{{ $baseUrl }}",
      "logo": "{{ $baseUrl }}/assets/img/logo.jpg",
      "sameAs": []
    }
    </script>

    <!-- Мова сторінки для запитів до /api (app/I18n/LocaleResolver читає X-Locale): форми, пошук, модалки -->
    <script>
        window.LANDING_LOCALE = @json(locale());
        window.apiFetch = function (url, options) {
            options = options || {};
            options.headers = Object.assign({ 'X-Locale': window.LANDING_LOCALE }, options.headers || {});
            return fetch(url, options);
        };
    </script>

    <!-- Основні стилі (зкомпільовані Tailwind) -->
    @php $cssVersion = @filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/css/style.css') ?: '1'; @endphp
    <link rel="stylesheet" href="/assets/css/style.css?v={{ $cssVersion }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Підключення Alpine.js та плагінів -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <!-- Передача конфігурації з бекенду -->
    @if(isset($apiConfig) && $apiConfig)
    <script>
        window.landingConfig = {!! json_encode($apiConfig) !!};
    </script>
    @endif

    @stack('styles')
</head>
<body class="text-slate-800 antialiased bg-white" x-data="{ yearly: false }">
    @include('partials.header')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- Модальні вікна (підключаються глобально ОДИН РАЗ) -->
    @include('partials.modal-form')
    @include('partials.cookie-consent')

    <!-- Скрипти сторінок -->
    @stack('scripts')
</body>
</html>