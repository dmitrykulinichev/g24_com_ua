"""
Сирі кадри реєстрації нового водія для сторінки «Як почати» (/docs/driver-start): код із SMS → «ви новий водій» →
«Ваші дані» (заповнено) → пароль. Реєстрацію НЕ завершує — номер лишається новим для наступного знімання.

    cd landing/tools/screens && python3 flows/driver-register.py en [ru …]
    node capture.mjs compose app-signin-code app-register-new-driver app-register-profile-filled app-register-password

Хто в кадрі — `CAST.md`. Емулятор `Medium_Phone` (1080×2400), застосунок 2.0.27+. Координати — цього екрана (зчитування uiautomator на
завантаженому емуляторі збоїть). Номер — тестовий Firebase «нового водія для скрінів» +380 79 111 22 98 (код
123456), парк «Київ» (код WP7FR2), дані для прикладу латиницею (adb не вводить кирилицю): `kmp/docs/testing.md`.
"""
import os, subprocess, sys, time

ADB = os.path.expanduser('~/Library/Android/sdk/platform-tools/adb')
CAPTURE = os.path.join(os.path.dirname(os.path.dirname(os.path.abspath(__file__))), 'capture.mjs')
PKG = 'ua.com.g24'
LOCALES = {'uk': 'uk-UA', 'en': 'en-US', 'ru': 'ru-RU'}
PHONE, SMS_CODE, PARK_CODE = '0791112298', '123456', 'WP7FR2'
FIRST, LAST, ADDRESS, LICENSE = 'Andrii', 'Tkachenko', 'Kyiv,%sSichovykh%sStriltsiv%sSt,%s12', 'AXK482917'
# 14.03.1990 у форматі календаря мови: англійська — місяць/день/рік
BIRTH = {'uk': '14031990', 'ru': '14031990', 'en': '03141990'}

# Координати на 1080×2400
FIELD_TOP = (540, 672)        # перше поле кроку (номер, код парку, посвідчення)
CODE_FIELD = (540, 1016)      # поле коду під номером
PRIMARY = (540, 2228)         # основна кнопка внизу
NAME, SURNAME, BIRTH_FIELD, ADDRESS_FIELD = (540, 606), (540, 806), (540, 1205), (540, 1405)
DATE_PENCIL, DATE_INPUT, DATE_SAVE = (916, 669), (540, 1265), (900, 1486)
SKIP_EMAIL = (540, 915)


def adb(*args):
    return subprocess.run([ADB, *args], capture_output=True, text=True).stdout


def tap(xy, wait=1.5):
    adb('shell', 'input', 'tap', str(xy[0]), str(xy[1]))
    time.sleep(wait)


def type_text(text):
    adb('shell', 'input', 'text', text)
    time.sleep(0.5)


def hide_keyboard():
    if 'mInputShown=true' in adb('shell', 'dumpsys', 'input_method'):
        adb('shell', 'input', 'keyevent', '4')
        time.sleep(1)


def capture(shot, locale):
    hide_keyboard()
    if f'{PKG}/' not in adb('shell', 'dumpsys', 'activity', 'activities').split('topResumedActivity', 1)[-1][:200]:
        sys.exit(f'{shot}/{locale}: на передньому плані не Garage24')
    subprocess.run(['node', CAPTURE, 'app', shot, '--locale', locale], check=True)


for locale in sys.argv[1:] or ['uk']:
    adb('shell', 'pm', 'clear', PKG)
    adb('shell', 'cmd', 'locale', 'set-app-locales', PKG, '--locales', LOCALES[locale])
    adb('shell', 'monkey', '-p', PKG, '-c', 'android.intent.category.LAUNCHER', '1')
    time.sleep(12)

    tap(FIELD_TOP); type_text(PHONE); hide_keyboard(); tap(PRIMARY, wait=20)  # Firebase буває повільний
    capture('signin-code', locale)

    tap(CODE_FIELD); type_text(SMS_CODE); hide_keyboard(); tap(PRIMARY, wait=15)
    capture('register-new-driver', locale)

    tap(FIELD_TOP); type_text(PARK_CODE); hide_keyboard(); tap(PRIMARY, wait=8)
    tap(NAME); type_text(FIRST); tap(SURNAME); type_text(LAST); hide_keyboard()
    tap(ADDRESS_FIELD); type_text(ADDRESS); hide_keyboard()
    tap(BIRTH_FIELD, wait=3); tap(DATE_PENCIL, wait=2); tap(DATE_INPUT)
    adb('shell', 'input', 'keyevent', 'KEYCODE_MOVE_END')
    for _ in range(12):
        adb('shell', 'input', 'keyevent', 'KEYCODE_DEL')
    type_text(BIRTH[locale]); hide_keyboard(); tap(DATE_SAVE, wait=2)
    capture('register-profile-filled', locale)

    tap(PRIMARY, wait=4); tap(FIELD_TOP); type_text(LICENSE); hide_keyboard(); tap(PRIMARY, wait=8)
    hide_keyboard(); tap(SKIP_EMAIL, wait=4)
    capture('register-password', locale)
