<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Блог - Garage24</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;800&display=swap" rel="stylesheet">
    <style>
        .article-container {
            max-width: 700px;
            margin: 0 auto;
            padding: 4rem 2rem;
            min-height: 60vh;
        }
        .article-meta {
            color: #6b7280;
            margin-bottom: 2rem;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 1rem;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 2rem;
            color: var(--primary-color);
            text-decoration: none;
        }

        /* Стилі контенту */
        .content h1 { font-size: 2.5rem; margin-bottom: 1rem; line-height: 1.2; color: var(--secondary-color); }
        .content h2 { margin-top: 2rem; margin-bottom: 1rem; font-size: 1.75rem; color: var(--secondary-color); }
        .content h3 { margin-top: 1.5rem; margin-bottom: 0.75rem; font-size: 1.25rem; }
        .content p { margin-bottom: 1.25rem; line-height: 1.8; color: #374151; font-size: 1.1rem; }
        .content ul, .content ol { margin-bottom: 1.5rem; padding-left: 1.5rem; }
        .content li { margin-bottom: 0.5rem; }
        .content img { max-width: 100%; border-radius: 0.5rem; margin: 2rem 0; border: 1px solid #e5e7eb; }

        /* Стиль для підсвічування */
        mark {
            background-color: #fef08a;
            padding: 0.1rem 0.2rem;
            border-radius: 0.2rem;
        }

        /* Навігація блогу */
        .blog-nav {
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
            /* Обрізаємо довгі заголовки */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .nav-newer {
            text-align: right;
            margin-left: auto;
        }
    </style>
</head>
<body>
    @include('partials.header')

    <main class="article-container">
        <a href="/blog" class="back-link">← Всі новини</a>

        <div class="article-meta">
            Опубліковано: {{ date('d.m.Y', $meta['date']) }}
        </div>

        <article class="content" id="blogContent">
            {!! $content !!}

            <div class="blog-nav">
                @if($older)
                    <a href="/blog/{{ $older['slug'] }}" class="nav-item nav-older">
                        <span class="nav-label">← Старіша</span>
                        <span class="nav-title">{{ $older['title'] }}</span>
                    </a>
                @endif

                @if($newer)
                    <a href="/blog/{{ $newer['slug'] }}" class="nav-item nav-newer">
                        <span class="nav-label">Новіша →</span>
                        <span class="nav-title">{{ $newer['title'] }}</span>
                    </a>
                @endif
            </div>
        </article>
    </main>

    @include('partials.footer')

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
</body>
</html>