<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title та Meta тепер у header.blade.php -->
    <link rel="stylesheet" href="/assets/css/style.css?v={{ time() }}">
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
            position: relative;
        }
        .sidebar {
            width: 250px;
            flex-shrink: 0;
            border-right: 1px solid #e5e7eb;
            padding-right: 1rem;
            /* Sticky Sidebar */
            position: sticky;
            top: 2rem;
            height: calc(100vh - 4rem);
            overflow-y: auto;
        }

        /* Стилізація скролбару для сайдбару */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 2px;
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        .sidebar-group {
            margin-bottom: 1rem;
        }
        .sidebar-title {
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.25rem;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar li {
            margin-bottom: 0.1rem;
        }
        .sidebar a {
            text-decoration: none;
            color: #4b5563;
            display: block;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.2s;
            font-size: 0.9rem;
            line-height: 1.4;
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
            background-color: #fef08a;
            padding: 0.1rem 0.2rem;
            border-radius: 0.2rem;
        }

        /* Навігація між статтями (Оновлено: світліший стиль з контрастним ховером) */
        .docs-nav {
            margin-top: 4rem;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        .nav-item {
            text-decoration: none;
            padding: 1.25rem 1.5rem;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            /* Світлий стиль за замовчуванням */
            background-color: #fff;
            border: 1px solid #e5e7eb;
            color: #1f2937;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        /* Контрастний стиль при наведенні або активності */
        .nav-item:hover, .nav-item.active-press {
            background-color: #1f2937; /* Темний фон */
            border-color: #1f2937;
            color: white; /* Білий текст */
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
            transform: translateY(-3px);
        }

        .nav-item.active-press {
            transform: translateY(1px); /* Ефект натискання */
        }

        .nav-label {
            display: flex;
            align-items: center;
            font-size: 0.85rem;
            color: #6b7280; /* Сірий */
            margin-bottom: 0.5rem;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        /* Зміна кольору лейблу при наведенні */
        .nav-item:hover .nav-label, .nav-item.active-press .nav-label {
            color: #9ca3af; /* Світло-сірий */
        }

        .nav-title {
            font-weight: 600;
            color: var(--secondary-color); /* Темно-синій */
            font-size: 1.1rem;
            transition: color 0.2s ease;
        }

        /* Зміна кольору заголовка при наведенні */
        .nav-item:hover .nav-title, .nav-item.active-press .nav-title {
            color: white;
        }

        /* Вирівнювання */
        .nav-prev {
            grid-column: 1;
            text-align: left;
        }
        .nav-next {
            grid-column: 2;
            text-align: right;
            align-items: flex-end;
        }

        /* Якщо тільки одна кнопка */
        .nav-prev:only-child { grid-column: 1; }
        .nav-next:only-child { grid-column: 2; }

        /* Ghost Button Animation (Залишаємо темним для контрасту) */
        .nav-ghost {
            position: fixed;
            bottom: 2rem;
            z-index: 1000;
            background-color: rgba(31, 41, 55, 0.95);
            color: white;
            padding: 1.25rem 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            min-width: 200px;
            pointer-events: none;
            animation: ghostJump 0.6s ease-out forwards;
        }

        .nav-ghost.ghost-left {
            text-align: left;
        }

        .nav-ghost.ghost-right {
            text-align: right;
            align-items: flex-end;
        }

        .nav-ghost .nav-label {
            color: #9ca3af;
            display: flex;
            align-items: center;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .nav-ghost .nav-title {
            font-weight: 600;
            color: white;
            font-size: 1.1rem;
        }

        @keyframes ghostJump {
            0% {
                opacity: 0;
                transform: translateY(100%);
            }
            20% {
                opacity: 1;
                transform: translateY(0);
            }
            70% {
                opacity: 1;
                transform: translateY(0);
            }
            100% {
                opacity: 0;
                transform: translateY(-10px);
            }
        }

        @media (max-width: 768px) {
            .docs-container {
                display: block;
            }
            .sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #e5e7eb;
                padding-right: 0;
                padding-bottom: 1rem;
                margin-bottom: 2rem;
                position: static;
                height: auto;
            }
            .docs-nav {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            .nav-prev, .nav-next {
                grid-column: 1;
                text-align: center;
                align-items: center;
            }
            .nav-ghost {
                display: none;
            }
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
                <div style="margin-bottom: 1.5rem;">
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
                        <span class="nav-label">
                            <span style="margin-right: 0.5rem; font-size: 1.2em;">←</span>
                            Попередня
                        </span>
                        <span class="nav-title">{{ $prev['title'] }}</span>
                    </a>
                @endif

                @if($next)
                    <a href="/docs/{{ $next['slug'] }}" class="nav-item nav-next">
                        <span class="nav-label">
                            Наступна
                            <span style="margin-left: 0.5rem; font-size: 1.2em;">→</span>
                        </span>
                        <span class="nav-title">{{ $next['title'] }}</span>
                    </a>
                @endif
            </div>
        </article>
    </div>

    @include('partials.footer')

    <!-- Скрипт для Lightbox (збільшення зображень) -->
    <script src="/assets/js/docs.js?v={{ time() }}"></script>

    <!-- Скрипт для підсвічування тексту та навігації -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Підсвічування тексту
            const urlParams = new URLSearchParams(window.location.search);
            const query = urlParams.get('highlight');

            if (query) {
                const searchInput = document.getElementById('searchInput');
                if (searchInput) {
                    searchInput.value = query;
                }

                const content = document.getElementById('docsContent');
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

            // Перевірка видимості елемента
            function isElementInViewport(el) {
                const rect = el.getBoundingClientRect();
                return (
                    rect.top >= 0 &&
                    rect.left >= 0 &&
                    rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                    rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                );
            }

            // Функція створення Ghost Button
            function triggerGhostNav(direction, title, xPos) {
                const ghost = document.createElement('div');
                ghost.className = `nav-ghost ghost-${direction}`;

                // Встановлюємо горизонтальну позицію
                if (direction === 'left') {
                    ghost.style.left = xPos + 'px';
                } else {
                    // Для правої кнопки ми отримали rect.right, але CSS right працює від правого краю
                    // Тому: windowWidth - rect.right
                    const rightPos = document.documentElement.clientWidth - xPos;
                    ghost.style.right = rightPos + 'px';
                }

                let arrowHtml = '';
                if (direction === 'left') {
                    arrowHtml = `<span class="nav-label"><span style="margin-right: 0.5rem; font-size: 1.2em;">←</span> Попередня</span>`;
                } else {
                    arrowHtml = `<span class="nav-label">Наступна <span style="margin-left: 0.5rem; font-size: 1.2em;">→</span></span>`;
                }

                ghost.innerHTML = `${arrowHtml}<span class="nav-title">${title}</span>`;

                document.body.appendChild(ghost);

                setTimeout(() => {
                    ghost.remove();
                }, 600);
            }

            // Навігація стрілками
            document.addEventListener('keydown', function(event) {
                if (event.target.tagName === 'INPUT' || event.target.tagName === 'TEXTAREA') return;

                if (event.key === 'ArrowLeft') {
                    const prevLink = document.querySelector('.nav-prev');
                    if (prevLink) {
                        if (isElementInViewport(prevLink)) {
                            // Якщо кнопка видима - просто клікаємо з ефектом
                            prevLink.classList.add('active-press');
                            setTimeout(() => prevLink.classList.remove('active-press'), 200);
                            setTimeout(() => prevLink.click(), 100);
                        } else {
                            // Якщо не видима - показуємо привида
                            const title = prevLink.querySelector('.nav-title').innerText;
                            // Вираховуємо позицію відносно контейнера навігації, щоб було рівно
                            const navContainer = document.querySelector('.docs-nav');
                            const rect = navContainer.getBoundingClientRect();

                            triggerGhostNav('left', title, rect.left);
                            setTimeout(() => prevLink.click(), 300);
                        }
                    }
                } else if (event.key === 'ArrowRight') {
                    const nextLink = document.querySelector('.nav-next');
                    if (nextLink) {
                        if (isElementInViewport(nextLink)) {
                            // Якщо кнопка видима
                            nextLink.classList.add('active-press');
                            setTimeout(() => nextLink.classList.remove('active-press'), 200);
                            setTimeout(() => nextLink.click(), 100);
                        } else {
                            // Якщо не видима
                            const title = nextLink.querySelector('.nav-title').innerText;
                            const navContainer = document.querySelector('.docs-nav');
                            const rect = navContainer.getBoundingClientRect();

                            // Передаємо rect.right для правої кнопки
                            triggerGhostNav('right', title, rect.right);
                            setTimeout(() => nextLink.click(), 300);
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>