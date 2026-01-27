# API Реєстрації Парку (для Лендінгу)

Цей документ описує публічні ендпоінти для інтеграції форми реєстрації на лендінгу з SaaS платформою.

**Base URL:** `https://app.your-domain.com/api/v1/public/landing`
**Auth:** API Key (Header `X-Landing-Api-Key`).
**Rate Limit:** 60 запитів / хв.
**CORS:** Дозволено для доменів, вказаних у конфігурації сервера.

---

## 🔐 Автентифікація та Безпека

### 1. API Key
Всі запити до API повинні містити заголовок `X-Landing-Api-Key`.
Цей ключ має зберігатися на бекенді лендінгу і не повинен бути доступним публічно.

```http
X-Landing-Api-Key: ваш_секретний_ключ
```

### 2. Google reCAPTCHA
Для захисту від ботів використовується Google reCAPTCHA (v3 або v2 Invisible).
Лендінг повинен отримати токен від Google і передати його в тілі запиту реєстрації.

*   **Site Key:** Повертається в ендпоінті `/config` (поле `recaptcha_site_key`).
*   **Action:** `register_park` (для реєстрації) або `lead_form` (для лідів).

---

## 📡 Ендпоінти

### 1. Отримання конфігурації (GET /config)
Отримує список доступних тарифних планів, налаштування (ключ капчі) та структуру форм.

- **URL:** `/config`
- **Method:** `GET`

#### Приклад відповіді (200 OK):

```json
{
  "plans": [
    {
      "id": 1,
      "slug": "standard-monthly-2026",
      "name": "Стандарт (Щомісячний)",
      "price_monthly": "1000.00",
      "price_yearly": "0.00",
      "price_per_car": "200.00",
      "currency": "UAH"
    },
    {
      "id": 2,
      "slug": "yearly-2026",
      "name": "Річний (Знижка 50% на авто)",
      "price_monthly": "0.00",
      "price_yearly": "12000.00",
      "price_per_car": "100.00",
      "currency": "UAH"
    }
  ],
  "recaptcha_site_key": "6Lc...",
  "forms": {
    "register_park": {
      "fields": [
        {"name": "park_name", "type": "text", "required": true, "label": "Назва Парку", "placeholder": "Наприклад: АвтоЛюкс"},
        {"name": "owner_name", "type": "text", "required": true, "label": "Ім'я Власника", "placeholder": "Ваше ім'я"},
        {"name": "owner_email", "type": "email", "required": true, "label": "Email", "placeholder": "email@example.com"},
        {"name": "phone", "type": "tel", "required": true, "label": "Телефон", "placeholder": "+380..."},
        {"name": "plan", "type": "hidden", "required": true, "default": "monthly"}
      ]
    },
    "lead": {
      "fields": [
        {"name": "email", "type": "email", "required": true, "label": "Email", "placeholder": "email@example.com"},
        {"name": "message", "type": "textarea", "required": true, "label": "Повідомлення", "placeholder": "Опишіть ваш запит..."},
        {"name": "name", "type": "text", "required": false, "label": "Ім'я", "placeholder": "Ваше ім'я"},
        {"name": "phone", "type": "tel", "required": false, "label": "Телефон", "placeholder": "+380..."},
        {"name": "type", "type": "hidden", "required": false, "default": "general"}
      ]
    }
  }
}
```

---

### 2. Реєстрація Парку (POST /register)
Створює новий парк, власника та відправляє лист активації.

- **URL:** `/register`
- **Method:** `POST`

#### Тіло запиту (JSON):

```json
{
  "park_name": "Назва Парку",
  "owner_name": "Ім'я Власника",
  "owner_email": "owner@mail.com",
  "phone": "+380501234567",
  "plan": "monthly", 
  "g-recaptcha-response": "03AFc...",
  "ip": "123.123.123.123",
  "user_agent": "Mozilla/5.0..."
}
```

| Поле | Тип | Обов'язкове | Опис |
|---|---|---|---|
| `park_name` | String | Так | Назва компанії/парку (2-255 символів). |
| `owner_name` | String | Так | ПІБ власника (2-255 символів). |
| `owner_email` | Email | Так | Email власника (унікальний логін). |
| `phone` | String | Так | Телефон власника (до 20 символів). |
| `plan` | String | Так | Тип плану: `monthly` або `yearly`. |
| `g-recaptcha-response` | String | Так | Токен від Google reCAPTCHA. |
| `ip` | String | Ні | IP адреса клієнта (для логів). |
| `user_agent` | String | Ні | User Agent клієнта (для логів). |

#### Успішна відповідь (201 Created):

```json
{
  "success": true,
  "message": "Парк успішно зареєстровано. Перевірте вашу пошту для активації акаунту.",
  "redirect_url": "https://app.g24.com.ua/login"
}
```

#### Помилка валідації (422 Unprocessable Entity):

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "owner_email": ["The owner email has already been taken."],
    "g-recaptcha-response": ["Помилка перевірки reCAPTCHA"]
  }
}
```

---

### 3. Створення Ліда (POST /lead)
Відправляє заявку з контактної форми (Enterprise, Консультація).

- **URL:** `/lead`
- **Method:** `POST`

#### Тіло запиту (JSON):

```json
{
  "email": "ivan@company.com",
  "message": "Цікавлять індивідуальні умови для парку 500+ авто.",
  "name": "Іван Директор",
  "phone": "+380501112233",
  "type": "enterprise",
  "g-recaptcha-response": "03AFc...",
  "ip": "123.123.123.123",
  "user_agent": "Mozilla/5.0..."
}
```

| Поле | Тип | Обов'язкове | Опис |
|---|---|---|---|
| `email` | Email | Так | Email для зв'язку. |
| `message` | String | Так | Текст повідомлення (до 5000 символів). |
| `name` | String | Ні | Ім'я контактної особи. |
| `phone` | String | Ні | Телефон. |
| `type` | String | Ні | Тип заявки. Якщо не вказано, використовується `general`. Рекомендовані значення: `general`, `enterprise`, `partnership`. |
| `g-recaptcha-response` | String | Так | Токен від Google reCAPTCHA. |
| `ip` | String | Ні | IP адреса клієнта. |
| `user_agent` | String | Ні | User Agent клієнта. |

#### Успішна відповідь (201 Created):

```json
{
  "success": true,
  "message": "Ваша заявка прийнята. Менеджер зв'яжеться з вами найближчим часом."
}
```

---

## 💻 Приклад реалізації (JS/Fetch)

```javascript
const API_URL = 'https://app.g24.com.ua/api/v1/public/landing';
const API_KEY = 'ваш_секретний_ключ_бекенду';

// Функція для реєстрації парку
async function registerPark(formData) {
  const recaptchaToken = await grecaptcha.execute(SITE_KEY, { action: 'register_park' });
  // ... (див. вище)
}

// Функція для відправки ліда
async function sendLead(formData) {
  const recaptchaToken = await grecaptcha.execute(SITE_KEY, { action: 'lead_form' });

  const payload = {
    ...formData,
    'g-recaptcha-response': recaptchaToken,
    'type': 'general' // Або 'enterprise', якщо це форма для великих клієнтів
  };

  const response = await fetch(`${API_URL}/lead`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-Landing-Api-Key': API_KEY
    },
    body: JSON.stringify(payload)
  });
  
  // ... обробка відповіді
}
```