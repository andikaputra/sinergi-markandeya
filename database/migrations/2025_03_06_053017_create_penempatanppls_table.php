<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('Penempatan_ppl', function (Blueprint $table) {
            $table->id();
            $table->string('nim'); // Simpan NIM Mahasiswa
            $table->unsignedBigInteger('sekolah_id'); // Simpan ID Lokasi
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswas')->onDelete('cascade');
            $table->foreign('sekolah_id')->references('id')->on('lokasi_ppl')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Penempatan_ppl');
    }
};
