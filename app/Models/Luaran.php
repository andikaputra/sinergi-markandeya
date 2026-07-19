<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Luaran extends Model
{
    protected $fillable = [
        'program_kerja_id',
        'judul',
        'deskripsi',
        'tipe',
        'tanggal_selesai',
        'file_path',
        'status',
        'persentase_selesai',
    ];

    protected $casts = [
        'tanggal_selesai' => 'date',
    ];

    public function programKerja()
    {
        return $this->belongsTo(ProgramKerja::class);
    }
}
