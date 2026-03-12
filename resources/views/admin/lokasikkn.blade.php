@extends('layouts.admin')

@section('title', 'Master Data Lokasi KKN')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
        <div></div>
        <a href="{{ route('lokasikkn.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-100 group">
            <i class="fas fa-plus-circle mr-2 group-hover:rotate-90 transition-transform"></i>
            Tambah Lokasi Baru
        </a>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-0" id="lokasiTable">
                <thead>
                    <tr>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 rounded-tl-2xl">Wilayah Desa</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100">Alamat Lengkap</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100">Kecamatan</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($tempatKKNs as $tempatKKN)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold border border-blue-100 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-map-marked-alt text-xs"></i>
                                </div>
                                <span class="text-sm font-bold text-slate-700">Desa {{ $tempatKKN->desa }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-sm text-slate-500 font-medium">
                            {{ $tempatKKN->alamat ?? '-' }}
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-xs font-bold text-slate-600 px-3 py-1 bg-slate-100 rounded-full border border-slate-200">
                                {{ $tempatKKN->kecamatan }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="#" class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-600 hover:text-white transition-all">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <form action="{{ route('lokasikkn.delete', $tempatKKN->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all" onclick="return confirm('Hapus lokasi ini?')">
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
        $('#lokasiTable').DataTable({
            "language": {
                "search": "",
                "searchPlaceholder": "Cari lokasi...",
                "lengthMenu": "_MENU_ baris"
            },
            "dom": '<"flex flex-col sm:flex-row sm:items-center sm:justify-between px-8 py-4 gap-4"lf>rt<"flex flex-col sm:flex-row sm:items-center sm:justify-between px-8 py-4 gap-4"ip>'
        });
    });
</script>
@endsection
