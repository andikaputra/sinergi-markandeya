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
        Schema::table('tahun_akademiks', function (Blueprint $table) {
            $table->date('tanggal_mulai_daftar')->nullable()->after('is_active');
            $table->date('tanggal_selesai_daftar')->nullable()->after('tanggal_mulai_daftar');
        });
    }

    public function down(): void
    {
        Schema::table('tahun_akademiks', function (Blueprint $table) {
            $table->dropColumn(['tanggal_mulai_daftar', 'tanggal_selesai_daftar']);
        });
    }
};
