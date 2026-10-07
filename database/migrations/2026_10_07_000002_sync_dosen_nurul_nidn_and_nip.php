<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $nidnDikti = '0020097807';
        $nipKampus = '11112304';
        $namaDosen = 'Nurul Isnaini Fitriyana, S.TP., MP.';

        Schema::disableForeignKeyConstraints();

        // 1. Simpan/Update Dosen di tabel dosens dengan NIDN Dikti dan NIP Kampus
        $dosen = DB::table('dosens')->where('nidn', $nipKampus)->orWhere('nidn', $nidnDikti)->first();
        if ($dosen) {
            DB::table('dosens')->where('id', $dosen->id)->update([
                'nidn' => $nidnDikti,
                'nip'  => $nipKampus,
                'nama' => $namaDosen,
            ]);
        } else {
            DB::table('dosens')->updateOrInsert(
                ['nidn' => $nidnDikti],
                [
                    'nip' => $nipKampus,
                    'nama' => $namaDosen,
                    'password' => bcrypt('password'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 2. Hubungkan data relasi ke NIDN resmi (0020097807)
        DB::table('dosen_monevs')->where('nidn', $nipKampus)->update(['nidn' => $nidnDikti]);
        DB::table('dosen_pembimbings')->where('nidn', $nipKampus)->update(['nidn' => $nidnDikti]);
        DB::table('dosen_pengujis')->where('nidn', $nipKampus)->update(['nidn' => $nidnDikti]);
        DB::table('dosen_penilai_publikasis')->where('nidn', $nipKampus)->update(['nidn' => $nidnDikti]);

        if (Schema::hasTable('individu_program_kerjas') && Schema::hasColumn('individu_program_kerjas', 'catatan_dosen_nidn')) {
            DB::table('individu_program_kerjas')->where('catatan_dosen_nidn', $nipKampus)->update(['catatan_dosen_nidn' => $nidnDikti]);
        }

        if (Schema::hasTable('kelompok_program_kerjas') && Schema::hasColumn('kelompok_program_kerjas', 'catatan_dosen_nidn')) {
            DB::table('kelompok_program_kerjas')->where('catatan_dosen_nidn', $nipKampus)->update(['catatan_dosen_nidn' => $nidnDikti]);
        }

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $nidnDikti = '0020097807';
        $nipKampus = '11112304';

        Schema::disableForeignKeyConstraints();

        DB::table('dosens')->where('nidn', $nidnDikti)->update(['nidn' => $nipKampus]);
        DB::table('dosen_monevs')->where('nidn', $nidnDikti)->update(['nidn' => $nipKampus]);

        Schema::enableForeignKeyConstraints();
    }
};
