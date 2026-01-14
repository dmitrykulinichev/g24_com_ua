# Водії та HR (Driver Management)
**Слоган:** "CRM-система для роботи з персоналом."

Модуль закриває всі питання кадрового обліку: від залучення нових водіїв до їх звільнення та розрахунку.

{{screenshot file="drivers_list.png" title="Drivers List"}}

## Інструменти менеджера

### Профіль водія
*   **DriverDetailsModal:** Повна картка з контактами, документами та фото.
*   **DriverCommentsModal:** Внутрішні нотатки менеджерів.

{{screenshot file="driver_profile_modal.png" title="Driver Profile Modal"}}

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

{{screenshot file="driver_telegram_connect_modal.png" title="Telegram Connection Modal"}}