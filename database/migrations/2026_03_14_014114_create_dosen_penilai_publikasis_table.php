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
        Schema::create('dosen_penilai_publikasis', function (Blueprint $table) {
            $table->id();
            $table->string('nim');
            $table->string('nidn');
            $table->decimal('nilai_ketercapaian', 5, 1)->nullable();
            $table->decimal('nilai_sistematika', 5, 1)->nullable();
            $table->decimal('nilai_kelayakan', 5, 1)->nullable();
            $table->decimal('nilai_presentasi', 5, 1)->nullable();
            $table->decimal('nilai_mempertahankan', 5, 1)->nullable();
            $table->decimal('nilai', 5, 1)->nullable();
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswas')->onDelete('cascade');
            $table->foreign('nidn')->references('nidn')->on('dosens')->onDelete('cascade');
            $table->unique('nim', 'dosen_penilai_publikasis_nim_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_penilai_publikasis');
    }
};
