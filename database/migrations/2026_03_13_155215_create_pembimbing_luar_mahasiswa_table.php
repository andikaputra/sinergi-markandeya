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
        Schema::create('pembimbing_luar_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->string('nim');
            $table->unsignedBigInteger('pembimbing_luar_id');
            $table->decimal('nilai', 5, 1)->nullable();
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswas')->onDelete('cascade');
            $table->foreign('pembimbing_luar_id')->references('id')->on('pembimbing_luars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembimbing_luar_mahasiswa');
    }
};
