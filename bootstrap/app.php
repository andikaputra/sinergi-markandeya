<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // SSO + API v1 routes (stateless CSRF exempt)
            \Illuminate\Support\Facades\Route::middleware('web')
                ->group(base_path('routes/sso.php'));

            \Illuminate\Support\Facades\Route::prefix('api/v1')
                ->name('api.v1.')
                ->middleware(['api', 'throttle:60,1'])
                ->group(base_path('routes/api_v1.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\TrackLastLogin::class,
        ]);

        $middleware->alias([
            'kegiatan'     => \App\Http\Middleware\CheckKegiatan::class,
            'superadmin'   => \App\Http\Middleware\SuperAdmin::class,
            'auth.sso.api' => \App\Http\Middleware\SsoApiAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
