<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bimbingan;
use App\Models\Mahasiswa;
use App\Models\DosenPembimbing;
use Illuminate\Http\Request;

class BimbinganMonitoringController extends Controller
{

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
        $dosenList = DosenPembimbing::with('dosen')->get()->groupBy('nidn')
            ->map(function ($assignments) {
                $reviews = Bimbingan::whereIn('dosen_pembimbing_id', $assignments->pluck('id'));
                $totalBimbingan = (clone $reviews)->count();
                $disetujui = (clone $reviews)->where('status', 'disetujui')->count();

                return [
                    'dosen' => $assignments->first(),
                    'total_mahasiswa' => $assignments->pluck('nim')->unique()->count(),
                    'total_bimbingan' => $totalBimbingan,
                    'disetujui' => $disetujui,
                    'performa' => $totalBimbingan > 0 ? round(($disetujui / $totalBimbingan) * 100) : 0,
                ];
            })->values();

        return view('admin.bimbingan.dosen-performa', compact('dosenList'));
    }

    public function laporan(Request $request)
    {
        $request->validate([
            'ta' => 'nullable|string|max:100', 'kegiatan' => 'nullable|in:KKN,PPL,PKL,Magang',
            'status' => 'nullable|in:disetujui,perlu_revisi,belum_direview',
            'tanggal_mulai' => 'nullable|date', 'tanggal_selesai' => ['nullable', 'date', ...($request->filled('tanggal_mulai') ? ['after_or_equal:tanggal_mulai'] : [])],
        ]);
        $allowed = auth('web')->user()->getAllowedKegiatan();
        if ($request->filled('kegiatan')) abort_unless(in_array($request->kegiatan, $allowed, true), 403);
        $query = Bimbingan::with('mahasiswa', 'dosenPembimbing.dosen')
            ->whereHas('mahasiswa', function ($students) use ($request, $allowed) {
                $students->withKegiatanIn($request->filled('kegiatan') ? [$request->kegiatan] : $allowed);
                if ($request->filled('ta')) $students->withTahunAkademik($request->ta);
            });
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('tanggal_mulai')) $query->whereDate('tanggal_bimbingan', '>=', $request->tanggal_mulai);
        if ($request->filled('tanggal_selesai')) $query->whereDate('tanggal_bimbingan', '<=', $request->tanggal_selesai);
        $bimbingans = $query->orderBy('tanggal_bimbingan', 'desc')->get();
        if ($request->boolean('cetak')) return view('reports.bimbingan', compact('bimbingans'));
        $tahunAkademiks = \App\Models\TahunAkademik::orderByDesc('tahun')->get();
        return view('admin.bimbingan.laporan', compact('bimbingans', 'tahunAkademiks'));
    }
}
