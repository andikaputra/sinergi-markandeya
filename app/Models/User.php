<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'kegiatan',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'kegiatan' => 'array',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function canManage(string $kegiatan): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return in_array($kegiatan, $this->getAllowedKegiatan(), true);
    }

    public function getAllowedKegiatan(): array
    {
        if ($this->isSuperAdmin()) {
            return ['KKN', 'PPL', 'PKL', 'Magang'];
        }

        $activities = $this->kegiatan;
        // Older seed data stored an already encoded JSON string in this array cast.
        if (is_string($activities)) {
            $activities = json_decode($activities, true);
        }
        return is_array($activities) ? array_values(array_intersect($activities, ['KKN', 'PPL', 'PKL', 'Magang'])) : [];
    }
}
