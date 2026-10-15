<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfer_vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->integer('passenger_capacity')->default(4);
            $table->integer('luggage_capacity')->default(2);
            $table->json('features')->nullable();
            $table->decimal('base_price', 10, 2)->default(0);
            $table->decimal('price_per_km', 10, 2)->nullable();
            $table->boolean('status')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
            
            $table->index(['status', 'display_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfer_vehicles');
    }
};
