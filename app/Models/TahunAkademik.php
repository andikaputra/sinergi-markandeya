<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    use HasFactory;

    protected $table = 'tahun_akademiks';
    protected $fillable = ['tahun', 'semester', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Helper untuk mendapatkan tahun akademik yang aktif
    public static function active()
    {
        return self::where('is_active', true)->first();
    }
}
