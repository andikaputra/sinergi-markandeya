@extends('layouts.main')

@section('title', 'Lupa Password')

<style>
    #sidebar, header, footer { display: none !important; }
    main { margin-left: 0 !important; width: 100% !important; background: white !important; }
</style>

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center p-4 bg-white">
    <div class="max-w-md w-full">

        <div class="text-center mb-10">
            <img src="{{ asset('logo-universitas-markandeya.png') }}" alt="Logo" class="h-16 w-auto mx-auto mb-5">
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Lupa Password</h2>
            <p class="text-gray-400 mt-2 font-medium text-sm">Masukkan NIM dan email terdaftar untuk melanjutkan.</p>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 sm:p-10">

            @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl text-red-700 text-sm font-bold flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 text-sm font-medium">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('lupa-password.submit') }}" method="POST" class="space-y-5">
                @csrf
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">NIM</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-id-card text-sm"></i>
                        </div>
                        <input type="text" name="nim" value="{{ old('nim') }}" required
                            placeholder="Nomor Induk Mahasiswa"
                            class="block w-full pl-11 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email Terdaftar</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-envelope text-sm"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            placeholder="email@contoh.com"
                            class="block w-full pl-11 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold">
                    </div>
                </div>
                <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-200 transition-all duration-300 hover:-translate-y-0.5 active:scale-[0.98]">
                    <i class="fas fa-key mr-2"></i> Lanjutkan Reset Password
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-50 text-center">
                <a href="{{ route('login') }}" class="text-sm text-blue-600 font-bold hover:underline">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
