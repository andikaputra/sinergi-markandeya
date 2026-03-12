@extends('layouts.admin')

@section('title', 'Peserta Magang')

@section('content')
<div class="space-y-6">
    @include('admin._import_mahasiswa')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <div class="flex-1">
            <form action="{{ route('admin.peserta.magang') }}" method="GET" class="mt-2 flex items-center space-x-2">
                <select name="ta" onchange="this.form.submit()" class="text-xs font-bold bg-slate-50 border-none rounded-lg focus:ring-indigo-500">
                    <option value="">Semua Tahun Akademik</option>
                    @foreach($tahunAkademiks as $ta)
                        <option value="{{ $ta->tahun }} {{ $ta->semester }}" {{ request('ta') == ($ta->tahun . ' ' . $ta->semester) ? 'selected' : '' }}>
                            {{ $ta->tahun }} - {{ $ta->semester }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.mahasiswa.create', ['kegiatan' => 'Magang']) }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-all duration-300 shadow-md shadow-indigo-100">
                <i class="fas fa-plus mr-2"></i>
                Tambah Peserta
            </a>
            <a href="{{ route('admin.export.magang', ['ta' => request('ta')]) }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition-all duration-300">
                <i class="fas fa-file-csv mr-2"></i>
                CSV
            </a>
            <a href="{{ route('admin.print.magang', ['ta' => request('ta')]) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center px-4 py-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white font-bold rounded-xl transition-all duration-300">
                <i class="fas fa-file-pdf mr-2"></i>
                Cetak PDF
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-0" id="magangTable">
                <thead>
                    <tr>
                        <th class="px-6 py-4 bg-gray-50/50 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 rounded-tl-2xl">No</th>
                        <th class="px-6 py-4 bg-gray-50/50 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">Mahasiswa</th>
                        <th class="px-6 py-4 bg-gray-50/50 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">Program Studi</th>
                        <th class="px-6 py-4 bg-gray-50/50 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 text-center">Nilai Akhir</th>
                        <th class="px-6 py-4 bg-gray-50/50 text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 rounded-tr-2xl">Waktu Daftar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($pesertaMagang as $index => $mahasiswa)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-6 py-5 text-sm text-gray-400 font-medium italic">{{ $index + 1 }}</td>
                        <td class="px-6 py-5">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs border border-indigo-200 group-hover:scale-110 transition-transform">
                                    {{ substr($mahasiswa->nama, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $mahasiswa->nama }}</p>
                                    <p class="text-xs text-gray-400 font-mono">{{ $mahasiswa->nim }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-xs font-semibold text-gray-600 px-3 py-1 bg-gray-100 rounded-full border border-gray-200 uppercase tracking-tighter">
                                {{ $mahasiswa->prodi_full }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-black border border-indigo-100">
                                {{ $mahasiswa->nilai_akhir }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-xs text-gray-400 font-medium">
                            {{ \Carbon\Carbon::parse($mahasiswa->created_at)->translatedFormat('d/m/y H:i') }}
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
            "pageLength": 10,
            "language": {
                "search": "",
                "searchPlaceholder": "Cari peserta...",
                "lengthMenu": "_MENU_ per halaman"
            },
            "dom": '<"flex flex-col sm:flex-row sm:items-center sm:justify-between px-6 py-4 gap-4"lf>rt<"flex flex-col sm:flex-row sm:items-center sm:justify-between px-6 py-4 gap-4"ip>'
        });
    });
</script>
@endsection
