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

    // Визначення активного пункту меню
    $currentUri = $_SERVER['REQUEST_URI'];

    // Чи є темний фон під хедером (передається з view)
    $isDarkBg = $darkBg ?? false;
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

<header
    x-data="{
        isOpen: false,
        scrolled: false,
        isDarkBg: {{ $isDarkBg ? 'true' : 'false' }}
    }"
    @scroll.window="scrolled = (window.pageYOffset > 20)"
    class="fixed top-0 w-full z-50 transition-all duration-300 border-b"
    :class="{
        'bg-white/90 backdrop-blur-md shadow-sm border-slate-100': scrolled,
        'bg-white border-transparent': !scrolled && isOpen,
        'bg-transparent border-transparent': !scrolled && !isOpen && isDarkBg,
        'bg-white border-slate-100': !scrolled && !isOpen && !isDarkBg
    }"
>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center justify-between h-20">
            <!-- Логотип -->
            <a href="/" class="flex items-center gap-3 group">
                <div class="relative overflow-hidden rounded-lg shadow-sm group-hover:shadow-md transition-all duration-300">
                    <img src="/assets/img/logo.jpg" alt="Garage24 Logo" class="h-10 w-auto transform group-hover:scale-105 transition-transform duration-500">
                </div>
                <span
                    class="font-bold text-xl tracking-tight transition-colors duration-300"
                    :class="{
                        'text-slate-800': scrolled || isOpen || !isDarkBg,
                        'text-white': !scrolled && !isOpen && isDarkBg
                    }"
                >
                    Garage24
                </span>
            </a>

            <!-- Десктопне меню -->
            <div class="hidden lg:flex items-center gap-8">
                <div class="flex items-center gap-1">
                    @foreach([
                        ['url' => '/features', 'title' => 'Можливості'],
                        ['url' => '/target', 'title' => 'Для кого'],
                        ['url' => '/pricing', 'title' => 'Тарифи'],
                        ['url' => '/blog', 'title' => 'Блог'],
                        ['url' => '/docs', 'title' => 'Документація'],
                        ['url' => '/contacts', 'title' => 'Контакти'],
                    ] as $item)
                        <a href="{{ $item['url'] }}"
                           class="relative px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200"
                           :class="{
                               'text-primary bg-blue-50': '{{ $currentUri }}'.startsWith('{{ $item['url'] }}') && (scrolled || !isDarkBg),
                               'text-white bg-white/10': '{{ $currentUri }}'.startsWith('{{ $item['url'] }}') && !scrolled && isDarkBg,
                               'text-slate-600 hover:text-slate-900 hover:bg-slate-50': !'{{ $currentUri }}'.startsWith('{{ $item['url'] }}') && (scrolled || !isDarkBg),
                               'text-white/80 hover:text-white hover:bg-white/10': !'{{ $currentUri }}'.startsWith('{{ $item['url'] }}') && !scrolled && isDarkBg
                           }"
                        >
                            {{ $item['title'] }}
                        </a>
                    @endforeach
                </div>

                <!-- Розділювач -->
                <div class="h-6 w-px transition-colors duration-300"
                     :class="{ 'bg-slate-200': scrolled || !isDarkBg, 'bg-white/20': !scrolled && isDarkBg }"></div>

                <!-- Кнопка входу -->
                <a href="https://app.g24.com.ua/login" target="_blank"
                   class="group flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-full transition-all duration-300 shadow-sm hover:shadow"
                   :class="{
                       'text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 hover:text-primary hover:border-primary/30': scrolled || !isDarkBg,
                       'text-white bg-white/10 border border-white/30 hover:bg-white/20 hover:border-white/50': !scrolled && isDarkBg
                   }"
                >
                    <span>В гараж</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>

            <!-- Мобільна кнопка -->
            <button class="lg:hidden p-2 focus:outline-none transition-colors duration-300"
                :class="{ 'text-slate-600 hover:text-slate-900': scrolled || isOpen || !isDarkBg, 'text-white hover:text-white/80': !scrolled && !isOpen && isDarkBg }"
                @click="isOpen = !isOpen" aria-label="Меню">
                <div class="w-6 h-6 relative flex flex-col justify-center gap-1.5">
                    <span class="block w-full h-0.5 bg-current rounded-full transition-all duration-300" :class="{ 'rotate-45 translate-y-2': isOpen }"></span>
                    <span class="block w-full h-0.5 bg-current rounded-full transition-all duration-300" :class="{ 'opacity-0': isOpen }"></span>
                    <span class="block w-full h-0.5 bg-current rounded-full transition-all duration-300" :class="{ '-rotate-45 -translate-y-2': isOpen }"></span>
                </div>
            </button>
        </nav>
    </div>

    <!-- Мобільне меню (Slide Down) -->
    <div
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="lg:hidden absolute top-20 left-0 w-full bg-white border-b border-slate-100 shadow-lg"
        style="display: none;"
    >
        <div class="container mx-auto px-4 py-6 flex flex-col gap-2">
            @foreach([
                ['url' => '/features', 'title' => 'Можливості'],
                ['url' => '/target', 'title' => 'Для кого'],
                ['url' => '/pricing', 'title' => 'Тарифи'],
                ['url' => '/blog', 'title' => 'Блог'],
                ['url' => '/docs', 'title' => 'Документація'],
                ['url' => '/contacts', 'title' => 'Контакти'],
            ] as $item)
                <a href="{{ $item['url'] }}"
                   class="block px-4 py-3 rounded-lg text-base font-medium transition-colors
                          {{ str_starts_with($currentUri, $item['url']) ? 'bg-blue-50 text-primary' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                   @click="isOpen = false">
                    {{ $item['title'] }}
                </a>
            @endforeach

            <div class="mt-4 pt-4 border-t border-slate-100">
                <a href="https://app.g24.com.ua/login" target="_blank"
                   class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-primary text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                    <span>Увійти в кабінет</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</header>

<style>
    .backdrop-blur-md { backdrop-filter: blur(12px); }
</style>