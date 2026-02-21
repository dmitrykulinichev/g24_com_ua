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
    @endphp

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDesc }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDesc }}">
    <meta property="og:image" content="{{ $pageImage }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="{{ $pageTitle }}">
    <meta property="twitter:description" content="{{ $pageDesc }}">
    <meta property="twitter:image" content="{{ $pageImage }}">

    <!-- Основні стилі (зкомпільовані Tailwind) -->
    <link rel="stylesheet" href="/assets/css/style.css?v={{ time() }}">

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

    <!-- Додаткові скрипти для конкретних сторінок -->
    @stack('scripts')

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
</body>
</html>