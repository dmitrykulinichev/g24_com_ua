@extends('layout')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-slate-900 text-white pt-32 pb-16 lg:pt-40 lg:pb-24 text-center overflow-hidden">
        <div class="absolute inset-0 bg-[url('/assets/img/grid.svg')] opacity-10"></div>
        <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-blue-900/50 to-transparent"></div>

        <div class="container mx-auto px-4 relative z-10">
            <h1 class="text-4xl font-extrabold sm:text-5xl mb-6">{{ t('contacts.hero.title') }}</h1>
            <p class="text-xl text-slate-300 max-w-2xl mx-auto">{{ t('contacts.hero.subtitle') }}</p>
        </div>
    </section>

    <section class="py-16 -mt-10 relative z-10">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-5 gap-8">

                <!-- Блок для нових клієнтів (Динамічна Форма) -->
                <div class="md:col-span-3 bg-white rounded-2xl shadow-lg border border-slate-100 p-8 min-h-[400px]" x-data="{
                    formData: {},
                    loading: false,
                    success: false,
                    ready: false,
                    generalError: null,
                    fieldErrors: {},
                    siteKey: '{{ $_ENV['RECAPTCHA_SITE_KEY'] ?? '' }}',
                    recaptchaEnabled: {{ $recaptchaEnabled ? 'true' : 'false' }},
                    fields: [],

                    // Fallback конфігурація
                    fallbackFields: [
                        {name: 'name', type: 'text', required: false, label: {{ t_js('contacts.form.fallback.name.label') }}, placeholder: {{ t_js('contacts.form.fallback.name.placeholder') }}},
                        {name: 'email', type: 'email', required: true, label: {{ t_js('contacts.form.fallback.email.label') }}, placeholder: 'email@example.com'},
                        {name: 'phone', type: 'tel', required: false, label: {{ t_js('contacts.form.fallback.phone.label') }}, placeholder: '+380 ...'},
                        {name: 'message', type: 'textarea', required: true, label: {{ t_js('contacts.form.fallback.message.label') }}, placeholder: {{ t_js('contacts.form.fallback.message.placeholder') }}},
                        {name: 'type', type: 'hidden', required: false, default: 'contact'}
                    ],

                    init() {
                        if (window.landingConfig) {
                            this.applyConfig(window.landingConfig);
                        } else {
                            this.fields = this.fallbackFields;
                            this.initFormData();
                            this.ready = true;

                            if (this.recaptchaEnabled && this.siteKey && this.siteKey !== 'YOUR_V3_SITE_KEY') {
                                loadRecaptchaV3(this.siteKey);
                            }
                        }

                        if (this.recaptchaEnabled) {
                            this.waitForRecaptcha();
                        }
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
                        if (this.recaptchaEnabled) {
                            if (data.recaptcha_site_key) {
                                this.siteKey = data.recaptcha_site_key;
                                loadRecaptchaV3(this.siteKey);
                            } else if (this.siteKey && this.siteKey !== 'YOUR_V3_SITE_KEY') {
                                loadRecaptchaV3(this.siteKey);
                            }
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
                        this.ready = true;
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
                        if (!this.recaptchaEnabled) return '';
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
                                this.fieldErrors[field.name] = {{ t_js('contacts.form.errors.required') }};
                                hasEmptyRequired = true;
                            }
                        });
                        if (hasEmptyRequired) return;

                        this.loading = true;

                        let captchaToken = '';
                        if (this.recaptchaEnabled) {
                            try {
                                captchaToken = await this.getRecaptchaToken();
                            } catch (e) {
                                console.error('Recaptcha error:', e);
                            }
                        } else {
                            captchaToken = '';
                        }

                        let payload = { ...this.formData };
                        if (captchaToken) {
                            payload['g-recaptcha-response'] = captchaToken;
                        }
                        if (!payload.type) payload.type = 'contact';

                        apiFetch('/api/lead', {
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

                                // DataLayer Event
                                window.dataLayer = window.dataLayer || [];
                                window.dataLayer.push({
                                    'event': 'lead_generated',
                                    'lead_type': 'contact_form'
                                });
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
                                    this.generalError = data.message || {{ t_js('contacts.form.errors.general') }};
                                }
                            }
                        })
                        .catch(() => {
                            this.loading = false;
                            this.generalError = {{ t_js('contacts.form.errors.network') }};
                        });
                    }
                }">
                    <h2 class="text-2xl font-bold text-slate-900 mb-2">{{ t('contacts.form.title') }}</h2>
                    <p class="text-slate-600 mb-8">{{ t('contacts.form.text') }}</p>

                    <!-- Спінер завантаження -->
                    <div x-show="!ready" class="flex justify-center items-center h-64">
                        <svg class="animate-spin h-10 w-10 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <div x-show="success" x-cloak class="bg-green-50 text-green-800 p-6 rounded-lg text-center mb-6 border border-green-100">
                        <div class="text-4xl mb-2">✅</div>
                        <strong>{{ t('contacts.form.success.title') }}</strong><br>
                        {{ t('contacts.form.success.text') }}
                    </div>

                    <div x-show="generalError" x-cloak class="bg-red-50 text-red-800 p-4 rounded-lg mb-6 border border-red-100 text-sm" x-text="generalError"></div>

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
                            <span x-show="!loading">{{ t('contacts.form.submit') }}</span>
                            <span x-show="loading">{{ t('contacts.form.sending') }}</span>
                        </button>

                        @if($recaptchaEnabled)
                        <div class="text-center text-xs text-gray-400 mt-2">
                            {{ t('contacts.form.recaptcha.text') }}
                            <a href="https://policies.google.com/privacy" class="underline" target="_blank">{{ t('contacts.form.recaptcha.privacy') }}</a> {{ t('contacts.form.recaptcha.and') }}
                            <a href="https://policies.google.com/terms" class="underline" target="_blank">{{ t('contacts.form.recaptcha.terms') }}</a> Google.
                        </div>
                        @endif
                    </form>
                </div>

                <!-- Блок для існуючих клієнтів -->
                <div class="md:col-span-2 bg-slate-50 rounded-2xl border border-slate-200 p-8 h-fit">
                    <h3 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <span class="text-2xl">🔑</span>
                        {{ t('contacts.clients.title') }}
                    </h3>
                    <p class="text-slate-600 text-sm mb-4 leading-relaxed">
                        {{ t('contacts.clients.text') }}
                    </p>
                    <p class="text-slate-600 text-sm mb-6 leading-relaxed">
                        {{ t('contacts.clients.text_2') }}
                    </p>

                    <a href="https://app.g24.com.ua/support" target="_blank" class="block w-full text-center py-3 bg-white border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-100 hover:text-primary transition shadow-sm">
                        {{ t('contacts.clients.cta') }}
                    </a>

                    <div class="mt-8 pt-8 border-t border-slate-200">
                        <div class="text-slate-500 text-sm">{{ t('contacts.clients.hours') }}</div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('scripts')
@if($recaptchaEnabled)
<script>
    function loadRecaptchaV3(siteKey) {
        if (document.getElementById('recaptcha-script')) return;
        const script = document.createElement('script');
        script.id = 'recaptcha-script';
        script.src = `https://www.google.com/recaptcha/api.js?render=${siteKey}`;
        document.head.appendChild(script);
    }
</script>
@endif
@endpush