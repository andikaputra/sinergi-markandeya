<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pengajuan_lokasi_magang', function (Blueprint $table) {
            $table->id();
            $table->string('nim');
            $table->string('nama_instansi');
            $table->string('alamat');
            $table->string('kontak')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengajuan_lokasi_magang');
    }
};
