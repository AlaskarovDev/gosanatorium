<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const SLUG = 'park-naftalan-skidka-15';

    public function up(): void
    {
        if (DB::table('akcii')->where('slug', self::SLUG)->exists()) {
            return;
        }

        $desc = 'Успейте забронировать санаторий Парк Нафталан со скидкой 15%. Предложение действует до 25 декабря 2026 года. Лечение нафталаном, все процедуры включены.';

        $serviceId = null;
        if (Schema::hasTable('services')) {
            $serviceId = DB::table('services')->where('slug', 'park-naftalan-sanatoriy-2026')->value('id')
                ?: DB::table('services')->where('slug', 'like', '%park-naftalan%')->value('id');
        }

        $now = now();
        DB::table('akcii')->insert([
            'title' => 'Парк Нафталан — скидка 15%',
            'slug' => self::SLUG,
            'description' => $desc,
            'content' => nl2br(e($desc)),
            'discount_percent' => 15,
            'badge_text' => '-15%',
            'valid_from' => null,
            'valid_until' => '2026-12-25',
            'sanatorium_name' => 'Park Naftalan',
            'service_id' => $serviceId,
            'is_hot' => false,
            'is_active' => true,
            'sort_order' => 120,
            'lang' => 'ru',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function down(): void
    {
        DB::table('akcii')->where('slug', self::SLUG)->delete();
    }
};
