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

<!-- Import Mahasiswa CSV -->
<div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="flex-1">
            <h5 class="text-sm font-bold text-gray-800 flex items-center mb-1">
                <i class="fas fa-file-csv text-emerald-500 mr-2"></i> Import Mahasiswa via CSV
            </h5>
            <p class="text-xs text-gray-400">Format: <code class="bg-gray-100 px-1.5 py-0.5 rounded text-gray-600 font-mono">nama, nim, email, prodi, kegiatan, kecamatan, kampus</code> &mdash; Password default = NIM</p>
        </div>
        <form action="{{ route('admin.import.mahasiswa') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
            @csrf
            <input type="file" name="file_csv" accept=".csv,.txt" required class="block text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-blue-50 file:text-blue-600 cursor-pointer hover:file:bg-blue-100">
            <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black transition-all whitespace-nowrap shadow-sm">
                <i class="fas fa-upload mr-1"></i> Upload
            </button>
        </form>
    </div>
</div>
