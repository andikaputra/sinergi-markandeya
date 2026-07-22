@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Buat Program Kerja Individu</h1>
        <p class="text-gray-300">Tambahkan program kerja baru untuk kegiatan {{ ucfirst($mahasiswa->kegiatan) }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-8">
        <form action="{{ route('program-kerja.store-individu') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Judul Program</label>
                <input type="text" name="judul" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600 @error('judul') border-red-500 @enderror"
                    value="{{ old('judul') }}" required>
                @error('judul')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                <textarea name="deskripsi" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600 @error('deskripsi') border-red-500 @enderror"
                    required>{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600 @error('tanggal_mulai') border-red-500 @enderror"
                        value="{{ old('tanggal_mulai') }}" required>
                    @error('tanggal_mulai')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600 @error('tanggal_selesai') border-red-500 @enderror"
                        value="{{ old('tanggal_selesai') }}" required>
                    @error('tanggal_selesai')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-gray-700 font-semibold mb-2">Lokasi</label>
                <input type="text" name="lokasi" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600 @error('lokasi') border-red-500 @enderror"
                    value="{{ old('lokasi') }}" required>
                @error('lokasi')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 px-6 py-3 bg-yellow-600 text-white font-semibold rounded-lg hover:bg-yellow-700 transition">
                    Buat Program
                </button>
                <a href="{{ route('program-kerja.index') }}" class="flex-1 px-6 py-3 bg-gray-300 text-gray-800 font-semibold rounded-lg hover:bg-gray-400 transition text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
