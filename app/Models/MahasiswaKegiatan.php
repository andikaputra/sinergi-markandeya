<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahasiswaKegiatan extends Model
{
    protected $table = 'mahasiswa_kegiatan';

    protected $fillable = [
        'nim',
        'kegiatan',
        'tahun_akademik',
        'is_active',
        'status_kegiatan',
        'preferensi_lokasi_id',
        'nama_instansi_pilihan',
        'alamat_instansi',
        'bidang_minat',
        'skill',
        'motivasi',
        'link_dokumen_1',
        'link_dokumen_2',
        'link_dokumen_3',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getStatusLabelAttribute(): string
    {
        return match($this->status_kegiatan) {
            'selesai'    => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default      => 'Berlangsung',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status_kegiatan) {
            'selesai'    => 'emerald',
            'dibatalkan' => 'red',
            default      => 'blue',
        };
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }
}
