import puppeteer from 'puppeteer';
import { mkdir } from 'fs/promises';
import path from 'path';

const BASE = 'https://sinergi.markandeyabali.ac.id';
const DIR = './screenshots';
const VIEWPORT = { width: 1440, height: 900 };

async function delay(ms) {
    return new Promise(r => setTimeout(r, ms));
}

async function shot(page, name) {
    await delay(2500);
    await page.screenshot({ path: path.join(DIR, `${name}.png`), fullPage: true });
    console.log(`  ✓ ${name}.png`);
}

async function loginAdmin(page, email, password) {
    await page.goto(`${BASE}/admin`, { waitUntil: 'networkidle2', timeout: 20000 });
    await delay(1000);
    // Clear and fill using keyboard
    await page.focus('input[name="email"]');
    await page.keyboard.down('Meta');
    await page.keyboard.press('a');
    await page.keyboard.up('Meta');
    await page.keyboard.type(email, { delay: 20 });

    await page.focus('input[name="password"]');
    await page.keyboard.down('Meta');
    await page.keyboard.press('a');
    await page.keyboard.up('Meta');
    await page.keyboard.type(password, { delay: 20 });

    await delay(500);
    await page.keyboard.press('Enter');
    await page.waitForNavigation({ waitUntil: 'networkidle2', timeout: 20000 }).catch(() => {});
    await delay(2000);
    console.log(`  → Redirected to: ${page.url()}`);
}

async function loginUser(page, emailOrNidn, password) {
    await page.goto(`${BASE}/login`, { waitUntil: 'networkidle2', timeout: 20000 });
    await delay(1000);

    await page.focus('input[name="email"]');
    await page.keyboard.down('Meta');
    await page.keyboard.press('a');
    await page.keyboard.up('Meta');
    await page.keyboard.type(emailOrNidn, { delay: 20 });

    await page.focus('input[name="password"]');
    await page.keyboard.down('Meta');
    await page.keyboard.press('a');
    await page.keyboard.up('Meta');
    await page.keyboard.type(password, { delay: 20 });

    await delay(500);
    await page.keyboard.press('Enter');
    await page.waitForNavigation({ waitUntil: 'networkidle2', timeout: 20000 }).catch(() => {});
    await delay(2000);
    console.log(`  → Redirected to: ${page.url()}`);
}

async function logout(page) {
    try {
        const found = await page.evaluate(() => {
            const links = [...document.querySelectorAll('form')];
            for (const f of links) {
                if (f.action && f.action.includes('logout')) {
                    f.submit();
                    return true;
                }
            }
            // Try button approach
            const btns = [...document.querySelectorAll('button, a')];
            for (const b of btns) {
                if (b.textContent && b.textContent.toLowerCase().includes('logout')) {
                    b.click();
                    return true;
                }
            }
            return false;
        });
        if (found) {
            await page.waitForNavigation({ waitUntil: 'networkidle2', timeout: 10000 }).catch(() => {});
        }
        await delay(2000);
        // Clear cookies as fallback
        const client = await page.createCDPSession();
        await client.send('Network.clearBrowserCookies');
        await delay(500);
    } catch (e) {
        // Force clear cookies
        const client = await page.createCDPSession();
        await client.send('Network.clearBrowserCookies');
    }
    console.log('  → Logged out');
}

async function safeGo(page, url) {
    try {
        await page.goto(url, { waitUntil: 'networkidle2', timeout: 20000 });
    } catch (e) {
        console.log(`    ⚠ Timeout on ${url}`);
    }
}

(async () => {
    await mkdir(DIR, { recursive: true });

    const browser = await puppeteer.launch({
        headless: true,
        defaultViewport: VIEWPORT,
        args: ['--no-sandbox', '--disable-setuid-sandbox'],
    });

    const page = await browser.newPage();

    // ============================================
    // 1. PUBLIC PAGES
    // ============================================
    console.log('\n📸 PUBLIC PAGES\n');

    await safeGo(page, BASE);
    await shot(page, '01_landing_page');

    await safeGo(page, `${BASE}/login`);
    await shot(page, '02_login_mahasiswa_dosen');

    await safeGo(page, `${BASE}/register`);
    await shot(page, '03_registrasi_mahasiswa');

    await safeGo(page, `${BASE}/admin`);
    await shot(page, '04_login_admin');

    // ============================================
    // 2. ADMIN PAGES
    // ============================================
    console.log('\n📸 ADMIN PAGES\n');

    await loginAdmin(page, 'admin@markandeya.ac.id', 'password');

    const adminPages = [
        ['admindashboard', '05_admin_dashboard'],
        ['tahun-akademik', '06_admin_tahun_akademik'],
        ['admin/peserta/kkn', '07_admin_peserta_kkn'],
        ['admin/peserta/ppl', '08_admin_peserta_ppl'],
        ['admin/peserta/pkl', '09_admin_peserta_pkl'],
        ['admin/peserta/magang', '10_admin_peserta_magang'],
        ['dosen', '11_admin_data_dosen'],
        ['dosen/create', '12_admin_tambah_dosen'],
        ['pembimbing-luar', '13_admin_pembimbing_luar'],
        ['pembimbing-luar/create', '14_admin_tambah_pembimbing_luar'],
        ['admin/mahasiswa/create', '15_admin_tambah_mahasiswa'],
        ['lokasikkn', '16_admin_lokasi_kkn'],
        ['lokasippl', '17_admin_lokasi_ppl'],
        ['lokasipkl', '18_admin_lokasi_pkl'],
        ['lokasimagang', '19_admin_lokasi_magang'],
        ['assign-lokasikkn', '20_admin_penempatan_kkn'],
        ['assign-lokasippl', '21_admin_penempatan_ppl'],
        ['assign-lokasipkl', '22_admin_penempatan_pkl'],
        ['assign-lokasimagang', '23_admin_penempatan_magang'],
        ['assign-dosenkkn', '24_admin_plot_dosen_kkn'],
        ['assign-dosenppl', '25_admin_plot_dosen_ppl'],
        ['assign-dosenpkl', '26_admin_plot_dosen_pkl'],
        ['assign-dosenmagang', '27_admin_plot_dosen_magang'],
        ['assign-dosenpenguji', '28_admin_plot_dosen_penguji'],
        ['assign-dosenpenilai', '29_admin_plot_penilai_publikasi'],
        ['assign-pembimbingluar-kkn', '30_admin_plot_pembluar_kkn'],
        ['assign-pembimbingluar-ppl', '31_admin_plot_pembluar_ppl'],
        ['assign-pembimbingluar-pkl', '32_admin_plot_pembluar_pkl'],
        ['assign-pembimbingluar-magang', '33_admin_plot_pembluar_magang'],
        ['pengajuan-pkladmin', '34_admin_persetujuan_pkl'],
        ['pengajuan-magangadmin', '35_admin_persetujuan_magang'],
        ['admin/kelola', '36_admin_kelola_admin'],
        ['change-password', '37_admin_ubah_password'],
    ];

    for (const [p, name] of adminPages) {
        await safeGo(page, `${BASE}/${p}`);
        await shot(page, name);
    }

    await logout(page);

    // ============================================
    // 3. MAHASISWA KKN (budi@gmail.com)
    // ============================================
    console.log('\n📸 MAHASISWA PAGES (KKN)\n');

    await loginUser(page, 'budi@gmail.com', 'password');

    const mhsPages = [
        ['dashboard', '38_mahasiswa_dashboard'],
        ['jurnal', '39_mahasiswa_jurnal'],
        ['jurnal/create', '40_mahasiswa_tambah_jurnal'],
        ['publikasi', '41_mahasiswa_publikasi'],
        ['publikasi/create', '42_mahasiswa_tambah_publikasi'],
        ['teman-selokasi', '43_mahasiswa_teman_selokasi'],
        ['change-password', '44_mahasiswa_ubah_password'],
    ];

    for (const [p, name] of mhsPages) {
        await safeGo(page, `${BASE}/${p}`);
        await shot(page, name);
    }

    await logout(page);

    // ============================================
    // 4. MAHASISWA PKL (andi@gmail.com) - pengajuan
    // ============================================
    console.log('\n📸 MAHASISWA PAGES (PKL)\n');

    await loginUser(page, 'andi@gmail.com', 'password');

    await safeGo(page, `${BASE}/dashboard`);
    await shot(page, '45_mahasiswa_pkl_dashboard');

    await safeGo(page, `${BASE}/pengajuan-pkl`);
    await shot(page, '46_mahasiswa_pengajuan_pkl');

    await logout(page);

    // ============================================
    // 5. MAHASISWA MAGANG (surya@gmail.com)
    // ============================================
    console.log('\n📸 MAHASISWA PAGES (Magang)\n');

    await loginUser(page, 'surya@gmail.com', 'password');

    await safeGo(page, `${BASE}/dashboard`);
    await shot(page, '47_mahasiswa_magang_dashboard');

    await safeGo(page, `${BASE}/pengajuan-magang`);
    await shot(page, '48_mahasiswa_pengajuan_magang');

    await logout(page);

    // ============================================
    // 6. DOSEN (1234567801 - pembimbing KKN + penguji PPL)
    // ============================================
    console.log('\n📸 DOSEN PAGES\n');

    await loginUser(page, '1234567801', 'password');

    const dosenPages = [
        ['dosen-pembimbing/dashboard', '49_dosen_dashboard'],
        ['dosen-pembimbing/bimbingan', '50_dosen_bimbingan'],
        ['dosen-pembimbing/mahasiswa/2026001', '51_dosen_detail_mhs_kkn'],
        ['dosen-pembimbing/ujian', '52_dosen_ujian'],
        ['dosen-pembimbing/ujian/2026003', '53_dosen_detail_ujian_ppl'],
        ['dosen-pembimbing/publikasi-penilaian', '54_dosen_penilaian_publikasi'],
        ['change-password', '55_dosen_ubah_password'],
    ];

    for (const [p, name] of dosenPages) {
        await safeGo(page, `${BASE}/${p}`);
        await shot(page, name);
    }

    await logout(page);

    // ============================================
    // 7. DOSEN 3 (pembimbing PKL + penguji Magang)
    // ============================================
    console.log('\n📸 DOSEN PAGES (PKL/Magang)\n');

    await loginUser(page, '1234567803', 'password');

    await safeGo(page, `${BASE}/dosen-pembimbing/mahasiswa/2026005`);
    await shot(page, '56_dosen_detail_mhs_pkl');

    await safeGo(page, `${BASE}/dosen-pembimbing/ujian/2026007`);
    await shot(page, '57_dosen_detail_ujian_magang');

    await safeGo(page, `${BASE}/dosen-pembimbing/publikasi-penilaian`);
    await shot(page, '58_dosen_publikasi_index_dosen3');

    await logout(page);

    // ============================================
    // 8. PEMBIMBING LUAR (pembimbing1@gmail.com - KKN)
    // ============================================
    console.log('\n📸 PEMBIMBING LUAR PAGES\n');

    await loginUser(page, 'pembimbing1@gmail.com', 'markandeyabali2026');

    const plPages = [
        ['pembimbing-luar/dashboard', '59_pembluar_dashboard'],
        ['pembimbing-luar/bimbingan', '60_pembluar_bimbingan'],
        ['pembimbing-luar/mahasiswa/2026001', '61_pembluar_detail_mhs_kkn'],
        ['change-password', '62_pembluar_ubah_password'],
    ];

    for (const [p, name] of plPages) {
        await safeGo(page, `${BASE}/${p}`);
        await shot(page, name);
    }

    await logout(page);

    // ============================================
    // 9. PEMBIMBING LUAR 2 (PKL/Magang)
    // ============================================
    console.log('\n📸 PEMBIMBING LUAR PAGES (PKL)\n');

    await loginUser(page, 'pembimbing2@gmail.com', 'markandeyabali2026');

    await safeGo(page, `${BASE}/pembimbing-luar/dashboard`);
    await shot(page, '63_pembluar_pkl_dashboard');

    await safeGo(page, `${BASE}/pembimbing-luar/mahasiswa/2026005`);
    await shot(page, '64_pembluar_detail_mhs_pkl');

    await safeGo(page, `${BASE}/pembimbing-luar/mahasiswa/2026007`);
    await shot(page, '65_pembluar_detail_mhs_magang');

    await logout(page);

    // ============================================
    console.log('\n✅ Semua screenshot selesai!');
    console.log(`📁 Folder: ${path.resolve(DIR)}/`);
    console.log(`📊 Total: 65 screenshot\n`);

    await browser.close();
})();
