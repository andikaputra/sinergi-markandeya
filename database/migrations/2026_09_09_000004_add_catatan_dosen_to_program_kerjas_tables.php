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
        if (Schema::hasTable('individu_program_kerjas')) {
            Schema::table('individu_program_kerjas', function (Blueprint $table) {
                if (!Schema::hasColumn('individu_program_kerjas', 'catatan_dosen')) {
                    $table->text('catatan_dosen')->nullable()->after('status');
                }
                if (!Schema::hasColumn('individu_program_kerjas', 'catatan_dosen_at')) {
                    $table->timestamp('catatan_dosen_at')->nullable()->after('catatan_dosen');
                }
                if (!Schema::hasColumn('individu_program_kerjas', 'catatan_dosen_nidn')) {
                    $table->string('catatan_dosen_nidn')->nullable()->after('catatan_dosen_at');
                }
            });
        }

        if (Schema::hasTable('kelompok_program_kerjas')) {
            Schema::table('kelompok_program_kerjas', function (Blueprint $table) {
                if (!Schema::hasColumn('kelompok_program_kerjas', 'catatan_dosen')) {
                    $table->text('catatan_dosen')->nullable()->after('status');
                }
                if (!Schema::hasColumn('kelompok_program_kerjas', 'catatan_dosen_at')) {
                    $table->timestamp('catatan_dosen_at')->nullable()->after('catatan_dosen');
                }
                if (!Schema::hasColumn('kelompok_program_kerjas', 'catatan_dosen_nidn')) {
                    $table->string('catatan_dosen_nidn')->nullable()->after('catatan_dosen_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('individu_program_kerjas')) {
            Schema::table('individu_program_kerjas', function (Blueprint $table) {
                $columns = ['catatan_dosen', 'catatan_dosen_at', 'catatan_dosen_nidn'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('individu_program_kerjas', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        if (Schema::hasTable('kelompok_program_kerjas')) {
            Schema::table('kelompok_program_kerjas', function (Blueprint $table) {
                $columns = ['catatan_dosen', 'catatan_dosen_at', 'catatan_dosen_nidn'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('kelompok_program_kerjas', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
