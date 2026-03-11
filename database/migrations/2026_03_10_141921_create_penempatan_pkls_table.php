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
        Schema::create('penempatan_pkls', function (Blueprint $table) {
            $table->id();
            $table->string('nim');
            $table->unsignedBigInteger('lokasi_pkl_id');
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswas')->onDelete('cascade');
            $table->foreign('lokasi_pkl_id')->references('id')->on('lokasi_pkls')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penempatan_pkls');
    }
};
