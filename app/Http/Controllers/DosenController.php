<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Jurnal;
use App\Models\DosenPembimbing;
use Illuminate\Support\Facades\Auth;

class DosenController extends Controller
{
    public function dashboard()
    {
        $dosen = Auth::guard('dosen')->user();
        $mahasiswaBimbingan = DosenPembimbing::where('nidn', $dosen->nidn)
            ->with(['mahasiswa.penempatankkn.lokasikkn', 'mahasiswa.penempatanppl.lokasippl', 'mahasiswa.penempatanpkl.lokasipkl', 'mahasiswa.dosenPenguji'])
            ->get();

        return view('dosen.dashboard', compact('mahasiswaBimbingan'));
    }

    public function detailMahasiswa($nim)
    {
        $dosen = Auth::guard('dosen')->user();
        
        // Verifikasi bahwa mahasiswa ini memang bimbingan dosen tersebut
        $isBimbingan = DosenPembimbing::where('nidn', $dosen->nidn)
            ->where('nim', $nim)
            ->firstOrFail();

        $mahasiswa = Mahasiswa::with(['penempatankkn.lokasikkn', 'penempatanppl.lokasippl', 'penempatanpkl.lokasipkl', 'penempatanmagang.lokasimagang'])
            ->where('nim', $nim)
            ->firstOrFail();
            
        $jurnals = Jurnal::where('nim', $nim)->orderBy('tanggal', 'desc')->get();

        return view('dosen.mahasiswa_detail', compact('mahasiswa', 'jurnals', 'isBimbingan'));
    }

    public function inputNilai(Request $request, $nim)
    {
        $dosen = Auth::guard('dosen')->user();
        
        $request->validate([
            'nilai' => 'required|string|max:10'
        ]);

        $bimbingan = DosenPembimbing::where('nidn', $dosen->nidn)
            ->where('nim', $nim)
            ->firstOrFail();

        $bimbingan->update([
            'nilai' => $request->nilai
        ]);

        return back()->with('success', 'Nilai mahasiswa berhasil diperbarui!');
    }
}
