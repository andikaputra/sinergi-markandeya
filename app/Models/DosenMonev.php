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

