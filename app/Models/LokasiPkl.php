<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiPkl extends Model
{
    use HasFactory;

    protected $table = 'lokasi_pkls';
    protected $fillable = ['nama_instansi', 'alamat', 'kontak', 'email', 'website'];

    public function penempatanpkl()
    {
        return $this->hasMany(PenempatanPkl::class, 'lokasi_pkl_id');
    }
}
