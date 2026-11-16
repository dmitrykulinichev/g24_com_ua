# Сайт водія — `driver.g24.com.ua`

Посібник водія на своєму домені. Водій бачить лише посібник: без меню SaaS-сайту, цін, форм заявки й входу в кабінет.

## Як це працює

- **Один код, одна тека.** На хостингу субдомен дивиться в ту саму теку, що й `g24.com.ua`: символьне посилання
  `~/g24.com.ua/driver -> www` (корінь субдомену змінити не можна; заглушку хостингу відкладено в `driver.bak`).
- **Сайт обирається за доменом запиту** — `DriverSite::active()` (домен — `DRIVER_HOST` у `.env`, за замовчуванням
  `driver.g24.com.ua`).
- **Адреси.** На домені водія — без `/driver`: `/start`, `/en/start`. Всередині це звичайні адреси розділу
  (`/driver/start`), тож контролер, вміст і пошук посібника ті самі, що й були (`DriverGuideController`,
  `content/driver`). Будь-яка сторінка SaaS-сайту на домені водія — 404 посібника.
- **Переїзд.** `g24.com.ua/driver…` → 301 на `driver.g24.com.ua/…` (старі посилання й пошук не губляться).
  Локально (`localhost`) нічого не переадресовується.
- **Пошук (SEO).** Мапи сайту окремі — кожна для своєї властивості в Google Search Console:
  `https://g24.com.ua/sitemap.xml` (сайт без посібника) і `https://driver.g24.com.ua/sitemap-driver.xml` (лише
  посібник, адресами домену водія). Обидві — статичні файли тієї ж теки, генерує `/api/sitemap/generate?key=…`;
  тому в мапи водія своя назва (за доменом розрізнити статичний файл не можна). `robots.txt` спільний — у ньому
  обидві мапи. Canonical і hreflang на сторінках — домен водія.

## Де код сайту водія

| Що | Де |
|---|---|
| Уся логіка (домен, адреси, переадресація) | `app/DriverSite/DriverSite.php` |
| Шапка й підвал домену водія | `views/driver-site/header.blade.php`, `views/driver-site/footer.blade.php` |
| Тести | `tests/DriverSiteTest.php` |

## Де основний лендінг його викликає (позначено коментарем «САЙТ ВОДІЯ»)

| Файл | Що робить |
|---|---|
| `index.php` | `DriverSite::handleRequest()` — до мов і маршрутів: адреса домену водія → адреса розділу; `/driver…` основного домену → 301 |
| `app/I18n/LocalizedUrl.php` | `to()` → `DriverSite::link()` (посилання посібника без `/driver`, решта — на основний домен); `base()` → домен водія (canonical, hreflang) |
| `app/Controllers/Docs2Controller.php` | `DriverSite::rewriteLinks()` — посилання `/driver/…` у текстах сторінок; стара `/docs/driver-*` → одразу на домен водія |
| `app/Controllers/SitemapController.php`, `app/routes.php`, `robots.txt` | окрема мапа `sitemap-driver.xml` (`DriverSite::isSectionUrl()`, `sitemapUrl()`); в основній мапі посібника немає |
| `views/layout.blade.php` | шапка й підвал водія замість основних; без форми заявки |
| `views/docs2/index.blade.php` | банер «Посібник водія» в документації парку → `DriverSite::driverUrl()` (на домен водія) |

Основний лендінг поза цими місцями про сайт водія не знає. Пункт «Водіям» з меню основного сайту прибрано
(16.11.2026, щоб не дублювати посібник); інших змін у шапці й підвалі основного сайту немає.

## Перевірити локально

```
curl -H "Host: driver.g24.com.ua" http://localhost:8001/start
```

Браузер: Chrome з `--host-resolver-rules="MAP driver.g24.com.ua:80 127.0.0.1:8001"
--disable-features=HttpsUpgrades` і чистим профілем (інакше HSTS поведе на прод).
Хостинг віддає 403 «HeadlessChrome» (захист від ботів) — це не помилка сайту.
