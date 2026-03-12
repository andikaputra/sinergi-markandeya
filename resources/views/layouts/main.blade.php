<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sinergi Markandeya')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
    
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" crossorigin="anonymous" referrerpolicy="no-referrer">
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    
    <!-- DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    
    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
        
        /* Custom Scrollbar for Sidebar */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }

        /* Sidebar Active State - Matching Landing Page Theme */
        .sidebar-active {
            @apply bg-blue-50 text-blue-700 font-bold border-r-4 border-blue-600;
        }
        .sidebar-active i {
            @apply text-blue-600;
        }

        /* DataTable Customization to match theme */
        .dataTables_wrapper .dataTables_length select {
            @apply border-gray-200 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 !important;
        }
        .dataTables_wrapper .dataTables_filter input {
            @apply border-gray-200 rounded-lg text-sm px-3 py-1 focus:ring-blue-500 focus:border-blue-500 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            @apply bg-blue-600 text-white border-blue-600 rounded-lg font-bold !important;
        }
    </style>
</head>
<body class="h-full text-slate-600 flex flex-col lg:flex-row overflow-hidden">

    <!-- Mobile Header -->
    <div class="lg:hidden flex items-center justify-between px-6 py-4 bg-white border-b border-gray-100 z-50">
        <div class="flex items-center space-x-3">
            <img src="https://markandeyabali.ac.id/wp-content/uploads/2023/06/cropped-cropped-logo-1.png" alt="Logo Markandeya" class="h-10 w-auto">
            <span class="font-black text-xl tracking-tight text-gray-900">Sinergi<span class="text-blue-600">.</span></span>
        </div>
        <button id="mobile-menu-button" class="text-gray-500 hover:text-blue-600 transition-colors">
            <i class="fas fa-bars text-2xl"></i>
        </button>
    </div>

    <!-- Sidebar -->
    <aside id="sidebar" class="hidden lg:flex flex-col w-72 bg-white border-r border-gray-100 h-full fixed lg:static z-50 transition-transform duration-300 transform -translate-x-full lg:translate-x-0 shadow-xl lg:shadow-none">
        <!-- Brand Logo -->
        <div class="px-8 py-8 flex items-center space-x-3">
            <img src="https://markandeyabali.ac.id/wp-content/uploads/2023/06/cropped-cropped-logo-1.png" alt="Logo Markandeya" class="h-12 w-auto">
            <div>
                <h1 class="font-black text-2xl tracking-tighter text-gray-900 leading-none">Sinergi<span class="text-blue-600">.</span></h1>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">@yield('user_type', 'Panel')</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 space-y-1 overflow-y-auto sidebar-scroll py-2">
            @if(Auth::guard('mahasiswa')->check())
                @include('layouts.adminmhs_menu')
            @elseif(Auth::guard('dosen')->check())
                @include('layouts.dosen_menu')
            @else
                @include('layouts.admin_menu')
            @endif
        </nav>

        <!-- User Profile (Bottom) -->
        <div class="p-6 border-t border-gray-50">
            <div class="bg-slate-50 p-4 rounded-2xl border border-gray-100 flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-blue-600 font-bold shadow-sm">
                    {{ substr(Auth::user()->nama ?? Auth::user()->name ?? 'U', 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->nama ?? Auth::user()->name ?? 'User' }}</p>
                    <p class="text-xs text-gray-400 truncate font-medium">@yield('user_type', 'Panel')</p>
                </div>
            </div>
            <form method="POST" action="@yield('logout_route')">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:bg-red-50 hover:text-red-600 hover:border-red-100 transition-all duration-200 group">
                    <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i>
                    <span>Keluar Aplikasi</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Overlay for Mobile -->
    <div id="sidebar-overlay" class="hidden fixed inset-0 bg-gray-900/20 backdrop-blur-sm z-40 transition-opacity lg:hidden"></div>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full min-w-0 bg-slate-50 relative">
        <!-- Topbar -->
        <header class="hidden lg:flex items-center justify-between px-10 py-6 bg-white/80 backdrop-blur-md sticky top-0 z-30 border-b border-gray-100">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">@yield('title')</h2>
                <p class="text-sm text-gray-400 font-medium">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="h-10 w-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 hover:border-blue-200 transition-all cursor-pointer shadow-sm">
                    <i class="far fa-bell"></i>
                </div>
            </div>
        </header>

        <!-- Page Content Scrollable -->
        <div class="flex-1 overflow-y-auto p-6 lg:p-10">
            <div class="max-w-7xl mx-auto space-y-8">
                @yield('content')
            </div>
        </div>
        
        <div class="px-10 py-6 text-center lg:text-left text-xs font-bold text-gray-300 uppercase tracking-widest">
            &copy; {{ date('Y') }} Sinergi Markandeya System.
        </div>
    </main>

    <script>
        $(document).ready(function() {
            // Mobile Sidebar Logic
            const $sidebar = $('#sidebar');
            const $overlay = $('#sidebar-overlay');
            const $btn = $('#mobile-menu-button');

            function toggleSidebar() {
                $sidebar.toggleClass('hidden flex -translate-x-full translate-x-0');
                $overlay.toggleClass('hidden');
            }

            $btn.on('click', toggleSidebar);
            $overlay.on('click', toggleSidebar);

            // Active State Logic
            const currentPath = window.location.href;
            $('nav a').each(function() {
                if (this.href === currentPath) {
                    $(this).addClass('sidebar-active');
                }
            });
        });
    </script>
</body>
</html>
