@extends('layouts.main')

@section('title', 'Admin Authorization')

{{-- Menghilangkan Sidebar & UI Dashboard untuk login --}}
<style>
    #sidebar, header, footer { display: none !important; }
    main { margin-left: 0 !important; width: 100% !important; background: white !important; }
</style>

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center p-6 relative overflow-hidden bg-white">
    <!-- Background Gradients (Consistent Theme) -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-50/50 rounded-full blur-[120px] -mr-48 -mt-48"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-indigo-50/50 rounded-full blur-[120px] -ml-48 -mb-48"></div>

    <div class="max-w-md w-full relative z-10">
        <!-- Logo & Header -->
        <div class="text-center mb-10">
            <img src="https://markandeyabali.ac.id/wp-content/uploads/2023/06/cropped-cropped-logo-1.png" alt="Logo Markandeya" class="h-24 w-auto mx-auto mb-6 transform hover:scale-105 transition-transform">
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Admin Portal</h2>
            <p class="text-gray-400 mt-2 font-bold uppercase tracking-[0.2em] text-[10px]">Secure Management Access</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden p-10 sm:p-12 transition-all hover:shadow-2xl hover:shadow-blue-900/5">
            
            @if (session('success'))
                <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-700 text-sm font-bold flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-8 p-4 bg-red-50 border border-red-100 rounded-2xl text-red-700 text-sm font-bold flex items-center animate-shake">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('loginadmin.submit') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label for="email" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Administrator Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-blue-600 transition-colors">
                            <i class="fas fa-envelope text-sm"></i>
                        </div>
                        <input type="email" name="email" id="email" required
                            class="block w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold"
                            placeholder="admin@markandeya.ac.id">
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="password" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Master Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-blue-600 transition-colors">
                            <i class="fas fa-lock text-sm"></i>
                        </div>
                        <input type="password" name="password" id="password" required
                            class="block w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-[1.5rem] text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-5 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-black rounded-[1.5rem] shadow-xl shadow-blue-200 transition-all duration-300 transform hover:-translate-y-1 active:scale-[0.98] tracking-widest uppercase text-xs">
                        Authorize Access
                    </button>
                </div>
            </form>

            <div class="mt-10 pt-8 border-t border-gray-50 text-center">
                <a href="{{ route('login') }}" class="text-[10px] font-black text-gray-400 hover:text-blue-600 transition-colors uppercase tracking-[0.2em]">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Student Portal
                </a>
            </div>
        </div>
        
        <p class="mt-12 text-center text-[10px] font-black text-gray-300 uppercase tracking-[0.4em]">
            System Kernel &bull; {{ date('Y') }}
        </p>
    </div>
</div>
@endsection
