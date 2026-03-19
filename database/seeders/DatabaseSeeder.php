<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\MahasiswaKegiatan;
use App\Models\Lokasikkn;
use App\Models\Lokasippl;
use App\Models\LokasiPkl;
use App\Models\LokasiMagang;
use App\Models\Jurnal;
use App\Models\Publikasi;
use App\Models\TahunAkademik;
use App\Models\DosenPembimbing;
use App\Models\DosenPenguji;
use App\Models\DosenPenilaiPublikasi;
use App\Models\PembimbingLuar;
use App\Models\PembimbingLuarMahasiswa;
use App\Models\Penempatankkn;
use App\Models\Penempatanppl;
use App\Models\PenempatanPkl;
use App\Models\PenempatanMagang;
use App\Models\PengajuanLokasiPKL;
use App\Models\PengajuanLokasiMagang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // 1. TAHUN AKADEMIK
        // ==========================================
        $ta = TahunAkademik::updateOrCreate(
            ['tahun' => '2025/2026', 'semester' => 'Genap'],
            ['is_active' => true]
        );

        TahunAkademik::updateOrCreate(
            ['tahun' => '2025/2026', 'semester' => 'Ganjil'],
            ['is_active' => false]
        );

        $taString = $ta->tahun . ' ' . $ta->semester;

        // ==========================================
        // 2. ADMIN (Super Admin + Admin Kegiatan)
        // ==========================================
        User::updateOrCreate(
            ['email' => 'admin@markandeya.ac.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'kegiatan' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'adminkkn@markandeya.ac.id'],
            [
                'name' => 'Admin KKN',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'kegiatan' => json_encode(['KKN']),
            ]
        );

        // ==========================================
        // 3. DOSEN (4 dosen untuk berbagai role)
        // ==========================================
        $dosen1 = Dosen::updateOrCreate(
            ['nidn' => '1234567801'],
            ['nama' => 'Dr. I Wayan Sudarsana, M.Pd.', 'password' => Hash::make('password')]
        );

        $dosen2 = Dosen::updateOrCreate(
            ['nidn' => '1234567802'],
            ['nama' => 'Ni Made Wahyuni, S.Kom., M.T.', 'password' => Hash::make('password')]
        );

        $dosen3 = Dosen::updateOrCreate(
            ['nidn' => '1234567803'],
            ['nama' => 'Dr. I Ketut Artana, M.Pd.', 'password' => Hash::make('password')]
        );

        $dosen4 = Dosen::updateOrCreate(
            ['nidn' => '1234567804'],
            ['nama' => 'Ni Luh Putu Sari, S.T., M.Cs.', 'password' => Hash::make('password')]
        );

        // ==========================================
        // 4. PEMBIMBING LUAR
        // ==========================================
        $pl1 = PembimbingLuar::updateOrCreate(
            ['email' => 'pembimbing1@gmail.com'],
            [
                'nama' => 'I Made Dharma Putra',
                'password' => 'markandeyabali' . date('Y'),
                'instansi' => 'Desa Taro',
                'no_hp' => '081234567890',
            ]
        );

        $pl2 = PembimbingLuar::updateOrCreate(
            ['email' => 'pembimbing2@gmail.com'],
            [
                'nama' => 'Ni Wayan Suci Rahayu',
                'password' => 'markandeyabali' . date('Y'),
                'instansi' => 'PT Bali Digital',
                'no_hp' => '081234567891',
            ]
        );

        $pl3 = PembimbingLuar::updateOrCreate(
            ['email' => 'pembimbing3@gmail.com'],
            [
                'nama' => 'I Gede Artha Wijaya',
                'password' => 'markandeyabali' . date('Y'),
                'instansi' => 'SMA Negeri 1 Bangli',
                'no_hp' => '081234567892',
            ]
        );

        // ==========================================
        // 5. LOKASI
        // ==========================================
        // KKN
        $lokasiKkn1 = Lokasikkn::updateOrCreate(
            ['desa' => 'Taro'],
            ['kecamatan' => 'Tegalalang', 'kabupaten' => 'Gianyar', 'provinsi' => 'Bali']
        );
        $lokasiKkn2 = Lokasikkn::updateOrCreate(
            ['desa' => 'Penglipuran'],
            ['kecamatan' => 'Bangli', 'kabupaten' => 'Bangli', 'provinsi' => 'Bali']
        );

        // PPL
        $lokasiPpl1 = Lokasippl::updateOrCreate(['Sekolah' => 'SMA Negeri 1 Bangli']);
        $lokasiPpl2 = Lokasippl::updateOrCreate(['Sekolah' => 'SMP Negeri 2 Gianyar']);

        // PKL
        $lokasiPkl1 = LokasiPkl::updateOrCreate(
            ['nama_instansi' => 'PT Bali Digital'],
            ['alamat' => 'Jl. Bypass Ngurah Rai No. 88, Denpasar', 'kontak' => '0361-123456', 'email' => 'info@balidigital.co.id']
        );
        $lokasiPkl2 = LokasiPkl::updateOrCreate(
            ['nama_instansi' => 'CV Karya Mandiri'],
            ['alamat' => 'Jl. Raya Ubud No. 15, Gianyar', 'kontak' => '0361-654321', 'email' => 'hrd@karyamandiri.com']
        );

        // Magang
        $lokasiMagang1 = LokasiMagang::updateOrCreate(
            ['nama_instansi' => 'Bank BPD Bali'],
            ['alamat' => 'Jl. Raya Puputan No. 33, Denpasar', 'kontak' => '0361-222333']
        );
        $lokasiMagang2 = LokasiMagang::updateOrCreate(
            ['nama_instansi' => 'Dinas Pariwisata Gianyar'],
            ['alamat' => 'Jl. Astina Utara No. 5, Gianyar', 'kontak' => '0361-943456']
        );

        // ==========================================
        // 6. MAHASISWA (semua kegiatan)
        // ==========================================
        // KKN
        $mhsKkn1 = Mahasiswa::updateOrCreate(
            ['nim' => '2026001'],
            [
                'nama' => 'I Kadek Budi Santoso', 'email' => 'budi@gmail.com',
                'password' => Hash::make('password'), 'prodi' => 'SI',
                'kampus' => 'ITP Markandeya Bali', 'kegiatan' => 'KKN',
                'kecamatan' => 'Tegalalang', 'pembayaranKRS' => 'Lunas', 'KRS' => 'Aktif',
                'tahun_akademik' => $taString,
            ]
        );
        $mhsKkn2 = Mahasiswa::updateOrCreate(
            ['nim' => '2026002'],
            [
                'nama' => 'Ni Putu Ayu Lestari', 'email' => 'ayu@gmail.com',
                'password' => Hash::make('password'), 'prodi' => 'PGSD',
                'kampus' => 'ITP Markandeya Bali', 'kegiatan' => 'KKN',
                'kecamatan' => 'Tegalalang', 'pembayaranKRS' => 'Lunas', 'KRS' => 'Aktif',
                'tahun_akademik' => $taString,
            ]
        );

        // PPL
        $mhsPpl1 = Mahasiswa::updateOrCreate(
            ['nim' => '2026003'],
            [
                'nama' => 'Siti Aminah', 'email' => 'siti@gmail.com',
                'password' => Hash::make('password'), 'prodi' => 'PGSD',
                'kampus' => 'ITP Markandeya Bali', 'kegiatan' => 'PPL',
                'kecamatan' => 'Bangli', 'pembayaranKRS' => 'Lunas', 'KRS' => 'Aktif',
                'tahun_akademik' => $taString,
            ]
        );
        $mhsPpl2 = Mahasiswa::updateOrCreate(
            ['nim' => '2026004'],
            [
                'nama' => 'I Wayan Dharma Putra', 'email' => 'dharma@gmail.com',
                'password' => Hash::make('password'), 'prodi' => 'PBI',
                'kampus' => 'ITP Markandeya Bali', 'kegiatan' => 'PPL',
                'kecamatan' => 'Gianyar', 'pembayaranKRS' => 'Lunas', 'KRS' => 'Aktif',
                'tahun_akademik' => $taString,
            ]
        );

        // PKL
        $mhsPkl1 = Mahasiswa::updateOrCreate(
            ['nim' => '2026005'],
            [
                'nama' => 'Andi Wijaya', 'email' => 'andi@gmail.com',
                'password' => Hash::make('password'), 'prodi' => 'SI',
                'kampus' => 'ITP Markandeya Bali', 'kegiatan' => 'PKL',
                'kecamatan' => 'Denpasar', 'pembayaranKRS' => 'Lunas', 'KRS' => 'Aktif',
                'tahun_akademik' => $taString,
            ]
        );
        $mhsPkl2 = Mahasiswa::updateOrCreate(
            ['nim' => '2026006'],
            [
                'nama' => 'Ni Made Rina Dewi', 'email' => 'rina@gmail.com',
                'password' => Hash::make('password'), 'prodi' => 'ME',
                'kampus' => 'ITP Markandeya Bali', 'kegiatan' => 'PKL',
                'kecamatan' => 'Gianyar', 'pembayaranKRS' => 'Lunas', 'KRS' => 'Aktif',
                'tahun_akademik' => $taString,
            ]
        );

        // Magang
        $mhsMagang1 = Mahasiswa::updateOrCreate(
            ['nim' => '2026007'],
            [
                'nama' => 'I Gede Surya Pratama', 'email' => 'surya@gmail.com',
                'password' => Hash::make('password'), 'prodi' => 'ME',
                'kampus' => 'ITP Markandeya Bali', 'kegiatan' => 'Magang',
                'kecamatan' => 'Denpasar', 'pembayaranKRS' => 'Lunas', 'KRS' => 'Aktif',
                'tahun_akademik' => $taString,
            ]
        );
        $mhsMagang2 = Mahasiswa::updateOrCreate(
            ['nim' => '2026008'],
            [
                'nama' => 'Ni Kadek Sri Utami', 'email' => 'sri@gmail.com',
                'password' => Hash::make('password'), 'prodi' => 'PARBUD',
                'kampus' => 'ITP Markandeya Bali', 'kegiatan' => 'Magang',
                'kecamatan' => 'Gianyar', 'pembayaranKRS' => 'Lunas', 'KRS' => 'Aktif',
                'tahun_akademik' => $taString,
            ]
        );

        // ==========================================
        // 6b. MAHASISWA KEGIATAN (Pivot)
        // ==========================================
        MahasiswaKegiatan::updateOrCreate(
            ['nim' => $mhsKkn1->nim, 'kegiatan' => 'KKN', 'tahun_akademik' => $taString],
            ['is_active' => true]
        );
        MahasiswaKegiatan::updateOrCreate(
            ['nim' => $mhsKkn2->nim, 'kegiatan' => 'KKN', 'tahun_akademik' => $taString],
            ['is_active' => true]
        );
        MahasiswaKegiatan::updateOrCreate(
            ['nim' => $mhsPpl1->nim, 'kegiatan' => 'PPL', 'tahun_akademik' => $taString],
            ['is_active' => true]
        );
        MahasiswaKegiatan::updateOrCreate(
            ['nim' => $mhsPpl2->nim, 'kegiatan' => 'PPL', 'tahun_akademik' => $taString],
            ['is_active' => true]
        );
        MahasiswaKegiatan::updateOrCreate(
            ['nim' => $mhsPkl1->nim, 'kegiatan' => 'PKL', 'tahun_akademik' => $taString],
            ['is_active' => true]
        );
        MahasiswaKegiatan::updateOrCreate(
            ['nim' => $mhsPkl2->nim, 'kegiatan' => 'PKL', 'tahun_akademik' => $taString],
            ['is_active' => true]
        );
        MahasiswaKegiatan::updateOrCreate(
            ['nim' => $mhsMagang1->nim, 'kegiatan' => 'Magang', 'tahun_akademik' => $taString],
            ['is_active' => true]
        );
        MahasiswaKegiatan::updateOrCreate(
            ['nim' => $mhsMagang2->nim, 'kegiatan' => 'Magang', 'tahun_akademik' => $taString],
            ['is_active' => true]
        );

        // ==========================================
        // 7. PENEMPATAN LOKASI
        // ==========================================
        Penempatankkn::updateOrCreate(['nim' => $mhsKkn1->nim], ['lokasi_kkn_id' => $lokasiKkn1->id]);
        Penempatankkn::updateOrCreate(['nim' => $mhsKkn2->nim], ['lokasi_kkn_id' => $lokasiKkn1->id]);
        Penempatanppl::updateOrCreate(['nim' => $mhsPpl1->nim], ['sekolah_id' => $lokasiPpl1->id]);
        Penempatanppl::updateOrCreate(['nim' => $mhsPpl2->nim], ['sekolah_id' => $lokasiPpl2->id]);
        PenempatanPkl::updateOrCreate(['nim' => $mhsPkl1->nim], ['lokasi_pkl_id' => $lokasiPkl1->id]);
        PenempatanPkl::updateOrCreate(['nim' => $mhsPkl2->nim], ['lokasi_pkl_id' => $lokasiPkl2->id]);
        PenempatanMagang::updateOrCreate(['nim' => $mhsMagang1->nim], ['lokasi_magang_id' => $lokasiMagang1->id]);
        PenempatanMagang::updateOrCreate(['nim' => $mhsMagang2->nim], ['lokasi_magang_id' => $lokasiMagang2->id]);

        // ==========================================
        // 8. PLOTTING DOSEN PEMBIMBING
        // ==========================================
        DosenPembimbing::updateOrCreate(['nim' => $mhsKkn1->nim], ['nidn' => $dosen1->nidn, 'nilai' => 85]);
        DosenPembimbing::updateOrCreate(['nim' => $mhsKkn2->nim], ['nidn' => $dosen1->nidn]);
        DosenPembimbing::updateOrCreate(['nim' => $mhsPpl1->nim], ['nidn' => $dosen2->nidn, 'nilai' => 78]);
        DosenPembimbing::updateOrCreate(['nim' => $mhsPpl2->nim], ['nidn' => $dosen2->nidn]);
        DosenPembimbing::updateOrCreate(['nim' => $mhsPkl1->nim], [
            'nidn' => $dosen3->nidn,
            'nilai_pkl_laporan' => 80, 'nilai_pkl_relevansi' => 75, 'nilai_pkl_presentasi' => 85,
            'nilai' => round((80*15 + 75*10 + 85*15) / 40, 1),
        ]);
        DosenPembimbing::updateOrCreate(['nim' => $mhsPkl2->nim], ['nidn' => $dosen3->nidn]);
        DosenPembimbing::updateOrCreate(['nim' => $mhsMagang1->nim], [
            'nidn' => $dosen4->nidn,
            'nilai_pkl_laporan' => 88, 'nilai_pkl_relevansi' => 82, 'nilai_pkl_presentasi' => 90,
            'nilai' => round((88*15 + 82*10 + 90*15) / 40, 1),
        ]);
        DosenPembimbing::updateOrCreate(['nim' => $mhsMagang2->nim], ['nidn' => $dosen4->nidn]);

        // ==========================================
        // 9. PLOTTING DOSEN PENGUJI
        // ==========================================
        DosenPenguji::updateOrCreate(['nim' => $mhsKkn1->nim], [
            'nidn' => $dosen2->nidn,
            'nilai_keterlaksanaan' => 82, 'nilai_kontribusi' => 85, 'nilai_kerjasama' => 80,
            'nilai_kreativitas' => 78, 'nilai_partisipasi' => 84,
            'nilai' => round((82+85+80+78+84) / 5, 1),
        ]);
        DosenPenguji::updateOrCreate(['nim' => $mhsKkn2->nim], ['nidn' => $dosen2->nidn]);
        DosenPenguji::updateOrCreate(['nim' => $mhsPpl1->nim], ['nidn' => $dosen1->nidn, 'nilai' => 80]);
        DosenPenguji::updateOrCreate(['nim' => $mhsPpl2->nim], ['nidn' => $dosen1->nidn]);
        DosenPenguji::updateOrCreate(['nim' => $mhsPkl1->nim], [
            'nidn' => $dosen4->nidn,
            'nilai_keterlaksanaan' => 85, 'nilai_kontribusi' => 80, 'nilai_kerjasama' => 82,
            'nilai_kreativitas' => 88, 'nilai_partisipasi' => 86,
            'nilai' => round((85+80+82+88+86) / 5, 1),
        ]);
        DosenPenguji::updateOrCreate(['nim' => $mhsPkl2->nim], ['nidn' => $dosen4->nidn]);
        DosenPenguji::updateOrCreate(['nim' => $mhsMagang1->nim], [
            'nidn' => $dosen3->nidn,
            'nilai_keterlaksanaan' => 90, 'nilai_kontribusi' => 85, 'nilai_kerjasama' => 88,
            'nilai_kreativitas' => 92, 'nilai_partisipasi' => 87,
            'nilai' => round((90+85+88+92+87) / 5, 1),
        ]);
        DosenPenguji::updateOrCreate(['nim' => $mhsMagang2->nim], ['nidn' => $dosen3->nidn]);

        // ==========================================
        // 10. PLOTTING PEMBIMBING LUAR
        // ==========================================
        // KKN
        PembimbingLuarMahasiswa::updateOrCreate(['nim' => $mhsKkn1->nim], [
            'pembimbing_luar_id' => $pl1->id,
            'nilai_kehadiran' => 90, 'nilai_luaran' => 85, 'nilai_keterlibatan' => 88,
            'nilai_inovatif' => 82, 'nilai_sosialisasi' => 86,
            'nilai' => round((90+85+88+82+86) / 5, 1),
        ]);
        PembimbingLuarMahasiswa::updateOrCreate(['nim' => $mhsKkn2->nim], ['pembimbing_luar_id' => $pl1->id]);

        // PPL
        PembimbingLuarMahasiswa::updateOrCreate(['nim' => $mhsPpl1->nim], [
            'pembimbing_luar_id' => $pl3->id, 'nilai' => 82,
        ]);
        PembimbingLuarMahasiswa::updateOrCreate(['nim' => $mhsPpl2->nim], ['pembimbing_luar_id' => $pl3->id]);

        // PKL
        PembimbingLuarMahasiswa::updateOrCreate(['nim' => $mhsPkl1->nim], [
            'pembimbing_luar_id' => $pl2->id,
            'nilai_pkl_disiplin' => 88, 'nilai_pkl_inisiatif' => 85,
            'nilai_pkl_kualitas' => 90, 'nilai_pkl_skill' => 82,
            'nilai' => round((88*15 + 85*15 + 90*15 + 82*15) / 60, 1),
        ]);
        PembimbingLuarMahasiswa::updateOrCreate(['nim' => $mhsPkl2->nim], ['pembimbing_luar_id' => $pl2->id]);

        // Magang
        PembimbingLuarMahasiswa::updateOrCreate(['nim' => $mhsMagang1->nim], [
            'pembimbing_luar_id' => $pl2->id,
            'nilai_pkl_disiplin' => 92, 'nilai_pkl_inisiatif' => 88,
            'nilai_pkl_kualitas' => 85, 'nilai_pkl_skill' => 90,
            'nilai' => round((92*15 + 88*15 + 85*15 + 90*15) / 60, 1),
        ]);
        PembimbingLuarMahasiswa::updateOrCreate(['nim' => $mhsMagang2->nim], ['pembimbing_luar_id' => $pl2->id]);

        // ==========================================
        // 11. PLOTTING PENILAI PUBLIKASI
        // ==========================================
        DosenPenilaiPublikasi::updateOrCreate(['nim' => $mhsKkn1->nim], [
            'nidn' => $dosen3->nidn,
            'nilai_ketercapaian' => 80, 'nilai_sistematika' => 85, 'nilai_kelayakan' => 78,
            'nilai_presentasi' => 82, 'nilai_mempertahankan' => 80,
            'nilai' => round((80+85+78+82+80) / 5, 1),
        ]);
        DosenPenilaiPublikasi::updateOrCreate(['nim' => $mhsKkn2->nim], ['nidn' => $dosen3->nidn]);
        DosenPenilaiPublikasi::updateOrCreate(['nim' => $mhsPkl1->nim], [
            'nidn' => $dosen1->nidn,
            'nilai_ketercapaian' => 85, 'nilai_sistematika' => 80, 'nilai_kelayakan' => 82,
            'nilai_presentasi' => 88, 'nilai_mempertahankan' => 84,
            'nilai' => round((85+80+82+88+84) / 5, 1),
        ]);
        DosenPenilaiPublikasi::updateOrCreate(['nim' => $mhsMagang1->nim], [
            'nidn' => $dosen2->nidn,
            'nilai_ketercapaian' => 90, 'nilai_sistematika' => 88, 'nilai_kelayakan' => 85,
            'nilai_presentasi' => 92, 'nilai_mempertahankan' => 87,
            'nilai' => round((90+88+85+92+87) / 5, 1),
        ]);

        // ==========================================
        // 12. JURNAL HARIAN
        // ==========================================
        $jurnalData = [
            [$mhsKkn1->nim, -5, 'Sosialisasi dengan warga Desa Taro tentang program KKN.'],
            [$mhsKkn1->nim, -4, 'Survei kebutuhan masyarakat dan pemetaan potensi desa.'],
            [$mhsKkn1->nim, -3, 'Persiapan program edukasi teknologi untuk remaja desa.'],
            [$mhsKkn1->nim, -2, 'Pelaksanaan pelatihan komputer dasar di Balai Desa.'],
            [$mhsKkn1->nim, -1, 'Evaluasi kegiatan dan rapat koordinasi dengan kepala desa.'],
            [$mhsKkn1->nim, 0, 'Penyusunan laporan mingguan kegiatan KKN.'],
            [$mhsPpl1->nim, -3, 'Orientasi kurikulum dan perkenalan dengan staf guru.'],
            [$mhsPpl1->nim, -2, 'Observasi kelas dan metode mengajar guru pamong.'],
            [$mhsPpl1->nim, -1, 'Praktik mengajar terbimbing mata pelajaran Bahasa Indonesia.'],
            [$mhsPpl1->nim, 0, 'Evaluasi mengajar dan konsultasi dengan guru pamong.'],
            [$mhsPkl1->nim, -3, 'Pengenalan lingkungan kerja dan sistem informasi perusahaan.'],
            [$mhsPkl1->nim, -2, 'Membantu tim IT dalam maintenance server.'],
            [$mhsPkl1->nim, -1, 'Mengerjakan modul testing aplikasi web perusahaan.'],
            [$mhsPkl1->nim, 0, 'Presentasi progress kerja ke supervisor.'],
            [$mhsMagang1->nim, -2, 'Orientasi divisi dan pengenalan SOP kantor.'],
            [$mhsMagang1->nim, -1, 'Membantu analisis data nasabah di divisi kredit.'],
            [$mhsMagang1->nim, 0, 'Menyusun laporan rekapitulasi transaksi harian.'],
        ];

        foreach ($jurnalData as [$nim, $daysOffset, $kegiatan]) {
            Jurnal::updateOrCreate(
                ['nim' => $nim, 'tanggal' => now()->addDays($daysOffset)->format('Y-m-d')],
                ['kegiatan' => $kegiatan]
            );
        }

        // ==========================================
        // 13. PUBLIKASI
        // ==========================================
        Publikasi::updateOrCreate(
            ['nim' => $mhsKkn1->nim, 'judul' => 'Pemanfaatan Teknologi Digital untuk Pemberdayaan Remaja Desa Taro'],
            ['link' => 'https://jurnal.markandeyabali.ac.id/article/001']
        );
        Publikasi::updateOrCreate(
            ['nim' => $mhsPkl1->nim, 'judul' => 'Analisis Sistem Informasi Manajemen pada PT Bali Digital'],
            ['link' => 'https://jurnal.markandeyabali.ac.id/article/002']
        );
        Publikasi::updateOrCreate(
            ['nim' => $mhsMagang1->nim, 'judul' => 'Optimalisasi Layanan Perbankan Digital di Bank BPD Bali'],
            ['link' => 'https://jurnal.markandeyabali.ac.id/article/003']
        );

        // ==========================================
        // 14. PENGAJUAN LOKASI PKL & MAGANG (contoh status)
        // ==========================================
        PengajuanLokasiPKL::updateOrCreate(
            ['nim' => $mhsPkl2->nim],
            [
                'nama_instansi' => 'PT Telkom Indonesia Denpasar',
                'alamat' => 'Jl. Teuku Umar No. 6, Denpasar',
                'kontak' => '0361-555666',
                'status' => 'pending',
            ]
        );

        PengajuanLokasiMagang::updateOrCreate(
            ['nim' => $mhsMagang2->nim],
            [
                'nama_instansi' => 'Hotel Grand Hyatt Bali',
                'alamat' => 'Nusa Dua, Badung',
                'kontak' => '0361-771234',
                'status' => 'pending',
            ]
        );

        // Contoh yang sudah approved
        PengajuanLokasiPKL::updateOrCreate(
            ['nim' => $mhsPkl1->nim],
            [
                'nama_instansi' => 'PT Bali Digital',
                'alamat' => 'Jl. Bypass Ngurah Rai No. 88, Denpasar',
                'kontak' => '0361-123456',
                'status' => 'approved',
            ]
        );

        PengajuanLokasiMagang::updateOrCreate(
            ['nim' => $mhsMagang1->nim],
            [
                'nama_instansi' => 'Bank BPD Bali',
                'alamat' => 'Jl. Raya Puputan No. 33, Denpasar',
                'kontak' => '0361-222333',
                'status' => 'approved',
            ]
        );

        // ==========================================
        // 15. LAPORAN LINK (untuk beberapa mahasiswa)
        // ==========================================
        $mhsKkn1->update(['laporan_link' => 'https://drive.google.com/file/d/laporan-kkn-budi']);
        $mhsPkl1->update(['laporan_link' => 'https://drive.google.com/file/d/laporan-pkl-andi']);
    }
}
