@extends('layouts.admin')

@section('title', 'Tambah Lokasi KKN Baru')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('lokasikkn.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 hover:border-blue-600 transition-all shadow-sm">
            <i class="fas fa-chevron-left text-xs"></i>
        </a>
        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Tambah Tempat KKN</h2>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 bg-slate-50/50">
            <div class="flex items-center space-x-3">
                <div class="bg-blue-600 p-2 rounded-lg text-white shadow-lg shadow-blue-100">
                    <i class="fas fa-map-marked-alt text-sm"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800 leading-none">Detail Wilayah</h3>
                    <p class="text-xs text-gray-400 font-medium mt-1">Lengkapi informasi geografis lokasi pengabdian.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('lokasikkn.store') }}" method="POST" class="p-10 space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label for="desa" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Nama Desa / Tempat</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-blue-600 transition-colors">
                        <i class="fas fa-home text-sm"></i>
                    </div>
                    <input type="text" name="desa" id="desa" required
                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold"
                        placeholder="Contoh: Desa Taro">
                </div>
            </div>

            <div class="space-y-2">
                <label for="alamat" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Alamat Lengkap</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-blue-600 transition-colors">
                        <i class="fas fa-map-pin text-sm"></i>
                    </div>
                    <input type="text" name="alamat" id="alamat" required
                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-medium"
                        placeholder="Nama Jalan, Dusun, dll.">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="kecamatan" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Kecamatan</label>
                    <input type="text" name="kecamatan" id="kecamatan" required
                        class="block w-full px-6 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-medium"
                        placeholder="Kecamatan">
                </div>
                <div class="space-y-2">
                    <label for="kabupaten" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Kabupaten</label>
                    <input type="text" name="kabupaten" id="kabupaten" required
                        class="block w-full px-6 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-medium"
                        placeholder="Kabupaten">
                </div>
            </div>

            <div class="space-y-2">
                <label for="provinsi" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Provinsi</label>
                <input type="text" name="provinsi" id="provinsi" value="Bali" required
                    class="block w-full px-6 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-medium"
                    placeholder="Provinsi">
            </div>

            <div class="pt-4 flex space-x-4">
                <button type="submit" class="flex-1 py-4 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-xl shadow-blue-100 transition-all flex items-center justify-center space-x-2 group">
                    <i class="fas fa-save group-hover:scale-110 transition-transform"></i>
                    <span class="uppercase tracking-widest text-xs">Simpan Lokasi KKN</span>
                </button>
                <a href="{{ route('lokasikkn.index') }}" class="px-8 py-4 bg-white border border-gray-200 text-gray-400 font-bold rounded-2xl hover:bg-gray-50 transition-all flex items-center justify-center uppercase tracking-widest text-xs">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
