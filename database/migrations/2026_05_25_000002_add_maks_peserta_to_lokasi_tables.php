<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lokasi_kkn', function (Blueprint $table) {
            $table->unsignedInteger('maks_peserta')->nullable()->after('provinsi');
        });
        Schema::table('lokasi_ppl', function (Blueprint $table) {
            $table->unsignedInteger('maks_peserta')->nullable()->after('Sekolah');
        });
        Schema::table('lokasi_pkls', function (Blueprint $table) {
            $table->unsignedInteger('maks_peserta')->nullable()->after('website');
        });
        Schema::table('lokasi_magangs', function (Blueprint $table) {
            $table->unsignedInteger('maks_peserta')->nullable()->after('kontak');
        });
    }

    public function down(): void
    {
        Schema::table('lokasi_kkn', fn($t) => $t->dropColumn('maks_peserta'));
        Schema::table('lokasi_ppl', fn($t) => $t->dropColumn('maks_peserta'));
        Schema::table('lokasi_pkls', fn($t) => $t->dropColumn('maks_peserta'));
        Schema::table('lokasi_magangs', fn($t) => $t->dropColumn('maks_peserta'));
    }
};
