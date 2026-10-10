<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackLastLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Aktivitas terakhir digunakan oleh dashboard monitoring mahasiswa dan dosen.
        foreach (['mahasiswa', 'dosen'] as $guard) {
            if ($user = auth()->guard($guard)->user()) {
                $user->forceFill(['last_login' => now()])->save();
            }
        }

        return $next($request);
    }
}
