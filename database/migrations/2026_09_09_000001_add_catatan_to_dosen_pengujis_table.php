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
            if (!Schema::hasColumn('dosen_pengujis', 'catatan')) {
                $table->text('catatan')->nullable()->after('nilai');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosen_pengujis', function (Blueprint $table) {
            if (Schema::hasColumn('dosen_pengujis', 'catatan')) {
                $table->dropColumn('catatan');
            }
        });
    }
};
