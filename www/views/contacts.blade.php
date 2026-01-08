<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title та Meta тепер у header.blade.php -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;800&display=swap" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        .contacts-header {
            text-align: center;
            padding: 4rem 2rem;
            background-color: var(--secondary-color);
            color: var(--white);
        }
        .contacts-header h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .contacts-container {
            max-width: 1000px;
            margin: -3rem auto 4rem;
            padding: 0 2rem;
            position: relative;
            z-index: 10;
        }

        .contacts-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 2rem;
        }

        /* Картка для нових клієнтів (Форма) */
        .lead-card {
            background: var(--white);
            border-radius: 1rem;
            padding: 2.5rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border: 1px solid var(--border-color);
        }

        /* Картка для існуючих клієнтів */
        .client-card {
            background: #f8fafc;
            border-radius: 1rem;
            padding: 2rem;
            border: 1px solid var(--border-color);
            height: fit-content;
        }

        .client-card h3 {
            font-size: 1.25rem;
            color: var(--secondary-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .client-card p {
            font-size: 0.95rem;
            color: #4b5563;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--secondary-color);
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 1rem;
            font-family: inherit;
        }
        .form-group input:focus, .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        @media (max-width: 768px) {
            .contacts-grid {
                grid-template-columns: 1fr;
            }
            .contacts-grid > :first-child {
                order: 2;
            }
            .contacts-grid > :last-child {
                order: 1;
                margin-bottom: 2rem;
            }
        }
    </style>
</head>
<body>
    @include('partials.header')

    <div class="contacts-header">
        <h1>Зв'яжіться з нами</h1>
        <p>Оберіть зручний спосіб комунікації залежно від вашого запиту.</p>
    </div>

    <div class="contacts-container">
        <div class="contacts-grid">

            <!-- Блок для нових клієнтів (Форма) -->
            <div class="lead-card" x-data="{
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
                <h2 style="margin-bottom: 0.5rem; font-size: 1.75rem; color: var(--secondary-color);">Ще не з нами?</h2>
                <p style="margin-bottom: 2rem; color: #6b7280;">Заповніть форму, якщо у вас є питання щодо підключення, тарифів або можливостей системи.</p>

                <div x-show="success" style="background: #d1fae5; color: #065f46; padding: 1.5rem; border-radius: 0.5rem; text-align: center; margin-bottom: 1rem;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">✅</div>
                    <strong>Повідомлення відправлено!</strong><br>
                    Ми зв'яжемося з вами найближчим часом.
                </div>

                <div x-show="error" style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;" x-text="error"></div>

                <form x-show="!success" @submit.prevent="submitForm">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label>Ваше ім'я</label>
                            <input type="text" x-model="formData.name" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" x-model="formData.email" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Телефон</label>
                        <input type="tel" x-model="formData.phone" required>
                    </div>

                    <div class="form-group">
                        <label>Ваше питання</label>
                        <textarea x-model="formData.message" rows="4" required placeholder="Наприклад: Чи є інтеграція з Bolt?"></textarea>
                    </div>

                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <div id="contact-recaptcha"></div>
                    </div>

                    <button type="submit" class="btn-primary" :disabled="loading" style="width: 100%;">
                        <span x-show="!loading">Відправити запит</span>
                        <span x-show="loading">Відправка...</span>
                    </button>
                </form>
            </div>

            <!-- Блок для існуючих клієнтів -->
            <div class="client-card">
                <h3>
                    <span style="font-size: 1.5rem;">🔑</span>
                    Вже клієнт Garage24?
                </h3>
                <p>
                    Ця форма призначена для загальних питань.
                    Для швидкого вирішення технічних проблем, будь ласка, створіть тікет у вашому особистому кабінеті.
                </p>
                <p>
                    Там ми бачимо історію вашого парку і зможемо допомогти набагато швидше.
                </p>

                <a href="https://app.g24.com.ua/support" target="_blank" class="btn-primary" style="width: 100%; text-align: center; background-color: var(--white); color: var(--primary-color); border: 1px solid var(--primary-color);">
                    Перейти в гараж
                </a>

                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e5e7eb;">
                    <div style="font-weight: 600; color: var(--secondary-color); margin-bottom: 0.5rem;">Інші контакти:</div>
                    <div style="margin-bottom: 0.5rem;"><a href="mailto:support@g24.com.ua" style="color: var(--primary-color);">support@g24.com.ua</a></div>
                    <div style="color: #6b7280; font-size: 0.9rem;">Пн-Пт: 10:00 - 18:00</div>
                </div>
            </div>

        </div>
    </div>

    @include('partials.footer')
</body>
</html>