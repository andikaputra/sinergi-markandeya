@extends('layouts.admin')

@section('title', 'Manajemen Pengajuan Magang')

@section('content')
<div class="space-y-8">
    @if(session('success'))
    <div class="p-5 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-700 text-sm font-bold flex items-center">
        <i class="fas fa-check-circle mr-3 text-lg"></i>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="p-5 bg-red-50 border border-red-200 rounded-2xl text-red-700 text-sm font-bold flex items-center">
        <i class="fas fa-times-circle mr-3 text-lg"></i>
        {{ session('error') }}
    </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Pengajuan Lokasi Magang</h2>
            <p class="text-sm text-gray-500 font-medium">Verifikasi dan kelola penempatan mandiri mahasiswa Magang.</p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl text-xs font-black border border-indigo-100">
                TOTAL: {{ $pengajuans->count() }}
            </span>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-0" id="magangPengajuanTable">
                <thead>
                    <tr>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 rounded-tl-2xl">Mahasiswa</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100">Instansi / Perusahaan</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 text-center">Status</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 text-right rounded-tr-2xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($pengajuans as $pengajuan)
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs border border-indigo-100 group-hover:scale-110 transition-transform">
                                    {{ substr($pengajuan->mahasiswa->nama, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $pengajuan->mahasiswa->nama }}</p>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $pengajuan->nim }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="space-y-1">
                                <p class="text-sm font-bold text-slate-700">{{ $pengajuan->nama_instansi }}</p>
                                <div class="flex items-center text-[10px] text-slate-400 font-medium italic">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    {{ $pengajuan->alamat }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($pengajuan->status == 'pending')
                                <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[10px] font-black border border-amber-100 uppercase tracking-wider animate-pulse">Pending</span>
                            @elseif($pengajuan->status == 'approved')
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black border border-emerald-100 uppercase tracking-wider">Approved</span>
                            @else
                                <span class="px-3 py-1 bg-red-50 text-red-600 rounded-full text-[10px] font-black border border-red-100 uppercase tracking-wider">Rejected</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-right">
                            @if($pengajuan->status == 'pending')
                            <div class="flex items-center justify-end space-x-2">
                                <form action="{{ route('pengajuanmagang.approve', $pengajuan->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-black rounded-lg transition-all shadow-md shadow-emerald-100 uppercase tracking-widest">
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('pengajuanmagang.reject', $pengajuan->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-white border border-gray-200 text-red-600 text-[10px] font-black rounded-lg hover:bg-red-50 transition-all uppercase tracking-widest">
                                        Reject
                                    </button>
                                </form>
                            </div>
                            @else
                                <span class="text-xs font-bold text-slate-300 italic">No Actions</span>
                            @endif
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
        $('#magangPengajuanTable').DataTable({
            "language": {
                "search": "",
                "searchPlaceholder": "Cari pengajuan..."
            }
        });
    });
</script>
@endsection
