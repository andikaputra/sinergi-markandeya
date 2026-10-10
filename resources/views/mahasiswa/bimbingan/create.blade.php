@extends('layouts.adminmhs')

@section('title', 'Ajukan Bimbingan Baru - ' . ($mahasiswa->kegiatan ?? 'Kegiatan'))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center space-x-4">
        <a href="{{ route('bimbingan.dashboard') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-2xl flex items-center justify-center text-gray-500 hover:text-primary-600 hover:border-primary-500 transition-all shadow-sm">
            <i class="fas fa-chevron-left text-sm"></i>
        </a>
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">Ajukan Permohonan Bimbingan</h2>
            <p class="text-xs text-gray-500">Sampaikan topik, progres laporan, atau kendala yang ingin Anda diskusikan</p>
        </div>
    </div>

    <!-- Info Dosen Pembimbing Banner -->
    <div class="p-6 rounded-xl text-white shadow-lg border border-slate-700/50 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #1e1b4b 100%);">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-400 text-slate-950 flex items-center justify-center text-xl font-bold shrink-0 shadow-md">
                <i class="fas fa-user-tie"></i>
            </div>
            <div>
                <span class="text-[10px] font-bold text-amber-400 uppercase tracking-wider block">Dosen Pembimbing {{ $mahasiswa->kegiatan }}</span>
                <h4 class="text-base font-bold text-white">{{ $dosenPembimbing->dosen->nama ?? '-' }}</h4>
                <p class="text-xs text-slate-300 font-mono">NIDN: {{ $dosenPembimbing->dosen->nidn ?? '-' }}</p>
            </div>
        </div>
        <span class="px-3 py-1 bg-white/10 text-xs font-bold rounded-lg border border-white/20 self-start sm:self-center text-white">
            {{ $mahasiswa->kegiatan }} ({{ $mahasiswa->tahun_akademik }})
        </span>
    </div>

    <!-- Error Messages -->
    @if(isset($errors) && $errors->any())
    <div class="p-5 bg-red-50 border border-red-200 rounded-2xl text-red-800 space-y-1">
        <div class="flex items-center space-x-2 font-bold text-sm text-red-900">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Terdapat beberapa kesalahan pengisian formulir:</span>
        </div>
        <ul class="list-disc list-inside text-xs space-y-1 text-red-700 pl-4">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form -->
    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
        <form action="{{ route('bimbingan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Topik Bimbingan -->
            <div class="space-y-2">
                <label for="topik" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                    Topik / Judul Konsultasi <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="topik"
                    id="topik"
                    value="{{ old('topik') }}"
                    placeholder="Contoh: Bimbingan Laporan PKL Bab 1-3, Rencana Program Kerja, Evaluasi Mingguan"
                    class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-600 transition-all"
                    required
                >
                <p class="text-[11px] text-gray-400">Tuliskan ringkasan materi atau bagian laporan yang ingin dikonsultasikan.</p>
            </div>

            <!-- Tanggal & Waktu Bimbingan -->
            <div class="space-y-2">
                <label for="tanggal_bimbingan" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                    Tanggal & Waktu Sesi Bimbingan <span class="text-red-500">*</span>
                </label>
                <input
                    type="datetime-local"
                    name="tanggal_bimbingan"
                    id="tanggal_bimbingan"
                    value="{{ old('tanggal_bimbingan', now()->format('Y-m-d\TH:i')) }}"
                    class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-600 transition-all"
                    required
                >
                <p class="text-[11px] text-gray-400">Pilih jadwal konsultasi yang disepakati bersama dosen pembimbing.</p>
            </div>

            <!-- Deskripsi Bimbingan -->
            <div class="space-y-2">
                <label for="deskripsi" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                    Pokok Bahasan / Deskripsi Detail <span class="text-red-500">*</span>
                </label>
                <textarea
                    name="deskripsi"
                    id="deskripsi"
                    rows="5"
                    placeholder="Jelaskan secara rinci progres pekerjaan Anda, pertanyaan, atau kendala yang dihadapi di lokasi {{ $mahasiswa->kegiatan }}..."
                    class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-normal text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-600 transition-all leading-relaxed resize-y"
                    required
                >{{ old('deskripsi') }}</textarea>
            </div>

            <!-- File Upload -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                    Unggah Berkas / Draft Laporan (Opsional)
                </label>
                <div class="border-2 border-dashed border-gray-200 hover:border-primary-500 rounded-2xl p-6 bg-gray-50 hover:bg-primary-50/20 text-center cursor-pointer transition-all duration-200">
                    <input
                        type="file"
                        name="materi_terlampir"
                        id="materi_terlampir"
                        accept=".pdf,.doc,.docx,.zip,.rar"
                        class="hidden"
                    >
                    <label for="materi_terlampir" class="cursor-pointer block space-y-2">
                        <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-primary-600 text-xl mx-auto shadow-sm border border-gray-200">
                            <i class="fas fa-file-arrow-up"></i>
                        </div>
                        <p class="text-xs font-bold text-gray-700" id="file_name_display">Klik di sini untuk memilih berkas draft / materi</p>
                        <p class="text-[11px] text-gray-400">Mendukung format: PDF, DOCX, DOC, ZIP, RAR (Maks. 10MB)</p>
                    </label>
                </div>
            </div>

            <!-- Submit & Cancel Buttons -->
            <div class="pt-4 flex flex-col sm:flex-row items-center gap-3">
                <button
                    type="submit"
                    class="w-full sm:w-auto flex-1 py-4 px-8 bg-gold-500 hover:bg-gold-600 text-primary-950 font-semibold text-sm rounded-2xl transition-all shadow-lg shadow-gold-500/20 active:scale-98 flex items-center justify-center space-x-2"
                >
                    <i class="fas fa-paper-plane"></i>
                    <span>Kirim Pengajuan Bimbingan</span>
                </button>
                <a
                    href="{{ route('bimbingan.dashboard') }}"
                    class="w-full sm:w-auto py-4 px-6 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold text-sm rounded-2xl text-center transition-all"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('materi_terlampir').addEventListener('change', function(e) {
        if (this.files && this.files.length > 0) {
            const fileName = this.files[0].name;
            const fileSize = (this.files[0].size / 1024 / 1024).toFixed(2);
            document.getElementById('file_name_display').innerHTML = `<span class="text-emerald-600 font-semibold"><i class="fas fa-check-circle mr-1"></i> ${fileName} (${fileSize} MB)</span>`;
        }
    });
</script>
@endsection
