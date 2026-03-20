<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LokasiKkn;
use App\Models\PenempatanKkn;
use App\Models\Mahasiswa;

class LokasiKknController extends Controller
{
    public function indexlokasikkn()
    {
        $tempatKKNs = LokasiKkn::all();
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

        LokasiKkn::create($request->only(['desa', 'alamat', 'kecamatan', 'kabupaten', 'provinsi']));

        return redirect()->route('lokasikkn.index')->with('success', 'Tempat KKN berhasil ditambahkan!');
    }

    public function editlokasikkn(LokasiKkn $tempatKKN)
    {
        return view('admin.tempat_kkn.edit', compact('tempatKKN'));
    }

    public function update(Request $request, LokasiKkn $tempatKKN)
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
        LokasiKkn::findOrFail($id)->delete();
        return redirect()->route('lokasikkn.index')->with('success', 'Tempat KKN berhasil dihapus!');
    }

    //asign lokasi kkn
    public function indexasignlokasikkn()
    {
        $mahasiswas = Mahasiswa::withKegiatan('KKN')
        ->whereDoesntHave('penempatankkn')
        ->get();
        $lokasikkns = LokasiKkn::all();
        $assignmentslokasikkn = PenempatanKkn::with(['mahasiswa', 'lokasikkn'])->get();
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
            PenempatanKkn::updateOrCreate(
                ['nim' => $nim],
                ['lokasi_kkn_id' => $request->lokasikkn]
            );
        }
    
        return redirect()->back()->with('success', 'Lokasi KKN berhasil ditetapkan!');
    }
    

    public function deletelokasikkn($id)
    {
        PenempatanKkn::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Penetapan Lokasi KKN berhasil dihapus!');
    }
}
