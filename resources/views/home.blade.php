<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sinergi Markandeya - Platform KKN, PPL, PKL & Magang Terpadu</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --primary: #1a5d4d;
            --gold: #d4a574;
            --cream: #f5e6d3;
            --dark-primary: #0f2d26;
        }

        body {
            font-family: 'Instrument Sans', sans-serif;
            background-color: #f5f3f0;
        }

        .glass-header {
            position: sticky;
            top: 0;
            z-index: 50;
            background-color: var(--primary);
            backdrop-filter: blur(12px);
            border-bottom: 2px solid var(--gold);
            transition: all 0.3s;
        }

        .glass-header a,
        .glass-header span {
            color: var(--cream);
        }

        .glass-header button {
            background-color: var(--gold);
            color: var(--primary);
        }

        .hero-gradient {
            background: linear-gradient(135deg, var(--primary) 0%, var(--dark-primary) 100%);
            color: white;
        }

        .hero-gradient h1,
        .hero-gradient h2 {
            color: #ffffff;
        }

        .hero-gradient p {
            color: rgba(255, 255, 255, 0.95);
        }

        .btn-primary {
            background-color: #d4a574;
            color: #1a5d4d;
            border: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #c9905c;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 165, 116, 0.3);
        }

        /* Override Tailwind button colors */
        .bg-primary-600 {
            background-color: #d4a574 !important;
            color: #1a5d4d !important;
        }

        .bg-primary-600:hover {
            background-color: #c9905c !important;
        }

        .bg-primary-700 {
            background-color: #c9905c !important;
            color: #1a5d4d !important;
        }

        .bg-primary-700:hover {
            background-color: #b87b44 !important;
        }

        .section-title {
            color: var(--primary);
            border-bottom: 3px solid var(--gold);
        }

        .card-accent {
            border-top: 4px solid var(--gold);
            background-color: white;
        }
    </style>
</head>

<body class="text-gray-900 selection:bg-gold selection:text-primary">

    <!-- Navigation -->
    <header class="glass-header" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('logo-universitas-markandeya.png') }}" alt="Logo Universitas Markandeya"
                    class="h-12 w-auto">
                <span class="font-black text-2xl tracking-tighter text-gray-900">Sinergi<span
                        class="text-primary-600">.</span></span>
            </div>

            <nav
                class="hidden md:flex items-center space-x-8 text-sm font-bold text-gray-500 uppercase tracking-widest">
                @if($pengumuman->count() > 0)
                    <a href="#pendaftaran"
                        class="text-gold-600 hover:text-gold-700 transition-colors flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-gold-500 rounded-full animate-pulse"></span>Pendaftaran
                    </a>
                @endif
                <a href="#program" class="hover:text-primary-600 transition-colors">Program</a>
                <a href="#faq" class="hover:text-primary-600 transition-colors">FAQ</a>
            </nav>

            <div class="flex items-center space-x-4">
                <a href="{{ route('login') }}"
                    class="hidden sm:inline-flex px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-2xl shadow-lg transition-all hover:-trangray-y-0.5 active:scale-95">
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk ke Sistem
                </a>
            </div>
        </div>
    </header>

    <main class="hero-gradient">
        <!-- Hero Section -->
        <section class="relative pt-20 pb-32 overflow-hidden">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="relative z-10 space-y-8 text-center lg:text-left">
                    <div
                        class="inline-flex items-center space-x-2 px-4 py-2 bg-primary-50 text-primary-600 rounded-full text-xs font-black uppercase tracking-widest border border-primary-100">
                        <span class="flex h-2 w-2 rounded-full bg-primary-600 animate-pulse"></span>
                        <span>Sistem Terintegrasi 2026</span>
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-black text-gray-900 leading-[1.1] tracking-tight">
                        Wujudkan <span
                            class=" bg-clip-text bg-gradient-to-r from-primary-600 to-primary-600">Sinergi</span>
                        Kampus & Masyarakat.
                    </h1>
                    <p class="text-xl text-gray-500 max-w-xl leading-relaxed mx-auto lg:mx-0 font-medium">
                        Platform digital terpadu Universitas Markandeya untuk manajemen program KKN, PPL, PKL, dan
                        Magang yang lebih transparan, efisien, dan modern.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <a href="{{ route('login') }}"
                            class="w-full sm:w-auto px-8 py-5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-[2rem] shadow-2xl transition-all flex items-center justify-center group">
                            <i class="fas fa-sign-in-alt mr-3"></i>Masuk ke Sistem
                        </a>
                        <a href="#program"
                            class="w-full sm:w-auto px-8 py-5 bg-white border border-gray-100 text-gray-700 font-bold rounded-[2rem] shadow-sm hover:shadow-lg transition-all text-center">
                            Pelajari Program
                        </a>
                    </div>
                </div>

                <div class="relative hidden lg:block">

                </div>
            </div>
        </section>

        <!-- Pengumuman Pendaftaran -->
        @if($pengumuman->count() > 0)
            <section id="pendaftaran" class="py-20 bg-white border-t border-gray-50">
                <div class="max-w-7xl mx-auto px-6">
                    <div class="text-center max-w-2xl mx-auto mb-12 space-y-3">
                        <div
                            class="inline-flex items-center space-x-2 px-4 py-2 bg-gold-50 text-gold-600 rounded-full text-xs font-black uppercase tracking-widest border border-gold-100">
                            <span class="flex h-2 w-2 rounded-full bg-gold-500 animate-pulse"></span>
                            <span>Pengumuman Pendaftaran</span>
                        </div>
                        <h3 class="text-3xl font-extrabold text-gray-900 tracking-tight">Pendaftaran Kegiatan Dibuka</h3>
                        <p class="text-gray-400 font-medium">Login terlebih dahulu untuk mendaftar kegiatan pilihan Anda.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($pengumuman as $ta)
                            @php
                                $isOpen = $ta->isPendaftaranOpen();
                                $isComing = !$isOpen && $ta->tanggal_mulai_daftar->isFuture();
                            @endphp
                            <div
                                class="bg-white rounded-[2rem] border {{ $isOpen ? 'border-gold-100 shadow-lg shadow-gold-50' : 'border-gray-100' }} p-8 hover:shadow-xl transition-all">
                                <div class="flex items-start justify-between mb-6">
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tahun
                                            Akademik</p>
                                        <h4 class="text-xl font-black text-gray-900">{{ $ta->tahun }}</h4>
                                        <span
                                            class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-[10px] font-bold uppercase">{{ $ta->semester }}</span>
                                    </div>
                                    @if($isOpen)
                                        <span
                                            class="px-3 py-1.5 bg-gold-50 text-gold-600 rounded-xl text-[10px] font-black border border-gold-100 uppercase tracking-widest whitespace-nowrap">
                                            <i class="fas fa-circle text-[6px] mr-1 animate-pulse"></i>Buka
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1.5 bg-gold-50 text-gold-600 rounded-xl text-[10px] font-black border border-gold-100 uppercase tracking-widest whitespace-nowrap">
                                            <i class="fas fa-clock text-[10px] mr-1"></i>Segera
                                        </span>
                                    @endif
                                </div>

                                <div class="space-y-2 mb-6">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-calendar-check text-primary-400 w-5 mr-2"></i>
                                        <span class="font-medium">Mulai: <strong
                                                class="text-gray-700">{{ $ta->tanggal_mulai_daftar->format('d/m/Y') }}</strong></span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-calendar-times text-red-400 w-5 mr-2"></i>
                                        <span class="font-medium">Tutup: <strong
                                                class="text-gray-700">{{ $ta->tanggal_selesai_daftar->format('d/m/Y') }}</strong></span>
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-2 mb-6">
                                    @foreach(['KKN', 'PPL', 'PKL', 'Magang'] as $keg)
                                        <span
                                            class="px-3 py-1 bg-primary-50 text-primary-600 rounded-lg text-[10px] font-bold uppercase">{{ $keg }}</span>
                                    @endforeach
                                </div>

                                @if($isOpen)
                                    <a href="{{ route('login') }}"
                                        class="block w-full py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl text-center text-sm transition-all shadow-lg shadow-primary-100">
                                        <i class="fas fa-sign-in-alt mr-2"></i>Login & Daftar Sekarang
                                    </a>
                                @else
                                    <div
                                        class="w-full py-3 bg-gray-50 text-gray-400 font-bold rounded-2xl text-center text-sm border border-gray-100">
                                        <i class="fas fa-clock mr-2"></i>Belum Dibuka
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <!-- Programs Section -->
        <section id="program" class="py-32 bg-white relative">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center max-w-2xl mx-auto mb-20 space-y-4">
                    <h2 class="text-[10px] font-black text-primary-600 uppercase tracking-[0.3em]">Program Kami</h2>
                    <h3 class="text-4xl font-extrabold text-gray-900 tracking-tight">Eksplorasi Berbagai Kesempatan</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div
                        class="group bg-gray-50/50 p-10 rounded-[2.5rem] border border-gray-100 hover:bg-white hover:shadow-2xl transition-all duration-500">
                        <div
                            class="w-16 h-16 bg-primary-100 text-primary-600 rounded-2xl flex items-center justify-center text-2xl mb-8 group-hover:bg-primary-600 group-hover:text-white transition-all">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4 class="text-2xl font-bold mb-4">KKN</h4>
                        <p class="text-gray-500 font-medium">Kuliah Kerja Nyata. Berinteraksi langsung dengan masyarakat
                            desa dan berikan solusi nyata.</p>
                    </div>

                    <div
                        class="group bg-gray-50/50 p-10 rounded-[2.5rem] border border-gray-100 hover:bg-white hover:shadow-2xl transition-all duration-500 lg:trangray-y-6">
                        <div
                            class="w-16 h-16 bg-gold-100 text-gold-600 rounded-2xl flex items-center justify-center text-2xl mb-8 group-hover:bg-gold-600 group-hover:text-white transition-all">
                            <i class="fas fa-school"></i>
                        </div>
                        <h4 class="text-2xl font-bold mb-4">PPL</h4>
                        <p class="text-gray-500 font-medium">Praktik Pengalaman Lapangan. Persiapkan diri Anda sebagai
                            pengajar profesional di sekolah mitra.</p>
                    </div>

                    <div
                        class="group bg-gray-50/50 p-10 rounded-[2.5rem] border border-gray-100 hover:bg-white hover:shadow-2xl transition-all duration-500">
                        <div
                            class="w-16 h-16 bg-gold-100 text-gold-600 rounded-2xl flex items-center justify-center text-2xl mb-8 group-hover:bg-gold-600 group-hover:text-white transition-all">
                            <i class="fas fa-building"></i>
                        </div>
                        <h4 class="text-2xl font-bold mb-4">PKL</h4>
                        <p class="text-gray-500 font-medium">Praktik Kerja Lapangan. Rasakan dunia kerja sesungguhnya di
                            perusahaan atau instansi ternama.</p>
                    </div>

                    <div
                        class="group bg-gray-50/50 p-10 rounded-[2.5rem] border border-gray-100 hover:bg-white hover:shadow-2xl transition-all duration-500 lg:trangray-y-6">
                        <div
                            class="w-16 h-16 bg-primary-100 text-primary-600 rounded-2xl flex items-center justify-center text-2xl mb-8 group-hover:bg-primary-600 group-hover:text-white transition-all">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <h4 class="text-2xl font-bold mb-4">Magang</h4>
                        <p class="text-gray-500 font-medium">Program Magang. Tingkatkan kompetensi profesional melalui
                            pengalaman kerja langsung di industri.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-24 bg-primary-600 text-white relative">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 lg:grid-cols-4 gap-12 text-center relative z-10">
                <div class="space-y-2">
                    <span class="block text-5xl font-black tracking-tighter" x-data="{ count: 0 }"
                        x-init="setInterval(() => { if(count < 1200) count+=10 }, 20)">
                        <span x-text="count">1200</span>+
                    </span>
                    <span class="text-sm font-bold text-primary-200 uppercase tracking-widest">Mahasiswa Aktif</span>
                </div>
                <div class="space-y-2">
                    <span class="block text-5xl font-black tracking-tighter">450+</span>
                    <span class="text-sm font-bold text-primary-200 uppercase tracking-widest">Lokasi Tersebar</span>
                </div>
                <div class="space-y-2">
                    <span class="block text-5xl font-black tracking-tighter">80+</span>
                    <span class="text-sm font-bold text-primary-200 uppercase tracking-widest">Dosen Pembimbing</span>
                </div>
                <div class="space-y-2">
                    <span class="block text-5xl font-black tracking-tighter">99%</span>
                    <span class="text-sm font-bold text-primary-200 uppercase tracking-widest">Kepuasan Sistem</span>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section id="faq" class="py-32 bg-white" x-data="{ active: null }">
            <div class="max-w-3xl mx-auto px-6">
                <div class="text-center mb-16 space-y-4">
                    <h2 class="text-[10px] font-black text-primary-600 uppercase tracking-[0.3em]">Pertanyaan Umum</h2>
                    <h3 class="text-4xl font-extrabold text-gray-900 tracking-tight">Sering Ditanyakan</h3>
                </div>

                <div class="space-y-4">
                    <div class="border border-gray-100 rounded-2xl overflow-hidden">
                        <button @click="active === 1 ? active = null : active = 1"
                            class="w-full flex items-center justify-between p-6 bg-gray-50 hover:bg-white transition-colors text-left focus:outline-none">
                            <span class="font-bold text-gray-800">Bagaimana cara mendaftar akun mahasiswa?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"
                                :class="active === 1 ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="active === 1" class="p-6 bg-white text-gray-500 text-sm border-t border-gray-100">
                            Klik tombol "Daftar Sekarang" di pojok kanan atas, isi formulir dengan NIM dan data diri
                            yang valid, lalu tunggu verifikasi.
                        </div>
                    </div>

                    <div class="border border-gray-100 rounded-2xl overflow-hidden">
                        <button @click="active === 2 ? active = null : active = 2"
                            class="w-full flex items-center justify-between p-6 bg-gray-50 hover:bg-white transition-colors text-left focus:outline-none">
                            <span class="font-bold text-gray-800">Apakah saya bisa memilih lokasi KKN sendiri?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"
                                :class="active === 2 ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="active === 2" class="p-6 bg-white text-gray-500 text-sm border-t border-gray-100">
                            Lokasi KKN ditentukan oleh panitia berdasarkan kuota dan persebaran program studi untuk
                            pemerataan.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-32 px-6">
            <div
                class="max-w-5xl mx-auto bg-gray-900 rounded-[3rem] p-12 lg:p-24 text-center text-white relative overflow-hidden">
                <div class="relative z-10 space-y-8">
                    <h2 class="text-4xl lg:text-5xl font-black tracking-tight leading-tight text-white">Siap Memulai
                        Pengalaman Luar Kampus?</h2>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                        <a href="{{ route('login') }}"
                            class="w-full sm:w-auto px-10 py-5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl transition-all shadow-xl shadow-primary-900 flex items-center justify-center gap-3">
                            <i class="fas fa-sign-in-alt"></i> Masuk ke Sistem
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-white pt-24 pb-12 border-t border-gray-100">
        <div class="md:col-span-1 space-y-6 text-center lg:text-left">
            <div class="flex items-center justify-center lg:justify-start space-x-3">
                <img src="{{ asset('logo-universitas-markandeya.png') }}" alt="Logo Universitas Markandeya"
                    class="h-10 w-auto">
                <span class="font-black text-2xl tracking-tighter italic text-gray-900">Sinergi<span
                        class="text-primary-600">.</span></span>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em]">
                &copy; {{ date('Y') }} Universitas Markandeya.
            </p>
        </div>
    </footer>

    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>