<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackLastLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Track mahasiswa login
        if (auth()->guard('mahasiswa')->check()) {
            $mahasiswa = auth()->guard('mahasiswa')->user();
            $mahasiswa->update(['last_login' => now()]);
        }

        // Track dosen login
        if (auth()->guard('web')->check()) {
            $user = auth()->guard('web')->user();
            if ($user && method_exists($user, 'update')) {
                $user->update(['last_login' => now()]);
            }
        }

        return $next($request);
    }
}
