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
        // custom_pages cədvəlinə yeni sütunlar əlavə et
        Schema::table('custom_pages', function (Blueprint $table) {
            if (!Schema::hasColumn('custom_pages', 'banner_image')) {
                $table->string('banner_image')->nullable()->after('status');
            }
            if (!Schema::hasColumn('custom_pages', 'video_url')) {
                $table->string('video_url')->nullable()->after('banner_image');
            }
            if (!Schema::hasColumn('custom_pages', 'video_thumbnail')) {
                $table->string('video_thumbnail')->nullable()->after('video_url');
            }
            if (!Schema::hasColumn('custom_pages', 'doctor_name')) {
                $table->string('doctor_name')->nullable()->after('video_thumbnail');
            }
            if (!Schema::hasColumn('custom_pages', 'doctor_image')) {
                $table->string('doctor_image')->nullable()->after('doctor_name');
            }
            if (!Schema::hasColumn('custom_pages', 'show_consultation_form')) {
                $table->boolean('show_consultation_form')->default(0)->after('doctor_image');
            }
            if (!Schema::hasColumn('custom_pages', 'show_video_banner')) {
                $table->boolean('show_video_banner')->default(0)->after('show_consultation_form');
            }
            if (!Schema::hasColumn('custom_pages', 'feature1_icon')) {
                $table->string('feature1_icon')->nullable()->default('fas fa-clock')->after('show_video_banner');
            }
            if (!Schema::hasColumn('custom_pages', 'feature2_icon')) {
                $table->string('feature2_icon')->nullable()->default('fas fa-user-tie')->after('feature1_icon');
            }
            if (!Schema::hasColumn('custom_pages', 'feature3_icon')) {
                $table->string('feature3_icon')->nullable()->default('fas fa-car')->after('feature2_icon');
            }
            if (!Schema::hasColumn('custom_pages', 'feature4_icon')) {
                $table->string('feature4_icon')->nullable()->default('fas fa-thumbs-up')->after('feature3_icon');
            }
        });

        // custom_page_translations cədvəlinə yeni sütunlar əlavə et
        Schema::table('custom_page_translations', function (Blueprint $table) {
            if (!Schema::hasColumn('custom_page_translations', 'banner_title')) {
                $table->string('banner_title')->nullable()->after('description');
            }
            if (!Schema::hasColumn('custom_page_translations', 'banner_description')) {
                $table->text('banner_description')->nullable()->after('banner_title');
            }
            if (!Schema::hasColumn('custom_page_translations', 'form_title')) {
                $table->string('form_title')->nullable()->after('banner_description');
            }
            if (!Schema::hasColumn('custom_page_translations', 'form_description')) {
                $table->text('form_description')->nullable()->after('form_title');
            }
            if (!Schema::hasColumn('custom_page_translations', 'feature1_title')) {
                $table->string('feature1_title')->nullable()->after('form_description');
            }
            if (!Schema::hasColumn('custom_page_translations', 'feature1_description')) {
                $table->text('feature1_description')->nullable()->after('feature1_title');
            }
            if (!Schema::hasColumn('custom_page_translations', 'feature2_title')) {
                $table->string('feature2_title')->nullable()->after('feature1_description');
            }
            if (!Schema::hasColumn('custom_page_translations', 'feature2_description')) {
                $table->text('feature2_description')->nullable()->after('feature2_title');
            }
            if (!Schema::hasColumn('custom_page_translations', 'feature3_title')) {
                $table->string('feature3_title')->nullable()->after('feature2_description');
            }
            if (!Schema::hasColumn('custom_page_translations', 'feature3_description')) {
                $table->text('feature3_description')->nullable()->after('feature3_title');
            }
            if (!Schema::hasColumn('custom_page_translations', 'feature4_title')) {
                $table->string('feature4_title')->nullable()->after('feature3_description');
            }
            if (!Schema::hasColumn('custom_page_translations', 'feature4_description')) {
                $table->text('feature4_description')->nullable()->after('feature4_title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_pages', function (Blueprint $table) {
            $table->dropColumn([
                'banner_image', 'video_url', 'video_thumbnail',
                'doctor_name', 'doctor_image',
                'show_consultation_form', 'show_video_banner',
                'feature1_icon', 'feature2_icon', 'feature3_icon', 'feature4_icon',
            ]);
        });

        Schema::table('custom_page_translations', function (Blueprint $table) {
            $table->dropColumn([
                'banner_title', 'banner_description',
                'form_title', 'form_description',
                'feature1_title', 'feature1_description',
                'feature2_title', 'feature2_description',
                'feature3_title', 'feature3_description',
                'feature4_title', 'feature4_description',
            ]);
        });
    }
};