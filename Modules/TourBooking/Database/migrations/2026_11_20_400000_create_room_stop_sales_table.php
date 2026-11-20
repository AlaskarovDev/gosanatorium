<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Task 42 — room availability: a period is either a stop-sale («Нет мест», rooms_left = null)
 * or a limited number of rooms left (rooms_left = N). Nights from date_from to date_to inclusive.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('room_stop_sales')) {
            return;
        }
        Schema::create('room_stop_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_room_id')->index();
            $table->date('date_from');
            $table->date('date_to');
            $table->unsignedSmallInteger('rooms_left')->nullable();
            $table->string('note', 255)->nullable();
            $table->timestamps();
            $table->index(['service_room_id', 'date_from', 'date_to']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_stop_sales');
    }
};
