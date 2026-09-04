@extends('layouts.adminmhs')

@section('title', 'Edit Program Kerja Individu')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header Card -->
    <div class="flex items-center justify-between bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <div>
            <a href="{{ route('program-kerja.show-individu', $individuProgramKerja) }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 inline-flex items-center gap-1 mb-1">
                <i class="fas fa-arrow-left"></i> Kembali ke Detail Program
            </a>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Edit Program Kerja Individu</h2>
            <p class="text-sm text-gray-500">{{ $individuProgramKerja->judul }}</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
            <i class="fas fa-edit"></i>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('program-kerja.update-individu', $individuProgramKerja) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Judul Program Kerja <span class="text-rose-500">*</span></label>
                <input type="text" name="judul" class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all @error('judul') border-rose-500 @enderror"
                    value="{{ old('judul', $individuProgramKerja->judul) }}" required>
                @error('judul')
                    <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi & Rincian Rencana <span class="text-rose-500">*</span></label>
                <textarea name="deskripsi" rows="5" class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all @error('deskripsi') border-rose-500 @enderror"
                    required>{{ old('deskripsi', $individuProgramKerja->deskripsi) }}</textarea>
                @error('deskripsi')
                    <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Mulai <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_mulai" class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all @error('tanggal_mulai') border-rose-500 @enderror"
                        value="{{ old('tanggal_mulai', $individuProgramKerja->tanggal_mulai ? $individuProgramKerja->tanggal_mulai->format('Y-m-d') : '') }}" required>
                    @error('tanggal_mulai')
                        <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Selesai <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_selesai" class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all @error('tanggal_selesai') border-rose-500 @enderror"
                        value="{{ old('tanggal_selesai', $individuProgramKerja->tanggal_selesai ? $individuProgramKerja->tanggal_selesai->format('Y-m-d') : '') }}" required>
                    @error('tanggal_selesai')
                        <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Lokasi Pelaksanaan <span class="text-rose-500">*</span></label>
                    <input type="text" name="lokasi" class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all @error('lokasi') border-rose-500 @enderror"
                        value="{{ old('lokasi', $individuProgramKerja->lokasi) }}" required>
                    @error('lokasi')
                        <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Status Program Kerja <span class="text-rose-500">*</span></label>
                    <select name="status" class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all @error('status') border-rose-500 @enderror" required>
                        <option value="rencana" @selected(old('status', $individuProgramKerja->status) === 'rencana')>Rencana</option>
                        <option value="sedang_berjalan" @selected(old('status', $individuProgramKerja->status) === 'sedang_berjalan')>Sedang Berjalan</option>
                        <option value="selesai" @selected(old('status', $individuProgramKerja->status) === 'selesai')>Selesai</option>
                        <option value="tunda" @selected(old('status', $individuProgramKerja->status) === 'tunda')>Tunda</option>
                    </select>
                    @error('status')
                        <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('program-kerja.show-individu', $individuProgramKerja) }}" class="px-6 py-3 rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-sm transition-all">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm transition-all shadow-lg shadow-blue-200">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
