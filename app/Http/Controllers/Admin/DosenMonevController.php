<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DosenMonevController extends Controller
{
    public function index(Request $request)
    {
        $kegiatan = $request->query('kegiatan', 'kkn');
        $type = $request->query('type', 'individu');

        if ($type === 'individu') {
            $programs = \App\Models\IndividuProgramKerja::where('kategori', $kegiatan)
                ->with('dosenMonev.dosen')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $programs = \App\Models\KelompokProgramKerja::where('kategori', $kegiatan)
                ->with('dosenMonev.dosen')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $dosens = \App\Models\Dosen::all();
        $existingAssignments = \App\Models\DosenMonev::where('monev_type', $type)
            ->pluck('program_id')
            ->toArray();

        return view('admin.dosen-monev.index', compact('programs', 'dosens', 'kegiatan', 'type', 'existingAssignments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nidn' => 'required|exists:dosens,nidn',
            'program_ids' => 'required|array',
            'program_ids.*' => 'required|integer',
            'monev_type' => 'required|in:individu,kelompok',
        ]);

        foreach ($validated['program_ids'] as $programId) {
            \App\Models\DosenMonev::updateOrCreate(
                ['monev_type' => $validated['monev_type'], 'program_id' => $programId],
                ['nidn' => $validated['nidn']]
            );
        }

        return redirect()->back()->with('success', 'Dosen pemonev berhasil ditugaskan');
    }

    public function delete($id)
    {
        $monev = \App\Models\DosenMonev::findOrFail($id);
        $monev->delete();

        return redirect()->back()->with('success', 'Penugasan dosen pemonev berhasil dihapus');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
            'monev_type' => 'required|in:individu,kelompok',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');
        fgetcsv($handle); // Skip header

        $imported = 0;
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 2) continue;

            $nim = trim($row[0]);
            $nidn = trim($row[1]);

            if (empty($nim) || empty($nidn)) continue;

            $program = null;
            if ($request->input('monev_type') === 'individu') {
                $program = \App\Models\IndividuProgramKerja::where('nim', $nim)->first();
            } else {
                $program = \App\Models\KelompokProgramKerja::where('nim_ketua', $nim)->first();
            }

            if ($program && \App\Models\Dosen::where('nidn', $nidn)->exists()) {
                \App\Models\DosenMonev::updateOrCreate(
                    ['monev_type' => $request->input('monev_type'), 'program_id' => $program->id],
                    ['nidn' => $nidn]
                );
                $imported++;
            }
        }

        fclose($handle);

        return redirect()->back()->with('success', "Import berhasil! $imported penugasan ditambahkan/diupdate");
    }
}
