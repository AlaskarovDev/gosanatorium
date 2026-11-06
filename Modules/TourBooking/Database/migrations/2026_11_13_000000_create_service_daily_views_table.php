<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('service_daily_views')) {
            return;
        }
        Schema::create('service_daily_views', function (Blueprint $table) {
            $table->unsignedBigInteger('service_id');
            $table->date('viewed_on');
            $table->unsignedInteger('views')->default(0);
            $table->primary(['service_id', 'viewed_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_daily_views');
    }
};
