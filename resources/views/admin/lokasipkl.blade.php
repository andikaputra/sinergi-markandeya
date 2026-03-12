@extends('layouts.admin')

@section('title', 'Master Data Lokasi PKL')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
        <div></div>
        <a href="{{ route('lokasipkl.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-amber-100 group">
            <i class="fas fa-plus mr-2 group-hover:scale-110 transition-transform"></i>
            Tambah Instansi
        </a>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-0" id="pklTable">
                <thead>
                    <tr>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 rounded-tl-2xl">Nama Instansi</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100">Alamat</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100">Kontak</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 text-right rounded-tr-2xl">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($lokasiPkls as $lokasi)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-black border border-amber-100 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-building text-xs"></i>
                                </div>
                                <span class="text-sm font-bold text-gray-800 tracking-tight">{{ $lokasi->nama_instansi }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-sm text-slate-500 font-medium">
                            {{ $lokasi->alamat ?? '-' }}
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-col space-y-1">
                                <span class="text-xs font-bold text-slate-600">{{ $lokasi->kontak ?? '-' }}</span>
                                <span class="text-[10px] text-slate-400">{{ $lokasi->email ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <form action="{{ route('lokasipkl.delete', $lokasi->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all border border-transparent hover:border-red-100" onclick="return confirm('Hapus instansi ini?')">
                                        <i class="fas fa-trash-alt text-xs"></i>
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
</div>

<script>
    $(document).ready(function() {
        $('#pklTable').DataTable({
            "language": {
                "search": "",
                "searchPlaceholder": "Cari instansi...",
                "lengthMenu": "_MENU_ baris"
            }
        });
    });
</script>
@endsection
