<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Hapus jika ada penugasan monev dengan kegiatan 'ppl' tapi ID lokasinya bukan ID lokasi PPL
        if (Schema::hasTable('lokasippls') && Schema::hasTable('dosen_monevs')) {
            $validPplIds = DB::table('lokasippls')->pluck('id')->toArray();
            DB::table('dosen_monevs')
                ->where('monev_type', 'kelompok')
                ->where('kegiatan', 'ppl')
                ->whereNotIn('lokasi_id', $validPplIds)
                ->delete();
        }

        // 2. Hapus penugasan monev kelompok yang terkait dengan Manukaya di tabel lokasikkns
        if (Schema::hasTable('lokasikkns') && Schema::hasTable('dosen_monevs')) {
            $manukayaKknIds = DB::table('lokasikkns')
                ->where('desa', 'like', '%manukaya%')
                ->pluck('id')
                ->toArray();

            if (!empty($manukayaKknIds)) {
                DB::table('dosen_monevs')
                    ->where('monev_type', 'kelompok')
                    ->whereIn('lokasi_id', $manukayaKknIds)
                    ->delete();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu rollback
    }
};
