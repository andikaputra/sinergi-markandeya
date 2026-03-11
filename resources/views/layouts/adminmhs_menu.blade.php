<a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200">
    <i class="fas fa-home text-lg"></i>
    <span class="font-medium text-sm">Beranda</span>
</a>

<div class="pt-4 pb-2">
    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Aktivitas</p>
</div>

<a href="{{ route('jurnal.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200">
    <i class="fas fa-book text-lg"></i>
    <span class="font-medium text-sm">Jurnal Harian</span>
</a>
<a href="{{ route('publikasi.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200">
    <i class="fas fa-paper-plane text-lg"></i>
    <span class="font-medium text-sm">Publikasi</span>
</a>

@if(Auth::guard('mahasiswa')->check() && Auth::guard('mahasiswa')->user()->kegiatan == 'PKL')
<div class="pt-4 pb-2">
    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Khusus PKL</p>
</div>
<a href="{{ route('pengajuanpkl.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200">
    <i class="fas fa-file-signature text-lg"></i>
    <span class="font-medium text-sm">Pengajuan Lokasi</span>
</a>
@endif

<div class="pt-4 pb-2">
    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Akun</p>
</div>
<a href="{{ route('password.change') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200">
    <i class="fas fa-user-lock text-lg"></i>
    <span class="font-medium text-sm">Ubah Password</span>
</a>
