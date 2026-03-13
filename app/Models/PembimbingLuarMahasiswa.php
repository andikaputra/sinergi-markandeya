<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembimbingLuarMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'pembimbing_luar_mahasiswa';

    protected $fillable = ['nim', 'pembimbing_luar_id', 'nilai'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function pembimbingLuar()
    {
        return $this->belongsTo(PembimbingLuar::class);
    }
}
