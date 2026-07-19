# Dokumen Pengembangan Sistem Sinergi Markandeya
**Arsitektur Teknis & Panduan Maintenance**

---

## 1. Stack Teknologi
*   **Backend:** PHP 8.4 + Laravel 12.x.
*   **Database:** MySQL.
*   **Frontend:** Tailwind CSS v4, Alpine.js, Chart.js.

---

## 2. Fitur Unggulan & Implementasi Baru

### A. Program Magang (Internship)
Sistem sekarang mendukung 4 kategori kegiatan: `KKN`, `PPL`, `PKL`, dan `Magang`.
*   **Model Baru:** `LokasiMagang` dan `PenempatanMagang`.
*   **Unifikasi:** Setiap program memiliki jalur plotting dosen dan lokasi yang terpisah namun dengan pola UI yang konsisten.

### B. Import Mahasiswa Massal (CSV)
Terletak pada `AdminController@importMahasiswa`.
*   **Logika:** Menggunakan *Native PHP Stream* untuk memproses file `.csv`.
*   **Default Data:** Mahasiswa hasil import akan otomatis masuk ke **Tahun Akademik Aktif** dan memiliki password default berupa **NIM** mereka.

### C. Manajemen Tahun Akademik
Admin dapat menyetel satu periode sebagai periode aktif.
*   Mahasiswa yang mendaftar otomatis terasosiasi dengan periode aktif tersebut.
*   Seluruh laporan ekspor (CSV/PDF) di sisi Admin mendukung filtering berdasarkan periode ini.

---

## 3. Skema Relasi (Updated)
*   `Mahasiswa` **1:1** `PenempatanMagang` **M:1** `LokasiMagang`.
*   `Mahasiswa` **1:1** `DosenPembimbing` (Field `nilai` untuk nilai lapangan).
*   `Mahasiswa` **1:1** `DosenPenguji` (Field `nilai` untuk nilai ujian).

---

## 4. Keamanan
*   **Multi-Auth:** Dipisah menggunakan guards (web, mahasiswa, dosen).
*   **Update Password:** Semua user memiliki akses mandiri untuk mengubah password terenkripsi (BCRYPT).

---
**Status:** Stable V1.1
**Update:** 11 Maret 2026
