<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lokasippl extends Model
{
    protected $table = 'lokasi_ppl';
    protected $fillable = ['Sekolah'];


    public function penempatanppl()
    {
        return $this->hasMany(Penempatanppl::class, 'sekolah_id', 'id');
    }
}
