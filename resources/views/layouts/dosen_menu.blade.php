<a href="{{ route('dosen.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200">
    <i class="fas fa-home text-lg"></i>
    <span class="font-medium text-sm">Beranda</span>
</a>

<div class="pt-4 pb-2">
    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Bimbingan</p>
</div>

<a href="{{ route('dosen.bimbingan') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200">
    <i class="fas fa-users text-lg"></i>
    <span class="font-medium text-sm">Mahasiswa Bimbingan</span>
</a>

<div class="pt-4 pb-2">
    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Pengujian</p>
</div>

<a href="{{ route('dosen.ujian.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200">
    <i class="fas fa-gavel text-lg"></i>
    <span class="font-medium text-sm">Mahasiswa Ujian</span>
</a>

<div class="pt-4 pb-2">
    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Publikasi & Diseminasi</p>
</div>

<a href="{{ route('dosen.publikasi.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-purple-50 hover:text-purple-600 transition-all duration-200">
    <i class="fas fa-book-reader text-lg"></i>
    <span class="font-medium text-sm">Penilaian Publikasi</span>
</a>

<div class="pt-4 pb-2">
    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Akun</p>
</div>
<a href="{{ route('password.change') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200">
    <i class="fas fa-user-lock text-lg"></i>
    <span class="font-medium text-sm">Ubah Password</span>
</a>
