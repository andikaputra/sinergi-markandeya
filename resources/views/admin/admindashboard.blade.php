@extends('layouts.admin')
@section('title', 'Beranda Admin')
@section('content')
<div class="space-y-8">

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-700 text-sm font-bold flex items-center gap-2">
        <i class="fas fa-check-circle"></i>{{ session('success') }}
    </div>
    @endif

    {{-- ═══ ROW 1: Stat Peserta ═══ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['KKN',    $jumlahKKN,    'fa-hands-helping',      'blue',   route('admin.peserta.kkn')],
            ['PPL',    $jumlahPPL,    'fa-chalkboard-teacher', 'emerald',route('admin.peserta.ppl')],
            ['PKL',    $jumlahPKL,    'fa-building',           'amber',  route('admin.peserta.pkl')],
            ['Magang', $jumlahMagang, 'fa-briefcase',          'indigo', route('admin.peserta.magang')],
        ] as [$label, $count, $icon, $color, $href])
        <a href="{{ $href }}" class="bg-white p-6 rounded-[2rem] border border-gray-100 hover:shadow-lg hover:border-{{ $color }}-100 transition-all group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-{{ $color }}-50 rounded-full blur-2xl -mr-8 -mt-8 group-hover:bg-{{ $color }}-100 transition-all"></div>
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="bg-{{ $color }}-50 p-3 rounded-xl text-{{ $color }}-600 group-hover:bg-{{ $color }}-600 group-hover:text-white transition-all">
                    <i class="fas {{ $icon }} text-lg"></i>
                </div>
                <span class="text-[10px] font-black text-{{ $color }}-500 bg-{{ $color }}-50 px-2 py-1 rounded-full uppercase tracking-wider">{{ $label }}</span>
            </div>
            <div class="relative z-10">
                <h3 class="text-3xl font-black text-gray-800 tracking-tight">{{ $count }}</h3>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Peserta</p>
            </div>
        </a>
        @endforeach
    </div>

    {{-- ═══ ROW 2: Alert Cards ═══ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        {{-- Pending Verifikasi --}}
        <a href="{{ route('admin.mahasiswa.pending') }}"
            class="flex items-center gap-4 p-5 bg-white rounded-2xl border {{ $jumlahPending > 0 ? 'border-amber-200 bg-amber-50/30' : 'border-gray-100' }} hover:shadow-md transition-all group">
            <div class="w-12 h-12 {{ $jumlahPending > 0 ? 'bg-amber-100 text-amber-600' : 'bg-gray-100 text-gray-400' }} rounded-2xl flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-2xl font-black {{ $jumlahPending > 0 ? 'text-amber-700' : 'text-gray-600' }}">{{ $jumlahPending }}</p>
                <p class="text-xs text-gray-500 font-bold">Akun Nonaktif</p>
            </div>
            @if($jumlahPending > 0)
            <span class="w-2.5 h-2.5 bg-amber-400 rounded-full animate-pulse shrink-0"></span>
            @endif
        </a>

        {{-- Pengumuman Aktif --}}
        <a href="{{ route('pengumuman.index') }}"
            class="flex items-center gap-4 p-5 bg-white rounded-2xl border border-gray-100 hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fas fa-bullhorn"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-2xl font-black text-gray-700">{{ $jumlahPengumuman }}</p>
                <p class="text-xs text-gray-500 font-bold">Pengumuman Aktif</p>
            </div>
            <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
        </a>

        {{-- TA Aktif --}}
        <a href="{{ route('tahun_akademik.index') }}"
            class="flex items-center gap-4 p-5 bg-white rounded-2xl border border-gray-100 hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="flex-1 min-w-0">
                @if($activeTA)
                <p class="text-sm font-black text-gray-800 leading-tight">{{ $activeTA->tahun }}</p>
                <p class="text-xs text-gray-500 font-bold">{{ $activeTA->semester }}
                    @if($activeTA->isPendaftaranOpen())
                    <span class="text-emerald-500 ml-1"><i class="fas fa-circle text-[6px]"></i> Buka</span>
                    @endif
                </p>
                @else
                <p class="text-sm font-bold text-gray-400">Belum diatur</p>
                @endif
            </div>
            <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
        </a>
    </div>

    {{-- ═══ ROW 3: Belum Penempatan ═══ --}}
    <div class="bg-white rounded-[2rem] border border-gray-100 p-6">
        <h4 class="text-sm font-black text-gray-600 uppercase tracking-widest mb-4 flex items-center gap-2">
            <i class="fas fa-map-marker-alt text-red-400"></i>
            Belum Mendapat Penempatan Lokasi
        </h4>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach([
                ['KKN',    $belumPenempatanKKN,    'blue',   route('admin.peserta.kkn')],
                ['PPL',    $belumPenempatanPPL,    'emerald',route('admin.peserta.ppl')],
                ['PKL',    $belumPenempatanPKL,    'amber',  route('admin.peserta.pkl')],
                ['Magang', $belumPenempatanMagang, 'indigo', route('admin.peserta.magang')],
            ] as [$label, $count, $color, $href])
            <a href="{{ $href }}" class="p-4 rounded-2xl border {{ $count > 0 ? 'border-red-100 bg-red-50/30 hover:bg-red-50' : 'border-gray-100 bg-gray-50/50' }} transition-all group text-center">
                <p class="text-2xl font-black {{ $count > 0 ? 'text-red-600' : 'text-gray-400' }}">{{ $count }}</p>
                <p class="text-xs font-bold text-gray-500 mt-1">{{ $label }}</p>
                @if($count > 0)
                <p class="text-[10px] text-red-400 mt-1">Perlu ditentukan</p>
                @else
                <p class="text-[10px] text-emerald-500 mt-1">Semua sudah</p>
                @endif
            </a>
            @endforeach
        </div>
    </div>

    {{-- ═══ ROW 4: Dua Kolom ═══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Akun Pending Terbaru --}}
        <div class="bg-white rounded-[2rem] border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-50 flex items-center justify-between">
                <h4 class="font-black text-gray-800 flex items-center gap-2">
                    <i class="fas fa-user-cog text-slate-500"></i>
                    Akun Nonaktif
                </h4>
                <a href="{{ route('admin.mahasiswa.pending') }}" class="text-xs text-blue-500 font-bold hover:underline">Lihat Semua</a>
            </div>
            @if($pendingTerbaru->isEmpty())
            <div class="p-8 text-center">
                <i class="fas fa-check-double text-3xl text-emerald-300 mb-2 block"></i>
                <p class="text-sm text-gray-400">Tidak ada yang menunggu</p>
            </div>
            @else
            <div class="divide-y divide-gray-50">
                @foreach($pendingTerbaru as $p)
                <div class="px-6 py-4 flex items-center gap-3 hover:bg-amber-50/30 transition-colors">
                    <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600 font-black text-sm shrink-0">
                        {{ substr($p->nama, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-800 text-sm truncate">{{ $p->nama }}</p>
                        <p class="text-xs text-gray-400">{{ $p->nim }} — {{ $p->prodi }}</p>
                    </div>
                    <p class="text-[10px] text-gray-400 shrink-0">{{ $p->created_at->diffForHumans() }}</p>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Pendaftaran Kegiatan Terbaru --}}
        <div class="bg-white rounded-[2rem] border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-50">
                <h4 class="font-black text-gray-800 flex items-center gap-2">
                    <i class="fas fa-clipboard-check text-blue-500"></i>
                    Pendaftaran Kegiatan Terbaru
                </h4>
            </div>
            @if($pendaftaranTerbaru->isEmpty())
            <div class="p-8 text-center">
                <p class="text-sm text-gray-400">Belum ada pendaftaran</p>
            </div>
            @else
            <div class="divide-y divide-gray-50">
                @foreach($pendaftaranTerbaru as $dk)
                @php
                    $colors = ['KKN'=>'blue','PPL'=>'emerald','PKL'=>'amber','Magang'=>'indigo'];
                    $c = $colors[$dk->kegiatan] ?? 'gray';
                @endphp
                <div class="px-6 py-3.5 flex items-center gap-3 hover:bg-slate-50/50 transition-colors">
                    <div class="w-8 h-8 bg-{{ $c }}-100 text-{{ $c }}-600 rounded-xl flex items-center justify-center text-xs font-black shrink-0">
                        {{ substr($dk->kegiatan, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-800 text-sm truncate">{{ $dk->mahasiswa?->nama ?? $dk->nim }}</p>
                        <p class="text-xs text-gray-400">{{ $dk->kegiatan }} — {{ $dk->tahun_akademik }}</p>
                    </div>
                    <span class="px-2 py-0.5 bg-{{ $c }}-50 text-{{ $c }}-600 rounded-full text-[10px] font-bold shrink-0">{{ $dk->status_kegiatan }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- ═══ ROW 5: Chart + Quick Actions ═══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] border border-gray-100">
            <h4 class="text-base font-black text-gray-800 mb-6">Distribusi Mahasiswa per Kegiatan</h4>
            <div class="h-[260px]"><canvas id="programChart"></canvas></div>
        </div>

        <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 space-y-3">
            <h4 class="font-black text-gray-800 mb-2">Akses Cepat</h4>
            @foreach([
                [route('tahun_akademik.index'), 'fa-calendar-check', 'blue',   'Tahun Akademik'],
                [route('pengumuman.index'),     'fa-bullhorn',       'purple', 'Pengumuman'],
                [route('admin.mahasiswa.pending'), 'fa-user-check',  'amber',  'Verifikasi Akun'],
                [route('admin.mahasiswa.create'), 'fa-user-plus',    'emerald','Tambah Mahasiswa'],
                [route('dosen.create'),          'fa-user-tie',      'indigo', 'Tambah Dosen'],
            ] as [$href, $icon, $color, $label])
            <a href="{{ $href }}" class="flex items-center gap-3 p-3.5 bg-slate-50 rounded-2xl hover:bg-{{ $color }}-50 transition-colors group">
                <div class="w-9 h-9 bg-white rounded-xl flex items-center justify-center text-{{ $color }}-500 shadow-sm shrink-0">
                    <i class="fas {{ $icon }} text-sm"></i>
                </div>
                <span class="text-sm font-bold text-gray-600 group-hover:text-{{ $color }}-700">{{ $label }}</span>
                <i class="fas fa-chevron-right text-gray-300 text-xs ml-auto"></i>
            </a>
            @endforeach
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('programChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['KKN', 'PPL', 'PKL', 'Magang'],
            datasets: [{
                data: [{{ $jumlahKKN }}, {{ $jumlahPPL }}, {{ $jumlahPKL }}, {{ $jumlahMagang }}],
                backgroundColor: ['#2563eb', '#10b981', '#f59e0b', '#6366f1'],
                borderWidth: 0, hoverOffset: 16
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 24, font: { weight: 'bold', size: 12 } } }
            },
            cutout: '68%'
        }
    });
});
</script>
@endsection
