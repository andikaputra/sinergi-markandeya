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
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function luarans()
    {
        return $this->hasMany(IndividuLuaran::class, 'individu_program_kerja_id');
    }
}
