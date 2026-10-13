@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('content')
@php
    $admin = Auth::guard('web')->user();
    $programs = collect([
        ['name' => 'KKN', 'count' => $jumlahKKN, 'unplaced' => $belumPenempatanKKN, 'color' => '#688c64', 'tone' => 'sage', 'icon' => 'fa-hands-helping', 'href' => route('admin.peserta.kkn'), 'assign' => route('assign.lokasikkn')],
        ['name' => 'PPL', 'count' => $jumlahPPL, 'unplaced' => $belumPenempatanPPL, 'color' => '#b2975f', 'tone' => 'gold', 'icon' => 'fa-school', 'href' => route('admin.peserta.ppl'), 'assign' => route('assign.lokasippl')],
        ['name' => 'PKL', 'count' => $jumlahPKL, 'unplaced' => $belumPenempatanPKL, 'color' => '#467c70', 'tone' => 'green', 'icon' => 'fa-building', 'href' => route('admin.peserta.pkl'), 'assign' => route('assign.lokasipkl')],
        ['name' => 'Magang', 'count' => $jumlahMagang, 'unplaced' => $belumPenempatanMagang, 'color' => '#8b84a0', 'tone' => 'lavender', 'icon' => 'fa-briefcase', 'href' => route('admin.peserta.magang'), 'assign' => route('assign.lokasimagang')],
    ])->filter(fn ($program) => $admin?->canManage($program['name']))->values();
    $totalParticipants = $programs->sum('count');
    $totalUnplaced = $programs->sum('unplaced');
@endphp
<div class="dashboard-page">
    @if(session('success'))<div class="dash-message"><i class="fas fa-check-circle" aria-hidden="true"></i>{{ session('success') }}</div>@endif
    <section class="dash-welcome">
        <div><p class="dash-eyebrow">PUSAT PENGELOLAAN KEGIATAN</p><h2>Selamat datang, {{ $admin?->name ?? 'Admin' }}</h2><p>Pantau peserta, kelola penempatan, dan pastikan kegiatan berjalan terarah.</p><div class="dash-welcome-meta"><i class="far fa-calendar-alt" aria-hidden="true"></i>{{ $activeTA ? $activeTA->tahun.' · '.$activeTA->semester : 'Tahun akademik belum diatur' }}<span class="dash-meta-divider"></span>{{ number_format($totalParticipants, 0, ',', '.') }} peserta kegiatan</div></div>
        <a href="{{ route('admin.mahasiswa.create') }}" class="dash-button dash-button-cream"><i class="fas fa-plus" aria-hidden="true"></i> Tambah Mahasiswa</a>
    </section>
    <section aria-labelledby="admin-program-title">
        <div class="dash-section-heading"><div><h2 id="admin-program-title">Ringkasan peserta</h2><p>Kegiatan aktif sesuai akses pengelolaan Anda.</p></div><span class="dash-subtle-chip">{{ $programs->count() }} program</span></div>
        <div class="dash-stats">
            @foreach($programs as $program)
            <a href="{{ $program['href'] }}" class="dash-stat dash-tone-{{ $program['tone'] }}"><div class="dash-stat-top"><span class="dash-stat-icon"><i class="fas {{ $program['icon'] }}" aria-hidden="true"></i></span><span class="dash-stat-tag">{{ $program['name'] }}</span></div><div class="dash-stat-value">{{ number_format($program['count'], 0, ',', '.') }}<i class="fas fa-arrow-up" aria-hidden="true"></i></div><p>Peserta {{ $program['name'] }}</p><div class="dash-stat-footer"><span>{{ $program['unplaced'] > 0 ? $program['unplaced'].' belum ditempatkan' : ($program['count'] > 0 ? 'Penempatan lengkap' : 'Belum ada peserta') }}</span><i class="fas fa-arrow-right" aria-hidden="true"></i></div></a>
            @endforeach
            @if($programs->isEmpty())<div class="dash-empty"><i class="fas fa-layer-group" aria-hidden="true"></i><h3>Akses kegiatan belum diatur</h3><p>Hubungi super admin untuk mengatur kegiatan yang dapat Anda kelola.</p></div>@endif
        </div>
    </section>
    <div class="dash-grid-main">
        <section class="dash-card"><div class="dash-card-heading"><div><p class="dash-eyebrow">GAMBARAN KEGIATAN</p><h2>Distribusi peserta</h2></div><span class="dash-subtle-chip">Kegiatan aktif</span></div><x-dashboard-distribution :items="$programs->all()" label="peserta" /></section>
        <section class="dash-card"><div class="dash-card-heading"><div><p class="dash-eyebrow">TINDAK LANJUT</p><h2>Perlu perhatian</h2></div><span class="dash-small-icon"><i class="far fa-flag" aria-hidden="true"></i></span></div>
            <a href="{{ route('admin.mahasiswa.pending') }}" class="dash-attention"><span class="dash-action-icon dash-tone-gold"><i class="fas fa-user-check" aria-hidden="true"></i></span><div><strong>Akun nonaktif</strong><span>Periksa status akun mahasiswa</span></div><b>{{ $jumlahPending }}</b><i class="fas fa-chevron-right" aria-hidden="true"></i></a>
            <div class="dash-attention"><span class="dash-action-icon dash-tone-sage"><i class="fas fa-map-marker-alt" aria-hidden="true"></i></span><div><strong>Menunggu penempatan</strong><span>{{ $totalUnplaced > 0 ? 'Pilih kegiatan untuk menentukan lokasi' : ($totalParticipants > 0 ? 'Seluruh peserta sudah ditempatkan' : 'Belum ada peserta kegiatan') }}</span></div><b>{{ $totalUnplaced }}</b></div>
            <div class="dash-placement-links">@foreach($programs as $program)<a href="{{ $program['assign'] }}">{{ $program['name'] }}<span>{{ $program['unplaced'] }}</span></a>@endforeach</div>
            <a href="{{ route('pengumuman.index') }}" class="dash-attention"><span class="dash-action-icon dash-tone-lavender"><i class="fas fa-bullhorn" aria-hidden="true"></i></span><div><strong>Pengumuman aktif</strong><span>Kelola informasi untuk mahasiswa</span></div><b>{{ $jumlahPengumuman }}</b><i class="fas fa-chevron-right" aria-hidden="true"></i></a>
        </section>
    </div>
    <section class="dash-card dash-monitoring"><div class="dash-card-heading"><div><p class="dash-eyebrow">PANTAU PERKEMBANGAN</p><h2>Monitoring kegiatan</h2></div><span class="dash-muted">Akses langsung ke laporan</span></div><div class="dash-monitoring-grid">
        @foreach([
            [route('admin.bimbingan.dashboard'), 'fa-comments', 'Bimbingan', 'Pantau permohonan dan tindak lanjut dosen.'],
            [route('admin.login-activity.dosen-belum-login'), 'fa-user-clock', 'Dosen Tanpa Login', 'Lihat dan cetak dosen tanpa aktivitas login tercatat.'],
            [route('admin.monev.monitoring'), 'fa-clipboard-check', 'Pelaksanaan Monev', 'Lihat dosen yang belum mencatat Monev dan tahap yang tertunda.'],
            [route('admin.program-kerja.dashboard'), 'fa-tasks', 'Program Kerja & Luaran', 'Tinjau program dan hasil kegiatan mahasiswa.'],
        ] as [$href, $icon, $title, $description])
        <a href="{{ $href }}"><span class="dash-action-icon dash-tone-green"><i class="fas {{ $icon }}" aria-hidden="true"></i></span><div><strong>{{ $title }}</strong><p>{{ $description }}</p></div><i class="fas fa-arrow-right" aria-hidden="true"></i></a>
        @endforeach
    </div></section>
    <div class="dash-grid-equal">
        <section class="dash-card"><div class="dash-card-heading"><div><h2>Pendaftaran terbaru</h2><p>Aktivitas pendaftaran kegiatan mahasiswa.</p></div><span class="dash-subtle-chip">{{ $pendaftaranTerbaru->count() }} terbaru</span></div><div class="dash-activity-list">
            @forelse($pendaftaranTerbaru as $registration)
            <div class="dash-activity"><span class="dash-initial">{{ mb_strtoupper(mb_substr($registration->mahasiswa?->nama ?? $registration->nim, 0, 1)) }}</span><div><strong>{{ $registration->mahasiswa?->nama ?? $registration->nim }}</strong><span>{{ $registration->kegiatan }} · {{ $registration->tahun_akademik ?? 'Periode belum diatur' }}</span></div><span class="dash-badge {{ $registration->status_kegiatan === 'aktif' ? 'dash-badge-green' : '' }}">{{ $registration->status_label }}</span></div>
            @empty<div class="dash-empty"><i class="far fa-clipboard" aria-hidden="true"></i><h3>Belum ada pendaftaran</h3><p>Pendaftaran kegiatan terbaru akan tampil di sini.</p></div>@endforelse
        </div></section>
        <section class="dash-card"><div class="dash-card-heading"><div><h2>Akun nonaktif terbaru</h2><p>Mahasiswa yang memerlukan peninjauan akun.</p></div><a href="{{ route('admin.mahasiswa.pending') }}" class="dash-link">Lihat semua <i class="fas fa-arrow-right" aria-hidden="true"></i></a></div><div class="dash-activity-list">
            @forelse($pendingTerbaru as $student)
            <a href="{{ route('admin.mahasiswa.edit', $student->id) }}" class="dash-activity"><span class="dash-initial dash-initial-gold">{{ mb_strtoupper(mb_substr($student->nama, 0, 1)) }}</span><div><strong>{{ $student->nama }}</strong><span>{{ $student->nim }} · {{ $student->prodi ?? 'Program studi belum diatur' }}</span></div><span class="dash-activity-time">{{ $student->created_at?->diffForHumans() }}</span></a>
            @empty<div class="dash-empty"><i class="far fa-check-circle" aria-hidden="true"></i><h3>Tidak ada akun nonaktif</h3><p>Akun nonaktif akan tampil di sini untuk ditinjau.</p></div>@endforelse
        </div></section>
    </div>
    <section class="dash-card"><div class="dash-card-heading"><div><h2>Akses cepat</h2><p>Pengelolaan yang sering Anda gunakan.</p></div></div><div class="dash-quick-grid">
        @foreach([[route('tahun_akademik.index'), 'fa-calendar-alt', 'Tahun Akademik'], [route('pengumuman.index'), 'fa-bullhorn', 'Pengumuman'], [route('admin.mahasiswa.pending'), 'fa-user-check', 'Kelola Akun'], [route('dosen.create'), 'fa-user-plus', 'Tambah Dosen']] as [$href, $icon, $label])
        <a href="{{ $href }}"><i class="fas {{ $icon }}" aria-hidden="true"></i><span>{{ $label }}</span><i class="fas fa-arrow-right" aria-hidden="true"></i></a>
        @endforeach
    </div></section>
</div>
@endsection
