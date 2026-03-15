<!DOCTYPE html>
<html lang="uk">
<head>
    @include('partials.analytics')

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO Meta Tags -->
    @php
        $pageTitle = $meta['title'] ?? 'Garage24 - CRM для автопарків';
        if (isset($meta['title'])) {
            $pageTitle .= ' | Garage24';
        }
        $pageDesc = $meta['description'] ?? 'Автоматизуйте виплати, контроль палива та роботу з водіями. Підключайтеся зараз і переходьте на новий рівень ефективності.';
        $pageImage = $meta['image'] ?? '/assets/img/landing/og-image.jpg';
        $baseUrl = rtrim($_ENV['APP_URL'] ?? ('https://' . $_SERVER['HTTP_HOST']), '/');
        $canonicalUrl = $baseUrl . parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $pageImage = str_starts_with($pageImage, 'http') ? $pageImage : $baseUrl . $pageImage;
    @endphp

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDesc }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
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
    @include('partials.modal-text')
    @include('partials.cookie-consent')

    <!-- Скрипти сторінок -->
    @stack('scripts')
</body>
</html>