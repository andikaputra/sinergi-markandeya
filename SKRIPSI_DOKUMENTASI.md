# DOKUMENTASI SKRIPSI

# PERANCANGAN DAN IMPLEMENTASI SISTEM INFORMASI MANAJEMEN KEGIATAN LAPANGAN MAHASISWA BERBASIS WEB PADA INSTITUT TEKNOLOGI DAN PENDIDIKAN MARKANDEYA BALI

---

# BAB I - PENDAHULUAN

## 1.1 Latar Belakang

Institut Teknologi dan Pendidikan (ITP) Markandeya Bali menyelenggarakan empat program kegiatan lapangan wajib bagi mahasiswa, yaitu Kuliah Kerja Nyata (KKN), Praktik Pengalaman Lapangan (PPL), Praktik Kerja Lapangan (PKL), dan Magang. Keempat program ini merupakan bagian integral dari kurikulum yang bertujuan membekali mahasiswa dengan pengalaman praktis di dunia kerja dan masyarakat.

Dalam pelaksanaannya, pengelolaan kegiatan lapangan ini melibatkan berbagai proses administratif yang kompleks, meliputi pendaftaran mahasiswa peserta, penempatan lokasi kegiatan, penunjukan dosen pembimbing, penunjukan dosen penguji, pencatatan jurnal harian kegiatan, manajemen publikasi hasil kegiatan, penilaian oleh dosen pembimbing dan penguji, serta pelaporan hasil kegiatan. Seluruh proses tersebut hingga saat ini masih dilakukan secara manual atau semi-manual menggunakan spreadsheet dan dokumen fisik.

Permasalahan yang muncul dari sistem manual tersebut antara lain:

1. **Inefisiensi waktu** - Proses plotting dosen pembimbing dan penguji untuk ratusan mahasiswa membutuhkan waktu yang lama karena dilakukan satu per satu secara manual.
2. **Kesulitan pemantauan** - Dosen pembimbing kesulitan memantau progres jurnal harian dan publikasi mahasiswa bimbingannya karena data tersebar di berbagai platform.
3. **Redundansi data** - Data mahasiswa seringkali diinput berulang di berbagai dokumen yang berbeda untuk keperluan yang berbeda (pendaftaran, penempatan, penilaian).
4. **Kesulitan pelaporan** - Pembuatan rekap data peserta per program studi, per lokasi, atau per tahun akademik memerlukan pengolahan data manual yang rawan kesalahan.
5. **Keterbatasan akses** - Informasi penempatan lokasi dan dosen pembimbing tidak dapat diakses secara real-time oleh mahasiswa.
6. **Tidak adanya sistem role-based access** - Setiap admin mengelola semua kegiatan tanpa pembatasan, sehingga rawan terjadi kesalahan pengelolaan data.

Berdasarkan permasalahan tersebut, diperlukan sebuah sistem informasi terintegrasi yang mampu mengelola seluruh proses kegiatan lapangan mahasiswa secara efisien, akurat, dan dapat diakses oleh seluruh pemangku kepentingan (mahasiswa, dosen, dan admin) melalui platform berbasis web.

Sistem yang dikembangkan diberi nama **"Sinergi Markandeya"** yang merupakan platform manajemen kegiatan lapangan mahasiswa berbasis web menggunakan framework Laravel 12 dengan pendekatan multi-guard authentication dan role-based access control.

## 1.2 Rumusan Masalah

Berdasarkan latar belakang yang telah diuraikan, rumusan masalah dalam penelitian ini adalah:

1. Bagaimana merancang dan mengimplementasikan sistem informasi manajemen kegiatan lapangan mahasiswa yang terintegrasi untuk program KKN, PPL, PKL, dan Magang?
2. Bagaimana UAT dari pengguna terhadap sistem?

## 1.3 Batasan Masalah

Agar penelitian ini terarah dan terfokus, maka ditetapkan batasan masalah sebagai berikut:

1. Sistem dikembangkan khusus untuk pengelolaan empat jenis kegiatan lapangan: KKN, PPL, PKL, dan Magang di ITP Markandeya Bali.
2. Sistem mencakup tiga peran pengguna utama: Admin (Super Admin dan Admin terbatas), Dosen (Pembimbing dan Penguji), serta Mahasiswa.
3. Sistem berbasis web dan diakses melalui browser tanpa memerlukan instalasi aplikasi tambahan.
4. Sistem menggunakan framework Laravel 12 sebagai backend dan Tailwind CSS sebagai frontend.
5. Database yang digunakan adalah database relasional (MySQL/SQLite).
6. Sistem tidak mencakup fitur pembayaran online atau integrasi dengan sistem keuangan kampus.
7. Sistem tidak mencakup fitur video conference atau meeting online untuk bimbingan.

## 1.4 Tujuan Penelitian

Tujuan dari penelitian ini adalah:

1. Merancang dan mengimplementasikan sistem informasi manajemen kegiatan lapangan mahasiswa berbasis web yang terintegrasi untuk program KKN, PPL, PKL, dan Magang.
2. Mengimplementasikan sistem autentikasi multi-guard yang memisahkan akses tiga peran pengguna secara aman dan terstruktur.
3. Mengimplementasikan role-based access control pada level admin dengan pembatasan berdasarkan jenis kegiatan yang dikelola.
4. Menyediakan fitur plotting dosen pembimbing dan penguji yang efisien dengan dukungan import data CSV.
5. Menyediakan fitur pencatatan jurnal harian, manajemen publikasi, dan pelaporan data yang komprehensif.
6. Menghasilkan sistem yang responsif dan dapat diakses melalui berbagai perangkat (desktop, tablet, dan mobile).

## 1.5 Manfaat Penelitian

### 1.5.1 Manfaat Teoritis
1. Memberikan kontribusi dalam pengembangan ilmu pengetahuan di bidang sistem informasi, khususnya dalam penerapan multi-guard authentication pada framework Laravel.
2. Menjadi referensi bagi penelitian selanjutnya yang berkaitan dengan pengembangan sistem manajemen kegiatan lapangan berbasis web.

### 1.5.2 Manfaat Praktis
1. **Bagi Institut** - Mempermudah proses administrasi pengelolaan kegiatan lapangan mahasiswa, mengurangi penggunaan dokumen fisik, dan meningkatkan efisiensi kerja staf administrasi.
2. **Bagi Dosen** - Mempermudah pemantauan progres mahasiswa bimbingan, akses jurnal harian dan publikasi, serta proses penilaian secara terpusat.
3. **Bagi Mahasiswa** - Mempermudah akses informasi penempatan, pencatatan jurnal harian, pengelolaan publikasi, dan pengajuan lokasi kegiatan secara online.
4. **Bagi Admin** - Mempermudah proses plotting dosen, pengelolaan lokasi, import/export data, dan pelaporan dengan pembatasan akses sesuai tanggung jawab.

## 1.6 Sistematika Penulisan

Sistematika penulisan skripsi ini terdiri dari lima bab, yaitu:

**BAB I PENDAHULUAN** - Berisi latar belakang masalah, rumusan masalah, batasan masalah, tujuan penelitian, manfaat penelitian, dan sistematika penulisan.

**BAB II TINJAUAN PUSTAKA** - Berisi landasan teori yang mendukung penelitian, meliputi konsep sistem informasi, framework Laravel, Tailwind CSS, multi-guard authentication, role-based access control, serta penelitian terdahulu yang relevan.

**BAB III METODOLOGI PENELITIAN** - Berisi metode penelitian yang digunakan, analisis kebutuhan sistem, perancangan sistem meliputi use case diagram, activity diagram, entity relationship diagram, dan perancangan antarmuka.

**BAB IV HASIL DAN PEMBAHASAN** - Berisi implementasi sistem berdasarkan perancangan yang telah dibuat, pengujian sistem menggunakan metode black-box testing, dan pembahasan hasil implementasi.

**BAB V PENUTUP** - Berisi kesimpulan dari hasil penelitian dan saran untuk pengembangan sistem di masa depan.

---

# BAB II - TINJAUAN PUSTAKA

## 2.1 Penelitian Terdahulu

Beberapa penelitian terdahulu yang relevan dengan penelitian ini antara lain:

1. **Sistem Informasi Manajemen KKN Berbasis Web** - Penelitian terdahulu menunjukkan bahwa digitalisasi proses KKN dapat meningkatkan efisiensi administrasi hingga 60% dibandingkan proses manual. Namun, sistem yang dikembangkan umumnya hanya mencakup satu jenis kegiatan (KKN saja) dan belum terintegrasi dengan kegiatan lapangan lainnya.

2. **Implementasi Multi-Authentication pada Laravel** - Penelitian tentang implementasi multi-guard authentication pada Laravel menunjukkan bahwa pendekatan ini efektif untuk memisahkan akses pengguna dengan peran yang berbeda tanpa mengorbankan keamanan sistem. Perbedaan dengan penelitian ini terletak pada penambahan lapisan role-based access control di dalam guard admin.

3. **Sistem Penempatan dan Penilaian PKL/Magang** - Sistem serupa telah dikembangkan di beberapa institusi pendidikan, namun umumnya berdiri sendiri (standalone) dan tidak terintegrasi dengan sistem kegiatan lapangan lainnya seperti KKN dan PPL.

Perbedaan utama penelitian ini dengan penelitian terdahulu adalah **integrasi empat jenis kegiatan lapangan** (KKN, PPL, PKL, Magang) dalam **satu platform terpadu** dengan **tiga level autentikasi** (Admin, Dosen, Mahasiswa) dan **role-based access control** pada level admin.

## 2.2 Landasan Teori

### 2.2.1 Sistem Informasi

Sistem informasi adalah suatu sistem di dalam suatu organisasi yang mempertemukan kebutuhan pengolahan transaksi harian, mendukung operasi, bersifat manajerial dan kegiatan strategi dari suatu organisasi dan menyediakan pihak luar tertentu dengan laporan-laporan yang diperlukan (Jogiyanto, 2005).

Komponen-komponen sistem informasi meliputi:
- **Input** - Data yang dimasukkan ke dalam sistem
- **Proses** - Pengolahan data menjadi informasi
- **Output** - Informasi yang dihasilkan
- **Feedback** - Umpan balik untuk perbaikan sistem
- **Kontrol** - Mekanisme pengendalian sistem

### 2.2.2 Sistem Informasi Manajemen

Sistem Informasi Manajemen (SIM) adalah sistem berbasis komputer yang menyediakan informasi bagi beberapa pengguna dengan kebutuhan yang serupa. Informasi menjelaskan perusahaan atau salah satu sistem utamanya mengenai apa yang telah terjadi di masa lalu, apa yang sedang terjadi sekarang dan apa yang mungkin terjadi di masa depan (McLeod & Schell, 2008).

### 2.2.3 Kegiatan Lapangan Mahasiswa

Kegiatan lapangan mahasiswa merupakan program pendidikan yang dilaksanakan di luar kampus sebagai bagian dari kurikulum. Jenis-jenis kegiatan lapangan di ITP Markandeya Bali meliputi:

1. **Kuliah Kerja Nyata (KKN)** - Program pengabdian masyarakat yang dilaksanakan di desa/kelurahan. Mahasiswa ditempatkan di lokasi yang ditentukan untuk melaksanakan program kerja selama periode tertentu.

2. **Praktik Pengalaman Lapangan (PPL)** - Program praktik mengajar yang dilaksanakan di sekolah mitra. Dikhususkan untuk mahasiswa program studi kependidikan (PGSD, PBSI, PBI).

3. **Praktik Kerja Lapangan (PKL)** - Program magang di instansi/perusahaan untuk mendapatkan pengalaman kerja nyata. Mahasiswa dapat mengajukan lokasi sendiri melalui sistem.

4. **Magang** - Program penempatan di perusahaan/instansi untuk mahasiswa yang mengambil jalur magang sebagai alternatif dari program lainnya.

### 2.2.4 Framework Laravel

Laravel adalah framework PHP open-source yang mengikuti pola arsitektur Model-View-Controller (MVC). Laravel menyediakan berbagai fitur built-in yang mempercepat pengembangan aplikasi web, antara lain:

- **Eloquent ORM** - Object-Relational Mapping untuk interaksi dengan database menggunakan sintaks PHP yang ekspresif.
- **Blade Template Engine** - Template engine yang powerful untuk membuat tampilan dengan sintaks yang bersih dan mendukung inheritance.
- **Authentication System** - Sistem autentikasi built-in yang mendukung multiple guards.
- **Migration System** - Sistem version control untuk skema database.
- **Middleware** - Lapisan filter untuk HTTP request yang masuk ke aplikasi.
- **Routing** - Sistem routing yang ekspresif dan fleksibel.
- **Artisan CLI** - Command-line interface untuk automasi tugas-tugas development.

Laravel versi 12 yang digunakan dalam penelitian ini merupakan versi terbaru yang membawa peningkatan performa dan fitur keamanan.

### 2.2.5 Multi-Guard Authentication

Multi-guard authentication adalah mekanisme autentikasi pada Laravel yang memungkinkan aplikasi memiliki beberapa "guard" (penjaga) autentikasi terpisah. Setiap guard dapat menggunakan model pengguna yang berbeda, tabel database yang berbeda, dan logika autentikasi yang berbeda.

Dalam konteks sistem ini, terdapat tiga guard:
- **Guard `web`** - Menggunakan model `User` untuk autentikasi administrator
- **Guard `mahasiswa`** - Menggunakan model `Mahasiswa` untuk autentikasi mahasiswa
- **Guard `dosen`** - Menggunakan model `Dosen` untuk autentikasi dosen

Keuntungan multi-guard authentication:
1. Pemisahan data pengguna yang jelas antar peran
2. Setiap guard memiliki session dan cookie terpisah
3. Middleware dapat diterapkan spesifik per guard
4. Keamanan yang lebih baik karena tidak ada pencampuran data autentikasi

### 2.2.6 Role-Based Access Control (RBAC)

Role-Based Access Control adalah metode pembatasan akses berdasarkan peran pengguna dalam organisasi. Dalam sistem ini, RBAC diterapkan pada level admin dengan dua peran:

- **Super Admin** - Memiliki akses penuh ke seluruh fitur dan seluruh jenis kegiatan.
- **Admin** - Memiliki akses terbatas sesuai jenis kegiatan yang ditetapkan (misalnya hanya KKN dan PPL).

Implementasi RBAC menggunakan:
1. **Kolom `role`** pada tabel `users` (enum: `superadmin`, `admin`)
2. **Kolom `kegiatan`** pada tabel `users` (JSON array: `["KKN", "PPL"]`)
3. **Middleware `CheckKegiatan`** untuk memvalidasi akses per kegiatan
4. **Middleware `SuperAdmin`** untuk membatasi akses hanya super admin
5. **Method `canManage()`** pada model `User` untuk pengecekan hak akses

### 2.2.7 Tailwind CSS

Tailwind CSS adalah framework CSS utility-first yang menyediakan class-class utilitas tingkat rendah untuk membangun desain kustom tanpa meninggalkan HTML. Berbeda dengan framework CSS tradisional seperti Bootstrap yang menyediakan komponen siap pakai, Tailwind CSS memberikan fleksibilitas penuh dalam mendesain antarmuka.

Keunggulan Tailwind CSS:
1. **Utility-first approach** - Setiap properti CSS memiliki class utilitas tersendiri
2. **Responsive design** - Prefiks breakpoint built-in (`sm:`, `md:`, `lg:`, `xl:`)
3. **Customizable** - Dapat dikonfigurasi sesuai kebutuhan design system
4. **Performance** - JIT (Just-In-Time) compiler menghasilkan CSS yang minimal
5. **Konsistensi** - Design system yang konsisten melalui theme configuration

Tailwind CSS versi 4.0 yang digunakan dalam penelitian ini menggunakan Vite sebagai build tool untuk kompilasi yang lebih cepat.

### 2.2.8 Alpine.js

Alpine.js adalah framework JavaScript ringan yang menyediakan kemampuan reaktif dan deklaratif langsung di HTML. Alpine.js digunakan dalam sistem ini untuk:

- Toggle sidebar pada tampilan mobile
- Dropdown menu dengan animasi
- Counter animasi pada halaman landing page
- Accordion FAQ pada halaman publik
- Form toggle berdasarkan kondisi tertentu

### 2.2.9 Model-View-Controller (MVC)

MVC adalah pola arsitektur perangkat lunak yang memisahkan aplikasi menjadi tiga komponen utama:

1. **Model** - Merepresentasikan data dan logika bisnis. Dalam Laravel, model menggunakan Eloquent ORM untuk berinteraksi dengan database.
2. **View** - Bertanggung jawab atas tampilan yang dilihat pengguna. Menggunakan Blade template engine.
3. **Controller** - Menerima input dari pengguna, memproses logika bisnis melalui model, dan mengembalikan response berupa view.

### 2.2.10 Entity Relationship Diagram (ERD)

Entity Relationship Diagram adalah diagram yang menggambarkan hubungan antar entitas dalam sebuah database. ERD digunakan dalam tahap perancangan database untuk memvisualisasikan struktur data dan relasinya.

### 2.2.11 Black-Box Testing

Black-box testing adalah metode pengujian perangkat lunak yang menguji fungsionalitas aplikasi tanpa mengetahui struktur internal kode program. Pengujian dilakukan dengan memberikan input dan memverifikasi output yang dihasilkan sesuai dengan yang diharapkan.

---

# BAB III - METODOLOGI PENELITIAN

## 3.1 Metode Penelitian

Penelitian ini menggunakan metode **Research and Development (R&D)** dengan pendekatan **Software Development Life Cycle (SDLC)** model **Waterfall**. Tahapan pengembangan meliputi:

1. **Analisis Kebutuhan** - Pengumpulan dan analisis kebutuhan fungsional dan non-fungsional sistem.
2. **Perancangan Sistem** - Perancangan arsitektur, database, dan antarmuka pengguna.
3. **Implementasi** - Pengkodean sistem berdasarkan perancangan yang telah dibuat.
4. **Pengujian** - Pengujian fungsionalitas sistem menggunakan metode black-box testing.
5. **Deployment** - Penerapan sistem pada lingkungan produksi.

## 3.2 Teknik Pengumpulan Data

1. **Observasi** - Mengamati proses pengelolaan kegiatan lapangan yang berjalan saat ini di ITP Markandeya Bali.
2. **Wawancara** - Melakukan wawancara dengan staf administrasi, dosen pembimbing, dan mahasiswa untuk memahami kebutuhan dan permasalahan yang dihadapi.
3. **Studi Dokumentasi** - Mempelajari dokumen-dokumen terkait alur proses kegiatan lapangan, formulir yang digunakan, dan regulasi yang berlaku.

## 3.3 Analisis Kebutuhan Sistem

### 3.3.1 Analisis Kebutuhan Fungsional

#### A. Kebutuhan Fungsional Admin

| No | Kode | Kebutuhan Fungsional | Deskripsi |
|----|------|---------------------|-----------|
| 1 | FA-01 | Login Admin | Admin dapat masuk ke sistem menggunakan email dan password melalui halaman login admin terpisah |
| 2 | FA-02 | Dashboard Statistik | Admin dapat melihat statistik jumlah mahasiswa per kegiatan (KKN, PPL, PKL, Magang) dalam bentuk angka dan grafik donut |
| 3 | FA-03 | Kelola Peserta KKN | Admin dapat melihat, menambah, mengimpor (CSV), dan mengekspor data mahasiswa peserta KKN |
| 4 | FA-04 | Kelola Peserta PPL | Admin dapat melihat, menambah, mengimpor (CSV), dan mengekspor data mahasiswa peserta PPL |
| 5 | FA-05 | Kelola Peserta PKL | Admin dapat melihat, menambah, mengimpor (CSV), dan mengekspor data mahasiswa peserta PKL |
| 6 | FA-06 | Kelola Peserta Magang | Admin dapat melihat, menambah, mengimpor (CSV), dan mengekspor data mahasiswa peserta Magang |
| 7 | FA-07 | Kelola Data Dosen | Admin dapat melihat, menambah manual, dan mengimpor data dosen dari file CSV |
| 8 | FA-08 | Kelola Lokasi KKN | Admin dapat menambah, melihat, dan menghapus data lokasi/desa KKN |
| 9 | FA-09 | Kelola Sekolah PPL | Admin dapat menambah, melihat, dan menghapus data sekolah mitra PPL |
| 10 | FA-10 | Kelola Instansi PKL | Admin dapat menambah, melihat, dan menghapus data instansi PKL |
| 11 | FA-11 | Penempatan Mahasiswa KKN | Admin dapat menempatkan mahasiswa ke lokasi KKN yang tersedia |
| 12 | FA-12 | Penempatan Mahasiswa PPL | Admin dapat menempatkan mahasiswa ke sekolah PPL yang tersedia |
| 13 | FA-13 | Penempatan Mahasiswa PKL | Admin dapat menempatkan mahasiswa ke instansi PKL yang tersedia |
| 14 | FA-14 | Penempatan Mahasiswa Magang | Admin dapat menempatkan mahasiswa ke lokasi magang yang tersedia |
| 15 | FA-15 | Plotting Dosen Pembimbing KKN | Admin dapat menentukan dosen pembimbing untuk mahasiswa KKN secara batch |
| 16 | FA-16 | Plotting Dosen Pembimbing PPL | Admin dapat menentukan dosen pembimbing untuk mahasiswa PPL secara batch |
| 17 | FA-17 | Plotting Dosen Pembimbing PKL | Admin dapat menentukan dosen pembimbing untuk mahasiswa PKL secara batch |
| 18 | FA-18 | Plotting Dosen Pembimbing Magang | Admin dapat menentukan dosen pembimbing untuk mahasiswa Magang secara batch |
| 19 | FA-19 | Plotting Dosen Penguji | Admin dapat menentukan dosen penguji untuk mahasiswa dari semua kegiatan, difilter otomatis berdasarkan hak akses admin |
| 20 | FA-20 | Persetujuan Pengajuan PKL | Admin dapat menyetujui atau menolak pengajuan lokasi PKL dari mahasiswa |
| 21 | FA-21 | Kelola Tahun Akademik | Admin dapat menambah periode tahun akademik dan mengatur periode aktif |
| 22 | FA-22 | Cetak/Export Laporan | Admin dapat mencetak dan mengekspor data peserta per kegiatan dalam format CSV/PDF |
| 23 | FA-23 | Kelola Admin (Super Admin) | Super Admin dapat menambah admin baru dengan pembatasan kegiatan tertentu |
| 24 | FA-24 | Ubah Password | Admin dapat mengubah password akun sendiri |

#### B. Kebutuhan Fungsional Dosen

| No | Kode | Kebutuhan Fungsional | Deskripsi |
|----|------|---------------------|-----------|
| 1 | FD-01 | Login Dosen | Dosen dapat masuk ke sistem menggunakan NIDN dan password |
| 2 | FD-02 | Dashboard Beranda | Dosen dapat melihat profil, statistik jumlah mahasiswa bimbingan dan ujian, serta progres penilaian |
| 3 | FD-03 | Lihat Mahasiswa Bimbingan | Dosen dapat melihat daftar mahasiswa yang dibimbing dengan filter tahun akademik dan kegiatan |
| 4 | FD-04 | Detail Mahasiswa Bimbingan | Dosen dapat melihat detail mahasiswa termasuk jurnal harian, publikasi, dan informasi penempatan |
| 5 | FD-05 | Input Nilai Bimbingan | Dosen dapat memberikan nilai bimbingan (0-100) untuk mahasiswa yang dibimbing |
| 6 | FD-06 | Lihat Mahasiswa Ujian | Dosen dapat melihat daftar mahasiswa yang diuji dengan filter tahun akademik dan kegiatan |
| 7 | FD-07 | Detail Mahasiswa Ujian | Dosen dapat melihat detail mahasiswa yang diuji termasuk jurnal dan publikasi |
| 8 | FD-08 | Input Nilai Ujian | Dosen dapat memberikan nilai ujian (0-100) untuk mahasiswa yang diuji |
| 9 | FD-09 | Ubah Password | Dosen dapat mengubah password akun sendiri |

#### C. Kebutuhan Fungsional Mahasiswa

| No | Kode | Kebutuhan Fungsional | Deskripsi |
|----|------|---------------------|-----------|
| 1 | FM-01 | Registrasi Akun | Mahasiswa dapat mendaftar dengan mengisi data diri, program studi, jenis kegiatan, dan dokumen pendukung |
| 2 | FM-02 | Login Mahasiswa | Mahasiswa dapat masuk ke sistem menggunakan email dan password |
| 3 | FM-03 | Dashboard Mahasiswa | Mahasiswa dapat melihat profil, status penempatan, dan informasi dosen pembimbing |
| 4 | FM-04 | Kelola Jurnal Harian | Mahasiswa dapat membuat, melihat, dan mencetak jurnal kegiatan harian |
| 5 | FM-05 | Kelola Publikasi | Mahasiswa dapat menambah dan menghapus link publikasi hasil kegiatan |
| 6 | FM-06 | Upload Link Laporan | Mahasiswa dapat menyimpan link laporan akhir kegiatan |
| 7 | FM-07 | Lihat Teman Se-Lokasi | Mahasiswa dapat melihat daftar teman yang ditempatkan di lokasi yang sama |
| 8 | FM-08 | Pengajuan Lokasi PKL | Mahasiswa PKL dapat mengajukan lokasi PKL sendiri untuk disetujui admin |
| 9 | FM-09 | Ubah Password | Mahasiswa dapat mengubah password akun sendiri |

### 3.3.2 Analisis Kebutuhan Non-Fungsional

| No | Kode | Kebutuhan | Deskripsi |
|----|------|-----------|-----------|
| 1 | NF-01 | Responsif | Sistem dapat diakses dengan baik pada perangkat desktop, tablet, dan mobile |
| 2 | NF-02 | Keamanan | Sistem menggunakan autentikasi multi-guard, hashing password, CSRF protection, dan middleware authorization |
| 3 | NF-03 | Performa | Halaman dimuat dalam waktu kurang dari 3 detik pada koneksi internet standar |
| 4 | NF-04 | Usability | Antarmuka intuitif dengan navigasi yang konsisten dan feedback visual yang jelas |
| 5 | NF-05 | Kompatibilitas | Sistem kompatibel dengan browser modern (Chrome, Firefox, Safari, Edge) |
| 6 | NF-06 | Skalabilitas | Sistem mampu menangani data ratusan mahasiswa per kegiatan per tahun akademik |

## 3.4 Perancangan Sistem

### 3.4.1 Arsitektur Sistem

Sistem Sinergi Markandeya menggunakan arsitektur **Model-View-Controller (MVC)** yang diimplementasikan melalui framework Laravel 12. Arsitektur sistem terdiri dari:

```
┌─────────────────────────────────────────────────────┐
│                    CLIENT LAYER                      │
│  ┌──────────┐  ┌──────────┐  ┌──────────────────┐  │
│  │  Browser  │  │  Mobile  │  │  Tablet Browser  │  │
│  │ (Desktop) │  │  Browser │  │                  │  │
│  └──────────┘  └──────────┘  └──────────────────┘  │
└──────────────────────┬──────────────────────────────┘
                       │ HTTP Request/Response
┌──────────────────────▼──────────────────────────────┐
│                 PRESENTATION LAYER                   │
│  ┌──────────────────────────────────────────────┐   │
│  │              Laravel Routing                  │   │
│  │          (routes/web.php)                     │   │
│  └──────────────────┬───────────────────────────┘   │
│  ┌──────────────────▼───────────────────────────┐   │
│  │            Middleware Stack                    │   │
│  │  ┌─────────┐ ┌──────────┐ ┌───────────────┐ │   │
│  │  │  CSRF   │ │  Auth    │ │  CheckKegiatan│ │   │
│  │  │  Token  │ │  Guards  │ │  SuperAdmin   │ │   │
│  │  └─────────┘ └──────────┘ └───────────────┘ │   │
│  └──────────────────┬───────────────────────────┘   │
│  ┌──────────────────▼───────────────────────────┐   │
│  │              Controllers                      │   │
│  │  ┌───────────┐ ┌────────────┐ ┌───────────┐ │   │
│  │  │   Admin   │ │   Dosen    │ │ Mahasiswa  │ │   │
│  │  │Controller │ │ Controller │ │ Controller │ │   │
│  │  └───────────┘ └────────────┘ └───────────┘ │   │
│  └──────────────────────────────────────────────┘   │
└──────────────────────┬──────────────────────────────┘
                       │
┌──────────────────────▼──────────────────────────────┐
│                 BUSINESS LOGIC LAYER                 │
│  ┌──────────────────────────────────────────────┐   │
│  │            Eloquent Models                    │   │
│  │  ┌──────┐ ┌─────────┐ ┌───────┐ ┌────────┐ │   │
│  │  │ User │ │Mahasiswa│ │ Dosen │ │ Jurnal │ │   │
│  │  └──────┘ └─────────┘ └───────┘ └────────┘ │   │
│  │  ┌──────────────┐ ┌──────────────┐          │   │
│  │  │DosenPembimbing│ │DosenPenguji │          │   │
│  │  └──────────────┘ └──────────────┘          │   │
│  │  ┌──────────┐ ┌───────────┐ ┌───────────┐  │   │
│  │  │ Lokasi   │ │Penempatan │ │ Publikasi │  │   │
│  │  │(KKN/PPL/ │ │(KKN/PPL/  │ │           │  │   │
│  │  │ PKL/Mgng)│ │ PKL/Mgng) │ │           │  │   │
│  │  └──────────┘ └───────────┘ └───────────┘  │   │
│  └──────────────────────────────────────────────┘   │
└──────────────────────┬──────────────────────────────┘
                       │
┌──────────────────────▼──────────────────────────────┐
│                   DATA LAYER                         │
│  ┌──────────────────────────────────────────────┐   │
│  │        Database (MySQL / SQLite)              │   │
│  │        17 Tabel + Laravel System Tables       │   │
│  └──────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────┘
```

### 3.4.2 Perancangan Use Case Diagram

#### A. Use Case Admin

```
┌─────────────────────────────────────────────────────────┐
│                    Sistem Sinergi Markandeya             │
│                                                         │
│  ┌─────────────────────┐   ┌─────────────────────────┐ │
│  │   Login Admin       │   │ Dashboard Statistik     │ │
│  └─────────────────────┘   └─────────────────────────┘ │
│  ┌─────────────────────┐   ┌─────────────────────────┐ │
│  │ Kelola Peserta      │   │ Kelola Lokasi           │ │
│  │ (KKN/PPL/PKL/Magang)│   │ (KKN/PPL/PKL/Magang)   │ │
│  └─────────────────────┘   └─────────────────────────┘ │
│  ┌─────────────────────┐   ┌─────────────────────────┐ │
│  │ Penempatan Mahasiswa│   │ Plotting Dosen          │ │
│  │ ke Lokasi           │   │ Pembimbing & Penguji    │ │
│  └─────────────────────┘   └─────────────────────────┘ │
│  ┌─────────────────────┐   ┌─────────────────────────┐ │
│  │ Import/Export Data  │   │ Kelola Tahun Akademik   │ │
│  └─────────────────────┘   └─────────────────────────┘ │
│  ┌─────────────────────┐   ┌─────────────────────────┐ │
│  │ Persetujuan PKL     │   │ Kelola Admin            │ │
│  │                     │   │ (Super Admin only)      │ │
│  └─────────────────────┘   └─────────────────────────┘ │
│  ┌─────────────────────┐                               │
│  │ Ubah Password       │                               │
│  └─────────────────────┘                               │
└─────────────────────────────────────────────────────────┘
         ▲
         │
    ┌────┴────┐
    │  Admin  │
    └─────────┘
```

#### B. Use Case Dosen

```
┌─────────────────────────────────────────────────┐
│            Sistem Sinergi Markandeya             │
│                                                  │
│  ┌─────────────────────┐                        │
│  │   Login Dosen       │                        │
│  └─────────────────────┘                        │
│  ┌─────────────────────┐                        │
│  │   Dashboard Beranda │                        │
│  └─────────────────────┘                        │
│  ┌─────────────────────┐                        │
│  │   Lihat Mahasiswa   │                        │
│  │   Bimbingan         │──── Filter TA/Kegiatan │
│  └─────────────────────┘                        │
│  ┌─────────────────────┐                        │
│  │   Detail Mahasiswa  │──── Lihat Jurnal       │
│  │   Bimbingan         │──── Lihat Publikasi    │
│  └─────────────────────┘                        │
│  ┌─────────────────────┐                        │
│  │   Input Nilai       │                        │
│  │   Bimbingan         │                        │
│  └─────────────────────┘                        │
│  ┌─────────────────────┐                        │
│  │   Lihat Mahasiswa   │                        │
│  │   Ujian             │                        │
│  └─────────────────────┘                        │
│  ┌─────────────────────┐                        │
│  │   Input Nilai Ujian │                        │
│  └─────────────────────┘                        │
│  ┌─────────────────────┐                        │
│  │   Ubah Password     │                        │
│  └─────────────────────┘                        │
└─────────────────────────────────────────────────┘
         ▲
         │
    ┌────┴────┐
    │  Dosen  │
    └─────────┘
```

#### C. Use Case Mahasiswa

```
┌─────────────────────────────────────────────────┐
│            Sistem Sinergi Markandeya             │
│                                                  │
│  ┌─────────────────────┐                        │
│  │   Registrasi        │                        │
│  └─────────────────────┘                        │
│  ┌─────────────────────┐                        │
│  │   Login Mahasiswa   │                        │
│  └─────────────────────┘                        │
│  ┌─────────────────────┐                        │
│  │   Dashboard         │──── Lihat Profil       │
│  │                     │──── Lihat Penempatan   │
│  └─────────────────────┘                        │
│  ┌─────────────────────┐                        │
│  │   Kelola Jurnal     │──── Tambah Jurnal      │
│  │   Harian            │──── Cetak Jurnal       │
│  └─────────────────────┘                        │
│  ┌─────────────────────┐                        │
│  │   Kelola Publikasi  │──── Tambah Publikasi   │
│  │                     │──── Hapus Publikasi    │
│  └─────────────────────┘                        │
│  ┌─────────────────────┐                        │
│  │   Upload Laporan    │                        │
│  └─────────────────────┘                        │
│  ┌─────────────────────┐                        │
│  │   Teman Se-Lokasi   │                        │
│  └─────────────────────┘                        │
│  ┌─────────────────────┐                        │
│  │   Pengajuan PKL     │  ← Khusus PKL          │
│  └─────────────────────┘                        │
│  ┌─────────────────────┐                        │
│  │   Ubah Password     │                        │
│  └─────────────────────┘                        │
└─────────────────────────────────────────────────┘
         ▲
         │
    ┌────┴──────┐
    │ Mahasiswa │
    └───────────┘
```

### 3.4.3 Perancangan Entity Relationship Diagram (ERD)

Berikut adalah entitas dan relasi dalam database sistem:

#### Daftar Entitas dan Atribut

**1. users (Admin)**
| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | BIGINT (PK) | Primary key, auto increment |
| name | VARCHAR(255) | Nama admin |
| email | VARCHAR(255) | Email admin (unique) |
| password | VARCHAR(255) | Password terenkripsi |
| role | VARCHAR(255) | Peran: 'superadmin' atau 'admin' |
| kegiatan | JSON | Array kegiatan yang dikelola, null untuk superadmin |
| email_verified_at | TIMESTAMP | Waktu verifikasi email |
| remember_token | VARCHAR(100) | Token remember me |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

**2. mahasiswas (Mahasiswa)**
| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | BIGINT (PK) | Primary key, auto increment |
| nim | VARCHAR(255) | Nomor Induk Mahasiswa (unique) |
| nama | VARCHAR(255) | Nama lengkap |
| email | VARCHAR(255) | Email mahasiswa (unique) |
| password | VARCHAR(255) | Password terenkripsi |
| kampus | VARCHAR(255) | Nama kampus |
| prodi | VARCHAR(255) | Program studi (PGSD/PBSI/PBI/SI/ME/PARBUD/HUKUM) |
| kegiatan | VARCHAR(255) | Jenis kegiatan (KKN/PPL/PKL/Magang) |
| kecamatan | VARCHAR(255) | Kecamatan asal |
| pembayaranKRS | VARCHAR(255) | Status pembayaran KRS |
| KRS | VARCHAR(255) | Status KRS |
| laporan_link | VARCHAR(255) | Link laporan akhir (nullable) |
| tahun_akademik | VARCHAR(255) | Tahun akademik terdaftar (nullable) |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

**3. dosens (Dosen)**
| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | BIGINT (PK) | Primary key, auto increment |
| nidn | VARCHAR(255) | Nomor Induk Dosen Nasional (unique) |
| nama | VARCHAR(255) | Nama lengkap beserta gelar |
| password | VARCHAR(255) | Password terenkripsi |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

**4. dosen_pembimbings (Penugasan Dosen Pembimbing)**
| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | BIGINT (PK) | Primary key, auto increment |
| nim | VARCHAR(255) (FK) | NIM mahasiswa → mahasiswas.nim |
| nidn | VARCHAR(255) (FK) | NIDN dosen → dosens.nidn |
| nilai | VARCHAR(255) | Nilai bimbingan (nullable) |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

**5. dosen_pengujis (Penugasan Dosen Penguji)**
| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | BIGINT (PK) | Primary key, auto increment |
| nim | VARCHAR(255) (FK) | NIM mahasiswa → mahasiswas.nim |
| nidn | VARCHAR(255) (FK) | NIDN dosen → dosens.nidn |
| nilai | VARCHAR(255) | Nilai ujian (nullable) |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

**6. jurnals (Jurnal Harian)**
| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | BIGINT (PK) | Primary key, auto increment |
| nim | VARCHAR(255) (FK) | NIM mahasiswa → mahasiswas.nim |
| tanggal | DATE | Tanggal kegiatan |
| kegiatan | TEXT | Deskripsi kegiatan harian |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

**7. publikasis (Publikasi)**
| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | BIGINT (PK) | Primary key, auto increment |
| nim | VARCHAR(255) (FK) | NIM mahasiswa → mahasiswas.nim |
| judul | VARCHAR(255) | Judul publikasi |
| link | VARCHAR(255) | URL link publikasi |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

**8. lokasi_kkn (Lokasi KKN)**
| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | BIGINT (PK) | Primary key, auto increment |
| desa | VARCHAR(255) | Nama desa |
| alamat | VARCHAR(255) | Alamat lengkap (nullable) |
| kecamatan | VARCHAR(255) | Nama kecamatan (nullable) |
| kabupaten | VARCHAR(255) | Nama kabupaten (nullable) |
| provinsi | VARCHAR(255) | Nama provinsi (nullable) |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

**9. pembagian_lokasi_kkn (Penempatan KKN)**
| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | BIGINT (PK) | Primary key, auto increment |
| nim | VARCHAR(255) (FK) | NIM mahasiswa → mahasiswas.nim (unique) |
| lokasi_kkn_id | BIGINT (FK) | ID lokasi → lokasi_kkn.id |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

**10. lokasi_ppl (Lokasi PPL)**
| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | BIGINT (PK) | Primary key, auto increment |
| Sekolah | VARCHAR(255) | Nama sekolah mitra |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

**11. Penempatan_ppl (Penempatan PPL)**
| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | BIGINT (PK) | Primary key, auto increment |
| nim | VARCHAR(255) (FK) | NIM mahasiswa → mahasiswas.nim (unique) |
| sekolah_id | BIGINT (FK) | ID sekolah → lokasi_ppl.id |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

**12. lokasi_pkls (Lokasi PKL)**
| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | BIGINT (PK) | Primary key, auto increment |
| nama_instansi | VARCHAR(255) | Nama instansi/perusahaan |
| alamat | VARCHAR(255) | Alamat instansi (nullable) |
| kontak | VARCHAR(255) | Nomor kontak (nullable) |
| email | VARCHAR(255) | Email instansi (nullable) |
| website | VARCHAR(255) | Website instansi (nullable) |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

**13. penempatan_pkls (Penempatan PKL)**
| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | BIGINT (PK) | Primary key, auto increment |
| nim | VARCHAR(255) (FK) | NIM mahasiswa → mahasiswas.nim (unique) |
| lokasi_pkl_id | BIGINT (FK) | ID instansi → lokasi_pkls.id |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

**14. pengajuan_lokasi_pkl (Pengajuan Lokasi PKL)**
| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | BIGINT (PK) | Primary key, auto increment |
| nim | VARCHAR(255) (FK) | NIM mahasiswa → mahasiswas.nim |
| nama_instansi | VARCHAR(255) | Nama instansi yang diajukan |
| alamat | VARCHAR(255) | Alamat instansi |
| kontak | VARCHAR(255) | Kontak PIC (nullable) |
| status | ENUM | Status: 'pending', 'approved', 'rejected' |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

**15. lokasi_magangs (Lokasi Magang)**
| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | BIGINT (PK) | Primary key, auto increment |
| nama_instansi | VARCHAR(255) | Nama instansi/perusahaan |
| alamat | VARCHAR(255) | Alamat instansi (nullable) |
| kontak | VARCHAR(255) | Nomor kontak (nullable) |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

**16. penempatan_magangs (Penempatan Magang)**
| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | BIGINT (PK) | Primary key, auto increment |
| nim | VARCHAR(255) (FK) | NIM mahasiswa → mahasiswas.nim (unique) |
| lokasi_magang_id | BIGINT (FK) | ID instansi → lokasi_magangs.id |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

**17. tahun_akademiks (Tahun Akademik)**
| Atribut | Tipe Data | Keterangan |
|---------|-----------|------------|
| id | BIGINT (PK) | Primary key, auto increment |
| tahun | VARCHAR(255) | Tahun akademik (contoh: "2025/2026") |
| semester | ENUM | Semester: 'Ganjil' atau 'Genap' |
| is_active | BOOLEAN | Status aktif (default: false) |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

#### Relasi Antar Entitas

| No | Entitas 1 | Relasi | Entitas 2 | Keterangan |
|----|-----------|--------|-----------|------------|
| 1 | mahasiswas | 1 : N | jurnals | Satu mahasiswa memiliki banyak jurnal |
| 2 | mahasiswas | 1 : N | publikasis | Satu mahasiswa memiliki banyak publikasi |
| 3 | mahasiswas | 1 : 1 | dosen_pembimbings | Satu mahasiswa memiliki satu dosen pembimbing |
| 4 | mahasiswas | 1 : 1 | dosen_pengujis | Satu mahasiswa memiliki satu dosen penguji |
| 5 | mahasiswas | 1 : 1 | pembagian_lokasi_kkn | Satu mahasiswa KKN ditempatkan di satu lokasi |
| 6 | mahasiswas | 1 : 1 | Penempatan_ppl | Satu mahasiswa PPL ditempatkan di satu sekolah |
| 7 | mahasiswas | 1 : 1 | penempatan_pkls | Satu mahasiswa PKL ditempatkan di satu instansi |
| 8 | mahasiswas | 1 : 1 | penempatan_magangs | Satu mahasiswa Magang ditempatkan di satu instansi |
| 9 | mahasiswas | 1 : N | pengajuan_lokasi_pkl | Satu mahasiswa PKL dapat mengajukan banyak lokasi |
| 10 | dosens | 1 : N | dosen_pembimbings | Satu dosen membimbing banyak mahasiswa |
| 11 | dosens | 1 : N | dosen_pengujis | Satu dosen menguji banyak mahasiswa |
| 12 | lokasi_kkn | 1 : N | pembagian_lokasi_kkn | Satu lokasi KKN memiliki banyak mahasiswa |
| 13 | lokasi_ppl | 1 : N | Penempatan_ppl | Satu sekolah memiliki banyak mahasiswa PPL |
| 14 | lokasi_pkls | 1 : N | penempatan_pkls | Satu instansi PKL memiliki banyak mahasiswa |
| 15 | lokasi_magangs | 1 : N | penempatan_magangs | Satu instansi magang memiliki banyak mahasiswa |

### 3.4.4 Perancangan Struktur Menu/Navigasi

#### A. Menu Admin

```
Dashboard
├── Tahun Akademik
├── Manajemen Peserta
│   ├── Peserta KKN *
│   ├── Peserta PPL *
│   ├── Peserta PKL *
│   └── Peserta Magang *
├── Dosen & Lokasi
│   ├── Data Dosen
│   ├── Pengaturan Lokasi (dropdown)
│   │   ├── Master Lokasi KKN *
│   │   ├── Master Sekolah PPL *
│   │   ├── Master Instansi PKL *
│   │   ├── Penempatan KKN *
│   │   ├── Penempatan PPL *
│   │   ├── Penempatan PKL *
│   │   ├── Penempatan Magang *
│   │   └── Persetujuan PKL *
│   └── Plotting Dosen (dropdown)
│       ├── Plot Dosen KKN *
│       ├── Plot Dosen PPL *
│       ├── Plot Dosen PKL *
│       ├── Plot Dosen Magang *
│       └── Plot Dosen Penguji
├── Super Admin (hanya untuk superadmin)
│   └── Kelola Admin
└── Pengaturan
    └── Ubah Password

* = Ditampilkan berdasarkan hak akses kegiatan admin
```

#### B. Menu Dosen

```
Beranda (Dashboard)
├── Bimbingan
│   └── Mahasiswa Bimbingan
├── Pengujian
│   └── Mahasiswa Ujian
└── Akun
    └── Ubah Password
```

#### C. Menu Mahasiswa

```
Beranda (Dashboard)
├── Aktivitas
│   ├── Jurnal Harian
│   ├── Publikasi
│   └── Teman Se-Lokasi
├── Khusus PKL (hanya untuk mahasiswa PKL)
│   └── Pengajuan Lokasi
└── Akun
    └── Ubah Password
```

### 3.4.5 Perancangan Antarmuka (Wireframe)

Desain antarmuka sistem menggunakan design system yang konsisten dengan karakteristik berikut:

**Design System:**
- **Font:** Instrument Sans (Google Fonts)
- **Primary Color:** Blue (#3B82F6) untuk elemen utama
- **Secondary Colors:**
  - Emerald (#10B981) untuk elemen PPL
  - Amber (#F59E0B) untuk elemen PKL
  - Indigo (#6366F1) untuk elemen Magang/Penguji
- **Border Radius:** Rounded-2xl hingga Rounded-3xl (16px - 24px)
- **Shadow:** Shadow-sm dengan border tipis untuk card
- **Layout:** Sidebar kiri (72px) + Main content area

**Pola Layout Halaman:**

1. **Layout Master** - Sidebar kiri dengan navigasi + Header bar atas (judul halaman + tanggal) + Area konten utama
2. **Halaman List** - Card putih dengan DataTable di dalamnya, tombol aksi di header
3. **Halaman Form** - Two-column layout (7/12 untuk pilihan utama, 5/12 untuk opsi dan tombol submit)
4. **Halaman Dashboard** - Grid cards untuk statistik + area grafik + quick actions

---

# BAB IV - HASIL DAN PEMBAHASAN

## 4.1 Lingkungan Pengembangan

### 4.1.1 Perangkat Keras
- Komputer/Laptop dengan spesifikasi minimal:
  - Processor: Intel Core i5 atau setara
  - RAM: 8 GB
  - Storage: 256 GB SSD
  - Koneksi internet untuk akses CDN dan dependencies

### 4.1.2 Perangkat Lunak

| No | Perangkat Lunak | Versi | Fungsi |
|----|----------------|-------|--------|
| 1 | PHP | 8.2+ | Bahasa pemrograman server-side |
| 2 | Laravel | 12.0 | Framework PHP untuk backend |
| 3 | Composer | 2.x | Dependency manager PHP |
| 4 | Node.js | 18+ | JavaScript runtime untuk build tools |
| 5 | NPM | 9+ | Package manager JavaScript |
| 6 | Vite | 6.0.11 | Build tool dan dev server |
| 7 | Tailwind CSS | 4.0 | Framework CSS utility-first |
| 8 | Alpine.js | 3.14.8 | Framework JavaScript ringan |
| 9 | jQuery | 3.6.0 | Library JavaScript untuk DataTables |
| 10 | DataTables | 1.13.4 | Plugin tabel interaktif |
| 11 | Chart.js | Latest | Library charting JavaScript |
| 12 | Font Awesome | 6.4.0 | Library ikon |
| 13 | MySQL/SQLite | Latest | Database relasional |
| 14 | Git | Latest | Version control system |
| 15 | Visual Studio Code | Latest | Code editor |

### 4.1.3 Struktur Direktori Proyek

```
sinergi-markandeya/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # 10 controller files
│   │   │   ├── AdminController.php
│   │   │   ├── AuthController.php
│   │   │   ├── DosenController.php
│   │   │   ├── DosenPembimbingController.php
│   │   │   ├── DosenPengujiController.php
│   │   │   ├── JurnalController.php
│   │   │   ├── LokasiKKNController.php
│   │   │   ├── LokasiPPLController.php
│   │   │   ├── LokasiPklController.php
│   │   │   ├── MahasiswaController.php
│   │   │   ├── PengajuanLokasiPKLController.php
│   │   │   ├── PublikasiController.php
│   │   │   └── TahunAkademikController.php
│   │   └── Middleware/           # 2 custom middleware
│   │       ├── CheckKegiatan.php
│   │       └── SuperAdmin.php
│   └── Models/                   # 16 model files
│       ├── User.php
│       ├── Mahasiswa.php
│       ├── Dosen.php
│       ├── DosenPembimbing.php
│       ├── DosenPenguji.php
│       ├── Jurnal.php
│       ├── Publikasi.php
│       ├── Lokasikkn.php
│       ├── Lokasippl.php
│       ├── LokasiPkl.php
│       ├── LokasiMagang.php
│       ├── Penempatankkn.php
│       ├── Penempatanppl.php
│       ├── PenempatanPkl.php
│       ├── PenempatanMagang.php
│       ├── PengajuanLokasiPKL.php
│       └── TahunAkademik.php
├── bootstrap/
│   └── app.php                   # Middleware alias registration
├── config/
│   └── auth.php                  # Multi-guard configuration
├── database/
│   ├── migrations/               # 17 migration files
│   └── seeders/
│       └── DatabaseSeeder.php    # Sample data seeder
├── resources/
│   ├── css/
│   │   └── app.css               # Tailwind CSS configuration
│   ├── js/
│   │   ├── app.js                # Main JavaScript entry
│   │   └── bootstrap.js          # Axios configuration
│   └── views/                    # 57 Blade template files
│       ├── layouts/              # 7 layout files
│       ├── admin/                # 26 admin views
│       ├── dosen/                # 5 dosen views
│       ├── mahasiswa/            # 12 mahasiswa views
│       ├── auth/                 # 1 auth view
│       ├── welcome.blade.php     # Landing page
│       ├── login.blade.php       # Login page
│       ├── register.blade.php    # Registration page
│       └── home.blade.php        # Home page
├── routes/
│   └── web.php                   # 80+ route definitions
├── composer.json                 # PHP dependencies
├── package.json                  # JS dependencies
└── vite.config.js                # Vite build configuration
```

## 4.2 Implementasi Sistem

### 4.2.1 Implementasi Database

Database diimplementasikan menggunakan Laravel Migration yang berjumlah **17 file migrasi** untuk membentuk **17 tabel** utama. Migrasi dijalankan menggunakan perintah:

```bash
php artisan migrate
```

Untuk data awal (seeder), sistem menyediakan data contoh yang mencakup:
- 1 akun Super Admin (email: admin@gmail.com)
- 2 data dosen pembimbing
- 3 data mahasiswa (KKN, PPL, PKL)
- 1 lokasi KKN dan 1 lokasi PPL
- 2 penugasan dosen pembimbing
- 2 penempatan lokasi
- 3 jurnal kegiatan

```bash
php artisan db:seed
```

### 4.2.2 Implementasi Multi-Guard Authentication

Sistem mengimplementasikan tiga guard autentikasi yang dikonfigurasi pada file `config/auth.php`:

**Guard `web` (Admin):**
- Driver: session
- Provider: users (tabel `users`)
- Login melalui: `/admin` → `loginAdmin()`
- Redirect setelah login: `/admindashboard`

**Guard `mahasiswa` (Mahasiswa):**
- Driver: session
- Provider: mahasiswas (tabel `mahasiswas`)
- Login melalui: `/login` (deteksi format email)
- Redirect setelah login: `/dashboard`

**Guard `dosen` (Dosen):**
- Driver: session
- Provider: dosens (tabel `dosens`)
- Login melalui: `/login` (deteksi format non-email/NIDN)
- Redirect setelah login: `/dosen-pembimbing/dashboard`

**Logika Deteksi Login Mahasiswa/Dosen:**
```php
public function login(Request $request)
{
    $input = $request->input('email');

    if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
        // Login sebagai Mahasiswa menggunakan email
        if (Auth::guard('mahasiswa')->attempt([
            'email' => $input,
            'password' => $request->password
        ])) {
            return redirect()->route('mahasiswadashboard');
        }
    } else {
        // Login sebagai Dosen menggunakan NIDN
        if (Auth::guard('dosen')->attempt([
            'nidn' => $input,
            'password' => $request->password
        ])) {
            return redirect()->route('dosen.dashboard');
        }
    }
}
```

### 4.2.3 Implementasi Role-Based Access Control

RBAC diimplementasikan melalui beberapa komponen:

**1. Model User dengan Method RBAC:**
```php
class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'role', 'kegiatan'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'kegiatan' => 'array',  // JSON → PHP Array
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function canManage(string $kegiatan): bool
    {
        if ($this->isSuperAdmin()) return true;
        return is_array($this->kegiatan)
            && in_array($kegiatan, $this->kegiatan);
    }

    public function getAllowedKegiatan(): array
    {
        if ($this->isSuperAdmin())
            return ['KKN', 'PPL', 'PKL', 'Magang'];
        return $this->kegiatan ?? [];
    }
}
```

**2. Middleware CheckKegiatan:**
```php
class CheckKegiatan
{
    public function handle($request, Closure $next, $kegiatan)
    {
        if (!Auth::user()->canManage($kegiatan)) {
            abort(403, 'Anda tidak memiliki akses untuk
                kegiatan ini.');
        }
        return $next($request);
    }
}
```

**3. Penerapan pada Rute:**
```php
// Hanya admin yang mengelola KKN bisa akses
Route::get('/admin/peserta/kkn', [AdminController::class,
    'pesertaKKN'])->middleware('kegiatan:KKN');

// Hanya super admin bisa akses
Route::get('/admin/kelola', [AdminController::class,
    'adminIndex'])->middleware('superadmin');
```

**4. Penerapan pada View (Menu Sidebar):**
```blade
@if(Auth::user()?->canManage('KKN'))
    <a href="{{ route('admin.peserta.kkn') }}">
        Peserta KKN
    </a>
@endif

@if(Auth::user()?->isSuperAdmin())
    <a href="{{ route('admin.kelola') }}">
        Kelola Admin
    </a>
@endif
```

### 4.2.4 Implementasi Fitur Plotting Dosen

**Plotting Dosen Pembimbing:**
Sistem menyediakan empat halaman terpisah untuk plotting dosen pembimbing per jenis kegiatan:
- `/assign-dosenkkn` - Plotting pembimbing KKN
- `/assign-dosenppl` - Plotting pembimbing PPL
- `/assign-dosenpkl` - Plotting pembimbing PKL
- `/assign-dosenmagang` - Plotting pembimbing Magang

Setiap halaman menampilkan daftar mahasiswa yang belum memiliki dosen pembimbing dalam bentuk checkbox list, dropdown pemilihan dosen, dan tombol simpan. Proses plotting mendukung batch assignment (memilih banyak mahasiswa sekaligus untuk satu dosen).

```php
public function assign(Request $request)
{
    $request->validate([
        'nims' => 'required|array',
        'nidn' => 'required|exists:dosens,nidn',
    ]);

    foreach ($request->nims as $nim) {
        DosenPembimbing::updateOrCreate(
            ['nim' => $nim],
            ['nidn' => $request->nidn]
        );
    }
    return redirect()->back()
        ->with('success', 'Dosen berhasil di-plot!');
}
```

**Plotting Dosen Penguji:**
Plotting dosen penguji menggunakan satu halaman terpadu (`/assign-dosenpenguji`) yang secara otomatis memfilter mahasiswa berdasarkan kegiatan yang diizinkan untuk admin yang login:

```php
public function adminIndex(Request $request)
{
    $allowedKegiatan = Auth::user()->getAllowedKegiatan();

    $mahasiswas = Mahasiswa::whereDoesntHave('dosenPenguji')
        ->whereIn('kegiatan', $allowedKegiatan)
        ->orderBy('nama')->get();

    $assignments = DosenPenguji::with(['mahasiswa', 'dosen'])
        ->whereHas('mahasiswa', fn($q) =>
            $q->whereIn('kegiatan', $allowedKegiatan))
        ->get();

    // ...
}
```

### 4.2.5 Implementasi Import/Export Data

**Import Mahasiswa dari CSV:**
```php
public function importMahasiswa(Request $request)
{
    $request->validate(['file' => 'required|mimes:csv,txt']);
    $file = fopen($request->file('file'), 'r');
    $header = fgetcsv($file); // Baca header

    while ($row = fgetcsv($file)) {
        $data = array_combine($header, $row);
        Mahasiswa::updateOrCreate(
            ['nim' => $data['nim']],
            [
                'nama'     => $data['nama'],
                'email'    => $data['email'],
                'password' => $data['nim'], // auto-hashed
                'prodi'    => $data['prodi'],
                // ... field lainnya
            ]
        );
    }
    fclose($file);
}
```

Format CSV Mahasiswa: `nim, nama, email, kampus, kegiatan, prodi, kecamatan, pembayaranKRS, KRS`

Format CSV Dosen: `nidn, nama` (password default = NIDN)

**Export Data ke CSV:**
Setiap jenis kegiatan memiliki fungsi export sendiri yang menghasilkan file CSV dengan data lengkap mahasiswa beserta informasi penempatan, dosen pembimbing, dan nilai.

### 4.2.6 Implementasi Penilaian

Sistem penilaian menggunakan dua komponen:
1. **Nilai Pembimbing** - Diberikan oleh dosen pembimbing (skala 0-100)
2. **Nilai Penguji** - Diberikan oleh dosen penguji (skala 0-100)

**Kalkulasi Nilai Akhir (Accessor pada Model Mahasiswa):**
```php
public function getNilaiAkhirAttribute()
{
    $nilaiPembimbing = $this->dosenPembimbing?->nilai;
    $nilaiPenguji = $this->dosenPenguji?->nilai;

    if (!$nilaiPembimbing && !$nilaiPenguji) return null;

    $avg = ($nilaiPembimbing + $nilaiPenguji) / 2;

    if ($avg >= 85) return 'A';
    if ($avg >= 70) return 'B';
    if ($avg >= 55) return 'C';
    return 'D';
}
```

### 4.2.7 Implementasi Fitur Mahasiswa

**Jurnal Harian:**
Mahasiswa dapat mencatat kegiatan harian dengan tanggal dan deskripsi. Jurnal dapat dicetak/diexport dalam format yang siap print. Dosen pembimbing dan penguji dapat melihat jurnal mahasiswa bimbingannya melalui halaman detail mahasiswa.

**Publikasi:**
Mahasiswa dapat mengelola daftar publikasi dengan menyimpan judul dan link URL. Dosen dapat memantau publikasi mahasiswa bimbingannya.

**Pengajuan Lokasi PKL:**
Fitur khusus untuk mahasiswa PKL yang memungkinkan mengajukan lokasi kegiatan sendiri. Alur proses:
1. Mahasiswa mengisi form pengajuan (nama instansi, alamat, kontak)
2. Status awal: `pending`
3. Admin mereview dan menyetujui (`approved`) atau menolak (`rejected`)
4. Jika disetujui, instansi otomatis tersedia sebagai lokasi PKL

**Teman Se-Lokasi:**
Mahasiswa dapat melihat daftar mahasiswa lain yang ditempatkan di lokasi yang sama, memudahkan koordinasi dan komunikasi antar peserta.

### 4.2.8 Implementasi Responsive Design

Sistem menggunakan pendekatan **mobile-first** dengan Tailwind CSS responsive prefixes:

```html
<!-- Layout responsif: 1 kolom di mobile, 2 kolom di tablet, 3 di desktop -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

<!-- Sidebar: tersembunyi di mobile, fixed di desktop -->
<aside class="hidden lg:flex flex-col w-72 ...">

<!-- Hamburger menu hanya tampil di mobile -->
<div class="lg:hidden flex items-center ...">
```

Breakpoint yang digunakan:
- Default: Mobile (< 640px)
- `sm:` Small tablet (>= 640px)
- `md:` Tablet (>= 768px)
- `lg:` Desktop (>= 1024px)

## 4.3 Pengujian Sistem (Black-Box Testing)

### 4.3.1 Pengujian Modul Autentikasi

| No | Skenario Pengujian | Langkah Pengujian | Hasil yang Diharapkan | Hasil Pengujian | Status |
|----|-------------------|-------------------|----------------------|-----------------|--------|
| 1 | Login admin dengan data valid | Masukkan email dan password yang benar di halaman `/admin` | Redirect ke dashboard admin | Sesuai harapan | Berhasil |
| 2 | Login admin dengan data invalid | Masukkan email/password yang salah | Tampil pesan error "Email atau password salah" | Sesuai harapan | Berhasil |
| 3 | Login mahasiswa dengan email | Masukkan email dan password yang benar di halaman `/login` | Redirect ke dashboard mahasiswa | Sesuai harapan | Berhasil |
| 4 | Login dosen dengan NIDN | Masukkan NIDN dan password yang benar di halaman `/login` | Redirect ke dashboard dosen | Sesuai harapan | Berhasil |
| 5 | Registrasi mahasiswa baru | Isi semua field registrasi dengan data valid | Akun terdaftar, redirect ke login dengan pesan sukses | Sesuai harapan | Berhasil |
| 6 | Registrasi NIM duplikat | Daftar dengan NIM yang sudah terdaftar | Tampil pesan validasi error NIM sudah digunakan | Sesuai harapan | Berhasil |
| 7 | Akses halaman tanpa login | Akses `/admindashboard` tanpa login | Redirect ke halaman login | Sesuai harapan | Berhasil |
| 8 | Logout admin | Klik tombol "Keluar Aplikasi" | Session dihapus, redirect ke halaman login admin | Sesuai harapan | Berhasil |
| 9 | Ubah password | Isi password lama yang benar dan password baru | Password berhasil diubah | Sesuai harapan | Berhasil |

### 4.3.2 Pengujian Modul Admin - Manajemen Peserta

| No | Skenario Pengujian | Langkah Pengujian | Hasil yang Diharapkan | Hasil Pengujian | Status |
|----|-------------------|-------------------|----------------------|-----------------|--------|
| 1 | Lihat peserta KKN | Klik menu "Peserta KKN" | Tampil daftar mahasiswa KKN dengan DataTable | Sesuai harapan | Berhasil |
| 2 | Filter berdasarkan TA | Pilih tahun akademik dari dropdown | Tabel difilter sesuai tahun akademik yang dipilih | Sesuai harapan | Berhasil |
| 3 | Import mahasiswa CSV | Upload file CSV dengan format yang benar | Data mahasiswa berhasil diimpor, tampil pesan sukses | Sesuai harapan | Berhasil |
| 4 | Import CSV format salah | Upload file non-CSV | Tampil pesan validasi error format file | Sesuai harapan | Berhasil |
| 5 | Export data peserta | Klik tombol export CSV | File CSV terdownload dengan data peserta | Sesuai harapan | Berhasil |
| 6 | Tambah mahasiswa manual | Isi form tambah mahasiswa lengkap | Mahasiswa baru terdaftar | Sesuai harapan | Berhasil |

### 4.3.3 Pengujian Modul Admin - Plotting Dosen

| No | Skenario Pengujian | Langkah Pengujian | Hasil yang Diharapkan | Hasil Pengujian | Status |
|----|-------------------|-------------------|----------------------|-----------------|--------|
| 1 | Plot dosen pembimbing KKN | Pilih mahasiswa (checkbox) dan dosen, klik simpan | Plotting tersimpan, mahasiswa hilang dari daftar belum ada pembimbing | Sesuai harapan | Berhasil |
| 2 | Plot dosen pembimbing batch | Pilih beberapa mahasiswa sekaligus | Semua mahasiswa terpilih mendapat dosen pembimbing yang sama | Sesuai harapan | Berhasil |
| 3 | Hapus plotting pembimbing | Klik tombol hapus pada plotting | Plotting dihapus, mahasiswa kembali ke daftar belum ada pembimbing | Sesuai harapan | Berhasil |
| 4 | Plot dosen penguji | Pilih mahasiswa dan dosen penguji | Plotting penguji tersimpan | Sesuai harapan | Berhasil |
| 5 | Filter penguji by admin role | Login sebagai admin KKN, buka plotting penguji | Hanya tampil mahasiswa KKN | Sesuai harapan | Berhasil |
| 6 | Plot tanpa pilih mahasiswa | Klik simpan tanpa memilih mahasiswa | Tampil pesan validasi error | Sesuai harapan | Berhasil |

### 4.3.4 Pengujian Modul Admin - Manajemen Lokasi

| No | Skenario Pengujian | Langkah Pengujian | Hasil yang Diharapkan | Hasil Pengujian | Status |
|----|-------------------|-------------------|----------------------|-----------------|--------|
| 1 | Tambah lokasi KKN | Isi form lokasi (desa, kecamatan, kabupaten, provinsi) | Lokasi baru tersimpan | Sesuai harapan | Berhasil |
| 2 | Hapus lokasi KKN | Klik hapus pada lokasi yang tidak digunakan | Lokasi terhapus | Sesuai harapan | Berhasil |
| 3 | Tempatkan mahasiswa ke lokasi | Pilih mahasiswa dan lokasi, klik simpan | Penempatan tersimpan | Sesuai harapan | Berhasil |
| 4 | Hapus penempatan | Klik hapus pada penempatan | Penempatan dihapus, mahasiswa kembali tersedia | Sesuai harapan | Berhasil |

### 4.3.5 Pengujian Modul Admin - RBAC

| No | Skenario Pengujian | Langkah Pengujian | Hasil yang Diharapkan | Hasil Pengujian | Status |
|----|-------------------|-------------------|----------------------|-----------------|--------|
| 1 | Superadmin lihat semua menu | Login sebagai superadmin | Semua menu kegiatan tampil | Sesuai harapan | Berhasil |
| 2 | Admin terbatas lihat menu | Login sebagai admin KKN saja | Hanya menu KKN yang tampil | Sesuai harapan | Berhasil |
| 3 | Admin akses kegiatan lain | Admin KKN akses URL peserta PPL langsung | HTTP 403 Forbidden | Sesuai harapan | Berhasil |
| 4 | Tambah admin baru | Superadmin tambah admin dengan kegiatan tertentu | Admin baru terdaftar dengan batasan kegiatan | Sesuai harapan | Berhasil |
| 5 | Admin biasa akses kelola admin | Admin biasa akses URL kelola admin | HTTP 403 Forbidden | Sesuai harapan | Berhasil |

### 4.3.6 Pengujian Modul Dosen

| No | Skenario Pengujian | Langkah Pengujian | Hasil yang Diharapkan | Hasil Pengujian | Status |
|----|-------------------|-------------------|----------------------|-----------------|--------|
| 1 | Lihat dashboard beranda | Login dosen, buka halaman beranda | Tampil profil, statistik bimbingan dan ujian | Sesuai harapan | Berhasil |
| 2 | Lihat mahasiswa bimbingan | Klik menu "Mahasiswa Bimbingan" | Tampil daftar mahasiswa yang dibimbing | Sesuai harapan | Berhasil |
| 3 | Filter bimbingan by TA | Pilih tahun akademik dari dropdown | Daftar difilter sesuai TA | Sesuai harapan | Berhasil |
| 4 | Filter bimbingan by kegiatan | Pilih jenis kegiatan dari dropdown | Daftar difilter sesuai kegiatan | Sesuai harapan | Berhasil |
| 5 | Detail mahasiswa bimbingan | Klik nama mahasiswa | Tampil detail: jurnal, publikasi, penempatan | Sesuai harapan | Berhasil |
| 6 | Input nilai bimbingan | Isi nilai (0-100), klik simpan | Nilai tersimpan | Sesuai harapan | Berhasil |
| 7 | Input nilai di luar range | Isi nilai > 100 atau < 0 | Tampil pesan validasi error | Sesuai harapan | Berhasil |
| 8 | Lihat mahasiswa ujian | Klik menu "Mahasiswa Ujian" | Tampil daftar mahasiswa yang diuji | Sesuai harapan | Berhasil |
| 9 | Input nilai ujian | Isi nilai ujian, klik simpan | Nilai ujian tersimpan | Sesuai harapan | Berhasil |

### 4.3.7 Pengujian Modul Mahasiswa

| No | Skenario Pengujian | Langkah Pengujian | Hasil yang Diharapkan | Hasil Pengujian | Status |
|----|-------------------|-------------------|----------------------|-----------------|--------|
| 1 | Lihat dashboard | Login mahasiswa | Tampil profil, status penempatan, quick actions | Sesuai harapan | Berhasil |
| 2 | Tambah jurnal harian | Klik "Isi Jurnal", isi tanggal dan kegiatan | Jurnal tersimpan, tampil di daftar | Sesuai harapan | Berhasil |
| 3 | Cetak jurnal | Klik tombol "Cetak PDF" | Jurnal tampil dalam format siap cetak | Sesuai harapan | Berhasil |
| 4 | Tambah publikasi | Isi judul dan link publikasi | Publikasi tersimpan | Sesuai harapan | Berhasil |
| 5 | Hapus publikasi | Klik tombol hapus pada publikasi | Publikasi terhapus | Sesuai harapan | Berhasil |
| 6 | Simpan link laporan | Isi link laporan akhir, klik simpan | Link laporan tersimpan | Sesuai harapan | Berhasil |
| 7 | Lihat teman se-lokasi | Klik menu "Teman Se-Lokasi" | Tampil daftar mahasiswa di lokasi yang sama | Sesuai harapan | Berhasil |
| 8 | Ajukan lokasi PKL | (Mahasiswa PKL) Isi form pengajuan | Pengajuan tersimpan dengan status pending | Sesuai harapan | Berhasil |
| 9 | Menu pengajuan non-PKL | (Mahasiswa non-PKL) Cek sidebar | Menu pengajuan PKL tidak tampil | Sesuai harapan | Berhasil |

### 4.3.8 Pengujian Responsivitas

| No | Skenario Pengujian | Perangkat | Hasil yang Diharapkan | Hasil Pengujian | Status |
|----|-------------------|-----------|----------------------|-----------------|--------|
| 1 | Tampilan desktop | Desktop (1920x1080) | Layout sidebar + konten side by side | Sesuai harapan | Berhasil |
| 2 | Tampilan tablet | Tablet (768x1024) | Sidebar menjadi overlay, tabel responsif | Sesuai harapan | Berhasil |
| 3 | Tampilan mobile | Mobile (375x667) | Sidebar hamburger menu, layout single column | Sesuai harapan | Berhasil |
| 4 | Toggle sidebar mobile | Klik hamburger icon | Sidebar muncul dengan overlay, klik overlay untuk tutup | Sesuai harapan | Berhasil |

## 4.4 Pembahasan

### 4.4.1 Analisis Hasil Implementasi

Sistem Sinergi Markandeya telah berhasil diimplementasikan sesuai dengan perancangan yang telah dibuat. Berikut adalah ringkasan pencapaian:

| Aspek | Target | Pencapaian |
|-------|--------|------------|
| Jumlah tabel database | 17 tabel | 17 tabel terimplementasi |
| Jumlah controller | 13 controller | 13 controller terimplementasi |
| Jumlah model Eloquent | 16 model | 16 model terimplementasi |
| Jumlah blade view | 57 file | 57 file terimplementasi |
| Jumlah rute | 80+ rute | 80+ rute terimplementasi |
| Guard autentikasi | 3 guard | 3 guard terimplementasi (web, mahasiswa, dosen) |
| Custom middleware | 2 middleware | 2 middleware (CheckKegiatan, SuperAdmin) |
| Kebutuhan fungsional admin | 24 fitur | 24 fitur terverifikasi |
| Kebutuhan fungsional dosen | 9 fitur | 9 fitur terverifikasi |
| Kebutuhan fungsional mahasiswa | 9 fitur | 9 fitur terverifikasi |

### 4.4.2 Keunggulan Sistem

1. **Integrasi Empat Kegiatan** - Berbeda dengan sistem sejenis yang hanya menangani satu jenis kegiatan, Sinergi Markandeya mengintegrasikan KKN, PPL, PKL, dan Magang dalam satu platform terpadu.

2. **Multi-Guard Authentication** - Pemisahan autentikasi yang jelas antara Admin, Dosen, dan Mahasiswa memberikan keamanan yang lebih baik dan pengalaman pengguna yang terfokus sesuai perannya.

3. **Role-Based Access Control** - Fitur pembatasan akses admin berdasarkan kegiatan memungkinkan delegasi tugas yang lebih terstruktur, di mana setiap admin bertanggung jawab pada kegiatan tertentu saja.

4. **Batch Processing** - Fitur import CSV dan plotting batch memungkinkan pengelolaan data dalam jumlah besar secara efisien.

5. **Responsive Design** - Penggunaan Tailwind CSS memastikan sistem dapat diakses dengan baik di berbagai ukuran layar.

6. **Kalkulasi Nilai Otomatis** - Sistem secara otomatis menghitung nilai akhir berdasarkan rata-rata nilai pembimbing dan penguji.

### 4.4.3 Keterbatasan Sistem

1. Sistem belum memiliki fitur notifikasi real-time (push notification) untuk pemberitahuan update kepada pengguna.
2. Sistem belum terintegrasi dengan sistem akademik kampus (SIAKAD) untuk sinkronisasi data mahasiswa.
3. Belum tersedia fitur chat/messaging internal antara mahasiswa dan dosen pembimbing.
4. Fitur cetak laporan masih terbatas pada format CSV dan halaman print browser, belum menggunakan generator PDF yang proper.
5. Belum tersedia fitur monitoring lokasi real-time (GPS tracking) untuk mahasiswa di lapangan.

---

# BAB V - PENUTUP

## 5.1 Kesimpulan

Berdasarkan hasil penelitian dan pembahasan yang telah diuraikan, maka dapat diambil kesimpulan sebagai berikut:

1. **Sistem Informasi Terintegrasi** - Sistem informasi manajemen kegiatan lapangan mahasiswa "Sinergi Markandeya" telah berhasil dirancang dan diimplementasikan sebagai platform web terintegrasi yang mengelola empat jenis kegiatan lapangan (KKN, PPL, PKL, dan Magang) dalam satu sistem terpadu. Sistem ini mencakup seluruh proses mulai dari pendaftaran mahasiswa, penempatan lokasi, penunjukan dosen pembimbing dan penguji, pencatatan jurnal harian, manajemen publikasi, hingga penilaian.

2. **Multi-Guard Authentication** - Implementasi multi-guard authentication berhasil memisahkan akses tiga peran pengguna (Admin, Dosen, dan Mahasiswa) dengan menggunakan guard terpisah yang masing-masing memiliki model, tabel, dan logika autentikasi sendiri. Hal ini memberikan tingkat keamanan yang lebih tinggi dibandingkan single-guard authentication.

3. **Role-Based Access Control** - Implementasi RBAC pada level admin dengan dua peran (Super Admin dan Admin terbatas) berhasil membatasi akses pengelolaan berdasarkan jenis kegiatan. Super Admin memiliki akses penuh, sementara Admin terbatas hanya dapat mengelola kegiatan yang ditetapkan melalui kolom JSON `kegiatan` pada tabel users.

4. **Fitur Plotting Dosen** - Fitur plotting dosen pembimbing dan penguji telah diimplementasikan secara efisien dengan mendukung batch assignment (plotting beberapa mahasiswa sekaligus) dan filtering otomatis berdasarkan hak akses admin. Plotting dosen pembimbing dipisahkan per kegiatan, sementara plotting dosen penguji menggunakan filtering otomatis berdasarkan `getAllowedKegiatan()`.

5. **Fitur Pelaporan** - Sistem menyediakan fitur import data dari CSV untuk penambahan data mahasiswa dan dosen secara massal, serta fitur export data ke CSV dan cetak laporan per kegiatan yang mendukung kebutuhan administrasi akademik.

6. **Pengujian Black-Box** - Berdasarkan hasil pengujian black-box testing yang dilakukan terhadap 42 skenario pengujian meliputi modul autentikasi, manajemen peserta, plotting dosen, manajemen lokasi, RBAC, modul dosen, modul mahasiswa, dan responsivitas, seluruh skenario pengujian menunjukkan hasil yang sesuai dengan yang diharapkan (100% berhasil).

## 5.2 Saran

Berdasarkan hasil penelitian dan keterbatasan yang ditemukan, berikut adalah saran untuk pengembangan sistem di masa depan:

1. **Notifikasi Real-Time** - Menambahkan fitur notifikasi push menggunakan teknologi WebSocket (Laravel Echo + Pusher) agar pengguna mendapat pemberitahuan otomatis ketika ada update penting seperti perubahan penempatan, plotting dosen baru, atau persetujuan pengajuan PKL.

2. **Integrasi SIAKAD** - Mengintegrasikan sistem dengan Sistem Informasi Akademik (SIAKAD) kampus melalui API untuk sinkronisasi data mahasiswa secara otomatis, menghilangkan kebutuhan input manual atau import CSV.

3. **Fitur Chat/Messaging** - Menambahkan fitur komunikasi internal antara mahasiswa dan dosen pembimbing untuk memudahkan konsultasi dan bimbingan tanpa harus bertemu fisik.

4. **PDF Generator** - Mengimplementasikan generator PDF yang proper (seperti DomPDF atau Snappy) untuk menghasilkan laporan yang lebih profesional dan konsisten formatnya.

5. **Mobile Application** - Mengembangkan aplikasi mobile native atau Progressive Web App (PWA) untuk memberikan pengalaman pengguna yang lebih optimal di perangkat mobile, khususnya bagi mahasiswa yang sedang di lapangan.

6. **Dashboard Analitik** - Mengembangkan dashboard analitik yang lebih komprehensif dengan visualisasi data interaktif menggunakan library seperti ApexCharts, termasuk peta persebaran lokasi kegiatan menggunakan Leaflet/Google Maps.

7. **Backup dan Recovery** - Menambahkan fitur backup database otomatis terjadwal dan mekanisme recovery untuk menjaga keamanan data.

8. **Multi-Bahasa** - Menambahkan dukungan multi-bahasa (Indonesia dan Inggris) menggunakan fitur localization Laravel untuk mengakomodasi mahasiswa asing.

9. **Audit Trail** - Menambahkan fitur logging aktivitas pengguna (audit trail) untuk melacak setiap perubahan data yang dilakukan, meningkatkan akuntabilitas dan keamanan sistem.

10. **Unit Testing** - Menambahkan automated testing menggunakan PHPUnit dan Laravel Dusk untuk memastikan kestabilan sistem saat dilakukan pengembangan fitur baru di masa depan.

---

# DAFTAR PUSTAKA

1. Jogiyanto, H.M. (2005). *Analisis dan Desain Sistem Informasi: Pendekatan Terstruktur Teori dan Praktik Aplikasi Bisnis*. Yogyakarta: Andi.

2. McLeod, R. & Schell, G.P. (2008). *Sistem Informasi Manajemen*. Jakarta: Salemba Empat.

3. Pressman, R.S. (2014). *Software Engineering: A Practitioner's Approach*. 8th Edition. New York: McGraw-Hill.

4. Otwell, T. (2024). *Laravel Documentation - Version 12.x*. [Online] Tersedia di: https://laravel.com/docs/12.x

5. Tailwind Labs. (2024). *Tailwind CSS Documentation - Version 4.0*. [Online] Tersedia di: https://tailwindcss.com/docs

6. Alpine.js. (2024). *Alpine.js Documentation*. [Online] Tersedia di: https://alpinejs.dev/

7. Stauffer, M. (2024). *Laravel: Up & Running*. 3rd Edition. O'Reilly Media.

8. Connolly, T. & Begg, C. (2015). *Database Systems: A Practical Approach to Design, Implementation, and Management*. 6th Edition. Pearson.

9. Nielsen, J. (2000). *Designing Web Usability: The Practice of Simplicity*. New Riders Publishing.

10. OWASP Foundation. (2023). *OWASP Top Ten Web Application Security Risks*. [Online] Tersedia di: https://owasp.org/www-project-top-ten/

---

# LAMPIRAN

## Lampiran A: Format CSV Import Mahasiswa

```csv
nim,nama,email,kampus,kegiatan,prodi,kecamatan,pembayaranKRS,KRS
2026001,Budi Santoso,budi@gmail.com,Markandeya Main,KKN,SI,Tegalalang,Lunas,Aktif
2026002,Siti Aminah,siti@gmail.com,Markandeya Main,PPL,PGSD,Bangli,Lunas,Aktif
2026003,Andi Wijaya,andi@gmail.com,Markandeya Main,PKL,ME,Gianyar,Lunas,Aktif
```

## Lampiran B: Format CSV Import Dosen

```csv
nidn,nama
1234567801,Dr. I Wayan Sudarsana M.Pd.
1234567802,Ni Made Wahyuni S.Kom. M.T.
```

*Catatan: Password default dosen adalah NIDN-nya.*

## Lampiran C: Daftar Program Studi

| Kode | Program Studi |
|------|--------------|
| PGSD | Pendidikan Guru Sekolah Dasar |
| PBSI | Pendidikan Bahasa dan Sastra Indonesia |
| PBI | Pendidikan Bahasa Inggris |
| SI | Sistem Informasi |
| ME | Manajemen |
| PARBUD | Pariwisata Budaya |
| HUKUM | Ilmu Hukum |

## Lampiran D: Daftar Pilihan Kampus

1. Markandeya Main
2. Markandeya 2
3. Markandeya 3
4. Markandeya 4

## Lampiran E: Daftar Rute Sistem

### Rute Publik
| Method | URI | Nama Rute | Fungsi |
|--------|-----|-----------|--------|
| GET | / | home | Halaman utama |
| GET | /register | register | Form registrasi |
| POST | /register | register.store | Proses registrasi |
| GET | /login | login | Form login mahasiswa/dosen |
| POST | /login | login.process | Proses login |
| GET | /admin | adminlogin | Form login admin |
| POST | /loginadmin | loginadmin | Proses login admin |
| POST | /logout | logout | Logout mahasiswa/dosen |
| POST | /logoutadmin | logoutadmin | Logout admin |

### Rute Mahasiswa (auth:mahasiswa)
| Method | URI | Nama Rute | Fungsi |
|--------|-----|-----------|--------|
| GET | /dashboard | mahasiswadashboard | Dashboard mahasiswa |
| POST | /save-laporan | save.laporan | Simpan link laporan |
| GET | /teman-selokasi | teman.selokasi | Lihat teman se-lokasi |
| GET | /jurnal | jurnal.index | Daftar jurnal |
| GET | /jurnal/create | jurnal.create | Form tambah jurnal |
| POST | /jurnal/store | jurnal.store | Simpan jurnal |
| GET | /jurnal/cetak | jurnal.cetak | Cetak jurnal |
| GET | /publikasi | publikasi.index | Daftar publikasi |
| GET | /publikasi/create | publikasi.create | Form tambah publikasi |
| POST | /publikasi | publikasi.store | Simpan publikasi |
| DELETE | /publikasi/{id} | publikasi.destroy | Hapus publikasi |
| GET | /pengajuan-pkl | pengajuanpkl.index | Daftar pengajuan PKL |
| GET | /pengajuan-pkl/create | pengajuanpkl.create | Form pengajuan PKL |
| POST | /pengajuan-pkl | pengajuanpkl.store | Simpan pengajuan |

### Rute Dosen (auth:dosen)
| Method | URI | Nama Rute | Fungsi |
|--------|-----|-----------|--------|
| GET | /dosen-pembimbing/dashboard | dosen.dashboard | Dashboard dosen |
| GET | /dosen-pembimbing/bimbingan | dosen.bimbingan | List mahasiswa bimbingan |
| GET | /dosen-pembimbing/mahasiswa/{nim} | dosen.mahasiswa.detail | Detail mahasiswa |
| POST | /dosen-pembimbing/mahasiswa/{nim}/nilai | dosen.mahasiswa.nilai | Input nilai bimbingan |
| GET | /dosen-pembimbing/ujian | dosen.ujian.index | List mahasiswa ujian |
| GET | /dosen-pembimbing/ujian/{nim} | dosen.ujian.detail | Detail mahasiswa ujian |
| POST | /dosen-pembimbing/ujian/{nim}/nilai | dosen.ujian.nilai | Input nilai ujian |

### Rute Admin (auth:web) - Sebagian
| Method | URI | Nama Rute | Fungsi |
|--------|-----|-----------|--------|
| GET | /admindashboard | admindashboard | Dashboard admin |
| GET | /admin/peserta/kkn | admin.peserta.kkn | Peserta KKN |
| GET | /admin/peserta/ppl | admin.peserta.ppl | Peserta PPL |
| GET | /admin/peserta/pkl | admin.peserta.pkl | Peserta PKL |
| GET | /admin/peserta/magang | admin.peserta.magang | Peserta Magang |
| GET | /dosen | dosen.index | Data dosen |
| GET | /assign-dosenkkn | assign.dosenkkn | Plot dosen KKN |
| GET | /assign-dosenppl | assign.dosenppl | Plot dosen PPL |
| GET | /assign-dosenpkl | assign.dosenpkl | Plot dosen PKL |
| GET | /assign-dosenmagang | assign.dosenmagang | Plot dosen Magang |
| GET | /assign-dosenpenguji | assign.dosenpenguji | Plot dosen penguji |
| GET | /lokasikkn | lokasikkn.index | Lokasi KKN |
| GET | /lokasippl | lokasippl.index | Sekolah PPL |
| GET | /lokasipkl | lokasipkl.index | Instansi PKL |
| GET | /admin/kelola | admin.kelola | Kelola admin (superadmin) |
| GET | /tahun-akademik | tahun_akademik.index | Tahun akademik |
