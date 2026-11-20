<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Transfer prices (per car, USD) from Baku airport, as given by the client on 2026-11-20:
 * Baku → Naftalan $49, Baku → Lankaran $60. Only these two routes are touched.
 */
return new class extends Migration
{
    private array $prices = [
        'Нафталан' => 49,
        'Ленкорань' => 60,
    ];

    public function up(): void
    {
        if (! Schema::hasTable('transfer_routes')) {
            return;
        }
        foreach ($this->prices as $to => $price) {
            DB::table('transfer_routes')
                ->where('from_location', 'Heydar Aliyev International Airport')
                ->where('to_location', $to)
                ->update(['base_price' => $price, 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('transfer_routes')) {
            return;
        }
        DB::table('transfer_routes')
            ->where('from_location', 'Heydar Aliyev International Airport')
            ->where('to_location', 'Ленкорань')
            ->update(['base_price' => 49, 'updated_at' => now()]);
    }
};
