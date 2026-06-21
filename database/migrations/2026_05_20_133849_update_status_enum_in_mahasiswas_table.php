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
        // Ubah semua nilai lama ke 'aktif' dulu sebelum ubah enum
        \DB::table('mahasiswas')->whereIn('status', ['pending','ditolak'])->update(['status' => 'aktif']);

        \DB::statement("ALTER TABLE mahasiswas MODIFY status ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif'");
    }

    public function down(): void
    {
        \DB::statement("ALTER TABLE mahasiswas MODIFY status ENUM('pending','aktif','ditolak') NOT NULL DEFAULT 'pending'");
    }
};
