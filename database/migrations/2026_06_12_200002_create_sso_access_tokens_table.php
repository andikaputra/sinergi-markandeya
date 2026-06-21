<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sso_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token', 80)->unique();
            $table->string('client_id');
            $table->string('user_type');               // dosen | mahasiswa | admin
            $table->unsignedBigInteger('user_id');
            $table->json('user_data');                 // cached user info
            $table->json('abilities')->nullable();     // ["dosen","lppm"]
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at');
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();

            $table->index(['token']);
            $table->index(['user_type', 'user_id']);
            $table->index('client_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sso_access_tokens');
    }
};
