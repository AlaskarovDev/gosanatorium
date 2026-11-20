<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Task 32: one table for all lead forms (contact, consultation, later transfer / tour / quiz / calculator). */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('leads')) {
            return;
        }
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('type', 30)->default('contact')->index();
            $table->string('name', 120);
            $table->string('phone', 40);
            $table->string('contact_method', 20)->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->string('service_title', 255)->nullable();
            $table->string('dates', 120)->nullable();
            $table->text('message')->nullable();
            $table->json('payload')->nullable();
            $table->string('status', 20)->default('new')->index(); // new | in_work | done | spam
            $table->string('source_url', 500)->nullable();
            $table->string('country', 2)->nullable();
            $table->string('ip', 45)->nullable();
            $table->boolean('telegram_sent')->default(false);
            $table->boolean('email_sent')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
