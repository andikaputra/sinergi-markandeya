<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Penempatankkn;
use App\Models\Penempatanppl;
use App\Models\PenempatanPkl;
use App\Models\PenempatanMagang;
use App\Models\DosenPembimbing;
use App\Models\DosenPenguji;
use App\Models\TahunAkademik;
use App\Models\Publikasi;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $jumlahKKN = Mahasiswa::where('kegiatan', 'KKN')->count();
        $jumlahPPL = Mahasiswa::where('kegiatan', 'PPL')->count();
        $jumlahPKL = Mahasiswa::where('kegiatan', 'PKL')->count();
        $jumlahMagang = Mahasiswa::where('kegiatan', 'Magang')->count();

        return view('admin.admindashboard', compact('jumlahKKN', 'jumlahPPL', 'jumlahPKL', 'jumlahMagang'));
    }

    public function pesertaKKN(Request $request)
    {
        $query = Mahasiswa::where('kegiatan', 'KKN')->with(['penempatankkn.lokasikkn', 'publikasis']);
        
        if ($request->has('ta') && $request->ta != '') {
            $query->where('tahun_akademik', $request->ta);
        }

        $pesertaKKN = $query->orderBy('kecamatan', 'asc')->get();
        $tahunAkademiks = TahunAkademik::orderBy('tahun', 'desc')->get();

        return view('admin.kkn', compact('pesertaKKN', 'tahunAkademiks'));
    }

    public function pesertaPPL(Request $request)
    {
        $query = Mahasiswa::where('kegiatan', 'PPL')->with(['penempatanppl.lokasippl', 'publikasis']);
        
        if ($request->has('ta') && $request->ta != '') {
            $query->where('tahun_akademik', $request->ta);
        }

        $pesertaPPL = $query->orderBy('kecamatan', 'asc')->get();
        $tahunAkademiks = TahunAkademik::orderBy('tahun', 'desc')->get();
    
        return view('admin.ppl', compact('pesertaPPL', 'tahunAkademiks'));
    }

    public function pesertaPKL(Request $request)
    {
        $query = Mahasiswa::where('kegiatan', 'PKL')->with(['penempatanpkl.lokasipkl', 'publikasis']);
        
        if ($request->has('ta') && $request->ta != '') {
            $query->where('tahun_akademik', $request->ta);
        }

        $pesertaPKL = $query->get();
        $tahunAkademiks = TahunAkademik::orderBy('tahun', 'desc')->get();

        return view('admin.pkl', compact('pesertaPKL', 'tahunAkademiks'));
    }

    public function pesertaMagang(Request $request)
    {
        $query = Mahasiswa::where('kegiatan', 'Magang')->with(['penempatanmagang.lokasimagang', 'publikasis']);
        
        if ($request->has('ta') && $request->ta != '') {
            $query->where('tahun_akademik', $request->ta);
        }

        $pesertaMagang = $query->get();
        $tahunAkademiks = TahunAkademik::orderBy('tahun', 'desc')->get();

        return view('admin.magang', compact('pesertaMagang', 'tahunAkademiks'));
    }

    // Magang Placement Methods
    public function assignMagangIndex()
    {
        $mahasiswas = Mahasiswa::where('kegiatan', 'Magang')->whereDoesntHave('penempatanmagang')->get();
        $lokasimagangs = \App\Models\LokasiMagang::all();
        $assignments = PenempatanMagang::with(['mahasiswa', 'lokasimagang'])->get();

        return view('admin.assignlokasimagang', compact('mahasiswas', 'lokasimagangs', 'assignments'));
    }

    public function assignMagangStore(Request $request)
    {
        $request->validate([
            'nims' => 'required|array',
            'lokasimagang' => 'required|exists:lokasi_magangs,id'
        ]);

        foreach ($request->nims as $nim) {
            PenempatanMagang::updateOrCreate(
                ['nim' => $nim],
                ['lokasi_magang_id' => $request->lokasimagang]
            );
        }

        return redirect()->back()->with('success', 'Penempatan Magang berhasil diperbarui!');
    }

    public function assignMagangDelete($id)
    {
        PenempatanMagang::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Penempatan Magang dihapus.');
    }

    public function indexdosen()
    {
        $dosens = Dosen::all();
        return view('admin.dosen', compact('dosens'));
    }

    public function createdosen()
    {
        return view('admin.tambahdosen');
    }

    public function storedosen(Request $request)
    {
        $request->validate([
            'nidn' => 'required|string|unique:dosens,nidn',
            'nama' => 'required|string',
        ]);

        Dosen::create([
            'nidn' => $request->nidn,
            'nama' => $request->nama,
            'password' => Hash::make($request->nidn),
        ]);

        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil ditambahkan.');
    }

    // Manual Student Creation
    public function createMahasiswa()
    {
        $tahunAkademiks = TahunAkademik::orderBy('tahun', 'desc')->get();
        return view('admin.mahasiswa.create', compact('tahunAkademiks'));
    }

    public function storeMahasiswa(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|unique:mahasiswas,nim',
            'email' => 'required|email|unique:mahasiswas,email',
            'prodi' => 'required',
            'kegiatan' => 'required',
            'tahun_akademik' => 'required',
            'kecamatan' => 'required',
            'kampus' => 'required',
        ]);

        Mahasiswa::create([
            'nama' => $request->nama,
            'nim' => $request->nim,
            'email' => $request->email,
            'prodi' => $request->prodi,
            'kegiatan' => $request->kegiatan,
            'tahun_akademik' => $request->tahun_akademik,
            'kecamatan' => $request->kecamatan,
            'kampus' => $request->kampus,
            'password' => Hash::make($request->nim), // Default: NIM
            'pembayaranKRS' => 'Lunas (By Admin)',
            'KRS' => 'Aktif (By Admin)'
        ]);

        // Redirect back to specific activity list
        $route = 'admin.peserta.' . strtolower($request->kegiatan);
        return redirect()->route($route)->with('success', 'Mahasiswa berhasil ditambahkan manual!');
    }

    // Fitur Import Mahasiswa CSV
    public function importMahasiswa(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|mimes:csv,txt'
        ]);

        $file = $request->file('file_csv');
        $handle = fopen($file->getPathname(), 'r');
        
        // Skip header
        fgetcsv($handle);

        $count = 0;
        $activeTA = TahunAkademik::active();
        $taString = $activeTA ? ($activeTA->tahun . ' ' . $activeTA->semester) : null;

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Format CSV: nama, nim, email, prodi, kegiatan, kecamatan, kampus
            if (count($data) >= 5) {
                Mahasiswa::updateOrCreate(
                    ['nim' => $data[1]],
                    [
                        'nama' => $data[0],
                        'email' => $data[2],
                        'prodi' => $data[3],
                        'kegiatan' => $data[4],
                        'kecamatan' => $data[5] ?? '-',
                        'kampus' => $data[6] ?? 'Markandeya',
                        'password' => Hash::make($data[1]), // Default password = NIM
                        'tahun_akademik' => $taString,
                        'pembayaranKRS' => 'Lunas (Import)',
                        'KRS' => 'Aktif (Import)'
                    ]
                );
                $count++;
            }
        }
        fclose($handle);

        return redirect()->back()->with('success', "Berhasil mengimpor $count mahasiswa!");
    }

    // Fitur Export CSV (KKN, PPL, PKL, Magang)
    public function exportKKN(Request $request)
    {
        return $this->generateCsvExport($request, 'KKN');
    }

    public function exportPPL(Request $request)
    {
        return $this->generateCsvExport($request, 'PPL');
    }

    public function exportPKL(Request $request)
    {
        return $this->generateCsvExport($request, 'PKL');
    }

    public function exportMagang(Request $request)
    {
        return $this->generateCsvExport($request, 'Magang');
    }

    private function generateCsvExport(Request $request, $kegiatan)
    {
        $fileName = 'rekap_peserta_' . strtolower($kegiatan) . '_' . date('Y-m-d') . '.csv';
        $query = Mahasiswa::where('kegiatan', $kegiatan)->with(['penempatankkn.lokasikkn', 'penempatanppl.lokasippl', 'penempatanpkl.lokasipkl', 'penempatanmagang.lokasimagang']);
        
        if ($request->has('ta') && $request->ta != '') {
            $query->where('tahun_akademik', $request->ta);
        }
        $tasks = $query->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('NIM', 'Nama Mahasiswa', 'Prodi', 'Lokasi', 'Dosen Pembimbing', 'Nilai DP', 'Dosen Penguji', 'Nilai DU', 'Nilai Akhir');

        $callback = function() use($tasks, $columns, $kegiatan) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tasks as $task) {
                $lokasi = '-';
                if ($kegiatan == 'KKN') $lokasi = $task->penempatankkn?->lokasikkn?->desa;
                elseif ($kegiatan == 'PPL') $lokasi = $task->penempatanppl?->lokasippl?->Sekolah;
                elseif ($kegiatan == 'PKL') $lokasi = $task->penempatanpkl?->lokasipkl?->nama_instansi;
                elseif ($kegiatan == 'Magang') $lokasi = $task->penempatanmagang?->lokasimagang?->nama_instansi;

                fputcsv($file, array(
                    $task->nim,
                    $task->nama,
                    $task->prodi_full,
                    $lokasi ?? '-',
                    $task->dosenPembimbing?->dosen?->nama ?? '-',
                    $task->dosenPembimbing?->nilai ?? '-',
                    $task->dosenPenguji?->dosen?->nama ?? '-',
                    $task->dosenPenguji?->nilai ?? '-',
                    $task->nilai_akhir,
                ));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // PDF / Print Reports
    public function printKKN(Request $request) { return $this->viewPrint($request, 'KKN'); }
    public function printPPL(Request $request) { return $this->viewPrint($request, 'PPL'); }
    public function printPKL(Request $request) { return $this->viewPrint($request, 'PKL'); }
    public function printMagang(Request $request) { return $this->viewPrint($request, 'Magang'); }

    private function viewPrint(Request $request, $kegiatan)
    {
        $query = Mahasiswa::where('kegiatan', $kegiatan)->with(['dosenPembimbing.dosen', 'dosenPenguji.dosen']);
        if ($request->has('ta') && $request->ta != '') {
            $query->where('tahun_akademik', $request->ta);
        }
        $peserta = $query->get();
        $title = "REKAPITULASI PESERTA " . $kegiatan;
        return view('admin.export.pdf_rekap', compact('peserta', 'title'));
    }
}
