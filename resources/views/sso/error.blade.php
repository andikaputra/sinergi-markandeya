@extends('layouts.main')
@section('title', 'SSO Error')
<style>
    #sidebar, header, footer { display: none !important; }
    main { margin-left: 0 !important; }
</style>
@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="text-center max-w-sm">
        <div class="text-6xl mb-4">🔒</div>
        <h1 class="text-2xl font-black text-gray-800 mb-2">Akses Ditolak</h1>
        <p class="text-gray-500 mb-6">{{ $message ?? 'Terjadi kesalahan pada proses autentikasi.' }}</p>
        <a href="{{ url()->previous() }}" class="text-blue-500 hover:underline text-sm">← Kembali</a>
    </div>
</div>
@endsection
