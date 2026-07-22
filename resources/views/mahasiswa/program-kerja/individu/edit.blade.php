@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Edit Program Kerja Individu</h1>
        <p class="text-gray-300">{{ $individuProgramKerja->judul }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-8">
        <form action="{{ route('program-kerja.update-individu', $individuProgramKerja) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Judul Program</label>
                <input type="text" name="judul" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600 @error('judul') border-red-500 @enderror"
                    value="{{ old('judul', $individuProgramKerja->judul) }}" required>
                @error('judul')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                <textarea name="deskripsi" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600 @error('deskripsi') border-red-500 @enderror"
                    required>{{ old('deskripsi', $individuProgramKerja->deskripsi) }}</textarea>
                @error('deskripsi')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600 @error('tanggal_mulai') border-red-500 @enderror"
                        value="{{ old('tanggal_mulai', $individuProgramKerja->tanggal_mulai->format('Y-m-d')) }}" required>
                    @error('tanggal_mulai')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600 @error('tanggal_selesai') border-red-500 @enderror"
                        value="{{ old('tanggal_selesai', $individuProgramKerja->tanggal_selesai->format('Y-m-d')) }}" required>
                    @error('tanggal_selesai')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Lokasi</label>
                <input type="text" name="lokasi" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600 @error('lokasi') border-red-500 @enderror"
                    value="{{ old('lokasi', $individuProgramKerja->lokasi) }}" required>
                @error('lokasi')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-8">
                <label class="block text-gray-700 font-semibold mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600 @error('status') border-red-500 @enderror" required>
                    <option value="rencana" @selected(old('status', $individuProgramKerja->status) === 'rencana')>Rencana</option>
                    <option value="sedang_berjalan" @selected(old('status', $individuProgramKerja->status) === 'sedang_berjalan')>Sedang Berjalan</option>
                    <option value="selesai" @selected(old('status', $individuProgramKerja->status) === 'selesai')>Selesai</option>
                    <option value="tunda" @selected(old('status', $individuProgramKerja->status) === 'tunda')>Tunda</option>
                </select>
                @error('status')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 px-6 py-3 bg-yellow-600 text-white font-semibold rounded-lg hover:bg-yellow-700 transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('program-kerja.show-individu', $individuProgramKerja) }}" class="flex-1 px-6 py-3 bg-gray-300 text-gray-800 font-semibold rounded-lg hover:bg-gray-400 transition text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
