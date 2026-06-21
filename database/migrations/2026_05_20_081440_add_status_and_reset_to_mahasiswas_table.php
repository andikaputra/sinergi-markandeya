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
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->enum('status', ['pending', 'aktif', 'ditolak'])->default('pending')->after('KRS');
            $table->text('catatan_penolakan')->nullable()->after('status');
            $table->string('reset_token', 100)->nullable()->after('catatan_penolakan');
            $table->timestamp('reset_token_expires_at')->nullable()->after('reset_token');
        });

        // Mahasiswa yang sudah ada dianggap aktif
        \DB::table('mahasiswas')->update(['status' => 'aktif']);
    }

    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn(['status', 'catatan_penolakan', 'reset_token', 'reset_token_expires_at']);
        });
    }
};
