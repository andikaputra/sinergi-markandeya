<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DosenMonev extends Model
{
    protected $fillable = [
        'nidn',
        'monev_type',
        'program_id',
        'nilai',
        'catatan',
        'foto_monev',
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

    public function programKerja()
    {
        if ($this->monev_type === 'individu') {
            return $this->belongsTo(IndividuProgramKerja::class, 'program_id');
        }
        return $this->belongsTo(KelompokProgramKerja::class, 'program_id');
    }

    /**
     * Get array of full public URLs for monev photos
     */
    public function getFotoUrlsAttribute(): array
    {
        if (empty($this->foto_monev) || !is_array($this->foto_monev)) {
            return [];
        }

        return array_map(function ($path) {
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
            }
            return asset('storage/' . ltrim($path, '/'));
        }, $this->foto_monev);
    }
}

