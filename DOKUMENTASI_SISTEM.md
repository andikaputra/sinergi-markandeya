# Dokumentasi Sistem Sinergi Markandeya
**Platform Terpadu Manajemen KKN, PPL, PKL, & Magang - ITP Markandeya Bali**

---

## 1. Pendahuluan

### 1.1 Latar Belakang
Sistem **Sinergi Markandeya** dibangun untuk mengintegrasikan seluruh pilar pengabdian dan praktik mahasiswa Markandeya Bali ke dalam satu ekosistem digital. Dengan dukungan program KKN, PPL, PKL, dan Magang, sistem ini memastikan efisiensi administrasi di tingkat LPPM dan transparansi bagi mahasiswa serta dosen.

### 1.2 Tujuan
*   **Sentralisasi Data:** Penyatuan database mahasiswa dari berbagai program lapangan.
*   **Efisiensi Plotting:** Otomasi penempatan mahasiswa dan dosen pembimbing/penguji.
*   **Pelaporan Digital:** Pengalihan log book fisik ke Jurnal Harian digital dan Laporan Cloud Link.

---

## 2. Fitur Utama Sistem

### A. Sisi Mahasiswa
1.  **Pendaftaran (Register):** Pilih program (KKN/PPL/PKL/Magang) dan isi data pendukung.
2.  **Dashboard Personal:** Pantau lokasi penempatan, dosen pembimbing, dan status laporan.
3.  **Jurnal Harian & Publikasi:** Catat log harian dan unggah link artikel ilmiah.
4.  **Laporan Akhir (NEW):** Simpan tautan (Google Drive) laporan PDF akhir.
5.  **Cetak Jurnal:** Ekspor jurnal harian ke PDF profesional untuk pengesahan dosen.

### B. Sisi Dosen
1.  **Dosen Pembimbing:** Dashboard pemantauan jurnal dan pemberian nilai lapangan.
2.  **Dosen Penguji (NEW):** Menu khusus "Mahasiswa Ujian" untuk memberikan nilai hasil laporan akhir.

### C. Sisi Admin (Super User)
1.  **Manajemen Periode (NEW):** Kelola Tahun Akademik dan Semester aktif.
2.  **Import Mahasiswa (NEW):** Unggah data mahasiswa secara massal via file CSV.
3.  **Plotting Terpadu:** Assign Lokasi dan Dosen (Pembimbing & Penguji) untuk 4 program utama.
4.  **Ekspor Laporan:** Download rekap nilai akhir dalam format CSV (Excel) atau Cetak PDF Resmi.

---

## 3. Panduan Akun Pengujian
| Peran | Username | Password |
| :--- | :--- | :--- |
| Admin | `admin@gmail.com` | `password` |
| Mahasiswa | `budi@gmail.com` | `password` |
| Dosen | `1234567801` | `password` |

---

## 4. Format Import CSV
Untuk fitur Import Mahasiswa, gunakan format file `.csv` sebagai berikut:
`nama, nim, email, prodi, kegiatan, kecamatan, kampus`
*(Default password mahasiswa hasil import adalah NIM mereka).*

---
**Status:** Stable V1.1 (Final Feature Set)
**Update:** 11 Maret 2026
