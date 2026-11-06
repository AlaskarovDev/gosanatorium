<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Task #313 — services.youtube_url: YouTube video of the sanatorium (filled in admin panel). */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('services') && !Schema::hasColumn('services', 'youtube_url')) {
            Schema::table('services', function (Blueprint $table) {
                $table->string('youtube_url', 255)->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('services', 'youtube_url')) {
            Schema::table('services', function (Blueprint $table) {
                $table->dropColumn('youtube_url');
            });
        }
    }
};
