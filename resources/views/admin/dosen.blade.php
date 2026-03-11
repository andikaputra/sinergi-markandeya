@extends('layouts.admin')

@section('title', 'Manajemen Data Dosen')

@section('content')
<div class="space-y-6">
    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Daftar Dosen Pembimbing</h2>
            <p class="text-sm text-gray-500 font-medium">Kelola data tenaga pendidik untuk pendampingan KKN, PPL, dan PKL.</p>
        </div>
        <a href="{{ route('dosen.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-100 group">
            <i class="fas fa-plus mr-2 group-hover:scale-110 transition-transform"></i>
            Tambah Dosen
        </a>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-0" id="dosenTable">
                <thead>
                    <tr>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 rounded-tl-2xl">Identitas Dosen</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100">NIDN</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 text-right rounded-tr-2xl">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($dosens as $dosen)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black border border-indigo-100 group-hover:scale-110 transition-transform">
                                    {{ substr($dosen->nama, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800 tracking-tight">{{ $dosen->nama }}</p>
                                    <p class="text-[10px] font-bold text-emerald-500 uppercase">Aktif Mengajar</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="font-mono text-sm text-gray-400 bg-gray-50 px-3 py-1 rounded-lg border border-gray-100">
                                {{ $dosen->nidn }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <button class="p-2.5 bg-slate-50 text-slate-400 rounded-xl hover:bg-blue-50 hover:text-blue-600 transition-all border border-transparent hover:border-blue-100">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <button class="p-2.5 bg-slate-50 text-slate-400 rounded-xl hover:bg-red-50 hover:text-red-600 transition-all border border-transparent hover:border-red-100">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
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
        $('#dosenTable').DataTable({
            "language": {
                "search": "",
                "searchPlaceholder": "Cari nama atau NIDN...",
                "lengthMenu": "_MENU_ baris"
            },
            "dom": '<"flex flex-col sm:flex-row sm:items-center sm:justify-between px-0 py-4 gap-4"lf>rt<"flex flex-col sm:flex-row sm:items-center sm:justify-between px-0 py-4 gap-4"ip>'
        });
    });
</script>
@endsection
