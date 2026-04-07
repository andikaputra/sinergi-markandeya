<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        @page { size: A4 landscape; margin: 15mm; }
        body { font-family: Arial, sans-serif; font-size: 10pt; color: #333; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 16pt; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 10pt; font-weight: bold; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 8px 5px; text-align: left; }
        th { background-color: #f2f2f2; text-align: center; text-transform: uppercase; font-size: 8pt; font-weight: bold; }
        
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        
        .footer { margin-top: 30px; width: 100%; }
        .footer td { border: none; width: 33%; text-align: center; vertical-align: top; }
        .sign-space { height: 60px; }

        .no-print { 
            background: #333; color: white; padding: 10px; text-align: center; 
            position: sticky; top: 0; z-index: 100;
        }
        .btn-print {
            padding: 8px 20px; background: #2563eb; color: white; border: none; 
            border-radius: 5px; cursor: pointer; font-weight: bold; text-decoration: none;
        }

        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">CETAK / SIMPAN PDF</button>
        <p style="font-size: 11px; margin-top: 5px; color: #ccc;">Pastikan orientasi kertas adalah <b>Landscape</b> saat menyimpan.</p>
    </div>

    <div class="header">
        <h1>{{ $title }}</h1>
        <h1>UNIVERSITAS MARKANDEYA</h1>
        <p>Tahun Akademik {{ date('Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 80px;">NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Prodi</th>
                <th>Lokasi Penempatan</th>
                <th>Dosen Pembimbing</th>
                <th style="width: 40px;">Nilai (DP)</th>
                <th>Dosen Penguji</th>
                <th style="width: 40px;">Nilai (DU)</th>
                <th style="width: 40px;">Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peserta as $index => $mhs)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $mhs->nim }}</td>
                <td class="font-bold">{{ $mhs->nama }}</td>
                <td class="text-center">{{ $mhs->prodi }}</td>
                <td>
                    @if($mhs->kegiatan == 'KKN')
                        Desa {{ $mhs->penempatankkn?->lokasikkn?->desa ?? '-' }}
                    @elseif($mhs->kegiatan == 'PPL')
                        {{ $mhs->penempatanppl?->lokasippl?->Sekolah ?? '-' }}
                    @elseif($mhs->kegiatan == 'PKL')
                        {{ $mhs->penempatanpkl?->lokasipkl?->nama_instansi ?? '-' }}
                    @else
                        {{ $mhs->penempatanmagang?->lokasimagang?->nama_instansi ?? '-' }}
                    @endif
                </td>
                <td>{{ $mhs->dosenPembimbing?->dosen?->nama ?? '-' }}</td>
                <td class="text-center font-bold">{{ $mhs->dosenPembimbing?->nilai ?? '-' }}</td>
                <td>{{ $mhs->dosenPenguji?->dosen?->nama ?? '-' }}</td>
                <td class="text-center font-bold">{{ $mhs->dosenPenguji?->nilai ?? '-' }}</td>
                <td class="text-center font-bold" style="background: #fdf2f2;">{{ $mhs->nilai_akhir }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="footer">
        <tr>
            <td></td>
            <td></td>
            <td>
                <p>Gianyar, {{ now()->translatedFormat('d F Y') }}</p>
                <p>Kepala Pusat LPPM,</p>
                <div class="sign-space"></div>
                <p><strong><u>(..........................................)</u></strong></p>
                <p>NIDN. ................................</p>
            </td>
        </tr>
    </table>
</body>
</html>
