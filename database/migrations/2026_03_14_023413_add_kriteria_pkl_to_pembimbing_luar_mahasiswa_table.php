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
        Schema::table('pembimbing_luar_mahasiswa', function (Blueprint $table) {
            // PKL criteria (bobot: 10%, 5%, 10%, 10%, 5% = 40%)
            $table->decimal('nilai_pkl_kehadiran', 5, 1)->nullable()->after('nilai_sosialisasi');
            $table->decimal('nilai_pkl_pemahaman', 5, 1)->nullable()->after('nilai_pkl_kehadiran');
            $table->decimal('nilai_pkl_kerjasama', 5, 1)->nullable()->after('nilai_pkl_pemahaman');
            $table->decimal('nilai_pkl_pengetahuan', 5, 1)->nullable()->after('nilai_pkl_kerjasama');
            $table->decimal('nilai_pkl_laporan', 5, 1)->nullable()->after('nilai_pkl_pengetahuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembimbing_luar_mahasiswa', function (Blueprint $table) {
            $table->dropColumn([
                'nilai_pkl_kehadiran',
                'nilai_pkl_pemahaman',
                'nilai_pkl_kerjasama',
                'nilai_pkl_pengetahuan',
                'nilai_pkl_laporan',
            ]);
        });
    }
};
