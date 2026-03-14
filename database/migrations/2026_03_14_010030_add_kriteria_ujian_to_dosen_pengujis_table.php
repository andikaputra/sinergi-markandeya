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
        Schema::table('dosen_pengujis', function (Blueprint $table) {
            $table->decimal('nilai_keterlaksanaan', 5, 1)->nullable()->after('nidn');
            $table->decimal('nilai_kontribusi', 5, 1)->nullable()->after('nilai_keterlaksanaan');
            $table->decimal('nilai_kerjasama', 5, 1)->nullable()->after('nilai_kontribusi');
            $table->decimal('nilai_kreativitas', 5, 1)->nullable()->after('nilai_kerjasama');
            $table->decimal('nilai_partisipasi', 5, 1)->nullable()->after('nilai_kreativitas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosen_pengujis', function (Blueprint $table) {
            $table->dropColumn([
                'nilai_keterlaksanaan',
                'nilai_kontribusi',
                'nilai_kerjasama',
                'nilai_kreativitas',
                'nilai_partisipasi',
            ]);
        });
    }
};
