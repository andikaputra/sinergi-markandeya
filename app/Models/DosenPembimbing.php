<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenPembimbing extends Model
{
    use HasFactory;
    protected $fillable = [
        'nim', 'nidn', 'nilai',
        // PKL/Magang (bobot: 15%, 10%, 15% = 40%)
        'nilai_pkl_laporan', 'nilai_pkl_relevansi', 'nilai_pkl_presentasi',
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
