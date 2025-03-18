<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('dosen_pembimbings', function (Blueprint $table) {
            $table->id();
            $table->string('nim'); // Simpan NIM Mahasiswa
            $table->string('nidn'); // Simpan NIDN Dosen
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswas')->onDelete('cascade');
            $table->foreign('nidn')->references('nidn')->on('dosens')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('dosen_pembimbings');
    }
};


