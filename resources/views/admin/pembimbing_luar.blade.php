@extends('layouts.admin')

@section('title', 'Manajemen Pembimbing Luar')

@section('content')
<div class="space-y-6">
    @if(session('success'))
    <div class="p-5 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-700 text-sm font-bold flex items-center">
        <i class="fas fa-check-circle mr-3 text-lg"></i>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="p-5 bg-red-50 border border-red-200 rounded-2xl text-red-700 text-sm font-bold flex items-center">
        <i class="fas fa-exclamation-triangle mr-3 text-lg"></i>
        {{ session('error') }}
    </div>
    @endif

    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
        <div>
            <p class="text-sm text-gray-400">Kelola data pembimbing dari luar institusi</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('pembimbing_luar.create') }}" class="inline-flex items-center justify-center px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-emerald-100 group">
                <i class="fas fa-plus mr-2 group-hover:scale-110 transition-transform"></i>
                Tambah Pembimbing Luar
            </a>
        </div>
    </div>

    <!-- Import CSV -->
    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="flex-1">
                <h5 class="text-sm font-bold text-gray-800 flex items-center mb-1">
                    <i class="fas fa-file-csv text-emerald-500 mr-2"></i> Import Pembimbing Luar via CSV
                </h5>
                <p class="text-xs text-gray-400">Format: <code class="bg-gray-100 px-1.5 py-0.5 rounded text-gray-600 font-mono">nama, email, instansi, no_hp</code> &mdash; Password default = Email</p>
            </div>
            <form action="{{ route('pembimbing_luar.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                @csrf
                <input type="file" name="file_csv" accept=".csv,.txt" required class="block text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-emerald-50 file:text-emerald-600 cursor-pointer hover:file:bg-emerald-100">
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black transition-all whitespace-nowrap shadow-sm">
                    <i class="fas fa-upload mr-1"></i> Upload
                </button>
            </form>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-0" id="pembimbingLuarTable">
                <thead>
                    <tr>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 rounded-tl-2xl">Identitas</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100">Instansi</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100">Kontak</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 text-right rounded-tr-2xl">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($pembimbingLuars as $pl)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black border border-emerald-100 group-hover:scale-110 transition-transform">
                                    {{ substr($pl->nama, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800 tracking-tight">{{ $pl->nama }}</p>
                                    <p class="text-[10px] font-bold text-emerald-500 uppercase">Pembimbing Luar</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-sm text-gray-600 font-medium">{{ $pl->instansi }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <p class="text-sm text-gray-600">{{ $pl->email }}</p>
                            @if($pl->no_hp)
                            <p class="text-xs text-gray-400">{{ $pl->no_hp }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-right">
                            <form action="{{ route('pembimbing_luar.delete', $pl->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-100" onclick="return confirm('Hapus pembimbing luar ini?')">
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
        $('#pembimbingLuarTable').DataTable({
            "language": {
                "search": "",
                "searchPlaceholder": "Cari nama atau instansi...",
                "lengthMenu": "_MENU_ baris"
            },
            "dom": '<"flex flex-col sm:flex-row sm:items-center sm:justify-between px-0 py-4 gap-4"lf>rt<"flex flex-col sm:flex-row sm:items-center sm:justify-between px-0 py-4 gap-4"ip>'
        });
    });
</script>
@endsection
