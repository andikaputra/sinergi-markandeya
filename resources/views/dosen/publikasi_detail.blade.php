@extends('layouts.dosen')

@section('title', 'Detail Penilaian Publikasi')

@section('content')
<div class="space-y-6">
    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('dosen.publikasi.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-purple-600 hover:border-purple-600 transition-all">
            <i class="fas fa-chevron-left text-xs"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Detail Penilaian Publikasi & Diseminasi</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Student Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 text-center">
                <div class="w-24 h-24 bg-purple-100 text-purple-600 rounded-3xl flex items-center justify-center text-3xl font-black mx-auto mb-6 shadow-lg shadow-purple-50 border-4 border-white">
                    {{ substr($mahasiswa->nama, 0, 1) }}
                </div>
                <h3 class="text-xl font-bold text-gray-800">{{ $mahasiswa->nama }}</h3>
                <p class="text-sm font-mono text-gray-400 mt-1 uppercase tracking-widest">{{ $mahasiswa->nim }}</p>

                <div class="mt-8 pt-8 border-t border-gray-50 space-y-4 text-left">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Program Studi</span>
                        <span class="text-sm font-semibold text-gray-700">{{ $mahasiswa->prodi_full }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Jenis Kegiatan</span>
                        <span class="text-sm font-semibold text-gray-700">{{ $mahasiswa->kegiatan }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Penempatan</span>
                        <span class="text-sm font-semibold text-gray-700">
                            @if($mahasiswa->kegiatan == 'KKN')
                                Desa {{ $mahasiswa->penempatankkn?->lokasikkn?->desa ?? '-' }}
                            @elseif($mahasiswa->kegiatan == 'PPL')
                                {{ $mahasiswa->penempatanppl?->lokasippl?->Sekolah ?? '-' }}
                            @elseif($mahasiswa->kegiatan == 'PKL')
                                {{ $mahasiswa->penempatanpkl?->lokasipkl?->nama_instansi ?? '-' }}
                            @elseif($mahasiswa->kegiatan == 'Magang')
                                {{ $mahasiswa->penempatanmagang?->lokasimagang?->nama_instansi ?? '-' }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Input Nilai Kriteria Publikasi -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h4 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-star text-amber-400 mr-2"></i>
                    Penilaian Publikasi & Diseminasi
                </h4>

                @if(session('success'))
                <div class="mb-4 p-3 bg-purple-50 border border-purple-200 rounded-xl text-purple-700 text-sm font-bold">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
                @endif

                @if($isPenilai->nilai !== null)
                <div class="mb-6 p-4 bg-purple-50 border border-purple-100 rounded-2xl text-center">
                    <span class="text-[10px] font-bold text-purple-500 uppercase tracking-widest">Nilai Akhir (Rata-rata)</span>
                    <p class="text-3xl font-black text-purple-700 mt-1">{{ $isPenilai->nilai }}</p>
                </div>
                @endif

                <form action="{{ route('dosen.publikasi.nilai', $mahasiswa->nim) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <label class="block">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">1. Ketercapaian Publikasi</span>
                            <p class="text-[10px] text-gray-400 mb-1">Submit, Review, LoA, Publish</p>
                            <input type="number" name="nilai_ketercapaian" value="{{ $isPenilai->nilai_ketercapaian }}" min="0" max="100" step="0.1"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all font-bold"
                                placeholder="0 - 100">
                            @error('nilai_ketercapaian') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </label>

                        <label class="block">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">2. Sistematika Publikasi</span>
                            <p class="text-[10px] text-gray-400 mb-1">Pendahuluan, Metode, Pelaksanaan & Hasil, Simpulan & Rekomendasi, Daftar Pustaka</p>
                            <input type="number" name="nilai_sistematika" value="{{ $isPenilai->nilai_sistematika }}" min="0" max="100" step="0.1"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all font-bold"
                                placeholder="0 - 100">
                            @error('nilai_sistematika') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </label>

                        <label class="block">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">3. Kelayakan Publikasi</span>
                            <p class="text-[10px] text-gray-400 mb-1">Orisinalitas, Relevansi, Ilmiah, Konsistensi</p>
                            <input type="number" name="nilai_kelayakan" value="{{ $isPenilai->nilai_kelayakan }}" min="0" max="100" step="0.1"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all font-bold"
                                placeholder="0 - 100">
                            @error('nilai_kelayakan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </label>

                        <label class="block">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">4. Presentasi</span>
                            <p class="text-[10px] text-gray-400 mb-1">Pemaparan, Tanya Jawab, Jurnal Sasaran</p>
                            <input type="number" name="nilai_presentasi" value="{{ $isPenilai->nilai_presentasi }}" min="0" max="100" step="0.1"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all font-bold"
                                placeholder="0 - 100">
                            @error('nilai_presentasi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </label>

                        <label class="block">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">5. Mempertahankan Hasil Penelitian</span>
                            <input type="number" name="nilai_mempertahankan" value="{{ $isPenilai->nilai_mempertahankan }}" min="0" max="100" step="0.1"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all font-bold"
                                placeholder="0 - 100">
                            @error('nilai_mempertahankan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </label>

                        <button type="submit" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg shadow-purple-100 transition-all mt-2">
                            <i class="fas fa-save mr-2"></i> Simpan Nilai
                        </button>
                    </div>
                </form>
            </div>

            <!-- Publikasi Luaran -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h4 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-newspaper text-purple-500 mr-2"></i>
                    Publikasi Luaran
                </h4>
                @forelse($mahasiswa->publikasis as $pub)
                    <a href="{{ $pub->link }}" target="_blank" rel="noopener noreferrer"
                       class="flex items-center justify-between p-4 bg-purple-50 rounded-xl border border-purple-100 hover:bg-purple-600 hover:text-white hover:border-purple-600 transition-all duration-300 group mb-3">
                        <div class="flex items-center space-x-3 min-w-0">
                            <i class="fas fa-external-link-alt text-purple-500 group-hover:text-white"></i>
                            <span class="text-sm font-bold truncate text-purple-700 group-hover:text-white">{{ $pub->judul ?? 'Lihat Artikel' }}</span>
                        </div>
                        <i class="fas fa-arrow-right text-xs text-purple-400 group-hover:text-white"></i>
                    </a>
                @empty
                    <div class="text-center py-6 text-gray-400">
                        <i class="fas fa-file-alt text-3xl opacity-20 mb-2"></i>
                        <p class="text-sm italic">Belum ada publikasi</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Journal Timeline -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden min-h-[600px]">
                <div class="p-8 border-b border-gray-50">
                    <h4 class="text-lg font-bold text-gray-800">Aktivitas Jurnal Harian</h4>
                </div>

                <div class="p-8">
                    @if($jurnals->isEmpty())
                        <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                            <i class="fas fa-calendar-times text-5xl opacity-20 mb-4"></i>
                            <p class="italic">Mahasiswa belum mengisi jurnal harian.</p>
                        </div>
                    @else
                        <div class="space-y-8 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-purple-500 before:via-purple-200 before:to-transparent">
                            @foreach ($jurnals as $jurnal)
                            <div class="relative flex items-start group">
                                <div class="absolute left-0 w-10 h-10 bg-purple-50 border-4 border-white rounded-full flex items-center justify-center text-purple-600 z-10 transition-colors group-hover:bg-purple-600 group-hover:text-white">
                                    <i class="far fa-clock text-xs"></i>
                                </div>
                                <div class="flex-1 ml-16 bg-gray-50/50 p-6 rounded-2xl border border-gray-100 group-hover:bg-white group-hover:shadow-md transition-all duration-300">
                                    <span class="text-xs font-black text-purple-600 uppercase tracking-widest mb-2 block">
                                        {{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d F Y') }}
                                    </span>
                                    <p class="text-gray-700 leading-relaxed">{{ $jurnal->kegiatan }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
