@extends('layouts.admin')

@section('title', 'Master Data Sekolah PPL')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
        <div></div>
        <a href="{{ route('lokasippl.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-emerald-100 group">
            <i class="fas fa-school mr-2 group-hover:scale-110 transition-transform"></i>
            Tambah Sekolah Baru
        </a>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-0" id="sekolahTable">
                <thead>
                    <tr>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 rounded-tl-2xl">Nama Sekolah</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 text-center rounded-tr-2xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($Lokasippl as $lokasippl)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold border border-emerald-100 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-university text-xs"></i>
                                </div>
                                <span class="text-sm font-bold text-slate-700">{{ $lokasippl->Sekolah }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="#" class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-600 hover:text-white transition-all">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <form action="{{ route('lokasippl.delete', $lokasippl->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all" onclick="return confirm('Hapus sekolah ini?')">
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
        $('#sekolahTable').DataTable({
            "language": {
                "search": "",
                "searchPlaceholder": "Cari sekolah...",
                "lengthMenu": "_MENU_ baris"
            },
            "dom": '<"flex flex-col sm:flex-row sm:items-center sm:justify-between px-8 py-4 gap-4"lf>rt<"flex flex-col sm:flex-row sm:items-center sm:justify-between px-8 py-4 gap-4"ip>'
        });
    });
</script>
@endsection
