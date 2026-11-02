<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('service_media') && !Schema::hasColumn('service_media', 'category')) {
            Schema::table('service_media', function (Blueprint $table) {
                $table->string('category', 20)->default('other');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('service_media', 'category')) {
            Schema::table('service_media', function (Blueprint $table) {
                $table->dropColumn('category');
            });
        }
    }
};
