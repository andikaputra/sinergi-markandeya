<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('jurnals', function (Blueprint $table) {
            $table->id();
            $table->string('nim'); // Pastikan ini sesuai dengan kolom di `mahasiswas`
            $table->date('tanggal');
            $table->text('kegiatan');
            $table->timestamps();

            // Foreign key yang benar
            $table->foreign('nim')->references('nim')->on('mahasiswas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('jurnals');
    }
};






