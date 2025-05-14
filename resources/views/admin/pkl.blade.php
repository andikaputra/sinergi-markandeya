@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Daftar Peserta PKL</h2>

    <table class="table table-bordered" id="pklTable">
        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Prodi</th>
                <th>Kampus</th>
                <th>Waktu Daftar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pesertaPKL as $index => $mahasiswa)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $mahasiswa->nim }}</td>
                    <td>{{ $mahasiswa->nama }}</td>
                    <td>{{ $mahasiswa->prodi_full }}</td>
                    <td>{{ $mahasiswa->kampus }}</td>
                    <td>{{ $mahasiswa->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
