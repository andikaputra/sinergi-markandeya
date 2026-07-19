<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SsoAccessToken extends Model
{
    protected $fillable = [
        'token', 'client_id', 'user_type', 'user_id',
        'user_data', 'abilities', 'last_used_at', 'expires_at', 'revoked_at',
    ];

    protected $casts = [
        'user_data'    => 'array',
        'abilities'    => 'array',
        'last_used_at' => 'datetime',
        'expires_at'   => 'datetime',
        'revoked_at'   => 'datetime',
    ];

    public static function issue(string $clientId, string $userType, int $userId, array $userData, array $abilities = []): self
    {
        $ttlHours = $userType === 'mahasiswa' ? 24 : 8;

        return static::create([
            'token'      => Str::random(80),
            'client_id'  => $clientId,
            'user_type'  => $userType,
            'user_id'    => $userId,
            'user_data'  => $userData,
            'abilities'  => $abilities,
            'expires_at' => now()->addHours($ttlHours),
        ]);
    }

    public function isValid(): bool
    {
        return $this->revoked_at === null && $this->expires_at->isFuture();
    }

    public function touchLastUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }

    public function revoke(): void
    {
        $this->update(['revoked_at' => now()]);
    }

    public function can(string $ability): bool
    {
        return in_array($ability, $this->abilities ?? []);
    }

    // Revoke all tokens for a user across all clients
    public static function revokeAllFor(string $userType, int $userId): int
    {
        return static::where('user_type', $userType)
            ->where('user_id', $userId)
            ->whereNull('revoked_at')
            ->update(['revoked_at' => now()]);
    }
}
