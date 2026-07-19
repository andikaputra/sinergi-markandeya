<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiPkl extends Model
{
    use HasFactory;

    protected $table = 'lokasi_pkls';
    protected $fillable = ['nama_instansi', 'alamat', 'kontak', 'email', 'website', 'maks_peserta'];

    public function jumlahPendaftar(): int
    {
        return $this->penempatanpkl()->count();
    }

    public function isFull(): bool
    {
        return $this->maks_peserta !== null && $this->jumlahPendaftar() >= $this->maks_peserta;
    }

    public function penempatanpkl()
    {
        return $this->hasMany(PenempatanPkl::class, 'lokasi_pkl_id');
    }
}
