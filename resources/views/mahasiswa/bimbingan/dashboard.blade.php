@extends('layouts.app')

@section('title', 'Dashboard Bimbingan')

@section('content')
<div style="background-color: #f5f3f0; min-height: 100vh; padding: 40px 20px;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #1a5d4d 0%, #0f2d26 100%); color: white; padding: 40px; border-radius: 12px; margin-bottom: 40px;">
            <h1 style="font-size: 2.5rem; margin: 0 0 10px 0; font-weight: 700; color: #ffffff;">Dashboard Bimbingan</h1>
            <p style="margin: 0; color: #d4a574; font-size: 1.1rem;">Kelola permohonan bimbingan dengan dosen pembimbing Anda</p>
        </div>

        <!-- Pesan Success/Error -->
        @if($errors->any())
            <div style="background-color: #ffd4d4; border: 1px solid #ffb3b3; color: #8b0000; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
                <strong>❌ Error:</strong> {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div style="background-color: #d4f1d4; border: 1px solid #b3d9b3; color: #1a5d4d; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
                <strong>✅ Berhasil:</strong> {{ session('success') }}
            </div>
        @endif

        <!-- Statistik Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 40px;">
            <div style="background: white; padding: 24px; border-radius: 12px; border-top: 4px solid #d4a574; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="color: #666; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">Total Bimbingan</div>
                <div style="font-size: 2.5rem; font-weight: 700; color: #1a5d4d;">{{ $statistik['total'] }}</div>
            </div>

            <div style="background: white; padding: 24px; border-radius: 12px; border-top: 4px solid #4caf50; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="color: #666; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">Disetujui</div>
                <div style="font-size: 2.5rem; font-weight: 700; color: #4caf50;">{{ $statistik['disetujui'] }}</div>
            </div>

            <div style="background: white; padding: 24px; border-radius: 12px; border-top: 4px solid #ff9800; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="color: #666; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">Perlu Revisi</div>
                <div style="font-size: 2.5rem; font-weight: 700; color: #ff9800;">{{ $statistik['perlu_revisi'] }}</div>
            </div>

            <div style="background: white; padding: 24px; border-radius: 12px; border-top: 4px solid #2196f3; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="color: #666; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">Belum Direview</div>
                <div style="font-size: 2.5rem; font-weight: 700; color: #2196f3;">{{ $statistik['belum_direview'] }}</div>
            </div>
        </div>

        <!-- Dosen Pembimbing Info & Action Button -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 40px;">
            @if($dosenPembimbing)
            <div style="background: white; padding: 24px; border-radius: 12px; border-left: 4px solid #d4a574; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h3 style="margin: 0 0 20px 0; color: #1a5d4d; font-size: 1.2rem; font-weight: 700;">👨‍🏫 Dosen Pembimbing</h3>
                <div style="margin-bottom: 12px;">
                    <span style="color: #666; font-size: 0.9rem;">Nama:</span>
                    <p style="margin: 4px 0 0 0; font-weight: 600; color: #1a5d4d;">{{ $dosenPembimbing->dosen->nama ?? 'N/A' }}</p>
                </div>
                <div style="margin-bottom: 12px;">
                    <span style="color: #666; font-size: 0.9rem;">NIDN:</span>
                    <p style="margin: 4px 0 0 0; font-weight: 600; color: #1a5d4d;">{{ $dosenPembimbing->dosen->nidn ?? 'N/A' }}</p>
                </div>
                <div>
                    <span style="color: #666; font-size: 0.9rem;">Email:</span>
                    <p style="margin: 4px 0 0 0; font-weight: 600; color: #1a5d4d;">{{ $dosenPembimbing->dosen->email ?? 'N/A' }}</p>
                </div>
            </div>
            @endif

            <div style="background: linear-gradient(135deg, #1a5d4d 0%, #0f2d26 100%); padding: 24px; border-radius: 12px; color: white; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 15px;">📋</div>
                <h3 style="margin: 0 0 10px 0; font-size: 1.3rem; font-weight: 700;">Ajukan Bimbingan</h3>
                <p style="margin: 0 0 20px 0; color: #d4a574;">Buat permohonan bimbingan baru dengan dosen pembimbing Anda</p>
                <a href="{{ route('bimbingan.create') }}" style="background-color: #d4a574; color: #1a5d4d; padding: 12px 32px; border-radius: 8px; text-decoration: none; font-weight: 700; transition: all 0.3s; display: inline-block; box-shadow: 0 4px 12px rgba(212, 165, 116, 0.2);" onmouseover="this.style.backgroundColor='#c9905c'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(212, 165, 116, 0.3)';" onmouseout="this.style.backgroundColor='#d4a574'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(212, 165, 116, 0.2)';">
                    + Ajukan Bimbingan
                </a>
            </div>
        </div>

        <!-- Daftar Bimbingan -->
        <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h2 style="margin: 0 0 24px 0; color: #1a5d4d; font-size: 1.5rem; font-weight: 700;">Riwayat Permohonan Bimbingan</h2>

            @if($bimbingans->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #f5f3f0; border-bottom: 2px solid #d4a574;">
                                <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">No.</th>
                                <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">Topik</th>
                                <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">Tanggal Bimbingan</th>
                                <th style="padding: 12px; text-align: center; color: #1a5d4d; font-weight: 700;">Status</th>
                                <th style="padding: 12px; text-align: center; color: #1a5d4d; font-weight: 700;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bimbingans as $index => $bimbingan)
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 16px 12px; color: #666;">{{ ($bimbingans->currentPage()-1) * $bimbingans->perPage() + $index + 1 }}</td>
                                <td style="padding: 16px 12px; color: #333; font-weight: 500;">{{ $bimbingan->topik }}</td>
                                <td style="padding: 16px 12px; color: #666;">{{ $bimbingan->tanggal_bimbingan->format('d M Y, H:i') }}</td>
                                <td style="padding: 16px 12px; text-align: center;">
                                    @if($bimbingan->status === 'disetujui')
                                        <span style="background-color: #d4edda; color: #155724; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">✅ Disetujui</span>
                                    @elseif($bimbingan->status === 'perlu_revisi')
                                        <span style="background-color: #ffe0b2; color: #e65100; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">⚠️ Perlu Revisi</span>
                                    @else
                                        <span style="background-color: #e3f2fd; color: #1565c0; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">⏳ Belum Direview</span>
                                    @endif
                                </td>
                                <td style="padding: 16px 12px; text-align: center;">
                                    <a href="{{ route('bimbingan.show', $bimbingan->id) }}" style="color: #d4a574; text-decoration: none; font-weight: 600; transition: color 0.3s;">
                                        Lihat Detail →
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div style="margin-top: 20px; text-align: center;">
                    {{ $bimbingans->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 40px 20px; background-color: #f9f9f9; border-radius: 8px;">
                    <div style="font-size: 3rem; margin-bottom: 15px;">📭</div>
                    <p style="color: #666; margin: 0; font-size: 1.1rem;">Belum ada permohonan bimbingan</p>
                    <p style="color: #999; margin: 8px 0 0 0;">Ajukan permohonan bimbingan pertama Anda sekarang!</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    a:hover {
        opacity: 0.9;
    }
</style>
@endsection
