@extends('layouts.adminmhs')

@section('content')
<div class="container mt-4">
    <h2>Pengajuan Lokasi PKL</h2>
    
    <a href="{{ route('pengajuanpkl.create') }}" class="btn btn-primary mb-3">Ajukan Lokasi Baru</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
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
                        @if (Auth::user()->role === 'admin')
                            <form action="{{ route('pengajuan_pkl.approve', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                            </form>
                            <form action="{{ route('pengajuan_pkl.reject', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                            </form>
                        @else
                            <span class="text-muted">Tidak ada aksi</span>
                        @endif
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
