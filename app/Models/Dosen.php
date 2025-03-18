<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{

    protected $fillable = [
        'nidn',
        'nama',
        'password',
    ];

    public function mahasiswaBimbingan()
{
    return $this->hasMany(DosenPembimbing::class, 'nidn', 'nidn');
}


}
