<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('lokasi_kkn', function (Blueprint $table) {
            $table->id();
            $table->string('desa');
            $table->string('alamat')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('provinsi')->nullable();
            $table->timestamps();
        });

    }

    public function down()
    {


        Schema::dropIfExists('lokasi_kkn');
    }
};

