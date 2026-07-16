<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bimbingan extends Model
{
    protected $fillable = [
        'nim',
        'dosen_pembimbing_id',
        'topik',
        'deskripsi',
        'catatan_dosen',
        'status',
        'tanggal_bimbingan',
        'materi_terlampir',
    ];

    protected $casts = [
        'tanggal_bimbingan' => 'datetime',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function dosenPembimbing()
    {
        return $this->belongsTo(DosenPembimbing::class);
    }
}
