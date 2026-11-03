<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('akcii', function (Blueprint $table) {
            $table->unsignedBigInteger('service_id')->nullable()->after('sanatorium_name');
            $table->decimal('original_price', 10, 2)->nullable()->after('discount_percent');
            $table->decimal('discounted_price', 10, 2)->nullable()->after('original_price');
            $table->date('valid_from')->nullable()->after('discounted_price');
            $table->boolean('is_hot')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('akcii', function (Blueprint $table) {
            $table->dropColumn(['service_id', 'original_price', 'discounted_price', 'valid_from', 'is_hot']);
        });
    }
};
