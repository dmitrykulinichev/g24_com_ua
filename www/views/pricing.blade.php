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
        /* Toggle Switch Styles */
        .toggle-checkbox:checked {
            right: 0;
            border-color: #2563eb;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #2563eb;
        }
    </style>

    <!-- Передача конфігурації з бекенду на фронтенд -->
    @if(isset($apiConfig) && $apiConfig)
    <script>
        window.landingConfig = {!! json_encode($apiConfig) !!};
    </script>
    @endif
</head>
<!-- Додаємо x-data, щоб Alpine працював на всій сторінці -->
<body class="text-slate-800 antialiased bg-white" x-data="{ yearly: false }">
    @include('partials.header')

    <main>
        <!-- Hero Section (Збільшено padding-top) -->
        <section class="relative pt-32 pb-20 lg:pt-40 bg-slate-900 text-white overflow-hidden">
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
                        <div class="text-center mb-8">
                            <h2 class="text-2xl font-bold text-slate-900">Проста арифметика вашого успіху</h2>
                            <p class="text-slate-500 mb-6">Ви платите тільки за те, що реально приносить гроші.</p>

                            <!-- Toggle Switch -->
                            <div class="flex items-center justify-center gap-4 mb-8">
                                <span class="text-sm font-medium" :class="!yearly ? 'text-slate-900' : 'text-slate-500'">Щомісячна оплата</span>
                                <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                                    <input type="checkbox" name="toggle" id="toggle" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer transition-all duration-300" :class="yearly ? 'right-0 border-primary' : 'left-0 border-slate-300'" @click="yearly = !yearly"/>
                                    <label for="toggle" class="toggle-label block overflow-hidden h-6 rounded-full cursor-pointer transition-colors duration-300" :class="yearly ? 'bg-primary' : 'bg-slate-300'"></label>
                                </div>
                                <span class="text-sm font-medium" :class="yearly ? 'text-slate-900' : 'text-slate-500'">
                                    Річна передплата
                                    <span class="ml-1 inline-block bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full font-bold">-50% на авто</span>
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row items-center justify-center gap-8 mb-12 relative">
                            <!-- Base Price -->
                            <div class="flex-1 text-center p-6 bg-slate-50 rounded-2xl border border-slate-100 w-full transition-all duration-300" :class="yearly ? 'ring-2 ring-primary/20' : ''">
                                <div class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">База</div>
                                <div class="text-5xl font-extrabold text-slate-900 mb-2">
                                    <span x-text="yearly ? '{{ $model['yearly']['base'] }}' : '{{ $model['monthly']['base'] }}'"></span>
                                    <span class="text-2xl font-medium text-slate-400">грн</span>
                                </div>
                                <div class="text-slate-600 font-medium">Щомісячна абонплата</div>
                                <div class="text-xs text-slate-400 mt-2" x-show="!yearly">за доступ до системи та сервер</div>
                                <div class="text-xs text-primary font-bold mt-2" x-show="yearly" style="display: none;">сплачується за 12 місяців</div>
                            </div>

                            <!-- Plus Sign -->
                            <div class="text-4xl text-slate-300 font-light hidden md:block">+</div>

                            <!-- Car Price -->
                            <div class="flex-1 text-center p-6 bg-blue-50 rounded-2xl border border-blue-100 w-full relative overflow-hidden transition-all duration-300" :class="yearly ? 'bg-green-50 border-green-200' : ''">
                                <div class="absolute top-0 right-0 text-white text-xs font-bold px-2 py-1 rounded-bl-lg transition-colors duration-300" :class="yearly ? 'bg-green-500' : 'bg-blue-500'" x-text="yearly ? 'Супер ціна' : 'Оплата за фактом'"></div>
                                <div class="text-sm font-bold uppercase tracking-wider mb-2 transition-colors duration-300" :class="yearly ? 'text-green-500' : 'text-blue-400'">Масштаб</div>

                                <div class="mb-2 h-12 flex items-center justify-center">
                                    <div x-show="!yearly" class="text-5xl font-extrabold text-primary transition-all duration-300">
                                        {{ $model['monthly']['car'] }} <span class="text-2xl font-medium text-blue-300">грн</span>
                                    </div>
                                    <div x-show="yearly" style="display: none;" class="text-5xl font-extrabold text-green-600 transition-all duration-300 flex items-center gap-2">
                                        {{ $model['yearly']['car'] }} <span class="text-2xl font-medium text-green-300">грн</span>
                                        <span class="text-lg text-slate-400 line-through decoration-2 decoration-red-400 opacity-60">{{ $model['yearly']['old_car'] }}</span>
                                    </div>
                                </div>

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
                            <button @click="$dispatch('open-order-modal', { type: yearly ? 'yearly' : 'monthly' })" class="inline-block bg-primary text-white font-bold text-lg px-12 py-4 rounded-xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-600/40 transition duration-300 transform hover:-translate-y-1 w-full sm:w-auto">
                                <span x-text="yearly ? 'Оформити річну підписку' : 'Почати роботу зараз'"></span>
                            </button>
                            <div class="mt-4 flex flex-col sm:flex-row items-center justify-center gap-2 text-sm text-slate-400">
                                <span class="inline-flex items-center gap-1"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Без прив'язки картки</span>
                                <span class="mx-2">•</span>
                                <span>Рахунок прийде через 30 днів</span>
                            </div>
                            <div class="mt-2">
                                <a href="/blog/how-billing-works" class="text-primary hover:text-blue-700 text-sm font-medium underline decoration-dashed underline-offset-4">
                                    Детальніше про те, як ми нараховуємо оплату →
                                </a>
                            </div>

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
                            <div class="text-2xl font-bold text-white mb-4">-{{ $model['yearly']['old_car'] - $model['yearly']['car'] }} грн <span class="text-sm font-normal text-slate-400">/ авто щомісяця</span></div>
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
                            Рахунок формується автоматично на наступний день після завершення вашого розрахункового періоду (місяць з дати реєстрації). Ви отримуєте його на email та в кабінеті. У вас є 5 днів на оплату.
                        </div>
                    </div>

                    <div class="border border-slate-200 rounded-lg overflow-hidden">
                        <button @click="active = (active === 2 ? null : 2)" class="w-full px-6 py-4 text-left bg-slate-50 hover:bg-slate-100 flex justify-between items-center font-semibold text-slate-900 transition">
                            <span>Що якщо я додав авто в кінці місяця?</span>
                            <span x-text="active === 2 ? '−' : '+'" class="text-xl text-slate-400"></span>
                        </button>
                        <div x-show="active === 2" class="px-6 py-4 text-slate-600 border-t border-slate-200 bg-white text-sm">
                            У нас немає поняття "кінець календарного місяця". Ми рахуємо активність авто протягом <strong>вашого персонального розрахункового періоду</strong> (місяць з дати реєстрації). Якщо за цей час авто відпрацювало менше 5 змін, плата за нього не стягується.
                        </div>
                    </div>

                    <div class="border border-slate-200 rounded-lg overflow-hidden">
                        <button @click="active = (active === 3 ? null : 3)" class="w-full px-6 py-4 text-left bg-slate-50 hover:bg-slate-100 flex justify-between items-center font-semibold text-slate-900 transition">
                            <span>Чи можу я платити як ФОП/ТОВ?</span>
                            <span x-text="active === 3 ? '−' : '+'" class="text-xl text-slate-400"></span>
                        </button>
                        <div x-show="active === 3" class="px-6 py-4 text-slate-600 border-t border-slate-200 bg-white text-sm">
                            Так, ми працюємо офіційно і надаємо всі необхідні документи для бухгалтерії.
                        </div>
                    </div>

                    <!-- Нове питання про автосписання -->
                    <div class="border border-slate-200 rounded-lg overflow-hidden">
                        <button @click="active = (active === 4 ? null : 4)" class="w-full px-6 py-4 text-left bg-slate-50 hover:bg-slate-100 flex justify-between items-center font-semibold text-slate-900 transition">
                            <span>Чи можна налаштувати автосписання?</span>
                            <span x-text="active === 4 ? '−' : '+'" class="text-xl text-slate-400"></span>
                        </button>
                        <div x-show="active === 4" class="px-6 py-4 text-slate-600 border-t border-slate-200 bg-white text-sm">
                            Так. Ви можете прив'язати картку для автоматичної оплати. Ми попередимо вас про суму списання за 24 години, а після оплати надішлемо квитанцію.
                        </div>
                    </div>

                    <div class="border border-slate-200 rounded-lg overflow-hidden">
                        <button @click="active = (active === 5 ? null : 5)" class="w-full px-6 py-4 text-left bg-slate-50 hover:bg-slate-100 flex justify-between items-center font-semibold text-slate-900 transition">
                            <span>Кому НЕ підходить Garage24?</span>
                            <span x-text="active === 5 ? '−' : '+'" class="text-xl text-slate-400"></span>
                        </button>
                        <div x-show="active === 5" class="px-6 py-4 text-slate-600 border-t border-slate-200 bg-white text-sm">
                            Garage24 не підійде, якщо у вас немає власних авто (ви працюєте тільки як диспетчерська) або якщо ви шукаєте складну ERP-систему для великої логістичної компанії. Ми фокусуємося на таксопарках.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Enterprise Link -->
        <section class="py-16 bg-slate-50 border-t border-slate-200">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto bg-white rounded-2xl p-8 shadow-lg border border-slate-100 flex flex-col md:flex-row items-center gap-8">
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Ми відкриті до партнерства</h3>
                        <p class="text-slate-600 text-sm mb-4">
                            Ми не просто продаємо софт, ми будуємо спільноту. Якщо вам потрібна специфічна функція, інтеграція або особливі умови — ми готові це обговорити.
                        </p>
                        <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Кастомна розробка</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Виділений сервер</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">White Label</span>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <button @click="$dispatch('open-order-modal', { type: 'enterprise' })" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-slate-800 hover:bg-slate-700 transition shadow-md">
                            Обговорити ідеї
                            <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </main>


    @include('partials.footer')
</body>
</html>