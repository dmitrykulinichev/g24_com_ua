#!/usr/bin/env node
/*
 * Скріни лендінгу трьома мовами за реєстром www/content/screens.json (app/Screens, app/I18n/README.md).
 *
 *   node capture.mjs app <кадр> --locale en     сирий кадр з Android-емулятора (adb) → src/en/app/<кадр>.png
 *   node capture.mjs compose [id…]              сирі кадри → рамка телефона → www/assets/img/screens/<мова>/<id>.webp
 *   node capture.mjs cabinet [id…] [--locale en] [--headless]  скрін вебкабінету (бойовий, CABINET_URL) кожною мовою → webp;
 *                                               за замовчуванням у видимому вікні — людина бачить, де ходить скрипт
 *   node capture.mjs login                      відкрити браузер профілю знімання, щоб один раз увійти в кабінет
 *   node capture.mjs status                     які скріни якими мовами знято й складено
 *
 * Сирі кадри лежать у src/<мова>/ (у git): перескласти скрін з іншою рамкою — без перезнімання.
 * Скрін застосунку в реєстрі: source.kind = "app", source.shots = ["кадр", …] — 1 кадр = один телефон, 3 = три поруч.
 * Скрін кабінету: source.kind = "cabinet", url, park_id (активний парк), viewport [ширина, висота] у 2x.
 * Мова — та сама, що обирає перемикач кабінету (localStorage i18nextLng); вхід — профіль Chrome CHROME_PROFILE.
 * Знімати всі мови за один захід — однакові дані в кадрі.
 */
import { execFileSync } from 'node:child_process';
import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import puppeteer from 'puppeteer-core';

const here = path.dirname(fileURLToPath(import.meta.url));
const www = path.resolve(here, '../../www');
const srcDir = path.join(here, 'src');
const registryFile = JSON.parse(fs.readFileSync(path.join(www, 'content/screens.json'), 'utf8'));
const registry = registryFile.screens;
// Назви інших парків акаунта знімання — розмити на кожному скріні кабінету
const BLUR_TEXT = registryFile.cabinet_blur_text ?? [];
const LOCALES = ['uk', 'en', 'ru'];
const ADB = process.env.ADB ?? path.join(os.homedir(), 'Library/Android/sdk/platform-tools/adb');
const CHROME = process.env.CHROME ?? '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome';
const CABINET_URL = (process.env.CABINET_URL ?? 'https://app.g24.com.ua').replace(/\/$/, '');
const CHROME_PROFILE = process.env.CHROME_PROFILE ?? path.join(here, '.chrome-profile');
// Cookie сесії кабінету — без терміну дії, Chrome стирає їх при закритті вікна; тому `login` зберігає їх сюди,
// а знімання підставляє (файл у .gitignore — це вхід у кабінет)
const SESSION_FILE = path.join(here, '.cabinet-session.json');
// Хостинг кабінету не пускає «HeadlessChrome» (403) — представляємось звичайним Chrome
const USER_AGENT = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36';

// Рамка телефона в CSS-пікселях; складається у 2x. Розміри — як у перших скрінах головної.
const PHONE = { width: 329, height: 708, bezel: 10, radius: 30, gap: 49, padX: 72, padY: 56, canvasHeight: 821 };

const [command, ...rest] = process.argv.slice(2);
const option = (name) => {
    const i = rest.indexOf(`--${name}`);
    return i === -1 ? undefined : rest.splice(i, 2)[1];
};

const rawPath = (locale, kind, shot) => path.join(srcDir, locale, kind, `${shot}.png`);
const outPath = (locale, id) => path.join(www, 'assets/img/screens', locale, `${id}.webp`);

function captureApp(shot, locale) {
    if (!shot || !LOCALES.includes(locale)) throw new Error('node capture.mjs app <кадр> --locale uk|en|ru');
    const file = rawPath(locale, 'app', shot);
    fs.mkdirSync(path.dirname(file), { recursive: true });
    // Демо-режим статус-бару: 12:00, повний заряд і сигнал — однаково в кожному кадрі
    const demo = (cmd, extra = []) => execFileSync(ADB, ['shell', 'am', 'broadcast', '-a', 'com.android.systemui.demo', '-e', 'command', cmd, ...extra]);
    execFileSync(ADB, ['shell', 'settings', 'put', 'global', 'sysui_demo_allowed', '1']);
    demo('enter');
    demo('clock', ['-e', 'hhmm', '1200']);
    demo('battery', ['-e', 'level', '100', '-e', 'plugged', 'false']);
    demo('network', ['-e', 'wifi', 'show', '-e', 'level', '4', '-e', 'mobile', 'hide']);
    demo('notifications', ['-e', 'visible', 'false']);
    fs.writeFileSync(file, execFileSync(ADB, ['exec-out', 'screencap', '-p'], { maxBuffer: 64 * 1024 * 1024 }));
    console.log(`✓ ${path.relative(here, file)}`);
}

function phoneHtml(shots) {
    const { width, height, bezel, radius, gap, padX, padY, canvasHeight } = PHONE;
    const canvasWidth = padX * 2 + shots.length * width + (shots.length - 1) * gap;
    const phones = shots.map((png) => `
        <div class="phone"><img src="data:image/png;base64,${fs.readFileSync(png).toString('base64')}"></div>`).join('');

    return {
        width: canvasWidth,
        height: canvasHeight,
        html: `<!doctype html><html><head><style>
            * { margin: 0; box-sizing: border-box; }
            body { width: ${canvasWidth}px; height: ${canvasHeight}px; display: flex; gap: ${gap}px; padding: ${padY}px ${padX}px;
                   background: radial-gradient(120% 90% at 50% 40%, #f7f8fc 0%, #eef1f8 60%, #e8ecf6 100%); }
            .phone { width: ${width}px; height: ${height}px; padding: ${bezel}px; border-radius: ${radius}px; background: #1f2127;
                     box-shadow: 0 30px 60px -20px rgba(30, 41, 80, .28), 0 12px 24px -12px rgba(30, 41, 80, .18); }
            .phone img { display: block; width: 100%; height: 100%; object-fit: cover; object-position: top; border-radius: ${radius - bezel}px; }
        </style></head><body>${phones}</body></html>`,
    };
}

async function compose(ids) {
    const browser = await puppeteer.launch({ executablePath: CHROME, headless: true });
    try {
        for (const id of ids.length ? ids : Object.keys(registry)) {
            const source = registry[id]?.source;
            if (!source) throw new Error(`«${id}» немає в www/content/screens.json`);
            if (source.kind !== 'app') continue;
            for (const locale of LOCALES) {
                const shots = source.shots.map((shot) => rawPath(locale, 'app', shot));
                const missing = shots.filter((f) => !fs.existsSync(f));
                if (missing.length) {
                    if (missing.length < shots.length) console.log(`  ${id} ${locale}: бракує ${missing.map((f) => path.basename(f)).join(', ')}`);
                    continue;
                }
                const { width, height, html } = phoneHtml(shots);
                const page = await browser.newPage();
                await page.setViewport({ width, height, deviceScaleFactor: 2 });
                await page.setContent(html, { waitUntil: 'load' });
                const png = path.join(os.tmpdir(), `screen-${id}-${locale}.png`);
                await page.screenshot({ path: png });
                await page.close();
                const out = outPath(locale, id);
                fs.mkdirSync(path.dirname(out), { recursive: true });
                execFileSync('cwebp', ['-quiet', '-q', '85', png, '-o', out]);
                fs.rmSync(png);
                console.log(`✓ ${path.relative(www, out)} (${width * 2}×${height * 2})`);
            }
        }
    } finally {
        await browser.close();
    }
}

async function captureCabinet(ids, onlyLocale, show = false) {
    const targets = (ids.length ? ids : Object.keys(registry)).filter((id) => registry[id]?.source?.kind === 'cabinet');
    if (!targets.length) throw new Error('у реєстрі немає таких скрінів кабінету');
    const browser = await puppeteer.launch({ executablePath: CHROME, headless: !show, userDataDir: CHROME_PROFILE });
    try {
        const page = await browser.newPage();
        await page.setUserAgent(USER_AGENT);
        if (fs.existsSync(SESSION_FILE)) {
            await page.setCookie(...JSON.parse(fs.readFileSync(SESSION_FILE, 'utf8')));
        }
        for (const id of targets) {
            const { source, viewport } = registry[id];
            const [width, height] = viewport;
            await page.setViewport({ width, height, deviceScaleFactor: 2 });
            for (const locale of onlyLocale ? [onlyLocale] : LOCALES) {
                await page.goto(CABINET_URL + '/', { waitUntil: 'networkidle2', timeout: 60000 });
                // Без сесії кабінет показує форму входу на будь-якій адресі — тоді не знімати (інакше форма ляже в скрін)
                if (await page.$('input[type="password"]')) throw new Error('профіль не увійшов у кабінет: node capture.mjs login');
                // Мова, парк і налаштування браузера з реєстру (local_storage) — лише в профілі знімання, не в акаунті
                await page.evaluate((l, parkId, extra) => {
                    localStorage.setItem('i18nextLng', l);
                    if (parkId) localStorage.setItem('active_park_id', String(parkId));
                    for (const [key, value] of Object.entries(extra)) {
                        localStorage.setItem(key, typeof value === 'string' ? value : JSON.stringify(value));
                    }
                }, locale, source.park_id ?? null, source.local_storage ?? {});
                await page.goto(CABINET_URL + source.url, { waitUntil: 'networkidle2', timeout: 60000 });
                // Сторінка з usePageReady сама скаже, що дані завантажені; графікам — ще мить на анімацію
                await page.waitForSelector('html[data-page-ready="true"]', { timeout: 15000 }).catch(() => {});
                // Кроки сценарію: лише перегляд (фільтри, вкладки) — нічого, що змінює дані
                for (const selector of source.click ?? []) {
                    await page.waitForSelector(selector, { timeout: 15000 });
                    await page.click(selector);
                    await page.waitForNetworkIdle({ idleTime: 800, timeout: 30000 }).catch(() => {});
                }
                await new Promise((r) => setTimeout(r, 2500));
                // Кадр — з початку сторінки: деякі сторінки після завантаження самі прокручують таблицю
                await page.evaluate(() => {
                    window.scrollTo(0, 0);
                    document.querySelectorAll('*').forEach((el) => { if (el.scrollTop > 0) el.scrollTop = 0; });
                });
                await new Promise((r) => setTimeout(r, 300));
                if (await page.$('input[type="password"]')) throw new Error(`${id} ${locale}: замість сторінки — форма входу`);
                await page.evaluate((texts) => {
                    const wanted = texts.map((t) => t.toLowerCase());
                    const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT);
                    for (let node = walker.nextNode(); node; node = walker.nextNode()) {
                        if (wanted.includes(node.textContent.trim().toLowerCase())) node.parentElement.style.filter = 'blur(6px)';
                    }
                }, BLUR_TEXT);
                const png = path.join(os.tmpdir(), `screen-${id}-${locale}.png`);
                await page.screenshot({ path: png });
                const out = outPath(locale, id);
                fs.mkdirSync(path.dirname(out), { recursive: true });
                execFileSync('cwebp', ['-quiet', '-q', '85', png, '-o', out]);
                fs.rmSync(png);
                console.log(`✓ ${path.relative(www, out)} (${width * 2}×${height * 2})`);
            }
        }
    } finally {
        await browser.close();
    }
}

/**
 * Вікно входу: людина входить сама; щойно з'явився кабінет — cookie сесії зберігаються у файл. Вікно скрипт не
 * закриває ніколи — людина закриває його сама, коли захоче (знімання бере сесію з файлу, вікно йому не потрібне).
 */
async function login() {
    const browser = await puppeteer.launch({ executablePath: CHROME, headless: false, userDataDir: CHROME_PROFILE, defaultViewport: null });
    const [page] = await browser.pages();
    await page.setUserAgent(USER_AGENT);
    await page.goto(CABINET_URL + '/login');
    console.log('Увійдіть у кабінет у вікні браузера. Вікно можна не закривати — сесію буде збережено.');
    for (;;) {
        await new Promise((r) => setTimeout(r, 2000));
        const loggedIn = await page.evaluate(() => !document.querySelector('input[type="password"]') && !!document.querySelector('nav, aside'))
            .catch(() => false);
        if (loggedIn) break;
    }
    const cookies = (await page.cookies(CABINET_URL)).map(({ name, value, domain, path: p, secure, httpOnly, sameSite }) => ({ name, value, domain, path: p, secure, httpOnly, sameSite }));
    fs.writeFileSync(SESSION_FILE, JSON.stringify(cookies), { mode: 0o600 });
    console.log(`✓ сесію збережено (${cookies.length} cookie) — вікно лишається відкритим`);
    await new Promise((r) => browser.on('disconnected', r));
}

function status() {
    for (const [id, { source }] of Object.entries(registry)) {
        const row = LOCALES.map((locale) => {
            const out = fs.existsSync(outPath(locale, id)) ? '✓' : '·';
            const raw = source.kind === 'app' ? source.shots.filter((s) => fs.existsSync(rawPath(locale, 'app', s))).length + '/' + source.shots.length : '';
            return `${locale} ${out}${raw && ` (кадри ${raw})`}`;
        });
        console.log(`${id.padEnd(20)} ${row.join('   ')}`);
    }
}

switch (command) {
    case 'app': captureApp(rest[0], option('locale')); break;
    case 'compose': await compose(rest); break;
    case 'cabinet': {
        const show = !rest.includes('--headless');
        const locale = option('locale');
        await captureCabinet(rest.filter((a) => a !== '--headless' && a !== '--show'), locale, show);
        break;
    }
    case 'login': await login(); break;
    case 'status': status(); break;
    default: console.log(fs.readFileSync(fileURLToPath(import.meta.url), 'utf8').split('*/')[0]);
}
