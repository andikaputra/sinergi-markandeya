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

        return is_array($this->kegiatan) && in_array($kegiatan, $this->kegiatan);
    }

    public function getAllowedKegiatan(): array
    {
        if ($this->isSuperAdmin()) {
            return ['KKN', 'PPL', 'PKL', 'Magang'];
        }

        return $this->kegiatan ?? [];
    }
}
