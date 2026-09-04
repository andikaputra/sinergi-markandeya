@extends('layouts.main')

@section('title', 'Plotting Dosen Monev')

@section('user_type', 'Admin')

@section('logout_route', route('logoutadmin'))

@section('content')
<div class="space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    <!-- Header Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-8 sm:p-10 shadow-xl border border-slate-800">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-400/30 text-xs font-semibold uppercase tracking-wider">
                    <i class="fas fa-user-check"></i> Plotting & Penugasan
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">Plotting Dosen Pemonev</h1>
                <p class="text-slate-300 text-sm max-w-2xl">
                    Tugaskan dosen sebagai evaluator/pemonev program kerja mahasiswa peserta kegiatan {{ strtoupper($kegiatan) }} (Individu maupun Kelompok/Lokasi).
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admindashboard') }}" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white text-sm font-bold rounded-xl border border-white/20 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i> Dashboard Admin
                </a>
            </div>
        </div>
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    @if ($message = Session::get('success'))
        <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl shadow-sm">
            <i class="fas fa-check-circle text-emerald-600 text-lg"></i>
            <span class="font-semibold text-sm">{{ $message }}</span>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-800 px-6 py-4 rounded-2xl shadow-sm">
            <i class="fas fa-exclamation-circle text-rose-600 text-lg"></i>
            <span class="font-semibold text-sm">{{ $message }}</span>
        </div>
    @endif

    <!-- Filter Tabs -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <!-- Kegiatan Tabs -->
        <div class="flex flex-wrap gap-2">
            @foreach (['kkn' => 'KKN', 'ppl' => 'PPL', 'pkl' => 'PKL', 'magang' => 'Magang'] as $kegKey => $kegLabel)
                <a href="?kegiatan={{ $kegKey }}&type={{ $type }}" class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all {{ $kegiatan === $kegKey ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    {{ $kegLabel }}
                </a>
            @endforeach
        </div>

        <!-- Type Selector (Individu vs Kelompok) -->
        <div class="inline-flex p-1 bg-gray-100 rounded-xl">
            <a href="?kegiatan={{ $kegiatan }}&type=individu" class="px-5 py-2 rounded-lg text-xs font-bold transition-all {{ $type === 'individu' ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-500 hover:text-gray-800' }}">
                <i class="fas fa-user mr-1.5"></i> Mahasiswa (Individu)
            </a>
            <a href="?kegiatan={{ $kegiatan }}&type=kelompok" class="px-5 py-2 rounded-lg text-xs font-bold transition-all {{ $type === 'kelompok' ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-500 hover:text-gray-800' }}">
                <i class="fas fa-users mr-1.5"></i> Kelompok / Lokasi
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Main Form & Selection List (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                    <div>
                        <h2 class="text-xl font-black text-gray-900">
                            {{ $type === 'individu' ? 'Daftar Mahasiswa Peserta ' . strtoupper($kegiatan) : 'Daftar Kelompok / Lokasi ' . strtoupper($kegiatan) }}
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ $type === 'individu' ? 'Pilih mahasiswa dan tentukan dosen pemonev' : 'Pilih kelompok/lokasi dan tentukan dosen pemonev' }}
                        </p>
                    </div>
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-full border border-indigo-100">
                        {{ $type === 'individu' ? $mahasiswas->count() . ' Mahasiswa' : $lokasis->count() . ' Kelompok/Lokasi' }}
                    </span>
                </div>

                @if ($type === 'individu')
                    <!-- FORM PLOTTING INDIVIDU -->
                    @if ($mahasiswas->count() > 0)
                        <form id="assignForm" action="{{ route('admin.dosen-monev.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="monev_type" value="individu">
                            <input type="hidden" name="kegiatan" value="{{ $kegiatan }}">

                            <!-- Dosen Selector -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Pilih Dosen Pemonev <span class="text-red-500">*</span>
                                </label>
                                <select name="nidn" id="dosenSelect" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                    <option value="">-- Pilih Nama Dosen --</option>
                                    @foreach ($dosens as $dosen)
                                        <option value="{{ $dosen->nidn }}">{{ $dosen->nama }} (NIDN: {{ $dosen->nidn }})</option>
                                    @endforeach
                                </select>
                                @error('nidn')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Checkbox Mahasiswa List -->
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Pilih Mahasiswa yang Ditugaskan <span class="text-red-500">*</span>
                                    </label>
                                    <button type="button" id="selectAllBtn" class="text-xs font-bold text-indigo-600 hover:underline">
                                        Pilih Semua
                                    </button>
                                </div>

                                <div class="space-y-2 max-h-[420px] overflow-y-auto pr-1">
                                    @foreach ($mahasiswas as $mhs)
                                        @php
                                            $isAssigned = in_array($mhs->nim, $existingAssignments);
                                            $assignedRecord = $assignments->where('nim', $mhs->nim)->first();
                                            $proker = $prokers->get($mhs->nim);
                                            $lokasiName = match($kegiatan) {
                                                'kkn' => $mhs->penempatankkn?->lokasikkn?->desa ?? $mhs->penempatankkn?->lokasikkn?->nama_kelompok,
                                                'ppl' => $mhs->penempatanppl?->lokasippl?->nama_sekolah,
                                                'pkl' => $mhs->penempatanpkl?->lokasipkl?->nama_instansi,
                                                'magang' => $mhs->penempatanmagang?->lokasimagang?->nama_instansi,
                                                default => null,
                                            };
                                        @endphp
                                        <label class="flex items-start gap-3 p-3.5 bg-gray-50 hover:bg-indigo-50/40 border border-gray-100 hover:border-indigo-200 rounded-2xl cursor-pointer transition">
                                            <input type="checkbox" name="nims[]" value="{{ $mhs->nim }}" class="item-checkbox mt-1 w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-start justify-between gap-2">
                                                    <div>
                                                        <p class="font-bold text-gray-900 text-sm leading-tight">{{ $mhs->nama }}</p>
                                                        <p class="text-xs text-gray-500 mt-0.5 font-mono">NIM: {{ $mhs->nim }} • {{ $mhs->prodi ?? '-' }}</p>
                                                    </div>
                                                    @if ($isAssigned && $assignedRecord?->dosen)
                                                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold rounded-md whitespace-nowrap">
                                                            <i class="fas fa-user-check mr-1"></i> {{ $assignedRecord->dosen->nama }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="mt-2 flex flex-wrap items-center gap-2 text-[11px]">
                                                    @if ($lokasiName)
                                                        <span class="px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-100 rounded-md">
                                                            <i class="fas fa-map-marker-alt text-rose-500 mr-1"></i> {{ $lokasiName }}
                                                        </span>
                                                    @endif

                                                    @if ($proker)
                                                        <span class="px-2 py-0.5 bg-purple-50 text-purple-700 border border-purple-100 rounded-md truncate max-w-xs" title="{{ $proker->judul }}">
                                                            <i class="fas fa-tasks mr-1"></i> {{ $proker->judul }}
                                                        </span>
                                                    @else
                                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-500 rounded-md">
                                                            <i class="far fa-file mr-1"></i> Belum ada judul proker
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('nims')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-600/20 transition flex items-center justify-center gap-2">
                                <i class="fas fa-check"></i>
                                <span>Tugaskan Dosen Pemonev</span>
                            </button>
                        </form>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                            <i class="fas fa-users-slash text-gray-300 text-4xl mb-3 block"></i>
                            <p class="text-xs font-bold text-gray-500">Belum ada mahasiswa terdaftar untuk kegiatan {{ strtoupper($kegiatan) }}.</p>
                            <p class="text-[11px] text-gray-400 mt-1">Pastikan data peserta kegiatan sudah diisi di menu Manajemen Peserta.</p>
                        </div>
                    @endif
                @else
                    <!-- FORM PLOTTING KELOMPOK / LOKASI -->
                    @if ($lokasis->count() > 0)
                        <form id="assignForm" action="{{ route('admin.dosen-monev.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="monev_type" value="kelompok">
                            <input type="hidden" name="kegiatan" value="{{ $kegiatan }}">

                            <!-- Dosen Selector -->
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    Pilih Dosen Pemonev <span class="text-red-500">*</span>
                                </label>
                                <select name="nidn" id="dosenSelectKelompok" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                    <option value="">-- Pilih Nama Dosen --</option>
                                    @foreach ($dosens as $dosen)
                                        <option value="{{ $dosen->nidn }}">{{ $dosen->nama }} (NIDN: {{ $dosen->nidn }})</option>
                                    @endforeach
                                </select>
                                @error('nidn')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Checkbox Kelompok List -->
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Pilih Kelompok / Lokasi yang Ditugaskan <span class="text-red-500">*</span>
                                    </label>
                                    <button type="button" id="selectAllBtn" class="text-xs font-bold text-indigo-600 hover:underline">
                                        Pilih Semua
                                    </button>
                                </div>

                                <div class="space-y-2 max-h-[420px] overflow-y-auto pr-1">
                                    @foreach ($lokasis as $lok)
                                        @php
                                            $isAssigned = in_array($lok->id, $existingAssignments);
                                            $assignedRecord = $assignments->where('lokasi_id', $lok->id)->first();
                                            $name = match($kegiatan) {
                                                'kkn' => ($lok->desa ?? 'Desa') . ' - ' . ($lok->nama_kelompok ?? 'Kelompok ' . $lok->id),
                                                'ppl' => $lok->nama_sekolah,
                                                'pkl' => $lok->nama_instansi,
                                                'magang' => $lok->nama_instansi,
                                                default => 'Lokasi #' . $lok->id,
                                            };
                                            $memberCount = match($kegiatan) {
                                                'kkn' => $lok->penempatankkn?->count() ?? 0,
                                                'ppl' => $lok->penempatanppl?->count() ?? 0,
                                                'pkl' => $lok->penempatanpkl?->count() ?? 0,
                                                'magang' => $lok->penempatanmagang?->count() ?? 0,
                                                default => 0,
                                            };
                                        @endphp
                                        <label class="flex items-start gap-3 p-3.5 bg-gray-50 hover:bg-indigo-50/40 border border-gray-100 hover:border-indigo-200 rounded-2xl cursor-pointer transition">
                                            <input type="checkbox" name="lokasi_ids[]" value="{{ $lok->id }}" class="item-checkbox mt-1 w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-start justify-between gap-2">
                                                    <div>
                                                        <p class="font-bold text-gray-900 text-sm leading-tight">{{ $name }}</p>
                                                        <p class="text-xs text-gray-500 mt-0.5">
                                                            Kecamatan: {{ $lok->kecamatan ?? '-' }} • Kabupaten: {{ $lok->kabupaten ?? '-' }}
                                                        </p>
                                                    </div>
                                                    @if ($isAssigned && $assignedRecord?->dosen)
                                                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold rounded-md whitespace-nowrap">
                                                            <i class="fas fa-user-check mr-1"></i> {{ $assignedRecord->dosen->nama }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="mt-2 flex items-center gap-2 text-[11px]">
                                                    <span class="px-2 py-0.5 bg-purple-50 text-purple-700 border border-purple-100 rounded-md">
                                                        <i class="fas fa-users mr-1"></i> {{ $memberCount }} Mahasiswa Ditempatkan
                                                    </span>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('lokasi_ids')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-600/20 transition flex items-center justify-center gap-2">
                                <i class="fas fa-check"></i>
                                <span>Tugaskan Dosen Pemonev Kelompok</span>
                            </button>
                        </form>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                            <i class="fas fa-map-marked-alt text-gray-300 text-4xl mb-3 block"></i>
                            <p class="text-xs font-bold text-gray-500">Belum ada lokasi/kelompok terdaftar untuk kegiatan {{ strtoupper($kegiatan) }}.</p>
                            <p class="text-[11px] text-gray-400 mt-1">Tambahkan data lokasi terlebih dahulu pada menu Master Lokasi.</p>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Existing Assignments & Import Sidebar (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            <!-- Active Assignments Card -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                    <div>
                        <h2 class="text-lg font-black text-gray-900">Penugasan Saat Ini</h2>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $assignments->count() }} penugasan aktif ({{ ucfirst($type) }} - {{ strtoupper($kegiatan) }})</p>
                    </div>
                    <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>

                @if ($assignments->count() > 0)
                    <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                        @foreach ($assignments as $assignment)
                            @php
                                if ($type === 'individu') {
                                    $targetName = $assignment->mahasiswa?->nama ?? ('NIM: ' . $assignment->nim);
                                    $targetSub = 'NIM: ' . $assignment->nim . ($assignment->mahasiswa?->prodi ? ' • ' . $assignment->mahasiswa->prodi : '');
                                } else {
                                    $lok = $assignment->lokasiKkn ?: ($assignment->lokasiPpl ?: ($assignment->lokasiPkl ?: $assignment->lokasiMagang));
                                    $targetName = $lok?->desa ?? $lok?->nama_sekolah ?? $lok?->nama_instansi ?? ('Lokasi #' . $assignment->lokasi_id);
                                    $targetSub = ($lok?->kecamatan ? 'Kec. ' . $lok->kecamatan : '') . ($lok?->kabupaten ? ', ' . $lok->kabupaten : '');
                                }
                                $fotoCount = !empty($assignment->foto_monev) ? count($assignment->foto_monev) : 0;
                            @endphp
                            <div class="p-3.5 bg-gray-50 border border-gray-100 rounded-2xl flex items-start justify-between gap-3 text-xs">
                                <div class="min-w-0 flex-1 space-y-1">
                                    <p class="font-bold text-gray-900 truncate">{{ $targetName }}</p>
                                    <p class="text-[11px] text-gray-400 truncate">{{ $targetSub }}</p>
                                    <p class="text-gray-600 pt-0.5">
                                        Dosen: <strong class="text-indigo-700">{{ $assignment->dosen->nama ?? '-' }}</strong>
                                    </p>
                                    <div class="flex items-center gap-2 pt-1">
                                        @if (!is_null($assignment->nilai))
                                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 font-bold rounded-md text-[10px]">
                                                Nilai: {{ $assignment->nilai }}
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 bg-gray-200 text-gray-600 font-semibold rounded-md text-[10px]">
                                                Belum dinilai
                                            </span>
                                        @endif

                                        @if ($fotoCount > 0)
                                            <span class="px-2 py-0.5 bg-blue-100 text-blue-800 font-bold rounded-md text-[10px]">
                                                <i class="fas fa-camera"></i> {{ $fotoCount }} Foto
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <form action="{{ route('admin.dosen-monev.delete', $assignment->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penugasan dosen monev ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition flex items-center justify-center text-xs" title="Hapus Penugasan">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-400 text-center py-6">Belum ada penugasan dosen monev untuk kategori ini.</p>
                @endif
            </div>

            <!-- Import CSV Card -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-file-csv text-amber-500"></i>
                        <span>Import Penugasan CSV</span>
                    </h3>
                </div>

                <form action="{{ route('admin.dosen-monev.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="monev_type" value="{{ $type }}">
                    <input type="hidden" name="kegiatan" value="{{ $kegiatan }}">

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">
                            File CSV (Format: {{ $type === 'individu' ? 'NIM, NIDN' : 'ID_LOKASI, NIDN' }})
                        </label>
                        <input type="file" name="file" accept=".csv,.txt" required class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>

                    <button type="submit" class="w-full px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center justify-center gap-2">
                        <i class="fas fa-upload"></i>
                        <span>Upload & Import Penugasan</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllBtn = document.getElementById('selectAllBtn');
        if (selectAllBtn) {
            let allSelected = false;
            selectAllBtn.addEventListener('click', function() {
                const checkboxes = document.querySelectorAll('.item-checkbox');
                allSelected = !allSelected;
                checkboxes.forEach(cb => cb.checked = allSelected);
                selectAllBtn.textContent = allSelected ? 'Batal Pilih Semua' : 'Pilih Semua';
            });
        }
    });
</script>
@endsection
