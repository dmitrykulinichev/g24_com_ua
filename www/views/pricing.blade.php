@extends('layout')

@section('content')
    <!-- Hero Section (Збільшено padding-top) -->
    <section class="relative pt-32 pb-20 lg:pt-40 bg-slate-900 text-white overflow-hidden">
        <!-- Декоративний фон -->
        <div class="absolute inset-0 bg-[url('/assets/img/grid.svg')] opacity-10"></div>
        <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-blue-900/50 to-transparent"></div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <div class="inline-block bg-blue-600/30 border border-blue-500/50 rounded-full px-4 py-1 mb-6 text-blue-200 text-sm font-medium">
                {{ t('pricing.hero.badge') }}
            </div>
            <h1 class="text-4xl font-extrabold sm:text-6xl mb-6 leading-tight">
                {{ t('pricing.hero.title_1') }} <br class="hidden sm:block">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">{{ t('pricing.hero.title_2') }}</span>
            </h1>
            <p class="text-xl text-slate-300 max-w-2xl mx-auto mb-10">
                {{ t('pricing.hero.subtitle') }}
            </p>
        </div>
    </section>

    <!-- Pricing Formula Card -->
    <section class="relative -mt-16 pb-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-5xl mx-auto bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden">
                <div class="p-8 sm:p-12">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-slate-900">{{ t('pricing.formula.title') }}</h2>
                        <p class="text-slate-500 mb-6">{{ t('pricing.formula.subtitle') }}</p>

                        <!-- Toggle Switch -->
                        <div class="flex items-center justify-center gap-4 mb-8">
                            <span class="text-sm font-medium" :class="!yearly ? 'text-slate-900' : 'text-slate-500'">{{ t('pricing.formula.monthly') }}</span>
                            <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                                <input type="checkbox" name="toggle" id="toggle" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer transition-all duration-300" :class="yearly ? 'right-0 border-primary' : 'left-0 border-slate-300'" @click="yearly = !yearly"/>
                                <label for="toggle" class="toggle-label block overflow-hidden h-6 rounded-full cursor-pointer transition-colors duration-300" :class="yearly ? 'bg-primary' : 'bg-slate-300'"></label>
                            </div>
                            <span class="text-sm font-medium" :class="yearly ? 'text-slate-900' : 'text-slate-500'">
                                {{ t('pricing.formula.yearly') }}
                                <span class="ml-1 inline-block bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full font-bold">{{ t('pricing.formula.yearly_discount') }}</span>
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row items-center justify-center gap-8 mb-12 relative">
                        <!-- Base Price -->
                        <div class="flex-1 text-center p-6 bg-slate-50 rounded-2xl border border-slate-100 w-full transition-all duration-300" :class="yearly ? 'ring-2 ring-primary/20' : ''">
                            <div class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">{{ t('pricing.formula.base') }}</div>
                            <div class="text-5xl font-extrabold text-slate-900 mb-2">
                                <span x-text="yearly ? '{{ $model['yearly']['base'] }}' : '{{ $model['monthly']['base'] }}'"></span>
                                <span class="text-2xl font-medium text-slate-400">{{ t('pricing.currency') }}</span>
                            </div>
                            <div class="text-slate-600 font-medium">{{ t('pricing.formula.base_text') }}</div>
                            <div class="text-xs text-slate-400 mt-2" x-show="!yearly">{{ t('pricing.formula.base_note_monthly') }}</div>
                            <div class="text-xs text-primary font-bold mt-2" x-show="yearly" style="display: none;">{{ t('pricing.formula.base_note_yearly') }}</div>
                        </div>

                        <!-- Plus Sign -->
                        <div class="text-4xl text-slate-300 font-light hidden md:block">+</div>

                        <!-- Car Price -->
                        <div class="flex-1 text-center p-6 bg-blue-50 rounded-2xl border border-blue-100 w-full relative overflow-hidden transition-all duration-300" :class="yearly ? 'bg-green-50 border-green-200' : ''">
                            <div class="absolute top-0 right-0 text-white text-xs font-bold px-2 py-1 rounded-bl-lg transition-colors duration-300" :class="yearly ? 'bg-green-500' : 'bg-blue-500'" x-text="yearly ? {{ t_js('pricing.formula.badge_yearly') }} : {{ t_js('pricing.formula.badge_monthly') }}"></div>
                            <div class="text-sm font-bold uppercase tracking-wider mb-2 transition-colors duration-300" :class="yearly ? 'text-green-500' : 'text-blue-400'">{{ t('pricing.formula.scale') }}</div>

                            <div class="mb-2 h-12 flex items-center justify-center">
                                <div x-show="!yearly" class="text-5xl font-extrabold text-primary transition-all duration-300">
                                    {{ $model['monthly']['car'] }} <span class="text-2xl font-medium text-blue-300">{{ t('pricing.currency') }}</span>
                                </div>
                                <div x-show="yearly" style="display: none;" class="text-5xl font-extrabold text-green-600 transition-all duration-300 flex items-center gap-2">
                                    {{ $model['yearly']['car'] }} <span class="text-2xl font-medium text-green-300">{{ t('pricing.currency') }}</span>
                                    <span class="text-lg text-slate-400 line-through decoration-2 decoration-red-400 opacity-60">{{ $model['yearly']['old_car'] }}</span>
                                </div>
                            </div>

                            <div class="text-slate-600 font-medium">{{ t('pricing.formula.per_car') }}</div>
                            <div class="text-xs text-slate-400 mt-2">{{ t('pricing.formula.per_month') }}</div>
                        </div>
                    </div>

                    <!-- Active Car Explanation -->
                    <div class="bg-yellow-50 rounded-xl p-6 border border-yellow-100 flex gap-4 items-start">
                        <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-600 text-xl">💡</div>
                        <div>
                            <h3 class="font-bold text-yellow-900 mb-1">{{ t('pricing.active_car.title') }}</h3>
                            <p class="text-yellow-800 text-sm leading-relaxed">
                                {!! t('pricing.active_car.text', ['condition' => e(t('pricing.active_car.condition'))]) !!}
                            </p>
                        </div>
                    </div>

                    <div class="mt-10 text-center">
                        <button @click="$dispatch('open-order-modal', { type: yearly ? 'yearly' : 'monthly' })" class="inline-block bg-primary text-white font-bold text-lg px-12 py-4 rounded-xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-600/40 transition duration-300 transform hover:-translate-y-1 w-full sm:w-auto">
                            <span x-text="yearly ? {{ t_js('pricing.cta.yearly') }} : {{ t_js('pricing.cta.monthly') }}"></span>
                        </button>
                        <div class="mt-4 flex flex-col sm:flex-row items-center justify-center gap-2 text-sm text-slate-400">
                            <span class="inline-flex items-center gap-1"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>{{ t('pricing.cta.no_card') }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ t('pricing.cta.invoice_note') }}</span>
                        </div>
                        <div class="mt-2">
                            <a href="{{ lurl('/blog/how-billing-works') }}" class="text-primary hover:text-blue-700 text-sm font-medium underline decoration-dashed underline-offset-4">
                                {{ t('pricing.cta.billing_link') }}
                            </a>
                        </div>

                        <!-- Trust Block -->
                        <div class="mt-8 pt-8 border-t border-slate-100 max-w-2xl mx-auto">
                            <p class="text-slate-500 italic text-sm">
                                {{ t('pricing.trust') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why it's better -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-slate-900">{{ t('pricing.why.title') }}</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="text-3xl mb-4">📉</div>
                    <h3 class="font-bold text-lg mb-2">{{ t('pricing.why.items.0.title') }}</h3>
                    <p class="text-slate-600 text-sm">{{ t('pricing.why.items.0.text') }}</p>
                </div>
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="text-3xl mb-4">🚀</div>
                    <h3 class="font-bold text-lg mb-2">{{ t('pricing.why.items.1.title') }}</h3>
                    <p class="text-slate-600 text-sm">{{ t('pricing.why.items.1.text') }}</p>
                </div>
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="text-3xl mb-4">🔓</div>
                    <h3 class="font-bold text-lg mb-2">{{ t('pricing.why.items.2.title') }}</h3>
                    <p class="text-slate-600 text-sm">{{ t('pricing.why.items.2.text') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- All Included List -->
    <section class="py-20 bg-slate-900 text-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-12 items-start">
                <div class="md:w-1/3">
                    <h2 class="text-3xl font-bold mb-6">{{ t('pricing.included.title') }}</h2>
                    <p class="text-slate-400 mb-8">
                        {{ t('pricing.included.text') }}
                    </p>
                    <div class="p-6 bg-slate-800 rounded-xl border border-slate-700">
                        <div class="text-yellow-400 font-bold mb-2">{{ t('pricing.included.bonus.title') }}</div>
                        <p class="text-sm text-slate-300 mb-4">{{ t('pricing.included.bonus.text') }}</p>
                        <div class="text-2xl font-bold text-white mb-4">-{{ $model['yearly']['old_car'] - $model['yearly']['car'] }} {{ t('pricing.currency') }} <span class="text-sm font-normal text-slate-400">{{ t('pricing.included.bonus.per_car_month') }}</span></div>
                        <button @click="$dispatch('open-order-modal', { type: 'yearly' })" class="w-full bg-yellow-500 hover:bg-yellow-400 text-slate-900 font-bold py-2 px-4 rounded-lg transition text-sm">
                            {{ t('pricing.cta.yearly') }}
                        </button>
                    </div>
                </div>

                <div class="md:w-2/3 grid sm:grid-cols-2 gap-6">
                    @foreach($model['features'] as $feature)
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-500/20 flex items-center justify-center mt-0.5">
                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-lg text-slate-200">{{ $feature }}</span>
                        </div>
                    @endforeach
                    <!-- Додаткові фічі -->
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-500/20 flex items-center justify-center mt-0.5">
                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-lg text-slate-200">{{ t('pricing.included.extra.0') }}</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-500/20 flex items-center justify-center mt-0.5">
                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-lg text-slate-200">{{ t('pricing.included.extra.1') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-slate-900">{{ t('pricing.faq.title') }}</h2>
            </div>

            <div class="space-y-4" x-data="{ active: null }">
                <div class="border border-slate-200 rounded-lg overflow-hidden">
                    <button @click="active = (active === 1 ? null : 1)" class="w-full px-6 py-4 text-left bg-slate-50 hover:bg-slate-100 flex justify-between items-center font-semibold text-slate-900 transition">
                        <span>{{ t('pricing.faq.items.0.q') }}</span>
                        <span x-text="active === 1 ? '−' : '+'" class="text-xl text-slate-400"></span>
                    </button>
                    <div x-show="active === 1" class="px-6 py-4 text-slate-600 border-t border-slate-200 bg-white text-sm">
                        {{ t('pricing.faq.items.0.a') }}
                    </div>
                </div>

                <div class="border border-slate-200 rounded-lg overflow-hidden">
                    <button @click="active = (active === 2 ? null : 2)" class="w-full px-6 py-4 text-left bg-slate-50 hover:bg-slate-100 flex justify-between items-center font-semibold text-slate-900 transition">
                        <span>{{ t('pricing.faq.items.1.q') }}</span>
                        <span x-text="active === 2 ? '−' : '+'" class="text-xl text-slate-400"></span>
                    </button>
                    <div x-show="active === 2" class="px-6 py-4 text-slate-600 border-t border-slate-200 bg-white text-sm">
                        {!! t('pricing.faq.items.1.a') !!}
                    </div>
                </div>

                <div class="border border-slate-200 rounded-lg overflow-hidden">
                    <button @click="active = (active === 3 ? null : 3)" class="w-full px-6 py-4 text-left bg-slate-50 hover:bg-slate-100 flex justify-between items-center font-semibold text-slate-900 transition">
                        <span>{{ t('pricing.faq.items.2.q') }}</span>
                        <span x-text="active === 3 ? '−' : '+'" class="text-xl text-slate-400"></span>
                    </button>
                    <div x-show="active === 3" class="px-6 py-4 text-slate-600 border-t border-slate-200 bg-white text-sm">
                        {{ t('pricing.faq.items.2.a') }}
                    </div>
                </div>

                <!-- Нове питання про автосписання -->
                <div class="border border-slate-200 rounded-lg overflow-hidden">
                    <button @click="active = (active === 4 ? null : 4)" class="w-full px-6 py-4 text-left bg-slate-50 hover:bg-slate-100 flex justify-between items-center font-semibold text-slate-900 transition">
                        <span>{{ t('pricing.faq.items.3.q') }}</span>
                        <span x-text="active === 4 ? '−' : '+'" class="text-xl text-slate-400"></span>
                    </button>
                    <div x-show="active === 4" class="px-6 py-4 text-slate-600 border-t border-slate-200 bg-white text-sm">
                        {{ t('pricing.faq.items.3.a') }}
                    </div>
                </div>

                <div class="border border-slate-200 rounded-lg overflow-hidden">
                    <button @click="active = (active === 5 ? null : 5)" class="w-full px-6 py-4 text-left bg-slate-50 hover:bg-slate-100 flex justify-between items-center font-semibold text-slate-900 transition">
                        <span>{{ t('pricing.faq.items.4.q') }}</span>
                        <span x-text="active === 5 ? '−' : '+'" class="text-xl text-slate-400"></span>
                    </button>
                    <div x-show="active === 5" class="px-6 py-4 text-slate-600 border-t border-slate-200 bg-white text-sm">
                        {{ t('pricing.faq.items.4.a') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enterprise Link -->
    <section class="py-16 bg-slate-50 border-t border-slate-200">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto bg-white rounded-2xl p-8 shadow-lg border border-slate-100 flex flex-col md:flex-row items-center gap-8">
                <div class="flex-1 text-center md:text-left">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">{{ t('pricing.partnership.title') }}</h3>
                    <p class="text-slate-600 text-sm mb-4">
                        {{ t('pricing.partnership.text') }}
                    </p>
                    <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ t('pricing.partnership.tags.0') }}</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">{{ t('pricing.partnership.tags.1') }}</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">{{ t('pricing.partnership.tags.2') }}</span>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <button @click="$dispatch('open-order-modal', { type: 'enterprise' })" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-slate-800 hover:bg-slate-700 transition shadow-md">
                        {{ t('pricing.partnership.cta') }}
                        <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
    <style>
        /* Toggle Switch Styles */
        .toggle-checkbox:checked {
            right: 0;
            border-color: #2563eb;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #2563eb;
        }
    </style>
    @endpush
@endsection