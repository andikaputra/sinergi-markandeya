<a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200">
    <i class="fas fa-home text-lg"></i>
    <span class="font-medium text-sm">Beranda</span>
</a>

@php
    $jumlahPendaftaranTerbuka = \App\Models\TahunAkademik::whereNotNull('tanggal_mulai_daftar')
        ->whereNotNull('tanggal_selesai_daftar')
        ->where('tanggal_mulai_daftar', '<=', now()->toDateString())
        ->where('tanggal_selesai_daftar', '>=', now()->toDateString())
        ->count();
@endphp
<a href="{{ route('mahasiswa.daftar-kegiatan.page') }}" class="flex items-center justify-between px-4 py-3 rounded-xl text-gray-600 hover:bg-gold-50 hover:text-gold-600 transition-all duration-200">
    <div class="flex items-center space-x-3">
        <i class="fas fa-clipboard-list text-lg"></i>
        <span class="font-medium text-sm">Daftar Kegiatan</span>
    </div>
    @if($jumlahPendaftaranTerbuka > 0)
    <span class="px-2 py-0.5 bg-gold-500 text-white text-[10px] font-black rounded-full animate-pulse">Buka</span>
    @endif
</a>

<div class="pt-4 pb-2">
    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Aktivitas</p>
</div>

<a href="{{ route('jurnal.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200">
    <i class="fas fa-book text-lg"></i>
    <span class="font-medium text-sm">Jurnal Harian</span>
</a>
<a href="{{ route('publikasi.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200">
    <i class="fas fa-paper-plane text-lg"></i>
    <span class="font-medium text-sm">Publikasi</span>
</a>
@php
    $kegiatanAktif = Auth::guard('mahasiswa')->user()->kegiatan ?? null;
    $labelTeman = match($kegiatanAktif) {
        'KKN'    => 'Teman Se-Desa',
        'PPL'    => 'Teman Se-Lokasi',
        'PKL'    => 'Teman Se-Lokasi PKL',
        'Magang' => 'Teman Se-Lokasi Magang',
        default  => null,
    };
@endphp
@if($labelTeman)
<a href="{{ route('mahasiswa.teman-selokasi') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200">
    <i class="fas fa-users text-lg"></i>
    <span class="font-medium text-sm">{{ $labelTeman }}</span>
</a>
@endif

@if(Auth::guard('mahasiswa')->check() && in_array(Auth::guard('mahasiswa')->user()->kegiatan, ['PKL', 'Magang']))
<div class="pt-4 pb-2">
    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Pengajuan Lokasi</p>
</div>
@if(Auth::guard('mahasiswa')->user()->kegiatan == 'PKL')
<a href="{{ route('pengajuanpkl.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200">
    <i class="fas fa-file-signature text-lg"></i>
    <span class="font-medium text-sm">Pengajuan Lokasi PKL</span>
</a>
@endif
@if(Auth::guard('mahasiswa')->user()->kegiatan == 'Magang')
<a href="{{ route('pengajuanmagang.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200">
    <i class="fas fa-file-signature text-lg"></i>
    <span class="font-medium text-sm">Pengajuan Lokasi Magang</span>
</a>
@endif
@endif

@php $jumlahNotifSidebar = \App\Models\Notifikasi::jumlahBelumDibaca(Auth::guard('mahasiswa')->user()->nim ?? ''); @endphp
<div class="pt-4 pb-2">
    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Informasi</p>
</div>
<a href="{{ route('mahasiswa.notifikasi') }}" class="flex items-center justify-between px-4 py-3 rounded-xl text-gray-600 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200">
    <div class="flex items-center space-x-3">
        <i class="fas fa-bell text-lg"></i>
        <span class="font-medium text-sm">Notifikasi</span>
    </div>
    @if($jumlahNotifSidebar > 0)
    <span class="px-2 py-0.5 bg-red-500 text-white text-[10px] font-black rounded-full">{{ $jumlahNotifSidebar > 9 ? '9+' : $jumlahNotifSidebar }}</span>
    @endif
</a>

<div class="pt-4 pb-2">
    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Akun</p>
</div>
<a href="{{ route('mahasiswa.profil.edit') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200">
    <i class="fas fa-user-edit text-lg"></i>
    <span class="font-medium text-sm">Edit Profil</span>
</a>
<a href="{{ route('password.change') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200">
    <i class="fas fa-user-lock text-lg"></i>
    <span class="font-medium text-sm">Ubah Password</span>
</a>
