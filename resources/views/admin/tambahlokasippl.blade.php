@extends('layouts.admin')

@section('title', 'Tambah Mitra Sekolah PPL')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('lokasippl.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-400 hover:text-emerald-600 hover:border-emerald-600 transition-all shadow-sm">
            <i class="fas fa-chevron-left text-xs"></i>
        </a>
        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Tambah Data Sekolah</h2>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 bg-slate-50/50">
            <div class="flex items-center space-x-3">
                <div class="bg-emerald-600 p-2 rounded-lg text-white shadow-lg shadow-emerald-100">
                    <i class="fas fa-school text-sm"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800 leading-none">Formulir Sekolah</h3>
                    <p class="text-xs text-gray-400 font-medium mt-1">Daftarkan sekolah mitra untuk program PPL.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('lokasippl.store') }}" method="POST" class="p-10 space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label for="Sekolah" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Nama Lengkap Sekolah</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-emerald-600 transition-colors">
                        <i class="fas fa-university text-sm"></i>
                    </div>
                    <input type="text" name="Sekolah" id="Sekolah" required
                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-600 focus:bg-white transition-all font-bold"
                        placeholder="Contoh: SMA Negeri 1 Bangli">
                </div>
            </div>

            <div class="bg-emerald-50 p-6 rounded-3xl border border-emerald-100 mb-4">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-info-circle text-emerald-500 mt-1"></i>
                    <p class="text-xs text-emerald-700 leading-relaxed font-medium">
                        <strong>Catatan:</strong> Pastikan nama sekolah sudah benar sesuai dengan dokumen kerjasama mitra untuk memudahkan proses penempatan mahasiswa.
                    </p>
                </div>
            </div>

            <div class="pt-4 flex space-x-4">
                <button type="submit" class="flex-1 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl shadow-xl shadow-emerald-100 transition-all flex items-center justify-center space-x-2 group">
                    <i class="fas fa-save group-hover:scale-110 transition-transform"></i>
                    <span class="uppercase tracking-widest text-xs">Simpan Data Sekolah</span>
                </button>
                <a href="{{ route('lokasippl.index') }}" class="px-8 py-4 bg-white border border-gray-200 text-gray-400 font-bold rounded-2xl hover:bg-gray-50 transition-all flex items-center justify-center uppercase tracking-widest text-xs">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
