<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PembimbingLuar;
use App\Models\PembimbingLuarMahasiswa;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PembimbingLuarController extends Controller
{
    public function index()
    {
        $pembimbingLuars = PembimbingLuar::all();
        return view('admin.pembimbing_luar', compact('pembimbingLuars'));
    }

    public function create()
    {
        return view('admin.tambahpembimbingluar');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pembimbing_luars,email',
            'instansi' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
        ]);

        PembimbingLuar::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => $request->email,
            'instansi' => $request->instansi,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('pembimbing_luar.index')->with('success', 'Pembimbing luar berhasil ditambahkan! Password default = email.');
    }

    public function destroy($id)
    {
        PembimbingLuar::findOrFail($id)->delete();
        return redirect()->route('pembimbing_luar.index')->with('success', 'Pembimbing luar berhasil dihapus!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|mimes:csv,txt|max:2048'
        ]);

        $file = $request->file('file_csv');
        $handle = fopen($file->getPathname(), 'r');

        // Skip header
        fgetcsv($handle);

        $count = 0;
        $errors = [];
        $row = 1;

        DB::beginTransaction();
        try {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row++;
                // Format CSV: nama, email, instansi, no_hp (opsional)
                if (count($data) < 3) {
                    $errors[] = "Baris $row: kolom kurang dari 3";
                    continue;
                }

                $nama = trim($data[0]);
                $email = trim($data[1]);
                $instansi = trim($data[2]);
                $no_hp = isset($data[3]) ? trim($data[3]) : null;

                if (empty($nama) || empty($email) || empty($instansi)) {
                    $errors[] = "Baris $row: Nama, Email, atau Instansi kosong";
                    continue;
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Baris $row: Format email tidak valid ($email)";
                    continue;
                }

                PembimbingLuar::updateOrCreate(
                    ['email' => $email],
                    [
                        'nama' => $nama,
                        'password' => $email,
                        'instansi' => $instansi,
                        'no_hp' => $no_hp ?: null,
                    ]
                );
                $count++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return redirect()->back()->with('error', 'Gagal mengimpor: ' . $e->getMessage());
        }
        fclose($handle);

        $message = "Berhasil mengimpor $count pembimbing luar!";
        if (!empty($errors)) {
            $message .= ' (' . count($errors) . ' baris dilewati: ' . implode('; ', array_slice($errors, 0, 5)) . ')';
        }
        return redirect()->back()->with('success', $message);
    }

    private function assignByKegiatan($kegiatan, $viewName)
    {
        $mahasiswas = Mahasiswa::where('kegiatan', $kegiatan)
            ->whereDoesntHave('pembimbingLuarMahasiswa')
            ->orderBy('nama')
            ->get();

        $pembimbingLuars = PembimbingLuar::all();

        $assignments = PembimbingLuarMahasiswa::whereHas('mahasiswa', function($query) use ($kegiatan) {
            $query->where('kegiatan', $kegiatan);
        })->with(['mahasiswa', 'pembimbingLuar'])->get();

        return view($viewName, compact('mahasiswas', 'pembimbingLuars', 'assignments', 'kegiatan'));
    }

    public function assignKKN()
    {
        return $this->assignByKegiatan('KKN', 'admin.assignpembimbingluarkkn');
    }

    public function assignPPL()
    {
        return $this->assignByKegiatan('PPL', 'admin.assignpembimbingluarppl');
    }

    public function assignPKL()
    {
        return $this->assignByKegiatan('PKL', 'admin.assignpembimbingluarpkl');
    }

    public function assignMagang()
    {
        return $this->assignByKegiatan('Magang', 'admin.assignpembimbingluarmagang');
    }

    public function assignStore(Request $request)
    {
        $request->validate([
            'nims' => 'required|array',
            'nims.*' => 'exists:mahasiswas,nim',
            'pembimbing_luar_id' => 'required|exists:pembimbing_luars,id',
        ]);

        foreach ($request->nims as $nim) {
            PembimbingLuarMahasiswa::create([
                'nim' => $nim,
                'pembimbing_luar_id' => $request->pembimbing_luar_id,
            ]);
        }

        return redirect()->back()->with('success', 'Pembimbing luar berhasil ditetapkan!');
    }

    public function assignDelete($id)
    {
        PembimbingLuarMahasiswa::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Plotting pembimbing luar berhasil dihapus!');
    }
}
