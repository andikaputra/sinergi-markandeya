<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('dosens', function (Blueprint $table) {
            $table->id();
            $table->string('nidn')->unique();
            $table->string('password');
            $table->string('nama');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('dosens');
    }
};

