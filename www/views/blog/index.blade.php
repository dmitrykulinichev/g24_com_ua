@extends('layout')

@section('content')
    <div class="pt-32 lg:pt-40 pb-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-3xl font-bold text-slate-900 sm:text-4xl mb-4">Блог розробників</h1>
                <p class="text-lg text-slate-600">Новини, оновлення та корисні поради щодо використання Garage24.</p>

                <!-- Пошук по блогу -->
                <div class="max-w-lg mx-auto mt-8">
                    @include('partials.blog-search')
                </div>
            </div>

            @if(count($posts) > 0)
                <div class="space-y-8">
                    @foreach($posts as $post)
                        <article class="post-card bg-white border border-slate-200 rounded-xl overflow-hidden flex shadow-sm hover:shadow-md transition-shadow duration-300">
                            @if(!empty($post['image']))
                                <a href="/blog/{{ $post['slug'] }}" class="post-image">
                                    <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}" loading="lazy">
                                </a>
                            @endif

                            <div class="flex flex-col flex-grow p-6 sm:p-8">
                                <div class="text-sm text-slate-500 mb-2">{{ date('d.m.Y', $post['date']) }}</div>
                                <h2 class="text-xl font-bold text-slate-900 mb-3 leading-tight">
                                    <a href="/blog/{{ $post['slug'] }}" class="hover:text-primary transition-colors">{{ $post['title'] }}</a>
                                </h2>
                                <p class="text-slate-600 mb-6 line-clamp-3 flex-grow">{{ $post['preview'] }}</p>
                                <a href="/blog/{{ $post['slug'] }}" class="inline-flex items-center text-primary font-medium hover:underline mt-auto">
                                    Читати далі
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 ml-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <p class="text-center text-slate-500 py-12">Поки що немає новин.</p>
            @endif
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* Стилі для зображення */
    .post-image {
        display: block;
        width: 280px; /* Фіксована ширина */
        flex-shrink: 0; /* Не стискати */
        border-right: 1px solid #e5e7eb; /* Розділювач */
        border-bottom: none;
        background-color: #f3f4f6;
        position: relative;
    }
    .post-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: transform 0.5s ease;
        position: absolute; /* Абсолютне позиціонування для cover */
        top: 0;
        left: 0;
    }
    .post-card:hover .post-image img {
        transform: scale(1.05);
    }

    /* Мобільна адаптація */
    @media (max-width: 768px) {
        .post-card {
            flex-direction: column; /* Вертикально на мобільному */
        }
        .post-image {
            width: 100%;
            height: 200px;
            border-right: none;
            border-bottom: 1px solid #e5e7eb;
        }
        .post-image img {
            position: static; /* Повертаємо звичайну поведінку */
            height: 100%;
        }
    }
</style>
@endpush