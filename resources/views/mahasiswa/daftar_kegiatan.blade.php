@extends('layouts.adminmhs')
@section('title', 'Daftar Kegiatan')
@section('content')
<div class="space-y-8">

    <div>
        <h2 class="text-2xl font-black text-gray-800">Daftar Kegiatan</h2>
        <p class="text-sm text-gray-400 mt-1">Lengkapi dokumen dan klik tombol daftar pada kegiatan yang Anda pilih.</p>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-700 text-sm font-bold flex items-center gap-2">
        <i class="fas fa-check-circle"></i>{{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-red-700 text-sm font-bold flex items-center gap-2">
        <i class="fas fa-exclamation-circle"></i>{{ session('error') }}
    </div>
    @endif
    @if($errors->any())
    <div class="p-4 bg-red-50 border border-red-100 rounded-2xl">
        <div class="flex items-center gap-2 mb-2 text-red-700 text-sm font-bold"><i class="fas fa-exclamation-circle"></i>Periksa isian berikut:</div>
        <ul class="list-disc ml-5 space-y-1 text-xs text-red-600 font-medium">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    @if($openTahunAkademiks->isEmpty())
    <div class="bg-white rounded-[2.5rem] border border-gray-100 p-16 text-center">
        <div class="w-20 h-20 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-clock text-3xl text-gray-400"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-600 mb-2">Belum ada pendaftaran dibuka</h3>
        <p class="text-gray-400 text-sm">Pantau pengumuman dari admin untuk informasi jadwal pendaftaran.</p>
    </div>

    @else
    @foreach($openTahunAkademiks as $ta)
    @php
        $taStr = $ta->tahun . ' ' . $ta->semester;
        $sudahDaftar = $mahasiswa->mahasiswaKegiatan->where('tahun_akademik', $taStr)->pluck('kegiatan')->toArray();
        $sudahAdaGrupKPN = !empty(array_intersect(['KKN','PPL','PKL'], $sudahDaftar));
    @endphp

    <div class="space-y-5">
        <div class="flex flex-wrap items-center justify-between gap-3 px-1">
            <div>
                <h3 class="text-lg font-black text-gray-800">{{ $ta->tahun }} — {{ $ta->semester }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">
                    <i class="fas fa-calendar-alt mr-1 text-blue-400"></i>
                    {{ $ta->tanggal_mulai_daftar->format('d M Y') }} – {{ $ta->tanggal_selesai_daftar->format('d M Y') }}
                </p>
            </div>
            <span class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-xl text-[10px] font-black border border-emerald-100 uppercase tracking-widest">
                <span class="inline-block w-2 h-2 bg-emerald-400 rounded-full animate-pulse mr-1"></span>Sedang Buka
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

{{-- ═══════════════════ CARD KKN ═══════════════════ --}}
@php $sudahKKN = in_array('KKN', $sudahDaftar); $kknLock = !$sudahKKN && $sudahAdaGrupKPN; @endphp
<div class="bg-white rounded-[2rem] border {{ $sudahKKN ? 'border-emerald-200' : ($kknLock ? 'border-gray-100' : 'border-blue-100') }} overflow-hidden shadow-sm {{ $kknLock ? 'opacity-50' : '' }}">

    {{-- Header --}}
    <div class="px-6 py-4 {{ $sudahKKN ? 'bg-emerald-50 border-emerald-100' : ($kknLock ? 'bg-gray-50 border-gray-100' : 'bg-blue-50 border-blue-100') }} border-b flex items-center gap-3">
        <div class="w-11 h-11 {{ $sudahKKN ? 'bg-emerald-100 text-emerald-600' : ($kknLock ? 'bg-gray-200 text-gray-400' : 'bg-blue-100 text-blue-600') }} rounded-2xl flex items-center justify-center text-lg shrink-0">
            <i class="fas fa-hands-helping"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="font-black text-gray-800 text-sm">KKN</p>
            <p class="text-[11px] text-gray-500">Kuliah Kerja Nyata</p>
        </div>
        @if($sudahKKN) <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-black uppercase shrink-0"><i class="fas fa-check mr-1"></i>Terdaftar</span>
        @elseif($kknLock) <span class="px-2.5 py-1 bg-gray-100 text-gray-400 rounded-full text-[10px] font-bold shrink-0"><i class="fas fa-lock mr-1"></i>Tidak Tersedia</span>
        @endif
    </div>

    @if($sudahKKN)
    @php
        $mk = $mahasiswa->mahasiswaKegiatan->where('tahun_akademik',$taStr)->where('kegiatan','KKN')->first();
        $adaPenempatanKKN = \App\Models\PenempatanKkn::where('nim', $mahasiswa->nim)->exists();
    @endphp
    <div class="p-6 space-y-3">
        @if($mk?->preferensi_lokasi_id)
        <div><p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Lokasi KKN</p>
        <p class="text-sm font-bold text-gray-700">Desa {{ \App\Models\LokasiKkn::find($mk->preferensi_lokasi_id)?->desa }}</p></div>
        @endif
        @foreach([['KRS', $mk?->link_dokumen_1], ['Bukti Pembayaran KRS', $mk?->link_dokumen_2]] as [$label, $link])
        @if($link)
        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-gray-100">
            <span class="text-xs font-bold text-gray-600">{{ $label }}</span>
            <a href="{{ $link }}" target="_blank" class="text-blue-500 hover:text-blue-700 text-xs font-bold flex items-center gap-1"><i class="fas fa-external-link-alt text-[10px]"></i> Lihat</a>
        </div>
        @endif
        @endforeach
        @if($mk && !$adaPenempatanKKN)
        <form action="{{ route('mahasiswa.batalkan-kegiatan', $mk->id) }}" method="POST" class="pt-1">
            @csrf
            <button type="submit" onclick="return confirm('Batalkan pendaftaran KKN?')"
                class="w-full py-2.5 text-red-500 border border-red-200 hover:bg-red-50 font-bold rounded-2xl text-xs transition-all">
                <i class="fas fa-times-circle mr-1"></i> Batalkan Pendaftaran KKN
            </button>
        </form>
        @elseif($adaPenempatanKKN)
        <p class="text-[10px] text-emerald-600 font-bold text-center pt-1"><i class="fas fa-map-marker-alt mr-1"></i>Sudah mendapat penempatan — tidak bisa dibatalkan</p>
        @endif
    </div>

    @elseif($kknLock)
    <div class="p-6 text-center text-xs text-gray-400">
        <p>Sudah terdaftar di <strong>{{ implode('/', array_intersect(['KKN','PPL','PKL'], $sudahDaftar)) }}</strong>.</p>
        <p class="mt-1">KKN, PPL, PKL hanya 1 per tahun akademik.</p>
    </div>

    @else
    <form action="{{ route('mahasiswa.daftar-kegiatan') }}" method="POST">
        @csrf
        <input type="hidden" name="kegiatan" value="KKN">
        <input type="hidden" name="tahun_akademik_id" value="{{ $ta->id }}">
        <div class="p-6 space-y-5">

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Preferensi Lokasi / Desa KKN</label>
                <select name="preferensi_lokasi_id" class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 text-sm focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all font-medium appearance-none">
                    <option value="">— Pilih Lokasi (opsional) —</option>
                    @foreach($lokasiKkn as $l)<option value="{{ $l->id }}">Desa {{ $l->desa }}, {{ $l->kecamatan }}</option>@endforeach
                </select>
            </div>

            <div class="p-5 bg-blue-50/50 rounded-3xl space-y-4 border border-blue-100">
                <p class="text-xs font-black text-blue-600 uppercase tracking-widest flex items-center gap-2"><i class="fas fa-file-alt"></i>Dokumen Persyaratan</p>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Link KRS <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-blue-300"><i class="fas fa-link text-sm"></i></div>
                        <input type="url" name="link_dokumen_1" required placeholder="https://drive.google.com/..."
                            class="block w-full pl-11 pr-4 py-3.5 bg-white border border-blue-100 rounded-2xl text-gray-700 text-sm focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 transition-all font-medium">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Link Bukti Pembayaran KRS <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-blue-300"><i class="fas fa-link text-sm"></i></div>
                        <input type="url" name="link_dokumen_2" required placeholder="https://drive.google.com/..."
                            class="block w-full pl-11 pr-4 py-3.5 bg-white border border-blue-100 rounded-2xl text-gray-700 text-sm focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 transition-all font-medium">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-xl shadow-blue-200 transition-all duration-300 hover:-translate-y-0.5 active:scale-[0.98] uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                <i class="fas fa-hands-helping"></i> Daftar KKN
            </button>
        </div>
    </form>
    @endif
</div>

{{-- ═══════════════════ CARD PPL ═══════════════════ --}}
@php $sudahPPL = in_array('PPL', $sudahDaftar); $pplLock = !$sudahPPL && $sudahAdaGrupKPN; @endphp
<div class="bg-white rounded-[2rem] border {{ $sudahPPL ? 'border-emerald-200' : ($pplLock ? 'border-gray-100' : 'border-emerald-100') }} overflow-hidden shadow-sm {{ $pplLock ? 'opacity-50' : '' }}">

    <div class="px-6 py-4 {{ $sudahPPL ? 'bg-emerald-50 border-emerald-100' : ($pplLock ? 'bg-gray-50 border-gray-100' : 'bg-emerald-50/70 border-emerald-100') }} border-b flex items-center gap-3">
        <div class="w-11 h-11 {{ $sudahPPL ? 'bg-emerald-100 text-emerald-600' : ($pplLock ? 'bg-gray-200 text-gray-400' : 'bg-emerald-100 text-emerald-600') }} rounded-2xl flex items-center justify-center text-lg shrink-0">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="font-black text-gray-800 text-sm">PPL</p>
            <p class="text-[11px] text-gray-500">Praktik Pengalaman Lapangan</p>
        </div>
        @if($sudahPPL) <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-black uppercase shrink-0"><i class="fas fa-check mr-1"></i>Terdaftar</span>
        @elseif($pplLock) <span class="px-2.5 py-1 bg-gray-100 text-gray-400 rounded-full text-[10px] font-bold shrink-0"><i class="fas fa-lock mr-1"></i>Tidak Tersedia</span>
        @endif
    </div>

    @if($sudahPPL)
    @php
        $mk = $mahasiswa->mahasiswaKegiatan->where('tahun_akademik',$taStr)->where('kegiatan','PPL')->first();
        $adaPenempatanPPL = \App\Models\PenempatanPpl::where('nim', $mahasiswa->nim)->exists();
    @endphp
    <div class="p-6 space-y-3">
        @if($mk?->preferensi_lokasi_id)
        <div><p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Sekolah Pilihan</p>
        <p class="text-sm font-bold text-gray-700">{{ \App\Models\LokasiPpl::find($mk->preferensi_lokasi_id)?->Sekolah }}</p></div>
        @endif
        @if($mk?->bidang_minat)<div><p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Mata Pelajaran</p><p class="text-sm font-bold text-gray-700">{{ $mk->bidang_minat }}</p></div>@endif
        @foreach([['KRS', $mk?->link_dokumen_1], ['Bukti Pembayaran KRS', $mk?->link_dokumen_2]] as [$label, $link])
        @if($link)
        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-gray-100">
            <span class="text-xs font-bold text-gray-600">{{ $label }}</span>
            <a href="{{ $link }}" target="_blank" class="text-blue-500 hover:text-blue-700 text-xs font-bold flex items-center gap-1"><i class="fas fa-external-link-alt text-[10px]"></i> Lihat</a>
        </div>
        @endif
        @endforeach
        @if($mk && !$adaPenempatanPPL)
        <form action="{{ route('mahasiswa.batalkan-kegiatan', $mk->id) }}" method="POST" class="pt-1">
            @csrf
            <button type="submit" onclick="return confirm('Batalkan pendaftaran PPL?')"
                class="w-full py-2.5 text-red-500 border border-red-200 hover:bg-red-50 font-bold rounded-2xl text-xs transition-all">
                <i class="fas fa-times-circle mr-1"></i> Batalkan Pendaftaran PPL
            </button>
        </form>
        @elseif($adaPenempatanPPL)
        <p class="text-[10px] text-emerald-600 font-bold text-center pt-1"><i class="fas fa-school mr-1"></i>Sudah mendapat penempatan — tidak bisa dibatalkan</p>
        @endif
    </div>

    @elseif($pplLock)
    <div class="p-6 text-center text-xs text-gray-400">
        <p>Sudah terdaftar di <strong>{{ implode('/', array_intersect(['KKN','PPL','PKL'], $sudahDaftar)) }}</strong>.</p>
        <p class="mt-1">KKN, PPL, PKL hanya 1 per tahun akademik.</p>
    </div>

    @else
    <form action="{{ route('mahasiswa.daftar-kegiatan') }}" method="POST">
        @csrf
        <input type="hidden" name="kegiatan" value="PPL">
        <input type="hidden" name="tahun_akademik_id" value="{{ $ta->id }}">
        <div class="p-6 space-y-5">

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Preferensi Sekolah PPL</label>
                <select name="preferensi_lokasi_id" class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-600 focus:bg-white transition-all font-medium appearance-none">
                    <option value="">— Pilih Sekolah (opsional) —</option>
                    @foreach($lokasiPpl as $l)<option value="{{ $l->id }}">{{ $l->Sekolah }}</option>@endforeach
                </select>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Mata Pelajaran yang Akan Diajarkan</label>
                <input type="text" name="bidang_minat" placeholder="Contoh: Matematika, Bahasa Inggris, IPA..."
                    class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-600 focus:bg-white transition-all">
            </div>

            <div class="p-5 bg-emerald-50/50 rounded-3xl space-y-4 border border-emerald-100">
                <p class="text-xs font-black text-emerald-600 uppercase tracking-widest flex items-center gap-2"><i class="fas fa-file-alt"></i>Dokumen Persyaratan</p>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Link KRS <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-emerald-300"><i class="fas fa-link text-sm"></i></div>
                        <input type="url" name="link_dokumen_1" required placeholder="https://drive.google.com/..."
                            class="block w-full pl-11 pr-4 py-3.5 bg-white border border-emerald-100 rounded-2xl text-gray-700 text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-600 transition-all font-medium">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Link Bukti Pembayaran KRS <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-emerald-300"><i class="fas fa-link text-sm"></i></div>
                        <input type="url" name="link_dokumen_2" required placeholder="https://drive.google.com/..."
                            class="block w-full pl-11 pr-4 py-3.5 bg-white border border-emerald-100 rounded-2xl text-gray-700 text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-600 transition-all font-medium">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl shadow-xl shadow-emerald-200 transition-all duration-300 hover:-translate-y-0.5 active:scale-[0.98] uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                <i class="fas fa-chalkboard-teacher"></i> Daftar PPL
            </button>
        </div>
    </form>
    @endif
</div>

{{-- ═══════════════════ CARD PKL ═══════════════════ --}}
@php $sudahPKL = in_array('PKL', $sudahDaftar); $pklLock = !$sudahPKL && $sudahAdaGrupKPN; @endphp
<div class="bg-white rounded-[2rem] border {{ $sudahPKL ? 'border-emerald-200' : ($pklLock ? 'border-gray-100' : 'border-amber-100') }} overflow-hidden shadow-sm {{ $pklLock ? 'opacity-50' : '' }}">

    <div class="px-6 py-4 {{ $sudahPKL ? 'bg-emerald-50 border-emerald-100' : ($pklLock ? 'bg-gray-50 border-gray-100' : 'bg-amber-50/70 border-amber-100') }} border-b flex items-center gap-3">
        <div class="w-11 h-11 {{ $sudahPKL ? 'bg-emerald-100 text-emerald-600' : ($pklLock ? 'bg-gray-200 text-gray-400' : 'bg-amber-100 text-amber-600') }} rounded-2xl flex items-center justify-center text-lg shrink-0">
            <i class="fas fa-building"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="font-black text-gray-800 text-sm">PKL</p>
            <p class="text-[11px] text-gray-500">Praktik Kerja Lapangan</p>
        </div>
        @if($sudahPKL) <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-black uppercase shrink-0"><i class="fas fa-check mr-1"></i>Terdaftar</span>
        @elseif($pklLock) <span class="px-2.5 py-1 bg-gray-100 text-gray-400 rounded-full text-[10px] font-bold shrink-0"><i class="fas fa-lock mr-1"></i>Tidak Tersedia</span>
        @endif
    </div>

    @if($sudahPKL)
    @php
        $mk = $mahasiswa->mahasiswaKegiatan->where('tahun_akademik',$taStr)->where('kegiatan','PKL')->first();
        $adaPenempatanPKL = \App\Models\PenempatanPkl::where('nim', $mahasiswa->nim)->exists();
    @endphp
    <div class="p-6 space-y-3">
        @if($mk?->nama_instansi_pilihan)<div><p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Instansi Pilihan</p><p class="text-sm font-bold text-gray-700">{{ $mk->nama_instansi_pilihan }}</p></div>@endif
        @if($mk?->bidang_minat)<div><p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Bidang Kerja</p><p class="text-sm font-bold text-gray-700">{{ $mk->bidang_minat }}</p></div>@endif
        @foreach([['KRS', $mk?->link_dokumen_1], ['Bukti Pembayaran KRS', $mk?->link_dokumen_2], ['CV', $mk?->link_dokumen_3]] as [$label, $link])
        @if($link)
        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-gray-100">
            <span class="text-xs font-bold text-gray-600">{{ $label }}</span>
            <a href="{{ $link }}" target="_blank" class="text-blue-500 hover:text-blue-700 text-xs font-bold flex items-center gap-1"><i class="fas fa-external-link-alt text-[10px]"></i> Lihat</a>
        </div>
        @endif
        @endforeach
        @if($mk && !$adaPenempatanPKL)
        <form action="{{ route('mahasiswa.batalkan-kegiatan', $mk->id) }}" method="POST" class="pt-1">
            @csrf
            <button type="submit" onclick="return confirm('Batalkan pendaftaran PKL?')"
                class="w-full py-2.5 text-red-500 border border-red-200 hover:bg-red-50 font-bold rounded-2xl text-xs transition-all">
                <i class="fas fa-times-circle mr-1"></i> Batalkan Pendaftaran PKL
            </button>
        </form>
        @elseif($adaPenempatanPKL)
        <p class="text-[10px] text-emerald-600 font-bold text-center pt-1"><i class="fas fa-building mr-1"></i>Sudah mendapat penempatan — tidak bisa dibatalkan</p>
        @endif
    </div>

    @elseif($pklLock)
    <div class="p-6 text-center text-xs text-gray-400">
        <p>Sudah terdaftar di <strong>{{ implode('/', array_intersect(['KKN','PPL','PKL'], $sudahDaftar)) }}</strong>.</p>
        <p class="mt-1">KKN, PPL, PKL hanya 1 per tahun akademik.</p>
    </div>

    @else
    <form action="{{ route('mahasiswa.daftar-kegiatan') }}" method="POST">
        @csrf
        <input type="hidden" name="kegiatan" value="PKL">
        <input type="hidden" name="tahun_akademik_id" value="{{ $ta->id }}">
        <div class="p-6 space-y-5">

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Instansi / Perusahaan yang Dituju <span class="text-red-400">*</span></label>
                <input type="text" name="nama_instansi_pilihan" required placeholder="Contoh: PT Bali Digital, CV Karya Mandiri..."
                    class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 text-sm focus:outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 focus:bg-white transition-all font-bold">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Bidang Kerja</label>
                    <input type="text" name="bidang_minat" placeholder="Contoh: IT, Akuntansi..."
                        class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 text-sm focus:outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 focus:bg-white transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Alamat Instansi</label>
                    <input type="text" name="alamat_instansi" placeholder="Jl. ..."
                        class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 text-sm focus:outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 focus:bg-white transition-all">
                </div>
            </div>

            <div class="p-5 bg-amber-50/50 rounded-3xl space-y-4 border border-amber-100">
                <p class="text-xs font-black text-amber-600 uppercase tracking-widest flex items-center gap-2"><i class="fas fa-file-alt"></i>Dokumen Persyaratan</p>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-amber-500 uppercase tracking-widest">Link KRS <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-amber-300"><i class="fas fa-link text-sm"></i></div>
                        <input type="url" name="link_dokumen_1" required placeholder="https://drive.google.com/..."
                            class="block w-full pl-11 pr-4 py-3.5 bg-white border border-amber-100 rounded-2xl text-gray-700 text-sm focus:outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all font-medium">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-amber-500 uppercase tracking-widest">Link Bukti Pembayaran KRS <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-amber-300"><i class="fas fa-link text-sm"></i></div>
                        <input type="url" name="link_dokumen_2" required placeholder="https://drive.google.com/..."
                            class="block w-full pl-11 pr-4 py-3.5 bg-white border border-amber-100 rounded-2xl text-gray-700 text-sm focus:outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all font-medium">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-amber-500 uppercase tracking-widest">Link CV <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-amber-300"><i class="fas fa-link text-sm"></i></div>
                        <input type="url" name="link_dokumen_3" required placeholder="https://drive.google.com/..."
                            class="block w-full pl-11 pr-4 py-3.5 bg-white border border-amber-100 rounded-2xl text-gray-700 text-sm focus:outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all font-medium">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-4 bg-amber-500 hover:bg-amber-600 text-white font-black rounded-2xl shadow-xl shadow-amber-100 transition-all duration-300 hover:-translate-y-0.5 active:scale-[0.98] uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                <i class="fas fa-building"></i> Daftar PKL
            </button>
        </div>
    </form>
    @endif
</div>

{{-- ═══════════════════ CARD MAGANG ═══════════════════ --}}
@php $sudahMagang = in_array('Magang', $sudahDaftar); @endphp
<div class="bg-white rounded-[2rem] border {{ $sudahMagang ? 'border-emerald-200' : 'border-indigo-100' }} overflow-hidden shadow-sm">

    <div class="px-6 py-4 {{ $sudahMagang ? 'bg-emerald-50 border-emerald-100' : 'bg-indigo-50/60 border-indigo-100' }} border-b flex items-center gap-3">
        <div class="w-11 h-11 {{ $sudahMagang ? 'bg-emerald-100 text-emerald-600' : 'bg-indigo-100 text-indigo-600' }} rounded-2xl flex items-center justify-center text-lg shrink-0">
            <i class="fas fa-briefcase"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="font-black text-gray-800 text-sm">Magang</p>
            <p class="text-[11px] text-gray-500">Internship / Magang Industri</p>
        </div>
        @if($sudahMagang)
            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-black uppercase shrink-0"><i class="fas fa-check mr-1"></i>Terdaftar</span>
        @else
            <span class="px-2.5 py-1 bg-indigo-50 text-indigo-500 rounded-full text-[10px] font-bold border border-indigo-100 shrink-0">Bebas</span>
        @endif
    </div>

    @if($sudahMagang)
    @php
        $mk = $mahasiswa->mahasiswaKegiatan->where('tahun_akademik',$taStr)->where('kegiatan','Magang')->first();
        $adaPenempatanMagang = \App\Models\PenempatanMagang::where('nim', $mahasiswa->nim)->exists();
    @endphp
    <div class="p-6 space-y-3">
        @if($mk?->nama_instansi_pilihan)<div><p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Instansi Pilihan</p><p class="text-sm font-bold text-gray-700">{{ $mk->nama_instansi_pilihan }}</p></div>@endif
        @if($mk?->bidang_minat)<div><p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Posisi / Divisi</p><p class="text-sm font-bold text-gray-700">{{ $mk->bidang_minat }}</p></div>@endif
        @foreach([['KRS', $mk?->link_dokumen_1], ['Bukti Pembayaran KRS', $mk?->link_dokumen_2], ['CV', $mk?->link_dokumen_3]] as [$label, $link])
        @if($link)
        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-gray-100">
            <span class="text-xs font-bold text-gray-600">{{ $label }}</span>
            <a href="{{ $link }}" target="_blank" class="text-blue-500 hover:text-blue-700 text-xs font-bold flex items-center gap-1"><i class="fas fa-external-link-alt text-[10px]"></i> Lihat</a>
        </div>
        @endif
        @endforeach
        @if($mk && !$adaPenempatanMagang)
        <form action="{{ route('mahasiswa.batalkan-kegiatan', $mk->id) }}" method="POST" class="pt-1">
            @csrf
            <button type="submit" onclick="return confirm('Batalkan pendaftaran Magang?')"
                class="w-full py-2.5 text-red-500 border border-red-200 hover:bg-red-50 font-bold rounded-2xl text-xs transition-all">
                <i class="fas fa-times-circle mr-1"></i> Batalkan Pendaftaran Magang
            </button>
        </form>
        @elseif($adaPenempatanMagang)
        <p class="text-[10px] text-emerald-600 font-bold text-center pt-1"><i class="fas fa-briefcase mr-1"></i>Sudah mendapat penempatan — tidak bisa dibatalkan</p>
        @endif
    </div>

    @else
    <form action="{{ route('mahasiswa.daftar-kegiatan') }}" method="POST">
        @csrf
        <input type="hidden" name="kegiatan" value="Magang">
        <input type="hidden" name="tahun_akademik_id" value="{{ $ta->id }}">
        <div class="p-6 space-y-5">

            <div class="p-3 bg-indigo-50 border border-indigo-100 rounded-2xl text-xs text-indigo-600 font-bold flex items-center gap-2">
                <i class="fas fa-info-circle shrink-0"></i>
                Magang dapat diikuti bersamaan dengan kegiatan lain, tidak dibatasi per tahun akademik.
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Instansi / Perusahaan yang Dituju <span class="text-red-400">*</span></label>
                <input type="text" name="nama_instansi_pilihan" required placeholder="Contoh: Bank BPD Bali, Dinas Pariwisata..."
                    class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 text-sm focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all font-bold">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Posisi / Divisi</label>
                    <input type="text" name="bidang_minat" placeholder="Contoh: IT Support..."
                        class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 text-sm focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Alamat Instansi</label>
                    <input type="text" name="alamat_instansi" placeholder="Jl. ..."
                        class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-gray-700 text-sm focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all">
                </div>
            </div>

            <div class="p-5 bg-indigo-50/50 rounded-3xl space-y-4 border border-indigo-100">
                <p class="text-xs font-black text-indigo-600 uppercase tracking-widest flex items-center gap-2"><i class="fas fa-file-alt"></i>Dokumen Persyaratan</p>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Link KRS <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-indigo-300"><i class="fas fa-link text-sm"></i></div>
                        <input type="url" name="link_dokumen_1" required placeholder="https://drive.google.com/..."
                            class="block w-full pl-11 pr-4 py-3.5 bg-white border border-indigo-100 rounded-2xl text-gray-700 text-sm focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Link Bukti Pembayaran KRS <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-indigo-300"><i class="fas fa-link text-sm"></i></div>
                        <input type="url" name="link_dokumen_2" required placeholder="https://drive.google.com/..."
                            class="block w-full pl-11 pr-4 py-3.5 bg-white border border-indigo-100 rounded-2xl text-gray-700 text-sm focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Link CV <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-indigo-300"><i class="fas fa-link text-sm"></i></div>
                        <input type="url" name="link_dokumen_3" required placeholder="https://drive.google.com/..."
                            class="block w-full pl-11 pr-4 py-3.5 bg-white border border-indigo-100 rounded-2xl text-gray-700 text-sm focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-xl shadow-indigo-100 transition-all duration-300 hover:-translate-y-0.5 active:scale-[0.98] uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                <i class="fas fa-briefcase"></i> Daftar Magang
            </button>
        </div>
    </form>
    @endif
</div>

        </div>{{-- end grid --}}
    </div>
    @endforeach
    @endif

</div>
@endsection
