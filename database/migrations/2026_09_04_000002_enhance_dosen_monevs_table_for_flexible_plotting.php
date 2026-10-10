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
        if (Schema::hasTable('dosen_monevs')) {
            // Drop unique constraint on (monev_type, program_id) if it exists so multiple records with null program_id can be created
            try {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE `dosen_monevs` DROP INDEX `dosen_monevs_monev_type_program_id_unique`");
            } catch (\Throwable $e) {}

            try {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE `dosen_monevs` MODIFY `program_id` BIGINT NULL");
            } catch (\Throwable $e) {}

            Schema::table('dosen_monevs', function (Blueprint $table) {
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
