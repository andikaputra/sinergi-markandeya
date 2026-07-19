@extends('layouts.app')

@section('title', 'Monitoring Login Activity')

@section('content')
<div style="background-color: #f5f3f0; min-height: 100vh; padding: 40px 20px;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #1a5d4d 0%, #0f2d26 100%); color: white; padding: 40px; border-radius: 12px; margin-bottom: 40px;">
            <h1 style="font-size: 2.5rem; margin: 0 0 10px 0; font-weight: 700;">🔍 Monitoring Login Activity</h1>
            <p style="margin: 0; color: #d4a574; font-size: 1.1rem;">Pantau aktivitas login mahasiswa dan dosen</p>
        </div>

        <!-- Mahasiswa Stats -->
        <div style="background: white; padding: 30px; border-radius: 12px; margin-bottom: 40px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h2 style="margin: 0 0 24px 0; color: #1a5d4d; font-size: 1.5rem; font-weight: 700;">👥 Statistik Mahasiswa</h2>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <div style="background: linear-gradient(135deg, #1a5d4d 0%, #0f2d26 100%); color: white; padding: 24px; border-radius: 12px;">
                    <div style="color: #d4a574; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">Total Mahasiswa</div>
                    <div style="font-size: 2.5rem; font-weight: 700;">{{ $statistik['mahasiswa']['total'] }}</div>
                </div>

                <div style="background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%); color: white; padding: 24px; border-radius: 12px;">
                    <div style="color: #c8e6c9; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">Sudah Login</div>
                    <div style="font-size: 2.5rem; font-weight: 700;">{{ $statistik['mahasiswa']['sudah_login'] }}</div>
                    <p style="margin: 8px 0 0 0; font-size: 0.9rem;">{{ $statistik['mahasiswa']['persentase'] }}% dari total</p>
                </div>

                <div style="background: linear-gradient(135deg, #d4a574 0%, #c9905c 100%); color: white; padding: 24px; border-radius: 12px;">
                    <div style="color: rgba(255,255,255,0.9); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">Belum Login Sama Sekali</div>
                    <div style="font-size: 2.5rem; font-weight: 700;">{{ $statistik['mahasiswa']['belum_login'] }}</div>
                    <a href="{{ route('admin.login-activity.mahasiswa-belum-login') }}" style="color: white; text-decoration: none; font-weight: 600; margin-top: 12px; display: inline-block;">
                        Lihat Detail →
                    </a>
                </div>

                <div style="background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%); color: white; padding: 24px; border-radius: 12px;">
                    <div style="color: rgba(255,255,255,0.9); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">Tidak Login 30 Hari</div>
                    <div style="font-size: 2.5rem; font-weight: 700;">{{ $statistik['mahasiswa']['tidak_login_sebulan'] }}</div>
                    <a href="{{ route('admin.login-activity.mahasiswa-tidak-aktif') }}" style="color: white; text-decoration: none; font-weight: 600; margin-top: 12px; display: inline-block;">
                        Lihat Detail →
                    </a>
                </div>
            </div>

            <!-- Mahasiswa Terbaru Login -->
            <h3 style="margin: 0 0 16px 0; color: #1a5d4d; font-weight: 700;">✅ Mahasiswa Terbaru Login</h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f5f3f0; border-bottom: 2px solid #d4a574;">
                            <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">NIM</th>
                            <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">Nama</th>
                            <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">Last Login</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mahasiswaTerbaru as $m)
                        <tr style="border-bottom: 1px solid #e0e0e0;">
                            <td style="padding: 12px; color: #333; font-weight: 500;">{{ $m->nim }}</td>
                            <td style="padding: 12px; color: #333;">{{ $m->nama }}</td>
                            <td style="padding: 12px; color: #666;">{{ $m->last_login?->format('d M Y H:i') ?? 'Belum pernah login' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Dosen Stats -->
        <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h2 style="margin: 0 0 24px 0; color: #1a5d4d; font-size: 1.5rem; font-weight: 700;">👨‍🏫 Statistik Dosen</h2>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <div style="background: linear-gradient(135deg, #1a5d4d 0%, #0f2d26 100%); color: white; padding: 24px; border-radius: 12px;">
                    <div style="color: #d4a574; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">Total Dosen</div>
                    <div style="font-size: 2.5rem; font-weight: 700;">{{ $statistik['dosen']['total'] }}</div>
                </div>

                <div style="background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%); color: white; padding: 24px; border-radius: 12px;">
                    <div style="color: #c8e6c9; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">Sudah Login</div>
                    <div style="font-size: 2.5rem; font-weight: 700;">{{ $statistik['dosen']['sudah_login'] }}</div>
                    <p style="margin: 8px 0 0 0; font-size: 0.9rem;">{{ $statistik['dosen']['persentase'] }}% dari total</p>
                </div>

                <div style="background: linear-gradient(135deg, #d4a574 0%, #c9905c 100%); color: white; padding: 24px; border-radius: 12px;">
                    <div style="color: rgba(255,255,255,0.9); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">Belum Login Sama Sekali</div>
                    <div style="font-size: 2.5rem; font-weight: 700;">{{ $statistik['dosen']['belum_login'] }}</div>
                    <a href="{{ route('admin.login-activity.dosen-belum-login') }}" style="color: white; text-decoration: none; font-weight: 600; margin-top: 12px; display: inline-block;">
                        Lihat Detail →
                    </a>
                </div>

                <div style="background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%); color: white; padding: 24px; border-radius: 12px;">
                    <div style="color: rgba(255,255,255,0.9); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">Tidak Login 30 Hari</div>
                    <div style="font-size: 2.5rem; font-weight: 700;">{{ $statistik['dosen']['tidak_login_sebulan'] }}</div>
                    <a href="{{ route('admin.login-activity.dosen-tidak-aktif') }}" style="color: white; text-decoration: none; font-weight: 600; margin-top: 12px; display: inline-block;">
                        Lihat Detail →
                    </a>
                </div>
            </div>

            <!-- Dosen Terbaru Login -->
            <h3 style="margin: 0 0 16px 0; color: #1a5d4d; font-weight: 700;">✅ Dosen Terbaru Login</h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f5f3f0; border-bottom: 2px solid #d4a574;">
                            <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">NIDN</th>
                            <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">Nama</th>
                            <th style="padding: 12px; text-align: left; color: #1a5d4d; font-weight: 700;">Last Login</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dosenTerbaru as $d)
                        <tr style="border-bottom: 1px solid #e0e0e0;">
                            <td style="padding: 12px; color: #333; font-weight: 500;">{{ $d->nidn }}</td>
                            <td style="padding: 12px; color: #333;">{{ $d->nama }}</td>
                            <td style="padding: 12px; color: #666;">{{ $d->last_login?->format('d M Y H:i') ?? 'Belum pernah login' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
