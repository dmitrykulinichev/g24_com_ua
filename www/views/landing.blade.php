<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title та Meta тепер у header.blade.php -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    @include('partials.header')

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="container">
                <h1>Забудь про Excel.<br>Наведи порядок у графіках та авто.</h1>
                <p>Проста система для таксопарків, яка збирає всю інформацію в одному місці.<br>Без хаосу, без складних налаштувань, без втрати даних.</p>
                <a href="/pricing" class="btn-primary">Навести порядок</a>
            </div>
        </section>

        <!-- Story Section (Біль) -->
        <section class="story-section">
            <div class="container">
                <div class="section-title">
                    <h2>Впізнаєте себе?</h2>
                    <p>Типова ситуація, з якою стикається кожен автопарк при масштабуванні.</p>
                </div>

                <div class="story-grid">
                    <div class="story-content">
                        <h3>Бізнес росте, а контроль зникає</h3>
                        <p>Поки у вас було 5 машин, ви знали про кожну все. Але коли машин стало більше, почався хаос.</p>
                        <p>Ви не розумієте, хто, коли і на якому авто працював. Графіки в голові, в чатах або на папірцях. Коли приходить штраф — ви не знаєте, з кого його списати.</p>

                        <p><strong>Інформація розпорошена:</strong></p>
                        <ul style="list-style: none; margin-bottom: 1.5rem; color: var(--text-muted);">
                            <li style="margin-bottom: 0.5rem;">📉 <strong>Фінанси</strong> живуть в одній таблиці.</li>
                            <li style="margin-bottom: 0.5rem;">🔧 <strong>Ремонти</strong> — в чатах з механіками.</li>
                            <li style="margin-bottom: 0.5rem;">📅 <strong>Графіки</strong> — на папірцях або в голові диспетчера.</li>
                        </ul>

                        <p>Ви бачите рух грошей, але не бачите реального стану бізнесу.</p>

                        <div class="pain-points">
                            <div class="pain-point">
                                <span class="pain-icon">❌</span>
                                <span>Хто був за кермом?</span>
                            </div>
                            <div class="pain-point">
                                <span class="pain-icon">❌</span>
                                <span>Графік сиплеться</span>
                            </div>
                            <div class="pain-point">
                                <span class="pain-icon">❌</span>
                                <span>Загублені документи</span>
                            </div>
                            <div class="pain-point">
                                <span class="pain-icon">❌</span>
                                <span>Ремонти "пожежами"</span>
                            </div>
                        </div>
                    </div>
                    <div class="story-quote">
                        "Проблема не в людях і не в кількості роботи. Проблема в архітектурі управління. Excel просто не створений для цього."
                    </div>
                </div>
            </div>
        </section>

        <!-- Value Proposition -->
        <section style="background-color: var(--bg-surface); text-align: center; padding: 4rem 0; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);">
            <div class="container">
                <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Garage24 — це концентрація інформації в одному місці</h2>
                <p style="font-size: 1.2rem; max-width: 800px; margin: 0 auto;">Ми не ускладнюємо життя "корпоративними ERP". Ми даємо простий інструмент, щоб ви бачили все в одному місці.</p>
            </div>
        </section>

        <!-- Features Grid -->
        <section id="features" class="features-section">
            <div class="container">
                <div class="section-title">
                    <h2>Як ми наводимо порядок</h2>
                    <p>6 кроків до прозорого автопарку</p>
                </div>
                <div class="features-grid">
                    <!-- 1. Графік (Пріоритет №1) -->
                    <div class="feature-card">
                        <div class="feature-icon">📅</div>
                        <h3>Розумний графік</h3>
                        <p>Планування змін без конфліктів та накладок. Ви завжди знаєте, хто на зміні.</p>
                        <ul class="feature-list">
                            <li>Drag & Drop календар</li>
                            <li>Контроль "один водій на два авто"</li>
                            <li>Історія призначень (хто був за кермом)</li>
                        </ul>
                    </div>

                    <!-- 2. Водії -->
                    <div class="feature-card">
                        <div class="feature-icon">👥</div>
                        <h3>Водії та Дисципліна</h3>
                        <p>Повний контроль роботи персоналу. Від найму до виходу на лінію.</p>
                        <ul class="feature-list">
                            <li>Швидкий онбординг за посиланням</li>
                            <li>Telegram-бот для комунікації</li>
                            <li>Цифрове досьє та рейтинг</li>
                        </ul>
                    </div>

                    <!-- 3. Автопарк -->
                    <div class="feature-card">
                        <div class="feature-icon">🚗</div>
                        <h3>Повний контроль над авто</h3>
                        <p>Цифровий паспорт кожного автомобіля. Більше ніяких паперових журналів.</p>
                        <ul class="feature-list">
                            <li><strong>Всі документи в хмарі</strong> (доступ 24/7)</li>
                            <li>Історія пробігу та статусів</li>
                            <li>QR-коди для швидкого доступу</li>
                        </ul>
                    </div>

                    <!-- 4. Фінанси -->
                    <div class="feature-card">
                        <div class="feature-icon">💰</div>
                        <h3>Кожна гривня під контролем</h3>
                        <p>Фінансове ядро системи для обліку взаєморозрахунків.</p>
                        <ul class="feature-list">
                            <li>Облік доходів та витрат</li>
                            <li>Баланси водіїв (хто скільки винен)</li>
                            <li>Інтеграція з Uklon (авто-імпорт)</li>
                        </ul>
                    </div>

                    <!-- 5. ТО -->
                    <div class="feature-card">
                        <div class="feature-icon">🛠️</div>
                        <h3>Прозорі ремонти</h3>
                        <p>Ви знаєте, куди йдуть гроші на запчастини.</p>
                        <ul class="feature-list">
                            <li>Нагадування про заміну мастила</li>
                            <li>Водій повідомляє про проблему з телефону</li>
                            <li>Фото-фіксація чеків та актів</li>
                        </ul>
                    </div>

                    <!-- 6. Безпека -->
                    <div class="feature-card">
                        <div class="feature-icon">🔒</div>
                        <h3>Безпека та Аудит</h3>
                        <p>Ви завжди знаєте, хто і що змінив у системі.</p>
                        <ul class="feature-list">
                            <li>Повний журнал дій (Activity Logs)</li>
                            <li>Гнучкі права доступу (RBAC)</li>
                            <li>Захист від видалення "незручних" даних</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Integration Section (Uklon) -->
        <section style="background-color: var(--bg-card); border-top: 1px solid var(--border);">
            <div class="container">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 4rem; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 300px;">
                        <h2 style="font-size: 2.5rem; margin-bottom: 1.5rem;">Офіційна інтеграція з Uklon</h2>
                        <p style="font-size: 1.1rem; margin-bottom: 2rem;">Забудьте про ручне перенесення даних з кабінету партнера. Garage24 робить це автоматично.</p>

                        <ul style="list-style: none;">
                            <li style="margin-bottom: 1rem; display: flex; align-items: center; font-size: 1.1rem; color: var(--text-main);">
                                <span style="color: var(--accent-green); margin-right: 0.75rem; font-weight: bold;">✓</span>
                                Автоматичний імпорт поїздок та каси
                            </li>
                            <li style="margin-bottom: 1rem; display: flex; align-items: center; font-size: 1.1rem; color: var(--text-main);">
                                <span style="color: var(--accent-green); margin-right: 0.75rem; font-weight: bold;">✓</span>
                                Синхронізація бази водіїв та авто
                            </li>
                            <li style="margin-bottom: 1rem; display: flex; align-items: center; font-size: 1.1rem; color: var(--text-main);">
                                <span style="color: var(--accent-green); margin-right: 0.75rem; font-weight: bold;">✓</span>
                                Точний розрахунок зарплати та комісії
                            </li>
                        </ul>
                    </div>
                    <div style="flex: 1; min-width: 300px; text-align: center;">
                        <!-- Логотип Uklon (стилізований) -->
                        <div style="background: #ffce00; color: #000; font-weight: 900; font-size: 3rem; padding: 2rem 4rem; border-radius: 1rem; display: inline-block; transform: rotate(-3deg); box-shadow: var(--shadow-lg);">
                            UKLON
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Ecosystem Section (Mobile + Telegram) -->
        <section class="mobile-section">
            <div class="container">
                <div class="section-title" style="margin-bottom: 3rem;">
                    <h2 style="color: white;">Екосистема Garage24</h2>
                    <p style="color: rgba(255,255,255,0.8);">Два зручних інтерфейси для різних задач</p>
                </div>

                <div class="mobile-grid">
                    <!-- Ліва частина: Мобільний додаток (PWA) -->
                    <div class="mobile-content">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">📱</div>
                        <h2>Мобільний офіс</h2>
                        <p>Для власників та менеджерів. Повноцінна система у вашому смартфоні (веб-версія).</p>
                        <ul class="mobile-features" style="list-style: none;">
                            <li>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <strong>Документи завжди під рукою</strong> (навіть для поліції)
                            </li>
                            <li>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Пульс парку 24/7 (хто на лінії, хто в ремонті)
                            </li>
                            <li>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Швидке створення задач для механіків
                            </li>
                        </ul>
                    </div>

                    <!-- Права частина: Telegram Бот -->
                    <div class="mobile-content" style="background: rgba(255,255,255,0.1); padding: 2rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.2);">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">🤖</div>
                        <h2>Telegram Бот</h2>
                        <p>Для водіїв. Простий інтерфейс у звичному месенджері.</p>
                        <ul class="mobile-features" style="list-style: none;">
                            <li>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Перегляд графіку змін
                            </li>
                            <li>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Перевірка балансу та боргів
                            </li>
                            <li>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Відправка звітів про поломки (фото/відео)
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Simplicity Section (Простота) -->
        <section style="background-color: var(--bg-surface); padding: 6rem 0;">
            <div class="container">
                <div class="section-title">
                    <h2>Складно? Ні, це просто.</h2>
                    <p>Ми прибрали все зайве. Тільки те, що потрібно для роботи.</p>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; text-align: center;">
                    <div style="background: var(--bg-card); padding: 2rem; border-radius: 1rem; border: 1px solid var(--border);">
                        <div style="font-size: 2rem; margin-bottom: 1rem; color: var(--text-muted);">📊</div>
                        <h3 style="margin-bottom: 1rem; color: var(--text-muted);">Excel</h3>
                        <p style="color: var(--text-muted);">Просто, але хаотично. Дані губляться, формули ламаються, доступу з телефону немає.</p>
                    </div>

                    <div style="background: var(--bg-card); padding: 2rem; border-radius: 1rem; border: 2px solid var(--primary); transform: scale(1.05); box-shadow: var(--shadow-lg); z-index: 10;">
                        <div style="font-size: 2rem; margin-bottom: 1rem; color: var(--primary);">🚀</div>
                        <h3 style="margin-bottom: 1rem; color: var(--text-main); font-weight: 800;">Garage24</h3>
                        <p style="color: var(--text-main);">Золота середина. Простота Excel + потужність бази даних. Все працює з коробки.</p>
                    </div>

                    <div style="background: var(--bg-card); padding: 2rem; border-radius: 1rem; border: 1px solid var(--border);">
                        <div style="font-size: 2rem; margin-bottom: 1rem; color: var(--text-muted);">🏢</div>
                        <h3 style="margin-bottom: 1rem; color: var(--text-muted);">Складні ERP</h3>
                        <p style="color: var(--text-muted);">Дорого, довго впроваджувати, потрібен окремий спеціаліст для обслуговування.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats -->
        <section class="stats-section">
            <div class="container">
                <div class="stats-grid">
                    <div>
                        <div class="stat-number">10-100</div>
                        <div class="stat-label">Авто в парку</div>
                    </div>
                    <div>
                        <div class="stat-number">15%</div>
                        <div class="stat-label">Економія на ремонтах</div>
                    </div>
                    <div>
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Контроль бізнесу</div>
                    </div>
                    <div>
                        <div class="stat-number">0</div>
                        <div class="stat-label">Загублених транзакцій</div>
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
                        <p>Ми зв'яжемося з вами та проведемо коротку демонстрацію.</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h3>Налаштування</h3>
                        <p>Допоможемо імпортувати ваші авто та водіїв з Excel.</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h3>Навчання</h3>
                        <p>Покажемо вашим диспетчерам, як працювати в системі.</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <h3>Спокій</h3>
                        <p>Ви отримуєте контроль, а бізнес працює як годинник.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section id="contact" class="contact-section">
            <div class="container" style="text-align: center;">
                <div class="section-title">
                    <h2>Порядок коштує дешевше, ніж хаос</h2>
                    <p>Спробуйте Garage24 безкоштовно протягом 14 днів. Жодних зобов'язань.</p>
                </div>
                <a href="/pricing" class="btn-primary" style="font-size: 1.2rem; padding: 1rem 3rem;">Спробувати безкоштовно</a>
            </div>
        </section>
    </main>

    @include('partials.footer')
</body>
</html>