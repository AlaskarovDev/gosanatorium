<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Park Naftalan: one official price per paid procedure everywhere (descriptions,
 * included/extra text blocks and extra charges), and no duplicate extra-charge rows.
 */
return new class extends Migration
{
    /** [regex for the procedure name, official price in AZN, extra_charges name keyword] */
    private function prices(): array
    {
        return [
            ['Гидроколонотерапия', 25],
            ['Озонотерапия', 10],
            ['Иглотерапия', 5],
            ['Массаж\s*10\s*мин(?:ут\w*)?', 5],
            ['Массаж\s*20\s*мин(?:ут\w*)?', 10],
            ['Парафин(?:\s*\(\s*1\s*сеанс\w*\s*\))?', 5],
            ['Световая\s+ванна', 5],
            ['Йодо[-\s]?бромная\s+ванна', 8],
            ['УВЧ', 6],
            ['Подводное\s+вытяжение', 15],
            ['Мануальная\s+терапия', 20],
            ['Электротерапия', 5],
            ['Фонофорез', 5],
            ['Амплипульс', 5],
            ['Дарсонваль', 5],
            ['Инфракрасное\s+облучение', 5],
            ['Биомассаж', 25],
            ['Биоультра\s+массаж\s+лица\s*10\s*мин(?:ут\w*)?', 10],
            ['Биоультра\s+массаж\s+лица\s*20\s*мин(?:ут\w*)?', 20],
            ['Биоэнергетический\s+массаж\s+тела', 25],
            ['Хиджама', 6],
            ['Нафталановая\s+баня', 8],
        ];
    }

    private function fixText(string $text): string
    {
        foreach ($this->prices() as [$name, $price]) {
            $re = '/(' . $name . ')([^0-9<>\n]{0,40}?)(\d+(?:[.,]\d+)?)(\s*(?:AZN|azn|Azn|₼|манат\w*|man\.?))/iu';
            $text = preg_replace($re, '${1}${2}' . $price . '${4}', $text) ?? $text;
        }
        return $text;
    }

    private function fixValue($value)
    {
        if (is_string($value)) {
            return $this->fixText($value);
        }
        if (is_array($value)) {
            return array_map(fn ($v) => $this->fixValue($v), $value);
        }
        return $value;
    }

    private function fixColumn(?string $raw): ?string
    {
        if ($raw === null || $raw === '') {
            return $raw;
        }
        $decoded = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE && (is_array($decoded) || is_string($decoded))) {
            return json_encode($this->fixValue($decoded), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        return $this->fixText($raw);
    }

    public function up(): void
    {
        if (!Schema::hasTable('services')) {
            return;
        }

        $ids = DB::table('services')->where('slug', 'like', '%park-naftalan%')->pluck('id');
        if ($ids->isEmpty()) {
            return;
        }

        if (Schema::hasTable('service_translations')) {
            $cols = array_values(array_filter(
                ['description', 'short_description', 'included', 'excluded', 'facilities', 'rules', 'safety', 'cancellation_policy'],
                fn ($c) => Schema::hasColumn('service_translations', $c)
            ));
            foreach (DB::table('service_translations')->whereIn('service_id', $ids)->get() as $row) {
                $update = [];
                foreach ($cols as $c) {
                    $new = $this->fixColumn($row->{$c});
                    if ($new !== $row->{$c}) {
                        $update[$c] = $new;
                    }
                }
                if ($update) {
                    DB::table('service_translations')->where('id', $row->id)->update($update);
                }
            }
        }

        if (Schema::hasTable('extra_charges')) {
            $seen = [];
            foreach (DB::table('extra_charges')->whereIn('service_id', $ids)->orderBy('id')->get() as $row) {
                $matched = null;
                foreach ($this->prices() as [$name, $price]) {
                    if (preg_match('/^\s*(?:' . $name . ')\s*$/iu', (string) $row->name)) {
                        $matched = $price;
                        break;
                    }
                }
                if ($matched === null) {
                    continue;
                }
                $key = $row->service_id . '|' . mb_strtolower(preg_replace('/\s+/u', ' ', trim($row->name)));
                if (isset($seen[$key])) {
                    DB::table('extra_charges')->where('id', $row->id)->delete(); // duplicate row
                    continue;
                }
                $seen[$key] = true;
                DB::table('extra_charges')->where('id', $row->id)->update(['price' => $matched]);
            }
        }
    }

    public function down(): void
    {
        // Data fix; not reversible.
    }
};
