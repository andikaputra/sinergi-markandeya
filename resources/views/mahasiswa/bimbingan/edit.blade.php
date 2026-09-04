@extends('layouts.adminmhs')

@section('title', 'Perbaiki Bimbingan - ' . $bimbingan->topik)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center space-x-4">
        <a href="{{ route('bimbingan.show', $bimbingan->id) }}" class="w-10 h-10 bg-white border border-gray-200 rounded-2xl flex items-center justify-center text-gray-500 hover:text-primary-600 hover:border-primary-500 transition-all shadow-sm">
            <i class="fas fa-chevron-left text-sm"></i>
        </a>
        <div>
            <h2 class="text-2xl font-black text-gray-800">Perbaiki & Kirim Ulang Bimbingan</h2>
            <p class="text-xs text-gray-500">Sesuaikan draf laporan dan pokok bahasan sesuai dengan arahan revisi dosen pembimbing</p>
        </div>
    </div>

    <!-- Catatan Dosen yang Harus Direvisi (Highlight Box) -->
    @if($bimbingan->catatan_dosen)
    <div class="bg-gradient-to-r from-amber-500 to-orange-500 text-white p-6 rounded-3xl shadow-lg shadow-amber-500/10 space-y-2">
        <div class="flex items-center space-x-2.5">
            <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center text-white text-sm font-bold backdrop-blur-xs">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div>
                <span class="text-[10px] font-black uppercase tracking-wider text-amber-100 block">Catatan & Masukan Dosen Pembimbing</span>
                <h4 class="text-sm font-bold text-white">{{ $bimbingan->dosenPembimbing?->dosen?->nama ?? 'Dosen Pembimbing' }}</h4>
            </div>
        </div>
        <div class="p-4 bg-white/10 rounded-2xl border border-white/20 backdrop-blur-xs text-xs leading-relaxed font-medium">
            <p class="whitespace-pre-line">{{ $bimbingan->catatan_dosen }}</p>
        </div>
    </div>
    @endif

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

    <!-- Form Edit Revisi -->
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <form action="{{ route('bimbingan.update', $bimbingan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Topik Bimbingan -->
            <div class="space-y-2">
                <label for="topik" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                    Topik / Judul Konsultasi <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="topik"
                    id="topik"
                    value="{{ old('topik', $bimbingan->topik) }}"
                    class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-600 transition-all"
                    required
                >
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
                    value="{{ old('tanggal_bimbingan', \Carbon\Carbon::parse($bimbingan->tanggal_bimbingan)->format('Y-m-d\TH:i')) }}"
                    class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-600 transition-all"
                    required
                >
            </div>

            <!-- Deskripsi / Keterangan Perbaikan -->
            <div class="space-y-2">
                <label for="deskripsi" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                    Penjelasan Hasil Revisi & Pokok Bahasan <span class="text-red-500">*</span>
                </label>
                <textarea
                    name="deskripsi"
                    id="deskripsi"
                    rows="6"
                    placeholder="Tuliskan poin-poin yang sudah Anda perbaiki sesuai catatan dosen..."
                    class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-normal text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-600 transition-all leading-relaxed resize-y"
                    required
                >{{ old('deskripsi', $bimbingan->deskripsi) }}</textarea>
                <p class="text-[11px] text-gray-400">Jelaskan secara jelas perbaikan yang sudah Anda lakukan agar dosen pembimbing mudah memverifikasi.</p>
            </div>

            <!-- File Upload -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                    Unggah Berkas Baru / Draft Laporan yang Sudah Direvisi
                </label>

                @if($bimbingan->materi_terlampir)
                <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-600 flex items-center justify-between mb-2">
                    <span class="flex items-center">
                        <i class="fas fa-file-alt text-primary-600 mr-2"></i>
                        Berkas saat ini: <strong>{{ $bimbingan->materi_terlampir }}</strong>
                    </span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Akan digantikan bila mengunggah berkas baru</span>
                </div>
                @endif

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
                        <p class="text-xs font-bold text-gray-700" id="file_name_display">Klik di sini untuk mengunggah draf laporan revisi</p>
                        <p class="text-[11px] text-gray-400">Format: PDF, DOCX, DOC, ZIP, RAR (Maks. 10MB)</p>
                    </label>
                </div>
            </div>

            <!-- Submit & Cancel Buttons -->
            <div class="pt-4 flex flex-col sm:flex-row items-center gap-3">
                <button
                    type="submit"
                    class="w-full sm:w-auto flex-1 py-4 px-8 bg-gold-500 hover:bg-gold-600 text-primary-950 font-black text-sm rounded-2xl transition-all shadow-lg shadow-gold-500/20 active:scale-98 flex items-center justify-center space-x-2"
                >
                    <i class="fas fa-paper-plane"></i>
                    <span>Kirim Hasil Revisi ke Dosen</span>
                </button>
                <a
                    href="{{ route('bimbingan.show', $bimbingan->id) }}"
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
            document.getElementById('file_name_display').innerHTML = `<span class="text-emerald-600 font-black"><i class="fas fa-check-circle mr-1"></i> ${fileName} (${fileSize} MB)</span>`;
        }
    });
</script>
@endsection
