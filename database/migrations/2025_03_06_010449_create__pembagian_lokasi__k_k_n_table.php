<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pembagian_lokasi_kkn', function (Blueprint $table) {
            $table->id();
            $table->string('nim'); // Simpan NIM Mahasiswa
            $table->unsignedBigInteger('lokasi_kkn_id'); // Simpan ID Lokasi KKn
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswas')->onDelete('cascade');
            $table->foreign('lokasi_kkn_id')->references('id')->on('lokasi_kkn')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembagian_lokasi_kkn');
    }
};