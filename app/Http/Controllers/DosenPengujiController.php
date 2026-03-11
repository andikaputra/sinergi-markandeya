<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\DosenPenguji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenPengujiController extends Controller
{
    // Admin: List and Form to Assign
    public function adminIndex()
    {
        $mahasiswas = Mahasiswa::whereDoesntHave('dosenPenguji')->get();
        $dosens = Dosen::all();
        $assignments = DosenPenguji::with(['mahasiswa', 'dosen'])->get();

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
    public function dosenIndex()
    {
        $dosen = Auth::guard('dosen')->user();
        $mahasiswaUjian = DosenPenguji::where('nidn', $dosen->nidn)
            ->with(['mahasiswa.publikasis'])
            ->get();

        return view('dosen.ujian_index', compact('mahasiswaUjian'));
    }

    public function inputNilai(Request $request, $nim)
    {
        $dosen = Auth::guard('dosen')->user();
        
        $request->validate([
            'nilai' => 'required|string|max:10'
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
