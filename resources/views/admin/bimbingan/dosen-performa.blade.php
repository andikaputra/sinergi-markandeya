@extends('layouts.app')

@section('title', 'Performa Dosen Pembimbing')

@section('content')
<div style="background-color: #f5f3f0; min-height: 100vh; padding: 40px 20px;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <a href="{{ route('admin.bimbingan.dashboard') }}" style="color: #d4a574; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 15px;">
                    ← Kembali ke Dashboard Bimbingan
                </a>
                <h1 style="margin: 0; color: #1a5d4d; font-size: 2rem; font-weight: 700;">👨‍🏫 Performa Dosen Pembimbing</h1>
            </div>
            <div style="background: linear-gradient(135deg, #1a5d4d 0%, #0f2d26 100%); color: white; padding: 20px; border-radius: 10px; font-weight: 700; text-align: center; min-width: 150px;">
                Total Dosen: {{ count($dosenList) }}
            </div>
        </div>

        <!-- Table -->
        <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow-x: auto;">
            @if(count($dosenList) > 0)
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f5f3f0; border-bottom: 2px solid #d4a574;">
                            <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">No.</th>
                            <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">Nama Dosen</th>
                            <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">NIDN</th>
                            <th style="padding: 12px; text-align: center; color: #1a5d4d; font-weight: 700;">Total Bimbingan</th>
                            <th style="padding: 12px; text-align: center; color: #1a5d4d; font-weight: 700;">Disetujui</th>
                            <th style="padding: 12px; text-align: center; color: #1a5d4d; font-weight: 700;">Penyelesaian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dosenList as $index => $item)
                        <tr style="border-bottom: 1px solid #e0e0e0; {{ $loop->odd ? 'background-color: #fafaf8;' : '' }}">
                            <td style="padding: 16px 12px; color: #666;">{{ $index + 1 }}</td>
                            <td style="padding: 16px 12px; font-weight: 600; color: #333;">
                                {{ $item['dosen']?->dosen?->nama ?? '-' }}
                            </td>
                            <td style="padding: 16px 12px; color: #666; font-family: monospace;">
                                {{ $item['dosen']?->nidn ?? '-' }}
                            </td>
                            <td style="padding: 16px 12px; text-align: center; font-weight: 700; color: #1a5d4d;">
                                {{ $item['total_bimbingan'] }}
                            </td>
                            <td style="padding: 16px 12px; text-align: center; font-weight: 700; color: #4caf50;">
                                {{ $item['disetujui'] }}
                            </td>
                            <td style="padding: 16px 12px; text-align: center;">
                                <div style="display: inline-flex; align-items: center; gap: 8px;">
                                    <div style="width: 80px; height: 8px; background: #e0e0e0; border-radius: 4px; overflow: hidden;">
                                        <div style="width: {{ $item['performa'] }}%; height: 100%; background: #4caf50;"></div>
                                    </div>
                                    <span style="font-weight: 700; color: #333; font-size: 0.85rem;">{{ $item['performa'] }}%</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="text-align: center; padding: 60px 20px;">
                    <div style="font-size: 3rem; margin-bottom: 15px;">👨‍🏫</div>
                    <h3 style="color: #1a5d4d; margin-bottom: 8px;">Belum Ada Data Plotting Dosen Pembimbing</h3>
                    <p style="color: #666; font-size: 0.95rem;">Lakukan plotting dosen pembimbing terlebih dahulu pada menu Plotting Dosen.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
