<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** [title keyword, general, treatment, reviews] */
    private const DEFAULTS = [
        ['Chinar', 9.6, 9.7, 50],
        ['Garabagh', 9.4, 9.5, 38],
        ['Gashalti', 9.3, 9.4, 31],
        ['Sun City', 9.1, 9.2, 24],
        ['Naftalan Central', 8.9, 9.0, 19],
        ['Borjomi Palace', 9.2, 9.3, 22],
        ['Tskaltubo', 9.0, 9.1, 17],
    ];

    public function up(): void
    {
        if (!Schema::hasTable('services')) {
            return;
        }

        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'rating_general')) {
                $table->decimal('rating_general', 3, 1)->nullable();
            }
            if (!Schema::hasColumn('services', 'rating_treatment')) {
                $table->decimal('rating_treatment', 3, 1)->nullable();
            }
            if (!Schema::hasColumn('services', 'reviews_count')) {
                $table->unsignedInteger('reviews_count')->default(0);
            }
        });

        $typeIds = DB::table('service_types')->whereIn('slug', ['hotels', 'sanatoriums'])->pluck('id');
        if ($typeIds->isEmpty()) {
            return;
        }

        $done = [];
        foreach (self::DEFAULTS as [$keyword, $general, $treatment, $count]) {
            $ids = DB::table('services')
                ->whereIn('service_type_id', $typeIds)
                ->where(function ($q) use ($keyword) {
                    $q->where('slug', 'like', '%' . str_replace(' ', '-', strtolower($keyword)) . '%')
                      ->orWhereIn('id', DB::table('service_translations')->where('title', 'like', '%' . $keyword . '%')->pluck('service_id'));
                })
                ->pluck('id')->all();
            if ($ids) {
                DB::table('services')->whereIn('id', $ids)->update([
                    'rating_general' => $general,
                    'rating_treatment' => $treatment,
                    'reviews_count' => $count,
                ]);
                $done = array_merge($done, $ids);
            }
        }

        DB::table('services')
            ->whereIn('service_type_id', $typeIds)
            ->whereNotIn('id', $done)
            ->whereNull('rating_general')
            ->update(['rating_general' => 8.8, 'rating_treatment' => 9.0, 'reviews_count' => 12]);
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['rating_general', 'rating_treatment', 'reviews_count']);
        });
    }
};
