<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->string('kampus')->nullable()->change();
            $table->string('kecamatan')->nullable()->change();
            $table->string('pembayaranKRS')->nullable()->change();
            $table->string('KRS')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->string('kampus')->nullable(false)->change();
            $table->string('kecamatan')->nullable(false)->change();
            $table->string('pembayaranKRS')->nullable(false)->change();
            $table->string('KRS')->nullable(false)->change();
        });
    }
};
