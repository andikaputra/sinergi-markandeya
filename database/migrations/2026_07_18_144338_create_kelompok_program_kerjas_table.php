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
        Schema::create('kelompok_program_kerjas', function (Blueprint $table) {
            $table->id();
            $table->string('nim_ketua');
            $table->enum('kategori', ['kkn', 'ppl'])->default('kkn');
            $table->string('judul');
            $table->text('deskripsi');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('lokasi');
            $table->enum('status', ['rencana', 'sedang_berjalan', 'selesai', 'tunda'])->default('rencana');
            $table->timestamps();

            $table->foreign('nim_ketua')->references('nim')->on('mahasiswas')->onDelete('cascade');
            $table->index('kategori');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok_program_kerjas');
    }
};
