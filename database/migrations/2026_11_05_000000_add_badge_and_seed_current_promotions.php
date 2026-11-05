<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function promos(): array
    {
        // [slug, title, badge, description, valid_until, sanatorium, service slug/title keywords]
        return [
            ['sairme-skidka-do-10-rannee-bronirovanie', 'Скидка до 10% в Саирме — раннее бронирование', '-10%', 'При бронировании за 1 месяц вперёд — скидка 5%. При проживании от 6 ночей — дополнительно 5%. Итого скидка до 10%.', null, 'Sairme Hotels & Resorts', ['sairme', 'саирме'], 10],
            ['borzhomi-skidka-5', 'Скидка 5% в санатории Боржоми', '-5%', 'Специальное предложение — скидка 5% на все пакеты в санатории Боржоми.', null, 'Боржоми', ['borjomi', 'borzhomi', 'боржоми'], 5],
            ['karvan-noyabr-skidka-10-dekabr-podarki', 'Ноябрь: скидка 10% + декабрьские пакеты в подарок — Карван', '-10%', "В ноябре — скидка 10% на все пакеты. В декабре действуют специальные пакеты: 6 ночей + 1 в подарок, 10 ночей + 2 в подарок.", '2026-12-31', 'Карван (Karvan Hotel Naftalan)', ['karvan', 'карван'], 10],
            ['gashalti-skidka-10-medicinskie-pakety', 'Скидка 10% на все медицинские пакеты — Гашалты', '-10%', 'Скидка 10% на все лечебные и медицинские пакеты в санатории Гашалты.', null, 'Гашалты (Gashalti Health Hotel Naftalan)', ['gashalti', 'гашалты'], 10],
            ['gold-naftalan-nochi-v-podarok-do-20-dekabrya', 'Ночи в подарок — Gold Naftalan (до 20 декабря)', '🔥', "Акция действует с 10 сентября по 20 декабря 2026:\n• 5 ночей + 2 ночи в подарок\n• 8 ночей + 3 ночи в подарок\n• 12 ночей + 4 ночи в подарок", '2026-12-20', 'Gold Naftalan', ['gold-naftalan', 'gold naftalan'], null],
            ['naftalan-central-pakety-6-1-i-10-2', 'Пакеты 6+1 и 10+2 — Нафталан Централ', '+1 / +2', "Специальные пакеты в санатории Нафталан Централ:\n• 6 ночей + 1 ночь в подарок\n• 10 ночей + 2 ночи в подарок", null, 'Нафталан Централ (Naftalan Central)', ['naftalan-central', 'naftalan central', 'нафталан централ'], null],
            ['chinar-skidka-10', 'Скидка 10% в санатории Чинар', '-10%', 'Скидка 10% на все пакеты в санатории Чинар Hotel Spa Naftalan.', null, 'Чинар (Chinar Hotel Spa Naftalan)', ['chinar', 'чинар'], 10],
        ];
    }

    public function up(): void
    {
        if (!Schema::hasColumn('akcii', 'badge_text')) {
            Schema::table('akcii', function (Blueprint $table) {
                $table->string('badge_text', 30)->nullable()->after('discount_percent');
            });
        }

        $now = now();
        foreach ($this->promos() as $i => [$slug, $title, $badge, $desc, $until, $san, $keys, $pct]) {
            if (DB::table('akcii')->where('slug', $slug)->exists()) {
                continue;
            }

            $serviceId = null;
            if (Schema::hasTable('services')) {
                foreach ($keys as $k) {
                    $serviceId = DB::table('services')->where('slug', 'like', '%' . $k . '%')->value('id');
                    if (!$serviceId && Schema::hasTable('service_translations')) {
                        $serviceId = DB::table('service_translations')->where('title', 'like', '%' . $k . '%')->value('service_id');
                    }
                    if ($serviceId) {
                        break;
                    }
                }
            }

            DB::table('akcii')->insert([
                'title' => $title,
                'slug' => $slug,
                'description' => $desc,
                'content' => nl2br(e($desc)),
                'discount_percent' => $pct,
                'badge_text' => $badge,
                'valid_until' => $until,
                'sanatorium_name' => $san,
                'service_id' => $serviceId,
                'is_hot' => $badge === '🔥',
                'is_active' => true,
                'sort_order' => 100 + $i,
                'lang' => 'ru',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('akcii')->whereIn('slug', array_column($this->promos(), 0))->delete();
        if (Schema::hasColumn('akcii', 'badge_text')) {
            Schema::table('akcii', function (Blueprint $table) {
                $table->dropColumn('badge_text');
            });
        }
    }
};
