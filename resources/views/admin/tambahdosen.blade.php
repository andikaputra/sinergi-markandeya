@extends('layouts.admin')

@section('title', 'Registrasi Dosen Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('dosen.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 hover:border-blue-600 transition-all shadow-sm">
            <i class="fas fa-chevron-left text-xs"></i>
        </a>
        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Tambah Data Dosen</h2>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 bg-slate-50/50">
            <div class="flex items-center space-x-3">
                <div class="bg-blue-600 p-2 rounded-lg text-white">
                    <i class="fas fa-user-plus text-sm"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800 leading-none">Formulir Dosen</h3>
                    <p class="text-xs text-gray-400 font-medium mt-1">Lengkapi data NIDN dan Nama Lengkap.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('dosen.store') }}" method="POST" class="p-10 space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label for="nidn" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Nomor Induk Dosen Nasional (NIDN)</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-blue-600 transition-colors">
                        <i class="fas fa-id-card text-sm"></i>
                    </div>
                    <input type="text" name="nidn" id="nidn" required
                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold"
                        placeholder="Contoh: 0812345678">
                </div>
            </div>

            <div class="space-y-2">
                <label for="nama" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Nama Lengkap & Gelar</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-blue-600 transition-colors">
                        <i class="fas fa-user-tie text-sm"></i>
                    </div>
                    <input type="text" name="nama" id="nama" required
                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold"
                        placeholder="Contoh: Dr. Nama Dosen, M.Kom.">
                </div>
            </div>

            <div class="bg-blue-50 p-6 rounded-3xl border border-blue-100 mb-4">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-shield-alt text-blue-500 mt-1"></i>
                    <p class="text-xs text-blue-700 leading-relaxed font-medium">
                        <strong>Keamanan:</strong> Secara default, password untuk dosen baru adalah sama dengan NIDN yang didaftarkan. Dosen dapat mengubah password setelah login pertama kali.
                    </p>
                </div>
            </div>

            <div class="pt-4 flex space-x-4">
                <button type="submit" class="flex-1 py-4 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-xl shadow-blue-100 transition-all flex items-center justify-center space-x-2 group">
                    <i class="fas fa-save group-hover:scale-110 transition-transform"></i>
                    <span class="uppercase tracking-widest text-xs">Simpan Data Dosen</span>
                </button>
                <a href="{{ route('dosen.index') }}" class="px-8 py-4 bg-white border border-gray-200 text-gray-400 font-bold rounded-2xl hover:bg-gray-50 transition-all flex items-center justify-center uppercase tracking-widest text-xs">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
