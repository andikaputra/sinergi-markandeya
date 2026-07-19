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
            $table->decimal('nilai_kehadiran', 5, 1)->nullable()->after('pembimbing_luar_id');
            $table->decimal('nilai_luaran', 5, 1)->nullable()->after('nilai_kehadiran');
            $table->decimal('nilai_keterlibatan', 5, 1)->nullable()->after('nilai_luaran');
            $table->decimal('nilai_inovatif', 5, 1)->nullable()->after('nilai_keterlibatan');
            $table->decimal('nilai_sosialisasi', 5, 1)->nullable()->after('nilai_inovatif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembimbing_luar_mahasiswa', function (Blueprint $table) {
            $table->dropColumn([
                'nilai_kehadiran',
                'nilai_luaran',
                'nilai_keterlibatan',
                'nilai_inovatif',
                'nilai_sosialisasi',
            ]);
        });
    }
};
