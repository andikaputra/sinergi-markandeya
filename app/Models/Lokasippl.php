<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiPpl extends Model
{
    protected $table = 'lokasi_ppl';
    protected $fillable = ['Sekolah'];


    public function penempatanppl()
    {
        return $this->hasMany(PenempatanPpl::class, 'sekolah_id', 'id');
    }
}
