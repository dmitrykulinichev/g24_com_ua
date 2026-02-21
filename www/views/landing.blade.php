<!DOCTYPE html>
<html lang="uk">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-P5VR2VN5');</script>
    <!-- End Google Tag Manager -->

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "SoftwareApplication",
      "name": "Garage24",
      "applicationCategory": "BusinessApplication",
      "operatingSystem": "Web, iOS, Android",
      "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "UAH",
        "description": "Безкоштовний старт, оплата по факту використання"
      },
      "description": "Операційна система для сучасного таксопарку. Автоматизація виплат, контроль палива та робота з водіями.",
      "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.8",
        "ratingCount": "124"
      }
    }
    </script>

    <!-- Основні стилі (зкомпільовані через npm run build) -->
    <link rel="stylesheet" href="/assets/css/style.css?v={{ time() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- AlpineJS для інтерактивності -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="text-slate-800 antialiased bg-white">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P5VR2VN5"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    @include('partials.header')

    <main>
        <!-- Hero Section -->
        <section class="relative overflow-hidden pt-32 pb-16 lg:pt-40 lg:pb-20 bg-cover bg-center" style="background-image: url('/assets/img/openart-image_hmp_kyur_1767826263185_raw.png');">
            <!-- Темний оверлей -->
            <div class="absolute inset-0 bg-slate-900/80"></div>
            <!-- Градієнт для глибини -->
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/50 via-transparent to-slate-900/90"></div>

            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center max-w-4xl mx-auto">
                    <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl mb-6">
                        Операційна система для<br>
                        <span class="text-blue-400">сучасного таксопарку</span>
                    </h1>
                    <p class="mt-4 max-w-2xl mx-auto text-xl text-slate-300 mb-8">
                        Замініть Excel, блокноти та хаос у чатах на єдину цифрову екосистему.
                        Автоматизуйте графіки, фінанси та ремонти в одному вікні
                    </p>

                    <!-- Кнопки перенесено нижче, за межі Hero -->

                    <!-- Key Benefits (Інтегровано в Hero) -->
                    <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6 text-left max-w-4xl mx-auto">
                        <div class="flex items-start gap-3 p-4 rounded-lg bg-white/10 border border-white/20 backdrop-blur-md text-white">
                            <div class="text-2xl">🤝</div>
                            <div>
                                <h3 class="font-bold text-white text-sm">Оплата по факту</h3>
                                <p class="text-slate-300 text-xs mt-1">Спочатку користуєтесь, потім платите. Жодних передплат</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-4 rounded-lg bg-white/10 border border-white/20 backdrop-blur-md text-white">
                            <div class="text-2xl">🔓</div>
                            <div>
                                <h3 class="font-bold text-white text-sm">Все включено</h3>
                                <p class="text-slate-300 text-xs mt-1">Один тариф. Всі функції доступні одразу. Жодних "Pro" версій</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-4 rounded-lg bg-white/10 border border-white/20 backdrop-blur-md text-white">
                            <div class="text-2xl">📈</div>
                            <div>
                                <h3 class="font-bold text-white text-sm">Платіть за активних</h3>
                                <p class="text-slate-300 text-xs mt-1">Машина в ремонті? Ви за неї не платите. Рахуємо тільки працюючі авто</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hero Image -->
                <div class="mt-16 relative rounded-xl shadow-2xl border border-slate-700 bg-slate-800 overflow-hidden">
                    <img src="/assets/img/docs/dashboard_main.png" alt="Інтерфейс Garage24" class="w-full h-auto opacity-90">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 to-transparent pointer-events-none"></div>
                </div>
            </div>
        </section>

        <!-- CTA Buttons Section (Винесено з Hero) -->
        <section class="py-10 bg-white border-b border-slate-100">
            <div class="container mx-auto px-4 text-center">
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="/pricing" class="btn-primary text-lg px-10 py-4 shadow-lg shadow-blue-500/30">
                        Почати роботу
                    </a>
                    <a href="/target" class="inline-flex items-center justify-center px-10 py-4 border border-slate-300 text-lg font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition duration-300 shadow-sm">
                        Кому підійде
                    </a>
                </div>
                <p class="mt-4 text-sm text-slate-500">
                    Налаштування займає менше 15 хвилин. Безкоштовний старт.
                </p>
            </div>
        </section>

        <!-- Pain Points Section -->
        <section class="py-20 bg-slate-50">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-slate-900 sm:text-4xl mb-4">Впізнаєте себе?</h2>
                    <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                        Типова ситуація при масштабуванні: бізнес росте, а контроль зникає.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="space-y-8">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-full flex items-center justify-center text-2xl">📉</div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 mb-2">Фінанси в "чорній скриньці"</h3>
                                <p class="text-slate-600">Ви бачите рух грошей, але не знаєте реального прибутку кожного авто. Гроші губляться між таблицями та картками.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-full flex items-center justify-center text-2xl">🤯</div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 mb-2">Хаос у графіках</h3>
                                <p class="text-slate-600">Диспетчер тримає зміни в голові. Машини простоюють, бо "забули" знайти водія. Штрафи приходять не на тих людей.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-full flex items-center justify-center text-2xl">🔧</div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 mb-2">Ремонти "пожежами"</h3>
                                <p class="text-slate-600">Про заміну мастила згадують, коли застукав двигун. Історія ремонтів живе в чатах Viber, які неможливо перевірити.</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-xl border border-slate-100 relative">
                        <div class="absolute -top-4 -right-4 bg-yellow-400 text-slate-900 font-bold px-4 py-2 rounded-lg transform rotate-3 shadow-md text-sm">
                            Реальна історія
                        </div>
                        <div class="prose text-slate-600 mb-6 relative z-10">
                            <p class="mb-4">"Поки у вас було 5 машин, ви знали про кожну все. Але коли машин стало більше, почався хаос.</p>
                            <p class="mb-4">Ви не розумієте, хто, коли і на якому авто працював. Графіки в голові, в чатах або на папірцях. Коли приходить штраф — ви не знаєте, з кого його списати.</p>
                            <p class="mb-4">Інформація розпорошена: фінанси живуть в одній таблиці, ремонти — в чатах з механіками, а графіки — в голові диспетчера.</p>
                            <p class="italic font-medium text-slate-800">Ви бачите рух грошей, але не бачите реального стану бізнесу."</p>
                        </div>
                        <div class="flex items-center gap-4 border-t border-slate-100 pt-4">
                            <div class="w-12 h-12 bg-slate-200 rounded-full flex items-center justify-center font-bold text-slate-500">?</div>
                            <div>
                                <div class="font-bold text-slate-900">Власник парку</div>
                                <div class="text-sm text-slate-500">до впровадження Garage24</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Grid -->
        <section id="features" class="py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-slate-900 sm:text-4xl mb-4">Порядок замість хаосу</h2>
                    <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                        6 модулів, які закривають 99% потреб вашого бізнесу.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- 1. Графік -->
                    <div class="bg-slate-50 rounded-xl p-8 border border-slate-100 hover:shadow-lg transition duration-300 group">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform">📅</div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Розумний графік роботи водіїв</h3>
                        <p class="text-slate-600 mb-4">Планування змін без конфліктів. Система не дасть поставити одного водія на дві машини.</p>
                        <ul class="space-y-2 text-sm text-slate-500">
                            <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Drag & Drop календар</li>
                            <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Контроль перезмінок</li>
                            <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Історія призначень</li>
                        </ul>
                    </div>

                    <!-- 2. Фінанси -->
                    <div class="bg-slate-50 rounded-xl p-8 border border-slate-100 hover:shadow-lg transition duration-300 group">
                        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform">💰</div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Фінанси та облік зарплати</h3>
                        <p class="text-slate-600 mb-4">Автоматичний розрахунок зарплати водіїв на основі поїздок Uklon та ручних кас, премій та штрафів.</p>
                        <ul class="space-y-2 text-sm text-slate-500">
                            <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Схеми розподілу грошей</li>
                            <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Агрегація доходів та витрат</li>
                            <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Економіка парку</li>
                        </ul>
                    </div>

                    <!-- 3. Автопарк -->
                    <div class="bg-slate-50 rounded-xl p-8 border border-slate-100 hover:shadow-lg transition duration-300 group">
                        <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform">🚗</div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Цифровий гараж</h3>
                        <p class="text-slate-600 mb-4">Електронна картка кожного авто. Документи, страховки та історія в одному місці.</p>
                        <ul class="space-y-2 text-sm text-slate-500">
                            <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Нагадування про страховки</li>
                            <li class="flex items-center gap-2"><span class="text-green-500">✓</span> QR-коди на авто</li>
                            <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Облік пробігу</li>
                        </ul>
                    </div>

                    <!-- 4. Водії -->
                    <div class="bg-slate-50 rounded-xl p-8 border border-slate-100 hover:shadow-lg transition duration-300 group">
                        <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform">👥</div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">HR та Рекрутинг</h3>
                        <p class="text-slate-600 mb-4">Від найму до звільнення. Зберігайте документи, контакти родичів та історію порушень.</p>
                        <ul class="space-y-2 text-sm text-slate-500">
                            <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Лендінг для реєстрації</li>
                            <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Масовий імпорт водіїв</li>
                            <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Чорний список</li>
                        </ul>
                    </div>

                    <!-- 5. ТО -->
                    <div class="bg-slate-50 rounded-xl p-8 border border-slate-100 hover:shadow-lg transition duration-300 group">
                        <div class="w-12 h-12 bg-red-100 text-red-600 rounded-lg flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform">🛠️</div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Сервіс та Ремонти</h3>
                        <p class="text-slate-600 mb-4">Контроль вартості володіння. Водії повідомляють про поломки через телефон.</p>
                        <ul class="space-y-2 text-sm text-slate-500">
                            <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Тікети поломок</li>
                            <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Регламентні роботи</li>
                            <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Контроль витрат на СТО</li>
                        </ul>
                    </div>

                    <!-- 6. Безпека -->
                    <div class="bg-slate-50 rounded-xl p-8 border border-slate-100 hover:shadow-lg transition duration-300 group">
                        <div class="w-12 h-12 bg-slate-200 text-slate-700 rounded-lg flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform">🔒</div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Аудит та Безпека</h3>
                        <p class="text-slate-600 mb-4">Повний журнал дій. Ви завжди знаєте, хто змінив налаштування або видалив поїздку.</p>
                        <ul class="space-y-2 text-sm text-slate-500">
                            <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Activity Logs</li>
                            <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Ролі доступу (RBAC)</li>
                            <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Щоденні бекапи</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Integration Section (Uklon) -->
        <section class="py-20 bg-slate-900 text-white overflow-hidden">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row items-center gap-12">
                    <div class="lg:w-1/2">
                        <div class="inline-block bg-yellow-400 text-slate-900 font-bold px-3 py-1 rounded-full text-sm mb-6">
                            Офіційна інтеграція
                        </div>
                        <h2 class="text-3xl font-bold sm:text-4xl mb-6">Синхронізація з Uklon Fleet API</h2>
                        <p class="text-slate-300 text-lg mb-8">
                            Забудьте про ручне перенесення даних. Garage24 підключається до вашого кабінету партнера і забирає всі дані автоматично.
                        </p>

                        <div class="space-y-6">
                            <div class="flex gap-4">
                                <div class="flex-shrink-0 w-10 h-10 bg-slate-800 rounded-full flex items-center justify-center text-green-400">✓</div>
                                <div>
                                    <h4 class="font-bold text-lg">Імпорт поїздок та каси</h4>
                                    <p class="text-slate-400 text-sm">Всі замовлення, бонуси та чайові автоматично потрапляють у фінансовий звіт.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="flex-shrink-0 w-10 h-10 bg-slate-800 rounded-full flex items-center justify-center text-green-400">✓</div>
                                <div>
                                    <h4 class="font-bold text-lg">Синхронізація бази</h4>
                                    <p class="text-slate-400 text-sm">Завантажте своїх водіїв та авто з Uklon одним кліком (Link Fleet).</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="flex-shrink-0 w-10 h-10 bg-slate-800 rounded-full flex items-center justify-center text-green-400">✓</div>
                                <div>
                                    <h4 class="font-bold text-lg">Контроль статусів</h4>
                                    <p class="text-slate-400 text-sm">Бачте в реальному часі, хто на лінії, а хто відпочиває.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lg:w-1/2 relative">
                        <!-- Декоративний елемент -->
                        <div class="absolute -inset-4 bg-yellow-400/20 rounded-full blur-3xl"></div>
                        <img src="/assets/img/docs/uklon_reports_dashboard.png" alt="Uklon Integration" class="relative rounded-xl shadow-2xl border border-slate-700">
                    </div>
                </div>
            </div>
        </section>

        <!-- Ecosystem Section -->
        <section class="py-24 bg-slate-50">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-slate-900 sm:text-4xl mb-4">Екосистема Garage24</h2>
                    <p class="text-lg text-slate-600">Два зручних інтерфейси для різних ролей у вашій команді.</p>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Mobile App (PWA) -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg border border-slate-100 flex flex-col md:flex-row gap-8 items-center">
                        <div class="w-full md:w-1/2">
                            <div class="text-4xl mb-4">📱</div>
                            <h3 class="text-2xl font-bold text-slate-900 mb-2">Мобільний офіс</h3>
                            <p class="text-slate-600 mb-4">Для власників та менеджерів. Повноцінна PWA-система у вашому смартфоні.</p>
                            <ul class="space-y-2 text-sm text-slate-500">
                                <li>• Документи завжди під рукою</li>
                                <li>• Пульс парку 24/7</li>
                                <li>• Темна тема для нічних змін</li>
                            </ul>
                        </div>
                        <div class="w-full md:w-1/2">
                            <img src="/assets/img/docs/dashboard_main.png" alt="Mobile App" class="rounded-lg shadow-md border border-slate-200">
                        </div>
                    </div>

                    <!-- Telegram Bot -->
                    <div class="bg-blue-600 rounded-2xl p-8 shadow-lg text-white flex flex-col md:flex-row gap-8 items-center">
                        <div class="w-full md:w-1/2">
                            <div class="text-4xl mb-4">🤖</div>
                            <h3 class="text-2xl font-bold mb-2">Telegram Бот</h3>
                            <p class="text-blue-100 mb-4">Для водіїв. Простий інтерфейс у звичному месенджері.</p>
                            <ul class="space-y-2 text-sm text-blue-100">
                                <li>• Прийом-здача авто</li>
                                <li>• Звіти про проблеми автомобіля</li>
                                <li>• Графік чергувань</li>
                                <li>• Передача пробігу</li>
                                <li>• Облік ручної каси</li>
                            </ul>
                        </div>
                        <div class="w-full md:w-1/2 flex justify-center">
                            <!-- Імітація інтерфейсу бота -->
                            <div class="bg-white text-slate-900 p-4 rounded-lg shadow-lg w-full max-w-[200px] text-xs">
                                <div class="bg-blue-100 p-2 rounded mb-2 self-start">Олександр: 5683 здав 76081</div>
                                <div class="bg-blue-500 text-white p-2 rounded self-end text-right">Бот: прийнято</div>

                                <div class="bg-blue-100 p-2 rounded mb-2 self-start">Ігор: 2312 прийняв</div>
                                <div class="bg-blue-500 text-white p-2 rounded self-end text-right">Бот: ок</div>
                                <div class="bg-blue-100 p-2 rounded mb-2 self-start">Олексій: плавають оберти, треба до газовика!</div>
                                <div class="bg-blue-500 text-white p-2 rounded self-end text-right">Бот: пепедано в обробку. З Вами звяжуться</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="py-20 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-slate-900">Часті запитання</h2>
                </div>

                <div class="space-y-6" x-data="{ active: null }">
                    <!-- FAQ Item 1 -->
                    <div class="border border-slate-200 rounded-lg overflow-hidden">
                        <button @click="active = (active === 1 ? null : 1)" class="w-full px-6 py-4 text-left bg-slate-50 hover:bg-slate-100 flex justify-between items-center font-semibold text-slate-900 transition duration-200">
                            <span>Чи можу я перенести дані зі своєї старої Excel таблиці?</span>
                            <span x-text="active === 1 ? '−' : '+'" class="text-xl"></span>
                        </button>
                        <div x-show="active === 1" x-collapse class="px-6 py-4 text-slate-600 border-t border-slate-200 bg-white">
                            Так! Наш модуль імпорту дозволяє завантажити водіїв та автомобілі за кілька хвилин. Система сама підкаже, як співставити колонки.
                            Крім того, доступна можливість синхронізації водіїв та автомобілів з базою Уклона.
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="border border-slate-200 rounded-lg overflow-hidden">
                        <button @click="active = (active === 2 ? null : 2)" class="w-full px-6 py-4 text-left bg-slate-50 hover:bg-slate-100 flex justify-between items-center font-semibold text-slate-900 transition duration-200">
                            <span>Чи безпечно зберігати дані в хмарі?</span>
                            <span x-text="active === 2 ? '−' : '+'" class="text-xl"></span>
                        </button>
                        <div x-show="active === 2" x-collapse class="px-6 py-4 text-slate-600 border-t border-slate-200 bg-white">
                            Ми використовуємо надійні мехнізми ізоляції даних та шифруємо чутливу інформацію. Щоденні бекапи гарантують, що ви ніколи не втратите свою базу. В індивідуальному порядку можемо розглянути варіант підключення нашої системи до вашої бази даних. Однак, якщо у Вас невеликий парк - краще використати наше сховище.
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="border border-slate-200 rounded-lg overflow-hidden">
                        <button @click="active = (active === 3 ? null : 3)" class="w-full px-6 py-4 text-left bg-slate-50 hover:bg-slate-100 flex justify-between items-center font-semibold text-slate-900 transition duration-200">
                            <span>Чи потрібен мені програміст для налаштування?</span>
                            <span x-text="active === 3 ? '−' : '+'" class="text-xl"></span>
                        </button>
                        <div x-show="active === 3" x-collapse class="px-6 py-4 text-slate-600 border-t border-slate-200 bg-white">
                            Ні. Система створена для звичайних користувачів. Всі налаштування виконуються через зрозумілий інтерфейс.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-24 bg-primary relative overflow-hidden">
            <div class="absolute inset-0 bg-blue-700/50"></div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative text-center">
                <h2 class="text-3xl font-bold text-white sm:text-4xl mb-6">Порядок коштує дешевше, ніж хаос</h2>
                <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto">
                    Почніть роботу вже сьогодні. Перший рахунок прийде тільки через місяць, і тільки якщо система принесе вам користь.
                </p>
                <a href="/pricing" class="inline-block bg-white text-blue-600 font-bold text-lg px-10 py-4 rounded-lg shadow-xl hover:bg-blue-50 transition duration-300">
                    Почати роботу
                </a>
                <p class="mt-6 text-sm text-blue-200">
                    Без прив'язки картки. Оплата по факту.
                </p>
            </div>
        </section>
    </main>

    @include('partials.footer')

    <!-- Модальні вікна (підключаються глобально ОДИН РАЗ) -->
    @include('partials.modal-form')
    @include('partials.modal-text')
    @include('partials.cookie-consent')
</body>
</html>