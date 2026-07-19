<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';
    protected $fillable = ['judul', 'isi', 'target', 'is_published', 'published_at', 'admin_id'];
    protected $casts = ['is_published' => 'boolean', 'published_at' => 'datetime'];

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->orderByDesc('published_at');
    }

    public function scopeUntukKegiatan($query, ?string $kegiatan)
    {
        return $query->where(function ($q) use ($kegiatan) {
            $q->where('target', 'semua');
            if ($kegiatan) $q->orWhere('target', $kegiatan);
        });
    }

    public function publish()
    {
        $this->update(['is_published' => true, 'published_at' => now()]);
    }

    public function unpublish()
    {
        $this->update(['is_published' => false, 'published_at' => null]);
    }
}
