# Технічне Завдання: Інтеграція Лендінгу з SPA API

Цей документ описує вимоги до API SaaS платформи для забезпечення повноцінної роботи форми реєстрації та сторінки цін на лендінгу.

**Архітектура:**
Лендінг працює як Proxy. Браузер клієнта відправляє запити на бекенд лендінгу, а лендінг пересилає їх на SPA API.
Це означає, що всі запити до SPA API будуть надходити з **IP-адреси сервера лендінгу**.

---

## 1. Ендпоінт конфігурації (Ціни)

Необхідний для динамічного відображення цін на сторінці `/pricing`.

- **Method:** `GET`
- **URL:** `/api/v1/public/landing/config`
- **Access:** Public

### Вимоги до відповіді (Response Body)
API має повертати масив `plans`. Для коректної роботи перемикача "Місяць/Рік" на лендінгу, в системі мають бути плани, що містять в `slug` слова `monthly` та `yearly`.

```json
{
  "plans": [
    {
      "id": 1,
      "slug": "standard-monthly",  // Лендінг шукає входження 'monthly'
      "name": "Standard Monthly",
      "price_monthly": "1000.00",  // Базова ціна (використовується лендінгом)
      "price_per_car": "200.00",   // Ціна за авто (використовується лендінгом)
      "currency": "UAH"
    },
    {
      "id": 2,
      "slug": "standard-yearly",   // Лендінг шукає входження 'yearly'
      "name": "Standard Yearly",
      "price_monthly": "0.00",     // Якщо 0, лендінг вирахує (price_yearly / 12)
      "price_yearly": "12000.00",  // Використовується для розрахунку бази
      "price_per_car": "100.00",   // Акційна ціна за авто
      "currency": "UAH"
    }
  ]
}
```

---

## 2. Ендпоінт реєстрації (Створення Парку)

Необхідний для обробки форми "Почати роботу".

- **Method:** `POST`
- **URL:** `/api/v1/public/landing/register`
- **Access:** Public (захищено Google reCAPTCHA)

### Тіло запиту (Request Body)

Лендінг відправляє наступні поля. Всі поля є обов'язковими (окрім UTM-міток).

```json
{
  "park_name": "Назва Парку",        // String, min: 2
  "owner_name": "Ім'я Власника",     // String, min: 2
  "owner_email": "owner@mail.com",   // Email, Unique (User login)
  "phone": "+380501234567",          // String, Phone format
  "plan": "monthly",                 // Enum: 'monthly' | 'yearly'
  "g-recaptcha-response": "03AFc...",// String (Google Token)
  
  // Технічні поля (для логування)
  "ip": "123.123.123.123",
  "user_agent": "Mozilla/5.0..."
}
```

### Логіка обробки на стороні API

1.  **Валідація reCAPTCHA:**
    *   API **обов'язково** має перевірити токен `g-recaptcha-response` через Google API (`https://www.google.com/recaptcha/api/siteverify`).
    *   Використовувати `RECAPTCHA_SECRET_KEY` з `.env` файлу SPA.
    *   Якщо перевірка не пройшла -> повернути помилку 422.

2.  **Валідація даних:**
    *   Перевірити унікальність `owner_email`.
    *   Перевірити формат телефону.

3.  **Створення сутностей:**
    *   Створити `Tenant` (Парк).
    *   Створити `User` (Власник) з роллю `owner`.
    *   Створити підписку (Subscription) відповідно до обраного `plan` ('monthly' або 'yearly').

4.  **Відповідь:**

#### Успіх (201 Created)
```json
{
  "success": true,
  "message": "Парк успішно зареєстровано. Перевірте вашу пошту для активації акаунту.",
  "redirect_url": "https://app.g24.com.ua/login" // Опціонально, якщо потрібен авто-логін
}
```

#### Помилка валідації (422 Unprocessable Entity)
**Критично важливо:** Формат помилок має відповідати стандарту Laravel, щоб лендінг міг підсвітити конкретні поля.

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "park_name": [
      "Така назва парку вже існує."
    ],
    "owner_email": [
      "Користувач з таким email вже зареєстрований."
    ],
    "g-recaptcha-response": [
      "Помилка перевірки капчі."
    ]
  }
}
```

---

## Чек-ліст для розробника API

- [ ] Додати `GET /api/v1/public/landing/config` (повертає плани).
- [ ] Додати `POST /api/v1/public/landing/register`.
- [ ] Налаштувати валідацію вхідних даних (Laravel Request Validation).
- [ ] Реалізувати серверну перевірку Google reCAPTCHA.
- [ ] Забезпечити повернення помилок у форматі `{ "errors": { "field": ["msg"] } }` при 422 статусі.
- [ ] Переконатися, що CORS налаштований (або дозволяє запити з IP лендінгу, або публічний).
