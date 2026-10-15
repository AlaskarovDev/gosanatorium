<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfer_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            
            // Foreign keys - nullable to avoid constraint issues
            $table->unsignedBigInteger('route_id')->nullable();
            $table->unsignedBigInteger('vehicle_id')->nullable();
            
            $table->date('transfer_date');
            $table->time('transfer_time')->nullable();
            $table->integer('passengers')->default(1);
            $table->string('pickup_address')->nullable();
            $table->string('dropoff_address')->nullable();
            $table->string('flight_number')->nullable();
            $table->text('special_requests')->nullable();
            
            $table->string('guest_name');
            $table->string('guest_email');
            $table->string('guest_phone');
            
            $table->decimal('base_price', 10, 2)->default(0);
            $table->decimal('vehicle_price', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2)->default(0);
            
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
            $table->text('admin_notes')->nullable();
            
            $table->timestamps();
            
            $table->index(['status', 'transfer_date']);
            $table->index('booking_code');
            
            // Add foreign keys only if tables exist
            if (Schema::hasTable('transfer_routes')) {
                $table->foreign('route_id')->references('id')->on('transfer_routes')->nullOnDelete();
            }
            if (Schema::hasTable('transfer_vehicles')) {
                $table->foreign('vehicle_id')->references('id')->on('transfer_vehicles')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfer_bookings');
    }
};
