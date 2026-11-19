<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Task 4: destination pages (/ru/hotels/{slug}) get their own H1, intro text (above the list),
 * SEO text (below the list), FAQ (question + answer pairs, JSON) and a hero image.
 * SEO title / description already exist (meta_title, meta_description).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('destinations')) {
            return;
        }
        Schema::table('destinations', function (Blueprint $table) {
            if (!Schema::hasColumn('destinations', 'h1')) {
                $table->string('h1')->nullable();
            }
            if (!Schema::hasColumn('destinations', 'intro_text')) {
                $table->longText('intro_text')->nullable();
            }
            if (!Schema::hasColumn('destinations', 'seo_text')) {
                $table->longText('seo_text')->nullable();
            }
            if (!Schema::hasColumn('destinations', 'faq')) {
                $table->json('faq')->nullable();
            }
            if (!Schema::hasColumn('destinations', 'hero_image')) {
                $table->string('hero_image')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            foreach (['h1', 'intro_text', 'seo_text', 'faq', 'hero_image'] as $c) {
                if (Schema::hasColumn('destinations', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
    }
};
