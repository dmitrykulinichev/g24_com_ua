@extends('layout')

@section('content')
    <section class="relative bg-slate-900 text-white pt-32 pb-16 lg:pt-40 lg:pb-24 text-center overflow-hidden">
        <div class="absolute inset-0 bg-[url('/assets/img/grid.svg')] opacity-10"></div>
        <div class="container mx-auto px-4 relative z-10">
            <h1 class="text-4xl font-extrabold sm:text-5xl mb-4">{{ $title }}</h1>
        </div>
    </section>

    <section class="py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
            <div class="prose prose-slate max-w-none">
                {!! $content !!}
            </div>
        </div>
    </section>
@endsection
