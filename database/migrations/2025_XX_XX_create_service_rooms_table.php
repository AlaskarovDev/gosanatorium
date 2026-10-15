<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price_per_night', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->integer('max_guests')->default(2);
            $table->integer('room_size')->nullable(); // sqm
            $table->string('bed_type')->nullable();
            $table->json('amenities')->nullable();
            $table->json('features')->nullable();
            $table->integer('quantity')->default(1); // Available room count
            $table->boolean('status')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        Schema::create('service_room_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_room_id')->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->enum('media_type', ['image', 'video'])->default('image');
            $table->string('caption')->nullable();
            $table->boolean('is_thumbnail')->default(false);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_room_media');
        Schema::dropIfExists('service_rooms');
    }
};