<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\DosenPenguji;
use App\Models\Jurnal;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DosenPengujiController extends Controller
{
    public function adminIndex(Request $request)
    {
        $allowedKegiatan = Auth::user()->getAllowedKegiatan();
        $selectedKegiatan = $request->input('kegiatan');

        $filterKegiatan = $selectedKegiatan ? [$selectedKegiatan] : $allowedKegiatan;

        $mahasiswas = Mahasiswa::whereDoesntHave('dosenPenguji')
            ->withKegiatanIn($filterKegiatan)
            ->orderBy('nama')->get();

        $dosens = Dosen::all();

        $assignments = DosenPenguji::with(['mahasiswa', 'dosen'])
            ->whereHas('mahasiswa', fn($q) => $q->withKegiatanIn($filterKegiatan))
            ->get();

        return view('admin.assigndosenpenguji', compact('mahasiswas', 'dosens', 'assignments', 'allowedKegiatan', 'selectedKegiatan'));
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

                DosenPenguji::updateOrCreate(['nim' => $nim], ['nidn' => $nidn]);
                $count++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return redirect()->back()->with('error', 'Gagal mengimpor: ' . $e->getMessage());
        }
        fclose($handle);

        $message = "Berhasil mengimpor $count plotting dosen penguji!";
        if (!empty($errors)) {
            $message .= ' (' . count($errors) . ' baris dilewati: ' . implode('; ', array_slice($errors, 0, 5)) . ')';
        }
        return redirect()->back()->with('success', $message);
    }

    public function dosenIndex(Request $request)
    {
        $dosen = Auth::guard('dosen')->user();
        $tahunAkademiks = TahunAkademik::orderBy('is_active', 'desc')->orderBy('id', 'desc')->get();
        $activeTA = TahunAkademik::active();

        $selectedTA = $request->input('tahun_akademik', $activeTA ? ($activeTA->tahun . ' ' . $activeTA->semester) : null);
        $selectedKegiatan = $request->input('kegiatan');

        $query = DosenPenguji::where('nidn', $dosen->nidn)
            ->with(['mahasiswa.publikasis', 'mahasiswa.penempatankkn.lokasikkn', 'mahasiswa.penempatanppl.lokasippl', 'mahasiswa.penempatanpkl.lokasipkl', 'mahasiswa.penempatanmagang.lokasimagang', 'mahasiswa.activeKegiatan'])
            ->whereHas('mahasiswa', function ($q) use ($selectedTA, $selectedKegiatan) {
                if ($selectedTA) {
                    $q->withTahunAkademik($selectedTA);
                }
                if ($selectedKegiatan) {
                    $q->withKegiatan($selectedKegiatan);
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
            'publikasis', 'activeKegiatan',
        ])->where('nim', $nim)->firstOrFail();

        $jurnals = Jurnal::where('nim', $nim)->orderBy('tanggal', 'desc')->get();

        return view('dosen.ujian_detail', compact('mahasiswa', 'jurnals', 'isUjian'));
    }

    public function inputNilai(Request $request, $nim)
    {
        $dosen = Auth::guard('dosen')->user();

        $ujian = DosenPenguji::where('nidn', $dosen->nidn)
            ->where('nim', $nim)
            ->firstOrFail();

        $mahasiswa = Mahasiswa::with('activeKegiatan')->where('nim', $nim)->firstOrFail();

        if ($mahasiswa->kegiatan === 'PPL') {
            $request->validate([
                'nilai' => 'required|numeric|min:0|max:100',
            ]);

            $ujian->update([
                'nilai' => $request->nilai,
            ]);
        } else {
            $request->validate([
                'nilai_keterlaksanaan' => 'required|numeric|min:0|max:100',
                'nilai_kontribusi' => 'required|numeric|min:0|max:100',
                'nilai_kerjasama' => 'required|numeric|min:0|max:100',
                'nilai_kreativitas' => 'required|numeric|min:0|max:100',
                'nilai_partisipasi' => 'required|numeric|min:0|max:100',
            ]);

            $nilaiRata = round(($request->nilai_keterlaksanaan + $request->nilai_kontribusi + $request->nilai_kerjasama + $request->nilai_kreativitas + $request->nilai_partisipasi) / 5, 1);

            $ujian->update([
                'nilai_keterlaksanaan' => $request->nilai_keterlaksanaan,
                'nilai_kontribusi' => $request->nilai_kontribusi,
                'nilai_kerjasama' => $request->nilai_kerjasama,
                'nilai_kreativitas' => $request->nilai_kreativitas,
                'nilai_partisipasi' => $request->nilai_partisipasi,
                'nilai' => $nilaiRata,
            ]);
        }

        return redirect()->back()->with('success', 'Nilai ujian berhasil disimpan!');
    }
}
