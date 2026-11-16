{{--
    САЙТ ВОДІЯ (app/DriverSite): шапка домену driver.g24.com.ua — логотип (на головну посібника), назва розділу й мова.
    Без меню SaaS-сайту й входу в кабінет. Основний лендінг — views/partials/header.blade.php.
--}}
<header x-data="{ scrolled: true, isDarkBg: false }" class="fixed top-0 w-full z-[999] bg-white border-b border-slate-100">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center justify-between h-20">
            <a href="{{ lurl('/driver') }}" class="flex items-center gap-3">
                <img src="/assets/img/logo.jpg" alt="Garage24" class="h-10 w-auto rounded-lg shadow-sm">
                <span class="font-bold text-xl tracking-tight text-slate-800">Garage24</span>
                <span class="hidden sm:inline text-sm text-slate-500 border-l border-slate-200 pl-3">{{ t('driver.index.title') }}</span>
            </a>
            @include('partials.language-switcher', ['variant' => 'desktop'])
        </nav>
    </div>
</header>
