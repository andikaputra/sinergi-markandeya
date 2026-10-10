<?php

namespace App\Services;

use App\Models\Dosen;
use App\Models\Mahasiswa;

class LoginActivityRecorder
{
    public function record($user, bool $force = false): void
    {
        if (! $user instanceof Dosen && ! $user instanceof Mahasiswa) {
            return;
        }
        if ($user instanceof Mahasiswa && $user->status === 'nonaktif') {
            return;
        }
        if (! $force && $user->last_login && $user->last_login->greaterThan(now()->subMinute())) {
            return;
        }
        $user->forceFill(['last_login' => now()])->saveQuietly();
    }
}
