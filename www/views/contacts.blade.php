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

    <!-- Підключення Google reCAPTCHA (Локально для цієї сторінки) -->
    <script>
        function loadRecaptchaV3(siteKey) {
            if (document.getElementById('recaptcha-script')) return;
            const script = document.createElement('script');
            script.id = 'recaptcha-script';
            script.src = `https://www.google.com/recaptcha/api.js?render=${siteKey}`;
            document.head.appendChild(script);
        }
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Стилі для помилок */
        .border-red-500 { border-color: #ef4444 !important; }
        .text-red-500 { color: #ef4444; }
        .text-xs { font-size: 0.75rem; }
        .mt-1 { margin-top: 0.25rem; }
        /* Приховуємо бейдж рекапчі */
        .grecaptcha-badge { visibility: hidden; }
        [x-cloak] { display: none !important; }
    </style>

    <!-- Передача конфігурації з бекенду -->
    @if(isset($apiConfig) && $apiConfig)
    <script>
        window.landingConfig = {!! json_encode($apiConfig) !!};
    </script>
    @endif
</head>
<body class="text-slate-800 antialiased bg-white">
    @include('partials.header')

    <main>
        <!-- Hero Section -->
        <div class="relative bg-slate-900 text-white pt-32 pb-16 lg:pt-40 lg:pb-24 text-center overflow-hidden">
            <div class="absolute inset-0 bg-[url('/assets/img/grid.svg')] opacity-10"></div>
            <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-blue-900/50 to-transparent"></div>

            <div class="container mx-auto px-4 relative z-10">
                <h1 class="text-4xl font-extrabold sm:text-5xl mb-6">Зв'яжіться з нами</h1>
                <p class="text-xl text-slate-300 max-w-2xl mx-auto">Оберіть зручний спосіб комунікації залежно від вашого запиту.</p>
            </div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 -mt-10 relative z-10">
            <div class="grid md:grid-cols-5 gap-8">

                <!-- Блок для нових клієнтів (Динамічна Форма) -->
                <div class="md:col-span-3 bg-white rounded-2xl shadow-lg border border-slate-100 p-8 min-h-[400px]" x-data="{
                    formData: {},
                    loading: false,
                    success: false,
                    ready: false, // Прапорець готовності форми
                    generalError: null,
                    fieldErrors: {},
                    siteKey: '{{ $_ENV['RECAPTCHA_SITE_KEY'] ?? '' }}',
                    fields: [],

                    // Fallback конфігурація
                    fallbackFields: [
                        {name: 'name', type: 'text', required: false, label: 'Ваше ім\'я', placeholder: 'Іван Іванов'},
                        {name: 'email', type: 'email', required: true, label: 'Email', placeholder: 'email@example.com'},
                        {name: 'phone', type: 'tel', required: false, label: 'Телефон', placeholder: '+380 ...'},
                        {name: 'message', type: 'textarea', required: true, label: 'Ваше питання', placeholder: 'Наприклад: Чи є інтеграція з Bolt?'},
                        {name: 'type', type: 'hidden', required: false, default: 'contact'}
                    ],

                    init() {
                        if (window.landingConfig) {
                            this.applyConfig(window.landingConfig);
                        } else {
                            this.fields = this.fallbackFields;
                            this.initFormData();
                            this.ready = true; // Готово (fallback)

                            if (this.siteKey && this.siteKey !== 'YOUR_V3_SITE_KEY') {
                                loadRecaptchaV3(this.siteKey);
                            }
                        }

                        this.waitForRecaptcha();
                    },

                    waitForRecaptcha() {
                        let attempts = 0;
                        const check = () => {
                            if (typeof grecaptcha !== 'undefined' && grecaptcha.render) {
                                // Ready
                            } else if (attempts < 20) {
                                attempts++;
                                setTimeout(check, 500);
                            }
                        };
                        check();
                    },

                    applyConfig(data) {
                        if (data.recaptcha_site_key) {
                            this.siteKey = data.recaptcha_site_key;
                            loadRecaptchaV3(this.siteKey);
                        } else if (this.siteKey && this.siteKey !== 'YOUR_V3_SITE_KEY') {
                            loadRecaptchaV3(this.siteKey);
                        }

                        if (data.forms && data.forms.lead && data.forms.lead.fields) {
                            this.fields = data.forms.lead.fields;
                            const typeField = this.fields.find(f => f.name === 'type');
                            if (typeField) {
                                typeField.default = 'contact';
                            }
                        } else {
                            this.fields = this.fallbackFields;
                        }

                        this.initFormData();
                        this.ready = true; // Готово (з конфігу)
                    },

                    initFormData() {
                        this.fields.forEach(field => {
                            if (field.default !== undefined) {
                                this.formData[field.name] = field.default;
                            } else {
                                this.formData[field.name] = '';
                            }
                        });
                    },

                    async getRecaptchaToken() {
                        if (!this.siteKey || this.siteKey === 'YOUR_V3_SITE_KEY') return '';

                        return new Promise((resolve) => {
                            const checkGrecaptcha = setInterval(() => {
                                if (typeof grecaptcha !== 'undefined' && grecaptcha.ready) {
                                    clearInterval(checkGrecaptcha);
                                    grecaptcha.ready(() => {
                                        grecaptcha.execute(this.siteKey, {action: 'contact'}).then((token) => {
                                            resolve(token);
                                        });
                                    });
                                }
                            }, 100);

                            setTimeout(() => {
                                clearInterval(checkGrecaptcha);
                                resolve('');
                            }, 5000);
                        });
                    },

                    async submitForm() {
                        this.generalError = null;
                        this.fieldErrors = {};

                        let hasEmptyRequired = false;
                        this.fields.forEach(field => {
                            if (field.required && !this.formData[field.name] && field.type !== 'hidden') {
                                this.fieldErrors[field.name] = 'Це поле обов\'язкове';
                                hasEmptyRequired = true;
                            }
                        });
                        if (hasEmptyRequired) return;

                        this.loading = true;

                        let captchaToken = '';
                        try {
                            captchaToken = await this.getRecaptchaToken();
                        } catch (e) {
                            console.error('Recaptcha error:', e);
                        }

                        let payload = { ...this.formData };
                        if (captchaToken) {
                            payload['g-recaptcha-response'] = captchaToken;
                        }
                        if (!payload.type) payload.type = 'contact';

                        fetch('/api/lead', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(payload)
                        })
                        .then(async response => {
                            const data = await response.json();
                            this.loading = false;

                            if (response.ok) {
                                this.success = true;
                                this.initFormData();
                            } else {
                                if (response.status === 422 && data.errors) {
                                    const apiErrors = data.errors;
                                    Object.keys(apiErrors).forEach(key => {
                                        this.fieldErrors[key] = apiErrors[key][0];
                                    });

                                    const knownKeys = this.fields.map(f => f.name);
                                    const unknownErrors = Object.keys(apiErrors).filter(key => !knownKeys.includes(key));

                                    if (unknownErrors.length > 0) {
                                        this.generalError = apiErrors[unknownErrors[0]][0];
                                    }
                                } else {
                                    this.generalError = data.message || 'Сталася помилка. Спробуйте пізніше.';
                                }
                            }
                        })
                        .catch(() => {
                            this.loading = false;
                            this.generalError = 'Сталася помилка мережі. Спробуйте пізніше.';
                        });
                    }
                }">
                    <h2 class="text-2xl font-bold text-slate-900 mb-2">Ще не з нами?</h2>
                    <p class="text-slate-600 mb-8">Заповніть форму, якщо у вас є питання щодо підключення, тарифів або можливостей системи.</p>

                    <!-- Спінер завантаження -->
                    <div x-show="!ready" class="flex justify-center items-center h-64">
                        <svg class="animate-spin h-10 w-10 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <div x-show="success" x-cloak class="bg-green-50 text-green-800 p-6 rounded-lg text-center mb-6 border border-green-100">
                        <div class="text-4xl mb-2">✅</div>
                        <strong>Повідомлення відправлено!</strong><br>
                        Ми зв'яжемося з вами найближчим часом.
                    </div>

                    <div x-show="generalError" x-cloak class="bg-red-50 text-red-800 p-4 rounded-lg mb-6 border border-red-100 text-sm" x-text="generalError"></div>

                    <!-- Форма показується тільки коли ready = true -->
                    <form x-show="ready && !success" @submit.prevent="submitForm" class="space-y-4" novalidate x-cloak>

                        <!-- Динамічні поля -->
                        <template x-for="field in fields" :key="field.name">
                            <div x-show="field.type !== 'hidden'">
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    <span x-text="field.label"></span>
                                    <span x-show="field.required" class="text-red-500">*</span>
                                </label>

                                <!-- Text/Email/Tel Input -->
                                <template x-if="['text', 'email', 'tel'].includes(field.type)">
                                    <input :type="field.type"
                                           x-model="formData[field.name]"
                                           :placeholder="field.placeholder"
                                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"
                                           :class="{'border-red-500': fieldErrors[field.name]}">
                                </template>

                                <!-- Textarea -->
                                <template x-if="field.type === 'textarea'">
                                    <textarea x-model="formData[field.name]"
                                              rows="4"
                                              :placeholder="field.placeholder"
                                              class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"
                                              :class="{'border-red-500': fieldErrors[field.name]}"></textarea>
                                </template>

                                <!-- Помилка поля -->
                                <div x-show="fieldErrors[field.name]" x-text="fieldErrors[field.name]" class="text-red-500 text-xs mt-1"></div>
                            </div>
                        </template>

                        <button type="submit" class="w-full bg-primary text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition disabled:opacity-70" :disabled="loading">
                            <span x-show="!loading">Відправити запит</span>
                            <span x-show="loading">Відправка...</span>
                        </button>

                        <div class="text-center text-xs text-gray-400 mt-2">
                            Цей сайт захищений reCAPTCHA і застосовуються
                            <a href="https://policies.google.com/privacy" class="underline" target="_blank">Політика конфіденційності</a> та
                            <a href="https://policies.google.com/terms" class="underline" target="_blank">Умови використання</a> Google.
                        </div>
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
                        <div class="text-slate-500 text-sm">Пн-Пт: 10:00 - 18:00</div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    @include('partials.footer')
</body>
</html>