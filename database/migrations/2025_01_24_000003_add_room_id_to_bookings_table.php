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
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('room_id')->nullable()->after('service_id')->constrained('rooms')->onDelete('set null');
            $table->decimal('room_price', 10, 2)->nullable()->after('service_price');
            $table->integer('nights')->default(1)->after('check_out_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
            $table->dropColumn(['room_id', 'room_price', 'nights']);
        });
    }
};
