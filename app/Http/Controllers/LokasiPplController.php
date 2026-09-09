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
            'Sekolah' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:500',
        ]);

        LokasiPpl::create([
            'Sekolah' => $request->Sekolah,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('lokasippl.index')->with('success', 'Mitra Sekolah PPL berhasil ditambahkan!');
    }

    public function editLokasiPpl(LokasiPpl $LokasiPpl)
    {
        return view('admin.LokasiPpl.edit', compact('LokasiPpl'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Sekolah' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:500',
        ]);

        $lokasi = LokasiPpl::findOrFail($id);
        $lokasi->update($request->only(['Sekolah', 'alamat']));

        return redirect()->route('lokasippl.index')->with('success', 'Data Sekolah PPL berhasil diperbarui!');
    }

    public function updateKapasitas(Request $request, $id)
    {
        $request->validate(['maks_peserta' => 'nullable|integer|min:1|max:9999']);
        LokasiPpl::findOrFail($id)->update(['maks_peserta' => $request->maks_peserta ?: null]);
        return redirect()->route('lokasippl.index')->with('success', 'Kapasitas PPL berhasil diperbarui!');
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

    public function setKetua($id)
    {
        $target = PenempatanPpl::findOrFail($id);
        
        // Reset all other students in the same school location to is_ketua = false
        PenempatanPpl::where('sekolah_id', $target->sekolah_id)->update(['is_ketua' => false]);
        
        // Set this student as ketua
        $target->is_ketua = true;
        $target->save();

        $nama = $target->mahasiswa->nama ?? $target->nim;
        return redirect()->back()->with('success', "Mahasiswa {$nama} berhasil ditetapkan sebagai Ketua Kelompok PPL!");
    }

    public function deleteLokasiPpl($id)
    {
        PenempatanPpl::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Penetapan Sekolah berhasil dihapus!');
    }
}
