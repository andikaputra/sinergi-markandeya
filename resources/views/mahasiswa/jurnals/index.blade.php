@extends('layouts.adminmhs')

@section('title', 'Jurnal Harian')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Riwayat Jurnal</h2>
            <p class="text-sm text-gray-500">Catat setiap aktivitas harian Anda selama program berlangsung.</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('jurnal.cetak') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center px-6 py-3 bg-white border border-gray-200 text-gray-600 hover:text-blue-600 hover:border-blue-200 font-bold rounded-2xl transition-all duration-300 shadow-sm group">
                <i class="fas fa-print mr-2 group-hover:scale-110 transition-transform"></i>
                Cetak Jurnal (PDF)
            </a>
            <a href="{{ route('jurnal.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all duration-300 shadow-lg shadow-blue-200 group">
                <i class="fas fa-plus mr-2 group-hover:rotate-90 transition-transform"></i>
                Tambah Jurnal Baru
            </a>
        </div>
    </div>

    @if($jurnals->isEmpty())
    <div class="bg-white p-12 rounded-3xl shadow-sm border border-gray-100 text-center">
        <div class="bg-gray-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
            <i class="fas fa-book-open text-3xl"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-800">Belum ada jurnal</h3>
        <p class="text-gray-500 max-w-xs mx-auto mt-2">Anda belum mencatat aktivitas harian. Klik tombol di atas untuk mulai mencatat.</p>
    </div>
    @else
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Aktivitas / Kegiatan</th>
                        <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($jurnals as $jurnal)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-8 py-5 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <div class="bg-blue-50 text-blue-600 p-2 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                    <i class="far fa-calendar-alt"></i>
                                </div>
                                <span class="text-sm font-bold text-gray-700">
                                    {{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d F Y') }}
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <p class="text-sm text-gray-600 leading-relaxed line-clamp-2 group-hover:line-clamp-none transition-all duration-300">
                                {{ $jurnal->kegiatan }}
                            </p>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <button class="p-2 text-gray-400 hover:text-blue-600 transition-colors">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
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
