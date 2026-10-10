<?php

namespace Tests\Support;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

abstract class WorkflowTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        $this->withoutVite();
        $tables = [
            'users' => ['name', 'email', 'password', 'role', 'kegiatan'],
            'mahasiswas' => ['nim', 'nama', 'email', 'password', 'status', 'kegiatan', 'tahun_akademik', 'reset_token', 'reset_token_expires_at', 'last_login'],
            'dosens' => ['nidn', 'nip', 'nama', 'password', 'last_login'],
            'mahasiswa_kegiatan' => ['nim', 'kegiatan', 'tahun_akademik', 'status_kegiatan', 'preferensi_lokasi_id', 'nama_instansi_pilihan', 'alamat_instansi', 'bidang_minat', 'skill', 'motivasi', 'link_dokumen_1', 'link_dokumen_2', 'link_dokumen_3'],
            'tahun_akademiks' => ['tahun', 'semester', 'tanggal_mulai_daftar', 'tanggal_selesai_daftar'],
            'lokasi_kkn' => ['desa'], 'lokasi_ppl' => ['Sekolah'],
            'lokasi_pkls' => ['nama_instansi', 'alamat', 'kontak'],
            'lokasi_magangs' => ['nama_instansi', 'alamat', 'kontak'],
            'pembagian_lokasi_kkn' => ['nim', 'lokasi_kkn_id'],
            'Penempatan_ppl' => ['nim', 'sekolah_id'],
            'penempatan_pkls' => ['nim', 'lokasi_pkl_id'],
            'penempatan_magangs' => ['nim', 'lokasi_magang_id'],
            'pengajuan_lokasi_pkl' => ['nim', 'nama_instansi', 'alamat', 'kontak', 'status'],
            'pengajuan_lokasi_magang' => ['nim', 'nama_instansi', 'alamat', 'kontak', 'status'],
            'dosen_pembimbings' => ['nim', 'nidn', 'nilai'],
            'dosen_pengujis' => ['nim', 'nidn'],
            'pengumuman' => ['judul', 'isi'],
            'bimbingans' => ['nim', 'dosen_pembimbing_id', 'topik', 'catatan_dosen', 'status', 'tanggal_bimbingan'],
            'individu_program_kerjas' => ['nim', 'status', 'judul', 'kategori', 'tanggal_mulai', 'tanggal_selesai'],
            'dosen_monevs' => ['monev_type', 'kegiatan', 'nidn', 'nim', 'program_id', 'lokasi_id'],
            'individu_luarans' => ['individu_program_kerja_id', 'judul', 'status'],
            'notifikasis' => ['nim', 'judul', 'isi', 'tipe'],
        ];
        foreach ($tables as $name => $columns) {
            Schema::create($name, function (Blueprint $table) use ($name, $columns) {
                $table->id();
                foreach ($columns as $column) {
                    $table->string($column)->nullable();
                }
                if (in_array($name, ['mahasiswa_kegiatan', 'tahun_akademiks'])) {
                    $table->boolean('is_active')->default(false);
                }
                if (str_starts_with($name, 'lokasi_')) {
                    $table->unsignedInteger('maks_peserta')->nullable();
                }
                if ($name === 'pengumuman') {
                    $table->boolean('is_published')->default(false);
                }
                if ($name === 'notifikasis') {
                    $table->boolean('is_read')->default(false);
                }
                $table->timestamps();
            });
        }
        foreach ([
            'pembagian_lokasi_kkn' => 'pembagian_lokasi_kkn_nim_unique',
            'Penempatan_ppl' => 'penempatan_ppl_nim_unique',
            'penempatan_pkls' => 'penempatan_pkls_nim_unique',
            'penempatan_magangs' => 'penempatan_magangs_nim_unique',
        ] as $name => $index) {
            Schema::table($name, fn (Blueprint $table) => $table->unique('nim', $index));
        }
        $this->periodMigration()->up();
    }

    protected function periodMigration()
    {
        return require database_path('migrations/2026_10_10_000001_preserve_placement_periods.php');
    }
}
