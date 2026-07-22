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
        Schema::create('dosen_monevs', function (Blueprint $table) {
            $table->id();
            $table->string('nidn');
            $table->enum('monev_type', ['individu', 'kelompok']);
            $table->bigInteger('program_id');
            $table->float('nilai')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('nidn')->references('nidn')->on('dosens')->onDelete('cascade');
            $table->index(['monev_type', 'program_id']);
            $table->unique(['monev_type', 'program_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_monevs');
    }
};
