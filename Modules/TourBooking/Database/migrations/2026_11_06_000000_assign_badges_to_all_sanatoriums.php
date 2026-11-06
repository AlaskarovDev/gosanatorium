<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Task #306 — badges live in ONE place: services.badges (JSON). This migration makes sure the column
 * exists and fills it for every sanatorium from its real data (rating, price, description, amenities…).
 * Badges already set in the admin panel are kept; missing ones are appended. Further edits: admin panel.
 */
return new class extends Migration
{
    private const RULES = [
        // key => [label, existing-badge detection regex]
        'recommended' => ['⭐ Рекомендуем', '/рекоменду|recommend/iu'],
        'transfer' => ['🚌 Бесплатный трансфер', '/трансфер|transfer/iu'],
        'transfer_ganja' => ['🚌 Бесплатный трансфер из Гянджи', '/трансфер|transfer/iu'],
        'best_price' => ['💰 Лучшая цена', '/лучшая цена|best price/iu'],
        'salt' => ['🧂 Соляные пещеры', '/соляны|salt/iu'],
        'pine' => ['🌲 Сосновый парк', '/сосн|pine/iu'],
        'pool' => ['🏊 Бассейн', '/бассейн|pool/iu'],
        'kids7' => ['👶 Дети от 7 лет', '/дети от|children from/iu'],
        'new' => ['🏅 Новый санаторий', '/новый санаторий|new sanatorium/iu'],
        'naftalan' => ['🌿 Нафталан', '/нафталан|naftalan/iu'],
    ];

    public function up(): void
    {
        if (!Schema::hasTable('services')) {
            return;
        }
        if (!Schema::hasColumn('services', 'badges')) {
            Schema::table('services', function (Blueprint $table) {
                $table->json('badges')->nullable();
            });
        }

        $services = DB::table('services')->where('status', 1)->get();
        if ($services->isEmpty()) {
            return;
        }

        $ratings = DB::table('reviews')->where('status', 1)->select('service_id', DB::raw('AVG(rating) as a'))
            ->groupBy('service_id')->pluck('a', 'service_id');
        $destinations = Schema::hasTable('destinations') ? DB::table('destinations')->pluck('name', 'id') : collect();
        $translations = DB::table('service_translations')->get()->groupBy('service_id');

        // cheapest sanatorium in each category
        $price = fn ($s) => (float) ($s->is_per_person ? $s->price_per_person : ($s->discount_price ?: $s->full_price));
        $cheapest = [];
        foreach ($services->groupBy('service_type_id') as $group) {
            $priced = $group->filter(fn ($s) => $price($s) > 0);
            if ($priced->count() > 1) {
                $cheapest[$priced->sortBy($price)->first()->id] = true;
            }
        }

        foreach ($services as $s) {
            $text = [$s->slug, $destinations[$s->destination_id] ?? '', $s->location ?? '', $s->amenities ?? '', $s->facilities ?? '', $s->included ?? '', $s->description ?? ''];
            $title = '';
            foreach ($translations[$s->id] ?? [] as $t) {
                $title .= ' ' . $t->title;
                $text[] = $t->short_description;
                $text[] = $t->description;
                $text[] = $t->amenities;
                $text[] = $t->facilities;
                $text[] = $t->included;
            }
            $hay = mb_strtolower(strip_tags(html_entity_decode(json_encode($text, JSON_UNESCAPED_UNICODE) . ' ' . $title)));
            $hay = preg_replace('/\\\\u[0-9a-f]{4}/i', ' ', $hay);
            $has = fn ($re) => (bool) preg_match($re, $hay);

            $want = [];
            if (($ratings[$s->id] ?? 0) >= 4.8) $want[] = 'recommended';
            if ($has('/(бесплатн\w*\s+(аэропорт\w*\s+)?трансфер|free\s+(airport\s+)?transfer|трансфер\s+включ|transfer\s+included)/u')) {
                $want[] = $has('/(гянд|ганд|ganja|gence)/u') ? 'transfer_ganja' : 'transfer';
            }
            if (!empty($cheapest[$s->id])) $want[] = 'best_price';
            if ($has('/(солян\w+\s+(пещер|комнат|шахт)|соляная|галокамер|галотерап|salt\s+(room|cave|mine))/u')) $want[] = 'salt';
            if ($has('/(сосн|pine|хвойн)/u')) $want[] = 'pine';
            if ($has('/(бассейн|swimming\s*pool|\bpool\b)/u')) $want[] = 'pool';
            if ((int) $s->child_min_age === 7) $want[] = 'kids7';
            if ($s->is_new) $want[] = 'new';
            if ($has('/(нафталан|naftalan)/u')) $want[] = 'naftalan';

            $current = json_decode($s->badges ?? '[]', true);
            $current = is_array($current) ? $current : [];
            $existingText = implode(' | ', array_map(fn ($b) => is_array($b) ? ($b['text'] ?? '') : (string) $b, $current));

            foreach ($want as $key) {
                [$label, $detect] = self::RULES[$key];
                if (preg_match($detect, $existingText) && !in_array($key, ['transfer', 'transfer_ganja'], true)) {
                    continue;
                }
                if (in_array($key, ['transfer', 'transfer_ganja'], true) && preg_match($detect, $existingText)) {
                    continue;
                }
                $current[] = ['text' => $label, 'color' => ''];
                $existingText .= ' | ' . $label;
            }

            if ($current) {
                DB::table('services')->where('id', $s->id)->update(['badges' => json_encode($current, JSON_UNESCAPED_UNICODE)]);
            }
        }
    }

    public function down(): void
    {
        // Data migration — nothing to roll back.
    }
};
