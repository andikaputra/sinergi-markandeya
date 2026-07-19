<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    use HasFactory;

    protected $table = 'tahun_akademiks';
    protected $fillable = ['tahun', 'semester', 'is_active', 'tanggal_mulai_daftar', 'tanggal_selesai_daftar'];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_mulai_daftar' => 'date',
        'tanggal_selesai_daftar' => 'date',
    ];

    public static function active()
    {
        return self::where('is_active', true)->first();
    }

    public function isPendaftaranOpen(): bool
    {
        if (!$this->tanggal_mulai_daftar || !$this->tanggal_selesai_daftar) {
            return false;
        }
        $today = now()->startOfDay();
        return $today->between($this->tanggal_mulai_daftar, $this->tanggal_selesai_daftar);
    }
}
