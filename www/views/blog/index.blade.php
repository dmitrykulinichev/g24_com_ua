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
        .blog-container {
            max-width: 900px; /* Трохи ширше для горизонтальних карток */
            margin: 0 auto;
            padding: 4rem 2rem;
            min-height: 60vh;
        }
        .blog-header {
            text-align: center;
            margin-bottom: 4rem;
        }
        .blog-header h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--secondary-color);
        }
        .post-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            margin-bottom: 2rem;
            transition: box-shadow 0.2s;
            overflow: hidden;
            padding: 0;
            display: flex; /* Горизонтальне розташування */
            flex-direction: row;
            align-items: stretch; /* Розтягуємо на всю висоту */
        }
        .post-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

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

        .post-content {
            padding: 1.5rem 2rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center; /* Центруємо контент вертикально, якщо тексту мало */
        }

        .post-date {
            color: #6b7280;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }
        .post-title {
            font-size: 1.4rem;
            margin-bottom: 0.75rem;
            line-height: 1.3;
        }
        .post-title a {
            color: var(--secondary-color);
            text-decoration: none;
        }
        .post-title a:hover {
            color: var(--primary-color);
        }
        .post-preview {
            color: #4b5563;
            margin-bottom: 1.25rem;
            line-height: 1.6;
            font-size: 0.95rem;
            /* Обмеження кількості рядків (опціонально) */
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .read-more {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            font-size: 0.9rem;
            margin-top: auto; /* Притискаємо до низу */
        }
        .read-more:hover {
            text-decoration: underline;
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
            .post-content {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    @include('partials.header')

    <main class="blog-container">
        <div class="blog-header">
            <h1>Блог розробників</h1>
            <p>Новини, оновлення та корисні поради щодо використання Garage24.</p>

            <!-- Пошук по блогу -->
            <div style="max-width: 500px; margin: 2rem auto 0;">
                @include('partials.blog-search')
            </div>
        </div>

        @if(count($posts) > 0)
            @foreach($posts as $post)
                <article class="post-card">
                    @if(!empty($post['image']))
                        <a href="/blog/{{ $post['slug'] }}" class="post-image">
                            <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}" loading="lazy">
                        </a>
                    @endif

                    <div class="post-content">
                        <div class="post-date">{{ date('d.m.Y', $post['date']) }}</div>
                        <h2 class="post-title">
                            <a href="/blog/{{ $post['slug'] }}">{{ $post['title'] }}</a>
                        </h2>
                        <p class="post-preview">{{ $post['preview'] }}</p>
                        <a href="/blog/{{ $post['slug'] }}" class="read-more">
                            Читати далі
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px; margin-left: 4px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </article>
            @endforeach
        @else
            <p style="text-align: center; color: #6b7280;">Поки що немає новин.</p>
        @endif
    </main>

    @include('partials.footer')
</body>
</html>