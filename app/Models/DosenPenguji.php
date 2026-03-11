<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenPenguji extends Model
{
    use HasFactory;

    protected $table = 'dosen_pengujis';
    protected $fillable = ['nim', 'nidn', 'nilai'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nidn', 'nidn');
    }
}
