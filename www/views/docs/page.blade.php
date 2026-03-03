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

@push('scripts')
@php
    $baseUrl = rtrim($_ENV['APP_URL'] ?? ('https://' . $_SERVER['HTTP_HOST']), '/');
    $docTitle = $meta['title'] ?? $slug;
@endphp
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type": "ListItem", "position": 1, "name": "Garage24", "item": "{{ $baseUrl }}/"},
    {"@type": "ListItem", "position": 2, "name": "Документація", "item": "{{ $baseUrl }}/docs"},
    {"@type": "ListItem", "position": 3, "name": "{{ $docTitle }}", "item": "{{ $baseUrl }}/docs/{{ $slug }}"}
  ]
}
</script>
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