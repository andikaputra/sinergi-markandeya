@extends('layouts.adminmhs')

@section('title', 'Ajukan Lokasi Magang Mandiri')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('pengajuanmagang.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:border-indigo-600 transition-all shadow-sm">
            <i class="fas fa-chevron-left text-xs"></i>
        </a>
        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Formulir Pengajuan Lokasi</h2>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 bg-slate-50/50">
            <div class="flex items-center space-x-3">
                <div class="bg-indigo-500 p-2 rounded-lg text-white shadow-lg shadow-indigo-100">
                    <i class="fas fa-paper-plane text-sm"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800 leading-none">Detail Instansi Magang</h3>
                    <p class="text-xs text-gray-400 font-medium mt-1">Usulkan tempat pelaksanaan magang Anda.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('pengajuanmagang.store') }}" method="POST" class="p-10 space-y-6">
            @csrf

            <div class="space-y-2">
                <label for="nama_instansi" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Nama Perusahaan / Instansi</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-indigo-500 transition-colors">
                        <i class="fas fa-building text-sm"></i>
                    </div>
                    <input type="text" name="nama_instansi" id="nama_instansi" required value="{{ old('nama_instansi') }}"
                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all font-bold"
                        placeholder="Contoh: PT. Teknologi Indonesia">
                </div>
                @error('nama_instansi') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="alamat" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Alamat Lengkap Instansi</label>
                <div class="relative group">
                    <div class="absolute top-4 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-indigo-500 transition-colors">
                        <i class="fas fa-map-marker-alt text-sm"></i>
                    </div>
                    <textarea name="alamat" id="alamat" rows="3" required
                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all font-medium"
                        placeholder="Jl. Nama Jalan No. XX, Kota/Kabupaten...">{{ old('alamat') }}</textarea>
                </div>
                @error('alamat') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="kontak" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Kontak Instansi (Opsional)</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-indigo-500 transition-colors">
                        <i class="fas fa-phone text-sm"></i>
                    </div>
                    <input type="text" name="kontak" id="kontak" value="{{ old('kontak') }}"
                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all font-medium"
                        placeholder="Nomor Telepon atau WhatsApp">
                </div>
                @error('kontak') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <div class="bg-indigo-50 p-6 rounded-3xl border border-indigo-100">
                <div class="flex items-start space-x-3 text-indigo-700">
                    <i class="fas fa-info-circle mt-1 text-indigo-500"></i>
                    <p class="text-xs font-medium leading-relaxed">
                        <strong>Perhatian:</strong> Pengajuan lokasi ini akan diverifikasi oleh Admin terlebih dahulu. Pastikan data yang Anda masukkan benar agar proses persetujuan lebih cepat.
                    </p>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-5 bg-indigo-500 hover:bg-indigo-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-100 transition-all flex items-center justify-center space-x-3 group transform hover:-translate-y-1">
                    <i class="fas fa-paper-plane group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                    <span class="uppercase tracking-widest text-xs">Kirim Pengajuan Sekarang</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
