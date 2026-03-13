@extends('layouts.admin')

@section('title', 'Master Data Lokasi Magang')

@section('content')
<div class="space-y-6">
    @if(session('success'))
    <div class="p-5 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-700 text-sm font-bold flex items-center">
        <i class="fas fa-check-circle mr-3 text-lg"></i>
        {{ session('success') }}
    </div>
    @endif

    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
        <div></div>
        <a href="{{ route('lokasimagang.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-indigo-500 hover:bg-indigo-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-indigo-100 group">
            <i class="fas fa-plus mr-2 group-hover:scale-110 transition-transform"></i>
            Tambah Instansi Magang
        </a>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-0" id="magangTable">
                <thead>
                    <tr>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 rounded-tl-2xl">Nama Instansi</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100">Alamat</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100">Kontak</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 text-right rounded-tr-2xl">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($lokasiMagangs as $lokasi)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black border border-indigo-100 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-briefcase text-xs"></i>
                                </div>
                                <span class="text-sm font-bold text-gray-800 tracking-tight">{{ $lokasi->nama_instansi }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-sm text-slate-500 font-medium">
                            {{ $lokasi->alamat ?? '-' }}
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-xs font-bold text-slate-600">{{ $lokasi->kontak ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <form action="{{ route('lokasimagang.delete', $lokasi->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all border border-transparent hover:border-red-100" onclick="return confirm('Hapus instansi ini?')">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#magangTable').DataTable({
            "language": {
                "search": "",
                "searchPlaceholder": "Cari instansi...",
                "lengthMenu": "_MENU_ baris"
            }
        });
    });
</script>
@endsection
