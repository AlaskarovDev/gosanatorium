<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('service_rooms') && !Schema::hasColumn('service_rooms', 'is_popular')) {
            Schema::table('service_rooms', function (Blueprint $table) {
                $table->boolean('is_popular')->default(false);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('service_rooms', 'is_popular')) {
            Schema::table('service_rooms', function (Blueprint $table) {
                $table->dropColumn('is_popular');
            });
        }
    }
};
