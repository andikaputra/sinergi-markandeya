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
    private function ensureTableSchema()
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('dosen_monevs')) {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('dosen_monevs', 'nim') || 
                !\Illuminate\Support\Facades\Schema::hasColumn('dosen_monevs', 'kegiatan') || 
                !\Illuminate\Support\Facades\Schema::hasColumn('dosen_monevs', 'lokasi_id') ||
                !\Illuminate\Support\Facades\Schema::hasColumn('dosen_monevs', 'link_monev') ||
                !\Illuminate\Support\Facades\Schema::hasColumn('dosen_monevs', 'foto_monev')) {
                
                try {
                    // Drop old unique index if exists
                    try {
                        \Illuminate\Support\Facades\Schema::table('dosen_monevs', function (\Illuminate\Database\Schema\Blueprint $table) {
                            $table->dropUnique('dosen_monevs_monev_type_program_id_unique');
                        });
                    } catch (\Throwable $e) {}

                    \Illuminate\Support\Facades\Schema::table('dosen_monevs', function (\Illuminate\Database\Schema\Blueprint $table) {
                        try {
                            $table->bigInteger('program_id')->nullable()->change();
                        } catch (\Throwable $e) {}

                        if (!\Illuminate\Support\Facades\Schema::hasColumn('dosen_monevs', 'nim')) {
                            $table->string('nim')->nullable()->after('nidn');
                        }
                        if (!\Illuminate\Support\Facades\Schema::hasColumn('dosen_monevs', 'kegiatan')) {
                            $table->string('kegiatan')->nullable()->after('monev_type');
                        }
                        if (!\Illuminate\Support\Facades\Schema::hasColumn('dosen_monevs', 'lokasi_id')) {
                            $table->bigInteger('lokasi_id')->nullable()->after('program_id');
                        }
                        if (!\Illuminate\Support\Facades\Schema::hasColumn('dosen_monevs', 'foto_monev')) {
                            $table->json('foto_monev')->nullable()->after('catatan');
                        }
                        if (!\Illuminate\Support\Facades\Schema::hasColumn('dosen_monevs', 'tanggal_monev')) {
                            $table->date('tanggal_monev')->nullable()->after('foto_monev');
                        }
                        if (!\Illuminate\Support\Facades\Schema::hasColumn('dosen_monevs', 'link_monev')) {
                            $table->string('link_monev', 1000)->nullable()->after('foto_monev');
                        }
                    });
                } catch (\Throwable $e) {
                    // Ignore schema migration error
                }
            }
        }
    }

    public function index(Request $request)
    {
        $this->ensureTableSchema();

        $kegiatan = strtolower($request->query('kegiatan', 'kkn'));
        $type = $request->query('type', 'individu');
        if ($kegiatan === 'pkl') {
            $type = 'individu';
        }
        $dosens = Dosen::orderBy('nama', 'asc')->get();

        $mahasiswas = collect();
        $prokers = collect();
        $lokasis = collect();
        $kelompokProkers = collect();

        $hasKegiatanCol = \Illuminate\Support\Facades\Schema::hasColumn('dosen_monevs', 'kegiatan');
        $hasLokasiCol = \Illuminate\Support\Facades\Schema::hasColumn('dosen_monevs', 'lokasi_id');
        $hasNimCol = \Illuminate\Support\Facades\Schema::hasColumn('dosen_monevs', 'nim');

        if ($type === 'individu') {
            // Get all Mahasiswa for this kegiatan
            $mahasiswas = Mahasiswa::withKegiatan(strtoupper($kegiatan))
                ->with(['penempatankkn.lokasikkn', 'penempatanppl.lokasippl', 'penempatanpkl.lokasipkl', 'penempatanmagang.lokasimagang'])
                ->orderBy('nama', 'asc')
                ->get();

            // Load any existing prokers for these students
            $prokers = IndividuProgramKerja::where('kategori', $kegiatan)->get()->keyBy('nim');

            // Active assignments
            $query = DosenMonev::where('monev_type', 'individu');
            if ($hasKegiatanCol) {
                $query->where(function ($q) use ($kegiatan) {
                    $q->where('kegiatan', $kegiatan)
                      ->orWhereNull('kegiatan')
                      ->orWhereHas('mahasiswa', function ($m) use ($kegiatan) {
                          $m->withKegiatan(strtoupper($kegiatan));
                      });
                });
            }
            $assignments = $query->with(['dosen', 'mahasiswa', 'programKerja'])
                ->orderBy('updated_at', 'desc')
                ->get();

            $existingAssignments = $hasNimCol ? $assignments->pluck('nim')->filter()->toArray() : [];
        } else {
            // Kelompok tab: load groups / locations and kelompok prokers
            if ($kegiatan === 'kkn') {
                $lokasis = LokasiKkn::with(['penempatankkn.mahasiswa'])->orderBy('desa', 'asc')->get();
            } elseif ($kegiatan === 'ppl') {
                $lokasis = LokasiPpl::with(['penempatanppl.mahasiswa'])->orderBy('Sekolah', 'asc')->get();
            } elseif ($kegiatan === 'magang') {
                $lokasis = LokasiMagang::with(['penempatanmagang.mahasiswa'])->orderBy('nama_instansi', 'asc')->get();
            }

            $kelompokProkers = KelompokProgramKerja::where('kategori', $kegiatan)->with('mahasiswaKetua')->get();

            $query = DosenMonev::where('monev_type', 'kelompok');
            if ($hasKegiatanCol) {
                $query->where(function ($q) use ($kegiatan) {
                    $q->where('kegiatan', $kegiatan)
                      ->orWhereNull('kegiatan');
                });
            }
            $assignments = $query->with(['dosen', 'lokasiKkn', 'lokasiPpl', 'lokasiPkl', 'lokasiMagang', 'programKerja'])
                ->orderBy('updated_at', 'desc')
                ->get();

            $existingAssignments = $hasLokasiCol ? $assignments->pluck('lokasi_id')->filter()->toArray() : [];
        }

        return view('admin.dosen-monev.index', compact(
            'mahasiswas', 'prokers', 'lokasis', 'kelompokProkers', 'dosens', 'kegiatan', 'type', 'assignments', 'existingAssignments'
        ));
    }

    public function store(Request $request)
    {
        $monevType = $request->input('monev_type', 'individu');
        $kegiatan = strtolower($request->input('kegiatan', 'kkn'));

        if ($kegiatan === 'pkl' && $monevType === 'kelompok') {
            return redirect()->back()->with('error', 'Penugasan monev kelompok tidak tersedia untuk kegiatan PKL.');
        }

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
                    ->where(function ($q) use ($kegiatan) {
                        $q->where('kegiatan', $kegiatan)
                          ->orWhereNull('kegiatan');
                    })
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
                    ->where(function ($q) use ($kegiatan) {
                        $q->where('kegiatan', $kegiatan)
                          ->orWhereNull('kegiatan');
                    })
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
                        'program_id' => null,
                    ]);
                }

                // Otomatis sinkronkan seluruh mahasiswa di lokasi ini agar memegang Dosen Pemonev yang sama untuk individu
                $nims = match($kegiatan) {
                    'kkn' => \App\Models\PenempatanKkn::where('lokasi_kkn_id', $lokasiId)->pluck('nim'),
                    'ppl' => \App\Models\PenempatanPpl::where('sekolah_id', $lokasiId)->pluck('nim'),
                    'magang' => \App\Models\PenempatanMagang::where('lokasi_magang_id', $lokasiId)->pluck('nim'),
                    default => collect(),
                };

                foreach ($nims as $nim) {
                    $proker = IndividuProgramKerja::where('nim', $nim)->first();
                    $mhsMonev = DosenMonev::where('monev_type', 'individu')
                        ->where(function ($q) use ($kegiatan) {
                            $q->where('kegiatan', $kegiatan)->orWhereNull('kegiatan');
                        })
                        ->where('nim', $nim)
                        ->first();

                    if ($mhsMonev) {
                        $mhsMonev->update([
                            'nidn' => $validated['nidn'],
                            'kegiatan' => $kegiatan,
                            'program_id' => $proker?->id ?? $mhsMonev->program_id,
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
            }
        }

        return redirect()->back()->with('success', 'Dosen pemonev berhasil ditugaskan dan disinkronkan ke seluruh mahasiswa kelompok!');
    }

    public function delete($id)
    {
        $monev = DosenMonev::findOrFail($id);
        
        if ($monev->monev_type === 'kelompok' && $monev->lokasi_id) {
            $kegiatan = strtolower($monev->kegiatan ?? 'kkn');
            $nims = match($kegiatan) {
                'kkn' => \App\Models\PenempatanKkn::where('lokasi_kkn_id', $monev->lokasi_id)->pluck('nim'),
                'ppl' => \App\Models\PenempatanPpl::where('sekolah_id', $monev->lokasi_id)->pluck('nim'),
                'magang' => \App\Models\PenempatanMagang::where('lokasi_magang_id', $monev->lokasi_id)->pluck('nim'),
                default => collect(),
            };

            // Hapus juga penugasan individu mahasiswa terkait yang belum dinilai
            if ($nims->isNotEmpty()) {
                DosenMonev::where('monev_type', 'individu')
                    ->whereIn('nim', $nims)
                    ->where('nidn', $monev->nidn)
                    ->whereNull('nilai')
                    ->whereNull('catatan')
                    ->delete();
            }
        }

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
                    $existing = DosenMonev::where('monev_type', 'individu')
                        ->where(function ($q) use ($kegiatan) {
                            $q->where('kegiatan', $kegiatan)
                              ->orWhereNull('kegiatan');
                        })
                        ->where('nim', $identifier)
                        ->first();

                    if ($existing) {
                        $existing->update([
                            'nidn' => $nidn,
                            'kegiatan' => $kegiatan,
                            'program_id' => $proker?->id,
                        ]);
                    } else {
                        DosenMonev::create([
                            'monev_type' => 'individu',
                            'nim' => $identifier,
                            'kegiatan' => $kegiatan,
                            'nidn' => $nidn,
                            'program_id' => $proker?->id,
                        ]);
                    }
                    $imported++;
                }
            } else {
                if (Dosen::where('nidn', $nidn)->exists()) {
                    $existing = DosenMonev::where('monev_type', 'kelompok')
                        ->where(function ($q) use ($kegiatan) {
                            $q->where('kegiatan', $kegiatan)
                              ->orWhereNull('kegiatan');
                        })
                        ->where('lokasi_id', $identifier)
                        ->first();

                    if ($existing) {
                        $existing->update([
                            'nidn' => $nidn,
                            'kegiatan' => $kegiatan,
                        ]);
                    } else {
                        DosenMonev::create([
                            'monev_type' => 'kelompok',
                            'lokasi_id' => $identifier,
                            'kegiatan' => $kegiatan,
                            'nidn' => $nidn,
                            'program_id' => null,
                        ]);
                    }
                    $imported++;
                }
            }
        }

        fclose($handle);

        return redirect()->back()->with('success', "Import berhasil! $imported penugasan ditambahkan/diupdate");
    }
}
