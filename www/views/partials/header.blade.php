<!-- Підключення Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

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

<!-- ЗАБОРОНА ІНДЕКСАЦІЇ (Тимчасово) -->
<meta name="robots" content="noindex, nofollow">

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

<header x-data="{ isOpen: false }">
    <div class="container">
        <nav>
            <a href="/" class="logo">Garage24</a>

            <button class="mobile-menu-btn" @click="isOpen = !isOpen" aria-label="Меню">
                <svg x-show="!isOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
                <svg x-show="isOpen" style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="nav-links" :class="{ 'mobile-open': isOpen }">
                <a href="/features" @click="isOpen = false">Можливості</a>
                <a href="/target" @click="isOpen = false">Клієнти</a>
                <a href="/pricing" @click="isOpen = false">Тарифи</a>
                <a href="/blog" @click="isOpen = false">Блог</a>
                <a href="/contacts" @click="isOpen = false">Контакти</a>
                <a href="/docs" @click="isOpen = false">Документація</a>

                <!-- Кнопка входу в додаток -->
                <a href="https://app.g24.com.ua/login" class="btn-login" target="_blank">
                    <!-- Іконка входу (стрілка входить у двері) -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 1 18 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 12 21h6a2.25 2.25 0 0 0 2.25-2.25V15m-3 0 3-3m0 0-3-3m3 3H9" />
                    </svg>
                    <span>В гараж</span>
                </a>
            </div>
        </nav>
    </div>
</header>