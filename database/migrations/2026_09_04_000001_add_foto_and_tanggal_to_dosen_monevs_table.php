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
        Schema::table('dosen_monevs', function (Blueprint $table) {
            if (!Schema::hasColumn('dosen_monevs', 'foto_monev')) {
                $table->json('foto_monev')->nullable()->after('catatan');
            }
            if (!Schema::hasColumn('dosen_monevs', 'tanggal_monev')) {
                $table->date('tanggal_monev')->nullable()->after('foto_monev');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosen_monevs', function (Blueprint $table) {
            if (Schema::hasColumn('dosen_monevs', 'tanggal_monev')) {
                $table->dropColumn('tanggal_monev');
            }
            if (Schema::hasColumn('dosen_monevs', 'foto_monev')) {
                $table->dropColumn('foto_monev');
            }
        });
    }
};
