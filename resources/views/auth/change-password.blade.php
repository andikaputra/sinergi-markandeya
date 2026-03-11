@extends('layouts.main')

@section('title', 'Keamanan Akun')

@section('sidebar_menu')
    @if(Auth::guard('mahasiswa')->check())
        @include('layouts.adminmhs_menu') {{-- Saya akan buat partial untuk menu --}}
    @elseif(Auth::guard('dosen')->check())
        @include('layouts.dosen_menu')
    @else
        @include('layouts.admin_menu')
    @endif
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center space-x-4 mb-8">
        <h2 class="text-2xl font-black text-gray-800 tracking-tight text-center w-full">Ubah Kata Sandi</h2>
    </div>

    <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-2xl hover:shadow-blue-900/5">
        <div class="p-8 sm:p-12">
            <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label for="current_password" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Password Saat Ini</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-blue-600 transition-colors">
                            <i class="fas fa-lock-open text-sm"></i>
                        </div>
                        <input type="password" name="current_password" id="current_password" required
                            class="block w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="new_password" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Password Baru</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-blue-600 transition-colors">
                            <i class="fas fa-key text-sm"></i>
                        </div>
                        <input type="password" name="new_password" id="new_password" required
                            class="block w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="new_password_confirmation" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Konfirmasi Password Baru</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-blue-600 transition-colors">
                            <i class="fas fa-check-double text-sm"></i>
                        </div>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                            class="block w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-5 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-black rounded-[1.5rem] shadow-xl shadow-blue-200 transition-all duration-300 transform hover:-translate-y-1 active:scale-[0.98] tracking-widest uppercase text-xs">
                        Perbarui Kata Sandi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
