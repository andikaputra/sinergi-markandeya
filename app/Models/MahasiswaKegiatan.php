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
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }
}
