<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\DosenPembimbing;

class DosenPembimbingController extends Controller
{
    public function index()
    {
        $mahasiswas = Mahasiswa::whereDoesntHave('dosenPembimbing')->get();
        $dosens = Dosen::all();
        $assignments = DosenPembimbing::with(['mahasiswa', 'dosen'])->get();
        return view('admin.assigndosen', compact('mahasiswas', 'dosens', 'assignments'));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'nims' => 'required|array', // Menerima array NIM
            'nims.*' => 'exists:mahasiswas,nim', // Validasi setiap NIM harus ada di tabel mahasiswas
            'nidn' => 'required|exists:dosens,nidn',
        ]);
    
        // Loop setiap NIM untuk disimpan
        foreach ($request->nims as $nim) {
            DosenPembimbing::create([
                'nim' => $nim,
                'nidn' => $request->nidn,
            ]);
        }
    
        return redirect()->back()->with('success', 'Dosen pembimbing berhasil ditetapkan untuk beberapa mahasiswa!');
    }
    

    public function delete($id)
    {
        DosenPembimbing::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Dosen pembimbing berhasil dihapus!');
    }
}
