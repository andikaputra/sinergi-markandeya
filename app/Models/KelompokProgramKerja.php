<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelompokProgramKerja extends Model
{
    protected $table = 'kelompok_program_kerjas';

    protected $fillable = [
        'nim_ketua',
        'kategori',
        'judul',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function mahasiswaKetua()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim_ketua', 'nim');
    }

    public function luarans()
    {
        return $this->hasMany(KelompokLuaran::class, 'kelompok_program_kerja_id');
    }

    public function dosenMonev()
    {
        return $this->hasOne(DosenMonev::class, 'program_id')->where('monev_type', 'kelompok');
    }

    public function anggota()
    {
        $ketua = $this->mahasiswaKetua;
        if (!$ketua) {
            return collect([]);
        }

        $penempatan = match ($this->kategori) {
            'kkn' => PenempatanKkn::where('nim', $ketua->nim)->first(),
            'ppl' => PenempatanPpl::where('nim', $ketua->nim)->first(),
            'pkl' => PenempatanPkl::where('nim', $ketua->nim)->first(),
            'magang' => PenempatanMagang::where('nim', $ketua->nim)->first(),
            default => null,
        };

        if (!$penempatan) {
            return collect([$ketua]);
        }

        $lokasiColumn = match ($this->kategori) {
            'kkn' => 'lokasi_kkn_id',
            'ppl' => 'lokasi_ppl_id',
            'pkl' => 'lokasi_pkl_id',
            'magang' => 'lokasi_magang_id',
            default => null,
        };

        $tabelPenempatan = match ($this->kategori) {
            'kkn' => 'penempatan_kkns',
            'ppl' => 'penempatan_ppls',
            'pkl' => 'penempatan_pkls',
            'magang' => 'penempatan_mamangs',
            default => null,
        };

        return Mahasiswa::whereIn('nim', function ($query) use ($lokasiColumn, $tabelPenempatan, $penempatan) {
            $query->select('nim')
                ->from($tabelPenempatan)
                ->where($lokasiColumn, $penempatan->getAttribute($lokasiColumn));
        })->get();
    }
}
