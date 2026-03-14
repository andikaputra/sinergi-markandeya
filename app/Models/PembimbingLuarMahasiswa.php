<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembimbingLuarMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'pembimbing_luar_mahasiswa';

    protected $fillable = [
        'nim', 'pembimbing_luar_id', 'nilai',
        // KKN
        'nilai_kehadiran', 'nilai_luaran', 'nilai_keterlibatan',
        'nilai_inovatif', 'nilai_sosialisasi',
        // PKL/Magang (bobot: 15%, 15%, 15%, 15% = 60%)
        'nilai_pkl_disiplin', 'nilai_pkl_inisiatif',
        'nilai_pkl_kualitas', 'nilai_pkl_skill',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function pembimbingLuar()
    {
        return $this->belongsTo(PembimbingLuar::class);
    }
}
