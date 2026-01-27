# Технічне Завдання: Розширення конфігурації форм (Dynamic Forms)

Необхідно розширити структуру відповіді ендпоінту `GET /api/v1/public/landing/config`, додавши детальний опис полів для динамічної побудови форм на фронтенді.

Це дозволить керувати набором полів, їх типами, лейблами та обов'язковістю безпосередньо з бекенду, без необхідності вносити зміни в код лендінгу.

### Нова структура відповіді `forms`

Кожна форма (`register_park`, `lead`) має містити масив `fields`. Кожне поле має мати наступні атрибути:

| Атрибут | Тип | Обов'язково | Опис | Приклад |
|---|---|---|---|---|
| `name` | String | Так | Назва поля (ключ для відправки в POST). | `owner_email` |
| `type` | String | Так | Тип поля для рендерингу. | `text`, `email`, `tel`, `textarea`, `hidden`, `number`, `select` |
| `label` | String | Ні | Підпис поля (Label). | `Ваш Email` |
| `placeholder` | String | Ні | Підказка (Placeholder). | `mail@example.com` |
| `required` | Boolean | Так | Чи є поле обов'язковим (для візуального виділення `*`). | `true` |
| `default` | String | Ні | Значення за замовчуванням. | `monthly` |
| `options` | Array | Ні | Список опцій (тільки для `type: select`). | `[{value: 'kiev', label: 'Київ'}]` |

### Приклад JSON відповіді (GET /config)

```json
{
  "plans": [...],
  "recaptcha_site_key": "...",
  "forms": {
    "register_park": {
      "fields": [
        {
          "name": "park_name",
          "type": "text",
          "label": "Назва Парку",
          "placeholder": "ТОВ Автопарк",
          "required": true
        },
        {
          "name": "owner_name",
          "type": "text",
          "label": "Ім'я Власника",
          "placeholder": "Іван Іванов",
          "required": true
        },
        {
          "name": "owner_email",
          "type": "email",
          "label": "Email (Логін)",
          "placeholder": "email@example.com",
          "required": true
        },
        {
          "name": "phone",
          "type": "tel",
          "label": "Телефон",
          "placeholder": "+380...",
          "required": true
        },
        {
          "name": "plan",
          "type": "hidden",
          "required": true,
          "default": "monthly"
        }
      ]
    },
    "lead": {
      "fields": [
        {
          "name": "name",
          "type": "text",
          "label": "Ваше ім'я",
          "placeholder": "Іван Іванов",
          "required": false
        },
        {
          "name": "email",
          "type": "email",
          "label": "Email",
          "placeholder": "email@example.com",
          "required": true
        },
        {
          "name": "phone",
          "type": "tel",
          "label": "Телефон",
          "placeholder": "+380...",
          "required": false
        },
        {
          "name": "message",
          "type": "textarea",
          "label": "Ваше повідомлення",
          "placeholder": "Опишіть ваш запит...",
          "required": true
        },
        {
          "name": "type",
          "type": "hidden",
          "required": false,
          "default": "general"
        }
      ]
    }
  }
}
```

### Вимоги до фронтенду (Вже реалізовано)

Лендінг вже має логіку для обробки такої структури:
1.  Отримує JSON.
2.  Проходиться циклом по `forms[activeForm].fields`.
3.  Рендерить відповідний HTML тег залежно від `type`.
4.  Додає `*` до лейблу, якщо `required: true`.
5.  Підставляє `default` значення.
6.  Мапить помилки валідації (422) на поля за атрибутом `name`.
