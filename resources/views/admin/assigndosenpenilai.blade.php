@extends('layouts.admin')

@section('title', 'Plotting Dosen Penilai Publikasi')

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
        <i class="fas fa-exclamation-triangle mr-3 text-lg"></i>{{ session('error') }}
    </div>
    @endif

    @include('admin._import_plotting', [
        'importRoute' => route('assign.dosenpenilai.import'),
        'label' => 'Plotting Penilai Publikasi',
        'formatInfo' => 'nim, nidn',
        'color' => 'purple',
    ])

    <!-- Filter Kegiatan -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
        <form method="GET" action="{{ route('assign.dosenpenilai') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Filter Kegiatan</label>
                <select name="kegiatan" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-600">
                    <option value="">Semua Kegiatan</option>
                    @foreach($allowedKegiatan as $k)
                        <option value="{{ $k }}" {{ $selectedKegiatan == $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl text-sm transition-colors shadow-lg shadow-purple-200">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('assign.dosenpenilai') }}" class="px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold rounded-xl text-sm transition-colors">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Assignment Form Card -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('assign.dosenpenilai.store') }}" method="POST" class="p-8">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Student Selection -->
                <div class="lg:col-span-7 space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">
                        Mahasiswa (Belum Ada Penilai Publikasi)
                        <span class="text-purple-500 ml-1">{{ $mahasiswas->count() }} orang</span>
                    </label>
                    <div class="bg-slate-50 rounded-3xl border border-gray-100 overflow-hidden">
                        @if($mahasiswas->isNotEmpty())
                        <div class="max-h-[400px] overflow-y-auto sidebar-scroll p-4 space-y-2">
                            @foreach($mahasiswas as $mahasiswa)
                                <label class="flex items-center p-4 bg-white border border-gray-100 rounded-2xl cursor-pointer hover:border-purple-300 hover:bg-purple-50 transition-all group">
                                    <div class="relative flex items-center justify-center">
                                        <input type="checkbox" name="nims[]" value="{{ $mahasiswa->nim }}" class="w-5 h-5 text-purple-600 border-gray-300 rounded-lg focus:ring-purple-500">
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <p class="text-sm font-bold text-gray-800 group-hover:text-purple-700 transition-colors">{{ $mahasiswa->nama }}</p>
                                        <div class="flex items-center space-x-2 mt-0.5">
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $mahasiswa->nim }}</span>
                                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                            <span class="text-[10px] font-bold text-purple-500 uppercase tracking-tighter">{{ $mahasiswa->kegiatan }}</span>
                                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $mahasiswa->prodi }}</span>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @else
                        <div class="p-8 text-center">
                            <i class="fas fa-check-circle text-emerald-300 text-3xl mb-3"></i>
                            <p class="text-sm text-gray-500 font-medium">Semua mahasiswa sudah memiliki dosen penilai publikasi.</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Dosen Selection & Submit -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Pilih Dosen Penilai Publikasi</label>
                        <div class="relative">
                            <select name="nidn" required class="w-full pl-5 pr-10 py-4 bg-slate-50 border border-gray-100 rounded-2xl text-gray-700 font-bold focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 appearance-none transition-all">
                                <option value="">-- Pilih Nama Dosen --</option>
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->nidn }}">{{ $dosen->nama }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                                <i class="fas fa-book-reader text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-5 bg-purple-600 hover:bg-purple-700 text-white font-black rounded-2xl shadow-xl shadow-purple-100 transition-all flex items-center justify-center space-x-3 group">
                            <i class="fas fa-book-reader group-hover:scale-110 transition-transform"></i>
                            <span class="uppercase tracking-widest text-xs">Simpan Plotting Penilai</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Assignments List -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 flex items-center justify-between border-b border-gray-50">
            <span class="text-xs font-black uppercase tracking-widest text-gray-400">Daftar Mahasiswa & Penilai Publikasi</span>
            <span class="px-3 py-1 bg-purple-50 text-purple-600 rounded-full text-xs font-bold border border-purple-100">{{ $assignments->count() }} plotting</span>
        </div>
        <div class="overflow-x-auto p-8">
            <table class="w-full text-left border-separate border-spacing-0" id="assignmentsTable">
                <thead>
                    <tr>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 rounded-tl-2xl">Mahasiswa</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100">Kegiatan</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100">Dosen Penilai</th>
                        <th class="px-6 py-4 bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-100 text-right rounded-tr-2xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($assignments as $assignment)
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs">
                                    {{ substr($assignment->mahasiswa->nama, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $assignment->mahasiswa->nama }}</p>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $assignment->nim }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                {{ $assignment->mahasiswa->kegiatan }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center space-x-2 text-purple-600">
                                <i class="fas fa-book-reader text-xs"></i>
                                <span class="text-sm font-bold">{{ $assignment->dosen->nama }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <form action="{{ route('assign.dosenpenilai.delete', $assignment->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-100" onclick="return confirm('Hapus plotting ini?')">
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
        $('#assignmentsTable').DataTable({
            "language": { "search": "", "searchPlaceholder": "Cari data..." }
        });
    });
</script>
@endsection
