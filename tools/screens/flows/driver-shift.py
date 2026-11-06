"""
Сирі кадри сторінки «Зміна: прийняти й здати авто» (/docs/driver-shift): вільні авто → пробіг при прийомі →
«Мій автомобіль» → «Головна» з авто → пробіг при здачі → тост «Автомобіль здано».

    cd landing/tools/screens && python3 flows/driver-shift.py <пробіг при прийомі> <пробіг при здачі> [uk en ru]
    node capture.mjs compose app-vehicle-choose app-vehicle-accept app-vehicle-mine app-home-with-car \\
        app-vehicle-handover app-vehicle-handover-done

Хто в кадрі — `CAST.md`: Andrii Tkachenko (0791112299), парк «Київ», Volkswagen Passat KA1001AA. Старт: Ткаченко
увійшов, без авто. Пробіг Passat росте з кожним прогоном: прийом — трохи більше за поточний (він під полем),
здача — +150…200 км. Наприкінці авто здано, стан як на старті.

Емулятор `Medium_Phone` (1080×2400), застосунок 2.0.28+. Координати — цього екрана; Passat — друга картка «Вільні».
"""
import os, subprocess, sys, time

ADB = os.path.expanduser('~/Library/Android/sdk/platform-tools/adb')
CAPTURE = os.path.join(os.path.dirname(os.path.dirname(os.path.abspath(__file__))), 'capture.mjs')
PKG = 'ua.com.g24'
LOCALES = {'uk': 'uk-UA', 'en': 'en-US', 'ru': 'ru-RU'}

# Координати на 1080×2400
TAB_HOME = (100, 2240)
CHOOSE_CAR = (540, 1200)      # «Обрати автомобіль» на «Головній» без авто
PASSAT_ACCEPT = (810, 1150)   # «Прийняти» на другій картці «Вільні»
SHEET_PRIMARY = (795, 2245)   # «Прийняти» / «Здати автомобіль» в аркуші пробігу (клавіатуру сховано)
HANDOVER = (540, 700)         # «Здати автомобіль» на «Головній» з авто


def adb(*args):
    return subprocess.run([ADB, *args], capture_output=True, text=True).stdout


def tap(xy, wait=3):
    adb('shell', 'input', 'tap', str(xy[0]), str(xy[1]))
    time.sleep(wait)


def type_mileage(km):
    for _ in range(8):
        adb('shell', 'input', 'keyevent', 'KEYCODE_DEL')
    adb('shell', 'input', 'text', str(km))
    time.sleep(1)


def hide_keyboard():
    if 'mInputShown=true' in adb('shell', 'dumpsys', 'input_method'):
        adb('shell', 'input', 'keyevent', '4')
        time.sleep(1.5)


def capture(shot, locale):
    hide_keyboard()
    if f'{PKG}/' not in adb('shell', 'dumpsys', 'activity', 'activities').split('topResumedActivity', 1)[-1][:200]:
        sys.exit(f'{shot}/{locale}: на передньому плані не Garage24')
    # Сповіщення (напр. «Air raid alert» від Google) лізуть у кадр банером і значком у статус-барі, демо-режим їх не ховає
    if 'NotificationRecord(' in adb('shell', 'dumpsys', 'notification', '--noredact'):
        sys.exit(f'{shot}/{locale}: на емуляторі є сповіщення — шторка → «Clear all», і знову')
    subprocess.run(['node', CAPTURE, 'app', shot, '--locale', locale], check=True)


accept_km, handover_km = int(sys.argv[1]), int(sys.argv[2])
for locale in sys.argv[3:] or ['uk']:
    adb('shell', 'cmd', 'locale', 'set-app-locales', PKG, '--locales', LOCALES[locale])
    adb('shell', 'am', 'force-stop', PKG)
    adb('shell', 'monkey', '-p', PKG, '-c', 'android.intent.category.LAUNCHER', '1')
    time.sleep(15)  # вкладка «Таксі» з'являється, коли підвантажиться стан таксі
    tap(TAB_HOME)

    tap(CHOOSE_CAR)
    capture('vehicle-choose', locale)
    tap(PASSAT_ACCEPT)
    type_mileage(accept_km)
    capture('vehicle-accept', locale)
    tap(SHEET_PRIMARY, wait=9)  # тост «Скасувати» має зникнути
    capture('vehicle-mine', locale)

    tap(TAB_HOME)
    capture('home-with-car', locale)
    tap(HANDOVER)
    type_mileage(handover_km)
    capture('vehicle-handover', locale)
    tap(SHEET_PRIMARY, wait=1.5)
    capture('vehicle-handover-done', locale)
    time.sleep(8)
    # наступна мова — з більшим пробігом
    accept_km, handover_km = handover_km + 12, handover_km + 12 + (handover_km - accept_km)
