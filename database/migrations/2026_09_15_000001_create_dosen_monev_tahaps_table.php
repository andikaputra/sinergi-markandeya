<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('dosen_monev_tahaps')) {
            Schema::create('dosen_monev_tahaps', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('dosen_monev_id');
                $table->unsignedTinyInteger('tahap_ke')->default(1); // 1 = Monev 1, 2 = Monev 2, 3 = Monev 3
                $table->date('tanggal_monev')->nullable();
                $table->text('catatan')->nullable();
                $table->float('nilai')->nullable();
                $table->string('link_monev', 1000)->nullable();
                $table->json('foto_monev')->nullable();
                $table->timestamps();

                $table->foreign('dosen_monev_id')->references('id')->on('dosen_monevs')->onDelete('cascade');
                $table->unique(['dosen_monev_id', 'tahap_ke']);
                $table->index('tahap_ke');
            });
        }

        // Migrate existing monev records from dosen_monevs to dosen_monev_tahaps as tahap_ke = 1
        try {
            if (Schema::hasTable('dosen_monevs')) {
                $existingMonevs = DB::table('dosen_monevs')->get();
                foreach ($existingMonevs as $dm) {
                    $hasContent = !empty($dm->catatan) || !is_null($dm->nilai) || !empty($dm->foto_monev) || !empty($dm->link_monev) || !empty($dm->tanggal_monev);
                    
                    // Always make sure Tahap 1, 2, 3 can be tracked, but only insert existing content for Tahap 1 if present
                    $alreadyExists = DB::table('dosen_monev_tahaps')
                        ->where('dosen_monev_id', $dm->id)
                        ->where('tahap_ke', 1)
                        ->exists();

                    if (!$alreadyExists && $hasContent) {
                        DB::table('dosen_monev_tahaps')->insert([
                            'dosen_monev_id' => $dm->id,
                            'tahap_ke' => 1,
                            'tanggal_monev' => $dm->tanggal_monev ?? null,
                            'catatan' => $dm->catatan ?? null,
                            'nilai' => $dm->nilai ?? null,
                            'link_monev' => $dm->link_monev ?? null,
                            'foto_monev' => $dm->foto_monev ?? null,
                            'created_at' => $dm->created_at ?? now(),
                            'updated_at' => $dm->updated_at ?? now(),
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {
            // Log or ignore if migration data transfer encounters any edge cases
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_monev_tahaps');
    }
};
