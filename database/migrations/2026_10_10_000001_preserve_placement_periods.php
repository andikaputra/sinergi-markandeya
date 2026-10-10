<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $placements = [
        'pembagian_lokasi_kkn' => ['KKN', 'pembagian_lokasi_kkn_nim_unique'],
        'Penempatan_ppl' => ['PPL', 'penempatan_ppl_nim_unique'],
        'penempatan_pkls' => ['PKL', 'penempatan_pkls_nim_unique'],
        'penempatan_magangs' => ['Magang', 'penempatan_magangs_nim_unique'],
        'pengajuan_lokasi_pkl' => ['PKL', null],
        'pengajuan_lokasi_magang' => ['Magang', null],
    ];

    public function up(): void
    {
        foreach ($this->placements as $name => [$activity, $index]) {
            Schema::table($name, function (Blueprint $table) {
                $table->string('tahun_akademik')->default('');
            });

            // Prefer a registration for the corresponding activity when backfilling.
            DB::table($name)->orderBy('id')->chunkById(200, function ($rows) use ($name, $activity) {
                foreach ($rows as $row) {
                    $period = DB::table('mahasiswa_kegiatan')->where('nim', $row->nim)
                        ->where('kegiatan', $activity)->orderByDesc('is_active')
                        ->orderByDesc('id')->value('tahun_akademik');
                    $period ??= DB::table('mahasiswas')->where('nim', $row->nim)->value('tahun_akademik');
                    DB::table($name)->where('id', $row->id)->update(['tahun_akademik' => $period ?? '']);
                }
            });

            if ($index !== null) {
                Schema::table($name, function (Blueprint $table) use ($index) {
                    $table->dropUnique($index);
                    $table->unique(['nim', 'tahun_akademik']);
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->placements as $name => [$activity, $index]) {
            if ($index !== null && DB::table($name)->select('nim')->groupBy('nim')->havingRaw('COUNT(*) > 1')->exists()) {
                throw new RuntimeException('Rollback dibatalkan: terdapat riwayat penempatan beberapa periode.');
            }
        }
        foreach ($this->placements as $name => [$activity, $index]) {
            Schema::table($name, function (Blueprint $table) use ($index) {
                if ($index !== null) {
                    $table->dropUnique(['nim', 'tahun_akademik']);
                    $table->unique('nim', $index);
                }
                $table->dropColumn('tahun_akademik');
            });
        }
    }
};
