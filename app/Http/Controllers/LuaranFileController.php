<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\DosenPembimbing;
use App\Models\DosenPenguji;
use App\Models\DosenPenilaiPublikasi;
use App\Models\IndividuLuaran;
use App\Models\KelompokLuaran;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LuaranFileController extends Controller
{
    public function download(Request $request, string $type, int $id)
    {
        $luaran = ($type === 'individu' ? IndividuLuaran::class : KelompokLuaran::class)::findOrFail($id);
        $program = $luaran->programKerja;
        abort_unless($program, 404);
        $nims = $type === 'individu' ? collect([$program->nim]) : $program->anggota()->pluck('nim')->push($program->nim_ketua)->unique();
        $user = $request->user();
        $allowed = false;
        if ($user instanceof Mahasiswa) {
            $allowed = $nims->contains($user->nim);
        } elseif ($user instanceof User) {
            $allowed = $user->isSuperAdmin() || $user->canManage((string) $program->kategori);
        } elseif ($user instanceof Dosen) {
            $nidns = $user->getAllNidns();
            foreach ([DosenPembimbing::class, DosenPenguji::class, DosenPenilaiPublikasi::class] as $assignment) {
                $allowed = $allowed || $assignment::whereIn('nim', $nims)->whereIn('nidn', $nidns)->exists();
            }
            $allowed = $allowed || in_array($program->monev?->nidn, $nidns, true);
        }
        abort_unless($allowed, 403, 'Anda tidak memiliki akses ke berkas luaran ini.');
        $path = $luaran->file_path;
        abort_unless(is_string($path) && $path !== '' && ! str_contains($path, '://') && ! str_contains($path, '\\') && ! str_starts_with($path, '/') && ! in_array('..', explode('/', $path), true), 404, 'Berkas luaran tidak tersedia.');
        $disk = Storage::disk('public');
        abort_unless($disk->exists($path), 404, 'Berkas tidak ditemukan. Hubungi pemilik berkas untuk mengunggah ulang.');
        $request->route()->setParameter('luaran', $luaran);

        return $disk->download($path, basename($path), ['X-Content-Type-Options' => 'nosniff']);
    }
}
