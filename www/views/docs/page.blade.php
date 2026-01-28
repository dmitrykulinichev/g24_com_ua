@extends('layout')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 lg:pt-40 pb-20 flex flex-col lg:flex-row gap-12 relative">
        <!-- Сайдбар з Alpine.js -->
        <aside class="w-full lg:w-64 flex-shrink-0 sidebar" x-data="{ docsMenuOpen: false }">
            <button class="lg:hidden w-full flex items-center justify-between p-4 bg-slate-50 rounded-lg border border-slate-200 mb-4 text-slate-700 font-medium" @click="docsMenuOpen = !docsMenuOpen">
                <span>Зміст розділу</span>
                <svg :class="{'rotate-180': docsMenuOpen}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            <div class="lg:block" :class="{ 'hidden': !docsMenuOpen }">
                <div class="mb-6">
                    @include('partials.docs-search')
                </div>

                @foreach($menu as $group)
                    <div class="mb-6">
                        <div class="font-bold text-slate-900 uppercase text-xs tracking-wider mb-2">{{ $group['title'] }}</div>
                        <ul class="space-y-1">
                            @foreach($group['items'] as $item)
                                <li>
                                    <a href="/docs/{{ $item['slug'] }}"
                                       class="block px-3 py-2 rounded-md text-sm transition-colors duration-200
                                              {{ $slug === $item['slug'] ? 'bg-blue-50 text-primary font-medium' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                        {{ $item['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </aside>

        <article class="flex-1 min-w-0 content" id="docsContent">
            {!! $content !!}

            <!-- Підключення компонента навігації -->
            @include('partials.navigation-buttons', [
                'prevLink' => $prev ? "/docs/{$prev['slug']}" : null,
                'prevTitle' => $prev['title'] ?? null,
                'nextLink' => $next ? "/docs/{$next['slug']}" : null,
                'nextTitle' => $next['title'] ?? null
            ])
        </article>
    </div>

    <!-- Toast Notification -->
    <div id="navToast" class="toast-notification">
        <span id="toastMessage"></span>
    </div>
@endsection

@push('styles')
<style>
    /* Sticky Sidebar */
    .sidebar {
        position: sticky;
        top: 6rem; /* Відступ від верху (враховуючи хедер) */
        height: calc(100vh - 7rem);
        overflow-y: auto;
    }

    /* Стилізація скролбару */
    .sidebar::-webkit-scrollbar { width: 4px; }
    .sidebar::-webkit-scrollbar-track { background: #f1f1f1; }
    .sidebar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 2px; }
    .sidebar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }

    /* Стилі контенту (Prose) */
    .content h1 { @apply text-3xl sm:text-4xl font-bold text-slate-900 mb-6 leading-tight; }
    .content h2 { @apply text-2xl font-bold text-slate-800 mb-4 mt-10 border-b border-slate-200 pb-2; }
    .content h3 { @apply text-xl font-bold text-slate-800 mb-3 mt-8; }
    .content p { @apply text-lg text-slate-700 mb-6 leading-relaxed; }
    .content ul { @apply list-disc list-outside ml-6 mb-6 text-slate-700; }
    .content ol { @apply list-decimal list-outside ml-6 mb-6 text-slate-700; }
    .content li { @apply mb-2; }
    .content img { @apply rounded-xl border border-slate-200 my-8 w-full h-auto shadow-sm; }
    .content blockquote { @apply border-l-4 border-primary pl-4 italic text-slate-600 bg-slate-50 p-4 rounded-r-lg my-6; }
    .content code { @apply bg-slate-100 text-slate-800 px-1.5 py-0.5 rounded text-sm font-mono; }
    .content pre { @apply bg-slate-800 text-slate-100 p-4 rounded-lg overflow-x-auto mb-6; }
    .content pre code { @apply bg-transparent text-inherit p-0; }
    .content table { @apply w-full border-collapse mb-6; }
    .content th { @apply border border-slate-200 p-3 text-left bg-slate-50 font-bold text-slate-700; }
    .content td { @apply border border-slate-200 p-3 text-slate-600; }

    /* Стиль для підсвічування */
    mark {
        background-color: #fef08a;
        padding: 0.1rem 0.2rem;
        border-radius: 0.2rem;
    }

    @media (max-width: 1024px) {
        .sidebar {
            position: static;
            height: auto;
            border-right: none;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 2rem;
            margin-bottom: 2rem;
        }
    }
</style>
@endpush

@push('scripts')
<!-- Скрипт для Lightbox -->
<script src="/assets/js/docs.js?v={{ time() }}"></script>

<!-- Скрипт для підсвічування тексту та навігації -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Підсвічування тексту
        const urlParams = new URLSearchParams(window.location.search);
        const query = urlParams.get('highlight');

        if (query) {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) { searchInput.value = query; }

            const content = document.getElementById('docsContent');
            if (content) {
                const regex = new RegExp(`(${query})`, 'gi');
                const walk = document.createTreeWalker(content, NodeFilter.SHOW_TEXT, null, false);
                let node;
                while (node = walk.nextNode()) {
                    if (node.nodeValue.match(regex)) {
                        const span = document.createElement('span');
                        span.innerHTML = node.nodeValue.replace(regex, '<mark>$1</mark>');
                        node.parentNode.replaceChild(span, node);
                    }
                }
            }
        }
    });
</script>
@endpush