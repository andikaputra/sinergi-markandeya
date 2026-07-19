<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penempatan_magangs', function (Blueprint $table) {
            $table->id();
            $table->string('nim');
            $table->unsignedBigInteger('lokasi_magang_id');
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswas')->onDelete('cascade');
            $table->foreign('lokasi_magang_id')->references('id')->on('lokasi_magangs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penempatan_magangs');
    }
};
