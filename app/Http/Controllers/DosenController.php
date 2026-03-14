<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Jurnal;
use App\Models\DosenPembimbing;
use App\Models\DosenPenguji;
use App\Models\TahunAkademik;
use Illuminate\Support\Facades\Auth;

class DosenController extends Controller
{
    public function beranda()
    {
        $dosen = Auth::guard('dosen')->user();
        $activeTA = TahunAkademik::active();
        $taString = $activeTA ? ($activeTA->tahun . ' ' . $activeTA->semester) : null;

        // Hitung mahasiswa bimbingan (TA aktif)
        $bimbinganQuery = DosenPembimbing::where('nidn', $dosen->nidn);
        if ($taString) {
            $bimbinganQuery->whereHas('mahasiswa', fn($q) => $q->where('tahun_akademik', $taString));
        }
        $totalBimbingan = $bimbinganQuery->count();

        // Hitung mahasiswa ujian (TA aktif)
        $ujianQuery = DosenPenguji::where('nidn', $dosen->nidn);
        if ($taString) {
            $ujianQuery->whereHas('mahasiswa', fn($q) => $q->where('tahun_akademik', $taString));
        }
        $totalUjian = $ujianQuery->count();

        // Hitung per kegiatan (bimbingan TA aktif)
        $bimbinganAll = DosenPembimbing::where('nidn', $dosen->nidn)
            ->with('mahasiswa')
            ->whereHas('mahasiswa', function ($q) use ($taString) {
                if ($taString) {
                    $q->where('tahun_akademik', $taString);
                }
            })->get();

        $countKKN = $bimbinganAll->filter(fn($i) => $i->mahasiswa->kegiatan == 'KKN')->count();
        $countPPL = $bimbinganAll->filter(fn($i) => $i->mahasiswa->kegiatan == 'PPL')->count();
        $countPKL = $bimbinganAll->filter(fn($i) => $i->mahasiswa->kegiatan == 'PKL')->count();
        $countMagang = $bimbinganAll->filter(fn($i) => $i->mahasiswa->kegiatan == 'Magang')->count();

        // Sudah dinilai vs belum
        $sudahDinilai = $bimbinganAll->filter(fn($i) => $i->nilai !== null)->count();
        $belumDinilai = $totalBimbingan - $sudahDinilai;

        return view('dosen.beranda', compact(
            'dosen', 'activeTA', 'totalBimbingan', 'totalUjian',
            'countKKN', 'countPPL', 'countPKL', 'countMagang',
            'sudahDinilai', 'belumDinilai'
        ));
    }

    public function bimbingan(Request $request)
    {
        $dosen = Auth::guard('dosen')->user();
        $tahunAkademiks = TahunAkademik::orderBy('is_active', 'desc')->orderBy('id', 'desc')->get();
        $activeTA = TahunAkademik::active();

        // Default to active tahun akademik
        $selectedTA = $request->input('tahun_akademik', $activeTA ? ($activeTA->tahun . ' ' . $activeTA->semester) : null);
        $selectedKegiatan = $request->input('kegiatan');

        $query = DosenPembimbing::where('nidn', $dosen->nidn)
            ->with(['mahasiswa.penempatankkn.lokasikkn', 'mahasiswa.penempatanppl.lokasippl', 'mahasiswa.penempatanpkl.lokasipkl', 'mahasiswa.penempatanmagang.lokasimagang', 'mahasiswa.dosenPenguji', 'mahasiswa.publikasis'])
            ->whereHas('mahasiswa', function ($q) use ($selectedTA, $selectedKegiatan) {
                if ($selectedTA) {
                    $q->where('tahun_akademik', $selectedTA);
                }
                if ($selectedKegiatan) {
                    $q->where('kegiatan', $selectedKegiatan);
                }
            });

        $mahasiswaBimbingan = $query->get();

        return view('dosen.bimbingan', compact('mahasiswaBimbingan', 'tahunAkademiks', 'selectedTA', 'selectedKegiatan'));
    }

    public function detailMahasiswa($nim)
    {
        $dosen = Auth::guard('dosen')->user();
        
        // Verifikasi bahwa mahasiswa ini memang bimbingan dosen tersebut
        $isBimbingan = DosenPembimbing::where('nidn', $dosen->nidn)
            ->where('nim', $nim)
            ->firstOrFail();

        $mahasiswa = Mahasiswa::with(['penempatankkn.lokasikkn', 'penempatanppl.lokasippl', 'penempatanpkl.lokasipkl', 'penempatanmagang.lokasimagang', 'publikasis'])
            ->where('nim', $nim)
            ->firstOrFail();
            
        $jurnals = Jurnal::where('nim', $nim)->orderBy('tanggal', 'desc')->get();

        return view('dosen.mahasiswa_detail', compact('mahasiswa', 'jurnals', 'isBimbingan'));
    }

    public function inputNilai(Request $request, $nim)
    {
        $dosen = Auth::guard('dosen')->user();

        $bimbingan = DosenPembimbing::where('nidn', $dosen->nidn)
            ->where('nim', $nim)
            ->firstOrFail();

        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();

        if ($mahasiswa->kegiatan === 'PKL' || $mahasiswa->kegiatan === 'Magang') {
            // PKL/Magang: bobot 15%, 10%, 15% = total 40%
            $request->validate([
                'nilai_pkl_laporan' => 'required|numeric|min:0|max:100',
                'nilai_pkl_relevansi' => 'required|numeric|min:0|max:100',
                'nilai_pkl_presentasi' => 'required|numeric|min:0|max:100',
            ]);

            $nilaiAkhir = round(
                ($request->nilai_pkl_laporan * 15
                + $request->nilai_pkl_relevansi * 10
                + $request->nilai_pkl_presentasi * 15) / 40,
                1
            );

            $bimbingan->update([
                'nilai_pkl_laporan' => $request->nilai_pkl_laporan,
                'nilai_pkl_relevansi' => $request->nilai_pkl_relevansi,
                'nilai_pkl_presentasi' => $request->nilai_pkl_presentasi,
                'nilai' => $nilaiAkhir,
            ]);
        } else {
            // KKN/PPL: nilai langsung
            $request->validate([
                'nilai' => 'required|numeric|min:0|max:100'
            ]);

            $bimbingan->update([
                'nilai' => $request->nilai
            ]);
        }

        return back()->with('success', 'Nilai mahasiswa berhasil diperbarui!');
    }
}
