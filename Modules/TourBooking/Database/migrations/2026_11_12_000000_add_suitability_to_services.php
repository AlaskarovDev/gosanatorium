<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Task #314 — services.suitable_for / services.not_suitable_for (JSON arrays):
 * "Подходит вам если..." / "Возможно, не подходит если..." per sanatorium.
 * Fills sanatoriums that have none yet; further edits: admin panel.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('services')) {
            return;
        }
        foreach (['suitable_for', 'not_suitable_for'] as $col) {
            if (!Schema::hasColumn('services', $col)) {
                Schema::table('services', function (Blueprint $table) use ($col) {
                    $table->json($col)->nullable();
                });
            }
        }

        $services = DB::table('services')->where('status', 1)->get();
        $destinations = Schema::hasTable('destinations') ? DB::table('destinations')->pluck('name', 'id') : collect();
        $translations = Schema::hasTable('service_translations') ? DB::table('service_translations')->get()->groupBy('service_id') : collect();

        foreach ($services as $s) {
            $empty = fn ($v) => empty($v) || $v === 'null' || $v === '[]';
            if (!$empty($s->suitable_for) && !$empty($s->not_suitable_for)) {
                continue;
            }
            $title = '';
            $text = [$s->slug, $destinations[$s->destination_id] ?? '', $s->location ?? '', $s->amenities ?? '', $s->facilities ?? '', $s->included ?? ''];
            foreach ($translations[$s->id] ?? [] as $t) {
                $title .= ' ' . $t->title;
                array_push($text, $t->short_description, $t->description, $t->amenities, $t->facilities, $t->included);
            }
            $hay = mb_strtolower(strip_tags(html_entity_decode(json_encode($text, JSON_UNESCAPED_UNICODE))));
            $hay = preg_replace('/\\\\u[0-9a-f]{4}/i', ' ', $hay);
            $name = mb_strtolower($s->slug . ' ' . $title);
            $has = fn ($re) => (bool) preg_match($re, $hay);

            $data = $this->fixed($name) ?? $this->derived($s, trim($title), $destinations[$s->destination_id] ?? '', $has);
            $update = [];
            if ($empty($s->suitable_for) && !empty($data[0])) {
                $update['suitable_for'] = json_encode($data[0], JSON_UNESCAPED_UNICODE);
            }
            if ($empty($s->not_suitable_for) && !empty($data[1])) {
                $update['not_suitable_for'] = json_encode($data[1], JSON_UNESCAPED_UNICODE);
            }
            if ($update) {
                DB::table('services')->where('id', $s->id)->update($update);
            }
        }
    }

    /** @return array{0: string[], 1: string[]}|null */
    private function fixed(string $n): ?array
    {
        if (preg_match('/park[\s-]*naftalan|парк[\s-]*нафталан/u', $n)) {
            return [[
                'У вас псориаз, экзема или атопический дерматит',
                'Боли в суставах, артроз, остеохондроз',
                'Хотите лечение + комфортный отдых в одном месте',
                'Едете из России, Казахстана или Узбекистана',
                'Предпочитаете новый современный санаторий (2022 г.)',
            ], [
                'Вам нужен только пляжный отдых без лечения',
                'Дети до 6 лет — не принимаются',
                'Есть онкологические заболевания (противопоказание)',
            ]];
        }
        if (preg_match('/sun[\s-]*city|сан[\s-]*сити/u', $n)) {
            return [[
                'Хотите самый новый санаторий Нафталана (2023 г.)',
                'Нужны раздельные залы ванн для мужчин и женщин',
                'Хотите воспользоваться скидкой до 10%',
                'Едете вдвоём или семьёй',
            ], [
                'Нужен 5-звёздочный уровень (санаторий 4★)',
                'Дети до 7 лет — не принимаются',
            ]];
        }
        if (preg_match('/lankaran|ленкорань|лянкяран/u', $n)) {
            return [[
                'Хотите термальные источники + Каспийское море рядом',
                'Диабет, подагра, метаболические нарушения',
                'Нужен 5-звёздочный уровень сервиса',
                'Хотите совместить лечение с морским отдыхом',
            ], [
                'Нужны именно нафталановые ванны (их здесь нет)',
                'Едете только ради суставов',
            ]];
        }
        if (preg_match('/gold[\s-]*naftalan|голд[\s-]*нафталан/u', $n)) {
            return [[
                'Хотите нафталановое лечение в 5-звёздочном комфорте',
                'Важны индивидуальный план лечения и SPA после процедур',
                'Лечите кожу (псориаз, экзему) или суставы и хотите максимум сервиса',
                'Ценность отдыха для вас не меньше, чем лечения',
            ], [
                'Ищете самый бюджетный вариант лечения',
                'Нужен только пляжный отдых без процедур',
            ]];
        }
        if (preg_match('/borjomi|боржоми|georgia|грузи/u', $n)) {
            return [[
                'Лечите желудочно-кишечный тракт, почки или обмен веществ',
                'Хотите пить минеральную воду Боржоми прямо из источника',
                'Любите горный воздух и спокойный отдых на природе',
                'Едете из стран СНГ — виза не требуется',
            ], [
                'Вам нужны именно нафталановые ванны',
                'Хотите морской пляжный отдых',
            ]];
        }
        return null;
    }

    /** Points built from the sanatorium's own data, so they differ between sanatoriums. */
    private function derived(object $s, string $title, string $place, callable $has): array
    {
        $yes = [];
        $no = [];
        $place = trim($place) ?: trim((string) $s->location);
        $naftalan = $has('/(нафталан|naftalan)/u');
        if ($naftalan) {
            $yes[] = 'Нужны нафталановые ванны при заболеваниях суставов, кожи или нервной системы';
        } else {
            $no[] = 'Нужны именно нафталановые ванны (их здесь нет)';
        }
        if ($has('/(солян\w+\s+(пещер|комнат|шахт)|соляная|галокамер|галотерап|salt\s+(room|cave|mine))/u')) $yes[] = 'Хотите дышать лучше: в санатории есть соляная комната';
        if ($has('/(сосн|pine|хвойн)/u')) $yes[] = 'Цените чистый воздух и сосновый парк рядом';
        if ($has('/(бассейн|swimming\s*pool|\bpool\b)/u')) $yes[] = 'Хотите совмещать процедуры с плаванием в бассейне';
        if ($has('/(термальн|thermal|минеральн\w+\s+(вод|источник))/u')) $yes[] = 'Вам показано лечение термальными и минеральными водами';
        if ($has('/(море|морск|beach|пляж)/u')) $yes[] = 'Хотите добавить к лечению прогулки у моря';
        if ($has('/(диабет|подагр|метабол)/u')) $yes[] = 'Есть диабет, подагра или нарушения обмена веществ';
        if ($s->is_new) $yes[] = 'Предпочитаете новый санаторий с современным оборудованием';
        if ((int) $s->procedures_per_day > 0) $yes[] = 'Готовы к лечению: до ' . (int) $s->procedures_per_day . ' процедур в день по назначению врача';
        if ($s->doctor_exam_included) $yes[] = 'Хотите, чтобы программу подобрал врач после осмотра';
        if ($s->meals_included) $yes[] = 'Хотите, чтобы питание уже было включено в курс';
        if ($place !== '') $yes[] = 'Вам удобно лечиться в регионе: ' . $place;
        if ((int) $s->child_min_age > 0) $no[] = 'Дети до ' . (int) $s->child_min_age . ' лет — не принимаются';
        if (!$has('/(море|морск|beach|пляж)/u')) $no[] = 'Вам нужен пляжный отдых — моря рядом нет';
        $no[] = 'Есть онкологические заболевания или острые состояния — это противопоказание, нужна консультация врача';

        $yes = array_slice($yes, 0, 4);
        if ($title !== '' && count($yes) < 3) {
            $yes[] = 'Хотите индивидуальную программу лечения в санатории «' . $title . '»';
        }

        return [$yes, array_slice($no, 0, 3)];
    }

    public function down(): void
    {
        foreach (['suitable_for', 'not_suitable_for'] as $col) {
            if (Schema::hasColumn('services', $col)) {
                Schema::table('services', function (Blueprint $table) use ($col) {
                    $table->dropColumn($col);
                });
            }
        }
    }
};
