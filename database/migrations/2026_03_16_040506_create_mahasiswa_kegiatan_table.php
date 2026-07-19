<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('nim');
            $table->string('kegiatan'); // KKN, PPL, PKL, Magang
            $table->string('tahun_akademik')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswas')->onDelete('cascade');
            $table->unique(['nim', 'kegiatan', 'tahun_akademik']);
            $table->index(['nim', 'is_active']);
        });

        // Migrasi data dari kolom lama ke tabel baru
        $mahasiswas = DB::table('mahasiswas')->whereNotNull('kegiatan')->get();
        foreach ($mahasiswas as $mhs) {
            DB::table('mahasiswa_kegiatan')->insert([
                'nim' => $mhs->nim,
                'kegiatan' => $mhs->kegiatan,
                'tahun_akademik' => $mhs->tahun_akademik,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_kegiatan');
    }
};
