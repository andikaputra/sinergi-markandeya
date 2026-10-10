<?php

namespace App\Services;

use App\Models\Dosen;
use App\Models\DosenMonev;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class MonevMonitoring
{
    public function assignments(Builder $query): Collection
    {
        $lecturers = Dosen::orderBy('id')->get();
        $identifiers = [];
        foreach ($lecturers as $lecturer) {
            if ($lecturer->nidn) {
                $identifiers[(string) $lecturer->nidn] = $lecturer;
            }
        }
        foreach ($lecturers as $lecturer) {
            foreach ($lecturer->getAllNidns() as $identifier) {
                $identifiers[(string) $identifier] ??= $lecturer;
            }
        }

        return $query->with([
            'tahaps', 'mahasiswa.activeKegiatan', 'individuProgram.mahasiswa.activeKegiatan',
            'kelompokProgram.mahasiswaKetua.activeKegiatan', 'lokasiKkn', 'lokasiPpl', 'lokasiPkl', 'lokasiMagang',
        ])->orderBy('id')->get()->map(function (DosenMonev $assignment) use ($identifiers) {
            $program = $assignment->monev_type === 'individu' ? $assignment->individuProgram : $assignment->kelompokProgram;
            $student = $assignment->mahasiswa ?? ($assignment->monev_type === 'individu' ? $program?->mahasiswa : $program?->mahasiswaKetua);
            $activity = strtolower($assignment->kegiatan ?: ($program?->kategori ?: $student?->kegiatan ?? ''));
            $location = match ($activity) {
                'kkn' => $assignment->lokasiKkn?->desa,
                'ppl' => $assignment->lokasiPpl?->Sekolah,
                'pkl' => $assignment->lokasiPkl?->nama_instansi,
                'magang' => $assignment->lokasiMagang?->nama_instansi,
                default => null,
            };
            $stages = $assignment->recordedStages();
            $count = count(array_filter($stages));
            $dates = $assignment->tahaps->filter(fn ($stage) => $stage->is_filled)->pluck('tanggal_monev')->filter();
            if ($assignment->tahaps->isEmpty() && $count > 0 && $assignment->tanggal_monev) {
                $dates->push($assignment->tanggal_monev);
            }
            $lecturer = $identifiers[(string) $assignment->nidn] ?? null;

            return [
                'id' => $assignment->id, 'dosen' => $lecturer, 'nidn' => $assignment->nidn,
                'dosen_key' => $lecturer ? 'dosen:'.$lecturer->id : 'unknown:'.$assignment->nidn,
                'activity' => $activity, 'type' => $assignment->monev_type,
                'target' => $assignment->monev_type === 'individu' ? ($student?->nama ?? $assignment->nim ?? $program?->judul ?? 'Mahasiswa belum tersedia') : ($location ?? $program?->judul ?? 'Kelompok #'.$assignment->lokasi_id),
                'nim' => $student?->nim ?? $assignment->nim, 'program' => $program?->judul,
                'stages' => $stages, 'stage_count' => $count,
                'status' => $count === 0 ? 'belum' : ($count === 3 ? 'lengkap' : 'berjalan'),
                'last_monev' => $dates->sort()->last(),
            ];
        });
    }

    public function lecturers(Collection $rows): Collection
    {
        return $rows->groupBy('dosen_key')->map(function ($assignments) {
            $first = $assignments->first();
            $started = $assignments->where('stage_count', '>', 0)->count();
            $complete = $assignments->where('stage_count', 3)->count();

            return [
                'dosen' => $first['dosen'], 'nidn' => $first['nidn'], 'assignments' => $assignments->values(),
                'total' => $assignments->count(), 'belum' => $assignments->where('stage_count', 0)->count(),
                'berjalan' => $started - $complete, 'lengkap' => $complete,
                'status' => $started === 0 ? 'belum_mulai' : ($complete === $assignments->count() ? 'lengkap' : 'berjalan'),
                'last_monev' => $assignments->pluck('last_monev')->filter()->sort()->last(),
            ];
        })->sortBy(fn ($row) => mb_strtolower($row['dosen']?->nama ?? $row['nidn']))->values();
    }
}
