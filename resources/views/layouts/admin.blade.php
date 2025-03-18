<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin')</title>

    <!-- Bootstrap Admin Template -->
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>
<body id="page-top">

    <!-- Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <div class="sidebar-brand-text mx-3">Admin</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Dashboard Link -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admindashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <!-- Peserta Nav -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Data Peserta</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar" style="">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{ route('admin.peserta.kkn') }}">Peserta KKN</a>
                        <a class="collapse-item" href="{{ route('admin.peserta.ppl') }}">Peserta PPL</a>
                        <a class="collapse-item" href="{{ route('admin.peserta.pkl') }}">Peserta PKL</a>
                    </div>
                </div>
            </li>
            <!-- Dosen Nav -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages1" aria-expanded="false" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Pengaturan Dosen</span>
                </a>
                <div id="collapsePages1" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar" style="">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{ route('dosen.index') }}">Tambah Dosen</a>
                        <a class="collapse-item" href="{{ route('assign.dosen') }}">Atur Dospem</a>
                    </div>
                </div>
            </li>

            <!-- KKN Nav -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages3" aria-expanded="false" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Pengaturan Lokasi</span>
                </a>
                <div id="collapsePages3" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar" style="">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{ route('lokasikkn.index') }}">Tambah Lokasi KKN</a>
                        <a class="collapse-item" href="{{ route('assign.lokasikkn') }}">Penempatan KKN</a>
                        <a class="collapse-item" href="{{ route('lokasippl.index') }}">Tambah Sekolah PPL</a>
                        <a class="collapse-item" href="{{ route('assign.lokasippl') }}">Penempatan PPL</a>
                    </div>
                </div>
            </li>


            <!-- Logout -->
            <li class="nav-item">
                <form method="POST" action="{{ route('logoutadmin') }}">
                    @csrf
                    <button type="submit" class="nav-link btn btn-danger w-100 text-left">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <h3 class="text-dark">@yield('title')</h3>
                </nav>
                <!-- End of Topbar -->

                <!-- Main Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap & Admin JS -->
    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>
</body>
</html>
