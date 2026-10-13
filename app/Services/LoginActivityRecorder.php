<?php

namespace App\Services;

use App\Models\Dosen;
use App\Models\Mahasiswa;

class LoginActivityRecorder
{
    public function record($user, bool $force = false): void
    {
        if (!$user || ($user instanceof Mahasiswa && $user->status === 'nonaktif')) {
            return;
        }
        if ($force) {
            app(ActivityAudit::class)->authentication('login',$user);
        } elseif (($user instanceof Dosen || $user instanceof Mahasiswa) && !$user->last_login) {
            app(ActivityAudit::class)->authentication('authenticated_access',$user);
        }
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
