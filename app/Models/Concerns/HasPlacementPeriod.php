<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait HasPlacementPeriod
{
    protected static function bootHasPlacementPeriod(): void
    {
        // Existing screens show the student's selected period. Historical rows remain
        // available through withoutGlobalScope('periode_aktif').
        static::addGlobalScope('periode_aktif', function (Builder $query) {
            $table = $query->getModel()->getTable();
            $query->whereExists(function ($students) use ($table) {
                $students->selectRaw('1')->from('mahasiswas as placement_student')
                    ->whereColumn('placement_student.nim', "$table.nim")
                    ->where(function ($period) use ($table) {
                        $period->whereColumn('placement_student.tahun_akademik', "$table.tahun_akademik")
                            ->orWhere(function ($legacy) use ($table) {
                                $legacy->whereNull('placement_student.tahun_akademik')
                                    ->where("$table.tahun_akademik", '');
                            });
                    });
            });
        });

        static::creating(function ($placement) {
            if ($placement->tahun_akademik === null) {
                $placement->tahun_akademik = DB::table('mahasiswas')
                    ->where('nim', $placement->nim)->value('tahun_akademik') ?? '';
            }
        });
    }
}
