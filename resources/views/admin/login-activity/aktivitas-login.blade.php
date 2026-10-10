@extends('layouts.admin')

@section('title', 'Laporan Aktivitas Login')

@section('content')
<div class="space-y-6">
    <a href="{{ route('admin.login-activity.dashboard') }}" class="text-primary-600 font-semibold">
        ← Kembali ke Monitoring Aktivitas Login
    </a>
    @foreach(['Mahasiswa' => $mahasiswas, 'Dosen' => $dosens] as $label => $users)
    <section class="bg-white p-6 rounded-2xl border border-gray-100 space-y-4">
        <h3 class="text-xl font-bold text-gray-800">{{ $label }}</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead><tr class="border-b">
                    <th class="p-3">NIM / NIDN</th><th class="p-3">Nama</th><th class="p-3">Aktivitas Terakhir</th>
                </tr></thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="border-b">
                        <td class="p-3">{{ $user->nim ?? $user->nidn }}</td>
                        <td class="p-3">{{ $user->nama }}</td>
                        <td class="p-3">{{ $user->last_login?->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="p-3 text-gray-500">Belum ada aktivitas {{ strtolower($label) }}.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $users->links() }}
    </section>
    @endforeach
</div>
@endsection
