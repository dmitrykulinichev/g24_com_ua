<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title та Meta тепер у header.blade.php -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <!-- Підключення Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb',
                        secondary: '#1e293b',
                        accent: '#10b981',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Стилі для іконок категорій */
        .category-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 3rem;
            height: 3rem;
            background-color: #eff6ff;
            color: #2563eb;
            border-radius: 0.75rem;
            font-size: 1.5rem;
            margin-right: 1rem;
        }
    </style>
</head>
<body class="text-slate-800 antialiased bg-white">
    @include('partials.header')

    <!-- Прибрано pt-32 з main -->
    <main>
        <!-- Hero Section: Додано pt-32 lg:pt-40 та декоративний фон -->
        <div class="relative bg-slate-900 text-white pt-32 pb-16 lg:pt-40 lg:pb-24 text-center overflow-hidden">
            <!-- Декоративний фон -->
            <div class="absolute inset-0 bg-[url('/assets/img/grid.svg')] opacity-10"></div>
            <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-blue-900/50 to-transparent"></div>

            <div class="container mx-auto px-4 relative z-10">
                <h1 class="text-4xl font-extrabold sm:text-5xl mb-6">Можливості системи</h1>
                <p class="text-xl text-slate-300 max-w-2xl mx-auto">Garage24 — це комплексне рішення, яке закриває всі потреби сучасного автопарку.</p>
            </div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 -mt-10 relative z-10">
            <div class="grid gap-12">
                @foreach($features as $categoryName => $category)
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                        <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex items-center">
                                <span class="category-icon">{{ $category['icon'] }}</span>
                                <h2 class="text-2xl font-bold text-slate-900">{{ $categoryName }}</h2>
                            </div>
                            @if(isset($category['slogan']))
                                <div class="text-primary font-medium italic">{{ $category['slogan'] }}</div>
                            @endif
                        </div>

                        <div class="p-8 grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach($category['items'] as $item)
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 mb-2">{{ $item['title'] }}</h3>
                                    <p class="text-slate-600 text-sm leading-relaxed">{{ $item['desc'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-16 text-center bg-blue-50 rounded-2xl p-12 border border-blue-100">
                <h2 class="text-2xl font-bold text-slate-900 mb-4">Готові спробувати?</h2>
                <p class="text-slate-600 mb-8 max-w-2xl mx-auto">Отримайте повний доступ до всіх функцій. Оплата тільки за активні авто в кінці місяця.</p>
                <a href="/pricing" class="inline-block bg-primary text-white font-bold text-lg px-10 py-4 rounded-xl shadow-lg hover:bg-blue-700 transition duration-300">
                    Почати роботу
                </a>
            </div>
        </div>
    </main>

    @include('partials.footer')
</body>
</html>