"""
Сирі кадри сторінки «Фото- й відеозвіт стану авто» (/driver/condition-report) і оновлений кадр меню «+» для
«Записи за зміну»: меню «+» → порожній аркуш «Фотозвіт» → 2 фото + відео + коментар → дії з відео →
«Мої записи → Фотозвіти» → дії з прийнятим звітом.

    cd landing/tools/screens && python3 flows/driver-condition-report.py [uk en ru] [--keep]
    node capture.mjs compose app-quickadd-menu app-condition-report-empty app-condition-report-filled \\
        app-condition-report-video-actions app-condition-reports app-condition-report-actions

Хто в кадрі — `CAST.md`: Andrii Tkachenko (0791112299), парк «Київ», Volkswagen Passat KA1001AA. Старт: Ткаченко на
Passat. Сценарій ЗБЕРІГАЄ звіт (2 фото + відео, без коментаря: adb не вводить кирилицю) — він іде на прод і в
групу Telegram «Київ»; після кадрів звіт видаляється (`--keep` — лишити).

Натискання — за ТЕКСТОМ з `uiautomator dump` (а не за координатами): 16.11.2026 координатний тап у час повільного
запуску влучив у «Здати автомобіль». Камера емулятора — координати її кнопок (затвор / запис / ✓ внизу по центру).
"""
import os, re, subprocess, sys, time

ADB = os.path.expanduser('~/Library/Android/sdk/platform-tools/adb')
CAPTURE = os.path.join(os.path.dirname(os.path.dirname(os.path.abspath(__file__))), 'capture.mjs')
PKG = 'ua.com.g24'
LOCALES = {'uk': 'uk-UA', 'en': 'en-US', 'ru': 'ru-RU'}
TEXTS = {
    'uk': {'report': 'Фотозвіт', 'photo': 'Зробити фото', 'video': 'Записати відео', 'save': 'Зберегти', 'delete': 'Видалити',
           'records': 'Мої записи', 'reports': 'Фотозвіти', 'home': 'Головна'},
    'en': {'report': 'Photo report', 'photo': 'Take photo', 'video': 'Record video', 'save': 'Save', 'delete': 'Delete',
           'records': 'My records', 'reports': 'Photo reports', 'home': 'Home'},
    'ru': {'report': 'Фотоотчёт', 'photo': 'Сделать фото', 'video': 'Записать видео', 'save': 'Сохранить', 'delete': 'Удалить',
           'records': 'Мои записи', 'reports': 'Фотоотчёты', 'home': 'Главная'},
}
FAB = (540, 2232)            # «+» по центру таббару
CAMERA_BUTTON = (540, 2242)  # затвор / почати й зупинити запис у камері емулятора
CAMERA_DONE = (560, 2242)    # ✓ після знімка


def adb(*args, timeout=30):
    try:
        return subprocess.run([ADB, *args], capture_output=True, text=True, timeout=timeout).stdout
    except subprocess.TimeoutExpired:
        return ''


def screen():
    """Тексти на екрані з центрами: [(text, x, y)]."""
    adb('shell', 'uiautomator', 'dump', '/sdcard/ui.xml', timeout=25)
    xml = adb('shell', 'cat', '/sdcard/ui.xml')
    out = []
    for text, x1, y1, x2, y2 in re.findall(r'text="([^"]+)"[^>]*bounds="\[(\d+),(\d+)\]\[(\d+),(\d+)\]"', xml):
        out.append((text, (int(x1) + int(x2)) // 2, (int(y1) + int(y2)) // 2))
    return out


def find(prefix, tries=6):
    for _ in range(tries):
        for text, x, y in screen():
            if text.startswith(prefix):
                return x, y
        time.sleep(2)
    sys.exit(f'на екрані немає «{prefix}»')


def tap_text(prefix, wait=3):
    x, y = find(prefix)
    adb('shell', 'input', 'tap', str(x), str(y))
    time.sleep(wait)


LANGUAGE_NAMES = {'uk': 'Українська', 'en': 'English', 'ru': 'Русский'}


def set_app_language(locale):
    """Мова застосунку — у його налаштуваннях (☰ → Налаштування → «Мова / Language»): вона переважає системну."""
    for _ in range(4):  # ☰ інколи не відкривається, поки застосунок довантажується
        tap((72, 148), wait=3)
        hit = [(x, y) for t, x, y in screen() if t in ('Налаштування', 'Settings', 'Настройки')]
        if hit:
            tap(hit[0], wait=3)
            break
        time.sleep(3)
    else:
        sys.exit('не знайшов «Налаштування» в меню')
    row = [(x, y) for t, x, y in screen() if 'Language' in t]
    if not row:
        sys.exit('не знайшов рядок мови')
    tap((540, row[0][1]), wait=3)
    tap_text(LANGUAGE_NAMES[locale], wait=4)
    adb('shell', 'input', 'keyevent', '4')
    time.sleep(2)


def tap(xy, wait=3):
    adb('shell', 'input', 'tap', str(xy[0]), str(xy[1]))
    time.sleep(wait)


def top_activity():
    return adb('shell', 'dumpsys', 'activity', 'activities').split('topResumedActivity', 1)[-1][:200]


def hide_keyboard():
    if 'mInputShown=true' in adb('shell', 'dumpsys', 'input_method'):
        adb('shell', 'input', 'keyevent', '4')
        time.sleep(1.5)


def capture(shot, locale):
    hide_keyboard()
    if f'{PKG}/' not in top_activity():
        sys.exit(f'{shot}/{locale}: на передньому плані не Garage24')
    if 'NotificationRecord(' in adb('shell', 'dumpsys', 'notification', '--noredact'):
        sys.exit(f'{shot}/{locale}: на емуляторі є сповіщення — шторка → «Clear all», і знову')
    subprocess.run(['node', CAPTURE, 'app', shot, '--locale', locale], check=True)


def camera_back(wait):
    """Чекати повернення з камери в застосунок."""
    for _ in range(wait):
        if f'{PKG}/' in top_activity():
            return
        time.sleep(1)
    sys.exit('камера не повернула в застосунок')


def take_photo():
    tap_text(T['photo'], wait=6)
    tap(CAMERA_BUTTON, wait=3)
    tap(CAMERA_DONE, wait=2)
    camera_back(15)
    time.sleep(2)


def record_video(seconds=6):
    tap_text(T['video'], wait=6)
    tap(CAMERA_BUTTON, wait=seconds)
    tap(CAMERA_BUTTON, wait=3)
    tap(CAMERA_DONE, wait=2)
    camera_back(15)
    time.sleep(10)  # стискання на телефоні


for locale in [a for a in sys.argv[1:] if not a.startswith('--')] or ['uk']:
    T = TEXTS[locale]
    # Мова: власне налаштування застосунку, потім перевіряємо текст «Головної» (застосовується не з першого разу).
    adb('shell', 'am', 'force-stop', PKG)
    adb('shell', 'monkey', '-p', PKG, '-c', 'android.intent.category.LAUNCHER', '1')
    time.sleep(15)
    set_app_language(locale)
    for attempt in range(4):
        adb('shell', 'cmd', 'locale', 'set-app-locales', PKG, '--locales', LOCALES[locale])
        adb('shell', 'am', 'force-stop', PKG)
        time.sleep(2)
        adb('shell', 'monkey', '-p', PKG, '-c', 'android.intent.category.LAUNCHER', '1')
        time.sleep(15)
        if any(text == T['home'] for text, _, _ in screen()):
            break
    else:
        sys.exit(f'{locale}: мова застосунку не змінилась')
    time.sleep(3)

    tap(FAB, wait=2)
    capture('quickadd-menu', locale)
    tap_text(T['report'], wait=3)
    capture('condition-report-empty', locale)

    take_photo()
    take_photo()
    record_video()
    capture('condition-report-filled', locale)

    # Плитка відео — над кнопкою «Зробити фото»
    vx, vy = find(T['photo'])
    tap((540, vy - 300), wait=2)
    capture('condition-report-video-actions', locale)
    adb('shell', 'input', 'keyevent', '4')
    time.sleep(1.5)

    tap_text(T['save'], wait=25)  # тост «Скасувати» 5 с, далі заливка; форма відкриває історію
    capture('condition-reports', locale)
    tap((540, 576), wait=2)       # верхня картка — щойно збережений звіт
    capture('condition-report-actions', locale)
    if '--keep' in sys.argv:
        adb('shell', 'input', 'keyevent', '4')
    else:
        tap_text(T['delete'], wait=8)  # тестовий звіт цієї мови — прибрати (і з групи Telegram)
