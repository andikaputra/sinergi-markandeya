<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\DosenMonev;
use App\Models\IndividuProgramKerja;
use App\Models\KelompokProgramKerja;
use App\Models\LokasiKkn;
use App\Models\LokasiPpl;
use App\Models\LokasiPkl;
use App\Models\LokasiMagang;

class DosenMonevController extends Controller
{
    public function index(Request $request)
    {
        $kegiatan = strtolower($request->query('kegiatan', 'kkn'));
        $type = $request->query('type', 'individu');
        $dosens = Dosen::orderBy('nama', 'asc')->get();

        if ($type === 'individu') {
            // Get all Mahasiswa for this kegiatan
            $mahasiswas = Mahasiswa::withKegiatan(strtoupper($kegiatan))
                ->with(['penempatankkn.lokasikkn', 'penempatanppl.lokasippl', 'penempatanpkl.lokasipkl', 'penempatanmagang.lokasimagang'])
                ->orderBy('nama', 'asc')
                ->get();

            // Load any existing prokers for these students
            $prokers = IndividuProgramKerja::where('kategori', $kegiatan)->get()->keyBy('nim');

            // Active assignments
            $assignments = DosenMonev::where('monev_type', 'individu')
                ->where(function ($q) use ($kegiatan) {
                    $q->where('kegiatan', $kegiatan)
                      ->orWhereHas('mahasiswa', function ($m) use ($kegiatan) {
                          $m->withKegiatan(strtoupper($kegiatan));
                      });
                })
                ->with(['dosen', 'mahasiswa'])
                ->orderBy('updated_at', 'desc')
                ->get();

            $existingAssignments = $assignments->pluck('nim')->filter()->toArray();

            return view('admin.dosen-monev.index', compact(
                'mahasiswas', 'prokers', 'dosens', 'kegiatan', 'type', 'assignments', 'existingAssignments'
            ));
        } else {
            // Kelompok tab: load groups / locations and kelompok prokers
            $lokasis = collect();
            if ($kegiatan === 'kkn') {
                $lokasis = LokasiKkn::with(['penempatankkn.mahasiswa'])->orderBy('desa', 'asc')->get();
            } elseif ($kegiatan === 'ppl') {
                $lokasis = LokasiPpl::with(['penempatanppl.mahasiswa'])->orderBy('nama_sekolah', 'asc')->get();
            } elseif ($kegiatan === 'pkl') {
                $lokasis = LokasiPkl::with(['penempatanpkl.mahasiswa'])->orderBy('nama_instansi', 'asc')->get();
            } elseif ($kegiatan === 'magang') {
                $lokasis = LokasiMagang::with(['penempatanmagang.mahasiswa'])->orderBy('nama_instansi', 'asc')->get();
            }

            $kelompokProkers = KelompokProgramKerja::where('kategori', $kegiatan)->with('mahasiswaKetua')->get();

            $assignments = DosenMonev::where('monev_type', 'kelompok')
                ->where('kegiatan', $kegiatan)
                ->with(['dosen', 'lokasiKkn', 'lokasiPpl', 'lokasiPkl', 'lokasiMagang'])
                ->orderBy('updated_at', 'desc')
                ->get();

            $existingAssignments = $assignments->pluck('lokasi_id')->filter()->toArray();

            return view('admin.dosen-monev.index', compact(
                'lokasis', 'kelompokProkers', 'dosens', 'kegiatan', 'type', 'assignments', 'existingAssignments'
            ));
        }
    }

    public function store(Request $request)
    {
        $monevType = $request->input('monev_type', 'individu');
        $kegiatan = strtolower($request->input('kegiatan', 'kkn'));

        if ($monevType === 'individu') {
            $validated = $request->validate([
                'nidn' => 'required|exists:dosens,nidn',
                'nims' => 'required|array',
                'nims.*' => 'required|exists:mahasiswas,nim',
            ], [
                'nims.required' => 'Pilih minimal satu mahasiswa untuk ditugaskan',
                'nidn.required' => 'Pilih dosen pemonev terlebih dahulu',
            ]);

            foreach ($validated['nims'] as $nim) {
                $proker = IndividuProgramKerja::where('nim', $nim)->first();
                $existing = DosenMonev::where('monev_type', 'individu')
                    ->where(function ($q) use ($nim, $proker) {
                        $q->where('nim', $nim);
                        if ($proker) {
                            $q->orWhere('program_id', $proker->id);
                        }
                    })
                    ->first();

                if ($existing) {
                    $existing->update([
                        'nidn' => $validated['nidn'],
                        'nim' => $nim,
                        'kegiatan' => $kegiatan,
                        'program_id' => $proker?->id,
                    ]);
                } else {
                    DosenMonev::create([
                        'monev_type' => 'individu',
                        'nim' => $nim,
                        'kegiatan' => $kegiatan,
                        'nidn' => $validated['nidn'],
                        'program_id' => $proker?->id,
                    ]);
                }
            }
        } else {
            $validated = $request->validate([
                'nidn' => 'required|exists:dosens,nidn',
                'lokasi_ids' => 'required|array',
                'lokasi_ids.*' => 'required|integer',
            ], [
                'lokasi_ids.required' => 'Pilih minimal satu kelompok/lokasi untuk ditugaskan',
                'nidn.required' => 'Pilih dosen pemonev terlebih dahulu',
            ]);

            foreach ($validated['lokasi_ids'] as $lokasiId) {
                $existing = DosenMonev::where('monev_type', 'kelompok')
                    ->where('kegiatan', $kegiatan)
                    ->where(function ($q) use ($lokasiId) {
                        $q->where('lokasi_id', $lokasiId)
                          ->orWhere('program_id', $lokasiId);
                    })
                    ->first();

                if ($existing) {
                    $existing->update([
                        'nidn' => $validated['nidn'],
                        'lokasi_id' => $lokasiId,
                        'kegiatan' => $kegiatan,
                    ]);
                } else {
                    DosenMonev::create([
                        'monev_type' => 'kelompok',
                        'lokasi_id' => $lokasiId,
                        'kegiatan' => $kegiatan,
                        'nidn' => $validated['nidn'],
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Dosen pemonev berhasil ditugaskan!');
    }

    public function delete($id)
    {
        $monev = DosenMonev::findOrFail($id);
        $monev->delete();

        return redirect()->back()->with('success', 'Penugasan dosen pemonev berhasil dihapus');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
            'monev_type' => 'required|in:individu,kelompok',
            'kegiatan' => 'nullable|string',
        ]);

        $kegiatan = strtolower($request->input('kegiatan', 'kkn'));
        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');
        fgetcsv($handle); // Skip header

        $imported = 0;
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 2) continue;

            $identifier = trim($row[0]); // NIM for individu or Lokasi ID for kelompok
            $nidn = trim($row[1]);

            if (empty($identifier) || empty($nidn)) continue;

            if ($request->input('monev_type') === 'individu') {
                if (Mahasiswa::where('nim', $identifier)->exists() && Dosen::where('nidn', $nidn)->exists()) {
                    $proker = IndividuProgramKerja::where('nim', $identifier)->first();
                    DosenMonev::updateOrCreate(
                        [
                            'monev_type' => 'individu',
                            'nim' => $identifier,
                            'kegiatan' => $kegiatan,
                        ],
                        [
                            'nidn' => $nidn,
                            'program_id' => $proker?->id,
                        ]
                    );
                    $imported++;
                }
            } else {
                if (Dosen::where('nidn', $nidn)->exists()) {
                    DosenMonev::updateOrCreate(
                        [
                            'monev_type' => 'kelompok',
                            'lokasi_id' => $identifier,
                            'kegiatan' => $kegiatan,
                        ],
                        [
                            'nidn' => $nidn,
                        ]
                    );
                    $imported++;
                }
            }
        }

        fclose($handle);

        return redirect()->back()->with('success', "Import berhasil! $imported penugasan ditambahkan/diupdate");
    }
}
