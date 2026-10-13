<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use App\Models\Dosen;
use App\Models\DosenPenguji;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BimbinganFileController extends Controller
{
    public function download(Request $request, Bimbingan $bimbingan)
    {
        $user = $request->user();
        $allowed = false;
        if ($user instanceof Mahasiswa) {
            $allowed = $user->nim === $bimbingan->nim;
        } elseif ($user instanceof Dosen) {
            $nidns = $user->getAllNidns();
            $allowed = in_array($bimbingan->dosenPembimbing?->nidn, $nidns, true)
                || DosenPenguji::where('nim', $bimbingan->nim)->whereIn('nidn', $nidns)->exists();
        } elseif ($user instanceof User) {
            $activity = $bimbingan->mahasiswa?->kegiatan;
            $allowed = $user->isSuperAdmin() || (is_string($activity) && $user->canManage($activity));
        }
        abort_unless($allowed, 403, 'Anda tidak memiliki akses ke lampiran ini.');

        $filename = $bimbingan->materi_terlampir;
        abort_unless($filename && basename($filename) === $filename && !str_contains($filename, '\\'), 404, 'Lampiran tidak tersedia.');
        $disk = Storage::disk('public');
        $path = 'bimbingan/'.$filename;
        abort_unless($disk->exists($path), 404, 'Berkas tidak ditemukan. Hubungi pemilik berkas untuk mengunggah ulang.');

        return $disk->download($path, $filename, ['X-Content-Type-Options' => 'nosniff']);
    }
}
