<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LokasiPpl;
use App\Models\PenempatanPpl;
use App\Models\Mahasiswa;

class LokasiPplController extends Controller
{
    public function indexLokasiPpl()
    {
        $lokasippl = LokasiPpl::all();
        return view('admin.lokasippl', compact('lokasippl'));
    }

    public function createLokasiPpl()
    {
        return view('admin.tambahlokasippl');
    }

    public function storeLokasiPpl(Request $request)
    {
        $request->validate([
            'Sekolah' => 'required',
        ]);

        LokasiPpl::create([
            'Sekolah' => $request->Sekolah,
        ]);

        return redirect()->route('lokasippl.index')->with('success', 'Tempat PPL berhasil ditambahkan!');
    }

    public function editLokasiPpl(LokasiPpl $LokasiPpl)
    {
        return view('admin.LokasiPpl.edit', compact('LokasiPpl'));
    }

    public function update(Request $request, LokasiPpl $LokasiPpl)
    {
        $request->validate([
            'Sekolah' => 'required',
        ]);

        $LokasiPpl->update($request->only(['Sekolah']));

        return redirect()->route('lokasippl.index')->with('success', 'Tempat PPL berhasil diperbarui!');
    }

    public function destroyLokasiPpl($id)
    {
        LokasiPpl::findOrFail($id)->delete();
        return redirect()->route('lokasippl.index')->with('success', 'Tempat PPL berhasil dihapus!');
    }

    //asign lokasi ppl
    public function indexasignLokasiPpl()
    {
        $mahasiswas = Mahasiswa::withKegiatan('PPL')
            ->whereDoesntHave('penempatanppl')
            ->get();

        $lokasippls = LokasiPpl::all();

        $assignmentslokasippl = PenempatanPpl::whereHas('mahasiswa', function($query) {
            $query->withKegiatan('PPL');
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
            PenempatanPpl::updateOrCreate(
                ['nim' => $nim],
                ['sekolah_id' => $request->sekolah]
            );
        }
    
        return redirect()->back()->with('success', 'Sekolah berhasil ditetapkan!');
    }

    public function deleteLokasiPpl($id)
    {
        PenempatanPpl::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Penetapan Sekolah berhasil dihapus!');
    }
}
