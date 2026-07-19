<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sinergi Markandeya - Sistem Manajemen KKN, PPL, PKL</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; background-color: #1a5d4d; color: #ffffff; line-height: 1.6; }
            a { text-decoration: none; color: inherit; }
            button { cursor: pointer; border: none; }
            .btn-gold { background-color: #d4a574; color: #1a5d4d; padding: 12px 32px; border-radius: 8px; font-weight: 600; transition: all 0.3s; }
            .btn-gold:hover { background-color: #c9905c; }
            h1 { font-size: 3.5rem; font-weight: 700; margin-bottom: 20px; line-height: 1.2; }
            h2 { font-size: 2rem; font-weight: 700; margin-bottom: 16px; }
            .section { padding: 80px 40px; }
            .container { max-width: 1200px; margin: 0 auto; }
        </style>
    @endif
</head>
<body style="background-color: #1a5d4d;">
    <!-- Navigation -->
    <nav style="background-color: #1a5d4d; padding: 20px 40px; border-bottom: 2px solid #d4a574; position: sticky; top: 0; z-index: 100;">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 12px; font-size: 1.3rem; font-weight: 700;">
                <span style="font-size: 2rem;">📚</span>
                <div>
                    <div style="color: #d4a574;">Markandeya Pustaka</div>
                    <div style="font-size: 0.85rem; color: #f5e6d3;">Universitas Markandeya</div>
                </div>
            </div>
            <div style="display: flex; gap: 20px; align-items: center;">
                <a href="{{ url('/') }}" style="color: #f5e6d3; hover-color: #d4a574; transition: 0.3s;">Beranda</a>
                <a href="{{ route('register.form') }}" style="color: #f5e6d3; hover-color: #d4a574; transition: 0.3s;">Daftar</a>
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-gold">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-gold">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="section" style="padding: 120px 40px; position: relative; overflow: hidden;">
        <div class="container">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center;">
                <!-- Left Content -->
                <div>
                    <h1 style="color: #ffffff; margin-bottom: 20px;">
                        Sumber Ilmu.<br/>Penggerak Peradaban.
                    </h1>
                    <p style="color: #f5e6d3; font-size: 1.1rem; margin-bottom: 30px; line-height: 1.8;">
                        Sinergi Markandeya hadir sebagai pusat literasi dan pengetahuan untuk mendukung pembelajaran, penelitian, dan pengabdian bagi seluruh civitas akademika dan masyarakat.
                    </p>
                    <a href="{{ route('register.form') }}" class="btn-gold" style="display: inline-block;">
                        Jelajahi Program →
                    </a>
                </div>

                <!-- Right Content - Features -->
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div style="background-color: rgba(212, 165, 116, 0.1); padding: 20px; border-left: 4px solid #d4a574; border-radius: 8px;">
                        <div style="color: #d4a574; font-weight: 700; margin-bottom: 8px;">📖 Koleksi Lengkap</div>
                        <p style="color: #f5e6d3; font-size: 0.95rem;">Bergam koleksi buku dan sumber terpercaya untuk semua kebutuhan akademik.</p>
                    </div>

                    <div style="background-color: rgba(212, 165, 116, 0.1); padding: 20px; border-left: 4px solid #d4a574; border-radius: 8px;">
                        <div style="color: #d4a574; font-weight: 700; margin-bottom: 8px;">🔍 Akses Mudah</div>
                        <p style="color: #f5e6d3; font-size: 0.95rem;">Temukan informasi dengan cepat melalui sistem pencarian yang efisien.</p>
                    </div>

                    <div style="background-color: rgba(212, 165, 116, 0.1); padding: 20px; border-left: 4px solid #d4a574; border-radius: 8px;">
                        <div style="color: #d4a574; font-weight: 700; margin-bottom: 8px;">👥 Untuk Semua</div>
                        <p style="color: #f5e6d3; font-size: 0.95rem;">Mahasiswa, dosen, peneliti, dan masyarakat umum dapat mengakses.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Decorative Element -->
        <div style="position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; background-color: rgba(212, 165, 116, 0.1); border-radius: 50%; z-index: -1;"></div>
    </section>

    <!-- Programs Section -->
    <section class="section" style="background-color: rgba(212, 165, 116, 0.05); padding: 80px 40px;">
        <div class="container">
            <h2 style="text-align: center; color: #d4a574; margin-bottom: 60px;">Program & Layanan Kami</h2>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px;">
                <div style="background-color: #1a5d4d; padding: 30px; border-radius: 12px; border-top: 4px solid #d4a574; text-align: center;">
                    <div style="font-size: 3rem; margin-bottom: 15px;">🎓</div>
                    <h3 style="color: #d4a574; margin-bottom: 12px;">KKN</h3>
                    <p style="color: #f5e6d3; font-size: 0.95rem;">Kuliah Kerja Nyata - Pengabdian kepada masyarakat lokal.</p>
                </div>

                <div style="background-color: #1a5d4d; padding: 30px; border-radius: 12px; border-top: 4px solid #d4a574; text-align: center;">
                    <div style="font-size: 3rem; margin-bottom: 15px;">💼</div>
                    <h3 style="color: #d4a574; margin-bottom: 12px;">PPL</h3>
                    <p style="color: #f5e6d3; font-size: 0.95rem;">Praktik Pengalaman Lapangan - Pengalaman praktis profesional.</p>
                </div>

                <div style="background-color: #1a5d4d; padding: 30px; border-radius: 12px; border-top: 4px solid #d4a574; text-align: center;">
                    <div style="font-size: 3rem; margin-bottom: 15px;">🏢</div>
                    <h3 style="color: #d4a574; margin-bottom: 12px;">PKL</h3>
                    <p style="color: #f5e6d3; font-size: 0.95rem;">Praktik Kerja Lapangan - Pelatihan industri berkualitas.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section" style="padding: 80px 40px; text-align: center;">
        <div class="container">
            <h2 style="margin-bottom: 20px;">Bergabunglah dengan Sinergi Markandeya</h2>
            <p style="color: #f5e6d3; font-size: 1.1rem; margin-bottom: 40px; max-width: 600px; margin-left: auto; margin-right: auto;">
                Tingkatkan pengetahuan, kembangkan keterampilan, dan berkontribusi untuk masa depan yang lebih baik.
            </p>
            <a href="{{ route('register.form') }}" class="btn-gold" style="display: inline-block;">
                Daftar Sekarang
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer style="background-color: #0f2d26; padding: 40px; text-align: center; border-top: 2px solid #d4a574;">
        <div class="container">
            <p style="color: #f5e6d3; margin-bottom: 10px;">
                &copy; 2025 Sinergi Markandeya - Universitas Markandeya
            </p>
            <p style="color: #d4a574; font-size: 0.9rem;">
                Pusat Literasi dan Pengetahuan untuk Civitas Akademika dan Masyarakat
            </p>
        </div>
    </footer>
</body>
</html>
