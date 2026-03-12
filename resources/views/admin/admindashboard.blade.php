@extends('layouts.admin')

@section('title', 'Beranda Admin')

@section('content')
<div class="space-y-8">
    @if(session('success'))
    <div class="p-5 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-700 text-sm font-bold flex items-center">
        <i class="fas fa-check-circle mr-3 text-lg"></i>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="p-5 bg-red-50 border border-red-200 rounded-2xl text-red-700 text-sm font-bold flex items-center">
        <i class="fas fa-exclamation-triangle mr-3 text-lg"></i>
        {{ session('error') }}
    </div>
    @endif

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Card KKN -->
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col justify-between transition-all duration-300 hover:shadow-lg hover:border-blue-100 group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full blur-3xl -mr-16 -mt-16 transition-all group-hover:bg-blue-100"></div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div class="bg-blue-50 p-4 rounded-2xl text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <span class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full uppercase tracking-wider">KKN</span>
            </div>
            <div class="relative z-10">
                <h3 class="text-4xl font-black text-gray-800 mb-1 tracking-tight">{{ $jumlahKKN }}</h3>
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest text-xs">Peserta</p>
            </div>
        </div>

        <!-- Card PPL -->
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col justify-between transition-all duration-300 hover:shadow-lg hover:border-emerald-100 group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-full blur-3xl -mr-16 -mt-16 transition-all group-hover:bg-emerald-100"></div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div class="bg-emerald-50 p-4 rounded-2xl text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                    <i class="fas fa-chalkboard-teacher text-2xl"></i>
                </div>
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full uppercase tracking-wider">PPL</span>
            </div>
            <div class="relative z-10">
                <h3 class="text-4xl font-black text-gray-800 mb-1 tracking-tight">{{ $jumlahPPL }}</h3>
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest text-xs">Peserta</p>
            </div>
        </div>

        <!-- Card PKL -->
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col justify-between transition-all duration-300 hover:shadow-lg hover:border-amber-100 group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-amber-50 rounded-full blur-3xl -mr-16 -mt-16 transition-all group-hover:bg-amber-100"></div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div class="bg-amber-50 p-4 rounded-2xl text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                    <i class="fas fa-building text-2xl"></i>
                </div>
                <span class="text-xs font-bold text-amber-600 bg-amber-50 px-3 py-1 rounded-full uppercase tracking-wider">PKL</span>
            </div>
            <div class="relative z-10">
                <h3 class="text-4xl font-black text-gray-800 mb-1 tracking-tight">{{ $jumlahPKL }}</h3>
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest text-xs">Peserta</p>
            </div>
        </div>

        <!-- Card Magang -->
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col justify-between transition-all duration-300 hover:shadow-lg hover:border-indigo-100 group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-full blur-3xl -mr-16 -mt-16 transition-all group-hover:bg-indigo-100"></div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div class="bg-indigo-50 p-4 rounded-2xl text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                    <i class="fas fa-briefcase text-2xl"></i>
                </div>
                <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full uppercase tracking-wider">Magang</span>
            </div>
            <div class="relative z-10">
                <h3 class="text-4xl font-black text-gray-800 mb-1 tracking-tight">{{ $jumlahMagang }}</h3>
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest text-xs">Peserta</p>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Banner & Import -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-gradient-to-br from-blue-600 to-indigo-800 p-10 rounded-[2.5rem] shadow-xl text-white relative overflow-hidden flex flex-col justify-between min-h-[300px]">
                <div class="relative z-10">
                    <h4 class="text-3xl font-black mb-4 tracking-tight">Portal Sinergi Admin</h4>
                    <p class="text-blue-100 opacity-80 mb-8 max-w-md">Gunakan dashboard ini untuk memantau pendaftaran, melakukan plotting dosen, dan mengelola database mahasiswa Markandeya Bali.</p>

                    <div class="grid grid-cols-2 gap-3 max-w-lg">
                        <a href="{{ route('admin.peserta.kkn') }}" class="flex items-center p-4 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10 hover:bg-white/20 transition-all group">
                            <i class="fas fa-users text-lg mr-3 group-hover:scale-110 transition-transform"></i>
                            <span class="text-xs font-black uppercase tracking-wider">Peserta KKN</span>
                        </a>
                        <a href="{{ route('admin.peserta.ppl') }}" class="flex items-center p-4 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10 hover:bg-white/20 transition-all group">
                            <i class="fas fa-chalkboard-teacher text-lg mr-3 group-hover:scale-110 transition-transform"></i>
                            <span class="text-xs font-black uppercase tracking-wider">Peserta PPL</span>
                        </a>
                        <a href="{{ route('admin.peserta.pkl') }}" class="flex items-center p-4 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10 hover:bg-white/20 transition-all group">
                            <i class="fas fa-building text-lg mr-3 group-hover:scale-110 transition-transform"></i>
                            <span class="text-xs font-black uppercase tracking-wider">Peserta PKL</span>
                        </a>
                        <a href="{{ route('admin.peserta.magang') }}" class="flex items-center p-4 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10 hover:bg-white/20 transition-all group">
                            <i class="fas fa-briefcase text-lg mr-3 group-hover:scale-110 transition-transform"></i>
                            <span class="text-xs font-black uppercase tracking-wider">Peserta Magang</span>
                        </a>
                    </div>
                </div>
                <div class="absolute -bottom-24 -right-24 w-80 h-80 bg-white/10 rounded-full blur-[80px]"></div>
            </div>

            <!-- Chart Card -->
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                <h4 class="text-lg font-bold text-gray-800 mb-6 italic">Distribusi Mahasiswa per Program</h4>
                <div class="h-[350px]">
                    <canvas id="programChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-8">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                <h4 class="text-lg font-bold text-gray-800 mb-6">Quick Actions</h4>
                <div class="grid grid-cols-1 gap-3">
                    <a href="{{ route('tahun_akademik.index') }}" class="flex items-center p-4 bg-slate-50 rounded-2xl hover:bg-blue-50 transition-colors group">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-blue-600 shadow-sm mr-4">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <span class="text-sm font-bold text-gray-600 group-hover:text-blue-700">Set Periode Aktif</span>
                    </a>
                    <a href="{{ route('admin.mahasiswa.create') }}" class="flex items-center p-4 bg-slate-50 rounded-2xl hover:bg-blue-50 transition-colors group">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-blue-600 shadow-sm mr-4">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <span class="text-sm font-bold text-gray-600 group-hover:text-blue-700">Tambah Mahasiswa</span>
                    </a>
                    <a href="{{ route('dosen.create') }}" class="flex items-center p-4 bg-slate-50 rounded-2xl hover:bg-emerald-50 transition-colors group">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-emerald-600 shadow-sm mr-4">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <span class="text-sm font-bold text-gray-600 group-hover:text-emerald-700">Tambah Dosen</span>
                    </a>
                    <a href="{{ route('dosen.index') }}" class="flex items-center p-4 bg-slate-50 rounded-2xl hover:bg-indigo-50 transition-colors group">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-600 shadow-sm mr-4">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <span class="text-sm font-bold text-gray-600 group-hover:text-indigo-700">Kelola Dosen</span>
                    </a>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                            <i class="fas fa-headset text-xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-800">Butuh Bantuan?</h4>
                    </div>
                    <p class="text-sm text-gray-500 leading-relaxed mb-6">Hubungi tim IT Markandeya jika Anda mengalami kendala pada sistem plotting atau sinkronisasi data.</p>
                    <a href="mailto:it@markandeyabali.ac.id" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black transition-all uppercase tracking-widest shadow-lg shadow-blue-200">
                        <i class="fas fa-envelope mr-2"></i>Kontak Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('programChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['KKN', 'PPL', 'PKL', 'Magang'],
                datasets: [{
                    data: [{{ $jumlahKKN }}, {{ $jumlahPPL }}, {{ $jumlahPKL }}, {{ $jumlahMagang }}],
                    backgroundColor: ['#2563eb', '#10b981', '#f59e0b', '#6366f1'],
                    borderWidth: 0,
                    hoverOffset: 20
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 30, font: { weight: 'bold', size: 12 } } }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endsection
