<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasikkn extends Model
{

    use HasFactory;
    protected $table = 'lokasi_kkn';
    protected $fillable = ['desa', 'alamat', 'kecamatan', 'kabupaten', 'provinsi'];


    public function penempatankkn()
    {
        return $this->hasMany(Penempatankkn::class, 'lokasi_kkn_id', 'id');
    }

}
