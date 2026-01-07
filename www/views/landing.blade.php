<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title та Meta тепер у header.blade.php -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;800&display=swap" rel="stylesheet">
</head>
<body>
    @include('partials.header')

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="container">
                <h1>CRM-система для таксопарків<br>та логістичних компаній</h1>
                <p>Автоматизуйте виплати, контроль палива та роботу з водіями. Підключайтеся зараз і переходьте на новий рівень ефективності.</p>
                <a href="#contact" class="btn-primary">Підключитися</a>
            </div>
        </section>

        <!-- Features Grid -->
        <section id="features">
            <div class="container">
                <div class="section-title">
                    <h2>Які завдання ми вирішуємо?</h2>
                    <p>Повний цикл управління автопарком в одній системі</p>
                </div>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">💸</div>
                        <h3>Миттєві виплати</h3>
                        <p>Автоматичні виплати водіям на карту 24/7 через мобільний додаток.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">📊</div>
                        <h3>Звітність</h3>
                        <p>Генерація детальних звітів по прибутку, витратам та ефективності авто в один клік.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">⛽</div>
                        <h3>Контроль палива</h3>
                        <p>Інтеграція з датчиками рівня палива. Виявлення зливів та недоливів.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">📱</div>
                        <h3>Додаток для водія</h3>
                        <p>Власний кабінет водія: баланс, рейтинг, заправки та зв'язок з диспетчером.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">📑</div>
                        <h3>Електронний документообіг</h3>
                        <p>Автоматичне формування договорів, актів та рахунків.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🛠️</div>
                        <h3>Обслуговування (ТО)</h3>
                        <p>Нагадування про заміну мастила, страховку та техогляд.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Products -->
        <section id="products" class="products-section">
            <div class="container">
                <div class="section-title">
                    <h2>Що ми пропонуємо</h2>
                </div>
                <div class="products-grid">
                    <div class="product-card">
                        <h3>🚗 Для власних парків</h3>
                        <p>Рішення для компаній, що здають авто в оренду або мають власний штат водіїв.</p>
                        <ul>
                            <li>Контроль оренди та заборгованості</li>
                            <li>GPS-трекінг та геозони</li>
                            <li>Облік ремонтів та запчастин</li>
                            <li>Скоринг водіїв (стиль водіння)</li>
                        </ul>
                        <a href="#contact" class="btn-primary">Дізнатися більше</a>
                    </div>
                    <div class="product-card">
                        <h3>🤝 Для партнерських парків</h3>
                        <p>CRM для агрегаторів таксі та диспетчерських служб.</p>
                        <ul>
                            <li>Масові виплати водіям</li>
                            <li>Реферальна система</li>
                            <li>Інтеграція з агрегаторами (Bolt, Uklon, Uber)</li>
                            <li>Автоматична бухгалтерія</li>
                        </ul>
                        <a href="#contact" class="btn-primary">Дізнатися більше</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats -->
        <section class="stats-section">
            <div class="container">
                <div class="stats-grid">
                    <div>
                        <div class="stat-number">5+</div>
                        <div class="stat-label">Років на ринку</div>
                    </div>
                    <div>
                        <div class="stat-number">120</div>
                        <div class="stat-label">Підключених парків</div>
                    </div>
                    <div>
                        <div class="stat-number">15k</div>
                        <div class="stat-label">Активних водіїв</div>
                    </div>
                    <div>
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Технічна підтримка</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How to start -->
        <section>
            <div class="container">
                <div class="section-title">
                    <h2>Як почати працювати?</h2>
                </div>
                <div class="steps-grid">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h3>Залиште заявку</h3>
                        <p>Заповніть форму нижче або зателефонуйте нам.</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h3>Тестовий доступ</h3>
                        <p>Отримайте безкоштовний доступ на 14 днів.</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h3>Налаштування</h3>
                        <p>Ми допоможемо імпортувати ваші дані та налаштувати трекери.</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <h3>Робота</h3>
                        <p>Насолоджуйтесь автоматизацією та зростанням прибутку.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Form -->
        <section id="contact" class="contact-section">
            <div class="container">
                <div class="section-title">
                    <h2>Залишилися запитання?</h2>
                    <p>Залиште заявку і ми зв'яжемося з вами протягом 15 хвилин.</p>
                </div>
                <form class="contact-form">
                    <div class="form-group">
                        <label>Ваше ім'я</label>
                        <input type="text" placeholder="Введіть ваше ім'я">
                    </div>
                    <div class="form-group">
                        <label>Назва компанії / таксопарку</label>
                        <input type="text" placeholder="Назва вашого бізнесу">
                    </div>
                    <div class="form-group">
                        <label>Номер телефону</label>
                        <input type="tel" placeholder="+380 ...">
                    </div>
                    <button type="submit" class="btn-primary" style="width: 100%">Відправити заявку</button>
                </form>
            </div>
        </section>
    </main>

    @include('partials.footer')
</body>
</html>