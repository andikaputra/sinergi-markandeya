<?php

namespace App\Http\Middleware;

use App\Models\SsoAccessToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SsoApiAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $header = $request->header('Authorization', '');
        if (!str_starts_with($header, 'Bearer ')) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $raw = substr($header, 7);
        $token = SsoAccessToken::where('token', $raw)->first();

        if (!$token || !$token->isValid()) {
            return response()->json(['message' => 'Token tidak valid atau kadaluarsa.'], 401);
        }

        $token->touchLastUsed();
        $request->merge([
            'sso_token'     => $token,
            'sso_user_type' => $token->user_type,
            'sso_user_data' => $token->user_data,
        ]);

        return $next($request);
    }
}
