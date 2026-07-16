<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bimbingan;
use App\Models\Mahasiswa;
use App\Models\DosenPembimbing;
use Illuminate\Http\Request;

class BimbinganMonitoringController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $totalMahasiswa = Mahasiswa::count();
        $mahasiswaBimbingan = Bimbingan::distinct('nim')->count('nim');
        $mahasiswaTidakBimbingan = $totalMahasiswa - $mahasiswaBimbingan;

        $statistik = [
            'total_mahasiswa' => $totalMahasiswa,
            'sudah_bimbingan' => $mahasiswaBimbingan,
            'belum_bimbingan' => $mahasiswaTidakBimbingan,
            'total_permohonan' => Bimbingan::count(),
            'disetujui' => Bimbingan::where('status', 'disetujui')->count(),
            'perlu_revisi' => Bimbingan::where('status', 'perlu_revisi')->count(),
            'belum_direview' => Bimbingan::where('status', 'belum_direview')->count(),
        ];

        $bimbinganTerbaru = Bimbingan::with('mahasiswa', 'dosenPembimbing.dosen')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.bimbingan.dashboard', compact('statistik', 'bimbinganTerbaru'));
    }

    public function mahasiswaBelumBimbingan()
    {
        $mahasiswaBelum = Mahasiswa::whereNotIn('nim', function ($query) {
            $query->select('nim')->from('bimbingans');
        })->paginate(20);

        return view('admin.bimbingan.belum-bimbingan', compact('mahasiswaBelum'));
    }

    public function permohonanBelumDireview()
    {
        $permohonan = Bimbingan::where('status', 'belum_direview')
            ->with('mahasiswa', 'dosenPembimbing.dosen')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return view('admin.bimbingan.belum-direview', compact('permohonan'));
    }

    public function permohonanPerluRevisi()
    {
        $permohonan = Bimbingan::where('status', 'perlu_revisi')
            ->with('mahasiswa', 'dosenPembimbing.dosen')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return view('admin.bimbingan.perlu-revisi', compact('permohonan'));
    }

    public function dosenPembimbingPerforma()
    {
        $dosenList = DosenPembimbing::with('dosen')
            ->get()
            ->map(function ($dosen) {
                $totalMahasiswa = Mahasiswa::where('nim', $dosen->nim)->count();
                $totalBimbingan = Bimbingan::where('dosen_pembimbing_id', $dosen->id)->count();
                $disetujui = Bimbingan::where('dosen_pembimbing_id', $dosen->id)
                    ->where('status', 'disetujui')
                    ->count();

                return [
                    'dosen' => $dosen,
                    'total_mahasiswa' => $totalMahasiswa,
                    'total_bimbingan' => $totalBimbingan,
                    'disetujui' => $disetujui,
                    'performa' => $totalBimbingan > 0 ? round(($disetujui / $totalBimbingan) * 100) : 0,
                ];
            });

        return view('admin.bimbingan.dosen-performa', compact('dosenList'));
    }

    public function laporan()
    {
        $bimbingans = Bimbingan::with('mahasiswa', 'dosenPembimbing.dosen')
            ->orderBy('tanggal_bimbingan', 'desc')
            ->get();

        return view('admin.bimbingan.laporan', compact('bimbingans'));
    }
}
