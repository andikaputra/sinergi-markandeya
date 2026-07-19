@extends('layouts.admin')

@section('title', 'Tambah Instansi Magang')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('lokasimagang.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:border-indigo-600 transition-all shadow-sm">
            <i class="fas fa-chevron-left text-xs"></i>
        </a>
        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Tambah Lokasi Magang</h2>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 bg-slate-50/50">
            <div class="flex items-center space-x-3">
                <div class="bg-indigo-500 p-2 rounded-lg text-white shadow-lg shadow-indigo-100">
                    <i class="fas fa-briefcase text-sm"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800 leading-none">Informasi Instansi</h3>
                    <p class="text-xs text-gray-400 font-medium mt-1">Daftarkan perusahaan atau lembaga tempat Magang.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('lokasimagang.store') }}" method="POST" class="p-10 space-y-6">
            @csrf

            <div class="space-y-2">
                <label for="nama_instansi" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Nama Perusahaan / Instansi</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-indigo-500 transition-colors">
                        <i class="fas fa-industry text-sm"></i>
                    </div>
                    <input type="text" name="nama_instansi" id="nama_instansi" required
                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all font-bold"
                        placeholder="Contoh: PT. Teknologi Indonesia">
                </div>
            </div>

            <div class="space-y-2">
                <label for="alamat" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Alamat Kantor</label>
                <div class="relative group">
                    <div class="absolute top-4 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-indigo-500 transition-colors">
                        <i class="fas fa-map-marker-alt text-sm"></i>
                    </div>
                    <textarea name="alamat" id="alamat" rows="3"
                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all font-medium"
                        placeholder="Jl. Raya Utama No. 123..."></textarea>
                </div>
            </div>

            <div class="space-y-2">
                <label for="kontak" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Telepon / WhatsApp</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-indigo-500 transition-colors">
                        <i class="fas fa-phone text-sm"></i>
                    </div>
                    <input type="text" name="kontak" id="kontak"
                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all font-medium"
                        placeholder="0812...">
                </div>
            </div>

            <div class="pt-4 flex space-x-4">
                <button type="submit" class="flex-1 py-4 bg-indigo-500 hover:bg-indigo-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-100 transition-all flex items-center justify-center space-x-2 group">
                    <i class="fas fa-save group-hover:scale-110 transition-transform"></i>
                    <span class="uppercase tracking-widest text-xs">Daftarkan Instansi</span>
                </button>
                <a href="{{ route('lokasimagang.index') }}" class="px-8 py-4 bg-white border border-gray-200 text-gray-400 font-bold rounded-2xl hover:bg-gray-50 transition-all flex items-center justify-center uppercase tracking-widest text-xs">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
