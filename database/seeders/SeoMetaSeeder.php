<?php

namespace Database\Seeders;

use App\Models\SeoMeta;
use Illuminate\Database\Seeder;

class SeoMetaSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            'home' => [
                'GoSanatorium — Санатории без предоплаты',
                'Бронируйте санатории в Нафталане, Боржоми, Цхалтубо без предоплаты. Консультация врача бесплатно.',
            ],
            'diseases' => [
                'Санатории по заболеваниям — подбор по диагнозу',
                'Найдите санаторий по вашему диагнозу. Лечение суставов, кожных заболеваний, нервной системы.',
            ],
            'bez-predoplaty' => [
                'Бронирование без предоплаты — GoSanatorium',
                'Забронируйте санаторий без предоплаты. Платите только по приезду.',
            ],
            'blogs' => [
                'Блог о санаториях — советы, цены, маршруты',
                'Полезные статьи о лечении в Нафталане, Боржоми и Цхалтубо.',
            ],
        ];

        foreach ($rows as $key => [$title, $description]) {
            // firstOrCreate: never overwrite values already edited in the admin panel
            SeoMeta::firstOrCreate(
                ['page_key' => $key],
                ['seo_title' => $title, 'seo_description' => $description, 'robots' => 'index,follow']
            );
        }
    }
}
