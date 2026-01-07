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

<!-- Theme Script -->
<script>
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.setAttribute('data-theme', 'dark');
    } else {
        document.documentElement.removeAttribute('data-theme');
    }
</script>

<header x-data="{ isOpen: false }">
    <div class="container">
        <nav>
            <a href="/" class="logo">Garage24</a>

            <div style="display: flex; align-items: center; gap: 1rem;">
                <!-- Theme Toggle -->
                <button class="theme-toggle"
                        @click="
                            if (document.documentElement.getAttribute('data-theme') === 'dark') {
                                document.documentElement.removeAttribute('data-theme');
                                localStorage.theme = 'light';
                            } else {
                                document.documentElement.setAttribute('data-theme', 'dark');
                                localStorage.theme = 'dark';
                            }
                        "
                        aria-label="Змінити тему">
                    <svg x-show="document.documentElement.getAttribute('data-theme') === 'dark'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                    </svg>
                    <svg x-show="!document.documentElement.getAttribute('data-theme')" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                    </svg>
                </button>

                <button class="mobile-menu-btn" @click="isOpen = !isOpen" aria-label="Меню">
                    <svg x-show="!isOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg x-show="isOpen" style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="nav-links" :class="{ 'mobile-open': isOpen }">
                <a href="/features" @click="isOpen = false">Можливості</a>
                <a href="/target" @click="isOpen = false">Клієнти</a> <!-- Додано -->
                <a href="/pricing" @click="isOpen = false">Тарифи</a>
                <a href="/blog" @click="isOpen = false">Блог</a>
                <a href="/contacts" @click="isOpen = false">Контакти</a>
                <a href="/docs" @click="isOpen = false">Документація</a>

                <a href="https://app.g24.com.ua/login" class="btn-login" target="_blank">Вхід</a>
            </div>
        </nav>
    </div>
</header>