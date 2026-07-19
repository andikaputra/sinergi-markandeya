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
        Schema::create('bimbingans', function (Blueprint $table) {
            $table->id();
            $table->string('nim');
            $table->unsignedBigInteger('dosen_pembimbing_id');
            $table->text('topik');
            $table->text('deskripsi');
            $table->text('catatan_dosen')->nullable();
            $table->enum('status', ['belum_direview', 'disetujui', 'perlu_revisi'])->default('belum_direview');
            $table->dateTime('tanggal_bimbingan');
            $table->string('materi_terlampir')->nullable();
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswas')->onDelete('cascade');
            $table->foreign('dosen_pembimbing_id')->references('id')->on('dosen_pembimbings')->onDelete('cascade');
            $table->index('nim');
            $table->index('dosen_pembimbing_id');
            $table->index('tanggal_bimbingan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bimbingans');
    }
};
