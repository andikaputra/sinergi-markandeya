<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenempatanPpl extends Model
{
    //

    protected $table = 'Penempatan_ppl';
    protected $fillable = ['nim', 'sekolah_id'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function lokasippl()
    {
        return $this->belongsTo(LokasiPpl::class, 'sekolah_id', 'id');
    }
}
