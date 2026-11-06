"""
Сирі кадри для сторінки документації «Як почати» (/docs/driver-start): «Вхід», код, перший крок реєстрації
нового водія («ви новий водій»).

    cd landing/tools/screens && python3 flows/driver-start.py      # лише uk (інші мови — при перекладі сторінки)
    node capture.mjs compose app-signin app-signin-code app-register-new-driver

Android-емулятор із Garage24 (2.0.27+). Дані застосунку очищаються (pm clear) — якщо на емуляторі був вхід,
після знімання увійти знову. Номер — тестовий Firebase «нового водія» (+380 00 000 00 02, код 123456,
`kmp/docs/testing.md`): акаунта немає, тож після коду — реєстрація; сценарій зупиняється на першому кроці
(акаунт не створюється).
"""
import os, re, subprocess, sys, time

ADB = os.path.expanduser('~/Library/Android/sdk/platform-tools/adb')
CAPTURE = os.path.join(os.path.dirname(os.path.dirname(os.path.abspath(__file__))), 'capture.mjs')
PKG = 'ua.com.g24'
TEST_PHONE = '0000000002'
TEST_CODE = '123456'
LOCALES = {'uk': 'uk-UA', 'en': 'en-US', 'ru': 'ru-RU'}


def adb(*args):
    return subprocess.run([ADB, *args], capture_output=True, text=True).stdout


def screen_text():
    adb('shell', 'uiautomator', 'dump', '/sdcard/ui.xml')
    return adb('shell', 'cat', '/sdcard/ui.xml')


def wait_for(pattern, seconds=30):
    for _ in range(seconds):
        if re.search(pattern, screen_text()):
            return
        time.sleep(1)
    sys.exit(f'не дочекався «{pattern}» на екрані')


def foreground_is_app():
    return f'{PKG}/' in adb('shell', 'dumpsys', 'activity', 'activities').split('topResumedActivity', 1)[-1][:200]


def hide_keyboard():
    # Esc клавіатуру тут не ховає (поле з автофокусом); «назад» — лише коли вона справді відкрита
    if 'mInputShown=true' in adb('shell', 'dumpsys', 'input_method'):
        adb('shell', 'input', 'keyevent', '4')
        time.sleep(1)


def capture(shot, locale):
    hide_keyboard()
    # Емулятором буває користується інша програма — не знімати чужий екран
    if not foreground_is_app():
        sys.exit(f'{shot}/{locale}: на передньому плані не Garage24')
    subprocess.run(['node', CAPTURE, 'app', shot, '--locale', locale], check=True)


for locale in sys.argv[1:] or ['uk']:
    adb('shell', 'pm', 'clear', PKG)
    adb('shell', 'cmd', 'locale', 'set-app-locales', PKG, '--locales', LOCALES[locale])
    adb('shell', 'monkey', '-p', PKG, '-c', 'android.intent.category.LAUNCHER', '1')
    wait_for(r'Увійти з паролем|Sign in with password|Войти с паролем')  # перший екран готовий
    time.sleep(2)
    capture('signin', locale)

    adb('shell', 'input', 'text', TEST_PHONE)
    adb('shell', 'input', 'keyevent', '66')
    wait_for(r'\+380000000002')  # з'явився крок коду
    time.sleep(1)
    capture('signin-code', locale)

    adb('shell', 'input', 'text', TEST_CODE)
    adb('shell', 'input', 'keyevent', '66')
    wait_for(r'новий водій|new driver|новый водитель')  # перший крок реєстрації
    time.sleep(1)
    capture('register-new-driver', locale)

adb('shell', 'cmd', 'locale', 'set-app-locales', PKG, '--locales', 'uk-UA')
