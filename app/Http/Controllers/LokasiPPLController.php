<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lokasippl;
use App\Models\Penempatanppl;
use App\Models\Mahasiswa;

class LokasiPPLController extends Controller
{
    public function indexlokasippl()
    {
        $Lokasippl = Lokasippl::all();
        return view('admin.lokasippl', compact('Lokasippl'));
    }

    public function createlokasippl()
    {
        return view('admin.tambahlokasippl');
    }

    public function storelokasippl(Request $request)
    {
        $request->validate([
            'Sekolah' => 'required',
        ]);


        Lokasippl::create([
            'Sekolah' => $request->Sekolah,
        ]);

        return redirect()->route('lokasippl.index')->with('success', 'Tempat PPL berhasil ditambahkan!');
    }

    public function editlokasippl(Lokasippl $lokasippl)
    {
        return view('admin.lokasippl.edit', compact('lokasippl'));
    }

    public function update(Request $request, Lokasippl $lokasippl)
    {
        $request->validate([
            'Sekolah' => 'required',
        ]);

        $lokasippl->update($request->all());

        return redirect()->route('lokasippl.index')->with('success', 'Tempat PPL berhasil diperbarui!');
    }

    public function destroylokasippl($id)
    {
        Lokasippl::findOrFail($id)->delete();
        return redirect()->route('lokasippl.index')->with('success', 'Tempat PPL berhasil dihapus!');
    }

    //asign lokasi ppl
    public function indexasignlokasippl()
    {
        // Pastikan mahasiswa yang belum memiliki lokasi PPL diambil
        $mahasiswas = Mahasiswa::where('kegiatan', 'PPL')
            ->whereDoesntHave('lokasippl') 
            ->get(); 
    
        // Ambil semua data lokasi PPL
        $lokasippls = Lokasippl::all();
    
        // Ambil semua penempatan mahasiswa PPL beserta relasi mereka
        $assignmentslokasippl = Penempatanppl::with(['mahasiswa', 'lokasippl'])->get();
    
        return view('admin.assignlokasippl', compact('mahasiswas', 'lokasippls', 'assignmentslokasippl'));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'nims' => 'required|array', // Menerima array NIM
            'nims.*' => 'exists:mahasiswas,nim', // Validasi setiap NIM harus ada di tabel mahasiswas
            'sekolah' => 'required|exists:lokasi_ppl,id',
        ]);
    
        // Loop setiap NIM untuk disimpan
        foreach ($request->nims as $nim) {
            Penempatanppl::create([
                'nim' => $nim,
                'sekolah_id' => $request->sekolah,
            ]);
        }
    
        return redirect()->back()->with('success', 'Sekolah berhasil ditetapkan untuk beberapa mahasiswa!');
    }
    

    public function deletelokasippl($id)
    {
        Penempatanppl::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Penetapan Sekolah berhasil dihapus!');
    }
}
