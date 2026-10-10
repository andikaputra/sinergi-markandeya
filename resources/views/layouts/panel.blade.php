<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#184c3d">
    <title>@yield('title', 'Dashboard') — Sinergi Markandeya</title>
    <link rel="icon" href="{{ asset('logo-universitas-markandeya.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    @stack('styles')
</head>
<body class="panel-shell">
    @php
        $panelGuard = trim($__env->yieldContent('panel_guard', 'web'));
        $displayUser = Auth::guard($panelGuard)->user();
        $panelName = trim($__env->yieldContent('user_type', 'Admin'));
        $homeRoute = match ($panelGuard) {
            'dosen' => 'dosen.dashboard',
            'mahasiswa' => 'dashboard',
            'pembimbing_luar' => 'pembimbing_luar.dashboard',
            default => 'admindashboard',
        };
    @endphp
    <a class="panel-skip" href="#panel-main">Langsung ke konten</a>
    <aside id="sidebar" class="panel-sidebar" aria-label="Navigasi {{ $panelName }}">
        <a href="{{ route($homeRoute) }}" class="panel-brand">
            <img src="{{ asset('logo-universitas-markandeya.png') }}" width="42" height="42" alt="Logo Universitas Markandeya">
            <span><strong>Sinergi<span>.</span></strong><small>UNIVERSITAS MARKANDEYA</small></span>
        </a>
        <div class="panel-workspace"><span class="panel-workspace-icon"><i class="fas {{ $panelGuard === 'dosen' ? 'fa-chalkboard-teacher' : 'fa-layer-group' }}" aria-hidden="true"></i></span><span>Ruang kerja<strong>{{ $panelName }}</strong></span><span class="panel-workspace-dot" aria-hidden="true"></span></div>
        <nav class="panel-nav" aria-label="Menu {{ $panelName }}">
            @if($panelGuard === 'dosen')
                @include('layouts.dosen_menu')
            
@elseif($panelGuard === 'mahasiswa')
                @include('layouts.adminmhs_menu')
            
@elseif($panelGuard === 'pembimbing_luar')
                @include('layouts.pembimbing_luar_menu')
            
@else

                @include('layouts.admin_menu')
            
@endif

        </nav>
        <div class="panel-account">
            <div class="panel-account-info"><div class="panel-avatar"><x-avatar :foto="$displayUser?->foto" :nama="$displayUser?->nama ?? $displayUser?->name ?? 'Pengguna'" /></div><div><strong>{{ $displayUser?->nama ?? $displayUser?->name ?? 'Pengguna' }}</strong><span>{{ $panelGuard === 'web' && $displayUser?->isSuperAdmin() ? 'Super Admin' : $panelName }}</span></div></div>
            <form method="POST" action="@yield('logout_route')">@csrf<button type="submit" class="panel-logout"><i class="fas fa-sign-out-alt" aria-hidden="true"></i> Keluar Aplikasi</button></form>
        </div>
    </aside>
    <button type="button" id="sidebar-overlay" class="panel-overlay" aria-label="Tutup menu navigasi" hidden></button>
    <div class="panel-body">
        <header class="panel-topbar">
            <div class="panel-topbar-leading"><button type="button" id="mobile-menu-button" class="panel-menu-toggle" aria-label="Buka menu navigasi" aria-controls="sidebar" aria-expanded="false" hidden><i class="fas fa-bars" aria-hidden="true"></i></button><div><p class="panel-breadcrumb"><span>Ruang kerja</span><i class="fas fa-chevron-right" aria-hidden="true"></i>{{ $panelName }}</p><h1>@yield('title', 'Dashboard')</h1></div></div>
            <div class="panel-topbar-actions"><button type="button" class="panel-site-link" onclick="window.print()" title="Mencetak halaman yang sedang ditampilkan"><i class="fas fa-print" aria-hidden="true"></i><span>Cetak halaman</span></button>@if($panelGuard === 'mahasiswa')<a href="{{ route('mahasiswa.notifikasi') }}" class="panel-site-link"><i class="far fa-bell" aria-hidden="true"></i><span>Notifikasi</span></a>
@endif
<time datetime="{{ now()->toDateString() }}"><i class="far fa-calendar-alt" aria-hidden="true"></i>{{ now()->translatedFormat('d M Y') }}</time><a href="{{ url('/') }}" class="panel-site-link" aria-label="Lihat halaman utama"><i class="fas fa-external-link-alt" aria-hidden="true"></i><span>Lihat website</span></a></div>
        </header>
        <main id="panel-main" class="panel-content" tabindex="-1">
            <div class="panel-print-heading"><strong>UNIVERSITAS MARKANDEYA</strong><h2>@yield('title')</h2><p>Dicetak {{ now()->translatedFormat('d F Y H:i') }}</p>@foreach(['ta'=>'Tahun akademik', 'tahun_akademik'=>'Tahun akademik', 'kegiatan'=>'Kegiatan', 'status'=>'Status'] as $key=>$label)@if(request()->filled($key))<span>{{ $label }}: {{ request($key) }} · </span>
@endif

@endforeach
</div>
            @yield('content')
        </main>
        <footer class="panel-footer"><span>© {{ date('Y') }} Universitas Markandeya</span><span>Belajar. Berkarya. Berdampak.</span></footer>
    </div>
    @stack('scripts')
</body>
</html>
