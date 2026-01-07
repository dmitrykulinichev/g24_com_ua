<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title та Meta тепер у header.blade.php -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;800&display=swap" rel="stylesheet">
    <style>
        .docs-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            display: flex;
            gap: 3rem;
            min-height: 70vh;
        }
        .sidebar {
            width: 250px;
            flex-shrink: 0;
            border-right: 1px solid #e5e7eb;
            padding-right: 1rem;
        }
        .sidebar-group {
            margin-bottom: 1.5rem;
        }
        .sidebar-title {
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar li {
            margin-bottom: 0.25rem;
        }
        .sidebar a {
            text-decoration: none;
            color: #4b5563;
            display: block;
            padding: 0.35rem 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.2s;
            font-size: 0.95rem;
        }
        .sidebar a:hover {
            color: var(--primary-color);
            background-color: #f3f4f6;
        }
        .sidebar a.active {
            color: var(--primary-color);
            background-color: #eff6ff;
            font-weight: 600;
        }
        .content {
            flex: 1;
            min-width: 0;
        }
        /* Стилі для Markdown контенту */
        .content h1 { margin-bottom: 1.5rem; font-size: 2.25rem; line-height: 1.2; }
        .content h2 { margin-top: 2.5rem; margin-bottom: 1rem; font-size: 1.5rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem; }
        .content h3 { margin-top: 2rem; margin-bottom: 0.75rem; font-size: 1.25rem; }
        .content p { margin-bottom: 1rem; line-height: 1.7; color: #374151; }
        .content ul, .content ol { margin-bottom: 1rem; padding-left: 1.5rem; }
        .content li { margin-bottom: 0.5rem; }
        .content code { background: #f3f4f6; padding: 0.2rem 0.4rem; border-radius: 0.25rem; font-family: monospace; font-size: 0.9em; }
        .content pre { background: #1f2937; color: #fff; padding: 1rem; border-radius: 0.5rem; overflow-x: auto; margin-bottom: 1.5rem; }
        .content pre code { background: none; padding: 0; color: inherit; }
        .content blockquote { border-left: 4px solid var(--primary-color); padding-left: 1rem; margin-left: 0; color: #4b5563; font-style: italic; background: #f9fafb; padding: 1rem; border-radius: 0 0.5rem 0.5rem 0; }
        .content table { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; }
        .content th, .content td { border: 1px solid #e5e7eb; padding: 0.75rem; text-align: left; }
        .content th { background-color: #f9fafb; font-weight: 600; }
        .content img { max-width: 100%; border-radius: 0.5rem; margin: 1rem 0; border: 1px solid #e5e7eb; }

        /* Стиль для підсвічування */
        mark {
            background-color: #fef08a; /* Жовтий */
            padding: 0.1rem 0.2rem;
            border-radius: 0.2rem;
        }

        /* Навігація між статтями */
        .docs-nav {
            margin-top: 4rem;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            gap: 1rem;
        }
        .nav-item {
            text-decoration: none;
            padding: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            width: 48%;
            transition: all 0.2s;
        }
        .nav-item:hover {
            border-color: var(--primary-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .nav-label {
            display: block;
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }
        .nav-title {
            display: block;
            font-weight: 600;
            color: var(--primary-color);
        }
        .nav-next {
            text-align: right;
            margin-left: auto;
        }
    </style>
</head>
<body>
    @include('partials.header')

    <div class="docs-container">
        <!-- Сайдбар з Alpine.js -->
        <aside class="sidebar" x-data="{ docsMenuOpen: false }">

            <!-- Кнопка для мобільного (видима тільки через CSS на малих екранах) -->
            <button class="docs-menu-toggle" @click="docsMenuOpen = !docsMenuOpen">
                <span>Зміст розділу</span>
                <!-- Стрілка вниз/вгору -->
                <svg :class="{'rotate-180': docsMenuOpen}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            <!-- Контент меню (на мобільному ховається, на десктопі завжди видно) -->
            <div class="sidebar-content" :class="{ 'mobile-hidden': !docsMenuOpen }">

                <!-- Пошук у сайдбарі -->
                <div style="margin-bottom: 2rem;">
                    @include('partials.docs-search')
                </div>

                @foreach($menu as $group)
                    <div class="sidebar-group">
                        <div class="sidebar-title">{{ $group['title'] }}</div>
                        <ul>
                            @foreach($group['items'] as $item)
                                <li>
                                    <a href="/docs/{{ $item['slug'] }}" class="{{ $slug === $item['slug'] ? 'active' : '' }}">
                                        {{ $item['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </aside>

        <article class="content" id="docsContent">
            {!! $content !!}

            <div class="docs-nav">
                @if($prev)
                    <a href="/docs/{{ $prev['slug'] }}" class="nav-item nav-prev">
                        <span class="nav-label">← Попередня</span>
                        <span class="nav-title">{{ $prev['title'] }}</span>
                    </a>
                @endif

                @if($next)
                    <a href="/docs/{{ $next['slug'] }}" class="nav-item nav-next">
                        <span class="nav-label">Наступна →</span>
                        <span class="nav-title">{{ $next['title'] }}</span>
                    </a>
                @endif
            </div>
        </article>
    </div>

    @include('partials.footer')

    <!-- Скрипт для підсвічування тексту -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const query = urlParams.get('highlight');

            if (query) {
                // 1. Вставляємо запит в інпут пошуку
                const searchInput = document.getElementById('searchInput');
                if (searchInput) {
                    searchInput.value = query;
                }

                // 2. Підсвічуємо текст
                const content = document.getElementById('docsContent');
                if (content) {
                    const regex = new RegExp(`(${query})`, 'gi');

                    // Проходимо по всіх текстових вузлах, щоб не зламати HTML теги
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
</body>
</html>