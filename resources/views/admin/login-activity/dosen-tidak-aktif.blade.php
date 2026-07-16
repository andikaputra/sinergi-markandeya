@extends('layouts.app')

@section('title', 'Dosen Tidak Aktif')

@section('content')
<div style="background-color: #f5f3f0; min-height: 100vh; padding: 40px 20px;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <a href="{{ route('admin.login-activity.dashboard') }}" style="color: #d4a574; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 20px;">
            ← Kembali
        </a>

        <div style="background: white; padding: 30px; border-radius: 12px; margin-bottom: 40px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h1 style="margin: 0; color: #1a5d4d; font-size: 1.8rem; font-weight: 700;">⏰ Dosen Tidak Login 30 Hari Terakhir</h1>
                <div style="background: #ff9800; color: white; padding: 12px 24px; border-radius: 20px; font-weight: 700;">
                    Total: {{ $dosen->total() }}
                </div>
            </div>

            @if($dosen->count() > 0)
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f5f3f0; border-bottom: 2px solid #ff9800;">
                            <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">No.</th>
                            <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">NIDN</th>
                            <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">Nama</th>
                            <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">Email</th>
                            <th style="padding: 12px; text-align: center; color: #1a5d4d; font-weight: 700;">Last Login</th>
                            <th style="padding: 12px; text-align: center; color: #1a5d4d; font-weight: 700;">Hari Tidak Aktif</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dosen as $index => $d)
                        <tr style="border-bottom: 1px solid #e0e0e0; {{ $loop->odd ? 'background-color: #fafaf8;' : '' }}">
                            <td style="padding: 12px; color: #666;">{{ ($dosen->currentPage()-1) * $dosen->perPage() + $index + 1 }}</td>
                            <td style="padding: 12px; color: #333; font-weight: 500;">{{ $d->nidn }}</td>
                            <td style="padding: 12px; color: #333;">{{ $d->nama }}</td>
                            <td style="padding: 12px; color: #666;">{{ $d->email }}</td>
                            <td style="padding: 12px; text-align: center;">
                                @if($d->last_login)
                                    <span style="background-color: #e3f2fd; color: #1565c0; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                        {{ $d->last_login->format('d M Y') }}
                                    </span>
                                @else
                                    <span style="background-color: #ffebee; color: #c62828; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                        Belum Login
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 12px; text-align: center;">
                                @if($d->last_login)
                                    <span style="background-color: #fff3e0; color: #e65100; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                        {{ now()->diffInDays($d->last_login) }} hari
                                    </span>
                                @else
                                    <span style="background-color: #ffcdd2; color: #b71c1c; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                        Tidak pernah
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 20px;">
                {{ $dosen->links() }}
            </div>
            @else
            <div style="text-align: center; padding: 60px 20px;">
                <div style="font-size: 3rem; margin-bottom: 15px;">✅</div>
                <p style="color: #666; font-size: 1.1rem; font-weight: 600;">Semua dosen aktif login dalam 30 hari terakhir!</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
