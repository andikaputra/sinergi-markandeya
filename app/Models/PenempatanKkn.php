<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenempatanKkn extends Model
{
    use \App\Models\Concerns\HasPlacementPeriod;

    protected $table = 'pembagian_lokasi_kkn';
    protected $fillable = ['nim', 'tahun_akademik', 'lokasi_kkn_id', 'is_ketua'];

    protected $casts = [
        'is_ketua' => 'boolean',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function lokasikkn()
    {
        return $this->belongsTo(LokasiKkn::class, 'lokasi_kkn_id', 'id');
    }
}
