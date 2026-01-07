<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title та Meta тепер у header.blade.php -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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

        /* Search Bar */
        .search-container {
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }
        .search-input {
            width: 100%;
            padding: 1rem 1.5rem;
            padding-left: 3rem;
            border: 2px solid #e5e7eb;
            border-radius: 2rem;
            font-size: 1.1rem;
            transition: all 0.2s;
            outline: none;
        }
        .search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }
        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            width: 24px;
            height: 24px;
        }

        /* Categories Grid */
        .docs-group {
            margin-bottom: 3rem;
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
            gap: 1.5rem;
        }
        .doc-card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            transition: all 0.2s;
            text-decoration: none;
            color: inherit;
            border: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .doc-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-color);
        }
        .doc-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }
        .doc-card h3 {
            font-size: 1.1rem;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        .doc-desc {
            font-size: 0.9rem;
            color: #6b7280;
            flex-grow: 1;
        }
        .doc-link {
            margin-top: 1rem;
            color: var(--primary-color);
            font-weight: 500;
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

    <!-- Simple Search Script -->
    <script>
        function filterDocs() {
            let input = document.getElementById('searchInput');
            let filter = input.value.toUpperCase();
            let cards = document.getElementsByClassName('doc-card');
            let groups = document.getElementsByClassName('docs-group');

            for (let i = 0; i < cards.length; i++) {
                let title = cards[i].getElementsByTagName("h3")[0];
                let txtValue = title.textContent || title.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    cards[i].style.display = "";
                } else {
                    cards[i].style.display = "none";
                }
            }

            // Hide empty groups
            for (let i = 0; i < groups.length; i++) {
                let visibleCards = groups[i].querySelectorAll('.doc-card:not([style*="display: none"])');
                if (visibleCards.length === 0) {
                    groups[i].style.display = "none";
                } else {
                    groups[i].style.display = "";
                }
            }
        }
    </script>
</head>
<body>
    @include('partials.header')

    <main class="docs-home">
        <div class="docs-header">
            <h1>База знань Garage24</h1>
            <p>Інструкції, поради та відповіді на часті запитання.</p>

            <div class="search-container">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input type="text" id="searchInput" onkeyup="filterDocs()" class="search-input" placeholder="Що ви шукаєте? (наприклад: водії, uklon...)">
            </div>
        </div>

        @foreach($menu as $group)
            <div class="docs-group">
                <h2 class="group-title">{{ $group['title'] }}</h2>
                <div class="docs-grid">
                    @foreach($group['items'] as $item)
                        <a href="/docs/{{ $item['slug'] }}" class="doc-card">
                            <!-- Іконка залежно від розділу (можна додати логіку, але поки заглушка) -->
                            <div class="doc-icon">
                                @if($group['title'] == 'Початок роботи') 🚀
                                @elseif($group['title'] == 'Основний функціонал') ⚙️
                                @elseif($group['title'] == 'Фінанси та Обслуговування') 💰
                                @elseif($group['title'] == 'Адміністрування') 🛡️
                                @else 📄
                                @endif
                            </div>

                            <h3>{{ $item['title'] }}</h3>
                            <div class="doc-desc">
                                <!-- Тут можна було б виводити короткий опис, якщо він є в json -->
                                Детальна інструкція по розділу "{{ $item['title'] }}".
                            </div>

                            <div class="doc-link">
                                Читати статтю
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
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