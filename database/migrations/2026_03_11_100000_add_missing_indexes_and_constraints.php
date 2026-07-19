<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Index on mahasiswas for frequent queries
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->index('kegiatan');
            $table->index('tahun_akademik');
            $table->index('kecamatan');
            $table->index('prodi');
        });

        // Unique constraint on dosen_pembimbings (1 pembimbing per mahasiswa)
        Schema::table('dosen_pembimbings', function (Blueprint $table) {
            $table->unique('nim', 'dosen_pembimbings_nim_unique');
            $table->index('nidn');
        });

        // Unique constraint on dosen_pengujis (1 penguji per mahasiswa)
        Schema::table('dosen_pengujis', function (Blueprint $table) {
            $table->unique('nim', 'dosen_pengujis_nim_unique');
            $table->index('nidn');
        });

        // Unique nim on placement tables (1 placement per mahasiswa)
        Schema::table('pembagian_lokasi_kkn', function (Blueprint $table) {
            $table->unique('nim', 'pembagian_lokasi_kkn_nim_unique');
            $table->index('lokasi_kkn_id');
        });

        Schema::table('Penempatan_ppl', function (Blueprint $table) {
            $table->unique('nim', 'penempatan_ppl_nim_unique');
            $table->index('sekolah_id');
        });

        Schema::table('penempatan_pkls', function (Blueprint $table) {
            $table->unique('nim', 'penempatan_pkls_nim_unique');
            $table->index('lokasi_pkl_id');
        });

        Schema::table('penempatan_magangs', function (Blueprint $table) {
            $table->unique('nim', 'penempatan_magangs_nim_unique');
            $table->index('lokasi_magang_id');
        });

        // Index on jurnals
        Schema::table('jurnals', function (Blueprint $table) {
            $table->index('nim');
            $table->index('tanggal');
        });

        // Index on publikasis
        Schema::table('publikasis', function (Blueprint $table) {
            $table->index('nim');
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropIndex(['kegiatan']);
            $table->dropIndex(['tahun_akademik']);
            $table->dropIndex(['kecamatan']);
            $table->dropIndex(['prodi']);
        });

        Schema::table('dosen_pembimbings', function (Blueprint $table) {
            $table->dropUnique('dosen_pembimbings_nim_unique');
            $table->dropIndex(['nidn']);
        });

        Schema::table('dosen_pengujis', function (Blueprint $table) {
            $table->dropUnique('dosen_pengujis_nim_unique');
            $table->dropIndex(['nidn']);
        });

        Schema::table('pembagian_lokasi_kkn', function (Blueprint $table) {
            $table->dropUnique('pembagian_lokasi_kkn_nim_unique');
            $table->dropIndex(['lokasi_kkn_id']);
        });

        Schema::table('Penempatan_ppl', function (Blueprint $table) {
            $table->dropUnique('penempatan_ppl_nim_unique');
            $table->dropIndex(['sekolah_id']);
        });

        Schema::table('penempatan_pkls', function (Blueprint $table) {
            $table->dropUnique('penempatan_pkls_nim_unique');
            $table->dropIndex(['lokasi_pkl_id']);
        });

        Schema::table('penempatan_magangs', function (Blueprint $table) {
            $table->dropUnique('penempatan_magangs_nim_unique');
            $table->dropIndex(['lokasi_magang_id']);
        });

        Schema::table('jurnals', function (Blueprint $table) {
            $table->dropIndex(['nim']);
            $table->dropIndex(['tanggal']);
        });

        Schema::table('publikasis', function (Blueprint $table) {
            $table->dropIndex(['nim']);
        });
    }
};
