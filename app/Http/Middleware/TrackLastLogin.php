<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackLastLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        // Also record authenticated access from an existing session, including
        // sessions created before login tracking was corrected.
        if ($response->getStatusCode() < 400) {
            foreach (['mahasiswa', 'dosen'] as $guard) {
                app(\App\Services\LoginActivityRecorder::class)->record(auth()->guard($guard)->user());
            }
        }

        return $response;
    }
}
