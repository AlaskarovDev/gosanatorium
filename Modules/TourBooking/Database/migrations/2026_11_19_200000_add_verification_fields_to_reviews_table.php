<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reviews')) {
            return;
        }

        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'country')) {
                $table->string('country', 100)->nullable()->after('review_title');
            }
            if (!Schema::hasColumn('reviews', 'city')) {
                $table->string('city', 100)->nullable()->after('country');
            }
            if (!Schema::hasColumn('reviews', 'language')) {
                $table->string('language', 10)->nullable()->after('city');
            }
            if (!Schema::hasColumn('reviews', 'stay_month')) {
                // YYYY-MM
                $table->string('stay_month', 7)->nullable()->after('language');
            }
            if (!Schema::hasColumn('reviews', 'booking_reference')) {
                $table->string('booking_reference', 64)->nullable()->after('stay_month');
            }
            if (!Schema::hasColumn('reviews', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('booking_reference');
            }
            if (!Schema::hasColumn('reviews', 'review_ru')) {
                // Russian translation; `review` keeps the original text
                $table->text('review_ru')->nullable()->after('review');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('reviews')) {
            return;
        }

        Schema::table('reviews', function (Blueprint $table) {
            foreach (['country', 'city', 'language', 'stay_month', 'booking_reference', 'is_verified', 'review_ru'] as $col) {
                if (Schema::hasColumn('reviews', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
