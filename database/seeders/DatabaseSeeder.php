<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Lokasikkn;
use App\Models\Lokasippl;
use App\Models\Jurnal;
use App\Models\DosenPembimbing;
use App\Models\Penempatankkn;
use App\Models\Penempatanppl;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin (Super Admin)
        User::updateOrCreate(
            ['email' => 'admin@markandeya.ac.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'kegiatan' => null,
            ]
        );

        // 2. Seed Dosen
        $dosen1 = Dosen::updateOrCreate(
            ['nidn' => '1234567801'],
            [
                'nama' => 'Dr. I Wayan Sudarsana, M.Pd.',
                'password' => Hash::make('password'),
            ]
        );

        $dosen2 = Dosen::updateOrCreate(
            ['nidn' => '1234567802'],
            [
                'nama' => 'Ni Made Wahyuni, S.Kom., M.T.',
                'password' => Hash::make('password'),
            ]
        );

        // 3. Seed Lokasi
        $lokasiKkn = Lokasikkn::updateOrCreate(
            ['desa' => 'Taro'],
            [
                'kecamatan' => 'Tegalalang',
                'kabupaten' => 'Gianyar',
                'provinsi' => 'Bali'
            ]
        );

        $lokasiPpl = Lokasippl::updateOrCreate(
            ['Sekolah' => 'SMA Negeri 1 Bangli'],
            []
        );

        // 4. Seed Mahasiswa
        // Mahasiswa KKN
        $mhsKkn = Mahasiswa::updateOrCreate(
            ['nim' => '2026001'],
            [
                'nama' => 'Budi Santoso',
                'email' => 'budi@gmail.com',
                'password' => Hash::make('password'),
                'prodi' => 'SI',
                'kampus' => 'Markandeya Main',
                'kegiatan' => 'KKN',
                'kecamatan' => 'Tegalalang',
                'pembayaranKRS' => 'Lunas',
                'KRS' => 'Aktif',
            ]
        );

        // Mahasiswa PPL
        $mhsPpl = Mahasiswa::updateOrCreate(
            ['nim' => '2026002'],
            [
                'nama' => 'Siti Aminah',
                'email' => 'siti@gmail.com',
                'password' => Hash::make('password'),
                'prodi' => 'PGSD',
                'kampus' => 'Markandeya Main',
                'kegiatan' => 'PPL',
                'kecamatan' => 'Bangli',
                'pembayaranKRS' => 'Lunas',
                'KRS' => 'Aktif',
            ]
        );

        // Mahasiswa PKL
        $mhsPkl = Mahasiswa::updateOrCreate(
            ['nim' => '2026003'],
            [
                'nama' => 'Andi Wijaya',
                'email' => 'andi@gmail.com',
                'password' => Hash::make('password'),
                'prodi' => 'ME',
                'kampus' => 'Markandeya Main',
                'kegiatan' => 'PKL',
                'kecamatan' => 'Gianyar',
                'pembayaranKRS' => 'Lunas',
                'KRS' => 'Aktif',
            ]
        );

        // 5. Assign Dosen Pembimbing
        DosenPembimbing::updateOrCreate(
            ['nim' => $mhsKkn->nim, 'nidn' => $dosen1->nidn],
            ['nilai' => 'A']
        );

        DosenPembimbing::updateOrCreate(
            ['nim' => $mhsPpl->nim, 'nidn' => $dosen2->nidn],
            ['nilai' => null]
        );

        // 6. Assign Lokasi
        Penempatankkn::updateOrCreate(
            ['nim' => $mhsKkn->nim],
            ['lokasi_kkn_id' => $lokasiKkn->id]
        );

        Penempatanppl::updateOrCreate(
            ['nim' => $mhsPpl->nim],
            ['sekolah_id' => $lokasiPpl->id]
        );

        // 7. Seed Jurnals
        Jurnal::updateOrCreate(
            ['nim' => $mhsKkn->nim, 'tanggal' => now()->subDays(1)->format('Y-m-d')],
            ['kegiatan' => 'Melakukan observasi lapangan di Desa Taro dan berkoordinasi dengan kepala desa.']
        );

        Jurnal::updateOrCreate(
            ['nim' => $mhsKkn->nim, 'tanggal' => now()->format('Y-m-d')],
            ['kegiatan' => 'Membantu persiapan program edukasi teknologi untuk remaja desa.']
        );

        Jurnal::updateOrCreate(
            ['nim' => $mhsPpl->nim, 'tanggal' => now()->format('Y-m-d')],
            ['kegiatan' => 'Mengikuti orientasi kurikulum di sekolah dan perkenalan dengan staf guru.']
        );
    }
}
