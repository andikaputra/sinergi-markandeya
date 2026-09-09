<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lokasi_ppl', function (Blueprint $table) {
            if (!Schema::hasColumn('lokasi_ppl', 'alamat')) {
                $table->string('alamat')->nullable()->after('Sekolah');
            }
        });
    }

    public function down(): void
    {
        Schema::table('lokasi_ppl', function (Blueprint $table) {
            if (Schema::hasColumn('lokasi_ppl', 'alamat')) {
                $table->dropColumn('alamat');
            }
        });
    }
};
