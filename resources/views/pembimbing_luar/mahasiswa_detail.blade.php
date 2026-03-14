@extends('layouts.pembimbing_luar')

@section('title', 'Detail Mahasiswa Bimbingan')

@section('content')
<div class="space-y-6">
    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('pembimbing_luar.bimbingan') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-emerald-600 hover:border-emerald-600 transition-all">
            <i class="fas fa-chevron-left text-xs"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Detail & Jurnal Mahasiswa</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Student Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 text-center">
                <div class="w-24 h-24 bg-emerald-100 text-emerald-600 rounded-3xl flex items-center justify-center text-3xl font-black mx-auto mb-6 shadow-lg shadow-emerald-50 border-4 border-white">
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

            <!-- Input Nilai Kriteria -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h4 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-star text-amber-400 mr-2"></i>
                    Penilaian Mahasiswa
                    <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-500 text-[10px] font-black rounded-lg uppercase">{{ $mahasiswa->kegiatan }}</span>
                </h4>
                @if(session('success'))
                <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-bold">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
                @endif

                @if($isBimbingan->nilai !== null)
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-center">
                    <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest">
                        Nilai Akhir {{ in_array($mahasiswa->kegiatan, ['PKL', 'Magang']) ? '(Tertimbang)' : '(Rata-rata)' }}
                    </span>
                    <p class="text-3xl font-black text-emerald-700 mt-1">{{ $isBimbingan->nilai }}</p>
                </div>
                @endif

                <form action="{{ route('pembimbing_luar.mahasiswa.nilai', $mahasiswa->nim) }}" method="POST">
                    @csrf
                    <div class="space-y-4">

                    @if(in_array($mahasiswa->kegiatan, ['PKL', 'Magang']))
                        {{-- Kriteria PKL/Magang dengan bobot --}}
                        <label class="block">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">1. Kedisiplinan & Etika <span class="text-emerald-500">(15%)</span></span>
                            <p class="text-[10px] text-gray-400 mb-1">Kehadiran, kerapian, tata krama</p>
                            <input type="number" name="nilai_pkl_disiplin" value="{{ $isBimbingan->nilai_pkl_disiplin }}" min="0" max="100" step="0.1"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all font-bold"
                                placeholder="0 - 100">
                            @error('nilai_pkl_disiplin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </label>

                        <label class="block">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">2. Inisiatif & Kerja Sama <span class="text-emerald-500">(15%)</span></span>
                            <p class="text-[10px] text-gray-400 mb-1">Kemandirian dan kontribusi dalam tim</p>
                            <input type="number" name="nilai_pkl_inisiatif" value="{{ $isBimbingan->nilai_pkl_inisiatif }}" min="0" max="100" step="0.1"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all font-bold"
                                placeholder="0 - 100">
                            @error('nilai_pkl_inisiatif') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </label>

                        <label class="block">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">3. Kualitas Hasil Kerja <span class="text-emerald-500">(15%)</span></span>
                            <p class="text-[10px] text-gray-400 mb-1">Ketepatan waktu dan akurasi tugas</p>
                            <input type="number" name="nilai_pkl_kualitas" value="{{ $isBimbingan->nilai_pkl_kualitas }}" min="0" max="100" step="0.1"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all font-bold"
                                placeholder="0 - 100">
                            @error('nilai_pkl_kualitas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </label>

                        <label class="block">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">4. Penguasaan Skill Teknis <span class="text-emerald-500">(15%)</span></span>
                            <p class="text-[10px] text-gray-400 mb-1">Pemahaman tools/sistem di lapangan</p>
                            <input type="number" name="nilai_pkl_skill" value="{{ $isBimbingan->nilai_pkl_skill }}" min="0" max="100" step="0.1"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all font-bold"
                                placeholder="0 - 100">
                            @error('nilai_pkl_skill') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </label>

                        <div class="p-3 bg-amber-50 border border-amber-100 rounded-xl text-amber-700 text-xs">
                            <i class="fas fa-info-circle mr-1"></i> Total bobot: 60%. Nilai akhir dihitung berdasarkan bobot masing-masing kriteria.
                        </div>

                    @else
                        {{-- Kriteria KKN/PPL --}}
                        <label class="block">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">1. Tingkat Kehadiran</span>
                            <input type="number" name="nilai_kehadiran" value="{{ $isBimbingan->nilai_kehadiran }}" min="0" max="100" step="0.1"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all font-bold"
                                placeholder="0 - 100">
                            @error('nilai_kehadiran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </label>

                        <label class="block">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">2. Luaran & Kesesuaian Program Kerja</span>
                            <p class="text-[10px] text-gray-400 mb-1">Kesesuaian dengan kebutuhan desa/mitra sasaran</p>
                            <input type="number" name="nilai_luaran" value="{{ $isBimbingan->nilai_luaran }}" min="0" max="100" step="0.1"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all font-bold"
                                placeholder="0 - 100">
                            @error('nilai_luaran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </label>

                        <label class="block">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">3. Keterlibatan Mahasiswa</span>
                            <p class="text-[10px] text-gray-400 mb-1">Keterlibatan dalam melaksanakan program</p>
                            <input type="number" name="nilai_keterlibatan" value="{{ $isBimbingan->nilai_keterlibatan }}" min="0" max="100" step="0.1"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all font-bold"
                                placeholder="0 - 100">
                            @error('nilai_keterlibatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </label>

                        <label class="block">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">4. Inovatif Pengembangan Program</span>
                            <p class="text-[10px] text-gray-400 mb-1">Inovasi pengembangan program untuk desa</p>
                            <input type="number" name="nilai_inovatif" value="{{ $isBimbingan->nilai_inovatif }}" min="0" max="100" step="0.1"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all font-bold"
                                placeholder="0 - 100">
                            @error('nilai_inovatif') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </label>

                        <label class="block">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">5. Sosialisasi & Interaksi</span>
                            <p class="text-[10px] text-gray-400 mb-1">Interaksi dengan aparat dan masyarakat di lokasi KKN</p>
                            <input type="number" name="nilai_sosialisasi" value="{{ $isBimbingan->nilai_sosialisasi }}" min="0" max="100" step="0.1"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all font-bold"
                                placeholder="0 - 100">
                            @error('nilai_sosialisasi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </label>
                    @endif

                        <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-100 transition-all mt-2">
                            <i class="fas fa-save mr-2"></i> Simpan Nilai
                        </button>
                    </div>
                </form>
            </div>

            <!-- Publikasi Luaran -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h4 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-newspaper text-emerald-500 mr-2"></i>
                    Publikasi Luaran
                </h4>
                @forelse($mahasiswa->publikasis as $pub)
                    <a href="{{ $pub->link }}" target="_blank" rel="noopener noreferrer"
                       class="flex items-center justify-between p-4 bg-emerald-50 rounded-xl border border-emerald-100 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition-all duration-300 group mb-3">
                        <div class="flex items-center space-x-3 min-w-0">
                            <i class="fas fa-external-link-alt text-emerald-500 group-hover:text-white"></i>
                            <span class="text-sm font-bold truncate text-emerald-700 group-hover:text-white">{{ $pub->judul ?? 'Lihat Artikel' }}</span>
                        </div>
                        <i class="fas fa-arrow-right text-xs text-emerald-400 group-hover:text-white"></i>
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
                        <div class="space-y-8 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-emerald-500 before:via-emerald-200 before:to-transparent">
                            @foreach ($jurnals as $jurnal)
                            <div class="relative flex items-start group">
                                <div class="absolute left-0 w-10 h-10 bg-emerald-50 border-4 border-white rounded-full flex items-center justify-center text-emerald-600 z-10 transition-colors group-hover:bg-emerald-600 group-hover:text-white">
                                    <i class="far fa-clock text-xs"></i>
                                </div>
                                <div class="flex-1 ml-16 bg-gray-50/50 p-6 rounded-2xl border border-gray-100 group-hover:bg-white group-hover:shadow-md transition-all duration-300">
                                    <span class="text-xs font-black text-emerald-600 uppercase tracking-widest mb-2 block">
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
