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

        .contacts-card {
            background: var(--white);
            border-radius: 1rem;
            padding: 3rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border: 1px solid var(--border-color);
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
        }

        .contact-info h3 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: var(--secondary-color);
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .info-icon {
            font-size: 1.5rem;
            margin-right: 1rem;
            color: var(--primary-color);
        }

        .info-content h4 {
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
            color: var(--secondary-color);
        }

        .info-content p, .info-content a {
            color: var(--text-color);
            text-decoration: none;
            line-height: 1.5;
        }

        .info-content a:hover {
            color: var(--primary-color);
        }

        .map-container {
            border-radius: 0.5rem;
            overflow: hidden;
            height: 100%;
            min-height: 300px;
            background-color: #f3f4f6;
        }

        .map-container iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }

        .cta-section {
            text-align: center;
            margin-top: 3rem;
        }

        @media (max-width: 768px) {
            .contacts-card {
                grid-template-columns: 1fr;
                padding: 2rem;
            }
            .map-container {
                min-height: 250px;
            }
        }
    </style>
</head>
<body>
    @include('partials.header')

    <div class="contacts-header">
        <h1>Контакти</h1>
        <p>Ми завжди раді допомогти вам. Зв'яжіться з нами будь-яким зручним способом.</p>
    </div>

    <div class="contacts-container">
        <div class="contacts-card">
            <div class="contact-info">
                <h3>Наші координати</h3>

                <div class="info-item">
                    <div class="info-icon">📍</div>
                    <div class="info-content">
                        <h4>Адреса</h4>
                        <p>Україна, м. Київ<br>вул. Хрещатик, 1</p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">📞</div>
                    <div class="info-content">
                        <h4>Телефон</h4>
                        <p><a href="tel:+380000000000">+380 00 000 0000</a></p>
                        <p style="font-size: 0.9rem; color: var(--gray);">Пн-Пт: 9:00 - 18:00</p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">✉️</div>
                    <div class="info-content">
                        <h4>Email</h4>
                        <p><a href="mailto:info@g24.com.ua">info@g24.com.ua</a></p>
                        <p><a href="mailto:support@g24.com.ua">support@g24.com.ua</a></p>
                    </div>
                </div>

                <div class="cta-section">
                    <p style="margin-bottom: 1rem;">Маєте запитання?</p>
                    <button @click="$dispatch('open-order-modal', {})" class="btn-primary" style="width: 100%;">Написати нам</button>
                </div>
            </div>

            <div class="map-container">
                <!-- Google Maps Embed (Київ, центр) -->
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2540.517847563668!2d30.52086337689253!3d50.45005898737526!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40d4ce50f8b6e3c3%3A0xb528f13296e9298!2z0JzQsNC50LTQsNC9INCd0LXQt9Cw0LvQtdC20L3QvtGB0YLRliwg0JrQuNGX0LIsIDAyMDAw!5e0!3m2!1suk!2sua!4v1700000000000!5m2!1suk!2sua" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>