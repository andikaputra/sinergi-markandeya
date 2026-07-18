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
        Schema::create('luarans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('program_kerja_id');
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('tipe'); // dokumen, laporan, produk, dll
            $table->date('tanggal_selesai');
            $table->string('file_path')->nullable();
            $table->enum('status', ['belum_dikerjakan', 'sedang_dikerjakan', 'selesai'])->default('belum_dikerjakan');
            $table->integer('persentase_selesai')->default(0);
            $table->timestamps();

            $table->foreign('program_kerja_id')->references('id')->on('program_kerjas')->onDelete('cascade');
            $table->index('program_kerja_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('luarans');
    }
};
