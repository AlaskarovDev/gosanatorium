<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('akcii', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('image')->nullable();
            $table->integer('discount_percent')->nullable();
            $table->date('valid_until')->nullable();
            $table->string('sanatorium_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('lang')->default('ru');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akcii');
    }
};
