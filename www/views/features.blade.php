@extends('layout')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-slate-900 text-white py-20 lg:py-28 overflow-hidden pt-32 lg:pt-40">
        <div class="absolute inset-0 bg-[url('/assets/img/grid.svg')] opacity-10"></div>

        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl mb-6">Можливості системи</h1>
            <p class="text-xl text-slate-300 max-w-2xl mx-auto">Garage24 — це комплексне рішення, яке закриває всі потреби сучасного автопарку.</p>
        </div>
    </div>

    <!-- Features List -->
    <div class="bg-slate-50 py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">

            <div class="space-y-16">
                @foreach($features as $categoryName => $category)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <!-- Header -->
                        <div class="p-6 md:p-8 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:items-center gap-4 md:gap-6">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-2xl text-white shadow-md">
                                {!! $category['icon'] !!}
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-slate-900">{{ $categoryName }}</h2>
                                @if(isset($category['slogan']))
                                    <p class="text-slate-600 mt-1">{{ $category['slogan'] }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Items -->
                        <div class="p-6 md:p-8">
                            <dl class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-8">
                                @foreach($category['items'] as $item)
                                    <div class="relative pl-8">
                                        <dt class="font-bold text-slate-900 text-base flex items-center mb-2">
                                            <svg class="absolute left-0 top-1 h-5 w-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg>
                                            {{ $item['title'] }}
                                        </dt>
                                        <dd class="text-sm leading-relaxed text-slate-600">{{ $item['desc'] }}</dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-white border-t border-slate-200 py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Готові оптимізувати свій бізнес?</h2>
            <p class="text-slate-600 mb-8 max-w-2xl mx-auto text-lg">Отримайте повний доступ до всіх функцій. Оплата тільки за активні авто в кінці місяця.</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="/pricing" class="inline-block bg-blue-600 text-white font-bold text-lg px-8 py-3 rounded-xl shadow-lg hover:bg-blue-700 transition duration-300">
                    Почати роботу
                </a>
                <a href="/docs" class="inline-block text-slate-700 font-semibold text-lg px-8 py-3 hover:text-blue-600 transition duration-300">
                    Документація →
                </a>
            </div>
        </div>
    </div>
@endsection