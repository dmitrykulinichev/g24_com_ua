<!DOCTYPE html>
<html lang="uk">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-P5VR2VN5');</script>
    <!-- End Google Tag Manager -->

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

    <link rel="stylesheet" href="/assets/css/style.css">

    <!-- Підключення Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb',
                        secondary: '#1e293b',
                        accent: '#10b981',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Підключення Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <!-- Передача конфігурації з бекенду -->
    @if(isset($apiConfig) && $apiConfig)
    <script>
        window.landingConfig = {!! json_encode($apiConfig) !!};
    </script>
    @endif

    <!-- Додаткові скрипти для конкретних сторінок -->
    @stack('scripts')

    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        .grecaptcha-badge { visibility: hidden; }
    </style>
    @stack('styles')
</head>
<body class="text-slate-800 antialiased bg-white" x-data="{ yearly: false }">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P5VR2VN5"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    @include('partials.header')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- Модальні вікна (підключаються глобально) -->
    @include('partials.modal-form')
    @include('partials.modal-text')
    @include('partials.cookie-consent')
</body>
</html>