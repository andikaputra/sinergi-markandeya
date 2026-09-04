@extends('layouts.app')

@section('title', 'Laporan Rekapitulasi Bimbingan')

@section('content')
<div style="background-color: #f5f3f0; min-height: 100vh; padding: 40px 20px;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <a href="{{ route('admin.bimbingan.dashboard') }}" style="color: #d4a574; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 15px;">
                    ← Kembali ke Dashboard Bimbingan
                </a>
                <h1 style="margin: 0; color: #1a5d4d; font-size: 2rem; font-weight: 700;">📑 Laporan Rekapitulasi Bimbingan</h1>
            </div>
            <div style="background: linear-gradient(135deg, #1a5d4d 0%, #0f2d26 100%); color: white; padding: 20px; border-radius: 10px; font-weight: 700; text-align: center; min-width: 150px;">
                Total: {{ count($bimbingans) }} Data
            </div>
        </div>

        <!-- Table -->
        <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow-x: auto;">
            @if(count($bimbingans) > 0)
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f5f3f0; border-bottom: 2px solid #d4a574;">
                            <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">No.</th>
                            <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">Mahasiswa</th>
                            <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">Dosen Pembimbing</th>
                            <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">Sesi & Topik</th>
                            <th style="padding: 12px; text-align: center; color: #1a5d4d; font-weight: 700;">Tanggal Bimbingan</th>
                            <th style="padding: 12px; text-align: center; color: #1a5d4d; font-weight: 700;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bimbingans as $index => $bimbingan)
                        <tr style="border-bottom: 1px solid #e0e0e0; {{ $loop->odd ? 'background-color: #fafaf8;' : '' }}">
                            <td style="padding: 16px 12px; color: #666;">{{ $index + 1 }}</td>
                            <td style="padding: 16px 12px;">
                                <div style="font-weight: 600; color: #333;">{{ $bimbingan->mahasiswa->nama ?? '-' }}</div>
                                <div style="color: #666; font-size: 0.85rem; font-family: monospace;">NIM: {{ $bimbingan->nim }} • {{ $bimbingan->mahasiswa->prodi ?? '-' }}</div>
                            </td>
                            <td style="padding: 16px 12px; color: #333; font-weight: 500;">
                                {{ $bimbingan->dosenPembimbing?->dosen?->nama ?? '-' }}
                            </td>
                            <td style="padding: 16px 12px; color: #444;">
                                <div style="font-weight: 600;">Sesi #{{ $bimbingan->sesi_ke }}: {{ $bimbingan->topik_bimbingan ?? '-' }}</div>
                            </td>
                            <td style="padding: 16px 12px; text-align: center; color: #666; font-size: 0.9rem;">
                                {{ $bimbingan->tanggal_bimbingan ? \Carbon\Carbon::parse($bimbingan->tanggal_bimbingan)->format('d M Y') : '-' }}
                            </td>
                            <td style="padding: 16px 12px; text-align: center;">
                                @if($bimbingan->status === 'disetujui')
                                    <span style="background-color: #e8f5e9; color: #2e7d32; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">Disetujui</span>
                                @elseif($bimbingan->status === 'perlu_revisi')
                                    <span style="background-color: #ffebee; color: #c62828; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">Perlu Revisi</span>
                                @else
                                    <span style="background-color: #fff3e0; color: #e65100; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">Belum Direview</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="text-align: center; padding: 60px 20px;">
                    <div style="font-size: 3rem; margin-bottom: 15px;">📑</div>
                    <h3 style="color: #1a5d4d; margin-bottom: 8px;">Belum Ada Riwayat Bimbingan</h3>
                    <p style="color: #666; font-size: 0.95rem;">Data bimbingan mahasiswa akan muncul setelah mahasiswa mengajukan permohonan bimbingan.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
