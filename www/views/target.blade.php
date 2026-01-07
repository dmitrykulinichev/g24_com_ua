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
        .target-header {
            text-align: center;
            padding: 4rem 2rem;
            background-color: var(--secondary-color);
            color: var(--white);
        }
        .target-header h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .target-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        .target-container {
            max-width: 1000px;
            margin: -3rem auto 4rem;
            padding: 0 2rem;
            position: relative;
            z-index: 10;
        }

        .persona-card {
            background: var(--white);
            border-radius: 1rem;
            padding: 2.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
            margin-bottom: 2rem;
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
            align-items: center;
        }

        .persona-icon {
            font-size: 4rem;
            text-align: center;
            background: #eff6ff;
            padding: 2rem;
            border-radius: 1rem;
            color: var(--primary-color);
        }

        .persona-content h3 {
            font-size: 1.5rem;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }

        .persona-pain {
            background: #fff1f2;
            border-left: 4px solid #ef4444;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0 0.5rem 0.5rem 0;
            font-size: 0.95rem;
            color: #7f1d1d;
        }

        .persona-solution {
            background: #ecfdf5;
            border-left: 4px solid #10b981;
            padding: 1rem;
            border-radius: 0 0.5rem 0.5rem 0;
            font-size: 0.95rem;
            color: #064e3b;
        }

        /* Reviews Section (Перенесено з головної) */
        .reviews-section {
            padding: 4rem 0;
        }

        .reviews-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .review-card {
            background: #f9fafb;
            padding: 2rem;
            border-radius: 1rem;
            border: 1px solid var(--border-color);
        }

        .review-text {
            font-style: italic;
            color: #4b5563;
            margin-bottom: 1.5rem;
            font-size: 1.05rem;
        }

        .review-author {
            display: flex;
            align-items: center;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            margin-right: 1rem;
        }

        .author-info h4 {
            color: var(--secondary-color);
            font-size: 1rem;
            margin-bottom: 0.1rem;
        }

        .author-info p {
            color: #6b7280;
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            .persona-card {
                grid-template-columns: 1fr;
                text-align: center;
            }
            .persona-pain, .persona-solution {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    @include('partials.header')

    <div class="target-header">
        <h1>Кому підійде Garage24?</h1>
        <p>Ми розробили систему, враховуючи специфіку різних моделей управління автопарком.</p>
    </div>

    <div class="target-container">

        <!-- 1. Партнери -->
        <div class="persona-card">
            <div class="persona-icon">🤝</div>
            <div class="persona-content">
                <h3>Партнери-власники</h3>
                <p>Ви керуєте бізнесом удвох або утрьох. Один відповідає за фінанси, інший — за технічний стан.</p>
                <div class="persona-pain">
                    <strong>Біль:</strong> Постійні суперечки "де гроші?". Один бачить прибуток на карті, інший — витрати на СТО. Немає єдиної картини.
                </div>
                <div class="persona-solution">
                    <strong>Рішення:</strong> Єдина база даних. Обидва партнери бачать кожну транзакцію, кожен ремонт і реальний баланс. Повна прозорість.
                </div>
            </div>
        </div>

        <!-- 2. Власник-Операціоніст -->
        <div class="persona-card">
            <div class="persona-icon">🤯</div>
            <div class="persona-content">
                <h3>Власник, який "живе в гаражі"</h3>
                <p>Ви самі шукаєте водіїв, контролюєте ремонти і видаєте зарплату. Ваш телефон дзвонить кожні 5 хвилин.</p>
                <div class="persona-pain">
                    <strong>Біль:</strong> Ви — вузьке місце бізнесу. Якщо ви захворієте або поїдете у відпустку, все зупиниться.
                </div>
                <div class="persona-solution">
                    <strong>Рішення:</strong> Мобільний офіс. Водії та механіки взаємодіють з системою, а не дзвонять вам. Ви керуєте процесами, а не гасите пожежі.
                </div>
            </div>
        </div>

        <!-- 3. Інвестор -->
        <div class="persona-card">
            <div class="persona-icon">💼</div>
            <div class="persona-content">
                <h3>Власник-Інвестор</h3>
                <p>Ви купили машини і найняли керуючого (диспетчера). Ви хочете отримувати пасивний дохід.</p>
                <div class="persona-pain">
                    <strong>Біль:</strong> Недовіра. Чи всі поїздки враховані? Чи не завищені ціни на запчастини? Чи не використовують авто "наліво"?
                </div>
                <div class="persona-solution">
                    <strong>Рішення:</strong> Тотальний контроль. Інтеграція з Uklon показує реальні поїздки. Журнал дій (Audit Log) показує, хто і що змінював у системі.
                </div>
            </div>
        </div>

        <!-- 4. Масштабування -->
        <div class="persona-card">
            <div class="persona-icon">📈</div>
            <div class="persona-content">
                <h3>Парк, що росте (10+ авто)</h3>
                <p>Раніше ви справлялися за допомогою Excel та блокнота. Але машин стало більше, і система зламалась.</p>
                <div class="persona-pain">
                    <strong>Біль:</strong> Хаос. Загублені документи, прострочені ТО, плутанина з графіками. Excel більше не працює.
                </div>
                <div class="persona-solution">
                    <strong>Рішення:</strong> Системність. Garage24 автоматизує рутину (нагадування, графіки, звіти), дозволяючи вам рости далі без збільшення штату менеджерів.
                </div>
            </div>
        </div>

        <!-- Відгуки (Перенесено сюди) -->
        <div class="reviews-section">
            <div class="section-title">
                <h2>Що кажуть наші клієнти</h2>
                <p>Реальні історії тих, хто вже навів порядок.</p>
            </div>

            <div class="reviews-grid">
                <div class="review-card">
                    <div class="review-text">
                        "Ми з партнером постійно сперечалися про гроші. Він казав, що ми в плюсі, а я бачив тільки витрати на ремонти. Garage24 показав реальну картину. Тепер ми бачимо прибутковість кожної машини до копійки."
                    </div>
                    <div class="review-author">
                        <div class="author-avatar">Д</div>
                        <div class="author-info">
                            <h4>Дмитро</h4>
                            <p>Співвласник (18 авто)</p>
                        </div>
                    </div>
                </div>

                <div class="review-card">
                    <div class="review-text">
                        "Раніше мій телефон дзвонив кожні 5 хвилин: 'Де машина?', 'Хто на зміні?'. Тепер я просто відкриваю сайт на телефоні і бачу все сам. Дзвінки припинилися, я нарешті можу займатися розвитком."
                    </div>
                    <div class="review-author">
                        <div class="author-avatar">О</div>
                        <div class="author-info">
                            <h4>Олександр</h4>
                            <p>Власник парку (25 авто)</p>
                        </div>
                    </div>
                </div>

                <div class="review-card">
                    <div class="review-text">
                        "Найбільше часу забирало перенесення поїздок з кабінету Uklon в нашу таблицю. Це були години ручної роботи. Тепер все залітає автоматично. Я навіть не заходжу в кабінет партнера."
                    </div>
                    <div class="review-author">
                        <div class="author-avatar">А</div>
                        <div class="author-info">
                            <h4>Андрій</h4>
                            <p>Власник (12 авто)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 2rem;">
            <a href="/pricing" class="btn-primary" style="padding: 1rem 3rem; font-size: 1.1rem;">Спробувати безкоштовно</a>
        </div>

    </div>

    @include('partials.footer')
</body>
</html>