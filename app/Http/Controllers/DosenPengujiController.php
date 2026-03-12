<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\DosenPenguji;
use App\Models\Jurnal;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenPengujiController extends Controller
{
    // Admin: List and Form to Assign
    public function adminIndex(Request $request)
    {
        $allowedKegiatan = Auth::user()->getAllowedKegiatan();

        $mahasiswas = Mahasiswa::whereDoesntHave('dosenPenguji')
            ->whereIn('kegiatan', $allowedKegiatan)
            ->orderBy('nama')->get();

        $dosens = Dosen::all();

        $assignments = DosenPenguji::with(['mahasiswa', 'dosen'])
            ->whereHas('mahasiswa', fn($q) => $q->whereIn('kegiatan', $allowedKegiatan))
            ->get();

        return view('admin.assigndosenpenguji', compact('mahasiswas', 'dosens', 'assignments'));
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'nims' => 'required|array',
            'nidn' => 'required|exists:dosens,nidn',
        ]);

        foreach ($request->nims as $nim) {
            DosenPenguji::updateOrCreate(
                ['nim' => $nim],
                ['nidn' => $request->nidn]
            );
        }

        return redirect()->back()->with('success', 'Dosen Penguji berhasil di-plot!');
    }

    public function adminDelete($id)
    {
        DosenPenguji::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Plotting Dosen Penguji dihapus.');
    }

    // Dosen: List Mahasiswa Ujian
    public function dosenIndex(Request $request)
    {
        $dosen = Auth::guard('dosen')->user();
        $tahunAkademiks = TahunAkademik::orderBy('is_active', 'desc')->orderBy('id', 'desc')->get();
        $activeTA = TahunAkademik::active();

        $selectedTA = $request->input('tahun_akademik', $activeTA ? ($activeTA->tahun . ' ' . $activeTA->semester) : null);
        $selectedKegiatan = $request->input('kegiatan');

        $query = DosenPenguji::where('nidn', $dosen->nidn)
            ->with(['mahasiswa.publikasis', 'mahasiswa.penempatankkn.lokasikkn', 'mahasiswa.penempatanppl.lokasippl', 'mahasiswa.penempatanpkl.lokasipkl', 'mahasiswa.penempatanmagang.lokasimagang'])
            ->whereHas('mahasiswa', function ($q) use ($selectedTA, $selectedKegiatan) {
                if ($selectedTA) {
                    $q->where('tahun_akademik', $selectedTA);
                }
                if ($selectedKegiatan) {
                    $q->where('kegiatan', $selectedKegiatan);
                }
            });

        $mahasiswaUjian = $query->get();

        return view('dosen.ujian_index', compact('mahasiswaUjian', 'tahunAkademiks', 'selectedTA', 'selectedKegiatan'));
    }

    public function detailMahasiswa($nim)
    {
        $dosen = Auth::guard('dosen')->user();

        $isUjian = DosenPenguji::where('nidn', $dosen->nidn)
            ->where('nim', $nim)
            ->firstOrFail();

        $mahasiswa = Mahasiswa::with([
            'penempatankkn.lokasikkn', 'penempatanppl.lokasippl',
            'penempatanpkl.lokasipkl', 'penempatanmagang.lokasimagang',
            'publikasis',
        ])->where('nim', $nim)->firstOrFail();

        $jurnals = Jurnal::where('nim', $nim)->orderBy('tanggal', 'desc')->get();

        return view('dosen.ujian_detail', compact('mahasiswa', 'jurnals', 'isUjian'));
    }

    public function inputNilai(Request $request, $nim)
    {
        $dosen = Auth::guard('dosen')->user();
        
        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100'
        ]);

        $ujian = DosenPenguji::where('nidn', $dosen->nidn)
            ->where('nim', $nim)
            ->firstOrFail();

        $ujian->update([
            'nilai' => $request->nilai
        ]);

        return redirect()->back()->with('success', 'Nilai ujian berhasil disimpan!');
    }
}
