<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mahasiswa_kegiatan', function (Blueprint $table) {
            // KKN: pilih lokasi dari dropdown; PPL: pilih sekolah dari dropdown
            $table->unsignedBigInteger('preferensi_lokasi_id')->nullable()->after('status_kegiatan');
            // PKL / Magang: nama instansi yang diinginkan (free text)
            $table->string('nama_instansi_pilihan')->nullable()->after('preferensi_lokasi_id');
            $table->string('alamat_instansi')->nullable()->after('nama_instansi_pilihan');
            // PPL: mata pelajaran; PKL/Magang: bidang kerja / posisi
            $table->string('bidang_minat')->nullable()->after('alamat_instansi');
            // Semua kegiatan
            $table->text('skill')->nullable()->after('bidang_minat');
            $table->text('motivasi')->nullable()->after('skill');
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa_kegiatan', function (Blueprint $table) {
            $table->dropColumn(['preferensi_lokasi_id','nama_instansi_pilihan','alamat_instansi','bidang_minat','skill','motivasi']);
        });
    }
};
