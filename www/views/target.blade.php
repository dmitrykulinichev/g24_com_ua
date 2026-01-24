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
                <h1 class="text-4xl font-extrabold sm:text-5xl mb-6">Кому підійде Garage24?</h1>
                <p class="text-xl text-slate-300 max-w-2xl mx-auto">Ми розробили систему, враховуючи специфіку різних моделей управління автопарком.</p>
            </div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 -mt-10 relative z-10">
            <div class="grid gap-8">
                <!-- 1. Партнери -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-8 flex flex-col md:flex-row gap-8 items-start">
                    <div class="flex-shrink-0 w-20 h-20 bg-blue-50 rounded-2xl flex items-center justify-center text-4xl">🤝</div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Партнери-власники</h3>
                        <p class="text-slate-600 mb-6">Ви керуєте бізнесом удвох або утрьох. Один відповідає за фінанси, інший — за технічний стан.</p>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                                <strong class="text-red-800 block mb-1">Біль:</strong>
                                <span class="text-red-700 text-sm">Постійні суперечки "де гроші?". Один бачить прибуток на карті, інший — витрати на СТО. Немає єдиної картини.</span>
                            </div>
                            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                                <strong class="text-green-800 block mb-1">Рішення:</strong>
                                <span class="text-green-700 text-sm">Єдина база даних. Обидва партнери бачать кожну транзакцію, кожен ремонт і реальний баланс. Повна прозорість.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Власник-Операціоніст -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-8 flex flex-col md:flex-row gap-8 items-start">
                    <div class="flex-shrink-0 w-20 h-20 bg-orange-50 rounded-2xl flex items-center justify-center text-4xl">🤯</div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Власник, який "живе в гаражі"</h3>
                        <p class="text-slate-600 mb-6">Ви самі шукаєте водіїв, контролюєте ремонти і видаєте зарплату. Ваш телефон дзвонить кожні 5 хвилин.</p>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                                <strong class="text-red-800 block mb-1">Біль:</strong>
                                <span class="text-red-700 text-sm">Ви — вузьке місце бізнесу. Якщо ви захворієте або поїдете у відпустку, все зупиниться.</span>
                            </div>
                            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                                <strong class="text-green-800 block mb-1">Рішення:</strong>
                                <span class="text-green-700 text-sm">Мобільний офіс. Водії та механіки взаємодіють з системою, а не дзвонять вам. Ви керуєте процесами, а не гасите пожежі.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Інвестор -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-8 flex flex-col md:flex-row gap-8 items-start">
                    <div class="flex-shrink-0 w-20 h-20 bg-purple-50 rounded-2xl flex items-center justify-center text-4xl">💼</div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Власник-Інвестор</h3>
                        <p class="text-slate-600 mb-6">Ви купили машини і найняли керуючого (диспетчера). Ви хочете отримувати пасивний дохід.</p>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                                <strong class="text-red-800 block mb-1">Біль:</strong>
                                <span class="text-red-700 text-sm">Недовіра. Чи всі поїздки враховані? Чи не завищені ціни на запчастини? Чи не використовують авто "наліво"?</span>
                            </div>
                            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                                <strong class="text-green-800 block mb-1">Рішення:</strong>
                                <span class="text-green-700 text-sm">Тотальний контроль. Інтеграція з Uklon показує реальні поїздки. Журнал дій (Audit Log) показує, хто і що змінював у системі.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Масштабування -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-8 flex flex-col md:flex-row gap-8 items-start">
                    <div class="flex-shrink-0 w-20 h-20 bg-blue-50 rounded-2xl flex items-center justify-center text-4xl">📈</div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Парк, що росте (10+ авто)</h3>
                        <p class="text-slate-600 mb-6">Раніше ви справлялися за допомогою Excel та блокнота. Але машин стало більше, і система зламалась.</p>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                                <strong class="text-red-800 block mb-1">Біль:</strong>
                                <span class="text-red-700 text-sm">Хаос. Загублені документи, прострочені ТО, плутанина з графіками. Excel більше не працює.</span>
                            </div>
                            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                                <strong class="text-green-800 block mb-1">Рішення:</strong>
                                <span class="text-green-700 text-sm">Системність. Garage24 автоматизує рутину (нагадування, графіки, звіти), дозволяючи вам рости далі без збільшення штату менеджерів.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews -->
            <div class="mt-20">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-slate-900">Що кажуть наші клієнти</h2>
                    <p class="text-slate-600 mt-2">Реальні історії тих, хто вже навів порядок.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-slate-50 p-8 rounded-2xl border border-slate-100">
                        <p class="text-slate-600 italic mb-6">"Ми з партнером постійно сперечалися про гроші. Він казав, що ми в плюсі, а я бачив тільки витрати на ремонти. Garage24 показав реальну картину. Тепер ми бачимо прибутковість кожної машини до копійки."</p>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">Д</div>
                            <div>
                                <div class="font-bold text-slate-900">Дмитро</div>
                                <div class="text-xs text-slate-500">Співвласник (18 авто)</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-8 rounded-2xl border border-slate-100">
                        <p class="text-slate-600 italic mb-6">"Раніше мій телефон дзвонив кожні 5 хвилин: 'Де машина?', 'Хто на зміні?'. Тепер я просто відкриваю сайт на телефоні і бачу все сам. Дзвінки припинилися, я нарешті можу займатися розвитком."</p>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center text-white font-bold">О</div>
                            <div>
                                <div class="font-bold text-slate-900">Олександр</div>
                                <div class="text-xs text-slate-500">Власник парку (25 авто)</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-8 rounded-2xl border border-slate-100">
                        <p class="text-slate-600 italic mb-6">"Найбільше часу забирало перенесення поїздок з кабінету Uklon в нашу таблицю. Це були години ручної роботи. Тепер все залітає автоматично. Я навіть не заходжу в кабінет партнера."</p>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold">А</div>
                            <div>
                                <div class="font-bold text-slate-900">Андрій</div>
                                <div class="text-xs text-slate-500">Власник (12 авто)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-16 text-center">
                <a href="/pricing" class="inline-block bg-primary text-white font-bold text-lg px-10 py-4 rounded-xl shadow-lg hover:bg-blue-700 transition duration-300">
                    Почати роботу
                </a>
            </div>
        </div>
    </main>

    @include('partials.footer')
</body>
</html>