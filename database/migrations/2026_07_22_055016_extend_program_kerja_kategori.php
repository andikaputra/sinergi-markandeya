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
        Schema::table('individu_program_kerjas', function (Blueprint $table) {
            $table->enum('kategori', ['kkn', 'ppl', 'pkl', 'magang'])->change();
        });

        Schema::table('kelompok_program_kerjas', function (Blueprint $table) {
            $table->enum('kategori', ['kkn', 'ppl', 'pkl', 'magang'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('individu_program_kerjas', function (Blueprint $table) {
            $table->enum('kategori', ['kkn', 'ppl'])->change();
        });

        Schema::table('kelompok_program_kerjas', function (Blueprint $table) {
            $table->enum('kategori', ['kkn', 'ppl'])->change();
        });
    }
};
