<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Kendali Bimbingan {{ $mahasiswa->kegiatan }} - {{ $mahasiswa->nama }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Times New Roman', Times, serif;
        }

        body {
            background-color: #f5f5f5;
            color: #111;
            padding: 20px;
        }

        .no-print-bar {
            max-width: 800px;
            margin: 0 auto 20px auto;
            background: #1a5d4d;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: sans-serif;
        }

        .btn-print {
            background: #d4a574;
            color: #0f2d26;
            border: none;
            padding: 8px 20px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .page {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px 50px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            min-height: 1000px;
        }

        /* Kop Surat */
        .kop {
            display: flex;
            align-items: center;
            border-bottom: 3px double #000;
            padding-bottom: 12px;
            margin-bottom: 24px;
            text-align: center;
        }

        .kop-logo {
            width: 75px;
            height: 75px;
            object-fit: contain;
            margin-right: 15px;
        }

        .kop-text {
            flex: 1;
        }

        .kop-text h2 {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kop-text h3 {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop-text p {
            font-size: 9.5pt;
            margin-top: 2px;
            color: #333;
        }

        /* Judul Dokumen */
        .doc-title {
            text-align: center;
            margin-bottom: 24px;
        }

        .doc-title h4 {
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .doc-title span {
            font-size: 10.5pt;
            display: block;
            margin-top: 4px;
        }

        /* Identitas Mahasiswa */
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 11pt;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .info-table td.label {
            width: 180px;
            font-weight: 500;
        }

        .info-table td.separator {
            width: 15px;
            text-align: center;
        }

        /* Tabel Log Bimbingan */
        .table-log {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 10pt;
        }

        .table-log th, .table-log td {
            border: 1px solid #000;
            padding: 8px 10px;
            vertical-align: top;
        }

        .table-log th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        /* Tanda Tangan */
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            font-size: 11pt;
            page-break-inside: avoid;
        }

        .sig-box {
            width: 250px;
            text-align: center;
        }

        .sig-space {
            height: 70px;
        }

        .sig-name {
            font-weight: bold;
            text-decoration: underline;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .no-print-bar {
                display: none;
            }
            .page {
                box-shadow: none;
                padding: 20px 30px;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <span><strong>Pratinjau Cetak:</strong> Kartu Kendali Bimbingan {{ $mahasiswa->kegiatan }}</span>
        <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
    </div>

    <div class="page">
        <!-- Kop Surat -->
        <div class="kop">
            <img src="{{ asset('logo-universitas-markandeya.png') }}" alt="Logo" class="kop-logo">
            <div class="kop-text">
                <h2>UNIVERSITAS MARKENDEYA BALI</h2>
                <h3>LEMBAGA PENGEMBANGAN PEMBELAJARAN & PENGABDIAN</h3>
                <p>Jl. Raya Bangli No. 45, Bali | Website: sinergi.markandeya.ac.id | Email: akademik@markandeya.ac.id</p>
            </div>
        </div>

        <!-- Judul -->
        <div class="doc-title">
            <h4>LEMBAR KENDALI BIMBINGAN {{ strtoupper($mahasiswa->kegiatan) }}</h4>
            <span>Tahun Akademik: {{ $mahasiswa->tahun_akademik ?? '2025/2026' }}</span>
        </div>

        <!-- Identitas -->
        <table class="info-table">
            <tr>
                <td class="label">Nama Mahasiswa</td>
                <td class="separator">:</td>
                <td><strong>{{ $mahasiswa->nama }}</strong></td>
            </tr>
            <tr>
                <td class="label">Nomor Induk Mahasiswa (NIM)</td>
                <td class="separator">:</td>
                <td>{{ $mahasiswa->nim }}</td>
            </tr>
            <tr>
                <td class="label">Program Studi</td>
                <td class="separator">:</td>
                <td>{{ $mahasiswa->prodi_full }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Kegiatan</td>
                <td class="separator">:</td>
                <td>{{ $mahasiswa->kegiatan }}</td>
            </tr>
            <tr>
                <td class="label">Lokasi Penempatan</td>
                <td class="separator">:</td>
                <td>
                    @if($mahasiswa->kegiatan == 'KKN')
                        Desa {{ $mahasiswa->penempatankkn?->lokasikkn?->desa ?? '-' }}, Kec. {{ $mahasiswa->penempatankkn?->lokasikkn?->kecamatan ?? '-' }}
                    @elseif($mahasiswa->kegiatan == 'PPL')
                        {{ $mahasiswa->penempatanppl?->lokasippl?->Sekolah ?? '-' }}
                    @elseif($mahasiswa->kegiatan == 'PKL')
                        {{ $mahasiswa->penempatanpkl?->lokasipkl?->nama_instansi ?? '-' }}
                    @elseif($mahasiswa->kegiatan == 'Magang')
                        {{ $mahasiswa->penempatanmagang?->lokasimagang?->nama_instansi ?? '-' }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Dosen Pembimbing</td>
                <td class="separator">:</td>
                <td>{{ $dosenPembimbing->dosen->nama ?? '-' }} (NIDN: {{ $dosenPembimbing->dosen->nidn ?? '-' }})</td>
            </tr>
        </table>

        <!-- Tabel Sesi Bimbingan -->
        <table class="table-log">
            <thead>
                <tr>
                    <th style="width: 35px;">No.</th>
                    <th style="width: 100px;">Tanggal</th>
                    <th>Pokok Bahasan / Materi Konsultasi</th>
                    <th>Catatan & Arahan Pembimbing</th>
                    <th style="width: 80px;">Status</th>
                    <th style="width: 70px;">Paraf Dosen</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bimbingans as $idx => $b)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($b->tanggal_bimbingan)->format('d/m/Y') }}</td>
                    <td>
                        <strong>{{ $b->topik }}</strong>
                        <p style="margin-top: 3px; font-size: 9pt; color: #444;">{{ $b->deskripsi }}</p>
                    </td>
                    <td>
                        {{ $b->catatan_dosen ?? '-' }}
                    </td>
                    <td style="text-align: center; font-weight: bold;">
                        @if($b->status === 'disetujui')
                            Acc
                        @elseif($b->status === 'perlu_revisi')
                            Revisi
                        @else
                            Proses
                        @endif
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        @if($b->status === 'disetujui')
                            <span style="font-size: 14pt;">✓</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; font-style: italic; color: #666;">
                        Belum ada riwayat sesi bimbingan yang tercatat.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Tanda Tangan -->
        <div class="signature-section">
            <div class="sig-box">
                <p>Mahasiswa,</p>
                <div class="sig-space"></div>
                <p class="sig-name">{{ $mahasiswa->nama }}</p>
                <p>NIM: {{ $mahasiswa->nim }}</p>
            </div>

            <div class="sig-box">
                <p>Bangli, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p>Dosen Pembimbing,</p>
                <div class="sig-space"></div>
                <p class="sig-name">{{ $dosenPembimbing->dosen->nama ?? '_______________________' }}</p>
                <p>NIDN: {{ $dosenPembimbing->dosen->nidn ?? '_________________' }}</p>
            </div>
        </div>
    </div>

</body>
</html>
