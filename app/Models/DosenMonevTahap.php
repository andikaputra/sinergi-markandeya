<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DosenMonevTahap extends Model
{
    protected $table = 'dosen_monev_tahaps';

    protected $fillable = [
        'dosen_monev_id',
        'tahap_ke',
        'tanggal_monev',
        'catatan',
        'nilai',
        'link_monev',
        'foto_monev',
    ];

    protected $casts = [
        'tahap_ke' => 'integer',
        'nilai' => 'float',
        'tanggal_monev' => 'date',
        'foto_monev' => 'array',
    ];

    public function dosenMonev()
    {
        return $this->belongsTo(DosenMonev::class, 'dosen_monev_id');
    }

    /**
     * Get label for this tahap
     */
    public function getTahapLabelAttribute(): string
    {
        return match ($this->tahap_ke) {
            1 => 'Monev 1 (Tahap Awal)',
            2 => 'Monev 2 (Tahap Progres/Tengah)',
            3 => 'Monev 3 (Tahap Evaluasi Akhir)',
            default => 'Monev ' . $this->tahap_ke,
        };
    }

    /**
     * Check if this tahap has been filled / submitted
     */
    public function getIsFilledAttribute(): bool
    {
        $photos = $this->foto_monev ?? [];
        if (is_string($photos)) $photos = json_decode($photos, true) ?? [$photos];
        if (!is_array($photos)) $photos = [$photos];
        return trim((string) $this->catatan) !== ''
            || !is_null($this->nilai)
            || count(array_filter($photos, fn ($photo) => is_string($photo) && trim($photo) !== '')) > 0
            || trim((string) $this->link_monev) !== ''
            || !empty($this->tanggal_monev);
    }

    /**
     * Get array of full public URLs for monev photos
     */
    public function getFotoUrlsAttribute(): array
    {
        if (empty($this->foto_monev)) {
            return [];
        }

        $photos = $this->foto_monev;
        if (is_string($photos)) {
            $decoded = json_decode($photos, true);
            $photos = is_array($decoded) ? $decoded : [$photos];
        }

        if (!is_array($photos)) {
            return [];
        }

        $validPhotos = array_filter($photos, fn($p) => !empty($p) && is_string($p));

        return array_values(array_map(function ($path) {
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
            }
            return asset('storage/' . ltrim($path, '/'));
        }, $validPhotos));
    }
}
