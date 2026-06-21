<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sso_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');                     // "LPPM Markandeya"
            $table->string('client_id')->unique();      // "lppm-markandeya"
            $table->string('client_secret');            // hashed secret
            $table->json('redirect_uris');              // ["https://lppm.markandeyabali.ac.id/sso/callback"]
            $table->json('allowed_roles');              // ["dosen"] or ["mahasiswa"] or ["dosen","mahasiswa"]
            $table->string('logo')->nullable();         // optional logo url
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sso_clients');
    }
};
