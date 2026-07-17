@extends('layouts.app')

@section('title', 'Ajukan Bimbingan')

@section('content')
<div style="background-color: #f5f3f0; min-height: 100vh; padding: 40px 20px;">
    <div style="max-width: 800px; margin: 0 auto;">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #1a5d4d 0%, #0f2d26 100%); color: white; padding: 40px; border-radius: 12px; margin-bottom: 40px;">
            <h1 style="font-size: 2rem; margin: 0 0 10px 0; font-weight: 700;">Ajukan Permohonan Bimbingan</h1>
            <p style="margin: 0; color: #d4a574;">Silakan isi formulir di bawah untuk mengajukan bimbingan baru</p>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div style="background-color: #ffd4d4; border: 1px solid #ffb3b3; color: #8b0000; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
                <strong>❌ Terjadi Kesalahan:</strong>
                <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <div style="background: white; padding: 40px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <form action="{{ route('bimbingan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Dosen Pembimbing Info -->
                <div style="background-color: #f5f3f0; padding: 16px; border-left: 4px solid #d4a574; border-radius: 8px; margin-bottom: 30px;">
                    <h3 style="margin: 0 0 12px 0; color: #1a5d4d; font-weight: 700;">👨‍🏫 Dosen Pembimbing</h3>
                    <p style="margin: 0; color: #666;"><strong>{{ $dosenPembimbing->dosen->nama ?? 'N/A' }}</strong> ({{ $dosenPembimbing->dosen->nidn ?? 'N/A' }})</p>
                </div>

                <!-- Topik Bimbingan -->
                <div style="margin-bottom: 30px;">
                    <label style="display: block; color: #0f2d26; font-weight: 700; margin-bottom: 8px; font-size: 15px;">Topik Bimbingan <span style="color: red;">*</span></label>
                    <input
                        type="text"
                        name="topik"
                        value="{{ old('topik') }}"
                        style="width: 100%; padding: 12px 16px; border: 2px solid #d4a574; border-radius: 8px; font-size: 1rem; font-family: inherit; transition: all 0.3s;"
                        placeholder="Contoh: Revisi Laporan KKN, Konsultasi Metodologi Penelitian"
                        required
                    >
                    @error('topik')
                        <p style="color: #d32f2f; margin: 8px 0 0 0; font-size: 0.9rem;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div style="margin-bottom: 30px;">
                    <label style="display: block; color: #0f2d26; font-weight: 700; margin-bottom: 8px; font-size: 15px;">Deskripsi Bimbingan <span style="color: red;">*</span></label>
                    <textarea
                        name="deskripsi"
                        style="width: 100%; padding: 12px 16px; border: 2px solid #d4a574; border-radius: 8px; font-size: 1rem; font-family: inherit; min-height: 150px; transition: all 0.3s; resize: vertical;"
                        placeholder="Jelaskan secara detail apa yang ingin Anda diskusikan dengan dosen pembimbing..."
                        required
                    >{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p style="color: #d32f2f; margin: 8px 0 0 0; font-size: 0.9rem;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Bimbingan -->
                <div style="margin-bottom: 30px;">
                    <label style="display: block; color: #0f2d26; font-weight: 700; margin-bottom: 8px; font-size: 15px;">Tanggal & Waktu Bimbingan <span style="color: red;">*</span></label>
                    <input
                        type="datetime-local"
                        name="tanggal_bimbingan"
                        value="{{ old('tanggal_bimbingan') }}"
                        style="width: 100%; padding: 12px 16px; border: 2px solid #d4a574; border-radius: 8px; font-size: 1rem; font-family: inherit; transition: all 0.3s;"
                        required
                    >
                    <p style="color: #666; margin: 8px 0 0 0; font-size: 0.9rem;">Pilih tanggal dan waktu yang sesuai untuk bimbingan</p>
                    @error('tanggal_bimbingan')
                        <p style="color: #d32f2f; margin: 8px 0 0 0; font-size: 0.9rem;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Upload -->
                <div style="margin-bottom: 30px;">
                    <label style="display: block; color: #0f2d26; font-weight: 700; margin-bottom: 8px; font-size: 15px;">Upload Materi (Opsional)</label>
                    <div style="border: 2px dashed #d4a574; border-radius: 8px; padding: 30px 20px; text-align: center; cursor: pointer; transition: all 0.3s; background-color: #fafaf8;">
                        <input
                            type="file"
                            name="materi_terlampir"
                            id="materi_terlampir"
                            accept=".pdf,.doc,.docx"
                            style="display: none;"
                        >
                        <label for="materi_terlampir" style="cursor: pointer; display: block;">
                            <div style="font-size: 2rem; margin-bottom: 10px;">📎</div>
                            <p style="color: #1a5d4d; font-weight: 600; margin: 0;">Klik untuk upload file</p>
                            <p style="color: #999; margin: 8px 0 0 0; font-size: 0.9rem;">Format: PDF, DOC, DOCX (Max: 5MB)</p>
                        </label>
                    </div>
                    @error('materi_terlampir')
                        <p style="color: #d32f2f; margin: 8px 0 0 0; font-size: 0.9rem;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 16px; margin-top: 40px;">
                    <button
                        type="submit"
                        style="flex: 1; background-color: #d4a574; color: #1a5d4d; padding: 16px; border: none; border-radius: 8px; font-weight: 700; font-size: 1rem; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 12px rgba(212, 165, 116, 0.2);"
                        onmouseover="this.style.backgroundColor='#c9905c'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(212, 165, 116, 0.3)';"
                        onmouseout="this.style.backgroundColor='#d4a574'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(212, 165, 116, 0.2)';"
                    >
                        ✓ Ajukan Permohonan
                    </button>
                    <a
                        href="{{ route('bimbingan.dashboard') }}"
                        style="flex: 1; background-color: #e0e0e0; color: #666; padding: 16px; border-radius: 8px; font-weight: 700; font-size: 1rem; text-align: center; text-decoration: none; transition: all 0.3s;"
                        onmouseover="this.style.backgroundColor='#d0d0d0'"
                        onmouseout="this.style.backgroundColor='#e0e0e0'"
                    >
                        ✕ Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // File upload preview
    document.getElementById('materi_terlampir').addEventListener('change', function(e) {
        if (this.files.length > 0) {
            const filename = this.files[0].name;
            const filesize = (this.files[0].size / 1024 / 1024).toFixed(2);
            alert(`File dipilih: ${filename} (${filesize} MB)`);
        }
    });

    // Form input focus styling
    document.querySelectorAll('input[type="text"], input[type="datetime-local"], textarea').forEach(el => {
        el.addEventListener('focus', function() {
            this.style.borderColor = '#0f2d26';
            this.style.boxShadow = '0 0 0 4px rgba(26, 93, 77, 0.1)';
        });
        el.addEventListener('blur', function() {
            this.style.borderColor = '#d4a574';
            this.style.boxShadow = 'none';
        });
    });
</script>
@endsection
