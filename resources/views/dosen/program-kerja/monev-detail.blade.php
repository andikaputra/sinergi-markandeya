@extends('layouts.main')

@php
    $kegiatan = strtoupper($monev->kegiatan ?? 'KKN');
    $pageTitle = $program?->judul ?? ($type === 'individu' ? 'Monev Individu - ' . ($mahasiswa?->nama ?? $monev->nim) : 'Monev Kelompok - ' . ($lokasi?->desa ?? $lokasi?->nama_sekolah ?? $lokasi?->nama_instansi ?? 'Kelompok #' . $monev->lokasi_id));
@endphp

@section('title', 'Detail & Hasil Monev - ' . $pageTitle)

@section('user_type', 'Dosen Pembimbing')

@section('logout_route', route('logout'))

@section('content')
<div x-data="{ 
    selectedImage: null, 
    previewImages: [],
    handleFileSelect(event) {
        this.previewImages = [];
        const files = event.target.files;
        if (files) {
            for (let i = 0; i < files.length; i++) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previewImages.push({
                        name: files[i].name,
                        size: (files[i].size / (1024 * 1024)).toFixed(2) + ' MB',
                        url: e.target.result
                    });
                };
                reader.readAsDataURL(files[i]);
            }
        }
    }
}" class="space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">

    <!-- Header Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-8 sm:p-10 shadow-xl border border-slate-800">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-400/30 text-xs font-semibold uppercase tracking-wider">
                    <i class="fas fa-search-location"></i> Evaluasi Lapangan & Monev
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white line-clamp-2">{{ $pageTitle }}</h1>
                <p class="text-slate-300 text-sm">
                    Kegiatan: <strong class="text-white uppercase">{{ $kegiatan }}</strong> • Tipe: <strong class="text-white uppercase">{{ $type }}</strong>
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('dosen.program-kerja.monev-dashboard') }}" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white text-sm font-bold rounded-xl border border-white/20 transition-all flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Daftar Monev</span>
                </a>
            </div>
        </div>
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    @if ($message = Session::get('success'))
        <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl shadow-sm">
            <i class="fas fa-check-circle text-emerald-600 text-lg"></i>
            <span class="font-semibold text-sm">{{ $message }}</span>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-800 px-6 py-4 rounded-2xl shadow-sm">
            <i class="fas fa-exclamation-circle text-rose-600 text-lg"></i>
            <span class="font-semibold text-sm">{{ $message }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left Column: Program Info & Luaran (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            <!-- Program / Target Details Card -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                    <h2 class="text-lg font-black text-gray-900 flex items-center gap-2">
                        <i class="fas fa-info-circle text-indigo-600"></i>
                        <span>Informasi {{ $type === 'individu' ? 'Mahasiswa' : 'Kelompok / Lokasi' }}</span>
                    </h2>
                    @if ($program)
                        @if ($program->status === 'rencana')
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold rounded-full">Rencana</span>
                        @elseif ($program->status === 'sedang_berjalan')
                            <span class="px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold rounded-full">Sedang Berjalan</span>
                        @elseif ($program->status === 'selesai')
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-full">Selesai</span>
                        @else
                            <span class="px-3 py-1 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded-full">Tunda</span>
                        @endif
                    @endif
                </div>

                <div class="space-y-4 text-sm">
                    @if ($type === 'individu')
                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Mahasiswa Pelaksana</p>
                            <p class="text-base font-bold text-gray-900 mt-1">{{ $mahasiswa->nama ?? ($program?->mahasiswa?->nama ?? '-') }}</p>
                            <p class="text-xs text-gray-500 font-mono">NIM: {{ $mahasiswa->nim ?? ($program?->nim ?? $monev->nim) }} • {{ $mahasiswa->prodi ?? ($program?->mahasiswa?->prodi ?? '-') }}</p>
                        </div>
                    @else
                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kelompok / Lokasi</p>
                            <p class="text-base font-bold text-gray-900 mt-1">
                                {{ $lokasi?->desa ?? $lokasi?->nama_sekolah ?? $lokasi?->nama_instansi ?? ($program?->mahasiswaKetua?->nama ?? 'Kelompok #' . $monev->lokasi_id) }}
                            </p>
                            @if(isset($lokasi) && ($lokasi->kecamatan || $lokasi->kabupaten))
                                <p class="text-xs text-gray-500">Kecamatan: {{ $lokasi->kecamatan ?? '-' }} • Kabupaten: {{ $lokasi->kabupaten ?? '-' }}</p>
                            @endif
                        </div>
                    @endif

                    @if ($program)
                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <span class="text-xs text-gray-400 font-medium block">Tgl Mulai</span>
                                <span class="font-bold text-gray-800 text-xs">{{ $program->tanggal_mulai ? $program->tanggal_mulai->format('d M Y') : '-' }}</span>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <span class="text-xs text-gray-400 font-medium block">Tgl Selesai</span>
                                <span class="font-bold text-gray-800 text-xs">{{ $program->tanggal_selesai ? $program->tanggal_selesai->format('d M Y') : '-' }}</span>
                            </div>
                        </div>

                        @if($program->lokasi)
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <span class="text-xs text-gray-400 font-medium block">Lokasi Program</span>
                            <span class="font-bold text-gray-800 text-xs">{{ $program->lokasi }}</span>
                        </div>
                        @endif

                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Deskripsi Program</span>
                            <p class="text-gray-700 text-xs bg-gray-50 p-3 rounded-xl border border-gray-100 leading-relaxed whitespace-pre-wrap">{{ $program->deskripsi ?: 'Tidak ada deskripsi' }}</p>
                        </div>
                    @else
                        <div class="p-4 bg-amber-50/60 rounded-2xl border border-amber-200/70 text-xs text-amber-900 leading-relaxed">
                            <i class="fas fa-info-circle mr-1 text-amber-600"></i> Mahasiswa/kelompok ini belum membuat rincian judul program kerja di sistem. Anda tetap dapat melakukan observasi dan mencatat evaluasi monev di formulir sebelah kanan.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Group Members if Kelompok -->
            @if ($type === 'kelompok' && isset($anggota) && count($anggota) > 0)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <h3 class="text-base font-black text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-users text-indigo-600"></i>
                    <span>Anggota Kelompok ({{ count($anggota) }})</span>
                </h3>
                <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                    @foreach ($anggota as $member)
                        <div class="p-3 bg-gray-50 hover:bg-gray-100 border border-gray-100 rounded-xl flex items-center justify-between text-xs">
                            <div class="min-w-0">
                                <p class="font-bold text-gray-900 truncate">{{ $member->nama }}</p>
                                <p class="text-gray-500 font-mono">{{ $member->nim }}</p>
                            </div>
                            @if (isset($program) && $member->nim === $program->nim_ketua)
                                <span class="px-2 py-0.5 bg-amber-100 text-amber-800 font-bold rounded-md text-[10px]">Ketua</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Deliverables / Luaran Card -->
            @if ($program)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <h3 class="text-base font-black text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-box-open text-indigo-600"></i>
                    <span>Luaran / Deliverables ({{ $luarans->count() }})</span>
                </h3>
                @if ($luarans->count() > 0)
                    <div class="space-y-3">
                        @foreach ($luarans as $luaran)
                            <div class="p-4 border border-gray-100 bg-gray-50/50 rounded-2xl">
                                <div class="flex items-start justify-between gap-2 mb-1">
                                    <h4 class="font-bold text-gray-900 text-sm">{{ $luaran->judul }}</h4>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black
                                        @if($luaran->status === 'belum_dikerjakan') bg-gray-100 text-gray-600
                                        @elseif($luaran->status === 'sedang_dikerjakan') bg-amber-100 text-amber-800
                                        @else bg-emerald-100 text-emerald-800
                                        @endif
                                    ">
                                        {{ str_replace('_', ' ', strtoupper($luaran->status)) }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 line-clamp-2 mb-2">{{ $luaran->deskripsi }}</p>
                                <div class="flex items-center justify-between text-xs font-semibold">
                                    <span class="text-indigo-600">Progress: {{ $luaran->persentase_selesai }}%</span>
                                    @if ($luaran->file_path)
                                        <a href="{{ asset('storage/' . $luaran->file_path) }}" target="_blank" class="text-blue-600 hover:underline flex items-center gap-1">
                                            <i class="fas fa-paperclip"></i> Berkas Luaran
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-400 text-center py-4">Belum ada luaran yang ditambahkan.</p>
                @endif
            </div>
            @endif
        </div>

        <!-- Right Column: Monev Form & Photo Gallery (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
            <!-- Monev Review Form -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                    <div>
                        <h2 class="text-xl font-black text-gray-900">Form Catatan & Dokumentasi Monev</h2>
                        <p class="text-xs text-gray-500 mt-1">Masukkan hasil evaluasi lapangan dan unggah foto dokumentasi kegiatan monev</p>
                    </div>
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                </div>

                <form action="{{ route('dosen.program-kerja.monev-nilai-id', $monev->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Tanggal Pelaksanaan Monev -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Tanggal Pelaksanaan Monev <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_monev" value="{{ old('tanggal_monev', $monev->tanggal_monev ? \Carbon\Carbon::parse($monev->tanggal_monev)->format('Y-m-d') : date('Y-m-d')) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition @error('tanggal_monev') border-red-500 @enderror">
                        @error('tanggal_monev')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catatan Monev / Hasil Pengamatan -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Catatan & Hasil Evaluasi Monev <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs text-gray-400 mb-2">Tuliskan hasil pengamatan di lokasi, pencapaian target, evaluasi kendala, serta arahan/saran untuk mahasiswa.</p>
                        <textarea name="catatan" rows="6" placeholder="Contoh: Berdasarkan monev lapangan pada tanggal ini, pelaksanaan kegiatan berjalan dengan baik. Mahasiswa aktif berkoordinasi dengan warga..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition leading-relaxed @error('catatan') border-red-500 @enderror">{{ old('catatan', $monev->catatan) }}</textarea>
                        @error('catatan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nilai Monev (Opsional) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Nilai Monev (Opsional, Skala 0 - 100)
                        </label>
                        <div class="relative">
                            <input type="number" step="0.1" min="0" max="100" name="nilai" value="{{ old('nilai', $monev->nilai) }}" placeholder="Contoh: 88.5" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition @error('nilai') border-red-500 @enderror">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-xs font-bold text-gray-400">
                                / 100
                            </div>
                        </div>
                        @error('nilai')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Upload Foto Hasil Monev (Multiple) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Unggah Foto Hasil Monev / Dokumentasi Lapangan
                        </label>
                        <p class="text-xs text-gray-400 mb-3">Pilih 1 atau beberapa foto sekaligus (Format: JPG, PNG, WEBP. Maks 10MB per foto).</p>

                        <div class="border-2 border-dashed border-gray-200 hover:border-indigo-500 rounded-2xl p-6 text-center bg-gray-50/50 transition-colors cursor-pointer relative">
                            <input type="file" name="foto_monev[]" multiple accept="image/*" @change="handleFileSelect" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="space-y-2">
                                <div class="w-12 h-12 mx-auto rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                                    <i class="fas fa-camera"></i>
                                </div>
                                <p class="text-xs font-bold text-gray-700">Klik di sini untuk memilih foto dokumentasi monev</p>
                                <p class="text-[11px] text-gray-400">Bisa memilih lebih dari satu foto secara bersamaan</p>
                            </div>
                        </div>

                        <!-- Live Preview Before Upload -->
                        <div x-show="previewImages.length > 0" class="mt-4 space-y-2" style="display: none;">
                            <p class="text-xs font-bold text-indigo-700">Foto yang akan diunggah (<span x-text="previewImages.length"></span> foto):</p>
                            <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                                <template x-for="(img, idx) in previewImages" :key="idx">
                                    <div class="relative group rounded-xl overflow-hidden border border-indigo-200 bg-black aspect-square shadow-sm">
                                        <img :src="img.url" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent flex items-end p-2">
                                            <span class="text-[10px] text-white font-mono truncate" x-text="img.name"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        @error('foto_monev.*')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                        <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-600/30 transition duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i>
                            <span>Simpan Catatan & Foto Monev</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Saved Photos Gallery Card -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                    <div>
                        <h2 class="text-lg font-black text-gray-900 flex items-center gap-2">
                            <i class="fas fa-images text-indigo-600"></i>
                            <span>Dokumentasi Foto Hasil Monev Tersimpan</span>
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ !empty($monev->foto_monev) ? count($monev->foto_monev) : 0 }} foto tersimpan untuk penugasan ini
                        </p>
                    </div>
                </div>

                @if (!empty($monev->foto_monev) && count($monev->foto_monev) > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach ($monev->foto_monev as $index => $photoPath)
                            @php
                                $photoUrl = asset('storage/' . ltrim($photoPath, '/'));
                            @endphp
                            <div class="group relative rounded-2xl overflow-hidden border border-gray-200 bg-slate-950 aspect-square shadow-sm flex flex-col justify-between">
                                <!-- Image with click to view -->
                                <img src="{{ $photoUrl }}" alt="Dokumentasi Monev {{ $index + 1 }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 cursor-pointer" @click="selectedImage = '{{ $photoUrl }}'">

                                <!-- Hover Overlay Actions -->
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-3 flex flex-col justify-between pointer-events-none">
                                    <div class="flex justify-end pointer-events-auto">
                                        <form action="{{ route('dosen.program-kerja.monev-delete-foto-id', ['id' => $monev->id, 'index' => $index]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto dokumentasi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-full bg-rose-600/90 hover:bg-rose-700 text-white flex items-center justify-center text-xs shadow-md transition" title="Hapus foto ini">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="pointer-events-auto">
                                        <button type="button" @click="selectedImage = '{{ $photoUrl }}'" class="text-white text-xs font-bold hover:underline flex items-center gap-1">
                                            <i class="fas fa-search-plus"></i> Lihat Penuh
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                        <i class="far fa-images text-gray-300 text-4xl mb-3 block"></i>
                        <p class="text-xs font-bold text-gray-500">Belum ada foto dokumentasi hasil monev yang diunggah.</p>
                        <p class="text-[11px] text-gray-400 mt-1">Gunakan formulir di atas untuk mengunggah foto kunjungan/evaluasi.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Image Lightbox Modal -->
    <div x-show="selectedImage" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-md" style="display: none;" @keydown.escape.window="selectedImage = null">
        <div class="relative max-w-4xl w-full max-h-[90vh] flex flex-col items-center justify-center" @click.away="selectedImage = null">
            <button type="button" @click="selectedImage = null" class="absolute -top-12 right-0 text-white hover:text-gray-300 text-2xl font-bold p-2 transition">
                <i class="fas fa-times"></i> Tutup
            </button>
            <img :src="selectedImage" class="max-w-full max-h-[85vh] rounded-2xl object-contain shadow-2xl border border-white/20">
        </div>
    </div>

</div>
@endsection
