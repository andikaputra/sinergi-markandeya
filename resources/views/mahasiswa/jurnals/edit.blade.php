@extends('layouts.adminmhs')

@section('title', 'Edit Jurnal Harian')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center space-x-4 mb-2">
        <a href="{{ route('jurnal.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 hover:border-blue-600 transition-all shadow-sm">
            <i class="fas fa-chevron-left text-xs"></i>
        </a>
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Edit Log Aktivitas</h2>
            <p class="text-xs text-gray-400 font-medium">Perbarui catatan aktivitas harian yang telah dibuat sebelumnya.</p>
        </div>
    </div>

    @if ($errors->any())
    <div class="p-4 bg-red-50 border border-red-200 rounded-2xl text-red-700 text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 bg-slate-50/50">
            <div class="flex items-center space-x-3">
                <div class="bg-amber-500 p-2.5 rounded-xl text-white shadow-lg shadow-amber-100">
                    <i class="fas fa-edit text-sm"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800 leading-none">Edit Jurnal Harian</h3>
                    <p class="text-xs text-gray-400 font-medium mt-1">Pastikan rincian kegiatan tercatat dengan jelas dan akurat.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('jurnal.update', $jurnal) }}" method="POST" class="p-8 sm:p-10 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-2">
                <label for="tanggal" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Tanggal Kegiatan</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-blue-600 transition-colors">
                        <i class="fas fa-calendar-alt text-sm"></i>
                    </div>
                    <input type="date" name="tanggal" id="tanggal" required value="{{ old('tanggal', \Carbon\Carbon::parse($jurnal->tanggal)->format('Y-m-d')) }}"
                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-bold @error('tanggal') border-red-500 @enderror">
                </div>
                @error('tanggal')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="kegiatan" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Deskripsi Kegiatan / Pekerjaan</label>
                <div class="relative group">
                    <div class="absolute top-4 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-blue-600 transition-colors">
                        <i class="fas fa-tasks text-sm"></i>
                    </div>
                    <textarea name="kegiatan" id="kegiatan" rows="6" required
                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-medium @error('kegiatan') border-red-500 @enderror"
                        placeholder="Jelaskan apa yang Anda kerjakan hari ini secara rinci...">{{ old('kegiatan', $jurnal->kegiatan) }}</textarea>
                </div>
                @error('kegiatan')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-blue-50 p-6 rounded-3xl border border-blue-100">
                <div class="flex items-start space-x-3 text-blue-700">
                    <i class="fas fa-info-circle mt-1 text-blue-500"></i>
                    <p class="text-xs font-medium leading-relaxed">
                        Perubahan pada jurnal ini akan langsung terupdate dan dapat dilihat oleh Dosen Pembimbing Anda.
                    </p>
                </div>
            </div>

            <div class="pt-4 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                <button type="submit" class="flex-1 py-4 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-xl shadow-blue-100 transition-all flex items-center justify-center space-x-2 group transform hover:-translate-y-1">
                    <i class="fas fa-save group-hover:scale-110 transition-transform"></i>
                    <span class="uppercase tracking-widest text-xs">Simpan Perubahan</span>
                </button>
                <a href="{{ route('jurnal.index') }}" class="px-8 py-4 bg-white border border-gray-200 text-gray-500 font-bold rounded-2xl hover:bg-gray-50 hover:text-gray-700 transition-all flex items-center justify-center uppercase tracking-widest text-xs text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
