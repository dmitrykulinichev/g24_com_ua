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
                        <div class="text-2xl">🤝</div>
                        <div>
                            <h3 class="font-bold text-white text-sm">{{ t('landing.benefits.pay_as_you_go.title') }}</h3>
                            <p class="text-slate-300 text-xs mt-1">{{ t('landing.benefits.pay_as_you_go.text') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4 rounded-lg bg-white/10 border border-white/20 backdrop-blur-md text-white">
                        <div class="text-2xl">🔓</div>
                        <div>
                            <h3 class="font-bold text-white text-sm">{{ t('landing.benefits.all_included.title') }}</h3>
                            <p class="text-slate-300 text-xs mt-1">{{ t('landing.benefits.all_included.text') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4 rounded-lg bg-white/10 border border-white/20 backdrop-blur-md text-white">
                        <div class="text-2xl">📈</div>
                        <div>
                            <h3 class="font-bold text-white text-sm">{{ t('landing.benefits.active_only.title') }}</h3>
                            <p class="text-slate-300 text-xs mt-1">{{ t('landing.benefits.active_only.text') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hero Image -->
            <div class="mt-16 relative rounded-xl shadow-2xl border border-slate-700 bg-slate-800 overflow-hidden">
                <img src="/assets/img/docs/dashboard_main.png" alt="{{ t('landing.hero.image_alt') }}" class="w-full h-auto opacity-90">
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

    <!-- Pain Points Section -->
    <section class="py-20 bg-slate-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900 sm:text-4xl mb-4">{{ t('landing.pains.title') }}</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    {{ t('landing.pains.subtitle') }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="space-y-8">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-full flex items-center justify-center text-2xl">📉</div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 mb-2">{{ t('landing.pains.finance.title') }}</h3>
                            <p class="text-slate-600">{{ t('landing.pains.finance.text') }}</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-full flex items-center justify-center text-2xl">🤯</div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 mb-2">{{ t('landing.pains.schedule.title') }}</h3>
                            <p class="text-slate-600">{{ t('landing.pains.schedule.text') }}</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-full flex items-center justify-center text-2xl">🔧</div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 mb-2">{{ t('landing.pains.repairs.title') }}</h3>
                            <p class="text-slate-600">{{ t('landing.pains.repairs.text') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-xl border border-slate-100 relative">
                    <div class="absolute -top-4 -right-4 bg-yellow-400 text-slate-900 font-bold px-4 py-2 rounded-lg transform rotate-3 shadow-md text-sm">
                        {{ t('landing.story.badge') }}
                    </div>
                    <div class="prose text-slate-600 mb-6 relative z-10">
                        <p class="mb-4">{{ t('landing.story.p1') }}</p>
                        <p class="mb-4">{{ t('landing.story.p2') }}</p>
                        <p class="mb-4">{{ t('landing.story.p3') }}</p>
                        <p class="italic font-medium text-slate-800">{{ t('landing.story.p4') }}</p>
                    </div>
                    <div class="flex items-center gap-4 border-t border-slate-100 pt-4">
                        <div class="w-12 h-12 bg-slate-200 rounded-full flex items-center justify-center font-bold text-slate-500">?</div>
                        <div>
                            <div class="font-bold text-slate-900">{{ t('landing.story.author') }}</div>
                            <div class="text-sm text-slate-500">{{ t('landing.story.author_note') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Grid -->
    <section id="features" class="py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900 sm:text-4xl mb-4">{{ t('landing.modules.title') }}</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    {{ t('landing.modules.subtitle') }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- 1. Графік -->
                <div class="bg-slate-50 rounded-xl p-8 border border-slate-100 hover:shadow-lg transition duration-300 group">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform">📅</div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">{{ t('landing.modules.schedule.title') }}</h3>
                    <p class="text-slate-600 mb-4">{{ t('landing.modules.schedule.text') }}</p>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li class="flex items-center gap-2"><span class="text-green-500">✓</span> {{ t('landing.modules.schedule.items.0') }}</li>
                        <li class="flex items-center gap-2"><span class="text-green-500">✓</span> {{ t('landing.modules.schedule.items.1') }}</li>
                        <li class="flex items-center gap-2"><span class="text-green-500">✓</span> {{ t('landing.modules.schedule.items.2') }}</li>
                    </ul>
                </div>

                <!-- 2. Фінанси -->
                <div class="bg-slate-50 rounded-xl p-8 border border-slate-100 hover:shadow-lg transition duration-300 group">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform">💰</div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">{{ t('landing.modules.finance.title') }}</h3>
                    <p class="text-slate-600 mb-4">{{ t('landing.modules.finance.text') }}</p>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li class="flex items-center gap-2"><span class="text-green-500">✓</span> {{ t('landing.modules.finance.items.0') }}</li>
                        <li class="flex items-center gap-2"><span class="text-green-500">✓</span> {{ t('landing.modules.finance.items.1') }}</li>
                        <li class="flex items-center gap-2"><span class="text-green-500">✓</span> {{ t('landing.modules.finance.items.2') }}</li>
                    </ul>
                </div>

                <!-- 3. Автопарк -->
                <div class="bg-slate-50 rounded-xl p-8 border border-slate-100 hover:shadow-lg transition duration-300 group">
                    <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform">🚗</div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">{{ t('landing.modules.garage.title') }}</h3>
                    <p class="text-slate-600 mb-4">{{ t('landing.modules.garage.text') }}</p>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li class="flex items-center gap-2"><span class="text-green-500">✓</span> {{ t('landing.modules.garage.items.0') }}</li>
                        <li class="flex items-center gap-2"><span class="text-green-500">✓</span> {{ t('landing.modules.garage.items.1') }}</li>
                        <li class="flex items-center gap-2"><span class="text-green-500">✓</span> {{ t('landing.modules.garage.items.2') }}</li>
                    </ul>
                </div>

                <!-- 4. Водії -->
                <div class="bg-slate-50 rounded-xl p-8 border border-slate-100 hover:shadow-lg transition duration-300 group">
                    <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform">👥</div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">{{ t('landing.modules.hr.title') }}</h3>
                    <p class="text-slate-600 mb-4">{{ t('landing.modules.hr.text') }}</p>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li class="flex items-center gap-2"><span class="text-green-500">✓</span> {{ t('landing.modules.hr.items.0') }}</li>
                        <li class="flex items-center gap-2"><span class="text-green-500">✓</span> {{ t('landing.modules.hr.items.1') }}</li>
                        <li class="flex items-center gap-2"><span class="text-green-500">✓</span> {{ t('landing.modules.hr.items.2') }}</li>
                    </ul>
                </div>

                <!-- 5. ТО -->
                <div class="bg-slate-50 rounded-xl p-8 border border-slate-100 hover:shadow-lg transition duration-300 group">
                    <div class="w-12 h-12 bg-red-100 text-red-600 rounded-lg flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform">🛠️</div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">{{ t('landing.modules.service.title') }}</h3>
                    <p class="text-slate-600 mb-4">{{ t('landing.modules.service.text') }}</p>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li class="flex items-center gap-2"><span class="text-green-500">✓</span> {{ t('landing.modules.service.items.0') }}</li>
                        <li class="flex items-center gap-2"><span class="text-green-500">✓</span> {{ t('landing.modules.service.items.1') }}</li>
                        <li class="flex items-center gap-2"><span class="text-green-500">✓</span> {{ t('landing.modules.service.items.2') }}</li>
                    </ul>
                </div>

                <!-- 6. Безпека -->
                <div class="bg-slate-50 rounded-xl p-8 border border-slate-100 hover:shadow-lg transition duration-300 group">
                    <div class="w-12 h-12 bg-slate-200 text-slate-700 rounded-lg flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform">🔒</div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">{{ t('landing.modules.security.title') }}</h3>
                    <p class="text-slate-600 mb-4">{{ t('landing.modules.security.text') }}</p>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li class="flex items-center gap-2"><span class="text-green-500">✓</span> {{ t('landing.modules.security.items.0') }}</li>
                        <li class="flex items-center gap-2"><span class="text-green-500">✓</span> {{ t('landing.modules.security.items.1') }}</li>
                        <li class="flex items-center gap-2"><span class="text-green-500">✓</span> {{ t('landing.modules.security.items.2') }}</li>
                    </ul>
                </div>
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
                    <img src="/assets/img/docs/uklon_reports_dashboard.png" alt="Uklon Integration" class="relative rounded-xl shadow-2xl border border-slate-700">
                </div>
            </div>
        </div>
    </section>

    <!-- Ecosystem Section -->
    <section class="py-24 bg-slate-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900 sm:text-4xl mb-4">{{ t('landing.ecosystem.title') }}</h2>
                <p class="text-lg text-slate-600">{{ t('landing.ecosystem.subtitle') }}</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Mobile App (PWA) -->
                <div class="bg-white rounded-2xl p-8 shadow-lg border border-slate-100 flex flex-col md:flex-row gap-8 items-center">
                    <div class="w-full md:w-1/2">
                        <div class="text-4xl mb-4">📱</div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">{{ t('landing.ecosystem.office.title') }}</h3>
                        <p class="text-slate-600 mb-4">{{ t('landing.ecosystem.office.text') }}</p>
                        <ul class="space-y-2 text-sm text-slate-500">
                            <li>{{ t('landing.ecosystem.office.items.0') }}</li>
                            <li>{{ t('landing.ecosystem.office.items.1') }}</li>
                            <li>{{ t('landing.ecosystem.office.items.2') }}</li>
                        </ul>
                    </div>
                    <div class="w-full md:w-1/2">
                        <img src="/assets/img/docs/dashboard_main.png" alt="Mobile App" class="rounded-lg shadow-md border border-slate-200">
                    </div>
                </div>

                <!-- Telegram Bot -->
                <div class="bg-blue-600 rounded-2xl p-8 shadow-lg text-white flex flex-col md:flex-row gap-8 items-center">
                    <div class="w-full md:w-1/2">
                        <div class="text-4xl mb-4">🤖</div>
                        <h3 class="text-2xl font-bold mb-2">{{ t('landing.ecosystem.bot.title') }}</h3>
                        <p class="text-blue-100 mb-4">{{ t('landing.ecosystem.bot.text') }}</p>
                        <ul class="space-y-2 text-sm text-blue-100">
                            <li>{{ t('landing.ecosystem.bot.items.0') }}</li>
                            <li>{{ t('landing.ecosystem.bot.items.1') }}</li>
                            <li>{{ t('landing.ecosystem.bot.items.2') }}</li>
                            <li>{{ t('landing.ecosystem.bot.items.3') }}</li>
                            <li>{{ t('landing.ecosystem.bot.items.4') }}</li>
                        </ul>
                    </div>
                    <div class="w-full md:w-1/2 flex justify-center">
                        <!-- Імітація інтерфейсу бота -->
                        <div class="bg-white text-slate-900 p-4 rounded-lg shadow-lg w-full max-w-[200px] text-xs">
                            <div class="bg-blue-100 p-2 rounded mb-2 self-start">{{ t('landing.ecosystem.bot.chat.0') }}</div>
                            <div class="bg-blue-500 text-white p-2 rounded self-end text-right">{{ t('landing.ecosystem.bot.chat.1') }}</div>

                            <div class="bg-blue-100 p-2 rounded mb-2 self-start">{{ t('landing.ecosystem.bot.chat.2') }}</div>
                            <div class="bg-blue-500 text-white p-2 rounded self-end text-right">{{ t('landing.ecosystem.bot.chat.3') }}</div>
                            <div class="bg-blue-100 p-2 rounded mb-2 self-start">{{ t('landing.ecosystem.bot.chat.4') }}</div>
                            <div class="bg-blue-500 text-white p-2 rounded self-end text-right">{{ t('landing.ecosystem.bot.chat.5') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-slate-900">{{ t('landing.faq.title') }}</h2>
            </div>

            <div class="space-y-6" x-data="{ active: null }">
                <!-- FAQ Item 1 -->
                <div class="border border-slate-200 rounded-lg overflow-hidden">
                    <button @click="active = (active === 1 ? null : 1)" class="w-full px-6 py-4 text-left bg-slate-50 hover:bg-slate-100 flex justify-between items-center font-semibold text-slate-900 transition duration-200">
                        <span>{{ t('landing.faq.items.0.q') }}</span>
                        <span x-text="active === 1 ? '−' : '+'" class="text-xl"></span>
                    </button>
                    <div x-show="active === 1" x-collapse class="px-6 py-4 text-slate-600 border-t border-slate-200 bg-white">
                        {{ t('landing.faq.items.0.a') }}
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="border border-slate-200 rounded-lg overflow-hidden">
                    <button @click="active = (active === 2 ? null : 2)" class="w-full px-6 py-4 text-left bg-slate-50 hover:bg-slate-100 flex justify-between items-center font-semibold text-slate-900 transition duration-200">
                        <span>{{ t('landing.faq.items.1.q') }}</span>
                        <span x-text="active === 2 ? '−' : '+'" class="text-xl"></span>
                    </button>
                    <div x-show="active === 2" x-collapse class="px-6 py-4 text-slate-600 border-t border-slate-200 bg-white">
                        {{ t('landing.faq.items.1.a') }}
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="border border-slate-200 rounded-lg overflow-hidden">
                    <button @click="active = (active === 3 ? null : 3)" class="w-full px-6 py-4 text-left bg-slate-50 hover:bg-slate-100 flex justify-between items-center font-semibold text-slate-900 transition duration-200">
                        <span>{{ t('landing.faq.items.2.q') }}</span>
                        <span x-text="active === 3 ? '−' : '+'" class="text-xl"></span>
                    </button>
                    <div x-show="active === 3" x-collapse class="px-6 py-4 text-slate-600 border-t border-slate-200 bg-white">
                        {{ t('landing.faq.items.2.a') }}
                    </div>
                </div>
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
