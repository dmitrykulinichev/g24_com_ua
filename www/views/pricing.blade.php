<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title та Meta тепер у header.blade.php -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <!-- Підключення Tailwind CSS (через CDN для розробки) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb', // blue-600
                        secondary: '#1e293b', // slate-800
                        accent: '#10b981', // emerald-500
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
<!-- Додаємо x-data, щоб Alpine працював на всій сторінці -->
<body class="text-slate-800 antialiased bg-white" x-data>
    @include('partials.header')

    <main>
        <!-- Hero Section -->
        <section class="relative pt-24 pb-20 bg-slate-900 text-white overflow-hidden">
            <!-- Декоративний фон -->
            <div class="absolute inset-0 bg-[url('/assets/img/grid.svg')] opacity-10"></div>
            <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-blue-900/50 to-transparent"></div>

            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                <div class="inline-block bg-blue-600/30 border border-blue-500/50 rounded-full px-4 py-1 mb-6 text-blue-200 text-sm font-medium">
                    Новий підхід до обліку автопарку
                </div>
                <h1 class="text-4xl font-extrabold sm:text-6xl mb-6 leading-tight">
                    Спочатку користуєтесь — <br class="hidden sm:block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">потім платите</span>
                </h1>
                <p class="text-xl text-slate-300 max-w-2xl mx-auto mb-10">
                    Ми скасували передплату і складні тарифи. Реєструйтеся, наводьте порядок у парку, а рахунок ми виставимо лише в кінці місяця.
                </p>
            </div>
        </section>

        <!-- Pricing Formula Card -->
        <section class="relative -mt-16 pb-20">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-5xl mx-auto bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden">
                    <div class="p-8 sm:p-12">
                        <div class="text-center mb-10">
                            <h2 class="text-2xl font-bold text-slate-900">Проста арифметика вашого успіху</h2>
                            <p class="text-slate-500">Ви платите тільки за те, що реально приносить гроші.</p>
                        </div>

                        <div class="flex flex-col md:flex-row items-center justify-center gap-8 mb-12 relative">
                            <!-- Base Price -->
                            <div class="flex-1 text-center p-6 bg-slate-50 rounded-2xl border border-slate-100 w-full">
                                <div class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">База</div>
                                <div class="text-5xl font-extrabold text-slate-900 mb-2">{{ $model['base_price'] }} <span class="text-2xl font-medium text-slate-400">грн</span></div>
                                <div class="text-slate-600 font-medium">Щомісячна абонплата</div>
                                <div class="text-xs text-slate-400 mt-2">за доступ до системи та сервер</div>
                            </div>

                            <!-- Plus Sign -->
                            <div class="text-4xl text-slate-300 font-light hidden md:block">+</div>

                            <!-- Car Price -->
                            <div class="flex-1 text-center p-6 bg-blue-50 rounded-2xl border border-blue-100 w-full relative overflow-hidden">
                                <div class="absolute top-0 right-0 bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded-bl-lg">Оплата за фактом</div>
                                <div class="text-sm font-bold text-blue-400 uppercase tracking-wider mb-2">Масштаб</div>
                                <div class="text-5xl font-extrabold text-primary mb-2">{{ $model['car_price'] }} <span class="text-2xl font-medium text-blue-300">грн</span></div>
                                <div class="text-slate-600 font-medium">За активне авто</div>
                                <div class="text-xs text-slate-400 mt-2">в місяць</div>
                            </div>
                        </div>

                        <!-- Active Car Explanation -->
                        <div class="bg-yellow-50 rounded-xl p-6 border border-yellow-100 flex gap-4 items-start">
                            <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-600 text-xl">💡</div>
                            <div>
                                <h3 class="font-bold text-yellow-900 mb-1">Що таке "Активне авто"?</h3>
                                <p class="text-yellow-800 text-sm leading-relaxed">
                                    Ми вважаємо автомобіль активним, тільки якщо він відпрацював <strong>мінімум {{ $model['active_condition'] }}</strong> за звітний місяць.
                                    Якщо машина стояла в ремонті, чекала водія або була продана — <strong>ви за неї не платите.</strong>
                                </p>
                            </div>
                        </div>

                        <div class="mt-10 text-center">
                            <button @click="$dispatch('open-order-modal', { type: 'monthly' })" class="inline-block bg-primary text-white font-bold text-lg px-12 py-4 rounded-xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-600/40 transition duration-300 transform hover:-translate-y-1 w-full sm:w-auto">
                                Почати роботу зараз
                            </button>
                            <p class="mt-4 text-sm text-slate-400">
                                <span class="inline-flex items-center gap-1"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Без прив'язки картки</span>
                                <span class="mx-2">•</span>
                                <span>Рахунок прийде через 30 днів</span>
                            </p>

                            <!-- Trust Block -->
                            <div class="mt-8 pt-8 border-t border-slate-100 max-w-2xl mx-auto">
                                <p class="text-slate-500 italic text-sm">
                                    "Ми самі керуємо автопарком. Garage24 зʼявився не як стартап, а як відповідь на реальний хаос, з яким ми стикалися щодня."
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why it's better -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-slate-900">Чому це вигідніше за звичайні тарифи?</h2>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="text-3xl mb-4">📉</div>
                        <h3 class="font-bold text-lg mb-2">Економія на простої</h3>
                        <p class="text-slate-600 text-sm">У вас 20 машин, але 5 в ремонті? В інших системах ви платите за пакет "до 20". У нас — тільки за 15 працюючих.</p>
                    </div>
                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="text-3xl mb-4">🚀</div>
                        <h3 class="font-bold text-lg mb-2">Легкий старт</h3>
                        <p class="text-slate-600 text-sm">Не потрібно платити 5000 грн наперед, щоб просто спробувати. Почніть з малого, платіть з прибутку.</p>
                    </div>
                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="text-3xl mb-4">🔓</div>
                        <h3 class="font-bold text-lg mb-2">Жодних лімітів</h3>
                        <p class="text-slate-600 text-sm">Ми не обмежуємо вас у функціях. Ви отримуєте повний доступ до всіх модулів, навіть якщо у вас всього 3 машини.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- All Included List -->
        <section class="py-20 bg-slate-900 text-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row gap-12 items-start">
                    <div class="md:w-1/3">
                        <h2 class="text-3xl font-bold mb-6">Все включено</h2>
                        <p class="text-slate-400 mb-8">
                            Ми не ділимо клієнтів на сорти. Ви отримуєте повний функціонал одразу, включаючи майбутні оновлення.
                        </p>
                        <div class="p-6 bg-slate-800 rounded-xl border border-slate-700">
                            <div class="text-yellow-400 font-bold mb-2">Бонус за довіру</div>
                            <p class="text-sm text-slate-300 mb-4">Оплатіть абонплату (базу) на рік вперед і отримайте знижку.</p>
                            <div class="text-2xl font-bold text-white mb-4">-100 грн <span class="text-sm font-normal text-slate-400">/ авто щомісяця</span></div>
                            <button @click="$dispatch('open-order-modal', { type: 'yearly' })" class="w-full bg-yellow-500 hover:bg-yellow-400 text-slate-900 font-bold py-2 px-4 rounded-lg transition text-sm">
                                Оформити річну підписку
                            </button>
                        </div>
                    </div>

                    <div class="md:w-2/3 grid sm:grid-cols-2 gap-6">
                        @foreach($model['features'] as $feature)
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-500/20 flex items-center justify-center mt-0.5">
                                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-lg text-slate-200">{{ $feature }}</span>
                            </div>
                        @endforeach
                        <!-- Додаткові фічі -->
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-500/20 flex items-center justify-center mt-0.5">
                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-lg text-slate-200">Безкоштовні оновлення системи</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-green-500/20 flex items-center justify-center mt-0.5">
                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-lg text-slate-200">Персональний онбординг</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="py-20 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-slate-900">Питання про оплату</h2>
                </div>

                <div class="space-y-4" x-data="{ active: null }">
                    <div class="border border-slate-200 rounded-lg overflow-hidden">
                        <button @click="active = (active === 1 ? null : 1)" class="w-full px-6 py-4 text-left bg-slate-50 hover:bg-slate-100 flex justify-between items-center font-semibold text-slate-900 transition">
                            <span>Як виставляється рахунок?</span>
                            <span x-text="active === 1 ? '−' : '+'" class="text-xl text-slate-400"></span>
                        </button>
                        <div x-show="active === 1" class="px-6 py-4 text-slate-600 border-t border-slate-200 bg-white text-sm">
                            Першого числа кожного місяця ми формуємо рахунок на основі активності вашого парку за минулий місяць. Ви отримуєте його на email та в кабінеті. У вас є 5 днів на оплату.
                        </div>
                    </div>

                    <div class="border border-slate-200 rounded-lg overflow-hidden">
                        <button @click="active = (active === 2 ? null : 2)" class="w-full px-6 py-4 text-left bg-slate-50 hover:bg-slate-100 flex justify-between items-center font-semibold text-slate-900 transition">
                            <span>Що якщо я додав авто в кінці місяця?</span>
                            <span x-text="active === 2 ? '−' : '+'" class="text-xl text-slate-400"></span>
                        </button>
                        <div x-show="active === 2" class="px-6 py-4 text-slate-600 border-t border-slate-200 bg-white text-sm">
                            Якщо авто відпрацювало менше 5 змін (або днів) до кінця місяця, плата за нього не стягується в цьому періоді.
                        </div>
                    </div>

                    <div class="border border-slate-200 rounded-lg overflow-hidden">
                        <button @click="active = (active === 3 ? null : 3)" class="w-full px-6 py-4 text-left bg-slate-50 hover:bg-slate-100 flex justify-between items-center font-semibold text-slate-900 transition">
                            <span>Кому НЕ підходить Garage24?</span>
                            <span x-text="active === 3 ? '−' : '+'" class="text-xl text-slate-400"></span>
                        </button>
                        <div x-show="active === 3" class="px-6 py-4 text-slate-600 border-t border-slate-200 bg-white text-sm">
                            Garage24 не підійде, якщо у вас немає власних авто (ви працюєте тільки як диспетчерська) або якщо ви шукаєте складну ERP-систему для великої логістичної компанії. Ми фокусуємося на таксопарках.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Enterprise Link -->
        <section class="py-12 bg-slate-50 border-t border-slate-200">
            <div class="container mx-auto px-4 text-center">
                <p class="text-slate-500 mb-2">У вас великий парк (50+ авто) і потрібні особливі умови?</p>
                <button @click="$dispatch('open-order-modal', { type: 'enterprise' })" class="text-primary font-semibold hover:text-blue-700 transition flex items-center justify-center gap-2 mx-auto">
                    Зв'яжіться з нами для індивідуальної пропозиції
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </button>
            </div>
        </section>
    </main>

    @include('partials.footer')
</body>
</html>