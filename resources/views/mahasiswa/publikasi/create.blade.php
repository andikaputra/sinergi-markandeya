@extends('layouts.adminmhs')

@section('title', 'Unggah Publikasi Ilmiah')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('publikasi.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-400 hover:text-emerald-600 hover:border-emerald-600 transition-all shadow-sm">
            <i class="fas fa-chevron-left text-xs"></i>
        </a>
        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Bagikan Karya Anda</h2>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 bg-slate-50/50">
            <div class="flex items-center space-x-3">
                <div class="bg-emerald-600 p-2 rounded-lg text-white shadow-lg shadow-emerald-100">
                    <i class="fas fa-paper-plane text-sm"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800 leading-none">Detail Publikasi</h3>
                    <p class="text-xs text-gray-400 font-medium mt-1">Masukkan tautan artikel atau publikasi ilmiah hasil kegiatan.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('publikasi.store') }}" method="POST" class="p-10 space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label for="judul" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Judul Publikasi / Artikel</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-emerald-600 transition-colors">
                        <i class="fas fa-file-signature text-sm"></i>
                    </div>
                    <input type="text" name="judul" id="judul" required
                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-600 focus:bg-white transition-all font-bold"
                        placeholder="Contoh: Implementasi Teknologi Tepat Guna di Desa Taro">
                </div>
            </div>

            <div class="space-y-2">
                <label for="link" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Tautan (URL) Publikasi</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-blue-600 transition-colors">
                        <i class="fas fa-link text-sm"></i>
                    </div>
                    <input type="url" name="link" id="link" required
                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-medium"
                        placeholder="https://journal.markandeya.ac.id/artikel-anda">
                </div>
                <p class="text-[10px] text-gray-400 ml-1 italic">*Pastikan link diawali dengan http:// atau https://</p>
            </div>

            <div class="bg-emerald-50 p-6 rounded-3xl border border-emerald-100 mb-4">
                <div class="flex items-start space-x-3 text-emerald-700">
                    <i class="fas fa-info-circle mt-1 text-emerald-500"></i>
                    <p class="text-xs font-medium leading-relaxed">
                        <strong>Info:</strong> Publikasi ini akan dapat dilihat oleh Dosen Pembimbing dan Admin sebagai bukti luaran dari kegiatan lapangan Anda.
                    </p>
                </div>
            </div>

            <div class="pt-4 flex space-x-4">
                <button type="submit" class="flex-1 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl shadow-xl shadow-emerald-100 transition-all flex items-center justify-center space-x-2 group transform hover:-translate-y-1">
                    <i class="fas fa-cloud-upload-alt group-hover:scale-110 transition-transform"></i>
                    <span class="uppercase tracking-widest text-xs">Publikasikan Sekarang</span>
                </button>
                <a href="{{ route('publikasi.index') }}" class="px-8 py-4 bg-white border border-gray-200 text-gray-400 font-bold rounded-2xl hover:bg-gray-50 transition-all flex items-center justify-center uppercase tracking-widest text-xs">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
