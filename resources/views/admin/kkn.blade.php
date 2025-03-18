@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Daftar Peserta KKN</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Prodi</th>
                <th>Kampus</th>
                <th>Desa</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pesertaKKN as $index => $mahasiswa)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $mahasiswa->nim }}</td>
                    <td>{{ $mahasiswa->nama }}</td>
                    <td>{{ $mahasiswa->prodi_full }}</td>
                    <td>{{ $mahasiswa->kampus }}</td>
                    <td>{{ $mahasiswa->penempatankkn?->lokasikkn?->desa ?? 'Belum Ditentukan' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
