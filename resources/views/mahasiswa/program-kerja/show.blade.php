@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="mb-6 flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">{{ $programKerja->judul }}</h1>
            <p class="text-gray-300">Detail Program Kerja</p>
        </div>
        <div class="space-x-2">
            <a href="{{ route('program-kerja.edit', $programKerja) }}" class="inline-block px-4 py-2 text-white font-semibold rounded-lg transition" style="background-color: #1a5d4d;" onmouseover="this.style.backgroundColor='#0f2d26';" onmouseout="this.style.backgroundColor='#1a5d4d';">
                Edit
            </a>
            <a href="{{ route('program-kerja.index') }}" class="inline-block px-4 py-2 text-white font-semibold rounded-lg bg-gray-600 transition" onmouseover="this.style.backgroundColor='#4b5563';" onmouseout="this.style.backgroundColor='#4b5563';">
                Kembali
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ $message }}
        </div>
    @endif

    <!-- Program Details -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <div class="grid grid-cols-2 gap-8 mb-8">
            <div>
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Status</h3>
                <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold
                    @if($programKerja->status === 'rencana') bg-blue-100 text-blue-800
                    @elseif($programKerja->status === 'sedang_berjalan') bg-orange-100 text-orange-800
                    @elseif($programKerja->status === 'selesai') bg-green-100 text-green-800
                    @else bg-gray-100 text-gray-800
                    @endif
                ">
                    {{ ucfirst(str_replace('_', ' ', $programKerja->status)) }}
                </span>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Lokasi</h3>
                <p class="text-gray-800">{{ $programKerja->lokasi }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-8">
            <div>
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Tanggal Mulai</h3>
                <p class="text-gray-800">{{ $programKerja->tanggal_mulai->format('d F Y') }}</p>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Tanggal Selesai</h3>
                <p class="text-gray-800">{{ $programKerja->tanggal_selesai->format('d F Y') }}</p>
            </div>
        </div>

        <div>
            <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Deskripsi</h3>
            <p class="text-gray-800 leading-relaxed">{{ $programKerja->deskripsi }}</p>
        </div>
    </div>

    <!-- Luaran Section -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Luaran / Deliverables</h2>
            <button onclick="document.getElementById('luaranForm').classList.toggle('hidden')" class="px-4 py-2 text-white font-semibold rounded-lg transition" style="background-color: #d4a574; color: #0f2d26;" onmouseover="this.style.backgroundColor='#c9905c';" onmouseout="this.style.backgroundColor='#d4a574';">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Luaran
            </button>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-yellow-500">
                <p class="text-gray-600 text-sm font-semibold">Total Luaran</p>
                <p class="text-2xl font-bold text-gray-800">{{ $statistikLuaran['total'] }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-green-500">
                <p class="text-gray-600 text-sm font-semibold">Selesai</p>
                <p class="text-2xl font-bold text-gray-800">{{ $statistikLuaran['selesai'] }}</p>
            </div>
        </div>

        <!-- Add Luaran Form -->
        <div id="luaranForm" class="hidden mb-8 bg-gray-50 p-6 rounded-lg border border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Tambah Luaran Baru</h3>
            <form action="{{ route('luaran.store', $programKerja) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="judul" class="block text-sm font-semibold mb-2" style="color: #0f2d26;">Judul Luaran</label>
                    <input type="text" name="judul" id="judul" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" placeholder="Contoh: Laporan Analisis" required>
                </div>

                <div class="mb-4">
                    <label for="deskripsi" class="block text-sm font-semibold mb-2" style="color: #0f2d26;">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" placeholder="Jelaskan luaran ini" required></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="tipe" class="block text-sm font-semibold mb-2" style="color: #0f2d26;">Tipe Luaran</label>
                        <select name="tipe" id="tipe" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
                            <option value="">Pilih Tipe</option>
                            <option value="dokumen">Dokumen</option>
                            <option value="laporan">Laporan</option>
                            <option value="produk">Produk</option>
                            <option value="presentasi">Presentasi</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label for="tanggal_selesai" class="block text-sm font-semibold mb-2" style="color: #0f2d26;">Target Selesai</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="file_path" class="block text-sm font-semibold mb-2" style="color: #0f2d26;">Link Google Drive (Opsional)</label>
                    <input type="url" name="file_path" id="file_path" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" placeholder="https://drive.google.com/file/d/..." autocomplete="off">
                    <p class="text-gray-600 text-xs mt-1">Masukkan link file dari Google Drive Anda</p>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 text-white font-semibold rounded-lg transition" style="background-color: #d4a574; color: #0f2d26;" onmouseover="this.style.backgroundColor='#c9905c';" onmouseout="this.style.backgroundColor='#d4a574';">
                        Simpan Luaran
                    </button>
                    <button type="button" onclick="document.getElementById('luaranForm').classList.add('hidden')" class="px-4 py-2 text-gray-700 font-semibold rounded-lg bg-gray-300 transition" onmouseover="this.style.backgroundColor='#d1d5db';" onmouseout="this.style.backgroundColor='#d3d4d6';">
                        Batal
                    </button>
                </div>
            </form>
        </div>

        <!-- Luaran List -->
        @if ($luarans->count() > 0)
            <div class="space-y-4">
                @foreach ($luarans as $luaran)
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="text-lg font-bold text-gray-800">{{ $luaran->judul }}</h4>
                                <p class="text-gray-600 text-sm mt-1">{{ $luaran->deskripsi }}</p>
                            </div>
                            <form action="{{ route('luaran.destroy', $luaran) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 text-xs rounded transition bg-red-600 text-white" onmouseover="this.style.backgroundColor='#dc2626';" onmouseout="this.style.backgroundColor='#ef4444';">
                                    Hapus
                                </button>
                            </form>
                        </div>

                        <div class="grid grid-cols-3 gap-4 mb-4 text-sm">
                            <div>
                                <p class="text-gray-600 text-xs font-semibold">TIPE</p>
                                <p class="text-gray-800">{{ ucfirst($luaran->tipe) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-xs font-semibold">TARGET SELESAI</p>
                                <p class="text-gray-800">{{ $luaran->tanggal_selesai->format('d F Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-xs font-semibold">STATUS</p>
                                <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                                    @if($luaran->status === 'belum_dikerjakan') bg-red-100 text-red-800
                                    @elseif($luaran->status === 'sedang_dikerjakan') bg-orange-100 text-orange-800
                                    @else bg-green-100 text-green-800
                                    @endif
                                ">
                                    {{ str_replace('_', ' ', ucfirst($luaran->status)) }}
                                </span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-sm font-semibold text-gray-700">Progress</p>
                                <p class="text-sm font-bold text-gray-700">{{ $luaran->persentase_selesai }}%</p>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full transition duration-300" style="width: {{ $luaran->persentase_selesai }}%"></div>
                            </div>
                        </div>

                        @if ($luaran->file_path)
                            <div class="mb-4">
                                <a href="{{ $luaran->file_path }}" class="text-sm text-blue-600 hover:text-blue-800" target="_blank" rel="noopener noreferrer">
                                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    Buka File di Google Drive
                                </a>
                            </div>
                        @endif

                        <!-- Update Status -->
                        <form action="{{ route('luaran.update-status', $luaran) }}" method="POST" class="pt-4 border-t border-gray-200">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="status_{{ $luaran->id }}" class="block text-sm font-semibold mb-2" style="color: #0f2d26;">Update Status</label>
                                    <select name="status" id="status_{{ $luaran->id }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
                                        <option value="belum_dikerjakan" @selected($luaran->status === 'belum_dikerjakan')>Belum Dikerjakan</option>
                                        <option value="sedang_dikerjakan" @selected($luaran->status === 'sedang_dikerjakan')>Sedang Dikerjakan</option>
                                        <option value="selesai" @selected($luaran->status === 'selesai')>Selesai</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="persentase_{{ $luaran->id }}" class="block text-sm font-semibold mb-2" style="color: #0f2d26;">% Selesai</label>
                                    <input type="number" name="persentase_selesai" id="persentase_{{ $luaran->id }}" min="0" max="100" value="{{ $luaran->persentase_selesai }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
                                </div>
                            </div>

                            <button type="submit" class="mt-4 px-4 py-2 text-white font-semibold rounded-lg transition" style="background-color: #1a5d4d;" onmouseover="this.style.backgroundColor='#0f2d26';" onmouseout="this.style.backgroundColor='#1a5d4d';">
                                Update Status
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-600 mb-4">Belum ada luaran untuk program ini</p>
                <button onclick="document.getElementById('luaranForm').classList.toggle('hidden')" class="px-4 py-2 text-white font-semibold rounded-lg transition" style="background-color: #d4a574; color: #0f2d26;">
                    Tambah Luaran Pertama
                </button>
            </div>
        @endif
    </div>
</div>
@endsection
