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
        Schema::table('mahasiswa_kegiatan', function (Blueprint $table) {
            $table->string('link_dokumen_1')->nullable()->after('motivasi'); // Transkip nilai (semua)
            $table->string('link_dokumen_2')->nullable()->after('link_dokumen_1'); // Surat pernyataan / permohonan
            $table->string('link_dokumen_3')->nullable()->after('link_dokumen_2'); // Surat sehat / CV / portfolio
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa_kegiatan', function (Blueprint $table) {
            $table->dropColumn(['link_dokumen_1', 'link_dokumen_2', 'link_dokumen_3']);
        });
    }
};
