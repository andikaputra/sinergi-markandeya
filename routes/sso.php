<?php

use App\Http\Controllers\Sso\SsoServerController;
use Illuminate\Support\Facades\Route;

// ─── SSO Web Routes (browser-facing) ─────────────────────────────────────────
Route::prefix('sso')->name('sso.')->group(function () {
    // Step 1: Client redirects user here
    Route::get('/authorize', [SsoServerController::class, 'authorize'])->name('authorize');

    // Step 2: Process login form
    Route::post('/authenticate', [SsoServerController::class, 'authenticate'])
        ->middleware('throttle:10,1')
        ->name('authenticate');
});

// ─── SSO API Routes (server-to-server) ───────────────────────────────────────
Route::prefix('api/sso')->name('api.sso.')->group(function () {
    // Step 3: Exchange auth code for access token
    Route::post('/token', [SsoServerController::class, 'token'])->name('token');

    // Step 4: Get current user info
    Route::get('/me', [SsoServerController::class, 'me'])->name('me');

    // Logout / revoke token
    Route::post('/logout', [SsoServerController::class, 'logout'])->name('logout');
});
