@extends('layouts.app')

@section('title', 'Detail Bimbingan')

@section('content')
<div style="background-color: #f5f3f0; min-height: 100vh; padding: 40px 20px;">
    <div style="max-width: 900px; margin: 0 auto;">
        <!-- Header with Back Button -->
        <div style="margin-bottom: 30px;">
            <a href="{{ route('bimbingan.dashboard') }}" style="color: #d4a574; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                ← Kembali ke Dashboard
            </a>
        </div>

        <!-- Status Header -->
        <div style="background: white; padding: 24px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <div style="display: grid; grid-template-columns: 1fr auto; gap: 20px; align-items: center;">
                <div>
                    <h1 style="margin: 0 0 8px 0; color: #1a5d4d; font-size: 1.8rem; font-weight: 700;">{{ $bimbingan->topik }}</h1>
                    <p style="margin: 0; color: #666; font-size: 0.95rem;">
                        📅 {{ $bimbingan->tanggal_bimbingan->format('d MMMM Y, H:i') }} WIB
                    </p>
                </div>
                <div style="text-align: right;">
                    @if($bimbingan->status === 'disetujui')
                        <span style="background-color: #d4edda; color: #155724; padding: 12px 20px; border-radius: 20px; font-weight: 600; display: inline-block;">✅ Disetujui</span>
                    @elseif($bimbingan->status === 'perlu_revisi')
                        <span style="background-color: #ffe0b2; color: #e65100; padding: 12px 20px; border-radius: 20px; font-weight: 600; display: inline-block;">⚠️ Perlu Revisi</span>
                    @else
                        <span style="background-color: #e3f2fd; color: #1565c0; padding: 12px 20px; border-radius: 20px; font-weight: 600; display: inline-block;">⏳ Belum Direview</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
            <!-- Main Content -->
            <div>
                <!-- Deskripsi -->
                <div style="background: white; padding: 24px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <h2 style="margin: 0 0 16px 0; color: #1a5d4d; font-size: 1.2rem; font-weight: 700;">Deskripsi Bimbingan</h2>
                    <div style="color: #333; line-height: 1.8; background-color: #fafaf8; padding: 16px; border-radius: 8px; border-left: 4px solid #d4a574;">
                        {{ $bimbingan->deskripsi }}
                    </div>
                </div>

                <!-- Materi Terlampir -->
                @if($bimbingan->materi_terlampir)
                <div style="background: white; padding: 24px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <h2 style="margin: 0 0 16px 0; color: #1a5d4d; font-size: 1.2rem; font-weight: 700;">📎 Materi Terlampir</h2>
                    <a href="{{ asset('storage/bimbingan/' . $bimbingan->materi_terlampir) }}"
                       target="_blank"
                       style="display: inline-flex; align-items: center; gap: 12px; background-color: #f5f3f0; border: 2px solid #d4a574; padding: 12px 16px; border-radius: 8px; text-decoration: none; color: #1a5d4d; font-weight: 600; transition: all 0.3s;"
                       onmouseover="this.style.backgroundColor='#efe9e0'"
                       onmouseout="this.style.backgroundColor='#f5f3f0'">
                        📥 {{ basename($bimbingan->materi_terlampir) }}
                    </a>
                </div>
                @endif

                <!-- Catatan Dosen -->
                @if($bimbingan->catatan_dosen)
                <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <h2 style="margin: 0 0 16px 0; color: #1a5d4d; font-size: 1.2rem; font-weight: 700;">👨‍🏫 Catatan dari Dosen</h2>
                    <div style="color: #333; line-height: 1.8; background-color: #fafaf8; padding: 16px; border-radius: 8px; border-left: 4px solid #d4a574;">
                        {{ $bimbingan->catatan_dosen }}
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Informasi Umum -->
                <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 24px;">
                    <h3 style="margin: 0 0 20px 0; color: #1a5d4d; font-size: 1.1rem; font-weight: 700;">Informasi Umum</h3>

                    <div style="margin-bottom: 20px;">
                        <p style="color: #999; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Dibuat</p>
                        <p style="margin: 8px 0 0 0; color: #333; font-weight: 600;">{{ $bimbingan->created_at->format('d M Y, H:i') }}</p>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <p style="color: #999; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Terakhir Diupdate</p>
                        <p style="margin: 8px 0 0 0; color: #333; font-weight: 600;">{{ $bimbingan->updated_at->format('d M Y, H:i') }}</p>
                    </div>

                    <div>
                        <p style="color: #999; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Status Permohonan</p>
                        <div style="margin: 8px 0 0 0;">
                            @if($bimbingan->status === 'disetujui')
                                <p style="margin: 0; color: #155724; font-weight: 600;">✅ Disetujui</p>
                            @elseif($bimbingan->status === 'perlu_revisi')
                                <p style="margin: 0; color: #e65100; font-weight: 600;">⚠️ Perlu Revisi</p>
                            @else
                                <p style="margin: 0; color: #1565c0; font-weight: 600;">⏳ Menunggu Review</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Dosen Pembimbing Card -->
                <div style="background: linear-gradient(135deg, #1a5d4d 0%, #0f2d26 100%); color: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <h3 style="margin: 0 0 20px 0; font-size: 1.1rem; font-weight: 700;">👨‍🏫 Dosen Pembimbing</h3>

                    <div style="margin-bottom: 16px;">
                        <p style="color: #d4a574; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Nama</p>
                        <p style="margin: 8px 0 0 0; font-weight: 600;">{{ $bimbingan->dosenPembimbing->dosen->nama ?? 'N/A' }}</p>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <p style="color: #d4a574; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">NIDN</p>
                        <p style="margin: 8px 0 0 0; font-weight: 600;">{{ $bimbingan->dosenPembimbing->dosen->nidn ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p style="color: #d4a574; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">Email</p>
                        <p style="margin: 8px 0 0 0; font-weight: 600; word-break: break-all;">{{ $bimbingan->dosenPembimbing->dosen->email ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
