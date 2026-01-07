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
            max-width: 800px;
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
            padding: 2rem;
            margin-bottom: 2rem;
            transition: box-shadow 0.2s;
        }
        .post-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .post-date {
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }
        .post-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
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
            margin-bottom: 1.5rem;
        }
        .read-more {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        .read-more:hover {
            text-decoration: underline;
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
                    <div class="post-date">{{ date('d.m.Y', $post['date']) }}</div>
                    <h2 class="post-title">
                        <a href="/blog/{{ $post['slug'] }}">{{ $post['title'] }}</a>
                    </h2>
                    <p class="post-preview">{{ $post['preview'] }}</p>
                    <a href="/blog/{{ $post['slug'] }}" class="read-more">Читати далі →</a>
                </article>
            @endforeach
        @else
            <p style="text-align: center; color: #6b7280;">Поки що немає новин.</p>
        @endif
    </main>

    @include('partials.footer')
</body>
</html>