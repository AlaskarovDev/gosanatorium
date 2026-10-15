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
        Schema::create('transfer_routes', function (Blueprint $table) {
            $table->id();
            $table->string('from_location');
            $table->string('to_location');
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2)->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->boolean('status')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
            
            // İndeksləmə
            $table->index(['status', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_routes');
    }
};