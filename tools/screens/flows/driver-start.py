"""
Сирі кадри для сторінки документації «Як почати» (/docs/driver-start) трьома мовами: вхід, «Код парку», телефон.

    cd landing/tools/screens && python3 flows/driver-start.py [uk en ru]
    node capture.mjs compose app-login app-register-park-code app-register-phone

Android-емулятор з Garage24, без входу. Дані застосунку очищаються (pm clear) — якщо на емуляторі був вхід
тестовим водієм, після знімання увійти знову. Код парку «Київ» лише перевіряється, акаунт не створюється.
"""
import os, re, subprocess, sys, time

ADB = os.path.expanduser('~/Library/Android/sdk/platform-tools/adb')
CAPTURE = os.path.join(os.path.dirname(os.path.dirname(os.path.abspath(__file__))), 'capture.mjs')
PKG = 'ua.com.g24'
CODE = 'WP7FR2'
TEXT = {
    'uk': {'register': 'Зареєструватися', 'field': 'Код парку', 'next': 'Далі', 'locale': 'uk-UA'},
    'en': {'register': 'Sign up', 'field': 'Fleet code', 'next': 'Next', 'locale': 'en-US'},
    'ru': {'register': 'Зарегистрироваться', 'field': 'Код парка', 'next': 'Далее', 'locale': 'ru-RU'},
}


def adb(*args):
    return subprocess.run([ADB, *args], capture_output=True, text=True).stdout


def find(text):
    adb('shell', 'uiautomator', 'dump', '/sdcard/ui.xml')
    xml = adb('shell', 'cat', '/sdcard/ui.xml')
    for node in re.findall(r'<node [^>]*>', xml):
        if f'text="{text}"' in node or f'content-desc="{text}"' in node:
            a, b, c, d = map(int, re.search(r'bounds="\[(\d+),(\d+)\]\[(\d+),(\d+)\]"', node).groups())
            return (a + c) // 2, (b + d) // 2
    sys.exit(f'не знайшов «{text}» на екрані')


def tap(text):
    x, y = find(text)
    adb('shell', 'input', 'tap', str(x), str(y))
    time.sleep(1.5)


def hide_keyboard():
    # Esc клавіатуру тут не ховає (поле з автофокусом); «назад» — лише коли вона справді відкрита
    if 'mInputShown=true' in adb('shell', 'dumpsys', 'input_method'):
        adb('shell', 'input', 'keyevent', '4')
        time.sleep(1)


def capture(shot, locale):
    hide_keyboard()
    for _ in range(10):  # банер «немає зв'язку» після запуску зникає сам — чекаємо
        adb('shell', 'uiautomator', 'dump', '/sdcard/ui.xml')
        if not re.search(r'сервер|server|сервер', adb('shell', 'cat', '/sdcard/ui.xml'), re.I):
            break
        time.sleep(2)
    else:
        sys.exit(f'{shot}/{locale}: банер «немає зв\'язку» не зник')
    subprocess.run(['node', CAPTURE, 'app', shot, '--locale', locale], check=True)


for locale in sys.argv[1:] or TEXT:
    t = TEXT[locale]
    adb('shell', 'pm', 'clear', PKG)  # чистий запуск: без збереженого стану майстра реєстрації
    adb('shell', 'cmd', 'locale', 'set-app-locales', PKG, '--locales', t['locale'])
    adb('shell', 'monkey', '-p', PKG, '-c', 'android.intent.category.LAUNCHER', '1')
    time.sleep(6)
    capture('login', locale)

    tap(t['register'])
    tap(t['field'])
    adb('shell', 'input', 'text', CODE)
    time.sleep(1)
    capture('register-park-code', locale)
    tap(t['next'])
    time.sleep(3)
    time.sleep(1)
    capture('register-phone', locale)

adb('shell', 'cmd', 'locale', 'set-app-locales', PKG, '--locales', 'uk-UA')
