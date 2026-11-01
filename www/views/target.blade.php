@extends('layout')

@section('content')
    @php
        // Сегменти: ключ словника (target.segments.*) → лінійна іконка (partials/icon) і акцентний колір
        $segments = [
            'partners' => ['icon' => 'handshake', 'tone' => 'bg-blue-50 text-blue-600'],
            'operator' => ['icon' => 'phone', 'tone' => 'bg-amber-50 text-amber-600'],
            'investor' => ['icon' => 'shield-check', 'tone' => 'bg-violet-50 text-violet-600'],
            'growing' => ['icon' => 'trending-up', 'tone' => 'bg-emerald-50 text-emerald-600'],
        ];
        $avatarTones = ['bg-blue-600', 'bg-emerald-600', 'bg-violet-600'];
    @endphp

    <!-- Hero -->
    <div class="relative bg-slate-900 text-white pt-32 pb-28 lg:pt-40 lg:pb-32 text-center overflow-hidden">
        <div class="absolute inset-0 bg-[url('/assets/img/grid.svg')] opacity-10"></div>
        <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-blue-900/50 to-transparent"></div>

        <div class="container mx-auto px-4 relative z-10">
            <h1 class="text-4xl font-extrabold sm:text-5xl mb-6">{{ t('target.hero.title') }}</h1>
            <p class="text-xl text-slate-300 max-w-2xl mx-auto">{{ t('target.hero.subtitle') }}</p>
        </div>
    </div>

    <!-- Сегменти: хто ви → що болить → що змінює Garage24 -->
    <section class="bg-slate-50 pb-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-10">
            <div class="grid lg:grid-cols-2 gap-6">
                @foreach ($segments as $key => $segment)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-7 flex flex-col">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $segment['tone'] }}">
                                @include('partials.icon', ['name' => $segment['icon'], 'class' => 'w-6 h-6'])
                            </div>
                            <h2 class="text-xl font-bold text-slate-900">{{ t("target.segments.$key.title") }}</h2>
                        </div>
                        <p class="text-slate-600 mb-6">{{ t("target.segments.$key.text") }}</p>

                        <div class="mt-auto space-y-3">
                            <div class="flex gap-3 rounded-xl bg-slate-50 p-4">
                                <svg class="h-5 w-5 shrink-0 text-slate-400 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
                                <div class="text-sm">
                                    <div class="font-semibold text-slate-500 mb-0.5">{{ t('target.label_pain') }}</div>
                                    <div class="text-slate-600 leading-relaxed">{{ t("target.segments.$key.pain") }}</div>
                                </div>
                            </div>
                            <div class="flex gap-3 rounded-xl bg-blue-50/70 p-4 ring-1 ring-blue-100">
                                <svg class="h-5 w-5 shrink-0 text-blue-600 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M8 12.5l2.5 2.5L16 9.5"/></svg>
                                <div class="text-sm">
                                    <div class="font-semibold text-blue-700 mb-0.5">{{ t('target.label_solution') }}</div>
                                    <div class="text-slate-700 leading-relaxed">{{ t("target.segments.$key.solution") }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Відгуки -->
    <section class="bg-white py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-slate-900 sm:text-4xl">{{ t('target.reviews.title') }}</h2>
                <p class="text-lg text-slate-600 mt-3">{{ t('target.reviews.subtitle') }}</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                @foreach (t_list('target.reviews.items') as $i => $review)
                    <figure class="relative bg-slate-50 rounded-2xl border border-slate-100 p-7 flex flex-col">
                        <svg class="h-8 w-8 text-blue-200 mb-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M9.5 6C6.46 6 4 8.46 4 11.5V18h6v-6H7c0-1.38 1.12-2.5 2.5-2.5V6zm10 0C16.46 6 14 8.46 14 11.5V18h6v-6h-3c0-1.38 1.12-2.5 2.5-2.5V6z"/></svg>
                        <blockquote class="text-slate-700 leading-relaxed mb-6 flex-1">{{ trim($review['text'], '"') }}</blockquote>
                        <figcaption class="flex items-center gap-3">
                            <div class="w-10 h-10 {{ $avatarTones[$i % count($avatarTones)] }} rounded-full flex items-center justify-center text-white font-bold">{{ mb_substr($review['name'], 0, 1) }}</div>
                            <div>
                                <div class="font-bold text-slate-900">{{ $review['name'] }}</div>
                                <div class="text-sm text-slate-500">{{ $review['role'] }}</div>
                            </div>
                        </figcaption>
                    </figure>
                @endforeach
            </div>

            <div class="mt-14 text-center">
                <a href="{{ lurl('/pricing') }}" class="inline-flex items-center gap-2 bg-primary text-white font-bold text-lg px-10 py-4 rounded-xl shadow-lg hover:bg-blue-700 transition duration-300">
                    {{ t('target.cta.start') }}
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </div>
        </div>
    </section>
@endsection
