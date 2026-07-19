<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('dosens', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('nama');
            $table->string('nip')->nullable()->after('nidn');
            $table->string('ais_token')->nullable()->after('foto');
        });
    }

    public function down()
    {
        Schema::table('dosens', function (Blueprint $table) {
            $table->dropColumn(['foto', 'nip', 'ais_token']);
        });
    }
};
