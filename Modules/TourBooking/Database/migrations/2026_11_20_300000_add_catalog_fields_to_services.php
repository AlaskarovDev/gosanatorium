<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Task 28/29 — catalog filters, sorting and comparison: stars, pools, spa, free transfer,
 * manual «popular» weight. Additive, nullable columns.
 */
return new class extends Migration
{
    private array $columns = ['stars', 'pool_indoor', 'pool_outdoor', 'has_spa', 'free_transfer', 'sort_weight'];

    public function up(): void
    {
        if (! Schema::hasTable('services')) {
            return;
        }
        Schema::table('services', function (Blueprint $table) {
            if (! Schema::hasColumn('services', 'stars')) {
                $table->unsignedTinyInteger('stars')->nullable();
            }
            foreach (['pool_indoor', 'pool_outdoor', 'has_spa', 'free_transfer'] as $c) {
                if (! Schema::hasColumn('services', $c)) {
                    $table->boolean($c)->nullable();
                }
            }
            if (! Schema::hasColumn('services', 'sort_weight')) {
                $table->integer('sort_weight')->default(0);
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('services')) {
            return;
        }
        Schema::table('services', function (Blueprint $table) {
            foreach ($this->columns as $c) {
                if (Schema::hasColumn('services', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
    }
};
