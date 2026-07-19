<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pengajuan_lokasi_pkl', function (Blueprint $table) {
            $table->id();
            $table->string('nim'); // Mahasiswa yang mengajukan
            $table->string('nama_instansi'); // Nama Instansi
            $table->string('alamat'); // Alamat Instansi
            $table->string('kontak')->nullable(); // Kontak Instansi
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status Pengajuan
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengajuan_lokasi_pkl');
    }
};

