<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenempatanPkl extends Model
{
    use \App\Models\Concerns\HasPlacementPeriod;

    use HasFactory;

    protected $table = 'penempatan_pkls';
    protected $fillable = ['nim', 'tahun_akademik', 'lokasi_pkl_id'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function lokasipkl()
    {
        return $this->belongsTo(LokasiPkl::class, 'lokasi_pkl_id', 'id');
    }
}
