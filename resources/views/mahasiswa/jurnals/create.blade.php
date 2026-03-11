@extends('layouts.adminmhs')

@section('title', 'Catat Jurnal Harian')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('jurnal.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 hover:border-blue-600 transition-all shadow-sm">
            <i class="fas fa-chevron-left text-xs"></i>
        </a>
        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Tambah Log Aktivitas</h2>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 bg-slate-50/50">
            <div class="flex items-center space-x-3">
                <div class="bg-blue-600 p-2 rounded-lg text-white shadow-lg shadow-blue-100">
                    <i class="fas fa-pen-nib text-sm"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800 leading-none">Jurnal Harian</h3>
                    <p class="text-xs text-gray-400 font-medium mt-1">Dokumentasikan kegiatan Anda secara detail.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('jurnal.store') }}" method="POST" class="p-10 space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label for="tanggal" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Tanggal Kegiatan</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-blue-600 transition-colors">
                        <i class="fas fa-calendar-alt text-sm"></i>
                    </div>
                    <input type="date" name="tanggal" id="tanggal" required value="{{ date('Y-m-d') }}"
                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold">
                </div>
            </div>

            <div class="space-y-2">
                <label for="kegiatan" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Deskripsi Kegiatan / Pekerjaan</label>
                <div class="relative group">
                    <div class="absolute top-4 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-blue-600 transition-colors">
                        <i class="fas fa-tasks text-sm"></i>
                    </div>
                    <textarea name="kegiatan" id="kegiatan" rows="6" required
                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-medium"
                        placeholder="Jelaskan apa yang Anda kerjakan hari ini secara rinci..."></textarea>
                </div>
            </div>

            <div class="bg-blue-50 p-6 rounded-3xl border border-blue-100">
                <div class="flex items-start space-x-3 text-blue-700">
                    <i class="fas fa-lightbulb mt-1 text-blue-500"></i>
                    <p class="text-xs font-medium leading-relaxed">
                        <strong>Tips:</strong> Jurnal yang detail membantu Dosen Pembimbing memberikan penilaian yang lebih akurat terhadap performa Anda di lapangan.
                    </p>
                </div>
            </div>

            <div class="pt-4 flex space-x-4">
                <button type="submit" class="flex-1 py-4 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-xl shadow-blue-100 transition-all flex items-center justify-center space-x-2 group transform hover:-translate-y-1">
                    <i class="fas fa-check-circle group-hover:scale-110 transition-transform"></i>
                    <span class="uppercase tracking-widest text-xs">Simpan Jurnal Hari Ini</span>
                </button>
                <a href="{{ route('jurnal.index') }}" class="px-8 py-4 bg-white border border-gray-200 text-gray-400 font-bold rounded-2xl hover:bg-gray-50 transition-all flex items-center justify-center uppercase tracking-widest text-xs">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
