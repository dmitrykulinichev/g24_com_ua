@extends('layout')

@section('content')
    <div class="pt-32 lg:pt-40 pb-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 bg-gradient-to-br from-blue-50 to-white p-10 rounded-3xl border border-blue-100">
                <h1 class="text-3xl font-bold text-slate-900 sm:text-4xl mb-4">База знань Garage24</h1>
                <p class="text-lg text-slate-600 mb-8">Інструкції, поради та відповіді на часті запитання.</p>

                <div class="max-w-xl mx-auto">
                    @include('partials.docs2-search')
                </div>
            </div>

            @foreach($menu as $group)
                <div class="mb-16">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6 pb-2 border-b border-slate-100 flex items-center">
                        {{ $group['title'] }}
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($group['items'] as $item)
                            <a href="/docs2/{{ $item['slug'] }}" class="group bg-white border border-slate-200 rounded-xl overflow-hidden hover:shadow-xl hover:border-primary/30 transition-all duration-300 flex flex-col h-full">
                                <!-- Зображення-прев'ю -->
                                <div class="h-40 bg-slate-100 overflow-hidden border-b border-slate-100 relative">
                                    @if(!empty($item['image']))
                                        <img src="/content/docs2/screenshots/light/desktop/{{ $item['image'] }}" alt="{{ $item['title'] }}" loading="lazy" class="w-full h-full object-cover object-top transform group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-12 h-12"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-300"></div>
                                </div>

                                <div class="p-6 flex flex-col flex-grow">
                                    <h3 class="text-lg font-bold text-slate-900 mb-2 group-hover:text-primary transition-colors">{{ $item['title'] }}</h3>
                                    <div class="text-sm text-slate-500 mb-4 flex-grow line-clamp-2">
                                        {{ $item['description'] ?? 'Перейти до розділу "' . $item['title'] . '".' }}
                                    </div>

                                    <div class="text-primary font-semibold text-sm flex items-center mt-auto group-hover:underline">
                                        Читати
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="mt-16 text-center bg-slate-50 rounded-2xl p-10 border border-slate-200">
                <h3 class="text-xl font-bold text-slate-900 mb-2">Не знайшли відповідь?</h3>
                <p class="text-slate-600 mb-6">Наша служба підтримки готова допомогти вам у будь-який час.</p>
                <button @click="$dispatch('open-order-modal', {})" class="inline-block bg-white border border-slate-300 text-slate-700 font-semibold px-6 py-3 rounded-lg hover:bg-slate-100 hover:text-primary transition shadow-sm">
                    Написати в підтримку
                </button>
            </div>
        </div>
    </div>
@endsection
