@extends('layouts.adminmhs')

@section('title', 'Detail Bimbingan - ' . $bimbingan->topik)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('bimbingan.dashboard') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-2xl flex items-center justify-center text-gray-500 hover:text-primary-600 hover:border-primary-500 transition-all shadow-sm">
                <i class="fas fa-chevron-left text-sm"></i>
            </a>
            <div>
                <h2 class="text-2xl font-black text-gray-800">Detail Sesi Bimbingan</h2>
                <p class="text-xs text-gray-500">Informasi pengajuan, pokok bahasan, dan evaluasi dari dosen pembimbing</p>
            </div>
        </div>

        <a href="{{ route('bimbingan.cetak') }}" target="_blank" class="inline-flex items-center space-x-2 px-4 py-2 bg-white border border-gray-200 hover:border-primary-500 text-gray-700 hover:text-primary-600 text-xs font-bold rounded-xl transition-all shadow-sm">
            <i class="fas fa-print text-gold-500"></i>
            <span>Cetak Kartu</span>
        </a>
    </div>

    <!-- Status Banner -->
    <div class="p-6 rounded-3xl border flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm @if($bimbingan->status === 'disetujui') bg-emerald-50 border-emerald-200 text-emerald-900 @elseif($bimbingan->status === 'perlu_revisi') bg-amber-50 border-amber-200 text-amber-900 @else bg-blue-50 border-blue-200 text-blue-900 @endif">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl font-bold shrink-0 @if($bimbingan->status === 'disetujui') bg-emerald-100 text-emerald-700 @elseif($bimbingan->status === 'perlu_revisi') bg-amber-100 text-amber-700 @else bg-blue-100 text-blue-700 @endif">
                @if($bimbingan->status === 'disetujui')
                    <i class="fas fa-check-circle"></i>
                @elseif($bimbingan->status === 'perlu_revisi')
                    <i class="fas fa-exclamation-circle"></i>
                @else
                    <i class="fas fa-clock"></i>
                @endif
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider block opacity-75">Status Verifikasi Dosen</span>
                <h3 class="text-lg font-black">
                    @if($bimbingan->status === 'disetujui')
                        Bimbingan Disetujui
                    @elseif($bimbingan->status === 'perlu_revisi')
                        Bimbingan Perlu Revisi
                    @else
                        Menunggu Review Dosen Pembimbing
                    @endif
                </h3>
            </div>
        </div>

        <div class="text-xs font-semibold opacity-80 sm:text-right">
            <p>Jadwal Bimbingan:</p>
            <p class="font-bold text-sm">{{ \Carbon\Carbon::parse($bimbingan->tanggal_bimbingan)->translatedFormat('d F Y, H:i') }} WITA</p>
        </div>
    </div>

    <!-- Catatan Dosen Pembimbing Section (Special Highlighting) -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8 space-y-4">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gold-50 text-gold-600 flex items-center justify-center font-bold">
                    <i class="fas fa-comment-dots text-lg"></i>
                </div>
                <div>
                    <h4 class="text-base font-black text-gray-800">Catatan & Masukan Dosen Pembimbing</h4>
                    <p class="text-xs text-gray-400">Arahan revisi atau rekomendasi penyempurnaan laporan dari dosen</p>
                </div>
            </div>
            <span class="text-xs font-bold text-gray-500">
                {{ $bimbingan->dosenPembimbing?->dosen?->nama ?? 'Dosen Pembimbing' }}
            </span>
        </div>

        @if($bimbingan->catatan_dosen)
            <div class="p-6 bg-gradient-to-r from-amber-50/70 to-orange-50/70 border border-amber-200/80 rounded-2xl text-amber-950 leading-relaxed font-medium space-y-4">
                <p class="whitespace-pre-line text-sm text-gray-800">{{ $bimbingan->catatan_dosen }}</p>
                
                @if($bimbingan->status === 'perlu_revisi')
                <div class="pt-3 border-t border-amber-200/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <p class="text-xs text-amber-800 font-bold">
                        <i class="fas fa-info-circle mr-1"></i> Silakan perbaiki draf Anda dan unggah kembali melalui tautan di samping.
                    </p>
                    <a href="{{ route('bimbingan.edit', $bimbingan->id) }}" class="inline-flex items-center justify-center space-x-2 px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-black rounded-xl shadow-md shadow-amber-600/20 transition-all active:scale-95">
                        <i class="fas fa-edit"></i>
                        <span>Perbaiki & Unggah Revisi</span>
                    </a>
                </div>
                @endif
            </div>
        @else
            <div class="p-8 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                <i class="fas fa-hourglass-start text-gray-300 text-3xl mb-2"></i>
                <p class="text-sm font-semibold text-gray-500">Dosen pembimbing belum memberikan catatan untuk sesi bimbingan ini.</p>
                <p class="text-xs text-gray-400 mt-1">Catatan dan arahan akan otomatis tampil di sini setelah dosen me-review.</p>
            </div>
        @endif
    </div>

    <!-- Main Details Box -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8 space-y-6">
        <h4 class="text-lg font-black text-gray-800 border-b border-gray-100 pb-4">Materi & Pokok Bahasan Mahasiswa</h4>

        <!-- Topik -->
        <div class="space-y-1">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Topik Konsultasi</span>
            <p class="text-base font-bold text-gray-900">{{ $bimbingan->topik }}</p>
        </div>

        <!-- Deskripsi -->
        <div class="space-y-1">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Deskripsi & Progres</span>
            <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100 text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                {{ $bimbingan->deskripsi }}
            </div>
        </div>

        <!-- Lampiran Dokumen -->
        @if($bimbingan->materi_terlampir)
        <div class="space-y-2 pt-2 border-t border-gray-100">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Berkas Terlampir</span>
            <div class="flex items-center justify-between p-4 bg-primary-50/40 border border-primary-100 rounded-2xl">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center font-bold">
                        <i class="fas fa-file-alt text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-800">{{ $bimbingan->materi_terlampir }}</p>
                        <p class="text-[10px] text-gray-400">Draft Laporan / Dokumen Bimbingan</p>
                    </div>
                </div>
                <a
                    href="{{ asset('storage/bimbingan/' . $bimbingan->materi_terlampir) }}"
                    download
                    class="inline-flex items-center space-x-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm"
                >
                    <i class="fas fa-download"></i>
                    <span>Unduh Berkas</span>
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
