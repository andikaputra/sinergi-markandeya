<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswa;
use App\Models\MahasiswaKegiatan;
use App\Models\Dosen;
use App\Models\DosenPembimbing;

class DosenPembimbingController extends Controller
{
    public function index()
    {
        $mahasiswas = Mahasiswa::withKegiatan('KKN')
            ->whereDoesntHave('dosenPembimbing')
            ->orderBy('kecamatan', 'asc')
            ->get();

        $dosens = Dosen::all();

        $assignments = DosenPembimbing::whereHas('mahasiswa', function($query) {
            $query->withKegiatan('KKN');
        })->with(['mahasiswa', 'dosen'])->get();

        return view('admin.assigndosenkkn', compact('mahasiswas', 'dosens', 'assignments'));
    }

    public function indexppl()
    {
        $mahasiswas = Mahasiswa::withKegiatan('PPL')
            ->whereDoesntHave('dosenPembimbing')
            ->orderBy('kecamatan', 'asc')
            ->get();

        $dosens = Dosen::all();

        $assignments = DosenPembimbing::whereHas('mahasiswa', function($query) {
            $query->withKegiatan('PPL');
        })->with(['mahasiswa', 'dosen'])->get();

        return view('admin.assigndosenppl', compact('mahasiswas', 'dosens', 'assignments'));
    }

    public function indexpkl()
    {
        $mahasiswas = Mahasiswa::withKegiatan('PKL')
            ->whereDoesntHave('dosenPembimbing')
            ->get();

        $dosens = Dosen::all();

        $assignments = DosenPembimbing::whereHas('mahasiswa', function($query) {
            $query->withKegiatan('PKL');
        })->with(['mahasiswa', 'dosen'])->get();

        return view('admin.assigndosenpkl', compact('mahasiswas', 'dosens', 'assignments'));
    }

    public function indexmagang()
    {
        $mahasiswas = Mahasiswa::withKegiatan('Magang')
            ->whereDoesntHave('dosenPembimbing')
            ->get();

        $dosens = Dosen::all();

        $assignments = DosenPembimbing::whereHas('mahasiswa', function($query) {
            $query->withKegiatan('Magang');
        })->with(['mahasiswa', 'dosen'])->get();

        return view('admin.assigndosenmagang', compact('mahasiswas', 'dosens', 'assignments'));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'nims' => 'required|array',
            'nims.*' => 'exists:mahasiswas,nim',
            'nidn' => 'required|exists:dosens,nidn',
        ]);

        foreach ($request->nims as $nim) {
            DosenPembimbing::create([
                'nim' => $nim,
                'nidn' => $request->nidn,
            ]);
        }

        return redirect()->back()->with('success', 'Dosen pembimbing berhasil ditetapkan!');
    }

    public function delete($id)
    {
        DosenPembimbing::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Dosen pembimbing berhasil dihapus!');
    }

    public function import(Request $request)
    {
        $request->validate(['file_csv' => 'required|mimes:csv,txt|max:2048']);

        $file = $request->file('file_csv');
        $handle = fopen($file->getPathname(), 'r');
        fgetcsv($handle); // skip header

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

                DosenPembimbing::updateOrCreate(['nim' => $nim], ['nidn' => $nidn]);
                $count++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return redirect()->back()->with('error', 'Gagal mengimpor: ' . $e->getMessage());
        }
        fclose($handle);

        $message = "Berhasil mengimpor $count plotting dosen pembimbing!";
        if (!empty($errors)) {
            $message .= ' (' . count($errors) . ' baris dilewati: ' . implode('; ', array_slice($errors, 0, 5)) . ')';
        }
        return redirect()->back()->with('success', $message);
    }
}
