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
        Schema::table('dosen_pembimbings', function (Blueprint $table) {
            // PKL/Magang criteria (bobot: 15%, 10%, 15% = 40%)
            $table->decimal('nilai_pkl_laporan', 5, 1)->nullable()->after('nilai');
            $table->decimal('nilai_pkl_relevansi', 5, 1)->nullable()->after('nilai_pkl_laporan');
            $table->decimal('nilai_pkl_presentasi', 5, 1)->nullable()->after('nilai_pkl_relevansi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosen_pembimbings', function (Blueprint $table) {
            $table->dropColumn([
                'nilai_pkl_laporan',
                'nilai_pkl_relevansi',
                'nilai_pkl_presentasi',
            ]);
        });
    }
};
