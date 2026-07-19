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
            $table->enum('status_kegiatan', ['aktif', 'selesai', 'dibatalkan'])->default('aktif')->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa_kegiatan', function (Blueprint $table) {
            $table->dropColumn('status_kegiatan');
        });
    }
};
