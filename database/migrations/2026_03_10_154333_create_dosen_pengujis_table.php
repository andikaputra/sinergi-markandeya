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
        Schema::create('dosen_pengujis', function (Blueprint $table) {
            $table->id();
            $table->string('nim');
            $table->string('nidn');
            $table->string('nilai')->nullable();
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswas')->onDelete('cascade');
            $table->foreign('nidn')->references('nidn')->on('dosens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_pengujis');
    }
};
