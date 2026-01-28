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

@push('styles')
<style>
    /* Стилі контенту (Prose) */
    .content h1 { @apply text-3xl sm:text-4xl font-bold text-slate-900 mb-6 mt-8 leading-tight; }
    .content h2 { @apply text-2xl font-bold text-slate-800 mb-4 mt-8 border-b border-slate-200 pb-2; }
    .content h3 { @apply text-xl font-bold text-slate-800 mb-3 mt-6; }
    .content p { @apply text-lg text-slate-700 mb-6 leading-relaxed; }
    .content ul { @apply list-disc list-outside ml-6 mb-6 text-slate-700; }
    .content ol { @apply list-decimal list-outside ml-6 mb-6 text-slate-700; }
    .content li { @apply mb-2; }
    .content img { @apply rounded-xl border border-slate-200 my-8 w-full h-auto shadow-sm; }
    .content blockquote { @apply border-l-4 border-primary pl-4 italic text-slate-600 bg-slate-50 p-4 rounded-r-lg my-6; }
    .content code { @apply bg-slate-100 text-slate-800 px-1.5 py-0.5 rounded text-sm font-mono; }
    .content pre { @apply bg-slate-800 text-slate-100 p-4 rounded-lg overflow-x-auto mb-6; }
    .content pre code { @apply bg-transparent text-inherit p-0; }

    /* Стиль для підсвічування */
    mark {
        background-color: #fef08a;
        padding: 0.1rem 0.2rem;
        border-radius: 0.2rem;
    }
</style>
@endpush

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