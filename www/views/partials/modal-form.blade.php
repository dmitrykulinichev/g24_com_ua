<!-- Підключення Google reCAPTCHA -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<!-- Логіка Alpine.js винесена в скрипт -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('orderForm', () => ({
            showModal: false,
            orderType: 'monthly', // monthly, yearly, enterprise
            formData: { name: '', email: '', company: '', phone: '', message: '', agreement: true },
            loading: false,
            success: false,
            successMessage: '',
            generalError: null,
            fieldErrors: {},
            captchaWidgetId: null,
            plans: [],
            siteKey: '{{ $_ENV['RECAPTCHA_SITE_KEY'] ?? '' }}', // Fallback ключ

            init() {
                this.fetchConfig();

                window.addEventListener('open-order-modal', (event) => {
                    this.showModal = true;
                    this.orderType = event.detail.type || 'monthly';
                    this.success = false;
                    this.generalError = null;
                    this.fieldErrors = {};
                    this.formData.agreement = true;
                    this.formData.message = '';

                    setTimeout(() => {
                        this.renderCaptcha();
                    }, 100);
                });
            },

            fetchConfig() {
                fetch('/api/config')
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        if (data.plans) {
                            this.plans = data.plans;
                        }
                        if (data.recaptcha_site_key) {
                            this.siteKey = data.recaptcha_site_key;
                        }
                    })
                    .catch(err => console.error('Failed to load config:', err));
            },

            renderCaptcha() {
                const container = document.getElementById('recaptcha-container');

                if (!this.siteKey || this.siteKey === 'YOUR_SITE_KEY') {
                    console.warn('Recaptcha Site Key not found');
                    return;
                }

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

            submitForm() {
                this.generalError = null;
                this.fieldErrors = {};

                if (!this.formData.agreement) {
                    this.generalError = 'Будь ласка, підтвердіть згоду з правилами.';
                    return;
                }

                // Валідація повідомлення для Enterprise
                if (this.orderType === 'enterprise' && (!this.formData.message || this.formData.message.length < 5)) {
                    this.fieldErrors.message = 'Будь ласка, напишіть коротко про ваші потреби.';
                    return;
                }

                // Валідація назви парку для Реєстрації
                if (this.orderType !== 'enterprise' && (!this.formData.company || this.formData.company.length < 2)) {
                    this.fieldErrors.company = 'Введіть назву парку.';
                    return;
                }

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

                let url = '/api/lead';
                let payload = {};

                if (this.orderType === 'enterprise') {
                    url = '/api/lead';
                    payload = {
                        ...this.formData,
                        plan: this.orderType,
                        'g-recaptcha-response': captchaToken
                    };
                } else {
                    url = '/api/register';
                    payload = {
                        park_name: this.formData.company,
                        owner_name: this.formData.name,
                        owner_email: this.formData.email,
                        phone: this.formData.phone,
                        plan: this.orderType,
                        'g-recaptcha-response': captchaToken
                    };
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
                        this.formData = { name: '', email: '', company: '', phone: '', message: '', agreement: true };
                        if (typeof grecaptcha !== 'undefined') try { grecaptcha.reset(this.captchaWidgetId); } catch(e){}
                        setTimeout(() => { this.showModal = false; }, 5000);
                    } else {
                        if (response.status === 422 && data.errors) {
                            const apiErrors = data.errors;
                            const mappedErrors = {};

                            // Мапінг для Реєстрації
                            if (apiErrors.park_name) mappedErrors.company = apiErrors.park_name[0];
                            if (apiErrors.owner_name) mappedErrors.name = apiErrors.owner_name[0];
                            if (apiErrors.owner_email) mappedErrors.email = apiErrors.owner_email[0];

                            // Мапінг для Лідів (Enterprise)
                            if (apiErrors.email) mappedErrors.email = apiErrors.email[0];
                            if (apiErrors.name) mappedErrors.name = apiErrors.name[0];
                            if (apiErrors.message) mappedErrors.message = apiErrors.message[0];

                            // Спільні поля
                            if (apiErrors.phone) mappedErrors.phone = apiErrors.phone[0];

                            const knownKeys = ['park_name', 'owner_name', 'owner_email', 'phone', 'email', 'name', 'message'];
                            const unknownErrors = Object.keys(apiErrors).filter(key => !knownKeys.includes(key));

                            if (unknownErrors.length > 0) {
                                this.generalError = apiErrors[unknownErrors[0]][0];
                            }

                            this.fieldErrors = mappedErrors;
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

            <!-- Вибір типу оплати -->
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

            <div class="form-group">
                <label>Ваше ім'я <span class="text-red-500">*</span></label>
                <input type="text" x-model="formData.name" placeholder="Іван Іванов" :class="{'border-red-500': fieldErrors.name}">
                <div x-show="fieldErrors.name" x-text="fieldErrors.name" class="text-red-500 text-xs mt-1"></div>
            </div>

            <div class="form-group">
                <label>Email (Логін) <span class="text-red-500">*</span></label>
                <input type="email" x-model="formData.email" placeholder="email@example.com" :class="{'border-red-500': fieldErrors.email}">
                <div x-show="fieldErrors.email" x-text="fieldErrors.email" class="text-red-500 text-xs mt-1"></div>
            </div>

            <!-- Назва парку: показуємо ТІЛЬКИ якщо це НЕ Enterprise -->
            <div class="form-group" x-show="orderType !== 'enterprise'">
                <label>Назва парку / Компанії <span class="text-red-500">*</span></label>
                <input type="text" x-model="formData.company" placeholder="ТОВ Автопарк" :class="{'border-red-500': fieldErrors.company}">
                <div x-show="fieldErrors.company" x-text="fieldErrors.company" class="text-red-500 text-xs mt-1"></div>
            </div>

            <div class="form-group">
                <label>Телефон <span class="text-red-500">*</span></label>
                <input type="tel" x-model="formData.phone" placeholder="+380 ..." :class="{'border-red-500': fieldErrors.phone}">
                <div x-show="fieldErrors.phone" x-text="fieldErrors.phone" class="text-red-500 text-xs mt-1"></div>
            </div>

            <!-- Поле повідомлення (Тільки для Enterprise) -->
            <div class="form-group" x-show="orderType === 'enterprise'">
                <label>Ваше повідомлення <span class="text-red-500">*</span></label>
                <textarea x-model="formData.message" rows="3" placeholder="Кількість авто, особливі побажання..."
                          class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                          :class="{'border-red-500': fieldErrors.message}"></textarea>
                <div x-show="fieldErrors.message" x-text="fieldErrors.message" class="text-red-500 text-xs mt-1"></div>
            </div>

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