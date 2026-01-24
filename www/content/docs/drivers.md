# Водії та HR (Driver Management)
**Слоган:** "CRM-система для роботи з персоналом."

Модуль закриває всі питання кадрового обліку: від залучення нових водіїв до їх звільнення та розрахунку.

{{screenshot file="drivers_list.png" title="Drivers List"}}

## Інструменти менеджера

### Профіль водія
*   **DriverDetailsModal:** Повна картка з контактами, документами та фото.
*   **Контакти родичів:** Можливість збереження контактів двох родичів для екстреного зв'язку.
*   **DriverCommentsModal:** Внутрішні нотатки менеджерів про водія.

{{screenshot file="driver_profile_modal.png" title="Driver Profile Modal"}}

### Масовий імпорт (Import)
Завантаження списку водіїв з Excel/CSV файлів.
*   **Валідація:** Перевірка дублікатів по номеру телефону, email та номеру прав.
*   **Попередній перегляд:** Таблиця з результатами перевірки перед завантаженням.

{{screenshot file="driver_import_modal.png" title="Driver Import Modal"}}

### Комунікація
*   **TelegramConnectionModal:** Швидке підключення бота через QR-код або посилання.
*   **Registration Link:** Копіювання посилання для самостійної реєстрації кандидата.

{{screenshot file="marketing_landing_settings.png" title="Landing Page Settings"}}

### Операційні дії
*   **AssignVehicleModal:** Закріплення авто за водієм прямо з його профілю.
*   **DriverIntegrationModal:** Налаштування синхронізації з Uklon.

{{screenshot file="driver_vehicle_assignments.png" title="Vehicle Assignments"}}

## Екосистема Telegram
Модуль забезпечує зв'язок між системою управління та персоналом через месенджер.
*   **Підключення:** Генерація унікальних кодів для прив'язки Telegram-акаунту водія.
*   **Функції бота:** Автоматичні сповіщення про борги, нагадування про зміну. Водій може дізнатися свій баланс.
*   **Ручні замовлення:** Водії можуть додавати замовлення "від бордюру" через бота командою `[номер авто] рука [сума]`.

{{screenshot file="driver_telegram_connect_modal.png" title="Telegram Connection Modal"}}