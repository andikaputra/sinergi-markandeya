<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Dosen;
use App\Models\DosenMonev;
use App\Models\LokasiPpl;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $nidnBaru = '0020097807';
        $nidnLama = '11112304';
        $namaDosen = 'Nurul Isnaini Fitriyana, S.TP., MP.';

        // 1. Simpan atau perbarui data Dosen Bu Nurul
        Dosen::updateOrCreate(
            ['nidn' => $nidnBaru],
            [
                'nama' => $namaDosen,
                'password' => Hash::make('password'),
            ]
        );

        // 2. Pastikan lokasi PPL SDN 3 Banjarangkan ada
        $loc1 = LokasiPpl::updateOrCreate(
            ['Sekolah' => 'SDN 3 Banjarangkan'],
            ['alamat' => 'Banjarangkan, Klungkung']
        );

        // 3. Pastikan lokasi PPL SMAN 1 Rendang ada
        $loc2 = LokasiPpl::updateOrCreate(
            ['Sekolah' => 'SMAN 1 Rendang'],
            ['alamat' => 'Rendang, Karangasem']
        );

        // 4. Buat / Hubungkan Dosen Monev Kelompok
        DosenMonev::updateOrCreate(
            [
                'monev_type' => 'kelompok',
                'kegiatan' => 'ppl',
                'lokasi_id' => $loc1->id,
            ],
            [
                'nidn' => $nidnBaru,
            ]
        );

        DosenMonev::updateOrCreate(
            [
                'monev_type' => 'kelompok',
                'kegiatan' => 'ppl',
                'lokasi_id' => $loc2->id,
            ],
            [
                'nidn' => $nidnBaru,
            ]
        );

        // 5. Update data lama jika ada NIDN lama di database
        DB::table('dosen_monevs')->where('nidn', $nidnLama)->update(['nidn' => $nidnBaru]);
        DB::table('dosen_pembimbings')->where('nidn', $nidnLama)->update(['nidn' => $nidnBaru]);
        DB::table('dosen_pengujis')->where('nidn', $nidnLama)->update(['nidn' => $nidnBaru]);
        DB::table('dosen_penilai_publikasis')->where('nidn', $nidnLama)->update(['nidn' => $nidnBaru]);
        DB::table('dosens')->where('nidn', $nidnLama)->update(['nidn' => $nidnBaru]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Opsional: rollback
    }
};
