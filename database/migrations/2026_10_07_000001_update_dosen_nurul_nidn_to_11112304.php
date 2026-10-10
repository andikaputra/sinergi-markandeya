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
        $oldNidn = '0020097807';
        $newNidn = '11112304';
        $namaDosen = 'Nurul Isnaini Fitriyana, S.TP., MP.';

        Schema::disableForeignKeyConstraints();

        // 1. Update di tabel dosens
        $dosen = DB::table('dosens')->where('nidn', $oldNidn)->first();
        if ($dosen) {
            DB::table('dosens')->where('nidn', $oldNidn)->update([
                'nidn' => $newNidn,
            ]);
        } else {
            DB::table('dosens')->updateOrInsert(
                ['nidn' => $newNidn],
                [
                    'nama' => $namaDosen,
                    'password' => bcrypt('password'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 2. Update di tabel dosen_monevs
        DB::table('dosen_monevs')->where('nidn', $oldNidn)->update(['nidn' => $newNidn]);

        // 3. Update di tabel dosen_pembimbings
        DB::table('dosen_pembimbings')->where('nidn', $oldNidn)->update(['nidn' => $newNidn]);

        // 4. Update di tabel dosen_pengujis
        DB::table('dosen_pengujis')->where('nidn', $oldNidn)->update(['nidn' => $newNidn]);

        // 5. Update di tabel dosen_penilai_publikasis
        DB::table('dosen_penilai_publikasis')->where('nidn', $oldNidn)->update(['nidn' => $newNidn]);

        // 6. Update di tabel individu_program_kerjas jika kolom catatan_dosen_nidn ada
        if (Schema::hasTable('individu_program_kerjas') && Schema::hasColumn('individu_program_kerjas', 'catatan_dosen_nidn')) {
            DB::table('individu_program_kerjas')->where('catatan_dosen_nidn', $oldNidn)->update(['catatan_dosen_nidn' => $newNidn]);
        }

        // 7. Update di tabel kelompok_program_kerjas jika kolom catatan_dosen_nidn ada
        if (Schema::hasTable('kelompok_program_kerjas') && Schema::hasColumn('kelompok_program_kerjas', 'catatan_dosen_nidn')) {
            DB::table('kelompok_program_kerjas')->where('catatan_dosen_nidn', $oldNidn)->update(['catatan_dosen_nidn' => $newNidn]);
        }

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $oldNidn = '0020097807';
        $newNidn = '11112304';

        Schema::disableForeignKeyConstraints();

        DB::table('dosens')->where('nidn', $newNidn)->update(['nidn' => $oldNidn]);
        DB::table('dosen_monevs')->where('nidn', $newNidn)->update(['nidn' => $oldNidn]);
        DB::table('dosen_pembimbings')->where('nidn', $newNidn)->update(['nidn' => $oldNidn]);
        DB::table('dosen_pengujis')->where('nidn', $newNidn)->update(['nidn' => $oldNidn]);
        DB::table('dosen_penilai_publikasis')->where('nidn', $newNidn)->update(['nidn' => $oldNidn]);

        if (Schema::hasTable('individu_program_kerjas') && Schema::hasColumn('individu_program_kerjas', 'catatan_dosen_nidn')) {
            DB::table('individu_program_kerjas')->where('catatan_dosen_nidn', $newNidn)->update(['catatan_dosen_nidn' => $oldNidn]);
        }

        if (Schema::hasTable('kelompok_program_kerjas') && Schema::hasColumn('kelompok_program_kerjas', 'catatan_dosen_nidn')) {
            DB::table('kelompok_program_kerjas')->where('catatan_dosen_nidn', $newNidn)->update(['catatan_dosen_nidn' => $oldNidn]);
        }

        Schema::enableForeignKeyConstraints();
    }
};
