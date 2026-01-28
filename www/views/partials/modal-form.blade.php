<!-- Підключення Google reCAPTCHA -->
@php
    $recaptchaEnabled = filter_var($_ENV['RECAPTCHA_ENABLED'] ?? true, FILTER_VALIDATE_BOOLEAN);
@endphp

@if($recaptchaEnabled)
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endif

<!-- Логіка Alpine.js винесена в скрипт -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('orderForm', () => ({
            showModal: false,
            orderType: 'monthly', // monthly, yearly, enterprise
            formData: {}, // Динамічні дані
            loading: false,
            success: false,
            successMessage: '',
            generalError: null,
            fieldErrors: {},
            siteKey: '{{ $_ENV['RECAPTCHA_SITE_KEY'] ?? '' }}',
            recaptchaEnabled: {{ $recaptchaEnabled ? 'true' : 'false' }},
            plans: [],

            // Resend Logic
            resendLoading: false,
            resendTimer: 0,
            registeredEmail: null,
            isActivated: false,

            // Draft Logic
            draftKey: 'registrationDraft',

            // Конфігурація форм (Fallback)
            forms: {
                register_park: {
                    fields: [
                        {name: "owner_name", type: "text", required: true, label: "Ваше ім'я", placeholder: "Іван Іванов"},
                        {name: "owner_email", type: "email", required: true, label: "Email (Логін)", placeholder: "email@example.com"},
                        {name: "park_name", type: "text", required: true, label: "Назва парку / Компанії", placeholder: "ТОВ Автопарк"},
                        {name: "phone", type: "tel", required: true, label: "Телефон", placeholder: "+380 ..."},
                        {name: "plan", type: "hidden", required: true, default: "monthly"}
                    ]
                },
                lead: {
                    fields: [
                        {name: "name", type: "text", required: false, label: "Ваше ім'я", placeholder: "Іван Іванов"},
                        {name: "email", type: "email", required: true, label: "Email", placeholder: "email@example.com"},
                        {name: "phone", type: "tel", required: false, label: "Телефон", placeholder: "+380 ..."},
                        {name: "message", type: "textarea", required: true, label: "Ваше повідомлення", placeholder: "Кількість авто, особливі побажання..."},
                        {name: "type", type: "hidden", required: false, default: "general"}
                    ]
                }
            },

            activeFormKey: 'register_park',

            init() {
                if (window.landingConfig) {
                    this.applyConfig(window.landingConfig);
                } else {
                    this.fetchConfig();
                    if (this.recaptchaEnabled && this.siteKey && this.siteKey !== 'YOUR_V3_SITE_KEY') {
                        this.loadRecaptchaV3(this.siteKey);
                    }
                }

                // Відновлення таймера
                const state = this.getState();
                if (state.lastResend) {
                    const diff = Math.floor((Date.now() - state.lastResend) / 1000);
                    if (diff < 60) {
                        this.startResendTimer(60 - diff);
                    }
                }

                // Авто-збереження чернетки
                this.$watch('formData', (val) => {
                    if (this.activeFormKey === 'register_park' && !this.success) {
                        this.updateState({ draft: val });
                    }
                });

                // Відстеження покинутої форми
                document.addEventListener('visibilitychange', () => {
                    if (document.visibilityState === 'hidden') {
                        this.sendAbandonedData();
                    }
                });

                window.addEventListener('open-order-modal', (event) => {
                    this.showModal = true;
                    this.orderType = event.detail.type || 'monthly';
                    this.updateActiveForm();

                    this.success = false;
                    this.isActivated = false;
                    this.generalError = null;
                    this.fieldErrors = {};

                    this.initFormData();
                    this.restoreDraft();
                    this.checkRegistrationStatus();

                    if (this.recaptchaEnabled) {
                        this.waitForRecaptcha();
                    }
                });

                this.$watch('orderType', (value) => {
                    this.updateActiveForm();
                    if (this.activeFormKey === 'register_park') {
                        this.formData.plan = value;
                        this.checkRegistrationStatus();
                    } else {
                        this.success = false;
                    }
                });
            },

            // --- Storage Helpers ---
            getState() {
                try {
                    return JSON.parse(localStorage.getItem(this.storageKey)) || {};
                } catch (e) {
                    return {};
                }
            },

            updateState(newData) {
                const state = this.getState();
                const updated = { ...state, ...newData };
                localStorage.setItem(this.storageKey, JSON.stringify(updated));
            },
            // -----------------------

            sendAbandonedData() {
                if (this.activeFormKey === 'register_park' && !this.success && this.formData.owner_email) {
                    const data = JSON.stringify(this.formData);
                    const blob = new Blob([data], {type: 'application/json'});
                    navigator.sendBeacon('/api/abandoned', blob);
                }
            },

            restoreDraft() {
                if (this.activeFormKey !== 'register_park') return;

                const state = this.getState();
                if (state.draft) {
                    this.formData = { ...this.formData, ...state.draft };
                    this.formData.plan = this.orderType;
                }
            },

            checkRegistrationStatus() {
                if (this.activeFormKey !== 'register_park') return;

                const state = this.getState();

                // 1. Перевірка активації
                if (state.isActivated) {
                    this.success = true;
                    this.isActivated = true;
                    this.successMessage = 'Ваш акаунт вже активовано.';
                    return;
                }

                // 2. Перевірка успішної реєстрації
                if (state.registration) {
                    if (Date.now() - state.registration.timestamp < 86400000) {
                        this.registeredEmail = state.registration.email;
                        this.checkUserStatus(state.registration.email);
                        return;
                    }
                }

                // 3. Перевірка email з форми
                if (this.formData.owner_email && this.isValidEmail(this.formData.owner_email)) {
                    this.checkUserStatus(this.formData.owner_email);
                }
            },

            isValidEmail(email) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            },

            async checkUserStatus(email) {
                if (!email) return;

                try {
                    const response = await fetch('/api/check-status', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ email: email })
                    });

                    const data = await response.json();

                    if (data.exists) {
                        this.success = true;
                        this.registeredEmail = email;

                        if (data.is_activated) {
                            this.isActivated = true;
                            this.successMessage = 'Ваш акаунт вже активовано.';
                            this.updateState({ isActivated: true });
                        } else {
                            this.isActivated = false;
                            this.successMessage = 'Ви вже зареєстровані, але не активовані.';
                        }
                    }
                } catch (e) {
                    console.error('Check status error:', e);
                }
            },

            onEmailBlur() {
                if (this.activeFormKey === 'register_park' && this.formData.owner_email) {
                    this.checkUserStatus(this.formData.owner_email);
                }
            },

            loadRecaptchaV3(siteKey) {
                if (!this.recaptchaEnabled) return;
                if (document.getElementById('recaptcha-script-modal')) return;
                if (document.getElementById('recaptcha-script')) return;

                const script = document.createElement('script');
                script.id = 'recaptcha-script-modal';
                script.src = `https://www.google.com/recaptcha/api.js?render=${siteKey}`;
                document.head.appendChild(script);
            },

            waitForRecaptcha() {
                if (!this.recaptchaEnabled) return;
                let attempts = 0;
                const check = () => {
                    if (typeof grecaptcha !== 'undefined' && grecaptcha.ready) {
                        // Ready
                    } else if (attempts < 20) {
                        attempts++;
                        setTimeout(check, 500);
                    }
                };
                check();
            },

            updateActiveForm() {
                if (this.orderType === 'enterprise') {
                    this.activeFormKey = 'lead';
                } else {
                    this.activeFormKey = 'register_park';
                }
            },

            initFormData() {
                const fields = this.forms[this.activeFormKey].fields;
                fields.forEach(field => {
                    if (field.default !== undefined) {
                        this.formData[field.name] = field.default;
                    } else {
                        if (field.name === 'plan' && this.activeFormKey === 'register_park') {
                            this.formData[field.name] = this.orderType;
                        }
                        else if (field.name === 'type' && this.activeFormKey === 'lead' && this.orderType === 'enterprise') {
                            this.formData[field.name] = 'enterprise';
                        }
                        else {
                            this.formData[field.name] = '';
                        }
                    }
                });
                this.formData.agreement = true;
            },

            applyConfig(data) {
                if (data.plans) this.plans = data.plans;

                if (this.recaptchaEnabled) {
                    if (data.recaptcha_site_key) {
                        this.siteKey = data.recaptcha_site_key;
                        this.loadRecaptchaV3(this.siteKey);
                    } else if (this.siteKey && this.siteKey !== 'YOUR_V3_SITE_KEY') {
                        this.loadRecaptchaV3(this.siteKey);
                    }
                }

                if (data.forms) this.forms = data.forms;
            },

            fetchConfig() {
                fetch('/api/config')
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        this.applyConfig(data);
                    })
                    .catch(err => console.error('Failed to load config:', err));
            },

            async getRecaptchaToken(action = 'register_park') {
                if (!this.recaptchaEnabled) return '';
                if (!this.siteKey || this.siteKey === 'YOUR_V3_SITE_KEY') return '';

                return new Promise((resolve) => {
                    grecaptcha.ready(() => {
                        grecaptcha.execute(this.siteKey, {action: action}).then((token) => {
                            resolve(token);
                        });
                    });
                });
            },

            get modalTitle() {
                if (this.orderType === 'enterprise') return 'Індивідуальні умови';
                return 'Реєстрація парку';
            },

            get buttonText() {
                if (this.loading) return 'Обробка...';
                if (this.orderType === 'enterprise') return 'Замовити консультацію';
                return 'Створити акаунт';
            },

            get activeFields() {
                return this.forms[this.activeFormKey].fields;
            },

            async submitForm() {
                this.generalError = null;
                this.fieldErrors = {};

                if (!this.formData.agreement) {
                    this.generalError = 'Будь ласка, підтвердіть згоду з правилами.';
                    return;
                }

                let hasEmptyRequired = false;
                this.activeFields.forEach(field => {
                    if (field.required && !this.formData[field.name] && field.type !== 'hidden') {
                        this.fieldErrors[field.name] = 'Це поле обов\'язкове';
                        hasEmptyRequired = true;
                    }
                });

                if (hasEmptyRequired) return;

                this.loading = true;

                let action = this.activeFormKey === 'lead' ? 'lead_form' : 'register_park';
                let captchaToken = '';
                if (this.recaptchaEnabled) {
                    try {
                        captchaToken = await this.getRecaptchaToken(action);
                    } catch (e) {
                        console.error('Recaptcha error:', e);
                    }
                } else {
                    captchaToken = '';
                }

                let url = this.activeFormKey === 'lead' ? '/api/lead' : '/api/register';

                let payload = { ...this.formData };
                if (captchaToken) {
                    payload['g-recaptcha-response'] = captchaToken;
                }

                fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                })
                .then(async response => {
                    const data = await response.json();
                    this.loading = false;

                    if (response.ok || response.status === 409) {
                        this.success = true;
                        this.successMessage = data.message || 'Дякуємо! Ваша заявка прийнята.';

                        // DataLayer Event
                        window.dataLayer = window.dataLayer || [];
                        window.dataLayer.push({
                            'event': 'lead_generated',
                            'lead_type': this.activeFormKey === 'register_park' ? 'registration' : 'consultation',
                            'plan': this.formData.plan || null
                        });

                        if (this.activeFormKey === 'register_park') {
                            this.registeredEmail = this.formData.owner_email;

                            // Очищаємо чернетку
                            const state = this.getState();
                            delete state.draft;

                            if (data.is_activated) {
                                this.isActivated = true;
                                state.isActivated = true;
                            } else {
                                state.registration = {
                                    email: this.registeredEmail,
                                    timestamp: Date.now()
                                };
                            }
                            localStorage.setItem(this.storageKey, JSON.stringify(state));
                        }

                        this.formData = { agreement: true };
                    } else {
                        if (response.status === 422 && data.errors) {
                            const apiErrors = data.errors;
                            Object.keys(apiErrors).forEach(key => {
                                this.fieldErrors[key] = apiErrors[key][0];
                            });

                            const knownKeys = this.activeFields.map(f => f.name);
                            const unknownErrors = Object.keys(apiErrors).filter(key => !knownKeys.includes(key));

                            if (unknownErrors.length > 0) {
                                this.generalError = apiErrors[unknownErrors[0]][0];
                            }
                        } else {
                            this.generalError = data.message || 'Сталася помилка сервера.';
                        }
                    }
                })
                .catch(() => {
                    this.loading = false;
                    this.generalError = 'Сталася помилка мережі. Спробуйте пізніше.';
                });
            },

            async resendEmail() {
                if (this.resendTimer > 0 || !this.registeredEmail) return;

                this.resendLoading = true;

                let captchaToken = '';
                if (this.recaptchaEnabled) {
                    try {
                        captchaToken = await this.getRecaptchaToken('resend_activation');
                    } catch (e) {}
                } else {
                    captchaToken = '';
                }

                fetch('/api/resend', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        email: this.registeredEmail,
                        'g-recaptcha-response': captchaToken
                    })
                })
                .then(async response => {
                    const data = await response.json();
                    this.resendLoading = false;

                    if (response.ok || response.status === 409) {
                        if (data.is_activated) {
                            this.isActivated = true;
                            this.successMessage = data.message || 'Акаунт вже активовано.';
                            this.updateState({ isActivated: true });
                        } else {
                            this.successMessage = data.message || 'Лист відправлено повторно!';
                            this.startResendTimer(60);
                        }
                    } else {
                        this.successMessage = data.message || 'Помилка відправки.';
                        if (response.status === 429) {
                            this.startResendTimer(60);
                        }
                    }
                })
                .catch(() => {
                    this.resendLoading = false;
                    this.successMessage = 'Помилка мережі.';
                });
            },

            startResendTimer(seconds) {
                this.resendTimer = seconds;
                this.updateState({ lastResend: Date.now() });

                const timer = setInterval(() => {
                    this.resendTimer--;
                    if (this.resendTimer <= 0) {
                        clearInterval(timer);
                    }
                }, 1000);
            }
        }));
    });
</script>

<!-- Модальне вікно -->
<div x-data="orderForm"
     x-show="showModal"
     style="display: none;"
     class="modal-overlay">

    <div class="modal-backdrop" @click="showModal = false"></div>

    <div class="modal-content">
        <button class="modal-close" @click="showModal = false">&times;</button>

        <h2 class="modal-title" x-text="modalTitle"></h2>

        <div x-show="success" class="success-message">
            <div style="font-size: 3rem; margin-bottom: 1rem;">✅</div>
            <p x-text="successMessage"></p>
            <p class="text-sm text-gray-500 mt-2" x-show="!isActivated">Перевірте вашу пошту (включаючи папку Спам).</p>

            <!-- Кнопка повторної відправки (тільки для реєстрації і якщо НЕ активовано) -->
            <div x-show="activeFormKey === 'register_park' && !isActivated" class="mt-6">
                <button @click="resendEmail"
                        class="text-sm text-primary hover:underline disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="resendLoading || resendTimer > 0">
                    <span x-show="!resendLoading && resendTimer === 0">Надіслати лист ще раз</span>
                    <span x-show="resendLoading">Відправка...</span>
                    <span x-show="resendTimer > 0" x-text="'Зачекайте ' + resendTimer + 'с'"></span>
                </button>
            </div>

            <!-- Кнопка входу (якщо активовано) -->
            <div x-show="isActivated" class="mt-6">
                <a href="https://app.g24.com.ua/login" target="_blank" class="inline-block bg-primary text-white font-bold py-2 px-6 rounded-lg hover:bg-blue-700 transition no-underline">
                    Увійти в кабінет
                </a>
                <div class="mt-4 text-xs text-gray-400">
                    Забули пароль? <a href="https://app.g24.com.ua/password/reset" target="_blank" class="text-primary hover:underline">Відновити доступ</a>
                </div>
            </div>

            <!-- Допомога (показується завжди при успіху) -->
            <div class="mt-8 pt-6 border-t border-gray-100 text-xs text-gray-400">
                Якщо виникли проблеми з входом або реєстрацією —
                <a href="/contacts" class="text-primary hover:underline">напишіть нам</a>.
            </div>
        </div>

        <!-- Додано novalidate -->
        <form x-show="!success" @submit.prevent="submitForm" novalidate>
            <!-- Загальна помилка -->
            <div x-show="generalError" class="error-message" x-text="generalError"></div>

            <!-- Вибір типу оплати (тільки якщо не Enterprise) -->
            <div x-show="orderType !== 'enterprise'" class="payment-type-selector">
                <label class="radio-label" :class="{ 'checked': orderType === 'monthly' }">
                    <input type="radio" name="orderType" value="monthly" x-model="orderType">
                    <div class="radio-content">
                        <span class="radio-title">Щомісячна оплата</span>
                        <span class="radio-desc">Оплата по факту в кінці місяця</span>
                    </div>
                </label>
                <label class="radio-label" :class="{ 'checked': orderType === 'yearly' }">
                    <input type="radio" name="orderType" value="yearly" x-model="orderType">
                    <div class="radio-content">
                        <span class="radio-title">Річна передплата</span>
                        <span class="radio-desc badge-green">-100 грн/авто знижка</span>
                    </div>
                </label>
            </div>

            <!-- Підказка про зміну тарифу -->
            <div x-show="orderType !== 'enterprise'" class="text-xs text-gray-400 mb-4 text-center">
                Ви зможете змінити тарифний план у будь-який момент в особистому кабінеті.
            </div>

            <!-- Динамічні поля -->
            <template x-for="field in activeFields" :key="field.name">
                <div class="form-group" x-show="field.type !== 'hidden'">
                    <label>
                        <span x-text="field.label"></span>
                        <span x-show="field.required" class="text-red-500">*</span>
                    </label>

                    <!-- Text Input -->
                    <template x-if="['text', 'email', 'tel'].includes(field.type)">
                        <input :type="field.type"
                               x-model="formData[field.name]"
                               :placeholder="field.placeholder"
                               :class="{'border-red-500': fieldErrors[field.name]}"
                               @blur="field.name === 'owner_email' ? onEmailBlur() : null">
                    </template>

                    <!-- Textarea -->
                    <template x-if="field.type === 'textarea'">
                        <textarea x-model="formData[field.name]"
                                  rows="3"
                                  :placeholder="field.placeholder"
                                  class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                  :class="{'border-red-500': fieldErrors[field.name]}"></textarea>
                    </template>

                    <!-- Помилка поля -->
                    <div x-show="fieldErrors[field.name]" x-text="fieldErrors[field.name]" class="text-red-500 text-xs mt-1"></div>
                </div>
            </template>

            <div class="form-group checkbox-group">
                <label class="checkbox-label">
                    <input type="checkbox" x-model="formData.agreement" required>
                    <span>
                        Я погоджуюсь з
                        <a href="#" @click.prevent="$dispatch('open-text-modal', { title: 'Політика конфіденційності', slug: 'privacy' })">Політикою конфіденційності</a>
                        та
                        <a href="#" @click.prevent="$dispatch('open-text-modal', { title: 'Публічна оферта', slug: 'offer' })">Публічною офертою</a>
                    </span>
                </label>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%" :disabled="loading || !formData.agreement" x-text="buttonText"></button>

            @if($recaptchaEnabled)
            <div class="text-center text-xs text-gray-400 mt-2">
                This site is protected by reCAPTCHA and the Google
                <a href="https://policies.google.com/privacy" class="underline">Privacy Policy</a> and
                <a href="https://policies.google.com/terms" class="underline">Terms of Service</a> apply.
            </div>
            @endif
        </form>
    </div>
</div>

<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow-y: auto;
        padding: 1rem;
    }

    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
    }

    .modal-content {
        background: white;
        padding: 2.5rem;
        border-radius: 1rem;
        width: 90%;
        max-width: 500px;
        position: relative;
        z-index: 1001;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        animation: modalSlideIn 0.3s ease-out;
        margin: auto;
    }

    @keyframes modalSlideIn {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .modal-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: none;
        border: none;
        font-size: 2rem;
        line-height: 1;
        cursor: pointer;
        color: #9ca3af;
    }

    .modal-title {
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        text-align: center;
        color: var(--secondary-color);
        font-weight: 700;
    }

    /* Payment Type Selector */
    .payment-type-selector {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .radio-label {
        display: flex;
        align-items: center;
        padding: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .radio-label:hover {
        border-color: var(--primary-color);
        background-color: #f9fafb;
    }

    .radio-label.checked {
        border-color: var(--primary-color);
        background-color: #eff6ff;
        box-shadow: 0 0 0 1px var(--primary-color);
    }

    .radio-label input {
        margin-right: 1rem;
        accent-color: var(--primary-color);
        width: 1.2rem;
        height: 1.2rem;
    }

    .radio-content {
        display: flex;
        flex-direction: column;
    }

    .radio-title {
        font-weight: 600;
        color: var(--secondary-color);
        font-size: 0.95rem;
    }

    .radio-desc {
        font-size: 0.85rem;
        color: #6b7280;
    }

    .badge-green {
        color: #059669;
        font-weight: 500;
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.4rem;
        font-size: 0.9rem;
        font-weight: 500;
        color: #374151;
    }

    .form-group input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 1rem;
        transition: border-color 0.2s;
    }

    .form-group input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .success-message {
        text-align: center;
        padding: 2rem 0;
        color: #065f46;
    }

    .error-message {
        background: #fee2e2;
        color: #991b1b;
        padding: 0.75rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        text-align: center;
        font-size: 0.9rem;
    }

    .checkbox-group {
        margin-top: 1rem;
        margin-bottom: 1.5rem;
    }

    .checkbox-label {
        display: flex !important;
        align-items: flex-start;
        font-weight: 400 !important;
        font-size: 0.85rem;
        color: #4b5563;
        cursor: pointer;
    }

    .checkbox-label input {
        width: auto !important;
        margin-right: 0.75rem;
        margin-top: 0.2rem;
    }

    .checkbox-label a {
        color: var(--primary-color);
        text-decoration: underline;
    }

    button:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    /* Приховуємо бейдж рекапчі */
    .grecaptcha-badge { visibility: hidden; }
</style>