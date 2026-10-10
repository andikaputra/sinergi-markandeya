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
        if (Schema::hasTable('pembagian_lokasi_kkn') && !Schema::hasColumn('pembagian_lokasi_kkn', 'is_ketua')) {
            Schema::table('pembagian_lokasi_kkn', function (Blueprint $table) {
                $table->boolean('is_ketua')->default(false)->after('lokasi_kkn_id');
            });
        }

        if (Schema::hasTable('Penempatan_ppl') && !Schema::hasColumn('Penempatan_ppl', 'is_ketua')) {
            Schema::table('Penempatan_ppl', function (Blueprint $table) {
                $table->boolean('is_ketua')->default(false)->after('sekolah_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pembagian_lokasi_kkn') && Schema::hasColumn('pembagian_lokasi_kkn', 'is_ketua')) {
            Schema::table('pembagian_lokasi_kkn', function (Blueprint $table) {
                $table->dropColumn('is_ketua');
            });
        }

        if (Schema::hasTable('Penempatan_ppl') && Schema::hasColumn('Penempatan_ppl', 'is_ketua')) {
            Schema::table('Penempatan_ppl', function (Blueprint $table) {
                $table->dropColumn('is_ketua');
            });
        }
    }
};
