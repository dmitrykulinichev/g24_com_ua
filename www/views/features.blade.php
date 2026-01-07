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
        .features-header {
            text-align: center;
            padding: 4rem 2rem;
            background-color: var(--secondary-color);
            color: var(--white);
        }
        .features-header h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .features-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        .features-container {
            max-width: 1000px;
            margin: -3rem auto 4rem;
            padding: 0 2rem;
            position: relative;
            z-index: 10;
        }

        .feature-section {
            background: var(--white);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
            margin-bottom: 2rem;
        }

        .feature-category-title {
            font-size: 1.5rem;
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 1rem;
        }

        .category-icon {
            font-size: 2rem;
            margin-right: 1rem;
        }

        .feature-items {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-item h3 {
            font-size: 1.1rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .feature-item p {
            color: var(--text-color);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .cta-block {
            text-align: center;
            margin-top: 4rem;
            padding: 3rem;
            background-color: #eff6ff;
            border-radius: 1rem;
        }

        .cta-block h2 {
            margin-bottom: 1rem;
            color: var(--secondary-color);
        }

        .cta-block p {
            margin-bottom: 2rem;
            color: #4b5563;
        }
    </style>
</head>
<body>
    @include('partials.header')

    <div class="features-header">
        <h1>Можливості системи</h1>
        <p>G24 — це комплексне рішення, яке закриває всі потреби сучасного автопарку.</p>
    </div>

    <div class="features-container">
        @foreach($features as $categoryName => $category)
            <div class="feature-section">
                <h2 class="feature-category-title">
                    <span class="category-icon">{{ $category['icon'] }}</span>
                    {{ $categoryName }}
                </h2>
                <div class="feature-items">
                    @foreach($category['items'] as $item)
                        <div class="feature-item">
                            <h3>{{ $item['title'] }}</h3>
                            <p>{{ $item['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="cta-block">
            <h2>Готові спробувати?</h2>
            <p>Отримайте повний доступ до всіх функцій на 14 днів безкоштовно.</p>
            <button @click="$dispatch('open-order-modal', {})" class="btn-primary" style="padding: 1rem 3rem; font-size: 1.1rem;">Почати безкоштовно</button>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>