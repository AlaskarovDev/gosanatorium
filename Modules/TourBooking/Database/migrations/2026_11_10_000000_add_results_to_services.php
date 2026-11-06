<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Task #312 — services.results (JSON): "Наши гости после курса" points per sanatorium.
 * Fills the column for every sanatorium that has none yet; further edits: admin panel.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('services')) {
            return;
        }
        if (!Schema::hasColumn('services', 'results')) {
            Schema::table('services', function (Blueprint $table) {
                $table->json('results')->nullable();
            });
        }

        $services = DB::table('services')->where('status', 1)->get();
        $destinations = Schema::hasTable('destinations') ? DB::table('destinations')->pluck('name', 'id') : collect();
        $translations = DB::table('service_translations')->get()->groupBy('service_id');

        foreach ($services as $s) {
            if (!empty($s->results) && $s->results !== 'null' && $s->results !== '[]') {
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

            $results = $this->fixed($name);
            if (!$results) {
                $results = $this->derived($s, trim($title), $destinations[$s->destination_id] ?? '', $has);
            }
            if ($results) {
                DB::table('services')->where('id', $s->id)->update(['results' => json_encode($results, JSON_UNESCAPED_UNICODE)]);
            }
        }
    }

    private function fixed(string $n): ?array
    {
        if (preg_match('/park[\s-]*naftalan|парк[\s-]*нафталан/u', $n)) {
            return ['Псориаз — улучшение заметно с 5–7-го дня курса', 'Боли в суставах — снижение у большинства гостей к 10-му дню', 'Каждый 3-й гость возвращается повторно', 'Дети принимаются с 6 лет — безопасные процедуры'];
        }
        if (preg_match('/gold[\s-]*naftalan|голд[\s-]*нафталан/u', $n)) {
            return ['5-звёздочный уровень сервиса — единственный в Нафталане', 'Нафталановые ванны + современный SPA в одном месте', 'Индивидуальный план лечения с первого дня', 'Бесплатный трансфер из аэропорта Гянджа'];
        }
        if (preg_match('/sun[\s-]*city|сан[\s-]*сити/u', $n)) {
            return ['Новейший санаторий Нафталана — открыт в 2023 году', '2 отдельных зала нафталановых ванн — мужской и женский', 'Полное обследование 6 специалистами на 2-й день', 'Скидка до 10% — актуальное предложение'];
        }
        if (preg_match('/lankaran|ленкорань|лянкяран/u', $n)) {
            return ['12 природных термальных источников прямо на территории', 'Единственный 5-звёздочный бальнеологический курорт Азербайджана', 'Лечение диабета, подагры, кожных и суставных заболеваний', 'Бесплатный трансфер с пляжа Каспийского моря'];
        }
        if (preg_match('/borjomi|боржоми|georgia|грузи/u', $n)) {
            return ['Знаменитая минеральная вода Боржоми — прямо из источника', 'Лечение ЖКТ, почек, обмена веществ', 'Уникальный горный воздух Кавказа', 'Безвизовый въезд для граждан СНГ'];
        }
        return null;
    }

    /** Unique points built from the sanatorium's own data (name, place, features). */
    private function derived(object $s, string $title, string $place, callable $has): array
    {
        $r = [];
        $place = trim($place) ?: trim((string) $s->location);
        if ($has('/(нафталан|naftalan)/u')) $r[] = 'Нафталановые ванны и лечение суставов, кожи и нервной системы';
        if ($has('/(солян\w+\s+(пещер|комнат|шахт)|соляная|галокамер|галотерап|salt\s+(room|cave|mine))/u')) $r[] = 'Соляная комната: облегчение дыхания уже в первую неделю';
        if ($has('/(сосн|pine|хвойн)/u')) $r[] = 'Сосновый парк на территории — чистый воздух для восстановления';
        if ($has('/(бассейн|swimming\s*pool|\bpool\b)/u')) $r[] = 'Бассейн на территории — реабилитация и отдых после процедур';
        if ($has('/(бесплатн\w*\s+(аэропорт\w*\s+)?трансфер|free\s+(airport\s+)?transfer|трансфер\s+включ)/u')) $r[] = 'Бесплатный трансфер — удобный приезд без забот';
        if ((int) $s->child_min_age > 0) $r[] = 'Дети принимаются с ' . (int) $s->child_min_age . ' лет';
        if ((int) $s->procedures_per_day > 0) $r[] = 'До ' . (int) $s->procedures_per_day . ' лечебных процедур в день по назначению врача';
        if ($s->doctor_exam_included) $r[] = 'Осмотр врача включён — программа подбирается индивидуально';
        if ($s->tests_included) $r[] = 'Анализы и обследование включены в курс';
        if ($s->meals_included) $r[] = 'Питание включено в стоимость курса';
        if ($s->is_new) $r[] = 'Новый санаторий — современное оборудование и обновлённые номера';
        if ($place !== '') $r[] = 'Лечение в регионе: ' . $place;
        $r = array_slice($r, 0, 4);
        if ($title !== '' && count($r) < 3) {
            $r[] = $title . ': индивидуальная программа лечения и восстановления';
        }
        return $r;
    }

    public function down(): void
    {
        if (Schema::hasColumn('services', 'results')) {
            Schema::table('services', function (Blueprint $table) {
                $table->dropColumn('results');
            });
        }
    }
};
