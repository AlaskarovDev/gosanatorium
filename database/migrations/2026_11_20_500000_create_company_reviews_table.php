<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Task 26: reviews about GoSanatorium itself (separate from sanatorium reviews), moderated in admin. */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('company_reviews')) {
            return;
        }
        Schema::create('company_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->string('country', 80)->nullable();
            $table->unsignedTinyInteger('rating');
            $table->text('text');
            $table->string('booking_code', 40)->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('status', 20)->default('pending')->index(); // pending | approved | rejected
            $table->text('reply')->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_reviews');
    }
};
