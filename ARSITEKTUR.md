# Arsitektur Sistem Sinergi Markandeya

## 1. Gambaran Umum

Sinergi Markandeya adalah Sistem Informasi Manajemen Kegiatan Akademik Lapangan berbasis **Laravel** untuk mengelola program **KKN, PPL, PKL, dan Magang**. Sistem ini mencakup pengelolaan peserta, penempatan lokasi, pembimbingan, penilaian multi-kriteria, dan pelaporan.

---

## 2. Arsitektur Sistem

```
┌──────────────────────────────────────────────────────────────────┐
│                        BROWSER (Client)                          │
└──────────────────────────┬───────────────────────────────────────┘
                           │ HTTP Request
┌──────────────────────────▼───────────────────────────────────────┐
│                      ROUTES (web.php)                            │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌────────────────────┐  │
│  │ Public   │ │ Admin    │ │ Dosen    │ │ Pembimbing Luar    │  │
│  │ Routes   │ │ Routes   │ │ Routes   │ │ Routes             │  │
│  └──────────┘ └──────────┘ └──────────┘ └────────────────────┘  │
│  ┌──────────┐                                                    │
│  │Mahasiswa │                                                    │
│  │ Routes   │                                                    │
│  └──────────┘                                                    │
└──────────────────────────┬───────────────────────────────────────┘
                           │
┌──────────────────────────▼───────────────────────────────────────┐
│                      MIDDLEWARE                                   │
│  ┌──────────────┐ ┌────────────────┐ ┌───────────────────────┐  │
│  │ auth:web     │ │ auth:mahasiswa │ │ auth:dosen            │  │
│  │ (Admin)      │ │ (Mahasiswa)    │ │ (Dosen)               │  │
│  ├──────────────┤ ├────────────────┤ ├───────────────────────┤  │
│  │ auth:        │ │ CheckKegiatan  │ │ SuperAdmin            │  │
│  │ pembimbing   │ │ (KKN/PPL/     │ │ (Superadmin only)     │  │
│  │ _luar        │ │  PKL/Magang)  │ │                       │  │
│  └──────────────┘ └────────────────┘ └───────────────────────┘  │
└──────────────────────────┬───────────────────────────────────────┘
                           │
┌──────────────────────────▼───────────────────────────────────────┐
│                      CONTROLLERS                                  │
│                                                                   │
│  ┌─────────────────┐  ┌──────────────────────────────────────┐  │
│  │ AuthController   │  │ AdminController                      │  │
│  │ (Login/Logout/   │  │ (Dashboard, Peserta, Import/Export,  │  │
│  │  Password)       │  │  Dosen, Mahasiswa, PDF Report)       │  │
│  └─────────────────┘  └──────────────────────────────────────┘  │
│  ┌─────────────────┐  ┌──────────────────────────────────────┐  │
│  │MahasiswaCtrl     │  │ DosenController                      │  │
│  │(Register,        │  │ (Beranda, Bimbingan, Detail,         │  │
│  │ Dashboard,       │  │  Input Nilai)                        │  │
│  │ Laporan)         │  │                                      │  │
│  └─────────────────┘  └──────────────────────────────────────┘  │
│  ┌─────────────────┐  ┌──────────────────────────────────────┐  │
│  │DosenPembimbing   │  │ DosenPengujiController               │  │
│  │Controller        │  │ (Plotting, Ujian, Nilai Kriteria)    │  │
│  │(Plotting,Import) │  │                                      │  │
│  └─────────────────┘  └──────────────────────────────────────┘  │
│  ┌─────────────────┐  ┌──────────────────────────────────────┐  │
│  │DosenPenilai      │  │ PembimbingLuarController             │  │
│  │PublikasiCtrl     │  │ (CRUD, Plotting, Import)             │  │
│  │(Plotting, Nilai) │  │                                      │  │
│  └─────────────────┘  └──────────────────────────────────────┘  │
│  ┌─────────────────┐  ┌──────────────────────────────────────┐  │
│  │PembimbingLuar    │  │ Lokasi Controllers                   │  │
│  │DashboardCtrl     │  │ (KKN, PPL, PKL, Magang)             │  │
│  │(Beranda, Nilai)  │  │ + Penempatan + Pengajuan             │  │
│  └─────────────────┘  └──────────────────────────────────────┘  │
│  ┌─────────────────┐  ┌──────────────────────────────────────┐  │
│  │JurnalController  │  │ PublikasiController                  │  │
│  │TahunAkademikCtrl │  │ PengajuanLokasi(PKL/Magang)Ctrl     │  │
│  └─────────────────┘  └──────────────────────────────────────┘  │
└──────────────────────────┬───────────────────────────────────────┘
                           │
┌──────────────────────────▼───────────────────────────────────────┐
│                      MODELS (Eloquent ORM)                       │
│                                                                   │
│  ┌─── User Auth ──────────────────────────────────────────────┐  │
│  │ User (Admin)  │ Mahasiswa  │ Dosen  │ PembimbingLuar       │  │
│  └────────────────────────────────────────────────────────────┘  │
│  ┌─── Penilaian ──────────────────────────────────────────────┐  │
│  │ DosenPembimbing │ DosenPenguji │ PembimbingLuarMahasiswa   │  │
│  │ DosenPenilaiPublikasi                                      │  │
│  └────────────────────────────────────────────────────────────┘  │
│  ┌─── Lokasi & Penempatan ────────────────────────────────────┐  │
│  │ LokasiKKN/PPL/PKL/Magang │ Penempatan KKN/PPL/PKL/Magang  │  │
│  │ PengajuanLokasiPKL/Magang                                  │  │
│  └────────────────────────────────────────────────────────────┘  │
│  ┌─── Akademik ───────────────────────────────────────────────┐  │
│  │ TahunAkademik │ Jurnal │ Publikasi                         │  │
│  └────────────────────────────────────────────────────────────┘  │
└──────────────────────────┬───────────────────────────────────────┘
                           │
┌──────────────────────────▼───────────────────────────────────────┐
│                      DATABASE (MySQL)                             │
│                                                                   │
│  users, mahasiswas, dosens, pembimbing_luars                     │
│  dosen_pembimbings, dosen_pengujis, dosen_penilai_publikasis     │
│  pembimbing_luar_mahasiswa                                        │
│  lokasi_kkn, lokasi_ppl, lokasi_pkls, lokasi_magangs             │
│  pembagian_lokasi_kkn, Penempatan_ppl, penempatan_pkls,          │
│  penempatan_magangs                                               │
│  pengajuan_lokasi_pkl, pengajuan_lokasi_magang                   │
│  jurnals, publikasis, tahun_akademiks                             │
└──────────────────────────────────────────────────────────────────┘
```

---

## 3. Multi-Role Authentication

```
┌─────────────────────────────────────────────────────────┐
│                   AUTHENTICATION GUARDS                  │
├─────────────┬───────────┬──────────┬────────────────────┤
│  web        │ mahasiswa │ dosen    │ pembimbing_luar    │
│  (Admin)    │ (Student) │ (Lect.)  │ (Ext. Supervisor)  │
├─────────────┼───────────┼──────────┼────────────────────┤
│ Login:      │ Login:    │ Login:   │ Login:             │
│ /admin      │ /login    │ /login   │ /login             │
│ email +     │ email +   │ NIDN +   │ email +            │
│ password    │ password  │ password │ password           │
├─────────────┼───────────┼──────────┼────────────────────┤
│ Default PW: │ Default:  │ Default: │ Default:           │
│ Manual      │ NIM       │ NIDN     │ markandeyabali     │
│             │           │          │ + tahun            │
├─────────────┼───────────┼──────────┼────────────────────┤
│ Roles:      │           │          │                    │
│ superadmin  │           │          │                    │
│ admin_kkn   │           │          │                    │
│ admin_ppl   │           │          │                    │
│ admin_pkl   │           │          │                    │
│ admin_magang│           │          │                    │
└─────────────┴───────────┴──────────┴────────────────────┘
```

---

## 4. Daftar Fitur Lengkap

### A. Modul Admin
| No | Fitur | Deskripsi |
|----|-------|-----------|
| 1 | Dashboard | Statistik peserta per kegiatan, tahun akademik aktif |
| 2 | Manajemen Tahun Akademik | CRUD, set aktif (hanya 1 aktif) |
| 3 | Manajemen Peserta | Daftar peserta KKN/PPL/PKL/Magang dengan filter TA |
| 4 | Tambah Mahasiswa | Manual + Import CSV |
| 5 | Manajemen Dosen | Tambah manual + Import CSV |
| 6 | Manajemen Pembimbing Luar | CRUD + Import/Export CSV |
| 7 | Master Lokasi | CRUD lokasi KKN/PPL/PKL/Magang |
| 8 | Penempatan Lokasi | Assign mahasiswa ke lokasi per kegiatan |
| 9 | Persetujuan PKL/Magang | Approve/reject pengajuan lokasi |
| 10 | Plotting Dosen Pembimbing | Manual + Import CSV, per kegiatan |
| 11 | Plotting Dosen Penguji | Manual + Import CSV, filter kegiatan |
| 12 | Plotting Penilai Publikasi | Manual + Import CSV, filter kegiatan |
| 13 | Plotting Pembimbing Luar | Manual + Import CSV, per kegiatan |
| 14 | Export CSV | Export data peserta per kegiatan |
| 15 | Cetak PDF | Rekap nilai per kegiatan |
| 16 | Kelola Admin | Superadmin: CRUD admin + role assignment |
| 17 | Tabel Peserta + Info Dosen | Kolom Dosen Pembimbing, Penguji, Pemb. Luar, Penilai Publikasi |

### B. Modul Mahasiswa
| No | Fitur | Deskripsi |
|----|-------|-----------|
| 1 | Registrasi | Daftar dengan pilih kegiatan + prodi |
| 2 | Dashboard | Info penempatan, dosen, nilai |
| 3 | Jurnal Harian | Catat kegiatan harian + cetak PDF |
| 4 | Publikasi | Submit link publikasi ilmiah |
| 5 | Laporan | Submit link laporan akhir |
| 6 | Pengajuan Lokasi PKL/Magang | Ajukan lokasi sendiri |
| 7 | Teman Se-Lokasi | Lihat rekan di lokasi yang sama |
| 8 | Ubah Password | Ganti password |

### C. Modul Dosen
| No | Fitur | Deskripsi |
|----|-------|-----------|
| 1 | Dashboard | Statistik bimbingan & ujian |
| 2 | Mahasiswa Bimbingan | Daftar, filter TA & kegiatan, input nilai |
| 3 | Mahasiswa Ujian (Penguji) | Daftar, detail, input nilai kriteria |
| 4 | Penilaian Publikasi | Daftar, detail, input nilai kriteria |
| 5 | Ubah Password | Ganti password |

### D. Modul Pembimbing Luar
| No | Fitur | Deskripsi |
|----|-------|-----------|
| 1 | Dashboard | Statistik bimbingan per kegiatan |
| 2 | Mahasiswa Bimbingan | Daftar, filter TA & kegiatan |
| 3 | Detail & Penilaian | Lihat jurnal + input nilai kriteria |
| 4 | Ubah Password | Ganti password |

---

## 5. Sistem Penilaian

### Kriteria Per Kegiatan dan Penilai

```
┌──────────────────────────────────────────────────────────────────────────┐
│                        SISTEM PENILAIAN                                  │
├────────────┬────────────────────┬────────────────────────────────────────┤
│ KEGIATAN   │ PENILAI            │ KRITERIA                              │
├────────────┼────────────────────┼────────────────────────────────────────┤
│            │ Dosen Pembimbing   │ Nilai langsung (0-100)                │
│            ├────────────────────┼────────────────────────────────────────┤
│            │ Dosen Penguji      │ 1. Keterlaksanaan Program             │
│            │                    │ 2. Kontribusi Terhadap Masyarakat     │
│   KKN      │                    │ 3. Kerjasama Tim                      │
│            │                    │ 4. Kreativitas dan Inovasi            │
│            │                    │ 5. Partisipasi, Komunikasi, Etika     │
│            │                    │ → Rata-rata 5 kriteria                │
│            ├────────────────────┼────────────────────────────────────────┤
│            │ Pembimbing Luar    │ 1. Tingkat Kehadiran                  │
│            │                    │ 2. Luaran & Kesesuaian Program Kerja  │
│            │                    │ 3. Keterlibatan Mahasiswa             │
│            │                    │ 4. Inovatif Pengembangan Program      │
│            │                    │ 5. Sosialisasi & Interaksi            │
│            │                    │ → Rata-rata 5 kriteria                │
│            ├────────────────────┼────────────────────────────────────────┤
│            │ Penilai Publikasi  │ 1. Ketercapaian Publikasi             │
│            │                    │ 2. Sistematika Publikasi              │
│            │                    │ 3. Kelayakan Publikasi                │
│            │                    │ 4. Presentasi                         │
│            │                    │ 5. Mempertahankan Hasil Penelitian    │
│            │                    │ → Rata-rata 5 kriteria                │
├────────────┼────────────────────┼────────────────────────────────────────┤
│            │ Dosen Pembimbing   │ Nilai langsung (0-100)                │
│   PPL      │ Dosen Penguji      │ Nilai langsung (0-100)                │
│            │ Pembimbing Luar    │ Nilai langsung (0-100)                │
│            │ Penilai Publikasi  │ 5 kriteria (sama dgn KKN)            │
├────────────┼────────────────────┼────────────────────────────────────────┤
│            │ Dosen Pembimbing   │ 1. Kualitas Laporan Akhir (15%)      │
│            │                    │ 2. Relevansi Teori (10%)              │
│  PKL /     │                    │ 3. Presentasi / Ujian (15%)           │
│  Magang    │                    │ → Tertimbang, total 40%               │
│            ├────────────────────┼────────────────────────────────────────┤
│            │ Dosen Penguji      │ 5 kriteria (sama dgn KKN)            │
│            ├────────────────────┼────────────────────────────────────────┤
│            │ Pembimbing Luar    │ 1. Kedisiplinan & Etika (15%)        │
│            │                    │ 2. Inisiatif & Kerja Sama (15%)      │
│            │                    │ 3. Kualitas Hasil Kerja (15%)        │
│            │                    │ 4. Penguasaan Skill Teknis (15%)     │
│            │                    │ → Tertimbang, total 60%               │
│            ├────────────────────┼────────────────────────────────────────┤
│            │ Penilai Publikasi  │ 5 kriteria (sama dgn KKN)            │
└────────────┴────────────────────┴────────────────────────────────────────┘

Nilai Akhir = Rata-rata semua penilai yang sudah memberi nilai
≥ 85 → A  │  ≥ 70 → B  │  ≥ 55 → C  │  < 55 → D
```

---

## 6. Flow Chart

### A. Flow Registrasi & Login Mahasiswa

```
┌─────────┐     ┌──────────────┐     ┌──────────────────┐
│  START  │────▶│ Buka /register│────▶│ Isi Form:        │
└─────────┘     └──────────────┘     │ Nama, NIM, Email, │
                                      │ Password, Prodi,  │
                                      │ Kegiatan, Kampus   │
                                      └────────┬───────────┘
                                               │
                                      ┌────────▼───────────┐
                                      │ Validasi Data      │
                                      │ NIM & Email unik?  │
                                      └────────┬───────────┘
                                          ┌────┴────┐
                                          │         │
                                       GAGAL     SUKSES
                                          │         │
                                    ┌─────▼──┐  ┌───▼──────────┐
                                    │Kembali │  │Simpan ke DB  │
                                    │+ Error │  │TA = TA aktif │
                                    └────────┘  └───┬──────────┘
                                                    │
                                              ┌─────▼────┐
                                              │ Redirect │
                                              │ /login   │
                                              └──────────┘
```

### B. Flow Login (Multi-Guard)

```
┌─────────┐     ┌──────────────┐     ┌─────────────────────┐
│  START  │────▶│ Buka /login  │────▶│ Input Email/NIDN    │
└─────────┘     └──────────────┘     │ + Password           │
                                      └────────┬────────────┘
                                               │
                                      ┌────────▼────────────┐
                                      │ Input = Email?       │
                                      └────────┬────────────┘
                                          ┌────┴────┐
                                         YA       TIDAK
                                          │         │
                                   ┌──────▼──────┐ ┌▼─────────────┐
                                   │Coba guard:  │ │Coba guard:   │
                                   │ mahasiswa   │ │ dosen (NIDN) │
                                   └──────┬──────┘ └┬─────────────┘
                                          │         │
                                   ┌──────▼──────┐  │
                                   │ Cocok?      │  │
                                   └──┬───┬──────┘  │
                                     YA  TIDAK      │
                                      │    │        │
                              ┌───────▼┐ ┌─▼──────────────┐
                              │→ /dash-│ │Coba guard:     │
                              │ board  │ │pembimbing_luar │
                              └────────┘ └──┬─────────────┘
                                           │
                                    ┌──────▼──────┐
                                    │ Cocok?      │
                                    └──┬───┬──────┘
                                      YA  TIDAK
                                       │    │
                              ┌────────▼┐ ┌─▼──────────┐
                              │→ /pemb- │ │ Error:     │
                              │ luar/   │ │ Kredensial │
                              │dashboard│ │ tidak      │
                              └─────────┘ │ ditemukan  │
                                          └────────────┘
```

### C. Flow Admin: Plotting Dosen/Pembimbing

```
┌─────────┐     ┌─────────────────┐
│  START  │────▶│ Halaman Plotting│
└─────────┘     └────────┬────────┘
                         │
              ┌──────────┴──────────┐
              │                     │
        ┌─────▼──────┐      ┌──────▼───────┐
        │ Manual     │      │ Import CSV   │
        │ (Checkbox) │      │ (Upload)     │
        └─────┬──────┘      └──────┬───────┘
              │                     │
     ┌────────▼────────┐   ┌───────▼────────────┐
     │ Pilih Mahasiswa │   │ Parse CSV:         │
     │ + Pilih Dosen   │   │ nim, nidn/email    │
     └────────┬────────┘   └───────┬────────────┘
              │                     │
     ┌────────▼────────┐   ┌───────▼────────────┐
     │ Submit Form     │   │ Validasi per baris: │
     │                 │   │ NIM ada? NIDN ada?  │
     └────────┬────────┘   │ Skip jika error     │
              │             └───────┬────────────┘
              │                     │
     ┌────────▼─────────────────────▼──┐
     │ updateOrCreate ke database       │
     │ (1 mahasiswa = 1 dosen/pembimbing)│
     └────────┬────────────────────────┘
              │
     ┌────────▼────────┐
     │ Flash message:  │
     │ Sukses + jumlah │
     │ + error (jika)  │
     └────────┬────────┘
              │
     ┌────────▼────────┐
     │ Redirect back   │
     └─────────────────┘
```

### D. Flow Penilaian Mahasiswa

```
┌───────────┐     ┌─────────────────┐     ┌──────────────────┐
│  Dosen /  │────▶│ Daftar Mahasiswa│────▶│ Klik "Detail"    │
│  Pemb.Luar│     │ Bimbingan/Ujian │     │ pada mahasiswa   │
└───────────┘     └─────────────────┘     └────────┬─────────┘
                                                    │
                                          ┌─────────▼──────────┐
                                          │ Halaman Detail:     │
                                          │ - Info mahasiswa    │
                                          │ - Jurnal harian     │
                                          │ - Publikasi         │
                                          │ - Form Penilaian    │
                                          └─────────┬──────────┘
                                                    │
                                          ┌─────────▼──────────┐
                                          │ Cek Kegiatan       │
                                          │ Mahasiswa           │
                                          └──┬──────┬──────┬───┘
                                             │      │      │
                                    ┌────────▼┐ ┌───▼───┐ ┌▼────────┐
                                    │ KKN    │ │ PPL   │ │PKL/     │
                                    │ 5 kri- │ │ Nilai │ │Magang   │
                                    │ teria  │ │langsung│ │Berbobot │
                                    └────┬───┘ └───┬───┘ └┬────────┘
                                         │        │      │
                                    ┌────▼────────▼──────▼─────┐
                                    │ Input nilai per kriteria  │
                                    │ (0-100 per kriteria)      │
                                    └────────────┬─────────────┘
                                                 │
                                    ┌────────────▼─────────────┐
                                    │ Hitung Nilai Akhir:       │
                                    │ - Rata-rata (KKN)         │
                                    │ - Langsung (PPL)          │
                                    │ - Tertimbang (PKL/Magang) │
                                    └────────────┬─────────────┘
                                                 │
                                    ┌────────────▼─────────────┐
                                    │ Simpan ke DB              │
                                    │ (kriteria + nilai akhir)  │
                                    └────────────┬─────────────┘
                                                 │
                                    ┌────────────▼─────────────┐
                                    │ Redirect + Flash Success  │
                                    └──────────────────────────┘
```

### E. Flow Mahasiswa: Pengajuan Lokasi PKL/Magang

```
┌──────────┐     ┌─────────────────┐     ┌──────────────────┐
│Mahasiswa │────▶│ Menu Pengajuan  │────▶│ Isi Form:        │
│Dashboard │     │ PKL / Magang    │     │ Nama Instansi,   │
└──────────┘     └─────────────────┘     │ Alamat, Kontak   │
                                          └────────┬─────────┘
                                                   │
                                          ┌────────▼─────────┐
                                          │ Simpan dengan    │
                                          │ status: PENDING  │
                                          └────────┬─────────┘
                                                   │
                              ┌────────────────────▼────────────────┐
                              │            ADMIN                    │
                              │  Lihat daftar pengajuan             │
                              │  ┌──────────┐   ┌───────────┐      │
                              │  │ Approve  │   │  Reject   │      │
                              │  └────┬─────┘   └─────┬─────┘      │
                              │       │               │             │
                              │  ┌────▼─────┐   ┌─────▼─────┐      │
                              │  │Buat      │   │Status =   │      │
                              │  │lokasi +  │   │rejected   │      │
                              │  │penempatan│   │           │      │
                              │  └──────────┘   └───────────┘      │
                              └─────────────────────────────────────┘
```

### F. Flow Keseluruhan Siklus Kegiatan

```
┌──────────────────────────────────────────────────────────────────┐
│                   SIKLUS KEGIATAN AKADEMIK                        │
│                                                                   │
│  1. SETUP (Admin)                                                 │
│  ┌──────────────────────────────────────────────────────────┐    │
│  │ Set Tahun Akademik → Buat Lokasi → Import Dosen         │    │
│  │ → Import Pembimbing Luar                                 │    │
│  └──────────────────────────────────────────────────────────┘    │
│                           │                                       │
│  2. REGISTRASI (Mahasiswa)                                        │
│  ┌──────────────────────────────────────────────────────────┐    │
│  │ Mahasiswa daftar → Pilih kegiatan (KKN/PPL/PKL/Magang)  │    │
│  │ → Otomatis masuk TA aktif                                │    │
│  └──────────────────────────────────────────────────────────┘    │
│                           │                                       │
│  3. PENEMPATAN (Admin)                                            │
│  ┌──────────────────────────────────────────────────────────┐    │
│  │ Assign mahasiswa ke lokasi                                │    │
│  │ PKL/Magang: bisa dari pengajuan mahasiswa                 │    │
│  └──────────────────────────────────────────────────────────┘    │
│                           │                                       │
│  4. PLOTTING (Admin)                                              │
│  ┌──────────────────────────────────────────────────────────┐    │
│  │ Plot Dosen Pembimbing → Plot Dosen Penguji               │    │
│  │ → Plot Pembimbing Luar → Plot Penilai Publikasi          │    │
│  │ (Manual atau Import CSV)                                  │    │
│  └──────────────────────────────────────────────────────────┘    │
│                           │                                       │
│  5. PELAKSANAAN (Mahasiswa)                                       │
│  ┌──────────────────────────────────────────────────────────┐    │
│  │ Isi jurnal harian → Submit publikasi → Upload laporan    │    │
│  └──────────────────────────────────────────────────────────┘    │
│                           │                                       │
│  6. PENILAIAN (Dosen & Pembimbing Luar)                          │
│  ┌──────────────────────────────────────────────────────────┐    │
│  │ Dosen Pembimbing menilai → Dosen Penguji menilai         │    │
│  │ → Pembimbing Luar menilai → Penilai Publikasi menilai    │    │
│  │ (kriteria berbeda per kegiatan)                           │    │
│  └──────────────────────────────────────────────────────────┘    │
│                           │                                       │
│  7. REKAPITULASI (Admin)                                          │
│  ┌──────────────────────────────────────────────────────────┐    │
│  │ Lihat nilai akhir → Export CSV → Cetak PDF Rekap         │    │
│  │ Nilai Akhir = Rata-rata semua penilai → Grade A/B/C/D   │    │
│  └──────────────────────────────────────────────────────────┘    │
└──────────────────────────────────────────────────────────────────┘
```

---

## 7. Entity Relationship Diagram (ERD)

```
┌──────────────┐      ┌─────────────────┐      ┌──────────────┐
│    users     │      │   mahasiswas     │      │    dosens    │
│──────────────│      │─────────────────│      │──────────────│
│ id (PK)      │      │ id (PK)         │      │ id (PK)      │
│ name         │      │ nim (UNIQUE)     │◄────┐│ nidn (UNIQUE)│◄──┐
│ email        │      │ nama             │     ││ nama         │   │
│ password     │      │ email            │     ││ password     │   │
│ role         │      │ password         │     │└──────────────┘   │
│ kegiatan[]   │      │ kegiatan         │     │                   │
└──────────────┘      │ prodi            │     │                   │
                      │ tahun_akademik   │     │                   │
                      │ kampus           │     │                   │
                      │ kecamatan        │     │                   │
                      │ laporan_link     │     │                   │
                      └────────┬─────────┘     │                   │
                               │               │                   │
            ┌──────────────────┼───────────────┼───────────────────┤
            │                  │               │                   │
  ┌─────────▼──────┐ ┌────────▼────────┐ ┌────▼──────────────┐   │
  │    jurnals     │ │dosen_pembimbings│ │  dosen_pengujis   │   │
  │────────────────│ │────────────────│ │──────────────────│   │
  │ nim (FK)       │ │ nim (FK)───────┘ │ nim (FK)──────────┘   │
  │ tanggal        │ │ nidn (FK)────────│ nidn (FK)─────────────┘
  │ kegiatan       │ │ nilai            │ │ nilai               │
  └────────────────┘ │ nilai_pkl_*      │ │ nilai_keterlaksanaan│
                      └─────────────────┘ │ nilai_kontribusi    │
  ┌────────────────┐                      │ nilai_kerjasama     │
  │  publikasis    │                      │ nilai_kreativitas   │
  │────────────────│                      │ nilai_partisipasi   │
  │ nim (FK)       │                      └─────────────────────┘
  │ judul          │
  │ link           │  ┌───────────────────────┐
  └────────────────┘  │pembimbing_luar_mhs    │
                      │───────────────────────│
┌──────────────────┐  │ nim (FK)──────────────┤
│ pembimbing_luars │  │ pembimbing_luar_id(FK)│◄──┐
│──────────────────│  │ nilai                 │   │
│ id (PK)          │──│ nilai_kehadiran (KKN) │   │
│ nama             │  │ nilai_pkl_* (PKL)     │   │
│ email            │  └───────────────────────┘   │
│ password         │                              │
│ instansi         │──────────────────────────────┘
└──────────────────┘
                      ┌───────────────────────┐
                      │dosen_penilai_publikasi│
                      │───────────────────────│
                      │ nim (FK)              │
                      │ nidn (FK)             │
                      │ nilai_ketercapaian    │
                      │ nilai_sistematika     │
                      │ nilai_kelayakan       │
                      │ nilai_presentasi      │
                      │ nilai_mempertahankan  │
                      │ nilai                 │
                      └───────────────────────┘

┌──────────────┐    ┌──────────────────┐
│  lokasi_kkn  │    │pembagian_lokasi  │
│──────────────│    │_kkn              │
│ id (PK)      │◄───│ lokasi_kkn_id(FK)│
│ desa         │    │ nim (FK)         │
│ kecamatan    │    └──────────────────┘
│ kabupaten    │
└──────────────┘    (Pola serupa untuk PPL, PKL, Magang)

┌──────────────────┐
│ tahun_akademiks  │
│──────────────────│
│ id (PK)          │
│ tahun            │
│ semester         │
│ is_active        │
└──────────────────┘
```

---

## 8. Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | Laravel (PHP) |
| Database | MySQL |
| Frontend | Blade Templates + Tailwind CSS |
| UI Components | Alpine.js, DataTables, Font Awesome |
| PDF | Laravel PDF (dompdf) |
| Auth | Laravel Multi-Guard Authentication |
| Import/Export | Native PHP CSV (fgetcsv/fputcsv) |
