@extends('layout')

@push('scripts')
@php
    // Структуровані дані — тими самими текстами, що на сторінці (FAQ має збігатися з видимим), мовою сторінки
    $faqItems = t_list('landing.faq.items');
    $faqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array_map(fn ($item) => [
            '@type' => 'Question',
            'name' => $item['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a']],
        ], $faqItems),
    ];
    $appSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'SoftwareApplication',
        'name' => 'Garage24',
        'url' => \App\I18n\LocalizedUrl::canonical(),
        'applicationCategory' => 'BusinessApplication',
        'operatingSystem' => 'Web, iOS, Android',
        'offers' => [
            '@type' => 'Offer',
            'price' => '0',
            'priceCurrency' => 'UAH',
            'description' => t('landing.schema.offer'),
        ],
        'description' => $meta['description'] ?? t('meta.site_description'),
    ];
@endphp
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
<script type="application/ld+json">{!! json_encode($appSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden pt-32 pb-16 lg:pt-40 lg:pb-20 bg-cover bg-center" style="background-image: url('/assets/img/openart-image_hmp_kyur_1767826263185_raw.png');">
        <!-- Темний оверлей -->
        <div class="absolute inset-0 bg-slate-900/80"></div>
        <!-- Градієнт для глибини -->
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/50 via-transparent to-slate-900/90"></div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-4xl mx-auto">
                <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl mb-6">
                    {{ t('landing.hero.title_1') }}<br>
                    <span class="text-blue-400">{{ t('landing.hero.title_2') }}</span>
                </h1>
                <p class="mt-4 max-w-2xl mx-auto text-xl text-slate-300 mb-8">
                    {{ t('landing.hero.subtitle') }}
                </p>

                <!-- Кнопки перенесено нижче, за межі Hero -->

                <!-- Key Benefits (Інтегровано в Hero) -->
                <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6 text-left max-w-4xl mx-auto">
                    <div class="flex items-start gap-3 p-4 rounded-lg bg-white/10 border border-white/20 backdrop-blur-md text-white">
                        <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center text-blue-300">@include('partials.icon', ['name' => 'handshake', 'class' => 'w-5 h-5'])</div>
                        <div>
                            <h3 class="font-bold text-white text-sm">{{ t('landing.benefits.pay_as_you_go.title') }}</h3>
                            <p class="text-slate-300 text-xs mt-1">{{ t('landing.benefits.pay_as_you_go.text') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4 rounded-lg bg-white/10 border border-white/20 backdrop-blur-md text-white">
                        <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center text-blue-300">@include('partials.icon', ['name' => 'lock-open', 'class' => 'w-5 h-5'])</div>
                        <div>
                            <h3 class="font-bold text-white text-sm">{{ t('landing.benefits.all_included.title') }}</h3>
                            <p class="text-slate-300 text-xs mt-1">{{ t('landing.benefits.all_included.text') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4 rounded-lg bg-white/10 border border-white/20 backdrop-blur-md text-white">
                        <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center text-blue-300">@include('partials.icon', ['name' => 'trending-up', 'class' => 'w-5 h-5'])</div>
                        <div>
                            <h3 class="font-bold text-white text-sm">{{ t('landing.benefits.active_only.title') }}</h3>
                            <p class="text-slate-300 text-xs mt-1">{{ t('landing.benefits.active_only.text') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hero Image -->
            <div class="mt-16 relative rounded-xl shadow-2xl border border-slate-700 bg-slate-800 overflow-hidden">
                @include('partials.screen', ['id' => 'cabinet-dashboard', 'eager' => true, 'class' => 'w-full h-auto opacity-90'])
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 to-transparent pointer-events-none"></div>
            </div>
        </div>
    </section>

    <!-- CTA Buttons Section (Винесено з Hero) -->
    <section class="py-10 bg-white border-b border-slate-100">
        <div class="container mx-auto px-4 text-center">
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ lurl('/pricing') }}" class="btn-primary text-lg px-10 py-4 shadow-lg shadow-blue-500/30">
                    {{ t('landing.cta.start') }}
                </a>
                <a href="{{ lurl('/target') }}" class="inline-flex items-center justify-center px-10 py-4 border border-slate-300 text-lg font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition duration-300 shadow-sm">
                    {{ t('landing.cta.target') }}
                </a>
            </div>
            <p class="mt-4 text-sm text-slate-500">
                {{ t('landing.cta.setup_note') }}
            </p>
        </div>
    </section>

    <!-- Болі -->
    <section class="py-20 bg-slate-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <h2 class="text-3xl font-bold text-slate-900 sm:text-4xl mb-4">{{ t('landing.pains.title') }}</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">{{ t('landing.pains.subtitle') }}</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach ([['finance', 'trending-down'], ['schedule', 'calendar'], ['repairs', 'wrench']] as [$pain, $painIcon])
                    <div class="bg-white rounded-2xl p-7 border border-slate-100 shadow-sm">
                        <div class="w-12 h-12 rounded-xl bg-red-50 text-red-500 flex items-center justify-center mb-5">
                            @include('partials.icon', ['name' => $painIcon, 'class' => 'w-6 h-6'])
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">{{ t("landing.pains.$pain.title") }}</h3>
                        <p class="text-slate-600 leading-relaxed">{{ t("landing.pains.$pain.text") }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Модулі -->
    <section id="features" class="py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900 sm:text-4xl mb-4">{{ t('landing.modules.title') }}</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">{{ t('landing.modules.subtitle') }}</p>
            </div>
            @php
                $moduleIcons = ['schedule' => 'calendar', 'payouts' => 'banknotes', 'economics' => 'chart-bar', 'fleet' => 'truck',
                    'drivers' => 'users', 'service' => 'wrench', 'mileage' => 'map', 'security' => 'shield-check'];
            @endphp
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach (t_list('landing.modules.items') as $moduleKey => $module)
                    <a href="{{ lurl('/features') }}" class="group bg-slate-50 rounded-2xl p-6 border border-slate-100 hover:border-primary/30 hover:shadow-lg transition duration-300 flex flex-col">
                        <div class="w-11 h-11 rounded-xl bg-blue-50 text-primary flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            @include('partials.icon', ['name' => $moduleIcons[$moduleKey] ?? 'check', 'class' => 'w-6 h-6'])
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">{{ $module['title'] }}</h3>
                        <p class="text-sm text-slate-600 mb-4">{{ $module['text'] }}</p>
                        <ul class="mt-auto space-y-1.5 text-sm text-slate-500">
                            @foreach ($module['items'] as $moduleItem)
                                <li class="flex items-center gap-2"><span class="text-green-500">@include('partials.icon', ['name' => 'check', 'class' => 'w-4 h-4'])</span>{{ $moduleItem }}</li>
                            @endforeach
                        </ul>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Integration Section (Uklon) -->
    <section class="py-20 bg-slate-900 text-white overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                <div class="lg:w-1/2">
                    <div class="inline-block bg-yellow-400 text-slate-900 font-bold px-3 py-1 rounded-full text-sm mb-6">
                        {{ t('landing.uklon.badge') }}
                    </div>
                    <h2 class="text-3xl font-bold sm:text-4xl mb-6">{{ t('landing.uklon.title') }}</h2>
                    <p class="text-slate-300 text-lg mb-8">
                        {{ t('landing.uklon.text') }}
                    </p>

                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-slate-800 rounded-full flex items-center justify-center text-green-400">✓</div>
                            <div>
                                <h4 class="font-bold text-lg">{{ t('landing.uklon.trips.title') }}</h4>
                                <p class="text-slate-400 text-sm">{{ t('landing.uklon.trips.text') }}</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-slate-800 rounded-full flex items-center justify-center text-green-400">✓</div>
                            <div>
                                <h4 class="font-bold text-lg">{{ t('landing.uklon.sync.title') }}</h4>
                                <p class="text-slate-400 text-sm">{{ t('landing.uklon.sync.text') }}</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-slate-800 rounded-full flex items-center justify-center text-green-400">✓</div>
                            <div>
                                <h4 class="font-bold text-lg">{{ t('landing.uklon.statuses.title') }}</h4>
                                <p class="text-slate-400 text-sm">{{ t('landing.uklon.statuses.text') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:w-1/2 relative">
                    <!-- Декоративний елемент -->
                    <div class="absolute -inset-4 bg-yellow-400/20 rounded-full blur-3xl"></div>
                    @include('partials.screen', ['id' => 'uklon-reports', 'class' => 'relative rounded-xl shadow-2xl border border-slate-700'])
                </div>
            </div>
        </div>
    </section>

    <!-- Екосистема -->
    <section class="py-24 bg-slate-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900 sm:text-4xl mb-4">{{ t('landing.ecosystem.title') }}</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">{{ t('landing.ecosystem.subtitle') }}</p>
            </div>
            <div class="grid lg:grid-cols-3 gap-6">
                @foreach ([['cabinet', 'computer'], ['app', 'phone'], ['bot', 'chat']] as [$eco, $ecoIcon])
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
                        {{-- Однакова за висотою зона з тим, як це виглядає насправді --}}
                        <div class="h-72 bg-slate-50 border-b border-slate-100 overflow-hidden relative">
                            @if ($eco === 'bot')
                                {{-- Як бот працює насправді: реакція 👌 на розпізнане; ввічлива підказка, якщо формат з помилкою --}}
                                <div class="p-5 space-y-2.5 text-sm">
                                    @foreach (t_list('landing.ecosystem.bot.chat') as $chatLine)
                                        <div class="flex items-end gap-1.5">
                                            <div class="rounded-2xl rounded-bl-sm bg-white border border-slate-200 px-3 py-2 text-slate-700 shadow-sm">
                                                <span class="font-semibold">{{ $chatLine['author'] }}:</span> {{ $chatLine['text'] }}
                                            </div>
                                            <span class="text-base leading-none pb-1" aria-hidden="true">👌</span>
                                        </div>
                                        @if (!empty($chatLine['reply']))
                                            <div class="ml-6 rounded-2xl rounded-tl-sm bg-blue-50 text-slate-700 px-3 py-2 text-xs leading-relaxed">{{ $chatLine['reply'] }}</div>
                                        @endif
                                    @endforeach
                                </div>
                            @elseif ($eco === 'app')
                                @include('partials.screen', ['id' => 'app-home', 'class' => 'absolute inset-x-0 top-0 mx-auto w-3/4 h-auto'])
                            @else
                                @include('partials.screen', ['id' => 'cabinet-dashboard', 'class' => 'absolute left-5 top-5 w-[160%] max-w-none h-auto rounded-lg border border-slate-200 shadow-sm'])
                            @endif
                        </div>
                        <div class="p-7 flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center">
                                    @include('partials.icon', ['name' => $ecoIcon, 'class' => 'w-5 h-5'])
                                </div>
                                <h3 class="text-xl font-bold text-slate-900">{{ t("landing.ecosystem.$eco.title") }}</h3>
                            </div>
                            <p class="text-slate-600 mb-5">{{ t("landing.ecosystem.$eco.text") }}</p>
                            <ul class="space-y-1.5 text-sm text-slate-500">
                                @foreach (t_list("landing.ecosystem.$eco.items") as $ecoItem)
                                    <li class="flex items-center gap-2"><span class="text-green-500">@include('partials.icon', ['name' => 'check', 'class' => 'w-4 h-4'])</span>{{ $ecoItem }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Застосунок водія -->
    <section class="py-24 bg-white overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-5 gap-12 items-center">
                <div class="lg:col-span-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-primary text-sm font-semibold mb-5">
                        @include('partials.icon', ['name' => 'phone', 'class' => 'w-4 h-4'])
                        {{ t('landing.app.badge') }}
                    </div>
                    <h2 class="text-3xl font-bold text-slate-900 sm:text-4xl mb-4">{{ t('landing.app.title') }}</h2>
                    <p class="text-lg text-slate-600 mb-8">{{ t('landing.app.subtitle') }}</p>
                    <ul class="space-y-3 mb-8">
                        @foreach (t_list('landing.app.items') as $appItem)
                            <li class="flex items-start gap-3 text-slate-700">
                                <span class="flex-shrink-0 mt-0.5 w-6 h-6 rounded-full bg-green-50 text-green-600 flex items-center justify-center">@include('partials.icon', ['name' => 'check', 'class' => 'w-4 h-4'])</span>
                                <span>{{ $appItem }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <p class="text-sm text-slate-500 mb-6">{{ t('landing.app.staff') }}</p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ $_ENV['APP_STORE_ANDROID'] ?? 'https://play.google.com/store/apps/details?id=ua.com.g24' }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-slate-900 text-white font-semibold hover:bg-slate-800 transition-colors">
                            Google Play
                        </a>
                        <a href="{{ $_ENV['APP_STORE_IOS'] ?? 'https://apps.apple.com/app/id6778890426' }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-slate-900 text-white font-semibold hover:bg-slate-800 transition-colors">
                            App Store
                        </a>
                    </div>
                </div>
                <div class="lg:col-span-3">
                    @include('partials.screen', ['id' => 'app-driver-trio', 'class' => 'w-full h-auto'])
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-white border-t border-slate-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-slate-900">{{ t('landing.faq.title') }}</h2>
            </div>

            <div class="space-y-6" x-data="{ active: null }">
                {{-- Питання — зі словника (landing.faq.items); ті самі тексти — у структурованих даних FAQPage вище --}}
                @foreach (t_list('landing.faq.items') as $faqIndex => $faqItem)
                    <div class="border border-slate-200 rounded-lg overflow-hidden">
                        <button @click="active = (active === {{ $faqIndex }} ? null : {{ $faqIndex }})" class="w-full px-6 py-4 text-left bg-slate-50 hover:bg-slate-100 flex justify-between items-center font-semibold text-slate-900 transition duration-200">
                            <span>{{ $faqItem['q'] }}</span>
                            <span x-text="active === {{ $faqIndex }} ? '−' : '+'" class="text-xl"></span>
                        </button>
                        <div x-show="active === {{ $faqIndex }}" x-collapse class="px-6 py-4 text-slate-600 border-t border-slate-200 bg-white">
                            {{ $faqItem['a'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-primary relative overflow-hidden">
        <div class="absolute inset-0 bg-blue-700/50"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative text-center">
            <h2 class="text-3xl font-bold text-white sm:text-4xl mb-6">{{ t('landing.cta.title') }}</h2>
            <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto">
                {{ t('landing.cta.text') }}
            </p>
            <a href="{{ lurl('/pricing') }}" class="inline-block bg-white text-blue-600 font-bold text-lg px-10 py-4 rounded-lg shadow-xl hover:bg-blue-50 transition duration-300">
                {{ t('landing.cta.start') }}
            </a>
            <p class="mt-6 text-sm text-blue-200">
                {{ t('landing.cta.note') }}
            </p>
        </div>
    </section>
@endsection
