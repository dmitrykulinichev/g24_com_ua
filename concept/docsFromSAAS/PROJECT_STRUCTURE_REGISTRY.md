# Реєстр структури проекту (Сторінки та Модальні вікна)

Цей документ відображає логічну структуру інтерфейсу користувача, групуючи сторінки за розділами та перераховуючи функціональні модальні вікна, доступні на кожній сторінці.

---

## 📊 1. Головна панель (Dashboard)
**Сторінка:** `Dashboard`
*   *Примітка: Використовує віджети замість модальних вікон.*

---

## 🚗 2. Автопарк (Fleet Management)

### Сторінка: Автомобілі (Vehicles)
**Файл:** `Vehicles/index.jsx`
**Модальні вікна:**
1.  **VehicleFormModal** — Створення нового автомобіля або редагування існуючого.
2.  **VehicleDetailsModal** — Перегляд детальної інформації про авто (картка авто).
3.  **VehicleDeleteModal** — Підтвердження видалення автомобіля.
4.  **UpdateMileageModal** — Ручне оновлення пробігу.
5.  **AssignDriverModal** — Призначення водія на авто.
6.  **UnassignDriverModal** — Зняття водія з авто.
7.  **IssueReportFormModal** — Створення звіту про проблему (швидка дія).
8.  **VehicleMaintenanceRecordFormModal** — Створення запису про ТО (швидка дія).
9.  **VehicleQrModal** — Перегляд та друк QR-коду автомобіля.
10. **VehicleImportModal** — Імпорт автомобілів з файлу (Excel/CSV) з попереднім переглядом.
11. **ImportBatchDetailsModal** — Детальний журнал операцій імпорту.
12. **FiltersModal** — Розширені фільтри списку.

### Сторінка: Несправності (Issue Reports)
**Файл:** `IssueReports/index.jsx`
**Модальні вікна:**
1.  **IssueReportFormModal** — Створення/Редагування тікета про проблему.
2.  **IssueReportDetailModal** — Перегляд деталей проблеми.
3.  **IssueReportActionsModal** — Прийняття рішення (Resolve) по проблемі.
4.  **TaskInfoModal** — Перегляд пов'язаної задачі.
5.  **FiltersModal** — Фільтрація звітів.

### Сторінка: Технічне обслуговування (Vehicle Maintenance Records)
**Файл:** `VehicleMaintenanceRecords/index.jsx`
**Модальні вікна:**
1.  **VehicleMaintenanceRecordFormModal** — Створення запису про ремонт/ТО.
2.  **EditMaintenanceRecordModal** — Редагування запису.
3.  **VehicleMaintenanceRecordDetailModal** — Перегляд деталей ремонту (вкл. файли).
4.  **FiltersModal** — Фільтрація журналу.

### Сторінка: Документи (Documents)
**Файл:** `Documents/index.jsx`
**Модальні вікна:**
1.  **DocumentFormModal** — Завантаження/Редагування документу.
2.  **FiltersModal** — Фільтрація документів.

### Сторінка: Звіти (Reports)
**Файл:** `Reports.jsx`
*   *Віджети та фільтри.*

---

## ⚡ 3. Операційна діяльність (Operations)

### Сторінка: Водії (Drivers)
**Файл:** `Drivers/index.jsx`
**Модальні вікна:**
1.  **DriverFormModal** — Створення/Редагування профілю водія.
2.  **DriverDetailsModal** — Картка водія (детальна інформація).
3.  **AssignVehicleModal** — Призначення авто (з боку водія).
4.  **TelegramConnectionModal** — Підключення Telegram-бота.
5.  **DriverCommentsModal** — Перегляд коментарів про водія.
6.  **DriverEditCommentModal** — Редагування коментаря.
7.  **DriverImportModal** — Імпорт водіїв з файлу (Excel/CSV) з попереднім переглядом.
8.  **ImportBatchDetailsModal** — Детальний журнал операцій імпорту.
9.  **FiltersModal** — Фільтрація списку водіїв.

### Сторінка: Призначення (Vehicle Assignments)
**Файл:** `VehicleAssignments/index.jsx`
**Модальні вікна:**
1.  **VehicleAssignmentFormModal** — Створення/Редагування запису про призначення.
2.  **VehicleAssignmentDetailModal** — Деталі призначення.
3.  **FiltersModal** — Фільтрація історії.

### Сторінка: Графік роботи (Driver Schedule)
**Файл:** `DriverSchedule/index.jsx`
**Модальні вікна:**
1.  **DriverScheduleSlotFormModal** — Створення/Редагування слоту графіку (Зміна, ТО, Ремонт).
2.  **ActualShiftDetailsModal** — Перегляд деталей фактичної зміни.
3.  **SimpleDriverDetailsModal** — Швидкий перегляд інформації про водія з графіку.
4.  **ConfirmationModal** — Підтвердження дій.

### Сторінка: Задачі (Tasks)
**Файл:** `Tasks/index.jsx`
**Модальні вікна:**
1.  **TaskFormModal** — Створення/Редагування задачі.
2.  **TaskDetailsModal** — Перегляд деталей задачі.
3.  **FiltersModal** — Фільтрація задач.

### Сторінка: Журнал подій (Activity Logs)
**Файл:** `ActivityLogs/index.jsx`
*   *Тільки перегляд списків.*

---

## 💰 4. Фінанси (Finance)

### Сторінка: Транзакції (Transactions)
**Файл:** `Transactions/index.jsx`
**Модальні вікна:**
1.  **TransactionFormModal** — Створення/Редагування транзакції (Дохід/Витрата).
2.  **FiltersModal** — Фільтрація фінансів.

### Сторінка: Ручні поїздки (Manual Trips)
**Файл:** `ManualTrips/index.jsx`
**Модальні вікна:**
1.  **ManualTripModal** — Створення/Редагування ручного замовлення.
2.  **ManualTripDetailsModal** — Перегляд деталей поїздки (вкл. Telegram ID).
3.  **FiltersModal** — Фільтрація списку.

### Сторінка: Заробіток водіїв (Driver Earnings)
**Файл:** `DriverEarnings/index.jsx`
*   *Сторінка з вкладками:*
    *   **EarningsReportTab:** Тижневий звіт по зарплаті (Вал, База, Бонус, Дохід парку).
    *   **EarningsSchemesTab:** Управління фінансовими схемами (тарифами).
    *   **DriversSettingsTab:** Прив'язка схем до водіїв.
**Модальні вікна:**
1.  **DriverFinancialSchemeModal** — Створення/Редагування тарифного плану.

### Сторінка: Заробіток автомобілів (Vehicle Earnings)
**Файл:** `VehicleEarnings/index.jsx`
*   *Звіт ефективності автопарку (Вал, Уклон, Водій, Парк, Витрати, Прибуток).*
**Модальні вікна:**
1.  **VehicleEarningsDetailsModal** — Детальна картка автомобіля (KPI + розбивка по днях).

### Сторінка: Налаштування фінансів (Finance Settings)
**Файл:** `FinanceSettings/index.jsx`
*   *Налаштування комісій та параметрів розрахунку.*

---

## 📢 5. Маркетинг (Marketing)

### Сторінка: Лендінг (Landing Settings)
**Файл:** `LandingSettings/index.jsx`
*   *Налаштування публічної сторінки.*

### Сторінка: Матеріали (Resources)
**Файл:** `Materials/index.jsx` (або `Resources/index.jsx`)
**Модальні вікна:**
1.  **Modal (Generic)** — Налаштування параметрів завантаження (наприклад, фільтр для QR-кодів).

---

## 🚕 6. Інтеграція Uklon

### Сторінка: Замовлення (Orders)
**Файл:** `Orders/index.jsx`
**Модальні вікна:**
1.  **OrderDetailsModal** — Детальна інформація про замовлення/поїздку.

### Сторінка: Автомобілі Uklon (Uklon Vehicles)
**Файл:** `UklonVehicles/index.jsx`
**Модальні вікна:**
1.  **VehicleIntegrationModal** — Зв'язування авто з Uklon.

### Сторінка: Водії Uklon (Uklon Drivers)
**Файл:** `UklonDrivers/index.jsx`
**Модальні вікна:**
1.  **DriverIntegrationModal** — Зв'язування водіїв з Uklon.

### Сторінка: Звіти Uklon (Uklon Reports)
**Файл:** `UklonReports/index.jsx`
*   *Сторінка аналітики з вкладками:*
    *   **DashboardTab:** Загальні KPI (Дохід, Поїздки, Пробіг, Середній чек), графік динаміки доходів, діаграма статусів.
    *   **DriversTab:** Рейтинг ефективності водіїв (Дохід, Кількість поїздок, Пробіг, Коефіцієнт грн/км).
    *   **VehiclesTab:** Рейтинг ефективності автомобілів (Дохід, Кількість поїздок, Пробіг, Коефіцієнт грн/км).
*   *Функціонал:* Фільтрація за датою (Date Range) та статусами замовлень (MultiSelect).

### Сторінка: Логи Uklon (Uklon Logs)
**Файл:** `UklonLogs/index.jsx`
**Модальні вікна:**
1.  **UklonLogDetailsModal** — Деталі API запиту.

### Сторінка: Налаштування Uklon (Uklon Settings)
**Файл:** `UklonSettings/index.jsx`
*   *Налаштування інтеграції.*

---

## ✈️ 7. Інтеграція Telegram

### Сторінка: Налаштування Telegram (Telegram Settings)
**Файл:** `TelegramSettings/index.jsx`
*   *Налаштування бота.*

### Сторінка: Логи Telegram (Telegram Logs)
**Файл:** `TelegramLogs/index.jsx`
*   *Історія повідомлень.*

---

## ⚙️ 8. Адміністрування та Налаштування

### Сторінка: Користувачі парку (Park Users)
**Файл:** `ParkUsers/index.jsx`
**Модальні вікна:**
1.  **ParkUserFormModal** — Додавання/Редагування співробітника.
2.  **FiltersModal** — Фільтрація.

### Сторінка: Налаштування (Settings)
**Файл:** `Settings/index.jsx`
*   *Використовує вкладки (Tabs):*
    *   AppearanceTab
    *   NotificationsTab
    *   MaintenanceTab
    *   PrivacyTab

### Сторінка: Системні Парки (System Parks) - Super Admin
**Файл:** `SystemParks/index.jsx`
**Модальні вікна:**
1.  **SystemParkFormModal** — Створення/Редагування парку.
2.  **FiltersModal** — Фільтрація.

### Сторінка: Тарифні плани (System Subscription Plans)
**Файл:** `SystemSubscriptionPlans/index.jsx`
**Модальні вікна:**
1.  **SystemSubscriptionPlanFormModal** — Налаштування тарифного плану.
2.  **FiltersModal** — Фільтрація.

### Сторінка: Функціонал підписок (System Subscription Features)
**Файл:** `SystemSubscriptionFeatures/index.jsx`
**Модальні вікна:**
1.  **SystemSubscriptionFeatureFormModal** — Налаштування фічі.

### Сторінка: Системні ролі (System Roles)
**Файл:** `SystemRoles/index.jsx`
**Модальні вікна:**
1.  **FiltersModal** — Фільтрація.

### Сторінка: Системні користувачі (System Users)
**Файл:** `SystemUsers/index.jsx`
**Модальні вікна:**
1.  **FiltersModal** — Фільтрація.

### Сторінка: Debug
**Файл:** `Debug.jsx`
*   *Інструментарій розробника (кнопки дій).*

---

## 👤 9. Особистий кабінет

### Сторінка: Профіль (Profile)
**Файл:** `Profile.jsx`
*   *Використовує вкладки (Tabs) для редагування даних та зміни паролю.*

---

## 📈 10. Аналітика

### Сторінка: Статистика (Statistics)
**Файл:** `Statistics.jsx`
*   *Графіки та діаграми.*
