<?php

namespace App\Http\Controllers;

use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class TahunAkademikController extends Controller
{
    public function index()
    {
        $tahunAkademiks = TahunAkademik::orderBy('tahun', 'desc')->get();
        return view('admin.tahun_akademik.index', compact('tahunAkademiks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|string',
            'semester' => 'required|in:Ganjil,Genap',
        ]);

        TahunAkademik::create([
            'tahun' => $request->tahun,
            'semester' => $request->semester,
            'is_active' => false
        ]);

        return redirect()->back()->with('success', 'Tahun Akademik berhasil ditambahkan!');
    }

    public function setActive($id)
    {
        // Nonaktifkan semua tahun akademik
        TahunAkademik::query()->update(['is_active' => false]);

        // Aktifkan yang dipilih
        $tahun = TahunAkademik::findOrFail($id);
        $tahun->update(['is_active' => true]);

        return redirect()->back()->with('success', 'Tahun Akademik aktif berhasil diubah!');
    }

    public function destroy($id)
    {
        $tahun = TahunAkademik::findOrFail($id);
        if ($tahun->is_active) {
            return redirect()->back()->with('error', 'Tahun akademik aktif tidak bisa dihapus!');
        }
        $tahun->delete();
        return redirect()->back()->with('success', 'Tahun Akademik berhasil dihapus!');
    }
}
