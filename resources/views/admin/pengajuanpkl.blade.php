@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Pengajuan Lokasi PKL</h2>
    

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Prodi</th>
                <th>Nama Instansi</th>
                <th>Alamat</th>
                <th>Kontak</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengajuan as $item)
                <tr>
                    <td>{{ $item->nim }}</td>
                    <td>{{ $item->mahasiswa->nama ?? 'Tidak Ada' }}</td>
                    <td>{{ $item->mahasiswa->prodi_full }}</td>
                    <td>{{ $item->nama_instansi }}</td>
                    <td>{{ $item->alamat }}</td>
                    <td>{{ $item->kontak ?? '-' }}</td>
                    <td>
                        @if ($item->status == 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif ($item->status == 'approved')
                            <span class="badge bg-success">Approved</span>
                        @else
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </td>
                    <td>
                            <form action="{{ route('pengajuanpkl.approve', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                            </form>
                            <form action="{{ route('pengajuanpkl.reject', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                            </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada pengajuan lokasi PKL.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
