@extends('layouts.main')

@section('title', 'Link Reset Password')

<style>
    #sidebar, header, footer { display: none !important; }
    main { margin-left: 0 !important; width: 100% !important; background: white !important; }
</style>

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center p-4 bg-white">
    <div class="max-w-md w-full">

        <div class="text-center mb-10">
            <img src="{{ asset('logo-universitas-markandeya.png') }}" alt="Logo" class="h-16 w-auto mx-auto mb-5">
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Link Reset Dibuat</h2>
            <p class="text-gray-400 mt-2 font-medium text-sm">Klik tombol di bawah untuk melanjutkan reset password.</p>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 sm:p-10 space-y-6">

            <div class="p-5 bg-amber-50 border border-amber-100 rounded-2xl">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-amber-500 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-bold text-amber-700">Link hanya berlaku 2 jam</p>
                        <p class="text-xs text-amber-600 mt-1">Segera gunakan link ini. Jangan bagikan kepada orang lain.</p>
                    </div>
                </div>
            </div>

            <div class="text-center space-y-2">
                <p class="text-xs text-gray-400 uppercase tracking-widest font-bold">Reset untuk</p>
                <p class="font-black text-gray-800">{{ $mahasiswa->nama }}</p>
                <p class="text-sm text-gray-400">{{ $mahasiswa->nim }} &bull; {{ $mahasiswa->email }}</p>
            </div>

            <a href="{{ route('reset-password', $token) }}"
                class="block w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl text-center shadow-lg shadow-blue-200 transition-all hover:-translate-y-0.5">
                <i class="fas fa-lock-open mr-2"></i> Buka Halaman Reset Password
            </a>

            <div class="pt-4 border-t border-gray-50 text-center">
                <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-blue-600 font-medium">
                    Kembali ke Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
