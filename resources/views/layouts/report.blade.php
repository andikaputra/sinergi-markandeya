<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>{{ $reportTitle }}</title><link rel="stylesheet" href="{{ asset('css/reports.css') }}"></head>
<body class="report-body {{ ($landscape ?? false) ? 'report-landscape' : '' }}">
<div class="report-toolbar no-print"><div><strong>{{ $reportTitle }}</strong><p>{{ ($landscape ?? false) ? 'A4 landscape' : 'A4 portrait' }} · Pilih Simpan sebagai PDF pada dialog cetak.</p></div><button type="button" onclick="window.print()">Cetak / Simpan PDF</button></div>
<main class="report-page"><header class="report-header"><img src="{{ asset('logo-universitas-markandeya.png') }}" alt="Logo Universitas Markandeya"><div><h1>UNIVERSITAS MARKANDEYA</h1><p>{{ $reportTitle }}</p></div></header><div class="report-meta"><span>@yield('report_period')</span><span>Dicetak: {{ now()->translatedFormat('d F Y H:i') }}</span></div>@yield('report_content')</main>
</body></html>
