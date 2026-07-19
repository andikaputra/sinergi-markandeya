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
}
