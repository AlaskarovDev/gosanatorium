<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('custom_pages', function (Blueprint $table) {
            // Banner/Video fields
            $table->string('banner_image')->nullable();
            $table->string('video_url')->nullable();
            $table->string('doctor_name')->nullable();
            $table->string('doctor_image')->nullable();
            
            // Form settings
            $table->boolean('show_consultation_form')->default(0);
            $table->boolean('show_video_banner')->default(0);
        });

        Schema::table('custom_page_translations', function (Blueprint $table) {
            // Translatable fields
            $table->string('banner_title')->nullable();
            $table->text('banner_description')->nullable();
            $table->string('form_title')->nullable();
            $table->text('form_description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('custom_pages', function (Blueprint $table) {
            $table->dropColumn([
                'banner_image', 'video_url', 'doctor_name', 
                'doctor_image', 'show_consultation_form', 'show_video_banner'
            ]);
        });

        Schema::table('custom_page_translations', function (Blueprint $table) {
            $table->dropColumn([
                'banner_title', 'banner_description', 
                'form_title', 'form_description'
            ]);
        });
    }
};