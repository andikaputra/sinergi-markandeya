<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\DosenMonev;
use App\Models\LokasiKkn;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Cari ID lokasi KKN Manukaya jika ada
        $manukayaLokasiIds = [];
        if (class_exists(LokasiKkn::class) && Schema::hasTable((new LokasiKkn)->getTable())) {
            $manukayaLokasiIds = LokasiKkn::where('desa', 'like', '%manukaya%')
                ->pluck('id')
                ->toArray();
        }

        // 2. Hapus penugasan monev kelompok yang mengarah ke Manukaya
        if (!empty($manukayaLokasiIds)) {
            DosenMonev::where('monev_type', 'kelompok')
                ->whereIn('lokasi_id', $manukayaLokasiIds)
                ->delete();
        }

        // 3. Hapus juga jika ada penugasan monev yang lokasi KKN-nya adalah Manukaya
        $allKelompokMonev = DosenMonev::where('monev_type', 'kelompok')->with('lokasiKkn')->get();
        foreach ($allKelompokMonev as $m) {
            if ($m->lokasiKkn && stripos($m->lokasiKkn->desa, 'manukaya') !== false) {
                $m->delete();
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
