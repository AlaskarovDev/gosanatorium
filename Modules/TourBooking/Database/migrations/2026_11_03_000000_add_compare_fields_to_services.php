<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('services')) {
            return;
        }
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'procedures_per_day')) {
                $table->unsignedSmallInteger('procedures_per_day')->nullable();
            }
            if (!Schema::hasColumn('services', 'meals_included')) {
                $table->boolean('meals_included')->nullable();
            }
            if (!Schema::hasColumn('services', 'tests_included')) {
                $table->boolean('tests_included')->nullable();
            }
            if (!Schema::hasColumn('services', 'doctor_exam_included')) {
                $table->boolean('doctor_exam_included')->nullable();
            }
            if (!Schema::hasColumn('services', 'airport_distance_km')) {
                $table->decimal('airport_distance_km', 7, 1)->nullable();
            }
            if (!Schema::hasColumn('services', 'child_min_age')) {
                $table->unsignedSmallInteger('child_min_age')->nullable();
            }
            if (!Schema::hasColumn('services', 'child_discount')) {
                $table->string('child_discount', 100)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            foreach (['procedures_per_day', 'meals_included', 'tests_included', 'doctor_exam_included', 'airport_distance_km', 'child_min_age', 'child_discount'] as $c) {
                if (Schema::hasColumn('services', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
    }
};
