<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title та Meta тепер у header.blade.php -->
    <link rel="stylesheet" href="/assets/css/style.css?v={{ time() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .docs-home {
            max-width: 1200px;
            margin: 0 auto;
            padding: 4rem 2rem;
            min-height: 60vh;
        }
        .docs-header {
            text-align: center;
            margin-bottom: 4rem;
            background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
            padding: 4rem 2rem;
            border-radius: 1rem;
            border: 1px solid #e5e7eb;
        }
        .docs-header h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--secondary-color);
        }
        .docs-header p {
            font-size: 1.1rem;
            color: #6b7280;
            margin-bottom: 2rem;
        }

        /* Стилі для пошуку на головній (перевизначаємо розмір) */
        .docs-header .search-container {
            max-width: 600px;
            margin: 0 auto;
        }
        .docs-header .search-input {
            padding: 1rem 1.5rem;
            padding-left: 3rem;
            border-radius: 2rem;
            font-size: 1.1rem;
        }
        .docs-header .search-icon {
            width: 24px;
            height: 24px;
            left: 1rem;
        }

        /* Categories Grid */
        .docs-group {
            margin-bottom: 4rem;
        }
        .group-title {
            font-size: 1.5rem;
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
        }

        .docs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
        }

        /* Новий стиль карток з картинками */
        .doc-card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            border: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            overflow: hidden; /* Щоб картинка не вилазила */
            height: 100%;
        }

        .doc-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: var(--primary-color);
        }

        .doc-image-wrapper {
            height: 160px;
            overflow: hidden;
            background-color: #f3f4f6;
            border-bottom: 1px solid #e5e7eb;
            position: relative;
        }

        .doc-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: top left; /* Показуємо верхній лівий кут інтерфейсу */
            transition: transform 0.5s ease;
        }

        .doc-card:hover .doc-image-wrapper img {
            transform: scale(1.05);
        }

        .doc-content {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .doc-card h3 {
            font-size: 1.2rem;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .doc-desc {
            font-size: 0.95rem;
            color: #6b7280;
            margin-bottom: 1rem;
            line-height: 1.5;
            flex-grow: 1;
        }

        .doc-link {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }

        .doc-link svg {
            width: 16px;
            height: 16px;
            margin-left: 0.25rem;
            transition: transform 0.2s;
        }

        .doc-card:hover .doc-link svg {
            transform: translateX(3px);
        }

        /* Support Block */
        .support-block {
            text-align: center;
            margin-top: 4rem;
            padding: 3rem;
            background-color: #f8fafc;
            border-radius: 1rem;
            border: 1px solid #e5e7eb;
        }
        .support-block h3 {
            margin-bottom: 0.5rem;
            color: var(--secondary-color);
        }
        .support-block p {
            color: #6b7280;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    @include('partials.header')

    <main class="docs-home">
        <div class="docs-header">
            <h1>База знань Garage24</h1>
            <p>Інструкції, поради та відповіді на часті запитання.</p>

            <!-- Підключаємо пошук -->
            @include('partials.docs-search')
        </div>

        @foreach($menu as $group)
            <div class="docs-group">
                <h2 class="group-title">{{ $group['title'] }}</h2>
                <div class="docs-grid">
                    @foreach($group['items'] as $item)
                        <a href="/docs/{{ $item['slug'] }}" class="doc-card">
                            <!-- Зображення-прев'ю -->
                            <div class="doc-image-wrapper">
                                <img src="/assets/img/docs/{{ $item['image'] ?? 'dashboard_main.png' }}" alt="{{ $item['title'] }}" loading="lazy">
                            </div>

                            <div class="doc-content">
                                <h3>{{ $item['title'] }}</h3>
                                <div class="doc-desc">
                                    Перейти до розділу "{{ $item['title'] }}".
                                </div>

                                <div class="doc-link">
                                    Читати
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="support-block">
            <h3>Не знайшли відповідь?</h3>
            <p>Наша служба підтримки готова допомогти вам у будь-який час.</p>
            <button @click="$dispatch('open-order-modal', {})" class="btn-primary">Написати в підтримку</button>
        </div>
    </main>

    @include('partials.footer')
</body>
</html>