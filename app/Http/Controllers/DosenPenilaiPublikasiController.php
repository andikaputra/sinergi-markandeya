<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\DosenPenilaiPublikasi;
use App\Models\Jurnal;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DosenPenilaiPublikasiController extends Controller
{
    // Admin: List and Form to Assign
    public function adminIndex(Request $request)
    {
        $allowedKegiatan = Auth::user()->getAllowedKegiatan();
        $selectedKegiatan = $request->input('kegiatan');

        $filterKegiatan = $selectedKegiatan ? [$selectedKegiatan] : $allowedKegiatan;

        $mahasiswas = Mahasiswa::whereDoesntHave('dosenPenilaiPublikasi')
            ->whereIn('kegiatan', $filterKegiatan)
            ->orderBy('nama')->get();

        $dosens = Dosen::all();

        $assignments = DosenPenilaiPublikasi::with(['mahasiswa', 'dosen'])
            ->whereHas('mahasiswa', fn($q) => $q->whereIn('kegiatan', $filterKegiatan))
            ->get();

        return view('admin.assigndosenpenilai', compact('mahasiswas', 'dosens', 'assignments', 'allowedKegiatan', 'selectedKegiatan'));
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'nims' => 'required|array',
            'nidn' => 'required|exists:dosens,nidn',
        ]);

        foreach ($request->nims as $nim) {
            DosenPenilaiPublikasi::updateOrCreate(
                ['nim' => $nim],
                ['nidn' => $request->nidn]
            );
        }

        return redirect()->back()->with('success', 'Dosen Penilai Publikasi berhasil di-plot!');
    }

    public function adminDelete($id)
    {
        DosenPenilaiPublikasi::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Plotting Dosen Penilai Publikasi dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate(['file_csv' => 'required|mimes:csv,txt|max:2048']);

        $file = $request->file('file_csv');
        $handle = fopen($file->getPathname(), 'r');
        fgetcsv($handle);

        $count = 0;
        $errors = [];
        $row = 1;

        DB::beginTransaction();
        try {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row++;
                if (count($data) < 2) { $errors[] = "Baris $row: kolom kurang"; continue; }

                $nim = trim($data[0]);
                $nidn = trim($data[1]);

                if (!Mahasiswa::where('nim', $nim)->exists()) { $errors[] = "Baris $row: NIM $nim tidak ditemukan"; continue; }
                if (!Dosen::where('nidn', $nidn)->exists()) { $errors[] = "Baris $row: NIDN $nidn tidak ditemukan"; continue; }

                DosenPenilaiPublikasi::updateOrCreate(['nim' => $nim], ['nidn' => $nidn]);
                $count++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return redirect()->back()->with('error', 'Gagal mengimpor: ' . $e->getMessage());
        }
        fclose($handle);

        $message = "Berhasil mengimpor $count plotting penilai publikasi!";
        if (!empty($errors)) {
            $message .= ' (' . count($errors) . ' baris dilewati: ' . implode('; ', array_slice($errors, 0, 5)) . ')';
        }
        return redirect()->back()->with('success', $message);
    }

    // Dosen: List Mahasiswa Publikasi
    public function dosenIndex(Request $request)
    {
        $dosen = Auth::guard('dosen')->user();
        $tahunAkademiks = TahunAkademik::orderBy('is_active', 'desc')->orderBy('id', 'desc')->get();
        $activeTA = TahunAkademik::active();

        $selectedTA = $request->input('tahun_akademik', $activeTA ? ($activeTA->tahun . ' ' . $activeTA->semester) : null);
        $selectedKegiatan = $request->input('kegiatan');

        $query = DosenPenilaiPublikasi::where('nidn', $dosen->nidn)
            ->with(['mahasiswa.publikasis', 'mahasiswa.penempatankkn.lokasikkn', 'mahasiswa.penempatanppl.lokasippl', 'mahasiswa.penempatanpkl.lokasipkl', 'mahasiswa.penempatanmagang.lokasimagang'])
            ->whereHas('mahasiswa', function ($q) use ($selectedTA, $selectedKegiatan) {
                if ($selectedTA) {
                    $q->where('tahun_akademik', $selectedTA);
                }
                if ($selectedKegiatan) {
                    $q->where('kegiatan', $selectedKegiatan);
                }
            });

        $mahasiswaPublikasi = $query->get();

        return view('dosen.publikasi_index', compact('mahasiswaPublikasi', 'tahunAkademiks', 'selectedTA', 'selectedKegiatan'));
    }

    public function detailMahasiswa($nim)
    {
        $dosen = Auth::guard('dosen')->user();

        $isPenilai = DosenPenilaiPublikasi::where('nidn', $dosen->nidn)
            ->where('nim', $nim)
            ->firstOrFail();

        $mahasiswa = Mahasiswa::with([
            'penempatankkn.lokasikkn', 'penempatanppl.lokasippl',
            'penempatanpkl.lokasipkl', 'penempatanmagang.lokasimagang',
            'publikasis',
        ])->where('nim', $nim)->firstOrFail();

        $jurnals = Jurnal::where('nim', $nim)->orderBy('tanggal', 'desc')->get();

        return view('dosen.publikasi_detail', compact('mahasiswa', 'jurnals', 'isPenilai'));
    }

    public function inputNilai(Request $request, $nim)
    {
        $dosen = Auth::guard('dosen')->user();

        $request->validate([
            'nilai_ketercapaian' => 'required|numeric|min:0|max:100',
            'nilai_sistematika' => 'required|numeric|min:0|max:100',
            'nilai_kelayakan' => 'required|numeric|min:0|max:100',
            'nilai_presentasi' => 'required|numeric|min:0|max:100',
            'nilai_mempertahankan' => 'required|numeric|min:0|max:100',
        ]);

        $penilai = DosenPenilaiPublikasi::where('nidn', $dosen->nidn)
            ->where('nim', $nim)
            ->firstOrFail();

        $nilaiRata = round(($request->nilai_ketercapaian + $request->nilai_sistematika + $request->nilai_kelayakan + $request->nilai_presentasi + $request->nilai_mempertahankan) / 5, 1);

        $penilai->update([
            'nilai_ketercapaian' => $request->nilai_ketercapaian,
            'nilai_sistematika' => $request->nilai_sistematika,
            'nilai_kelayakan' => $request->nilai_kelayakan,
            'nilai_presentasi' => $request->nilai_presentasi,
            'nilai_mempertahankan' => $request->nilai_mempertahankan,
            'nilai' => $nilaiRata,
        ]);

        return redirect()->back()->with('success', 'Nilai publikasi & diseminasi berhasil disimpan!');
    }
}
