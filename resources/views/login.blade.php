@extends('layouts.main')

@section('title', 'Masuk ke Sistem')

{{-- Menghilangkan Sidebar untuk halaman login --}}
<style>
    #sidebar, header, footer { display: none !important; }
    main { margin-left: 0 !important; }
</style>

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center p-4">
    <div class="max-w-md w-full">
        <div class="text-center mb-10">
            <img src="{{ asset('logo-universitas-markandeya.png') }}" alt="Logo Universitas Markandeya" class="h-20 w-auto mx-auto mb-4">
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Sinergi Universitas Markandeya</h2>
            <p class="text-gray-500 mt-2 font-medium">Silakan masuk untuk melanjutkan</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden p-8 sm:p-12 transition-all duration-300 hover:shadow-xl hover:shadow-gray-200/50">
            @if (session('success'))
                <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-700 text-sm font-medium flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-8 p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 text-sm font-medium animate-shake">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label for="email" class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Email / NIDN</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition-colors">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <input type="text" name="email" id="email" required
                            class="block w-full pl-11 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all"
                            placeholder="Masukkan Email atau NIDN">
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center ml-1">
                        <label for="password" class="text-xs font-bold text-gray-400 uppercase tracking-widest">Kata Sandi</label>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition-colors">
                            <i class="fas fa-lock text-sm"></i>
                        </div>
                        <input type="password" name="password" id="password" required
                            class="block w-full pl-11 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all"
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-200 transition-all duration-300 transform hover:-translate-y-1 active:scale-[0.98]">
                    Masuk Sekarang
                </button>
            </form>

            <div class="mt-10 pt-8 border-t border-gray-50 text-center">
                <p class="text-sm text-gray-500">
                    Belum punya akun? 
                    <a href="{{ route('register.form') }}" class="text-blue-600 font-bold hover:underline">Daftar Mahasiswa</a>
                </p>
                <div class="mt-4">
                    <a href="{{ route('loginadmin') }}" class="text-xs font-bold text-gray-400 hover:text-gray-600 uppercase tracking-tighter">Login sebagai Admin</a>
                </div>
            </div>
        </div>
        
        <p class="mt-8 text-center text-xs text-gray-400 font-medium tracking-wide">
            &copy; {{ date('Y') }} Universitas Markandeya. All rights reserved.
        </p>
    </div>
</div>
@endsection
