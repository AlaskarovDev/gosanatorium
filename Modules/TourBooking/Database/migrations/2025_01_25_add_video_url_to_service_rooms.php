<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ============================================================
 * Migration: service_rooms və service_room_media cədvəlləri
 * ============================================================
 * 
 * Fayl adı: 2025_01_25_create_service_rooms_tables.php
 * Qovluq: database/migrations/ və ya 
 *         Modules/TourBooking/database/migrations/
 * 
 * İcra: php artisan migrate
 * ============================================================
 */

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // service_rooms cədvəli
        if (!Schema::hasTable('service_rooms')) {
            Schema::create('service_rooms', function (Blueprint $table) {
                $table->id();
                $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('price_per_night', 10, 2);
                $table->decimal('discount_price', 10, 2)->nullable();
                $table->integer('max_guests')->nullable()->default(2);
                $table->integer('room_size')->nullable()->comment('m²');
                $table->string('bed_type', 100)->nullable();
                $table->integer('quantity')->default(1);
                $table->boolean('status')->default(true);
                $table->json('features')->nullable();
                $table->string('thumbnail')->nullable();
                $table->string('video_url')->nullable();
                $table->integer('display_order')->default(0);
                $table->timestamps();
                
                $table->index('service_id');
                $table->index('status');
            });
        }
        
        // service_room_media cədvəli
        if (!Schema::hasTable('service_room_media')) {
            Schema::create('service_room_media', function (Blueprint $table) {
                $table->id();
                $table->foreignId('service_room_id')->constrained('service_rooms')->onDelete('cascade');
                $table->string('file_path');
                $table->enum('media_type', ['image', 'video'])->default('image');
                $table->boolean('is_thumbnail')->default(false);
                $table->integer('display_order')->default(0);
                $table->timestamps();
                
                $table->index('service_room_id');
                $table->index(['service_room_id', 'is_thumbnail']);
                $table->index(['service_room_id', 'media_type']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_room_media');
        Schema::dropIfExists('service_rooms');
    }
};


/**
 * ============================================================
 * Əgər service_rooms cədvəli artıq varsa və video_url əlavə etmək lazımdırsa:
 * ============================================================
 */

/*
// Ayrıca migration yaradın: 2025_01_25_add_video_url_to_service_rooms.php

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_rooms', function (Blueprint $table) {
            if (!Schema::hasColumn('service_rooms', 'video_url')) {
                $table->string('video_url')->nullable()->after('thumbnail');
            }
        });
    }

    public function down(): void
    {
        Schema::table('service_rooms', function (Blueprint $table) {
            $table->dropColumn('video_url');
        });
    }
};
*/