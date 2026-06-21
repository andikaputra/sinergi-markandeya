<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v1 — Mobile & Integrasi Langsung
|--------------------------------------------------------------------------
| Base: /api/v1
|
| Berbeda dari SSO web flow — ini untuk mobile app, third-party, atau
| app yang tidak bisa melakukan redirect browser.
*/

// Login (tidak butuh autentikasi)
Route::post('/login',            [AuthController::class, 'login']);
Route::post('/login/dosen',      [AuthController::class, 'loginDosenEndpoint']);
Route::post('/login/mahasiswa',  [AuthController::class, 'loginMahasiswaEndpoint']);

// Endpoint terproteksi (butuh Bearer token)
Route::middleware('auth.sso.api')->group(function () {
    Route::get('/me',      [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
