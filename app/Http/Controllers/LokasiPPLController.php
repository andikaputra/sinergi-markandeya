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
        $LokasiPpl = LokasiPpl::all();
        return view('admin.LokasiPpl', compact('LokasiPpl'));
    }

    public function createLokasiPpl()
    {
        return view('admin.tambahLokasiPpl');
    }

    public function storeLokasiPpl(Request $request)
    {
        $request->validate([
            'Sekolah' => 'required',
        ]);

        LokasiPpl::create([
            'Sekolah' => $request->Sekolah,
        ]);

        return redirect()->route('LokasiPpl.index')->with('success', 'Tempat PPL berhasil ditambahkan!');
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

        return redirect()->route('LokasiPpl.index')->with('success', 'Tempat PPL berhasil diperbarui!');
    }

    public function destroyLokasiPpl($id)
    {
        LokasiPpl::findOrFail($id)->delete();
        return redirect()->route('LokasiPpl.index')->with('success', 'Tempat PPL berhasil dihapus!');
    }

    //asign lokasi ppl
    public function indexasignLokasiPpl()
    {
        // Ambil mahasiswa PPL yang belum punya lokasi
        $mahasiswas = Mahasiswa::withKegiatan('PPL')
            ->whereDoesntHave('PenempatanPpl')
            ->get(); 
    
        $LokasiPpls = LokasiPpl::all();
    
        // Filter penempatan agar hanya muncul data PPL saja
        $assignmentsLokasiPpl = PenempatanPpl::whereHas('mahasiswa', function($query) {
            $query->withKegiatan('PPL');
        })->with(['mahasiswa', 'LokasiPpl'])->get();
    
        return view('admin.assignLokasiPpl', compact('mahasiswas', 'LokasiPpls', 'assignmentsLokasiPpl'));
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
