<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndividuProgramKerja extends Model
{
    protected $table = 'individu_program_kerjas';

    protected $fillable = [
        'nim',
        'kategori',
        'judul',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'status',
        'catatan_dosen',
        'catatan_dosen_at',
        'catatan_dosen_nidn',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'catatan_dosen_at' => 'datetime',
    ];

    public function dosenCatatan()
    {
        return $this->belongsTo(Dosen::class, 'catatan_dosen_nidn', 'nidn');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function luarans()
    {
        return $this->hasMany(IndividuLuaran::class, 'individu_program_kerja_id');
    }

    public function dosenMonev()
    {
        return $this->hasOne(DosenMonev::class, 'program_id')->where('monev_type', 'individu');
    }

    public function getMonevAttribute()
    {
        $kegiatanLower = strtolower($this->kategori ?? '');

        // 1. Prioritaskan penugasan aktif Dosen Pemonev Individu berdasarkan NIM mahasiswa
        $monevIndividu = DosenMonev::where('monev_type', 'individu')
            ->where(function ($q) use ($kegiatanLower) {
                if ($kegiatanLower) {
                    $q->where('kegiatan', $kegiatanLower)
                      ->orWhereNull('kegiatan');
                }
            })
            ->where('nim', $this->nim)
            ->first();

        if ($monevIndividu) {
            // Cek apakah ada record evaluasi khusus program ini yang dibuat oleh dosen tersebut
            $evaluasiKhusus = DosenMonev::where('monev_type', 'individu')
                ->where('nidn', $monevIndividu->nidn)
                ->where('program_id', $this->id)
                ->first();

            return $evaluasiKhusus ?: $monevIndividu;
        }

        // 2. Fallback untuk KKN, PPL, Magang: ambil dari Dosen Pemonev Kelompok/Lokasi
        if ($kegiatanLower !== 'pkl') {
            $lokasiId = match($kegiatanLower) {
                'kkn' => PenempatanKkn::where('nim', $this->nim)->value('lokasi_kkn_id'),
                'ppl' => PenempatanPpl::where('nim', $this->nim)->value('sekolah_id'),
                'magang' => PenempatanMagang::where('nim', $this->nim)->value('lokasi_magang_id'),
                default => null,
            };

            if ($lokasiId) {
                $kelompokMonev = DosenMonev::where('monev_type', 'kelompok')
                    ->where(function ($q) use ($kegiatanLower) {
                        $q->where('kegiatan', $kegiatanLower)
                          ->orWhereNull('kegiatan');
                    })
                    ->where('lokasi_id', $lokasiId)
                    ->first();

                if ($kelompokMonev) {
                    $evaluasiIndividu = DosenMonev::where('monev_type', 'individu')
                        ->where('nidn', $kelompokMonev->nidn)
                        ->where(function ($q) {
                            $q->where('program_id', $this->id)
                              ->orWhere('nim', $this->nim);
                        })
                        ->first();

                    return $evaluasiIndividu ?: $kelompokMonev;
                }
            }
        }

        // 3. Fallback jika ada relation eager loaded atau by program_id
        if ($this->relationLoaded('dosenMonev') && $this->dosenMonev) {
            return $this->dosenMonev;
        }

        return DosenMonev::where('monev_type', 'individu')
            ->where('program_id', $this->id)
            ->first();
    }
}
