@extends('layouts.app')

@section('title', 'Admin Dashboard Bimbingan')

@section('content')
<div style="background-color: #f5f3f0; min-height: 100vh; padding: 40px 20px;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #1a5d4d 0%, #0f2d26 100%); color: white; padding: 40px; border-radius: 12px; margin-bottom: 40px;">
            <h1 style="font-size: 2.5rem; margin: 0 0 10px 0; font-weight: 700;">📊 Admin Dashboard Bimbingan</h1>
            <p style="margin: 0; color: #d4a574; font-size: 1.1rem;">Monitoring dan kontrol sistem bimbingan mahasiswa</p>
        </div>

        <!-- Quick Links -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 40px;">
            <a href="{{ route('admin.bimbingan.belum-bimbingan') }}" style="background: linear-gradient(135deg, #d4a574 0%, #c9905c 100%); color: #1a5d4d; padding: 20px; border-radius: 10px; text-decoration: none; text-align: center; font-weight: 700; transition: transform 0.3s; box-shadow: 0 4px 12px rgba(212, 165, 116, 0.2);" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 20px rgba(212, 165, 116, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(212, 165, 116, 0.2)';">
                👤 Belum Bimbingan
            </a>
            <a href="{{ route('admin.bimbingan.belum-direview') }}" style="background: linear-gradient(135deg, #d4a574 0%, #c9905c 100%); color: #1a5d4d; padding: 20px; border-radius: 10px; text-decoration: none; text-align: center; font-weight: 700; transition: transform 0.3s; box-shadow: 0 4px 12px rgba(212, 165, 116, 0.2);" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 20px rgba(212, 165, 116, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(212, 165, 116, 0.2)';">
                ⏳ Belum Direview
            </a>
            <a href="{{ route('admin.bimbingan.perlu-revisi') }}" style="background: linear-gradient(135deg, #d4a574 0%, #c9905c 100%); color: #1a5d4d; padding: 20px; border-radius: 10px; text-decoration: none; text-align: center; font-weight: 700; transition: transform 0.3s; box-shadow: 0 4px 12px rgba(212, 165, 116, 0.2);" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 20px rgba(212, 165, 116, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(212, 165, 116, 0.2)';">
                ⚠️ Perlu Revisi
            </a>
            <a href="{{ route('admin.bimbingan.dosen-performa') }}" style="background: linear-gradient(135deg, #d4a574 0%, #c9905c 100%); color: #1a5d4d; padding: 20px; border-radius: 10px; text-decoration: none; text-align: center; font-weight: 700; transition: transform 0.3s; box-shadow: 0 4px 12px rgba(212, 165, 116, 0.2);" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 20px rgba(212, 165, 116, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(212, 165, 116, 0.2)';">
                👨‍🏫 Performa Dosen
            </a>
        </div>

        <!-- Statistik Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 40px;">
            <div style="background: white; padding: 24px; border-radius: 12px; border-top: 4px solid #1a5d4d; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="color: #666; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">👥 Total Mahasiswa</div>
                <div style="font-size: 2.5rem; font-weight: 700; color: #1a5d4d;">{{ $statistik['total_mahasiswa'] }}</div>
            </div>

            <div style="background: white; padding: 24px; border-radius: 12px; border-top: 4px solid #4caf50; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="color: #666; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">✅ Sudah Bimbingan</div>
                <div style="font-size: 2.5rem; font-weight: 700; color: #4caf50;">{{ $statistik['sudah_bimbingan'] }}</div>
                <p style="margin: 8px 0 0 0; color: #999; font-size: 0.85rem;">{{ round(($statistik['sudah_bimbingan'] / $statistik['total_mahasiswa'] * 100), 1) }}% dari total</p>
            </div>

            <div style="background: white; padding: 24px; border-radius: 12px; border-top: 4px solid #d4a574; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="color: #666; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">❌ Belum Bimbingan</div>
                <div style="font-size: 2.5rem; font-weight: 700; color: #d4a574;">{{ $statistik['belum_bimbingan'] }}</div>
                <p style="margin: 8px 0 0 0; color: #999; font-size: 0.85rem;">Perlu follow-up</p>
            </div>

            <div style="background: white; padding: 24px; border-radius: 12px; border-top: 4px solid #2196f3; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="color: #666; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">📋 Total Permohonan</div>
                <div style="font-size: 2.5rem; font-weight: 700; color: #2196f3;">{{ $statistik['total_permohonan'] }}</div>
            </div>

            <div style="background: white; padding: 24px; border-radius: 12px; border-top: 4px solid #ff9800; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="color: #666; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">⏳ Belum Direview</div>
                <div style="font-size: 2.5rem; font-weight: 700; color: #ff9800;">{{ $statistik['belum_direview'] }}</div>
            </div>

            <div style="background: white; padding: 24px; border-radius: 12px; border-top: 4px solid #e64a19; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="color: #666; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">✏️ Perlu Revisi</div>
                <div style="font-size: 2.5rem; font-weight: 700; color: #e64a19;">{{ $statistik['perlu_revisi'] }}</div>
            </div>
        </div>

        <!-- Permohonan Terbaru -->
        <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h2 style="margin: 0; color: #1a5d4d; font-size: 1.5rem; font-weight: 700;">📰 Permohonan Bimbingan Terbaru</h2>
                <a href="{{ route('admin.bimbingan.laporan') }}" style="color: #d4a574; text-decoration: none; font-weight: 600;">Lihat Semua →</a>
            </div>

            @if($bimbinganTerbaru->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #f5f3f0; border-bottom: 2px solid #d4a574;">
                                <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">NIM / Nama</th>
                                <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">Topik Bimbingan</th>
                                <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">Dosen Pembimbing</th>
                                <th style="padding: 12px; text-align: center; color: #1a5d4d; font-weight: 700;">Status</th>
                                <th style="padding: 12px; text-align: center; color: #1a5d4d; font-weight: 700;">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bimbinganTerbaru as $bimbingan)
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="padding: 16px 12px; color: #333; font-weight: 500;">
                                    <div>{{ $bimbingan->nim }}</div>
                                    <div style="color: #666; font-size: 0.9rem;">{{ $bimbingan->mahasiswa->nama ?? 'N/A' }}</div>
                                </td>
                                <td style="padding: 16px 12px; color: #333;">{{ $bimbingan->topik }}</td>
                                <td style="padding: 16px 12px; color: #666;">{{ $bimbingan->dosenPembimbing->dosen->nama ?? 'N/A' }}</td>
                                <td style="padding: 16px 12px; text-align: center;">
                                    @if($bimbingan->status === 'disetujui')
                                        <span style="background-color: #d4edda; color: #155724; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">✅ Disetujui</span>
                                    @elseif($bimbingan->status === 'perlu_revisi')
                                        <span style="background-color: #ffe0b2; color: #e65100; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">⚠️ Revisi</span>
                                    @else
                                        <span style="background-color: #e3f2fd; color: #1565c0; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">⏳ Menunggu</span>
                                    @endif
                                </td>
                                <td style="padding: 16px 12px; text-align: center; color: #666; font-size: 0.9rem;">
                                    {{ $bimbingan->created_at->format('d M Y') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align: center; padding: 40px;">
                    <p style="color: #666; font-size: 1.1rem;">Belum ada permohonan bimbingan</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
