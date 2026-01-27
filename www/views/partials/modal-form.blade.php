<!-- Підключення Google reCAPTCHA -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

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
            captchaWidgetId: null,
            plans: [],
            siteKey: '{{ $_ENV['RECAPTCHA_SITE_KEY'] ?? '' }}',

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
                // Спроба завантажити конфіг з глобальної змінної (якщо ми на сторінці Pricing)
                if (window.landingConfig) {
                    this.applyConfig(window.landingConfig);
                } else {
                    // Інакше вантажимо через API
                    this.fetchConfig();
                }

                window.addEventListener('open-order-modal', (event) => {
                    this.showModal = true;
                    this.orderType = event.detail.type || 'monthly';
                    this.updateActiveForm();

                    this.success = false;
                    this.generalError = null;
                    this.fieldErrors = {};
                    this.formData = { agreement: true };

                    this.initFormData();

                    setTimeout(() => {
                        this.renderCaptcha();
                    }, 100);
                });

                this.$watch('orderType', (value) => {
                    this.updateActiveForm();
                    if (this.activeFormKey === 'register_park') {
                        this.formData.plan = value;
                    }
                });
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
                        } else {
                            this.formData[field.name] = '';
                        }
                    }
                });
                this.formData.agreement = true;
            },

            applyConfig(data) {
                if (data.plans) this.plans = data.plans;
                if (data.recaptcha_site_key) this.siteKey = data.recaptcha_site_key;
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

            renderCaptcha() {
                const container = document.getElementById('recaptcha-container');
                if (!this.siteKey || this.siteKey === 'YOUR_SITE_KEY') return;

                if (container && !container.hasChildNodes()) {
                    if (typeof grecaptcha !== 'undefined') {
                        try {
                            this.captchaWidgetId = grecaptcha.render('recaptcha-container', {
                                'sitekey': this.siteKey
                            });
                        } catch (e) {
                            console.error('Captcha render error:', e);
                        }
                    }
                } else if (typeof grecaptcha !== 'undefined' && this.captchaWidgetId !== null) {
                    try {
                        grecaptcha.reset(this.captchaWidgetId);
                    } catch (e) {}
                }
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

            submitForm() {
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

                let captchaToken = '';
                if (this.siteKey && this.siteKey !== 'YOUR_SITE_KEY') {
                    if (typeof grecaptcha !== 'undefined') {
                        try {
                            captchaToken = grecaptcha.getResponse(this.captchaWidgetId);
                        } catch (e) {}

                        if (!captchaToken) {
                            this.generalError = 'Будь ласка, пройдіть перевірку "Я не робот".';
                            return;
                        }
                    }
                }

                this.loading = true;

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

                    if (response.ok) {
                        this.success = true;
                        this.successMessage = data.message || 'Дякуємо! Ваша заявка прийнята.';
                        this.formData = { agreement: true };
                        if (typeof grecaptcha !== 'undefined') try { grecaptcha.reset(this.captchaWidgetId); } catch(e){}
                        setTimeout(() => { this.showModal = false; }, 5000);
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
                        if (typeof grecaptcha !== 'undefined') try { grecaptcha.reset(this.captchaWidgetId); } catch(e){}
                    }
                })
                .catch(() => {
                    this.loading = false;
                    this.generalError = 'Сталася помилка мережі. Спробуйте пізніше.';
                });
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
            <p class="text-sm text-gray-500 mt-2">Перевірте вашу пошту.</p>
        </div>

        <form x-show="!success" @submit.prevent="submitForm">
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
                               :class="{'border-red-500': fieldErrors[field.name]}">
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

            <!-- Контейнер для reCAPTCHA -->
            <div class="form-group" style="display: flex; justify-content: center; margin-bottom: 1rem;">
                <div id="recaptcha-container"></div>
            </div>

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
        </form>
    </div>
</div>

<style>
    /* Додаємо стилі для червоної рамки помилки */
    .border-red-500 {
        border-color: #ef4444 !important;
    }
    .text-red-500 {
        color: #ef4444;
    }
    .text-xs {
        font-size: 0.75rem;
    }
    .mt-1 {
        margin-top: 0.25rem;
    }

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

    .form-group input, .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 1rem;
        transition: border-color 0.2s;
    }

    .form-group input:focus, .form-group textarea:focus {
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
</style>