import puppeteer from 'puppeteer';
import { mkdir } from 'fs/promises';
import path from 'path';

const BASE = 'https://sinergi.markandeyabali.ac.id';
const DIR = './screenshots';
const VP = { width: 1440, height: 900 };
const delay = ms => new Promise(r => setTimeout(r, ms));

async function shot(page, name) {
    await delay(3000);
    await page.screenshot({ path: path.join(DIR, `${name}.png`), fullPage: true });
    console.log(`  ✓ ${name}.png`);
}

async function loginAdmin(browser, email, pw) {
    const page = await browser.newPage();
    await page.goto(`${BASE}/admin`, { waitUntil: 'networkidle2', timeout: 20000 });
    await delay(500);
    await page.click('input[name="email"]');
    await page.type('input[name="email"]', email, { delay: 5 });
    await page.click('input[name="password"]');
    await page.type('input[name="password"]', pw, { delay: 5 });
    await delay(200);
    await page.keyboard.press('Enter');
    await page.waitForNavigation({ waitUntil: 'networkidle2', timeout: 20000 }).catch(() => {});
    await delay(2000);
    console.log(`  → ${page.url()}`);
    return page;
}

async function loginUser(browser, id, pw) {
    const page = await browser.newPage();
    await page.goto(`${BASE}/login`, { waitUntil: 'networkidle2', timeout: 20000 });
    await delay(500);
    await page.click('input[name="email"]');
    await page.type('input[name="email"]', id, { delay: 5 });
    await page.click('input[name="password"]');
    await page.type('input[name="password"]', pw, { delay: 5 });
    await delay(200);
    await page.keyboard.press('Enter');
    await page.waitForNavigation({ waitUntil: 'networkidle2', timeout: 20000 }).catch(() => {});
    await delay(2000);
    console.log(`  → ${page.url()}`);
    return page;
}

async function go(page, url) {
    try { await page.goto(url, { waitUntil: 'networkidle2', timeout: 20000 }); }
    catch { console.log(`    ⚠ timeout`); }
}

(async () => {
    await mkdir(DIR, { recursive: true });
    const browser = await puppeteer.launch({ headless: true, defaultViewport: VP, args: ['--no-sandbox'] });
    let page;

    // ── SUPER ADMIN ──
    console.log('\n🔵 SUPER ADMIN');
    page = await loginAdmin(browser, 'admin@markandeya.ac.id', 'password');
    await go(page, `${BASE}/admindashboard`);
    await shot(page, '01_superadmin_dashboard');
    await page.close();

    // ── ADMIN KKN ──
    console.log('\n🔵 ADMIN KKN');
    page = await loginAdmin(browser, 'adminkkn@markandeya.ac.id', 'password');
    await go(page, `${BASE}/admindashboard`);
    await shot(page, '02_adminkkn_dashboard');
    await page.close();

    // ── MAHASISWA KKN ──
    console.log('\n🟢 MAHASISWA KKN');
    page = await loginUser(browser, 'budi@gmail.com', 'password');
    await go(page, `${BASE}/dashboard`);
    await shot(page, '03_mahasiswa_kkn_dashboard');
    await page.close();

    // ── MAHASISWA PPL ──
    console.log('\n🟢 MAHASISWA PPL');
    page = await loginUser(browser, 'siti@gmail.com', 'password');
    await go(page, `${BASE}/dashboard`);
    await shot(page, '04_mahasiswa_ppl_dashboard');
    await page.close();

    // ── MAHASISWA PKL ──
    console.log('\n🟢 MAHASISWA PKL');
    page = await loginUser(browser, 'andi@gmail.com', 'password');
    await go(page, `${BASE}/dashboard`);
    await shot(page, '05_mahasiswa_pkl_dashboard');
    await page.close();

    // ── MAHASISWA MAGANG ──
    console.log('\n🟢 MAHASISWA MAGANG');
    page = await loginUser(browser, 'surya@gmail.com', 'password');
    await go(page, `${BASE}/dashboard`);
    await shot(page, '06_mahasiswa_magang_dashboard');
    await page.close();

    // ── DOSEN 1 ──
    console.log('\n🟣 DOSEN 1 - Pembimbing KKN, Penguji PPL');
    page = await loginUser(browser, '1234567801', 'password');
    await go(page, `${BASE}/dosen-pembimbing/dashboard`);
    await shot(page, '07_dosen1_dashboard');
    await go(page, `${BASE}/dosen-pembimbing/mahasiswa/2026001`);
    await shot(page, '08_dosen1_penilaian_bimbingan_kkn');
    await go(page, `${BASE}/dosen-pembimbing/ujian/2026003`);
    await shot(page, '09_dosen1_penilaian_ujian_ppl');
    await page.close();

    // ── DOSEN 2 ──
    console.log('\n🟣 DOSEN 2 - Pembimbing PPL, Penguji KKN');
    page = await loginUser(browser, '1234567802', 'password');
    await go(page, `${BASE}/dosen-pembimbing/dashboard`);
    await shot(page, '10_dosen2_dashboard');
    await go(page, `${BASE}/dosen-pembimbing/mahasiswa/2026003`);
    await shot(page, '11_dosen2_penilaian_bimbingan_ppl');
    await go(page, `${BASE}/dosen-pembimbing/ujian/2026001`);
    await shot(page, '12_dosen2_penilaian_ujian_kkn');
    await go(page, `${BASE}/dosen-pembimbing/publikasi-penilaian/2026007`);
    await shot(page, '13_dosen2_penilaian_publikasi_magang');
    await page.close();

    // ── DOSEN 3 ──
    console.log('\n🟣 DOSEN 3 - Pembimbing PKL, Penguji Magang');
    page = await loginUser(browser, '1234567803', 'password');
    await go(page, `${BASE}/dosen-pembimbing/dashboard`);
    await shot(page, '14_dosen3_dashboard');
    await go(page, `${BASE}/dosen-pembimbing/mahasiswa/2026005`);
    await shot(page, '15_dosen3_penilaian_bimbingan_pkl');
    await go(page, `${BASE}/dosen-pembimbing/ujian/2026007`);
    await shot(page, '16_dosen3_penilaian_ujian_magang');
    await go(page, `${BASE}/dosen-pembimbing/publikasi-penilaian/2026001`);
    await shot(page, '17_dosen3_penilaian_publikasi_kkn');
    await page.close();

    // ── DOSEN 4 ──
    console.log('\n🟣 DOSEN 4 - Pembimbing Magang, Penguji PKL');
    page = await loginUser(browser, '1234567804', 'password');
    await go(page, `${BASE}/dosen-pembimbing/dashboard`);
    await shot(page, '18_dosen4_dashboard');
    await go(page, `${BASE}/dosen-pembimbing/mahasiswa/2026007`);
    await shot(page, '19_dosen4_penilaian_bimbingan_magang');
    await go(page, `${BASE}/dosen-pembimbing/ujian/2026005`);
    await shot(page, '20_dosen4_penilaian_ujian_pkl');
    await page.close();

    // ── PEMBIMBING LUAR 1 (KKN) ──
    console.log('\n🟠 PEMBIMBING LUAR 1 - KKN');
    page = await loginUser(browser, 'pembimbing1@gmail.com', 'markandeyabali2026');
    await go(page, `${BASE}/pembimbing-luar/dashboard`);
    await shot(page, '21_pembluar1_dashboard');
    await go(page, `${BASE}/pembimbing-luar/mahasiswa/2026001`);
    await shot(page, '22_pembluar1_penilaian_kkn');
    await page.close();

    // ── PEMBIMBING LUAR 2 (PKL/Magang) ──
    console.log('\n🟠 PEMBIMBING LUAR 2 - PKL/Magang');
    page = await loginUser(browser, 'pembimbing2@gmail.com', 'markandeyabali2026');
    await go(page, `${BASE}/pembimbing-luar/dashboard`);
    await shot(page, '23_pembluar2_dashboard');
    await go(page, `${BASE}/pembimbing-luar/mahasiswa/2026005`);
    await shot(page, '24_pembluar2_penilaian_pkl');
    await go(page, `${BASE}/pembimbing-luar/mahasiswa/2026007`);
    await shot(page, '25_pembluar2_penilaian_magang');
    await page.close();

    // ── PEMBIMBING LUAR 3 (PPL) ──
    console.log('\n🟠 PEMBIMBING LUAR 3 - PPL');
    page = await loginUser(browser, 'pembimbing3@gmail.com', 'markandeyabali2026');
    await go(page, `${BASE}/pembimbing-luar/dashboard`);
    await shot(page, '26_pembluar3_dashboard');
    await go(page, `${BASE}/pembimbing-luar/mahasiswa/2026003`);
    await shot(page, '27_pembluar3_penilaian_ppl');
    await page.close();

    console.log('\n✅ Selesai! 27 screenshot di ./screenshots/');
    await browser.close();
})();
