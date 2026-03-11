<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sinergi Markandeya - Platform KKN, PPL & PKL Terpadu</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
    
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
        .glass-header {
            position: sticky;
            top: 0;
            z-index: 50;
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #f3f4f6;
            transition: all 0.3s;
        }
        .hero-gradient {
            background: radial-gradient(circle at top right, rgba(59, 130, 246, 0.08), transparent),
                        radial-gradient(circle at bottom left, rgba(99, 102, 241, 0.05), transparent);
        }
    </style>
</head>
<body class="text-gray-900 bg-white selection:bg-blue-100 selection:text-blue-700">

    <!-- Navigation -->
    <header class="glass-header" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <img src="https://markandeyabali.ac.id/wp-content/uploads/2023/06/cropped-cropped-logo-1.png" alt="Logo Markandeya" class="h-12 w-auto">
                <span class="font-black text-2xl tracking-tighter text-gray-900">Sinergi<span class="text-blue-600">.</span></span>
            </div>
            
            <nav class="hidden md:flex items-center space-x-8 text-sm font-bold text-gray-500 uppercase tracking-widest">
                <a href="#program" class="hover:text-blue-600 transition-colors">Program</a>
                <a href="#fitur" class="hover:text-blue-600 transition-colors">Fitur</a>
                <a href="#galeri" class="hover:text-blue-600 transition-colors">Galeri</a>
                <a href="#faq" class="hover:text-blue-600 transition-colors">FAQ</a>
            </nav>

            <div class="flex items-center space-x-4">
                <a href="{{ route('login') }}" class="text-sm font-bold text-gray-700 hover:text-blue-600 px-4 py-2 transition-all">Masuk</a>
                <a href="{{ route('register.form') }}" class="hidden sm:inline-flex px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-2xl shadow-lg shadow-blue-100 transition-all hover:-translate-y-0.5 active:scale-95">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </header>

    <main class="hero-gradient">
        <!-- Hero Section -->
        <section class="relative pt-20 pb-32 overflow-hidden">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="relative z-10 space-y-8 text-center lg:text-left">
                    <div class="inline-flex items-center space-x-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-full text-xs font-black uppercase tracking-widest border border-blue-100">
                        <span class="flex h-2 w-2 rounded-full bg-blue-600 animate-pulse"></span>
                        <span>Sistem Terintegrasi 2026</span>
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-black text-gray-900 leading-[1.1] tracking-tight">
                        Wujudkan <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Sinergi</span> Kampus & Masyarakat.
                    </h1>
                    <p class="text-xl text-gray-500 max-w-xl leading-relaxed mx-auto lg:mx-0 font-medium">
                        Platform digital terpadu ITP Markandeya Bali untuk manajemen program KKN, PPL, dan PKL yang lebih transparan, efisien, dan modern.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <a href="{{ route('register.form') }}" class="w-full sm:w-auto px-8 py-5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-[2rem] shadow-2xl shadow-blue-200 transition-all flex items-center justify-center group">
                            Mulai Perjalanan Anda
                            <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <a href="#program" class="w-full sm:w-auto px-8 py-5 bg-white border border-gray-100 text-gray-700 font-bold rounded-[2rem] shadow-sm hover:shadow-lg transition-all text-center">
                            Pelajari Program
                        </a>
                    </div>
                </div>

                <div class="relative hidden lg:block">
                    <div class="relative w-full aspect-square bg-white rounded-[3rem] shadow-2xl border border-gray-100 overflow-hidden transform rotate-3">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-indigo-50 p-12">
                            <div class="space-y-6 text-gray-300">
                                <div class="h-6 w-32 bg-blue-200 rounded-full"></div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="h-40 bg-white rounded-3xl border border-gray-100 p-6 flex flex-col justify-end">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 mb-2"></div>
                                        <div class="h-3 w-full bg-gray-100 rounded-full"></div>
                                    </div>
                                    <div class="h-40 bg-white rounded-3xl border border-gray-100 p-6 flex flex-col justify-end translate-y-8">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-100 mb-2"></div>
                                        <div class="h-3 w-full bg-gray-100 rounded-full"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Programs Section -->
        <section id="program" class="py-32 bg-white relative">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center max-w-2xl mx-auto mb-20 space-y-4">
                    <h2 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em]">Program Kami</h2>
                    <h3 class="text-4xl font-extrabold text-gray-900 tracking-tight">Eksplorasi Berbagai Kesempatan</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="group bg-gray-50/50 p-10 rounded-[2.5rem] border border-gray-100 hover:bg-white hover:shadow-2xl transition-all duration-500">
                        <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-2xl mb-8 group-hover:bg-blue-600 group-hover:text-white transition-all">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4 class="text-2xl font-bold mb-4">KKN</h4>
                        <p class="text-gray-500 font-medium">Kuliah Kerja Nyata. Berinteraksi langsung dengan masyarakat desa dan berikan solusi nyata.</p>
                    </div>

                    <div class="group bg-gray-50/50 p-10 rounded-[2.5rem] border border-gray-100 hover:bg-white hover:shadow-2xl transition-all duration-500 md:translate-y-6">
                        <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl mb-8 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                            <i class="fas fa-school"></i>
                        </div>
                        <h4 class="text-2xl font-bold mb-4">PPL</h4>
                        <p class="text-gray-500 font-medium">Praktik Pengalaman Lapangan. Persiapkan diri Anda sebagai pengajar profesional di sekolah mitra.</p>
                    </div>

                    <div class="group bg-gray-50/50 p-10 rounded-[2.5rem] border border-gray-100 hover:bg-white hover:shadow-2xl transition-all duration-500">
                        <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center text-2xl mb-8 group-hover:bg-amber-600 group-hover:text-white transition-all">
                            <i class="fas fa-building"></i>
                        </div>
                        <h4 class="text-2xl font-bold mb-4">PKL</h4>
                        <p class="text-gray-500 font-medium">Praktik Kerja Lapangan. Rasakan dunia kerja sesungguhnya di perusahaan atau instansi ternama.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-24 bg-blue-600 text-white relative">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 lg:grid-cols-4 gap-12 text-center relative z-10">
                <div class="space-y-2">
                    <span class="block text-5xl font-black tracking-tighter" x-data="{ count: 0 }" x-init="setInterval(() => { if(count < 1200) count+=10 }, 20)">
                        <span x-text="count">1200</span>+
                    </span>
                    <span class="text-sm font-bold text-blue-200 uppercase tracking-widest">Mahasiswa Aktif</span>
                </div>
                <div class="space-y-2">
                    <span class="block text-5xl font-black tracking-tighter">450+</span>
                    <span class="text-sm font-bold text-blue-200 uppercase tracking-widest">Lokasi Tersebar</span>
                </div>
                <div class="space-y-2">
                    <span class="block text-5xl font-black tracking-tighter">80+</span>
                    <span class="text-sm font-bold text-blue-200 uppercase tracking-widest">Dosen Pembimbing</span>
                </div>
                <div class="space-y-2">
                    <span class="block text-5xl font-black tracking-tighter">99%</span>
                    <span class="text-sm font-bold text-blue-200 uppercase tracking-widest">Kepuasan Sistem</span>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section id="faq" class="py-32 bg-white" x-data="{ active: null }">
            <div class="max-w-3xl mx-auto px-6">
                <div class="text-center mb-16 space-y-4">
                    <h2 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em]">Pertanyaan Umum</h2>
                    <h3 class="text-4xl font-extrabold text-gray-900 tracking-tight">Sering Ditanyakan</h3>
                </div>

                <div class="space-y-4">
                    <div class="border border-gray-100 rounded-2xl overflow-hidden">
                        <button @click="active === 1 ? active = null : active = 1" class="w-full flex items-center justify-between p-6 bg-gray-50 hover:bg-white transition-colors text-left focus:outline-none">
                            <span class="font-bold text-gray-800">Bagaimana cara mendaftar akun mahasiswa?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform" :class="active === 1 ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="active === 1" class="p-6 bg-white text-gray-500 text-sm border-t border-gray-100">
                            Klik tombol "Daftar Sekarang" di pojok kanan atas, isi formulir dengan NIM dan data diri yang valid, lalu tunggu verifikasi.
                        </div>
                    </div>

                    <div class="border border-gray-100 rounded-2xl overflow-hidden">
                        <button @click="active === 2 ? active = null : active = 2" class="w-full flex items-center justify-between p-6 bg-gray-50 hover:bg-white transition-colors text-left focus:outline-none">
                            <span class="font-bold text-gray-800">Apakah saya bisa memilih lokasi KKN sendiri?</span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform" :class="active === 2 ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="active === 2" class="p-6 bg-white text-gray-500 text-sm border-t border-gray-100">
                            Lokasi KKN ditentukan oleh panitia berdasarkan kuota dan persebaran program studi untuk pemerataan.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-32 px-6">
            <div class="max-w-5xl mx-auto bg-gray-900 rounded-[3rem] p-12 lg:p-24 text-center text-white relative overflow-hidden">
                <div class="relative z-10 space-y-8">
                    <h2 class="text-4xl lg:text-5xl font-black tracking-tight leading-tight text-white">Siap Memulai Pengalaman Luar Kampus?</h2>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                        <a href="{{ route('register.form') }}" class="w-full sm:w-auto px-10 py-5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all shadow-xl shadow-blue-900">
                            Buat Akun Sekarang
                        </a>
                        <a href="{{ route('login') }}" class="w-full sm:w-auto px-10 py-5 bg-white/10 hover:bg-white/20 text-white font-bold rounded-2xl transition-all backdrop-blur-sm">
                            Masuk Kembali
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-white pt-24 pb-12 border-t border-gray-100">
            <div class="md:col-span-1 space-y-6 text-center lg:text-left">
                <div class="flex items-center justify-center lg:justify-start space-x-3">
                    <img src="https://markandeyabali.ac.id/wp-content/uploads/2023/06/cropped-cropped-logo-1.png" alt="Logo Markandeya" class="h-10 w-auto">
                    <span class="font-black text-2xl tracking-tighter italic text-gray-900">Sinergi<span class="text-blue-600">.</span></span>
                </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em]">
                &copy; {{ date('Y') }} Institut Teknologi dan Pendidikan Markandeya Bali.
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
