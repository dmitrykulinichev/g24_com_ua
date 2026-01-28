@extends('layout')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-slate-50 pt-20 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full text-center">
            <div class="text-9xl font-extrabold text-primary mb-4">404</div>
            <h1 class="text-3xl font-bold text-slate-900 mb-4">Сторінку не знайдено</h1>
            <p class="text-lg text-slate-600 mb-8">
                Схоже, ви заблукали в гаражі. Сторінка, яку ви шукаєте, була переміщена або видалена.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="/" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-primary hover:bg-blue-700 transition duration-300 shadow-lg">
                    На головну
                </a>
                <a href="/contacts" class="inline-flex items-center justify-center px-6 py-3 border border-slate-300 text-base font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition duration-300 shadow-sm">
                    Зв'язатися з нами
                </a>
            </div>
        </div>
    </div>
@endsection