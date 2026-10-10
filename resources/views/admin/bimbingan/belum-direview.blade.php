@extends('layouts.app')

@section('title', 'Bimbingan Belum Direview')

@section('content')
<div style="background-color: #f5f3f0; min-height: 100vh; padding: 40px 20px;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <a href="{{ route('admin.bimbingan.dashboard') }}" style="color: #d4a574; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 15px;">
                    ← Kembali ke Dashboard Bimbingan
                </a>
                <h1 style="margin: 0; color: #1a5d4d; font-size: 2rem; font-weight: 700;">⏳ Permohonan Bimbingan Belum Direview</h1>
            </div>
            <div style="background: linear-gradient(135deg, #d4a574 0%, #c9905c 100%); color: white; padding: 20px; border-radius: 10px; font-weight: 700; text-align: center; min-width: 150px;">
                Total: {{ $permohonan->total() }}
            </div>
        </div>

        <!-- Table -->
        <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow-x: auto;">
            @if($permohonan->count() > 0)
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f5f3f0; border-bottom: 2px solid #d4a574;">
                            <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">No.</th>
                            <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">Mahasiswa</th>
                            <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">Dosen Pembimbing</th>
                            <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">Topik Bimbingan</th>
                            <th style="padding: 12px; text-align: center; color: #1a5d4d; font-weight: 700;">Tanggal Pengajuan</th>
                            <th style="padding: 12px; text-align: center; color: #1a5d4d; font-weight: 700;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permohonan as $index => $bimbingan)
                        <tr style="border-bottom: 1px solid #e0e0e0; {{ $loop->odd ? 'background-color: #fafaf8;' : '' }}">
                            <td style="padding: 16px 12px; color: #666;">{{ ($permohonan->currentPage()-1) * $permohonan->perPage() + $index + 1 }}</td>
                            <td style="padding: 16px 12px;">
                                <div style="font-weight: 600; color: #333;">{{ $bimbingan->mahasiswa->nama ?? '-' }}</div>
                                <div style="color: #666; font-size: 0.85rem; font-family: monospace;">NIM: {{ $bimbingan->nim }}</div>
                            </td>
                            <td style="padding: 16px 12px; color: #333; font-weight: 500;">
                                {{ $bimbingan->dosenPembimbing?->dosen?->nama ?? '-' }}
                            </td>
                            <td style="padding: 16px 12px; color: #444; max-width: 300px;">
                                <div style="font-weight: 600;">{{ $bimbingan->topik_bimbingan ?? 'Bimbingan #' . $bimbingan->sesi_ke }}</div>
                                <div style="color: #777; font-size: 0.85rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Str::limit($bimbingan->catatan_mahasiswa, 60) }}</div>
                            </td>
                            <td style="padding: 16px 12px; text-align: center; color: #666; font-size: 0.9rem;">
                                {{ $bimbingan->created_at ? $bimbingan->created_at->format('d M Y H:i') : '-' }}
                            </td>
                            <td style="padding: 16px 12px; text-align: center;">
                                <span style="background-color: #fff3e0; color: #e65100; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                    Belum Direview
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div style="margin-top: 20px;">
                    {{ $permohonan->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 60px 20px;">
                    <div style="font-size: 3rem; margin-bottom: 15px;">✅</div>
                    <h3 style="color: #1a5d4d; margin-bottom: 8px;">Tidak Ada Bimbingan yang Menunggu Review</h3>
                    <p style="color: #666; font-size: 0.95rem;">Semua permohonan bimbingan mahasiswa telah selesai direview oleh dosen pembimbing.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
