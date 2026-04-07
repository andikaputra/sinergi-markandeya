<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurnal Harian - {{ $mahasiswa->nama }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.5; color: #000; margin: 0; padding: 0; }
        .page { width: 210mm; min-height: 297mm; padding: 20mm; margin: 10mm auto; background: white; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16pt; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 10pt; }
        
        .info-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .info-table td { padding: 3px 0; vertical-align: top; }
        .info-table td:first-child { width: 150px; font-weight: bold; }
        .info-table td:nth-child(2) { width: 20px; text-align: center; }

        .journal-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .journal-table th, .journal-table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .journal-table th { background-color: #f2f2f2; text-align: center; text-transform: uppercase; font-size: 10pt; }
        
        .footer-sign { width: 100%; margin-top: 50px; }
        .footer-sign td { width: 50%; text-align: center; }
        .sign-space { height: 80px; }

        @media print {
            body { margin: 0; background: none; }
            .page { margin: 0; border: none; box-shadow: none; width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="background: #f4f4f4; padding: 15px; text-align: center; border-bottom: 1px solid #ddd;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
            Cetak Jurnal (PDF)
        </button>
        <p style="font-size: 12px; color: #666; margin-top: 10px;">Gunakan browser Chrome/Edge, lalu pilih "Save as PDF" pada menu Destination.</p>
    </div>

    <div class="page">
        <div class="header">
            <h1>Laporan Jurnal Harian Mahasiswa</h1>
            <h1>Universitas Markandeya</h1>
            <p>Program: {{ $mahasiswa->kegiatan }} | Tahun Akademik {{ date('Y') }}</p>
        </div>

        <table class="info-table">
            <tr>
                <td>Nama Mahasiswa</td>
                <td>:</td>
                <td>{{ $mahasiswa->nama }}</td>
            </tr>
            <tr>
                <td>NIM</td>
                <td>:</td>
                <td>{{ $mahasiswa->nim }}</td>
            </tr>
            <tr>
                <td>Program Studi</td>
                <td>:</td>
                <td>{{ $mahasiswa->prodi_full }}</td>
            </tr>
            <tr>
                <td>Lokasi Penempatan</td>
                <td>:</td>
                <td>
                    @if($mahasiswa->kegiatan == 'KKN')
                        Desa {{ $mahasiswa->penempatankkn?->lokasikkn?->desa ?? '-' }}
                    @elseif($mahasiswa->kegiatan == 'PPL')
                        {{ $mahasiswa->penempatanppl?->lokasippl?->Sekolah ?? '-' }}
                    @elseif($mahasiswa->kegiatan == 'PKL')
                        {{ $mahasiswa->penempatanpkl?->lokasipkl?->nama_instansi ?? '-' }}
                    @endif
                </td>
            </tr>
        </table>

        <table class="journal-table">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th style="width: 120px;">Hari / Tanggal</th>
                    <th>Kegiatan / Aktivitas Harian</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jurnals as $index => $jurnal)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('l, d F Y') }}</td>
                    <td>{{ $jurnal->kegiatan }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center; font-style: italic;">Belum ada catatan jurnal.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <table class="footer-sign">
            <tr>
                <td>
                    <p>Mengetahui,</p>
                    <p>Dosen Pembimbing</p>
                    <div class="sign-space"></div>
                    <p><strong><u>{{ $pembimbing->dosen->nama ?? '(..........................................)' }}</u></strong></p>
                    <p>NIDN. {{ $pembimbing->nidn ?? '................................' }}</p>
                </td>
                <td>
                    <p>Gianyar, {{ now()->translatedFormat('d F Y') }}</p>
                    <p>Mahasiswa,</p>
                    <div class="sign-space"></div>
                    <p><strong><u>{{ $mahasiswa->nama }}</u></strong></p>
                    <p>NIM. {{ $mahasiswa->nim }}</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
