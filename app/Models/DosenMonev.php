<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DosenMonev extends Model
{
    protected $fillable = [
        'nidn',
        'nim',
        'kegiatan',
        'monev_type',
        'program_id',
        'lokasi_id',
        'nilai',
        'catatan',
        'foto_monev',
        'link_monev',
        'tanggal_monev',
    ];

    protected $casts = [
        'foto_monev' => 'array',
        'tanggal_monev' => 'date',
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nidn', 'nidn');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function programKerja()
    {
        if ($this->monev_type === 'individu') {
            return $this->belongsTo(IndividuProgramKerja::class, 'program_id');
        }
        return $this->belongsTo(KelompokProgramKerja::class, 'program_id');
    }

    public function individuProgram()
    {
        return $this->belongsTo(IndividuProgramKerja::class, 'program_id');
    }

    public function kelompokProgram()
    {
        return $this->belongsTo(KelompokProgramKerja::class, 'program_id');
    }

    public function recordedStages(): array
    {
        $stages = [];
        foreach ([1, 2, 3] as $number) {
            $stage = $this->getTahap($number);
            $stages[$number] = $stage->exists && $stage->is_filled;
        }
        // Parent fields can mirror stage 2/3 too. Use legacy data only when
        // there are no structured stages, so one submission is not counted twice.
        if ($this->tahaps->isEmpty()) {
            $legacy = new DosenMonevTahap($this->only(['catatan', 'nilai', 'foto_monev', 'link_monev', 'tanggal_monev']));
            $stages[1] = $legacy->is_filled;
        }
        return $stages;
    }

    public function lokasiKkn()
    {
        return $this->belongsTo(LokasiKkn::class, 'lokasi_id');
    }

    public function lokasiPpl()
    {
        return $this->belongsTo(LokasiPpl::class, 'lokasi_id');
    }

    public function lokasiPkl()
    {
        return $this->belongsTo(LokasiPkl::class, 'lokasi_id');
    }

    public function lokasiMagang()
    {
        return $this->belongsTo(LokasiMagang::class, 'lokasi_id');
    }

    /**
     * Relationship to all 3 monev stages (Tahap 1, 2, 3)
     */
    public function tahaps()
    {
        return $this->hasMany(DosenMonevTahap::class, 'dosen_monev_id')->orderBy('tahap_ke', 'asc');
    }

    /**
     * Get specific Tahap instance (1, 2, or 3)
     */
    public function getTahap(int $ke): DosenMonevTahap
    {
        if ($this->relationLoaded('tahaps')) {
            $found = $this->tahaps->firstWhere('tahap_ke', $ke);
            if ($found) {
                return $found;
            }
        } else {
            $found = $this->tahaps()->where('tahap_ke', $ke)->first();
            if ($found) {
                return $found;
            }
        }

        // Return a fresh unsaved instance for smooth blade rendering
        return new DosenMonevTahap([
            'dosen_monev_id' => $this->id,
            'tahap_ke' => $ke,
            'tanggal_monev' => null,
            'catatan' => null,
            'nilai' => null,
            'link_monev' => null,
            'foto_monev' => null,
        ]);
    }

    public function getTahap1Attribute(): DosenMonevTahap
    {
        return $this->getTahap(1);
    }

    public function getTahap2Attribute(): DosenMonevTahap
    {
        return $this->getTahap(2);
    }

    public function getTahap3Attribute(): DosenMonevTahap
    {
        return $this->getTahap(3);
    }

    /**
     * Get a standardized list of all 3 tahaps (1, 2, 3)
     */
    public function getAllTahapsListAttribute(): array
    {
        return [
            1 => $this->getTahap(1),
            2 => $this->getTahap(2),
            3 => $this->getTahap(3),
        ];
    }

    /**
     * Total number of completed monev stages out of 3
     */
    public function getMonevSelesaiCountAttribute(): int
    {
        return count(array_filter($this->recordedStages()));
    }

    /**
     * Average grade from all evaluated tahaps
     */
    public function getRataRataNilaiAttribute(): ?float
    {
        $scores = [];
        foreach ([1, 2, 3] as $ke) {
            $t = $this->getTahap($ke);
            if ($t->exists && !is_null($t->nilai)) {
                $scores[] = $t->nilai;
            }
        }

        if (empty($scores)) {
            return !is_null($this->nilai) ? (float)$this->nilai : null;
        }

        return round(array_sum($scores) / count($scores), 2);
    }

    /**
     * Get array of full public URLs for monev photos (legacy or tahap 1 fallback)
     */
    public function getFotoUrlsAttribute(): array
    {
        $t1 = $this->getTahap(1);
        if ($t1->exists && !empty($t1->foto_urls)) {
            return $t1->foto_urls;
        }

        if (empty($this->foto_monev)) {
            return [];
        }

        $photos = $this->foto_monev;
        if (is_string($photos)) {
            $decoded = json_decode($photos, true);
            $photos = is_array($decoded) ? $decoded : [$photos];
        }

        if (!is_array($photos)) {
            return [];
        }

        $validPhotos = array_filter($photos, fn($p) => !empty($p) && is_string($p));

        return array_values(array_map(function ($path) {
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
            }
            return asset('storage/' . ltrim($path, '/'));
        }, $validPhotos));
    }
}
