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
        Schema::table('dosen_monevs', function (Blueprint $table) {
            if (!Schema::hasColumn('dosen_monevs', 'link_monev')) {
                $table->text('link_monev')->nullable()->after('foto_monev');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosen_monevs', function (Blueprint $table) {
            if (Schema::hasColumn('dosen_monevs', 'link_monev')) {
                $table->dropColumn('link_monev');
            }
        });
    }
};
