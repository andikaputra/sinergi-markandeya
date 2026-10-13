<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sinergi Markandeya menghubungkan mahasiswa, dosen, dan mitra dalam program KKN, PPL, PKL, dan Magang. Temukan program dan informasi pendaftaran di sini.">
    <meta name="theme-color" content="#184c3d">
    <title>Sinergi Markandeya — Belajar, Berkarya, Berdampak</title>
    <link rel="icon" href="{{ asset('logo-universitas-markandeya.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="landing-page">
    @php
        $openPeriods = $pengumuman->filter(fn ($period) => $period->isPendaftaranOpen());
        $programs = [
            ['name' => 'KKN', 'full' => 'Kuliah Kerja Nyata', 'icon' => 'community', 'tag' => 'Pengabdian masyarakat', 'description' => 'Belajar bersama masyarakat. Kembangkan potensi desa melalui program kerja yang memberi manfaat nyata.', 'class' => 'community'],
            ['name' => 'PPL', 'full' => 'Praktik Pengalaman Lapangan', 'icon' => 'book', 'tag' => 'Pengalaman mengajar', 'description' => 'Bawa ilmu ke ruang kelas. Asah keterampilan mengajar dan tumbuh bersama sekolah mitra.', 'class' => 'education'],
            ['name' => 'PKL', 'full' => 'Praktik Kerja Lapangan', 'icon' => 'building', 'tag' => 'Penerapan ilmu', 'description' => 'Hubungkan teori dengan praktik. Kenali proses kerja dan terapkan kompetensi di instansi atau perusahaan.', 'class' => 'practice'],
            ['name' => 'Magang', 'full' => 'Program Magang', 'icon' => 'briefcase', 'tag' => 'Pengembangan karier', 'description' => 'Ambil langkah menuju dunia profesional. Perluas pengalaman, keterampilan, dan jejaring di tempat kerja.', 'class' => 'career'],
        ];
    @endphp
    <svg xmlns="http://www.w3.org/2000/svg" class="lp-icon-library" aria-hidden="true">
        <symbol id="lp-arrow" viewBox="0 0 24 24"><path d="M5 12h14m-6-6 6 6-6 6"/></symbol>
        <symbol id="lp-arrow-up" viewBox="0 0 24 24"><path d="M6 18 18 6M6 6h12v12"/></symbol>
        <symbol id="lp-community" viewBox="0 0 24 24"><circle cx="12" cy="7" r="3"/><path d="M6 21v-3a6 6 0 0 1 12 0v3M3 12a3 3 0 0 1 2-5m14 0a3 3 0 0 1 2 5M2 21v-3a4 4 0 0 1 3-4m14 0a4 4 0 0 1 3 4v3"/></symbol>
        <symbol id="lp-book" viewBox="0 0 24 24"><path d="M12 5v16M3 4c4-1 7 0 9 2 2-2 5-3 9-2v15c-4-1-7 0-9 2-2-2-5-3-9-2V4Z"/></symbol>
        <symbol id="lp-building" viewBox="0 0 24 24"><path d="M4 21V3h12v18M16 9h4v12M2 21h20M8 7h4M8 11h4M8 15h4M9 21v-3h2v3"/></symbol>
        <symbol id="lp-briefcase" viewBox="0 0 24 24"><rect x="3" y="7" width="18" height="14" rx="2"/><path d="M8 7V3h8v4M3 12a23 23 0 0 0 18 0M12 12v4"/></symbol>
        <symbol id="lp-check" viewBox="0 0 24 24"><path d="m5 12 4 4L19 6"/></symbol>
        <symbol id="lp-calendar" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4m10-4v4M3 11h18m-13 5h3m3 0h2"/></symbol>
        <symbol id="lp-chevron" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></symbol>
        <symbol id="lp-menu" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></symbol>
    </svg>
    <a class="lp-skip" href="#konten">Langsung ke konten</a>

    <header class="lp-header">
        <div class="lp-container lp-header-inner">
            <a href="{{ url('/') }}" class="lp-brand" aria-label="Sinergi Markandeya — Beranda">
                <img src="{{ asset('logo-universitas-markandeya.png') }}" width="46" height="46" alt="Logo Universitas Markandeya">
                <span><strong>Sinergi<span class="lp-brand-dot">.</span></strong><small>UNIVERSITAS MARKANDEYA</small></span>
            </a>
            <nav id="lp-navigation" class="lp-navigation" aria-label="Navigasi utama">
                <a href="#program">Program</a>
                <a href="#pendaftaran">Pendaftaran @if($openPeriods->isNotEmpty())<span class="lp-nav-dot" aria-label="Sedang dibuka"></span>@endif</a>
                <a href="#alur">Alur Kegiatan</a>
                <a href="#faq">FAQ</a>
            </nav>
            <div class="lp-header-actions">
                <a href="{{ route('login') }}" class="lp-button lp-button-small">Masuk <svg class="lp-icon" aria-hidden="true"><use href="#lp-arrow"/></svg></a>
                <button type="button" id="lp-menu-toggle" class="lp-menu-toggle" aria-controls="lp-navigation" aria-expanded="false" aria-label="Buka menu navigasi" hidden>
                    <svg class="lp-icon" aria-hidden="true"><use href="#lp-menu"/></svg>
                </button>
            </div>
        </div>
    </header>

    <main id="konten">
        <section class="lp-hero" aria-labelledby="lp-hero-title">
            <div class="lp-container lp-hero-grid">
                <div class="lp-hero-copy">
                    <p class="lp-eyebrow"><span></span> BELAJAR DI LUAR KAMPUS</p>
                    <h1 id="lp-hero-title">Dari kampus,<br>untuk <em>dampak</em><br>yang lebih nyata<span class="lp-title-dot">.</span></h1>
                    <p class="lp-hero-description">Setiap pengalaman adalah kesempatan untuk tumbuh. Wujudkan pengabdian, asah keterampilan, dan temukan potensi diri bersama Sinergi Markandeya.</p>
                    <div class="lp-hero-actions">
                        <a href="{{ route('login') }}" class="lp-button">Masuk ke Sistem <svg class="lp-icon" aria-hidden="true"><use href="#lp-arrow"/></svg></a>
                        <a href="#program" class="lp-text-link">Jelajahi Program <svg class="lp-icon" aria-hidden="true"><use href="#lp-arrow-up"/></svg></a>
                    </div>
                    <div class="lp-hero-note">
                        <span class="lp-note-icon"><svg class="lp-icon" aria-hidden="true"><use href="#lp-check"/></svg></span>
                        <p>Mahasiswa, dosen, dan mitra.<br><strong>Terhubung dalam satu platform.</strong></p>
                    </div>
                </div>
                <div class="lp-hero-visual">
                    <div class="lp-visual-caption"><span class="lp-caption-line"></span> BELAJAR · BERKARYA · BERDAMPAK</div>
                    <img class="lp-journey-image" src="{{ asset('images/landing-journey.svg') }}" width="720" height="760" fetchpriority="high" alt="Ilustrasi mahasiswa berjalan dari kampus menuju desa dan sekolah di tengah lanskap hijau">
                    <div class="lp-visual-seal" aria-hidden="true"><span>ILMU UNTUK</span><svg class="lp-icon"><use href="#lp-community"/></svg><span>MASYARAKAT</span></div>
                    <div class="lp-visual-label"><span class="lp-label-number">04</span><div><strong>Program, banyak kesempatan.</strong><span>Satu langkah awal untuk masa depanmu.</span></div><svg class="lp-icon" aria-hidden="true"><use href="#lp-arrow-up"/></svg></div>
                </div>
            </div>
        </section>

        <div class="lp-program-strip" aria-label="Empat program kegiatan">
            <div class="lp-container lp-strip-grid">
                @foreach($programs as $program)
                <a href="#program-{{ strtolower($program['name']) }}" class="lp-strip-item">
                    <svg class="lp-icon" aria-hidden="true"><use href="#lp-{{ $program['icon'] }}"/></svg>
                    <span><strong>{{ $program['name'] }}</strong><small>{{ $program['tag'] }}</small></span>
                    <svg class="lp-icon lp-strip-arrow" aria-hidden="true"><use href="#lp-arrow-up"/></svg>
                </a>
                @endforeach
            </div>
        </div>

        <section id="pendaftaran" class="lp-section lp-registration" aria-labelledby="lp-registration-title">
            <div class="lp-container">
                <div class="lp-section-heading">
                    <div><p class="lp-eyebrow">INFORMASI PENDAFTARAN</p><h2 id="lp-registration-title">Langkah pertamamu<br>dimulai <em>di sini.</em></h2></div>
                    <p>Pilih periode yang tersedia, siapkan dokumenmu, dan daftar kegiatan melalui akun mahasiswa.</p>
                </div>
                @if($pengumuman->isNotEmpty())
                <div class="lp-period-grid">
                    @foreach($pengumuman as $ta)
                    @php $isOpen = $ta->isPendaftaranOpen(); @endphp
                    <article class="lp-period {{ $isOpen ? 'lp-period-open' : '' }}">
                        <div class="lp-period-heading"><span class="lp-period-eyebrow">TAHUN AKADEMIK</span><span class="lp-status {{ $isOpen ? 'lp-status-open' : '' }}"><span></span>{{ $isOpen ? 'Pendaftaran dibuka' : 'Segera dibuka' }}</span></div>
                        <h3>{{ $ta->tahun }} <span>{{ $ta->semester }}</span></h3>
                        <div class="lp-period-dates">
                            <svg class="lp-icon" aria-hidden="true"><use href="#lp-calendar"/></svg>
                            <div><span>{{ $isOpen ? 'Periode pendaftaran' : 'Pendaftaran mulai' }}</span><strong><time datetime="{{ $ta->tanggal_mulai_daftar->toDateString() }}">{{ $ta->tanggal_mulai_daftar->translatedFormat('d M Y') }}</time><span class="lp-date-dash">—</span><time datetime="{{ $ta->tanggal_selesai_daftar->toDateString() }}">{{ $ta->tanggal_selesai_daftar->translatedFormat('d M Y') }}</time></strong></div>
                        </div>
                        <div class="lp-period-footer"><p>KKN · PPL · PKL · Magang</p>
                            @if($isOpen)
                            <a href="{{ route('login') }}" class="lp-text-link">Masuk & Daftar <svg class="lp-icon" aria-hidden="true"><use href="#lp-arrow"/></svg></a>
                            @else
                            <span class="lp-period-wait">Tunggu jadwal pembukaan</span>
                            @endif
                        </div>
                    </article>
                    @endforeach
                </div>
                @else
                <div class="lp-registration-empty">
                    <div class="lp-empty-icon"><svg class="lp-icon" aria-hidden="true"><use href="#lp-calendar"/></svg></div>
                    <div><h3>Periode baru sedang dipersiapkan.</h3><p>Jadwal pendaftaran akan tampil di sini saat tersedia. Sementara itu, kenali program yang sesuai dengan tujuanmu.</p></div>
                    <a href="#program" class="lp-text-link">Lihat Program <svg class="lp-icon" aria-hidden="true"><use href="#lp-arrow"/></svg></a>
                </div>
                @endif
            </div>
        </section>

        <section id="program" class="lp-section lp-programs" aria-labelledby="lp-program-title">
            <div class="lp-container">
                <div class="lp-section-heading">
                    <div><p class="lp-eyebrow">TEMUKAN PENGALAMANMU</p><h2 id="lp-program-title">Empat jalan.<br><em>Beragam peluang.</em></h2></div>
                    <p>Dari pengabdian di desa hingga pengalaman di dunia kerja, ada ruang untuk menerapkan ilmu dan mengembangkan diri.</p>
                </div>
                <div class="lp-program-grid">
                    @foreach($programs as $program)
                    <article id="program-{{ strtolower($program['name']) }}" class="lp-program-card lp-program-{{ $program['class'] }}">
                        <div class="lp-program-top"><div class="lp-program-icon"><svg class="lp-icon" aria-hidden="true"><use href="#lp-{{ $program['icon'] }}"/></svg></div><span>0{{ $loop->iteration }}</span></div>
                        <p class="lp-program-tag">{{ $program['tag'] }}</p>
                        <h3>{{ $program['name'] }}</h3>
                        <p class="lp-program-full">{{ $program['full'] }}</p>
                        <p class="lp-program-description">{{ $program['description'] }}</p>
                        <a href="#pendaftaran" class="lp-program-link">Lihat Pendaftaran <svg class="lp-icon" aria-hidden="true"><use href="#lp-arrow-up"/></svg></a>
                    </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="alur" class="lp-section lp-process" aria-labelledby="lp-process-title">
            <div class="lp-container lp-process-layout">
                <div class="lp-process-intro"><p class="lp-eyebrow">TERARAH DARI AWAL</p><h2 id="lp-process-title">Fokus pada<br><em>pengalaman.</em><br>Kami bantu alurnya.</h2><p>Pendaftaran, kegiatan, bimbingan, hingga laporan. Semua dapat dikelola dalam satu tempat.</p><a href="{{ route('login') }}" class="lp-text-link">Mulai dari akunmu <svg class="lp-icon" aria-hidden="true"><use href="#lp-arrow"/></svg></a></div>
                <ol class="lp-process-steps">
                    <li><span class="lp-step-number">01</span><div><h3>Masuk & pilih kegiatan</h3><p>Gunakan akunmu, pilih program dan periode yang dibuka, lalu lengkapi dokumen pendaftaran.</p></div></li>
                    <li><span class="lp-step-number">02</span><div><h3>Persiapkan & jalani program</h3><p>Pantau penempatan, susun program kerja, dan catat perjalanan kegiatan melalui jurnal harian.</p></div></li>
                    <li><span class="lp-step-number">03</span><div><h3>Bimbingan & kembangkan hasil</h3><p>Ajukan bimbingan, tindak lanjuti masukan dosen, dan dokumentasikan luaran kegiatanmu.</p></div></li>
                    <li><span class="lp-step-number">04</span><div><h3>Lengkapi laporan akhir</h3><p>Unggah tautan laporan akhir dan pantau hasil penilaian melalui dashboard mahasiswa.</p></div></li>
                </ol>
            </div>
        </section>

        <section id="faq" class="lp-section lp-faq" aria-labelledby="lp-faq-title">
            <div class="lp-container lp-faq-layout">
                <div><p class="lp-eyebrow">SEBELUM MEMULAI</p><h2 id="lp-faq-title">Masih ada<br><em>pertanyaan?</em></h2><p class="lp-faq-description">Beberapa hal yang perlu kamu ketahui sebelum memulai kegiatan di Sinergi Markandeya.</p></div>
                <div class="lp-faq-list">
                    <details open><summary>Bagaimana cara masuk ke sistem?<svg class="lp-icon" aria-hidden="true"><use href="#lp-chevron"/></svg></summary><p>Klik “Masuk ke Sistem”. Mahasiswa dapat menggunakan NIM dan password akun AIS, atau email dan password akun lokal yang sudah terdaftar. Dosen dapat masuk menggunakan NIDN atau NIP. Pembimbing luar menggunakan email dan password yang diberikan.</p></details>
                    <details><summary>Kapan saya bisa mendaftar kegiatan?<svg class="lp-icon" aria-hidden="true"><use href="#lp-chevron"/></svg></summary><p>Periksa bagian <a href="#pendaftaran">Informasi Pendaftaran</a> untuk melihat periode yang tersedia. Saat pendaftaran dibuka, masuk ke dashboard mahasiswa dan pilih menu Daftar Kegiatan untuk melengkapi pendaftaran.</p></details>
                    <details><summary>Apakah saya bisa memilih lokasi kegiatan?<svg class="lp-icon" aria-hidden="true"><use href="#lp-chevron"/></svg></summary><p>Untuk KKN dan PPL, kamu dapat memilih lokasi yang tersedia sesuai kuota saat pendaftaran. Untuk PKL dan Magang, pengajuan instansi melalui sistem memerlukan persetujuan admin.</p></details>
                    <details><summary>Apa yang bisa saya kelola di dashboard?<svg class="lp-icon" aria-hidden="true"><use href="#lp-chevron"/></svg></summary><p>Kamu dapat melihat kegiatan dan penempatan, mengisi jurnal, menyusun program kerja, mengajukan bimbingan, mencatat luaran atau publikasi, serta mengirim tautan laporan akhir dan melihat nilai.</p></details>
                    <details><summary>Bagaimana jika saya lupa password?<svg class="lp-icon" aria-hidden="true"><use href="#lp-chevron"/></svg></summary><p>Untuk akun lokal mahasiswa, gunakan <a href="{{ route('lupa-password') }}">Lupa Password</a> di halaman masuk. Masukkan NIM dan email terdaftar untuk meminta link reset melalui email. Jika kamu menggunakan akun AIS, perubahan password AIS dilakukan melalui layanan AIS.</p></details>
                </div>
            </div>
        </section>

        <section class="lp-final-section" aria-labelledby="lp-final-title">
            <div class="lp-container">
                <div class="lp-final-cta"><div class="lp-final-art" aria-hidden="true"></div><div class="lp-final-copy"><p class="lp-eyebrow">PENGALAMAN BARU MENUNGGUMU</p><h2 id="lp-final-title">Ilmu yang dipelajari.<br><em>Manfaat yang dibagikan.</em></h2><p>Mulai perjalananmu bersama Sinergi Markandeya.</p></div><a href="{{ route('login') }}" class="lp-button lp-button-gold">Masuk ke Sistem <svg class="lp-icon" aria-hidden="true"><use href="#lp-arrow"/></svg></a></div>
            </div>
        </section>
    </main>
    <footer class="lp-footer">
        <div class="lp-container">
            <div class="lp-footer-top"><div><a href="{{ url('/') }}" class="lp-brand"><img src="{{ asset('logo-universitas-markandeya.png') }}" width="42" height="42" alt="Logo Universitas Markandeya" loading="lazy"><span><strong>Sinergi<span class="lp-brand-dot">.</span></strong><small>UNIVERSITAS MARKANDEYA</small></span></a><p>Menghubungkan ilmu, pengalaman,<br>dan pengabdian dalam satu langkah.</p></div><nav aria-label="Navigasi footer"><a href="#program">Program</a><a href="#pendaftaran">Pendaftaran</a><a href="#faq">FAQ</a><a href="{{ route('loginadmin') }}">Portal Admin <svg class="lp-icon" aria-hidden="true"><use href="#lp-arrow-up"/></svg></a></nav></div>
            <div class="lp-footer-bottom"><p>© {{ date('Y') }} Universitas Markandeya. Semua hak dilindungi.</p><span>Belajar. Berkarya. Berdampak.</span></div>
        </div>
    </footer>
</body>
</html>
