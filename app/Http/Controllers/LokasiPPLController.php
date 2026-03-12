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

        $lokasippl->update($request->only(['Sekolah']));

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
        // Ambil mahasiswa PPL yang belum punya lokasi
        $mahasiswas = Mahasiswa::where('kegiatan', 'PPL')
            ->whereDoesntHave('penempatanppl')
            ->get(); 
    
        $lokasippls = Lokasippl::all();
    
        // Filter penempatan agar hanya muncul data PPL saja
        $assignmentslokasippl = Penempatanppl::whereHas('mahasiswa', function($query) {
            $query->where('kegiatan', 'PPL');
        })->with(['mahasiswa', 'lokasippl'])->get();
    
        return view('admin.assignlokasippl', compact('mahasiswas', 'lokasippls', 'assignmentslokasippl'));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'nims' => 'required|array',
            'nims.*' => 'exists:mahasiswas,nim',
            'sekolah' => 'required|exists:lokasi_ppl,id',
        ]);
    
        foreach ($request->nims as $nim) {
            Penempatanppl::updateOrCreate(
                ['nim' => $nim],
                ['sekolah_id' => $request->sekolah]
            );
        }
    
        return redirect()->back()->with('success', 'Sekolah berhasil ditetapkan!');
    }

    public function deletelokasippl($id)
    {
        Penempatanppl::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Penetapan Sekolah berhasil dihapus!');
    }
}
