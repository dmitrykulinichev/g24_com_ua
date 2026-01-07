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
        .docs-home {
            max-width: 1000px;
            margin: 0 auto;
            padding: 4rem 2rem;
            min-height: 60vh;
        }
        .docs-header {
            text-align: center;
            margin-bottom: 4rem;
        }
        .docs-header h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--secondary-color);
        }

        .docs-group {
            margin-bottom: 3rem;
        }
        .group-title {
            font-size: 1.5rem;
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .docs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        .doc-card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
            color: inherit;
            border: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .doc-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-color: var(--primary-color);
        }
        .doc-card h3 {
            font-size: 1.1rem;
            color: var(--text-color);
            margin: 0;
        }
        .arrow {
            color: var(--primary-color);
            font-weight: bold;
        }
    </style>
</head>
<body>
    @include('partials.header')

    <main class="docs-home">
        <div class="docs-header">
            <h1>Документація користувача</h1>
            <p>Знайдіть відповіді на всі питання щодо використання системи G24.com.ua.</p>
        </div>

        @foreach($menu as $group)
            <div class="docs-group">
                <h2 class="group-title">{{ $group['title'] }}</h2>
                <div class="docs-grid">
                    @foreach($group['items'] as $item)
                        <a href="/docs/{{ $item['slug'] }}" class="doc-card">
                            <h3>{{ $item['title'] }}</h3>
                            <span class="arrow">→</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </main>

    @include('partials.footer')
</body>
</html>