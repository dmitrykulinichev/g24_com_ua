@extends('layout')

@section('content')
    <div class="pt-32 lg:pt-40 pb-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="/blog" class="inline-flex items-center text-primary font-medium hover:underline mb-8 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Всі новини
            </a>

            <div class="text-sm text-slate-500 mb-4 border-b border-slate-100 pb-4">
                Опубліковано: {{ date('d.m.Y', $meta['date']) }}
            </div>

            <article class="content" id="blogContent">
                {!! $content !!}

                <!-- Навігація блогу (через компонент) -->
                @include('partials.navigation-buttons', [
                    'prevLink' => $older ? "/blog/{$older['slug']}" : null,
                    'prevTitle' => $older['title'] ?? null,
                    'nextLink' => $newer ? "/blog/{$newer['slug']}" : null,
                    'nextTitle' => $newer['title'] ?? null
                ])
            </article>
        </div>
    </div>
@endsection

@push('scripts')
<!-- Скрипт для підсвічування тексту -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const query = urlParams.get('highlight');

        if (query) {
            const content = document.getElementById('blogContent');
            if (content) {
                const regex = new RegExp(`(${query})`, 'gi');
                const walk = document.createTreeWalker(content, NodeFilter.SHOW_TEXT, null, false);
                let node;
                const nodesToReplace = [];

                while (node = walk.nextNode()) {
                    if (node.nodeValue.match(regex)) {
                        nodesToReplace.push(node);
                    }
                }

                nodesToReplace.forEach(node => {
                    const span = document.createElement('span');
                    span.innerHTML = node.nodeValue.replace(regex, '<mark>$1</mark>');
                    node.parentNode.replaceChild(span, node);
                });
            }
        }
    });
</script>
@endpush