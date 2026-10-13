@extends('layouts.dosen')
@section('title', 'Dashboard Dosen')
@section('content')
@php
    $assessmentProgress = $totalBimbingan > 0 ? round($sudahDinilai / $totalBimbingan * 100) : 0;
    $distribution = [
        ['name' => 'KKN', 'count' => $countKKN, 'color' => '#688c64'],
        ['name' => 'PPL', 'count' => $countPPL, 'color' => '#b2975f'],
        ['name' => 'PKL', 'count' => $countPKL, 'color' => '#467c70'],
        ['name' => 'Magang', 'count' => $countMagang, 'color' => '#8b84a0'],
    ];
@endphp
<div class="dashboard-page">
    @if(session('success'))<div class="dash-message"><i class="fas fa-check-circle" aria-hidden="true"></i>{{ session('success') }}</div>@endif
    <section class="dash-welcome"><div><p class="dash-eyebrow">RUANG PENDAMPINGAN MAHASISWA</p><h2>Selamat datang, {{ $dosen->nama }}</h2><p>Dampingi proses belajar, tinjau kegiatan, dan pantau perkembangan mahasiswa.</p><div class="dash-welcome-meta"><i class="far fa-calendar-alt" aria-hidden="true"></i>{{ $activeTA ? $activeTA->tahun.' · '.$activeTA->semester : 'Tahun akademik belum diatur' }}<span class="dash-meta-divider"></span>NIDN {{ $dosen->nidn }}</div></div><a href="{{ route('dosen.bimbingan') }}" class="dash-button dash-button-cream">Kelola Bimbingan <i class="fas fa-arrow-right" aria-hidden="true"></i></a></section>
    <section aria-labelledby="dosen-summary-title"><div class="dash-section-heading"><div><h2 id="dosen-summary-title">Ringkasan pendampingan</h2><p>{{ $activeTA ? 'Penugasan Anda pada tahun akademik aktif.' : 'Seluruh penugasan Anda. Tahun akademik aktif belum diatur.' }}</p></div><span class="dash-subtle-chip">{{ $activeTA ? 'Periode aktif' : 'Semua periode' }}</span></div><div class="dash-stats">
        @foreach([
            [$totalBimbingan, 'Mahasiswa bimbingan', 'fa-users', 'sage', route('dosen.bimbingan'), 'Lihat daftar mahasiswa'],
            [$totalUjian, 'Mahasiswa ujian', 'fa-gavel', 'lavender', route('dosen.ujian.index'), 'Kelola penilaian ujian'],
            [$sudahDinilai, 'Sudah dinilai', 'fa-check-circle', 'green', route('dosen.bimbingan'), 'Nilai bimbingan tersimpan'],
            [$belumDinilai, 'Belum dinilai', 'fa-pen', 'gold', route('dosen.bimbingan'), 'Lanjutkan penilaian'],
        ] as [$count, $title, $icon, $tone, $href, $caption])
        <a href="{{ $href }}" class="dash-stat dash-tone-{{ $tone }}"><div class="dash-stat-top"><span class="dash-stat-icon"><i class="fas {{ $icon }}" aria-hidden="true"></i></span><i class="fas fa-arrow-right dash-stat-arrow" aria-hidden="true"></i></div><div class="dash-stat-value">{{ number_format($count, 0, ',', '.') }}</div><p>{{ $title }}</p><div class="dash-stat-footer"><span>{{ $caption }}</span></div></a>
        @endforeach
    </div></section>
    <div class="dash-grid-main">
        <section class="dash-card"><div class="dash-card-heading"><div><p class="dash-eyebrow">SEBARAN BIMBINGAN</p><h2>Mahasiswa per kegiatan</h2></div><span class="dash-subtle-chip">{{ $totalBimbingan }} mahasiswa</span></div><x-dashboard-distribution :items="$distribution" /></section>
        <section class="dash-card dash-assessment"><div class="dash-card-heading"><div><p class="dash-eyebrow">PERKEMBANGAN PENILAIAN</p><h2>Progres bimbingan</h2></div><span class="dash-small-icon"><i class="fas fa-chart-line" aria-hidden="true"></i></span></div><div class="dash-progress-summary"><strong>{{ $assessmentProgress }}<span>%</span></strong><p>{{ $totalBimbingan > 0 ? 'Nilai bimbingan telah diselesaikan' : 'Belum ada mahasiswa bimbingan' }}</p></div><div class="dash-progress-track" role="progressbar" aria-label="Progres penilaian bimbingan" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $assessmentProgress }}"><span style="width: {{ $assessmentProgress }}%"></span></div><div class="dash-assessment-counts"><div><span class="dash-legend-dot" style="background: #688c64"></span><span>Sudah dinilai</span><strong>{{ $sudahDinilai }}</strong></div><div><span class="dash-legend-dot" style="background: #b2975f"></span><span>Belum dinilai</span><strong>{{ $belumDinilai }}</strong></div></div><a href="{{ route('dosen.bimbingan') }}" class="dash-assessment-link">{{ $belumDinilai > 0 ? 'Lanjutkan penilaian mahasiswa' : 'Lihat mahasiswa bimbingan' }}<i class="fas fa-arrow-right" aria-hidden="true"></i></a></section>
    </div>
    <section class="dash-card dash-student-card"><div class="dash-card-heading"><div><h2>Mahasiswa bimbingan</h2><p>Penugasan terbaru dalam periode yang ditampilkan.</p></div><a href="{{ route('dosen.bimbingan') }}" class="dash-link">Lihat semua <i class="fas fa-arrow-right" aria-hidden="true"></i></a></div>
        @if($mahasiswaTerbaru->isNotEmpty())
        <div class="dash-table-wrap"><table class="dash-table"><thead><tr><th>Mahasiswa</th><th>Kegiatan</th><th>Nilai bimbingan</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
            @foreach($mahasiswaTerbaru as $assignment)
            <tr><td><div class="dash-table-person"><span class="dash-initial">{{ mb_strtoupper(mb_substr($assignment->mahasiswa?->nama ?? $assignment->nim, 0, 1)) }}</span><div><strong>{{ $assignment->mahasiswa?->nama ?? $assignment->nim }}</strong><span>{{ $assignment->nim }}</span></div></div></td><td><span class="dash-badge">{{ $assignment->mahasiswa?->kegiatan ?? 'Belum diatur' }}</span></td><td class="dash-grade">{{ $assignment->nilai !== null ? $assignment->nilai : '—' }}</td><td><span class="dash-badge {{ $assignment->nilai !== null ? 'dash-badge-green' : 'dash-badge-gold' }}">{{ $assignment->nilai !== null ? 'Sudah dinilai' : 'Belum dinilai' }}</span></td><td><a href="{{ route('dosen.mahasiswa.detail', $assignment->nim) }}" class="dash-table-link" aria-label="Lihat detail {{ $assignment->mahasiswa?->nama ?? $assignment->nim }}">Detail <i class="fas fa-arrow-right" aria-hidden="true"></i></a></td></tr>
            @endforeach
        </tbody></table></div>
        @else<div class="dash-empty"><i class="fas fa-user-graduate" aria-hidden="true"></i><h3>Belum ada mahasiswa bimbingan</h3><p>Mahasiswa akan tampil setelah penugasan dosen pembimbing diatur oleh admin.</p></div>@endif
    </section>
    <div class="dash-grid-equal">
        <section class="dash-card"><div class="dash-card-heading"><div><h2>Permohonan bimbingan</h2><p>Ajukan tindak lanjut untuk permohonan yang masuk.</p></div><span class="dash-subtle-chip">{{ $bimbinganBelumDireview }} belum direview</span></div>
            @forelse($bimbinganTerbaru as $review)
            <a href="{{ route('dosen.mahasiswa.detail', $review->nim) }}" class="dash-activity"><span class="dash-initial dash-initial-gold"><i class="far fa-comment-dots" aria-hidden="true"></i></span><div><strong>{{ $review->topik }}</strong><span>{{ $review->mahasiswa?->nama ?? $review->nim }} · {{ $review->tanggal_bimbingan?->translatedFormat('d M Y') }}</span></div><i class="fas fa-chevron-right dash-muted" aria-hidden="true"></i></a>
            @empty<div class="dash-empty"><i class="far fa-check-circle" aria-hidden="true"></i><h3>{{ $totalBimbingan > 0 ? 'Tidak ada permohonan menunggu' : 'Belum ada permohonan bimbingan' }}</h3><p>Permohonan yang belum direview akan tampil di sini.</p></div>@endforelse
        </section>
        <section class="dash-card"><div class="dash-card-heading"><div><h2>Akses cepat</h2><p>Semua peran pendampingan dalam satu tempat.</p></div></div><div class="dash-shortcut-list">
            @foreach([[route('dosen.ujian.index'), 'fa-gavel', 'Mahasiswa Ujian', 'Tinjau laporan dan kelola nilai ujian.'], [route('dosen.publikasi.index'), 'fa-book-reader', 'Penilaian Publikasi', 'Tinjau publikasi dan diseminasi mahasiswa.'], [route('dosen.program-kerja.dashboard'), 'fa-tasks', 'Program Kerja Bimbingan', 'Pantau program kerja dan luaran kegiatan.'], [route('dosen.program-kerja.monev-dashboard'), 'fa-clipboard-check', 'Monitoring & Evaluasi', 'Buka penugasan monev program kerja.']] as [$href, $icon, $title, $description])
            <a href="{{ $href }}" class="dash-attention"><span class="dash-action-icon dash-tone-sage"><i class="fas {{ $icon }}" aria-hidden="true"></i></span><div><strong>{{ $title }}</strong><span>{{ $description }}</span></div><i class="fas fa-arrow-right" aria-hidden="true"></i></a>
            @endforeach
        </div></section>
    </div>
</div>
@endsection
