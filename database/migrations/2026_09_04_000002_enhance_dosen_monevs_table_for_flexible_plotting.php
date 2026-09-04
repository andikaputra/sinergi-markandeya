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
            // Make program_id nullable if it's not already
            $table->bigInteger('program_id')->nullable()->change();

            if (!Schema::hasColumn('dosen_monevs', 'nim')) {
                $table->string('nim')->nullable()->after('nidn');
                $table->index('nim');
            }

            if (!Schema::hasColumn('dosen_monevs', 'kegiatan')) {
                $table->string('kegiatan')->nullable()->after('monev_type');
                $table->index('kegiatan');
            }

            if (!Schema::hasColumn('dosen_monevs', 'lokasi_id')) {
                $table->bigInteger('lokasi_id')->nullable()->after('program_id');
                $table->index('lokasi_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosen_monevs', function (Blueprint $table) {
            if (Schema::hasColumn('dosen_monevs', 'lokasi_id')) {
                $table->dropColumn('lokasi_id');
            }
            if (Schema::hasColumn('dosen_monevs', 'kegiatan')) {
                $table->dropColumn('kegiatan');
            }
            if (Schema::hasColumn('dosen_monevs', 'nim')) {
                $table->dropColumn('nim');
            }
        });
    }
};
