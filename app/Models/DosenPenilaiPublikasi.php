<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenPenilaiPublikasi extends Model
{
    use HasFactory;

    protected $table = 'dosen_penilai_publikasis';

    protected $fillable = [
        'nim', 'nidn', 'nilai',
        'nilai_ketercapaian', 'nilai_sistematika', 'nilai_kelayakan',
        'nilai_presentasi', 'nilai_mempertahankan',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nidn', 'nidn');
    }
}
