<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckKegiatan
{
    public function handle(Request $request, Closure $next, string $kegiatan): Response
    {
        $user = $request->user();

        if (!$user || !$user->canManage($kegiatan)) {
            abort(403, 'Anda tidak memiliki akses untuk kegiatan ini.');
        }

        return $next($request);
    }
}
