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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->string('name'); // Room name (e.g., "Deluxe Room", "Suite")
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->decimal('price_per_night', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->integer('max_adults')->default(2);
            $table->integer('max_children')->default(1);
            $table->integer('room_size')->nullable(); // in square meters
            $table->string('bed_type')->nullable(); // single, double, king, twin
            $table->integer('bed_count')->default(1);
            $table->integer('total_rooms')->default(1); // Total available rooms of this type
            $table->json('amenities')->nullable(); // Room-specific amenities
            $table->json('features')->nullable(); // Room features like view, balcony, etc.
            $table->boolean('is_featured')->default(false);
            $table->boolean('status')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
