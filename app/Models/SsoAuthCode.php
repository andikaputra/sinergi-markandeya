<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SsoAuthCode extends Model
{
    protected $fillable = [
        'code', 'client_id', 'user_type', 'user_id', 'redirect_uri', 'expires_at', 'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at'    => 'datetime',
    ];

    public static function generate(string $clientId, string $userType, int $userId, string $redirectUri): self
    {
        // Clean up expired codes
        static::where('expires_at', '<', now())->delete();

        return static::create([
            'code'         => Str::random(64),
            'client_id'    => $clientId,
            'user_type'    => $userType,
            'user_id'      => $userId,
            'redirect_uri' => $redirectUri,
            'expires_at'   => now()->addSeconds(60),
        ]);
    }

    public function isValid(): bool
    {
        return $this->used_at === null && $this->expires_at->isFuture();
    }

    public function markUsed(): void
    {
        $this->update(['used_at' => now()]);
    }
}
