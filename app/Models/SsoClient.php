<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class SsoClient extends Model
{
    protected $fillable = [
        'name', 'client_id', 'client_secret',
        'redirect_uris', 'allowed_roles', 'logo', 'active',
    ];

    protected $casts = [
        'redirect_uris' => 'array',
        'allowed_roles' => 'array',
        'active'        => 'boolean',
    ];

    public function verifySecret(string $secret): bool
    {
        return Hash::check($secret, $this->client_secret);
    }

    public function allowsRole(string $role): bool
    {
        return in_array($role, $this->allowed_roles);
    }

    public function allowsRedirect(string $uri): bool
    {
        return in_array($uri, $this->redirect_uris);
    }
}
