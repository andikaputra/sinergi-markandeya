<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Short-lived one-time codes (60 second TTL)
        Schema::create('sso_auth_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 64)->unique();
            $table->string('client_id');
            $table->string('user_type');   // dosen | mahasiswa | admin
            $table->unsignedBigInteger('user_id');
            $table->string('redirect_uri');
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            $table->index(['code', 'client_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sso_auth_codes');
    }
};
