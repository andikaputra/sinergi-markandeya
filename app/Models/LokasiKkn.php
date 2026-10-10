<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiKkn extends Model
{

    use HasFactory;
    protected $table = 'lokasi_kkn';
    protected $fillable = ['desa', 'alamat', 'kecamatan', 'kabupaten', 'provinsi', 'maks_peserta'];

    public function jumlahPendaftar(?string $tahunAkademik = null): int
    {
        return \App\Models\MahasiswaKegiatan::where('kegiatan', 'KKN')
            ->where('preferensi_lokasi_id', $this->id)
            ->where('status_kegiatan', 'aktif')
            ->when($tahunAkademik !== null, fn ($query) => $query->where('tahun_akademik', $tahunAkademik))
            ->count();
    }

    public function isFull(?string $tahunAkademik = null): bool
    {
        return $this->maks_peserta !== null && $this->jumlahPendaftar($tahunAkademik) >= $this->maks_peserta;
    }


    public function penempatankkn()
    {
        return $this->hasMany(PenempatanKkn::class, 'lokasi_kkn_id', 'id');
    }

    public function dosenMonev()
    {
        return $this->hasOne(DosenMonev::class, 'lokasi_id', 'id')->where('monev_type', 'kelompok');
    }

}
