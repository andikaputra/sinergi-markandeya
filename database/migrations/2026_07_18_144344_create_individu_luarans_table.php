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
        Schema::create('individu_luarans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('individu_program_kerja_id');
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('tipe');
            $table->date('tanggal_selesai');
            $table->string('file_path')->nullable();
            $table->enum('status', ['belum_dikerjakan', 'sedang_dikerjakan', 'selesai'])->default('belum_dikerjakan');
            $table->integer('persentase_selesai')->default(0);
            $table->timestamps();

            $table->foreign('individu_program_kerja_id')->references('id')->on('individu_program_kerjas')->onDelete('cascade');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individu_luarans');
    }
};
