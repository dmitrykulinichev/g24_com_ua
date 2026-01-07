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
        .pricing-header {
            text-align: center;
            padding: 4rem 2rem;
            background-color: var(--secondary-color);
            color: var(--white);
        }
        .pricing-header h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .pricing-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Trial Badge */
        .trial-badge {
            display: inline-block;
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #60a5fa; /* Світло-блакитний */
        }

        .pricing-container {
            max-width: 1000px;
            margin: -3rem auto 4rem;
            padding: 0 2rem;
            position: relative;
            z-index: 10;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .plan-card {
            background: var(--white);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: relative;
            transition: transform 0.3s;
        }

        .plan-card:hover {
            transform: translateY(-5px);
        }

        .plan-card.popular {
            border: 2px solid var(--primary-color);
            transform: scale(1.05);
            z-index: 2;
        }

        .popular-badge {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background-color: var(--primary-color);
            color: white;
            padding: 0.25rem 1rem;
            border-radius: 1rem;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .plan-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .plan-desc {
            text-align: center;
            color: var(--gray);
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
            min-height: 2.5em;
        }

        .plan-price {
            text-align: center;
            margin-bottom: 2rem;
        }

        .price-amount {
            font-size: 3rem;
            font-weight: 800;
            color: var(--secondary-color);
        }

        .price-currency {
            font-size: 1.5rem;
            vertical-align: top;
        }

        .price-period {
            color: var(--gray);
            font-size: 0.9rem;
        }

        .plan-features {
            list-style: none;
            margin-bottom: 2rem;
            flex-grow: 1;
        }

        .plan-features li {
            margin-bottom: 0.75rem;
            padding-left: 1.5rem;
            position: relative;
            color: var(--text-color);
            font-size: 0.95rem;
        }

        .plan-features li::before {
            content: "✓";
            color: var(--accent-green);
            position: absolute;
            left: 0;
            font-weight: bold;
        }

        .plan-btn {
            display: block;
            text-align: center;
            padding: 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.2s;
            cursor: pointer;
            border: none;
            width: 100%;
            font-size: 1rem;
        }

        .plan-btn.primary {
            background-color: var(--primary-color);
            color: white;
        }
        .plan-btn.primary:hover {
            background-color: var(--primary-hover);
        }

        .plan-btn.outline {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
        }
        .plan-btn.outline:hover {
            background-color: #eff6ff;
        }

        /* Toggle Switch */
        .billing-toggle {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 1rem;
            color: var(--white);
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 26px;
            margin: 0 1rem;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255,255,255,0.3);
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: var(--primary-color);
        }

        input:checked + .slider:before {
            transform: translateX(24px);
        }

        .save-badge {
            background-color: var(--accent-green);
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            margin-left: 0.5rem;
        }

        @media (max-width: 768px) {
            .plan-card.popular {
                transform: none;
            }
            .pricing-container {
                margin-top: 2rem;
            }
        }
    </style>
</head>
<body>
    @include('partials.header')

    <div x-data="{ yearly: false }">
        <div class="pricing-header">
            <h1>Тарифні плани</h1>
            <p>Оберіть рішення, яке найкраще підходить для вашого бізнесу.</p>

            <div class="trial-badge">
                🎁 14 днів безкоштовного доступу до будь-якого тарифу
            </div>

            <div class="billing-toggle" style="margin-top: 2rem;">
                <span :class="{ 'font-bold': !yearly }">Щомісяця</span>
                <label class="toggle-switch">
                    <input type="checkbox" x-model="yearly">
                    <span class="slider"></span>
                </label>
                <span :class="{ 'font-bold': yearly }">Щорічно <span class="save-badge">-20%</span></span>
            </div>
        </div>

        <div class="pricing-container">
            <div class="pricing-grid">
                @foreach($plans as $plan)
                    <div class="plan-card {{ $plan['is_popular'] ? 'popular' : '' }}">
                        @if($plan['is_popular'])
                            <div class="popular-badge">Найпопулярніший</div>
                        @endif

                        <div class="plan-name">{{ $plan['name'] }}</div>
                        <div class="plan-desc">{{ $plan['description'] }}</div>

                        <div class="plan-price">
                            <span class="price-currency">{{ $plan['currency'] }}</span>
                            <!-- Ціна змінюється залежно від перемикача -->
                            <span class="price-amount" x-text="yearly ? {{ round($plan['price_yearly'] / 12) }} : {{ $plan['price_monthly'] }}">
                                {{ $plan['price_monthly'] }}
                            </span>
                            <span class="price-period">/ міс</span>

                            <div x-show="yearly" style="font-size: 0.85rem; color: var(--accent-green); margin-top: 0.5rem;">
                                Сплачується {{ $plan['price_yearly'] }} {{ $plan['currency'] }} / рік
                            </div>
                        </div>

                        <ul class="plan-features">
                            @foreach($plan['features'] as $feature)
                                <li>{{ $feature }}</li>
                            @endforeach
                        </ul>

                        <!-- Кнопка відкриває модалку -->
                        <button @click="$dispatch('open-order-modal', { plan: '{{ $plan['name'] }}' })" class="plan-btn {{ $plan['is_popular'] ? 'primary' : 'outline' }}">
                            {{ $plan['button_text'] }}
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>