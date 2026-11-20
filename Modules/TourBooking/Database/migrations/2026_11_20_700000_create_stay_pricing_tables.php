<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Round 2, Bug 4: length-of-stay prices and stay discount rules.
 *
 * room_stay_tiers: price per room per night for a season (null = base price outside seasons),
 *   a number of adults (0 = one child) and a range of nights of the whole booking (max null = no limit).
 * service_stay_discounts: percent off the stay for a number of nights and/or early booking
 *   (days between booking and arrival); all matching rules of a sanatorium add up.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('room_stay_tiers')) {
            Schema::create('room_stay_tiers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('room_id')->index();
                $table->unsignedBigInteger('season_id')->nullable()->index();
                $table->unsignedTinyInteger('adults');
                $table->unsignedSmallInteger('min_nights');
                $table->unsignedSmallInteger('max_nights')->nullable();
                $table->decimal('price', 10, 2);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('service_stay_discounts')) {
            Schema::create('service_stay_discounts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('service_id')->index();
                $table->unsignedSmallInteger('min_nights')->nullable();
                $table->unsignedSmallInteger('max_nights')->nullable();
                $table->unsignedSmallInteger('min_days_before')->nullable();
                $table->decimal('percentage', 5, 2);
                $table->string('note')->nullable();
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('room_stay_tiers');
        Schema::dropIfExists('service_stay_discounts');
    }
};
