<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PembimbingLuar;
use App\Models\PembimbingLuarMahasiswa;
use App\Models\Mahasiswa;
use App\Models\Jurnal;
use App\Models\TahunAkademik;
use Illuminate\Support\Facades\Auth;

class PembimbingLuarDashboardController extends Controller
{
    public function beranda()
    {
        $pembimbing = Auth::guard('pembimbing_luar')->user();
        $activeTA = TahunAkademik::active();
        $taString = $activeTA ? ($activeTA->tahun . ' ' . $activeTA->semester) : null;

        $bimbinganQuery = PembimbingLuarMahasiswa::where('pembimbing_luar_id', $pembimbing->id);
        if ($taString) {
            $bimbinganQuery->whereHas('mahasiswa', fn($q) => $q->where('tahun_akademik', $taString));
        }
        $totalBimbingan = $bimbinganQuery->count();

        $bimbinganAll = PembimbingLuarMahasiswa::where('pembimbing_luar_id', $pembimbing->id)
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

        $sudahDinilai = $bimbinganAll->filter(fn($i) => $i->nilai !== null)->count();
        $belumDinilai = $totalBimbingan - $sudahDinilai;

        return view('pembimbing_luar.beranda', compact(
            'pembimbing', 'activeTA', 'totalBimbingan',
            'countKKN', 'countPPL', 'countPKL', 'countMagang',
            'sudahDinilai', 'belumDinilai'
        ));
    }

    public function bimbingan(Request $request)
    {
        $pembimbing = Auth::guard('pembimbing_luar')->user();
        $tahunAkademiks = TahunAkademik::orderBy('is_active', 'desc')->orderBy('id', 'desc')->get();
        $activeTA = TahunAkademik::active();

        $selectedTA = $request->input('tahun_akademik', $activeTA ? ($activeTA->tahun . ' ' . $activeTA->semester) : null);
        $selectedKegiatan = $request->input('kegiatan');

        $query = PembimbingLuarMahasiswa::where('pembimbing_luar_id', $pembimbing->id)
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

        return view('pembimbing_luar.bimbingan', compact('mahasiswaBimbingan', 'tahunAkademiks', 'selectedTA', 'selectedKegiatan'));
    }

    public function detailMahasiswa($nim)
    {
        $pembimbing = Auth::guard('pembimbing_luar')->user();

        $isBimbingan = PembimbingLuarMahasiswa::where('pembimbing_luar_id', $pembimbing->id)
            ->where('nim', $nim)
            ->firstOrFail();

        $mahasiswa = Mahasiswa::with(['penempatankkn.lokasikkn', 'penempatanppl.lokasippl', 'penempatanpkl.lokasipkl', 'penempatanmagang.lokasimagang', 'publikasis'])
            ->where('nim', $nim)
            ->firstOrFail();

        $jurnals = Jurnal::where('nim', $nim)->orderBy('tanggal', 'desc')->get();

        return view('pembimbing_luar.mahasiswa_detail', compact('mahasiswa', 'jurnals', 'isBimbingan'));
    }

    public function inputNilai(Request $request, $nim)
    {
        $pembimbing = Auth::guard('pembimbing_luar')->user();

        $bimbingan = PembimbingLuarMahasiswa::where('pembimbing_luar_id', $pembimbing->id)
            ->where('nim', $nim)
            ->firstOrFail();

        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();

        if ($mahasiswa->kegiatan === 'PKL' || $mahasiswa->kegiatan === 'Magang') {
            // PKL/Magang: bobot 15%, 15%, 15%, 15% = total 60%
            $request->validate([
                'nilai_pkl_disiplin' => 'required|numeric|min:0|max:100',
                'nilai_pkl_inisiatif' => 'required|numeric|min:0|max:100',
                'nilai_pkl_kualitas' => 'required|numeric|min:0|max:100',
                'nilai_pkl_skill' => 'required|numeric|min:0|max:100',
            ]);

            // Nilai tertimbang: (score * bobot) / total bobot
            $nilaiAkhir = round(
                ($request->nilai_pkl_disiplin * 15
                + $request->nilai_pkl_inisiatif * 15
                + $request->nilai_pkl_kualitas * 15
                + $request->nilai_pkl_skill * 15) / 60,
                1
            );

            $bimbingan->update([
                'nilai_pkl_disiplin' => $request->nilai_pkl_disiplin,
                'nilai_pkl_inisiatif' => $request->nilai_pkl_inisiatif,
                'nilai_pkl_kualitas' => $request->nilai_pkl_kualitas,
                'nilai_pkl_skill' => $request->nilai_pkl_skill,
                'nilai' => $nilaiAkhir,
            ]);
        } else {
            // KKN/PPL: rata-rata biasa
            $request->validate([
                'nilai_kehadiran' => 'required|numeric|min:0|max:100',
                'nilai_luaran' => 'required|numeric|min:0|max:100',
                'nilai_keterlibatan' => 'required|numeric|min:0|max:100',
                'nilai_inovatif' => 'required|numeric|min:0|max:100',
                'nilai_sosialisasi' => 'required|numeric|min:0|max:100',
            ]);

            $nilaiRata = round(($request->nilai_kehadiran + $request->nilai_luaran + $request->nilai_keterlibatan + $request->nilai_inovatif + $request->nilai_sosialisasi) / 5, 1);

            $bimbingan->update([
                'nilai_kehadiran' => $request->nilai_kehadiran,
                'nilai_luaran' => $request->nilai_luaran,
                'nilai_keterlibatan' => $request->nilai_keterlibatan,
                'nilai_inovatif' => $request->nilai_inovatif,
                'nilai_sosialisasi' => $request->nilai_sosialisasi,
                'nilai' => $nilaiRata,
            ]);
        }

        return back()->with('success', 'Nilai mahasiswa berhasil diperbarui!');
    }
}
