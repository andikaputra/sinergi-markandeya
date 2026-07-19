<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('email');
            $table->string('ais_token')->nullable()->after('foto');
        });
    }

    public function down()
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn(['foto', 'ais_token']);
        });
    }
};
