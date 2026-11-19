<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Date discounts per sanatorium: between start_date and end_date (inclusive)
 * every night of every room of the sanatorium is cheaper by `percentage`.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('service_date_discounts')) {
            return;
        }

        Schema::create('service_date_discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id')->index();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('percentage', 5, 2);
            $table->string('note')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->index(['service_id', 'start_date', 'end_date'], 'sdd_service_dates_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_date_discounts');
    }
};
