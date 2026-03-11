<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenempatanMagang extends Model
{
    use HasFactory;

    protected $table = 'penempatan_magangs';
    protected $fillable = ['nim', 'lokasi_magang_id'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function lokasimagang()
    {
        return $this->belongsTo(LokasiMagang::class, 'lokasi_magang_id', 'id');
    }
}
