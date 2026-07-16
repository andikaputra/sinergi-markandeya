@extends('layouts.app')

@section('title', 'Mahasiswa Belum Bimbingan')

@section('content')
<div style="background-color: #f5f3f0; min-height: 100vh; padding: 40px 20px;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <a href="{{ route('admin.bimbingan.dashboard') }}" style="color: #d4a574; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 15px;">
                    ← Kembali ke Dashboard
                </a>
                <h1 style="margin: 0; color: #1a5d4d; font-size: 2rem; font-weight: 700;">👤 Mahasiswa Belum Melakukan Bimbingan</h1>
            </div>
            <div style="background: linear-gradient(135deg, #d4a574 0%, #c9905c 100%); color: white; padding: 20px; border-radius: 10px; font-weight: 700; text-align: center; min-width: 150px;">
                Total: {{ $mahasiswaBelum->total() }}
            </div>
        </div>

        <!-- Table -->
        <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow-x: auto;">
            @if($mahasiswaBelum->count() > 0)
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f5f3f0; border-bottom: 2px solid #d4a574;">
                            <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">No.</th>
                            <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">NIM</th>
                            <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">Nama Mahasiswa</th>
                            <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">Program Studi</th>
                            <th style="padding: 12px; text-align: center; color: #1a5d4d; font-weight: 700;">Kegiatan</th>
                            <th style="padding: 12px; text-align: center; color: #1a5d4d; font-weight: 700;">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mahasiswaBelum as $index => $mahasiswa)
                        <tr style="border-bottom: 1px solid #e0e0e0; {{ $loop->odd ? 'background-color: #fafaf8;' : '' }}">
                            <td style="padding: 16px 12px; color: #666;">{{ ($mahasiswaBelum->currentPage()-1) * $mahasiswaBelum->perPage() + $index + 1 }}</td>
                            <td style="padding: 16px 12px; color: #333; font-weight: 500;">{{ $mahasiswa->nim }}</td>
                            <td style="padding: 16px 12px; color: #333; font-weight: 500;">{{ $mahasiswa->nama }}</td>
                            <td style="padding: 16px 12px; color: #666;">{{ $mahasiswa->prodi ?? '-' }}</td>
                            <td style="padding: 16px 12px; text-align: center;">
                                <span style="background-color: #e3f2fd; color: #1565c0; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                    {{ $mahasiswa->kegiatan ?? '-' }}
                                </span>
                            </td>
                            <td style="padding: 16px 12px; text-align: center; color: #666; font-size: 0.9rem;">
                                {{ $mahasiswa->email }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div style="margin-top: 20px;">
                    {{ $mahasiswaBelum->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 60px 20px;">
                    <div style="font-size: 3rem; margin-bottom: 15px;">🎉</div>
                    <p style="color: #666; margin: 0; font-size: 1.2rem; font-weight: 600;">Semua mahasiswa sudah melakukan bimbingan!</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
