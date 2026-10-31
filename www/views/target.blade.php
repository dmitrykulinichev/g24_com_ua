@extends('layout')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-slate-900 text-white pt-32 pb-16 lg:pt-40 lg:pb-24 text-center overflow-hidden">
        <div class="absolute inset-0 bg-[url('/assets/img/grid.svg')] opacity-10"></div>
        <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-blue-900/50 to-transparent"></div>

        <div class="container mx-auto px-4 relative z-10">
            <h1 class="text-4xl font-extrabold sm:text-5xl mb-6">{{ t('target.hero.title') }}</h1>
            <p class="text-xl text-slate-300 max-w-2xl mx-auto">{{ t('target.hero.subtitle') }}</p>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 -mt-10 relative z-10">
        <div class="grid gap-8">
            <!-- 1. Партнери -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-8 flex flex-col md:flex-row gap-8 items-start">
                <div class="flex-shrink-0 w-20 h-20 bg-blue-50 rounded-2xl flex items-center justify-center text-4xl">🤝</div>
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">{{ t('target.segments.partners.title') }}</h3>
                    <p class="text-slate-600 mb-6">{{ t('target.segments.partners.text') }}</p>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                            <strong class="text-red-800 block mb-1">{{ t('target.label_pain') }}</strong>
                            <span class="text-red-700 text-sm">{{ t('target.segments.partners.pain') }}</span>
                        </div>
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                            <strong class="text-green-800 block mb-1">{{ t('target.label_solution') }}</strong>
                            <span class="text-green-700 text-sm">{{ t('target.segments.partners.solution') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Власник-Операціоніст -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-8 flex flex-col md:flex-row gap-8 items-start">
                <div class="flex-shrink-0 w-20 h-20 bg-orange-50 rounded-2xl flex items-center justify-center text-4xl">🤯</div>
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">{{ t('target.segments.operator.title') }}</h3>
                    <p class="text-slate-600 mb-6">{{ t('target.segments.operator.text') }}</p>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                            <strong class="text-red-800 block mb-1">{{ t('target.label_pain') }}</strong>
                            <span class="text-red-700 text-sm">{{ t('target.segments.operator.pain') }}</span>
                        </div>
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                            <strong class="text-green-800 block mb-1">{{ t('target.label_solution') }}</strong>
                            <span class="text-green-700 text-sm">{{ t('target.segments.operator.solution') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Інвестор -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-8 flex flex-col md:flex-row gap-8 items-start">
                <div class="flex-shrink-0 w-20 h-20 bg-purple-50 rounded-2xl flex items-center justify-center text-4xl">💼</div>
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">{{ t('target.segments.investor.title') }}</h3>
                    <p class="text-slate-600 mb-6">{{ t('target.segments.investor.text') }}</p>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                            <strong class="text-red-800 block mb-1">{{ t('target.label_pain') }}</strong>
                            <span class="text-red-700 text-sm">{{ t('target.segments.investor.pain') }}</span>
                        </div>
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                            <strong class="text-green-800 block mb-1">{{ t('target.label_solution') }}</strong>
                            <span class="text-green-700 text-sm">{{ t('target.segments.investor.solution') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Масштабування -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-8 flex flex-col md:flex-row gap-8 items-start">
                <div class="flex-shrink-0 w-20 h-20 bg-blue-50 rounded-2xl flex items-center justify-center text-4xl">📈</div>
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">{{ t('target.segments.growing.title') }}</h3>
                    <p class="text-slate-600 mb-6">{{ t('target.segments.growing.text') }}</p>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                            <strong class="text-red-800 block mb-1">{{ t('target.label_pain') }}</strong>
                            <span class="text-red-700 text-sm">{{ t('target.segments.growing.pain') }}</span>
                        </div>
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                            <strong class="text-green-800 block mb-1">{{ t('target.label_solution') }}</strong>
                            <span class="text-green-700 text-sm">{{ t('target.segments.growing.solution') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews -->
        <div class="mt-20">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-slate-900">{{ t('target.reviews.title') }}</h2>
                <p class="text-slate-600 mt-2">{{ t('target.reviews.subtitle') }}</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-slate-50 p-8 rounded-2xl border border-slate-100">
                    <p class="text-slate-600 italic mb-6">{{ t('target.reviews.items.0.text') }}</p>
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">Д</div>
                        <div>
                            <div class="font-bold text-slate-900">{{ t('target.reviews.items.0.name') }}</div>
                            <div class="text-xs text-slate-500">{{ t('target.reviews.items.0.role') }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 p-8 rounded-2xl border border-slate-100">
                    <p class="text-slate-600 italic mb-6">{{ t('target.reviews.items.1.text') }}</p>
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center text-white font-bold">О</div>
                        <div>
                            <div class="font-bold text-slate-900">{{ t('target.reviews.items.1.name') }}</div>
                            <div class="text-xs text-slate-500">{{ t('target.reviews.items.1.role') }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 p-8 rounded-2xl border border-slate-100">
                    <p class="text-slate-600 italic mb-6">{{ t('target.reviews.items.2.text') }}</p>
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold">А</div>
                        <div>
                            <div class="font-bold text-slate-900">{{ t('target.reviews.items.2.name') }}</div>
                            <div class="text-xs text-slate-500">{{ t('target.reviews.items.2.role') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-16 text-center">
            <a href="{{ lurl('/pricing') }}" class="inline-block bg-primary text-white font-bold text-lg px-10 py-4 rounded-xl shadow-lg hover:bg-blue-700 transition duration-300">
                {{ t('target.cta.start') }}
            </a>
        </div>
    </div>
@endsection