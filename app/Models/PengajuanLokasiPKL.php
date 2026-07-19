<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanLokasiPKL extends Model
{
    use HasFactory;
    
    protected $table = 'pengajuan_lokasi_pkl';
    protected $fillable = ['nim', 'nama_instansi', 'alamat', 'kontak', 'status'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }
}

