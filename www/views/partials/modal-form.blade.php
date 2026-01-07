<!-- Підключення Google reCAPTCHA -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<!-- Логіка Alpine.js винесена в скрипт -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('orderForm', () => ({
            showModal: false,
            selectedPlan: '',
            formData: { name: '', email: '', company: '', phone: '', plan: '', agreement: true },
            loading: false,
            success: false,
            error: null,
            captchaWidgetId: null,

            init() {
                window.addEventListener('open-order-modal', (event) => {
                    this.showModal = true;
                    this.selectedPlan = event.detail.plan || '';
                    this.formData.plan = this.selectedPlan;
                    this.success = false;
                    this.error = null;
                    this.formData.agreement = true;

                    setTimeout(() => {
                        this.renderCaptcha();
                    }, 100);
                });
            },

            renderCaptcha() {
                const container = document.getElementById('recaptcha-container');
                if (container && !container.hasChildNodes()) {
                    if (typeof grecaptcha !== 'undefined') {
                        try {
                            this.captchaWidgetId = grecaptcha.render('recaptcha-container', {
                                'sitekey': '{{ $_ENV['RECAPTCHA_SITE_KEY'] ?? 'YOUR_SITE_KEY' }}'
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

            submitForm() {
                if (!this.formData.agreement) {
                    this.error = 'Будь ласка, підтвердіть згоду з правилами.';
                    return;
                }

                let captchaToken = '';
                if (typeof grecaptcha !== 'undefined') {
                    try {
                        captchaToken = grecaptcha.getResponse(this.captchaWidgetId);
                    } catch (e) {}

                    // Якщо ключ не заданий (локалка), пропускаємо перевірку токена на клієнті
                    // Але якщо ключ є, то вимагаємо токен
                    @if(($_ENV['RECAPTCHA_SITE_KEY'] ?? '') !== '' && ($_ENV['RECAPTCHA_SITE_KEY'] ?? '') !== 'YOUR_SITE_KEY')
                        if (!captchaToken) {
                            this.error = 'Будь ласка, пройдіть перевірку "Я не робот".';
                            return;
                        }
                    @endif
                }

                this.loading = true;
                this.error = null;

                let payload = { ...this.formData, 'g-recaptcha-response': captchaToken };

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
                        this.formData = { name: '', email: '', company: '', phone: '', plan: '', agreement: true };
                        if (typeof grecaptcha !== 'undefined') try { grecaptcha.reset(this.captchaWidgetId); } catch(e){}
                        setTimeout(() => { this.showModal = false; }, 3000);
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

        <h2 class="modal-title">
            <span x-show="!success">Заявка на підключення</span>
            <span x-show="success">Успішно!</span>
        </h2>

        <p class="modal-subtitle" x-show="!success && selectedPlan">
            Обраний тариф: <strong x-text="selectedPlan" style="color: var(--primary-color);"></strong>
        </p>

        <div x-show="success" class="success-message">
            <div style="font-size: 3rem; margin-bottom: 1rem;">✅</div>
            <p>Дякуємо! Ваша заявка прийнята.</p>
            <p>Ми зв'яжемося з вами найближчим часом.</p>
        </div>

        <form x-show="!success" @submit.prevent="submitForm">
            <div x-show="error" class="error-message" x-text="error"></div>

            <div class="form-group">
                <label>Ваше ім'я</label>
                <input type="text" x-model="formData.name" placeholder="Іван Іванов" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" x-model="formData.email" placeholder="email@example.com" required>
            </div>

            <div class="form-group">
                <label>Назва компанії</label>
                <input type="text" x-model="formData.company" placeholder="ТОВ Автопарк">
            </div>

            <div class="form-group">
                <label>Телефон</label>
                <input type="tel" x-model="formData.phone" placeholder="+380 ..." required>
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
                        <a href="#" @click.prevent="$dispatch('open-text-modal', { title: 'Угода користувача', slug: 'terms' })">Умовами використання</a>
                    </span>
                </label>
            </div>

            <input type="hidden" x-model="formData.plan">

            <button type="submit" class="btn-primary" style="width: 100%" :disabled="loading || !formData.agreement">
                <span x-show="!loading">Відправити заявку</span>
                <span x-show="loading">Відправка...</span>
            </button>
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
    }

    .modal-backdrop {
        position: absolute;
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
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        animation: modalSlideIn 0.3s ease-out;
        max-height: 90vh;
        overflow-y: auto;
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
        margin-bottom: 0.5rem;
        text-align: center;
        color: var(--secondary-color);
    }

    .modal-subtitle {
        text-align: center;
        margin-bottom: 1.5rem;
        color: #6b7280;
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
        font-size: 0.9rem;
        color: var(--text-color);
        cursor: pointer;
    }

    .checkbox-label input {
        width: auto !important;
        margin-right: 0.75rem;
        margin-top: 0.25rem;
        cursor: pointer;
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