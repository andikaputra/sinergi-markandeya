@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Buat Program Kerja Baru</h1>
        <p class="text-gray-300">Isi form di bawah untuk membuat program kerja baru</p>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8">
        <form action="{{ route('program-kerja.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="judul" class="block text-sm font-semibold mb-2" style="color: #0f2d26; font-size: 15px;">Judul Program Kerja</label>
                <input type="text" name="judul" id="judul" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 @error('judul') border-red-500 @enderror" placeholder="Contoh: Pengembangan Website" value="{{ old('judul') }}" required>
                @error('judul')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="deskripsi" class="block text-sm font-semibold mb-2" style="color: #0f2d26; font-size: 15px;">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 @error('deskripsi') border-red-500 @enderror" placeholder="Jelaskan detail program kerja Anda" required>{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-semibold mb-2" style="color: #0f2d26; font-size: 15px;">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 @error('tanggal_mulai') border-red-500 @enderror" value="{{ old('tanggal_mulai') }}" required>
                    @error('tanggal_mulai')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="tanggal_selesai" class="block text-sm font-semibold mb-2" style="color: #0f2d26; font-size: 15px;">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 @error('tanggal_selesai') border-red-500 @enderror" value="{{ old('tanggal_selesai') }}" required>
                    @error('tanggal_selesai')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="lokasi" class="block text-sm font-semibold mb-2" style="color: #0f2d26; font-size: 15px;">Lokasi</label>
                <input type="text" name="lokasi" id="lokasi" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 @error('lokasi') border-red-500 @enderror" placeholder="Lokasi pelaksanaan program kerja" value="{{ old('lokasi') }}" required>
                @error('lokasi')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="px-6 py-3 text-white font-semibold rounded-lg transition duration-200" style="background-color: #d4a574; color: #0f2d26;" onmouseover="this.style.backgroundColor='#c9905c'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.1)';" onmouseout="this.style.backgroundColor='#d4a574'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    Simpan Program Kerja
                </button>
                <a href="{{ route('program-kerja.index') }}" class="px-6 py-3 text-white font-semibold rounded-lg transition duration-200 bg-gray-600" onmouseover="this.style.backgroundColor='#4b5563';" onmouseout="this.style.backgroundColor='#4b5563';">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
