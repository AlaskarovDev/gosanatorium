<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const SLUG = 'sun-city-osennyaya-kampaniya-skidka-do-10';

    public function up(): void
    {
        if (DB::table('akcii')->where('slug', self::SLUG)->exists()) {
            return;
        }

        $desc = "Осенняя кампания действует с 15 октября по 15 декабря 2026 года:\n\n"
            . "• Размещение 1 человека — скидка 5%\n"
            . "• Размещение 2 и более человек — скидка 10%\n\n"
            . "В стоимость входит: комфортное проживание, качественное питание, лечение и отдых, спокойная атмосфера.\n\n"
            . "Забронируйте заранее и проведите осень с пользой для здоровья.";

        $serviceId = null;
        if (Schema::hasTable('services')) {
            foreach (['sun-city', 'suncity', 'sun city'] as $k) {
                $serviceId = DB::table('services')->where('slug', 'like', '%' . $k . '%')->value('id');
                if (!$serviceId && Schema::hasTable('service_translations')) {
                    $serviceId = DB::table('service_translations')->where('title', 'like', '%' . $k . '%')->value('service_id');
                }
                if ($serviceId) {
                    break;
                }
            }
        }

        $now = now();
        DB::table('akcii')->insert([
            'title' => 'Осенняя кампания Sun City — скидка до 10%',
            'slug' => self::SLUG,
            'description' => $desc,
            'content' => nl2br(e($desc)),
            'discount_percent' => 10,
            'badge_text' => '-10%',
            'valid_from' => '2026-10-15',
            'valid_until' => '2026-12-15',
            'sanatorium_name' => 'Sun City Naftalan (Sun City Hotel and Spa Naftalan)',
            'service_id' => $serviceId,
            'is_hot' => false,
            'is_active' => true,
            'sort_order' => 110,
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
