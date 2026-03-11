<a href="{{ route('admindashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200">
    <i class="fas fa-chart-line text-lg"></i>
    <span class="font-medium text-sm">Dashboard</span>
</a>

<a href="{{ route('tahun_akademik.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 transition-all duration-200">
    <i class="fas fa-calendar-check text-lg"></i>
    <span class="font-medium text-sm">Tahun Akademik</span>
</a>

<div class="pt-4 pb-2">
    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Manajemen Peserta</p>
</div>

<a href="{{ route('admin.peserta.kkn') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200">
    <i class="fas fa-users text-lg"></i>
    <span class="font-medium text-sm">Peserta KKN</span>
</a>
<a href="{{ route('admin.peserta.ppl') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200">
    <i class="fas fa-user-graduate text-lg"></i>
    <span class="font-medium text-sm">Peserta PPL</span>
</a>
<a href="{{ route('admin.peserta.pkl') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200">
    <i class="fas fa-user-tie text-lg"></i>
    <span class="font-medium text-sm">Peserta PKL</span>
</a>
<a href="{{ route('admin.peserta.magang') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200">
    <i class="fas fa-briefcase text-lg"></i>
    <span class="font-medium text-sm">Peserta Magang</span>
</a>

<div class="pt-4 pb-2">
    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Dosen & Lokasi</p>
</div>

<a href="{{ route('dosen.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200">
    <i class="fas fa-chalkboard-teacher text-lg"></i>
    <span class="font-medium text-sm">Data Dosen</span>
</a>

<div x-data="{ open: false }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200">
        <div class="flex items-center space-x-3">
            <i class="fas fa-map-marked-alt text-lg"></i>
            <span class="font-medium text-sm">Pengaturan Lokasi</span>
        </div>
        <i class="fas fa-chevron-down text-[10px] transform transition-transform" :class="open ? 'rotate-180' : ''"></i>
    </button>
    <div x-show="open" class="pl-12 space-y-1 border-l-2 border-slate-50 ml-6">
        <a href="{{ route('lokasikkn.index') }}" class="block py-2 text-sm text-gray-500 hover:text-blue-600">Master Lokasi KKN</a>
        <a href="{{ route('lokasippl.index') }}" class="block py-2 text-sm text-gray-500 hover:text-blue-600">Master Sekolah PPL</a>
        <a href="{{ route('lokasipkl.index') }}" class="block py-2 text-sm text-gray-500 hover:text-blue-600">Master Instansi PKL</a>
        <div class="h-px bg-slate-50 my-2 mr-4"></div>
        <a href="{{ route('assign.lokasikkn') }}" class="block py-2 text-sm text-gray-500 hover:text-blue-600">Penempatan KKN</a>
        <a href="{{ route('assign.lokasippl') }}" class="block py-2 text-sm text-gray-500 hover:text-blue-600">Penempatan PPL</a>
        <a href="{{ route('assign.lokasipkl') }}" class="block py-2 text-sm text-gray-500 hover:text-blue-600">Penempatan PKL</a>
        <a href="{{ route('assign.lokasimagang') }}" class="block py-2 text-sm text-gray-500 hover:text-blue-600">Penempatan Magang</a>
        <a href="{{ route('pengajuanpkl.adminindex') }}" class="block py-2 text-sm text-gray-500 hover:text-blue-600">Persetujuan PKL</a>
    </div>
</div>

<div x-data="{ open: false }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200">
        <div class="flex items-center space-x-3">
            <i class="fas fa-user-tie text-lg"></i>
            <span class="font-medium text-sm">Plotting Dosen</span>
        </div>
        <i class="fas fa-chevron-down text-[10px] transform transition-transform" :class="open ? 'rotate-180' : ''"></i>
    </button>
    <div x-show="open" class="pl-12 space-y-1 border-l-2 border-slate-50 ml-6">
        <a href="{{ route('assign.dosenkkn') }}" class="block py-2 text-sm text-gray-500 hover:text-blue-600">Plot Dosen KKN</a>
        <a href="{{ route('assign.dosenppl') }}" class="block py-2 text-sm text-gray-500 hover:text-blue-600">Plot Dosen PPL</a>
        <a href="{{ route('assign.dosenpkl') }}" class="block py-2 text-sm text-gray-500 hover:text-blue-600">Plot Dosen PKL</a>
        <a href="{{ route('assign.dosenmagang') }}" class="block py-2 text-sm text-gray-500 hover:text-blue-600">Plot Dosen Magang</a>
        <a href="{{ route('assign.dosenpenguji') }}" class="block py-2 text-sm text-gray-500 hover:text-blue-600">Plot Dosen Penguji</a>
    </div>
</div>

<div class="pt-4 pb-2">
    <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Pengaturan</p>
</div>
<a href="{{ route('password.change') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200">
    <i class="fas fa-shield-alt text-lg"></i>
    <span class="font-medium text-sm">Ubah Password</span>
</a>
