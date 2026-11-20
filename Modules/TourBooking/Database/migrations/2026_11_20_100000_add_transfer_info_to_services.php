<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Task 30 — «Коротко» box on the sanatorium page: per-sanatorium transfer text
 * (e.g. «Бесплатно из Гянджи · из Баку — платно»). Additive, nullable column.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('services') && ! Schema::hasColumn('services', 'transfer_info')) {
            Schema::table('services', function (Blueprint $table) {
                $table->string('transfer_info', 255)->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('services') && Schema::hasColumn('services', 'transfer_info')) {
            Schema::table('services', function (Blueprint $table) {
                $table->dropColumn('transfer_info');
            });
        }
    }
};
