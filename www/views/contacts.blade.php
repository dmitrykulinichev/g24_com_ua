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
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
                <h1 class="text-4xl font-extrabold sm:text-5xl mb-6">Зв'яжіться з нами</h1>
                <p class="text-xl text-slate-300 max-w-2xl mx-auto">Оберіть зручний спосіб комунікації залежно від вашого запиту.</p>
            </div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 -mt-10 relative z-10">
            <div class="grid md:grid-cols-5 gap-8">

                <!-- Блок для нових клієнтів (Форма) -->
                <div class="md:col-span-3 bg-white rounded-2xl shadow-lg border border-slate-100 p-8" x-data="{
                    formData: { name: '', email: '', phone: '', message: '' },
                    loading: false,
                    success: false,
                    error: null,
                    captchaWidgetId: null,

                    init() {
                        setTimeout(() => {
                            if (typeof grecaptcha !== 'undefined') {
                                try {
                                    this.captchaWidgetId = grecaptcha.render('contact-recaptcha', {
                                        'sitekey': '{{ $_ENV['RECAPTCHA_SITE_KEY'] ?? 'YOUR_SITE_KEY' }}'
                                    });
                                } catch (e) {
                                    console.error('Captcha render error:', e);
                                }
                            }
                        }, 500);
                    },

                    submitForm() {
                        let captchaToken = '';
                        if (typeof grecaptcha !== 'undefined') {
                            try {
                                captchaToken = grecaptcha.getResponse(this.captchaWidgetId);
                            } catch (e) {}

                            @if(($_ENV['RECAPTCHA_SITE_KEY'] ?? '') !== '' && ($_ENV['RECAPTCHA_SITE_KEY'] ?? '') !== 'YOUR_SITE_KEY')
                                if (!captchaToken) {
                                    this.error = 'Будь ласка, пройдіть перевірку &quot;Я не робот&quot;.';
                                    return;
                                }
                            @endif
                        }

                        this.loading = true;
                        this.error = null;

                        let payload = {
                            name: this.formData.name,
                            email: this.formData.email,
                            phone: this.formData.phone,
                            company: 'Питання з сайту: ' + this.formData.message,
                            'g-recaptcha-response': captchaToken
                        };

                        fetch('/api/lead', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(payload)
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.loading = false;
                            if (data.status === 'success') {
                                this.success = true;
                                this.formData = { name: '', email: '', phone: '', message: '' };
                                if (typeof grecaptcha !== 'undefined') try { grecaptcha.reset(this.captchaWidgetId); } catch(e){}
                            } else {
                                this.error = data.errors ? Object.values(data.errors)[0] : data.message;
                                if (typeof grecaptcha !== 'undefined') try { grecaptcha.reset(this.captchaWidgetId); } catch(e){}
                            }
                        })
                        .catch(() => {
                            this.loading = false;
                            this.error = 'Сталася помилка. Спробуйте пізніше.';
                        });
                    }
                }">
                    <h2 class="text-2xl font-bold text-slate-900 mb-2">Ще не з нами?</h2>
                    <p class="text-slate-600 mb-8">Заповніть форму, якщо у вас є питання щодо підключення, тарифів або можливостей системи.</p>

                    <div x-show="success" class="bg-green-50 text-green-800 p-6 rounded-lg text-center mb-6 border border-green-100">
                        <div class="text-4xl mb-2">✅</div>
                        <strong>Повідомлення відправлено!</strong><br>
                        Ми зв'яжемося з вами найближчим часом.
                    </div>

                    <div x-show="error" class="bg-red-50 text-red-800 p-4 rounded-lg mb-6 border border-red-100 text-sm" x-text="error"></div>

                    <form x-show="!success" @submit.prevent="submitForm" class="space-y-4">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Ваше ім'я</label>
                                <input type="text" x-model="formData.name" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                                <input type="email" x-model="formData.email" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Телефон</label>
                            <input type="tel" x-model="formData.phone" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Ваше питання</label>
                            <textarea x-model="formData.message" rows="4" required placeholder="Наприклад: Чи є інтеграція з Bolt?" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"></textarea>
                        </div>

                        <div class="flex justify-center">
                            <div id="contact-recaptcha"></div>
                        </div>

                        <button type="submit" class="w-full bg-primary text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition disabled:opacity-70" :disabled="loading">
                            <span x-show="!loading">Відправити запит</span>
                            <span x-show="loading">Відправка...</span>
                        </button>
                    </form>
                </div>

                <!-- Блок для існуючих клієнтів -->
                <div class="md:col-span-2 bg-slate-50 rounded-2xl border border-slate-200 p-8 h-fit">
                    <h3 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <span class="text-2xl">🔑</span>
                        Вже клієнт Garage24?
                    </h3>
                    <p class="text-slate-600 text-sm mb-4 leading-relaxed">
                        Ця форма призначена для загальних питань.
                        Для швидкого вирішення технічних проблем, будь ласка, створіть тікет у вашому особистому кабінеті.
                    </p>
                    <p class="text-slate-600 text-sm mb-6 leading-relaxed">
                        Там ми бачимо історію вашого парку і зможемо допомогти набагато швидше.
                    </p>

                    <a href="https://app.g24.com.ua/support" target="_blank" class="block w-full text-center py-3 bg-white border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-100 hover:text-primary transition shadow-sm">
                        Перейти в гараж
                    </a>

                    <div class="mt-8 pt-8 border-t border-slate-200">
                        <div class="font-bold text-slate-900 mb-2">Інші контакти:</div>
                        <div class="mb-1"><a href="mailto:support@g24.com.ua" class="text-primary hover:underline">support@g24.com.ua</a></div>
                        <div class="text-slate-500 text-sm">Пн-Пт: 10:00 - 18:00</div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    @include('partials.footer')
</body>
</html>