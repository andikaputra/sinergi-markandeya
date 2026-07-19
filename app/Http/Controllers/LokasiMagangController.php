<?php

namespace App\Http\Controllers;

use App\Models\LokasiMagang;
use Illuminate\Http\Request;

class LokasiMagangController extends Controller
{
    public function index()
    {
        $lokasiMagangs = LokasiMagang::all();
        return view('admin.lokasimagang', compact('lokasiMagangs'));
    }

    public function create()
    {
        return view('admin.tambahlokasimagang');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_instansi' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string',
        ]);

        LokasiMagang::create($request->only(['nama_instansi', 'alamat', 'kontak']));

        return redirect()->route('lokasimagang.index')->with('success', 'Lokasi Magang berhasil ditambahkan!');
    }

    public function updateKapasitas(Request $request, $id)
    {
        $request->validate(['maks_peserta' => 'nullable|integer|min:1|max:9999']);
        LokasiMagang::findOrFail($id)->update(['maks_peserta' => $request->maks_peserta ?: null]);
        return redirect()->route('lokasimagang.index')->with('success', 'Kapasitas Magang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        LokasiMagang::findOrFail($id)->delete();
        return redirect()->route('lokasimagang.index')->with('success', 'Lokasi Magang berhasil dihapus!');
    }
}
