<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('request_id')->index();
            $table->string('actor_type', 30)->index();
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('actor_name');
            $table->string('actor_identifier')->nullable();
            $table->string('module', 50)->index();
            $table->string('action', 50)->index();
            $table->string('subject_type')->nullable();
            $table->string('subject_id', 100)->nullable();
            $table->string('subject_label')->nullable();
            $table->string('activity', 20)->nullable()->index();
            $table->string('route_name')->nullable();
            $table->json('changes')->nullable();
            $table->timestamp('occurred_at')->index();
            $table->timestamps();
            $table->index(['actor_type', 'actor_id', 'occurred_at']);
            // No foreign keys to user/business records: history survives deletion.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
