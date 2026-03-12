<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lokasikkn;
use App\Models\Penempatankkn;
use App\Models\Mahasiswa;

class LokasiKKNController extends Controller
{
    public function indexlokasikkn()
    {
        $tempatKKNs = Lokasikkn::all();
        return view('admin.lokasikkn', compact('tempatKKNs'));
    }

    public function createlokasikkn()
    {
        return view('admin.tambahlokasikkn');
    }

    public function storelokasikkn(Request $request)
    {
        $request->validate([
            'desa' => 'required',
            'alamat' => 'required',
            'kecamatan' => 'required',
            'kabupaten' => 'required',
            'provinsi' => 'required',
        ]);

        Lokasikkn::create($request->only(['desa', 'alamat', 'kecamatan', 'kabupaten', 'provinsi']));

        return redirect()->route('lokasikkn.index')->with('success', 'Tempat KKN berhasil ditambahkan!');
    }

    public function editlokasikkn(Lokasikkn $tempatKKN)
    {
        return view('admin.tempat_kkn.edit', compact('tempatKKN'));
    }

    public function update(Request $request, Lokasikkn $tempatKKN)
    {
        $request->validate([
            'desa' => 'required',
            'alamat' => 'required',
            'kecamatan' => 'required',
            'kabupaten' => 'required',
            'provinsi' => 'required',
        ]);

        $tempatKKN->update($request->only(['desa', 'alamat', 'kecamatan', 'kabupaten', 'provinsi']));

        return redirect()->route('lokasikkn.index')->with('success', 'Tempat KKN berhasil diperbarui!');
    }

    public function destroylokasikkn($id)
    {
        Lokasikkn::findOrFail($id)->delete();
        return redirect()->route('lokasikkn.index')->with('success', 'Tempat KKN berhasil dihapus!');
    }

    //asign lokasi kkn
    public function indexasignlokasikkn()
    {
        $mahasiswas = Mahasiswa::where('kegiatan', 'KKN')
        ->whereDoesntHave('penempatankkn')
        ->get();
        $lokasikkns = Lokasikkn::all();
        $assignmentslokasikkn = Penempatankkn::with(['mahasiswa', 'lokasikkn'])->get();
        return view('admin.assignlokasikkn', compact('mahasiswas', 'lokasikkns', 'assignmentslokasikkn'));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'nims' => 'required|array', // Menerima array NIM
            'nims.*' => 'exists:mahasiswas,nim', // Validasi setiap NIM harus ada di tabel mahasiswas
            'lokasikkn' => 'required|exists:lokasi_kkn,id',
        ]);
    
        // Loop setiap NIM untuk disimpan
        foreach ($request->nims as $nim) {
            Penempatankkn::updateOrCreate(
                ['nim' => $nim],
                ['lokasi_kkn_id' => $request->lokasikkn]
            );
        }
    
        return redirect()->back()->with('success', 'Lokasi KKN berhasil ditetapkan!');
    }
    

    public function deletelokasikkn($id)
    {
        Penempatankkn::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Penetapan Lokasi KKN berhasil dihapus!');
    }
}
