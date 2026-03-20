<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenempatanKkn extends Model
{
    use HasFactory;
    protected $table = 'pembagian_lokasi_kkn';
    protected $fillable = ['nim', 'lokasi_kkn_id'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function lokasikkn()
    {
        return $this->belongsTo(LokasiKkn::class, 'lokasi_kkn_id', 'id');
    }
}
