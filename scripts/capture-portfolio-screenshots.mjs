import fs from 'node:fs/promises';
import path from 'node:path';
import process from 'node:process';
import puppeteer from 'puppeteer';

const OUTPUT_DIR = path.resolve(process.cwd(), 'public/images/portfolio');

const TARGETS = [
    {
        name: 'Carpathian AI SaaS Marketplace',
        url: 'https://chat.carphatian.ro',
        file: 'saas-marketplace.jpg',
    },
    {
        name: 'Demo Tools Portfolio',
        url: 'https://social.carphatian.ro',
        file: 'demo-tools.jpg',
    },
    {
        name: 'PDF Summary Generator',
        url: 'https://social.carphatian.ro',
        file: 'pdf-generator.jpg',
    },
    {
        name: 'ATMN - Antimony Coin Explorer',
        url: 'https://explorer.carphatian.ro',
        file: 'antimony-coin.jpg',
    },
    {
        name: 'Language Detection',
        url: 'https://antimony.carphatian.ro',
        file: 'language-detection.jpg',
    },
    {
        name: 'Carpathian CMS',
        url: 'https://cms.carphatian.ro',
        file: 'carpathian-cms.jpg',
    },
];

function sleep(ms) {
    return new Promise((resolve) => setTimeout(resolve, ms));
}

function log(msg) {
    process.stdout.write(`${msg}\n`);
}

async function ensureDir(dir) {
    await fs.mkdir(dir, { recursive: true });
}

async function fileExists(p) {
    try {
        await fs.access(p);
        return true;
    } catch {
        return false;
    }
}

async function safeReplace(tmpPath, finalPath) {
    // Only replace the final file if tmp looks sane.
    const stat = await fs.stat(tmpPath);
    if (stat.size < 15 * 1024) {
        throw new Error(`Screenshot too small (${stat.size} bytes) – refusing to overwrite ${path.basename(finalPath)}`);
    }

    await fs.rename(tmpPath, finalPath);
}

async function captureOne(browser, target) {
    const finalPath = path.join(OUTPUT_DIR, target.file);
    const tmpPath = `${finalPath}.tmp`;

    // Never destroy an existing image unless we have a good replacement.
    if (await fileExists(tmpPath)) {
        await fs.unlink(tmpPath);
    }

    const page = await browser.newPage();

    try {
        await page.setViewport({ width: 1200, height: 675, deviceScaleFactor: 1 });
        await page.setUserAgent(
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        );
        await page.setExtraHTTPHeaders({ 'Accept-Language': 'en-US,en;q=0.9' });

        // Some apps keep long-polling/WebSockets; don't block forever waiting for network idle.
        const response = await page.goto(target.url, {
            waitUntil: 'domcontentloaded',
            timeout: 60_000,
        });

        const status = response?.status?.() ?? 0;
        if (status >= 400) {
            throw new Error(`HTTP ${status}`);
        }

        // Allow client-side rendering, hydration, and fonts.
        await sleep(2500);

        await page.screenshot({
            path: tmpPath,
            type: 'jpeg',
            quality: 82,
            fullPage: false,
            captureBeyondViewport: false,
        });

        await safeReplace(tmpPath, finalPath);
        const newStat = await fs.stat(finalPath);
        log(`✅ ${target.name}: saved ${target.file} (${Math.round(newStat.size / 1024)} KB)`);
    } finally {
        await page.close();
    }
}

async function main() {
    await ensureDir(OUTPUT_DIR);

    const browser = await puppeteer.launch({
        headless: true,
        args: [
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-dev-shm-usage',
            '--disable-gpu',
            '--hide-scrollbars',
        ],
    });

    let ok = 0;
    let fail = 0;

    try {
        for (const target of TARGETS) {
            try {
                await captureOne(browser, target);
                ok += 1;
            } catch (e) {
                fail += 1;
                log(`❌ ${target.name}: ${e instanceof Error ? e.message : String(e)}`);
            }
        }
    } finally {
        await browser.close();
    }

    log(`\nDone. Success: ${ok}, Failed: ${fail}`);
    process.exitCode = fail > 0 ? 1 : 0;
}

await main();
