<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sinergi Markandeya')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; background-color: #f5e6d3; color: #1a5d4d; }
        </style>
    @endif
</head>
<body>

    <!-- Navbar with Markandeya Theme -->
    <nav class="sticky top-0 z-50 bg-primary text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2 font-bold text-xl hover:opacity-80 transition">
                <img src="{{ asset('logo-universitas-markandeya.png') }}" alt="Logo" style="width: 40px; height: 40px; object-fit: contain;">
                <span class="hidden sm:inline">Sinergi Markandeya</span>
            </a>

            <div class="flex items-center gap-4">
                <a href="{{ url('/') }}" class="hover:text-gold transition">Beranda</a>
                <a href="{{ route('register.form') }}" class="hover:text-gold transition">Pendaftaran</a>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-gold text-primary font-semibold rounded-lg hover:bg-gold-600 transition">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="hover:text-gold transition">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 border-2 border-gold text-gold hover:bg-gold hover:text-primary transition rounded-lg font-semibold">
                            Login
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-primary text-cream py-8">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-sm opacity-90">&copy; 2025 Sinergi KKN, PPL, PKL - Universitas Markandeya</p>
            <p class="text-xs opacity-75 mt-2">Pusat Literasi dan Pengetahuan untuk Civitas Akademika</p>
        </div>
    </footer>

</body>
</html>
