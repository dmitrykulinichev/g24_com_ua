<!-- Підключення Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

<!-- SEO Meta Tags -->
@php
    $pageTitle = $meta['title'] ?? 'G24.com.ua - CRM для автопарків';
    // Якщо це не головна, додаємо назву бренду в кінець
    if (isset($meta['title'])) {
        $pageTitle .= ' | G24';
    }
    $pageDesc = $meta['description'] ?? 'Автоматизуйте виплати, контроль палива та роботу з водіями. Підключайтеся зараз і переходьте на новий рівень ефективності.';
    $pageImage = $meta['image'] ?? '/assets/img/landing/og-image.jpg'; // Заглушка
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

<header x-data="{ isOpen: false }">
    <div class="container">
        <nav>
            <a href="/" class="logo">G24.com.ua</a>

            <!-- Кнопка Гамбургер (видима тільки на мобільному) -->
            <button class="mobile-menu-btn" @click="isOpen = !isOpen" aria-label="Меню">
                <!-- Іконка меню (3 смужки) -->
                <svg x-show="!isOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
                <!-- Іконка закрити (хрестик) -->
                <svg x-show="isOpen" style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Меню (Desktop - завжди видно, Mobile - керується Alpine) -->
            <div class="nav-links" :class="{ 'mobile-open': isOpen }">
                <a href="/#features" @click="isOpen = false">Можливості</a>
                <a href="/#products" @click="isOpen = false">Продукти</a>
                <a href="/blog" @click="isOpen = false">Блог</a>
                <a href="/#contact" @click="isOpen = false">Контакти</a>
                <a href="/docs" @click="isOpen = false">Документація</a>
                <!-- <a href="https://app.g24.com.ua/login" class="btn-login">Вхід</a> -->
            </div>
        </nav>
    </div>
</header>