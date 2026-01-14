# Фінанси та Транзакції (Finance)
**Слоган:** "Кожна гривня під контролем."

Фінансове ядро системи для обліку взаєморозрахунків з водіями та операційних витрат парку.

{{screenshot file="finance_transactions_log.png" title="Transactions Log"}}

## Управління транзакціями

### TransactionFormModal
Універсальне вікно для створення фінансових операцій:
*   **Тип:** Дохід (Income) або Витрата (Expense).
*   **Категорія:** Вибір зі списку (Паливо, Ремонт, Оренда, Зарплата).
*   **Суб'єкт:** Прив'язка до Водія або Автомобіля.

{{screenshot file="finance_manual_trips.png" title="Manual Trips"}}

### Фільтрація та Пошук
Потужний `FiltersModal` дозволяє знайти будь-яку транзакцію за:
*   Датою (періодом).
*   Сумою.
*   Описом.
*   Категорією.

## Баланси водіїв
Система автоматично розраховує баланс кожного водія на основі всіх його транзакцій (поїздки Uklon + ручні нарахування - списання).

{{screenshot file="finance_driver_payroll.png" title="Driver Payroll"}}

{{screenshot file="finance_payment_schemes.png" title="Payment Schemes"}}

## Ефективність авто

{{screenshot file="finance_vehicle_economics.png" title="Vehicle Economics"}}

## Налаштування фінансів

{{screenshot file="finance_global_settings.png" title="Finance Settings"}}