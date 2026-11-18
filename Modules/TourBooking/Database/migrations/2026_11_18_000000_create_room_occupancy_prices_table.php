<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\TourBooking\App\Models\Season;
use Modules\TourBooking\App\Models\ServiceRoom;
use Modules\TourBooking\App\Services\RoomPricing;

/**
 * Room prices per season and per number of adults.
 * Existing prices are copied in as the 2-adult price (1 adult for single rooms),
 * with today's season rules applied, so the site shows the same prices as before.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('room_occupancy_prices')) {
            Schema::create('room_occupancy_prices', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('room_id')->index();
                $table->unsignedBigInteger('season_id')->nullable()->index();
                $table->unsignedTinyInteger('adults');
                $table->decimal('price', 10, 2);
                $table->timestamps();
                $table->unique(['room_id', 'season_id', 'adults'], 'room_occ_room_season_adults_unique');
            });
        }

        if (Schema::hasTable('services')) {
            Schema::table('services', function (Blueprint $table) {
                if (! Schema::hasColumn('services', 'min_stay_nights')) {
                    $table->unsignedSmallInteger('min_stay_nights')->nullable();
                }
                if (! Schema::hasColumn('services', 'meal_plan')) {
                    $table->string('meal_plan')->nullable();
                }
            });
        }

        if (! Schema::hasTable('service_rooms')) {
            return;
        }

        $seasons = Schema::hasTable('seasons') ? Season::query()->orderBy('start_date')->get() : collect();
        $hasRules = Schema::hasTable('season_prices');
        $now = now();

        ServiceRoom::query()->orderBy('id')->chunkById(100, function ($rooms) use ($seasons, $hasRules, $now) {
            foreach ($rooms as $room) {
                if (DB::table('room_occupancy_prices')->where('room_id', $room->id)->exists()) {
                    continue; // already filled (migration re-run)
                }
                if (! $hasRules) {
                    $room->setRelation('seasonPrices', collect());
                }
                $grid = RoomPricing::legacyGrid($room, $seasons);
                $rows = [];
                foreach ($grid as $key => $byAdults) {
                    foreach ($byAdults as $adults => $price) {
                        if ($price <= 0) {
                            continue;
                        }
                        $rows[] = [
                            'room_id'    => $room->id,
                            'season_id'  => $key === RoomPricing::BASE ? null : (int) $key,
                            'adults'     => (int) $adults,
                            'price'      => $price,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }
                if ($rows) {
                    DB::table('room_occupancy_prices')->insert($rows);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_occupancy_prices');

        if (Schema::hasTable('services')) {
            Schema::table('services', function (Blueprint $table) {
                foreach (['min_stay_nights', 'meal_plan'] as $col) {
                    if (Schema::hasColumn('services', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
