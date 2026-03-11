<?php

namespace App\Http\Controllers;

use App\Models\LokasiPkl;
use App\Models\Mahasiswa;
use App\Models\PenempatanPkl;
use Illuminate\Http\Request;

class LokasiPklController extends Controller
{
    public function index()
    {
        $lokasiPkls = LokasiPkl::all();
        return view('admin.lokasipkl', compact('lokasiPkls'));
    }

    public function create()
    {
        return view('admin.tambahlokasipkl');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_instansi' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
        ]);

        LokasiPkl::create($request->all());

        return redirect()->route('lokasipkl.index')->with('success', 'Lokasi PKL berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $lokasi = LokasiPkl::findOrFail($id);
        $lokasi->delete();

        return redirect()->route('lokasipkl.index')->with('success', 'Lokasi PKL berhasil dihapus!');
    }

    // Fitur Assign Penempatan
    public function assignIndex()
    {
        // Ambil mahasiswa yang kegiatannya PKL
        $mahasiswas = Mahasiswa::where('kegiatan', 'PKL')->get();
        $lokasipkls = LokasiPkl::all();
        $assignments = PenempatanPkl::with(['mahasiswa', 'lokasipkl'])->get();

        return view('admin.assignlokasipkl', compact('mahasiswas', 'lokasipkls', 'assignments'));
    }

    public function assignStore(Request $request)
    {
        $request->validate([
            'nims' => 'required|array',
            'lokasipkl' => 'required|exists:lokasi_pkls,id'
        ]);

        foreach ($request->nims as $nim) {
            PenempatanPkl::updateOrCreate(
                ['nim' => $nim],
                ['lokasi_pkl_id' => $request->lokasipkl]
            );
        }

        return redirect()->back()->with('success', 'Penempatan PKL berhasil diperbarui!');
    }

    public function assignDelete($id)
    {
        $assignment = PenempatanPkl::findOrFail($id);
        $assignment->delete();

        return redirect()->back()->with('success', 'Penempatan PKL berhasil dihapus!');
    }
}
