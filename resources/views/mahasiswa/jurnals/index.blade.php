@extends('layouts.adminmhs')

@section('title', 'Jurnal Harian')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Riwayat Jurnal</h2>
            <p class="text-sm text-gray-500">Catat setiap aktivitas harian Anda selama program berlangsung.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('jurnal.cetak') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center px-5 py-2.5 bg-white border border-gray-200 text-gray-600 hover:text-primary-600 hover:border-primary-200 font-bold rounded-2xl transition-all duration-300 shadow-sm group text-sm">
                <i class="fas fa-print mr-2 group-hover:scale-110 transition-transform"></i>
                Cetak Jurnal (PDF)
            </a>
            <a href="{{ route('jurnal.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl transition-all duration-300 shadow-lg shadow-primary-200 group text-sm">
                <i class="fas fa-plus mr-2 group-hover:rotate-90 transition-transform"></i>
                Tambah Jurnal Baru
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm font-medium animate-fadeIn">
        <i class="fas fa-check-circle text-emerald-600 text-lg flex-shrink-0"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if (session('error'))
    <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl text-sm font-medium">
        <i class="fas fa-exclamation-circle text-red-600 text-lg flex-shrink-0"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    @if($jurnals->isEmpty())
    <div class="bg-white p-12 rounded-xl shadow-sm border border-gray-100 text-center">
        <div class="bg-gray-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
            <i class="fas fa-book-open text-3xl"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-800">Belum ada jurnal</h3>
        <p class="text-gray-500 max-w-xs mx-auto mt-2">Anda belum mencatat aktivitas harian. Klik tombol di atas untuk mulai mencatat.</p>
    </div>
    @else
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest w-48">Tanggal</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Aktivitas / Kegiatan</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right w-44">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($jurnals as $jurnal)
                    <tr class="hover:bg-primary-50/30 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap align-top">
                            <div class="flex items-center space-x-3">
                                <div class="bg-primary-50 text-primary-600 p-2.5 rounded-xl group-hover:bg-primary-600 group-hover:text-white transition-colors flex-shrink-0">
                                    <i class="far fa-calendar-alt"></i>
                                </div>
                                <div>
                                    <span class="text-sm font-bold text-gray-800 block">
                                        {{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d F Y') }}
                                    </span>
                                    <span class="text-[11px] text-gray-400 font-medium">
                                        {{ \Carbon\Carbon::parse($jurnal->tanggal)->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 align-top">
                            <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap font-medium">
                                {{ $jurnal->kegiatan }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-right align-top whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('jurnal.edit', $jurnal) }}" class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-xl transition-all inline-flex items-center gap-1.5 font-bold text-xs border border-amber-200/60 shadow-sm" title="Edit Jurnal">
                                    <i class="fas fa-edit text-[11px]"></i>
                                    <span>Edit</span>
                                </a>
                                <form action="{{ route('jurnal.destroy', $jurnal) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jurnal tanggal {{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d F Y') }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl transition-all inline-flex items-center gap-1.5 font-bold text-xs border border-red-200/60 shadow-sm hover:text-red-700" title="Hapus Jurnal">
                                        <i class="fas fa-trash-alt text-[11px]"></i>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
