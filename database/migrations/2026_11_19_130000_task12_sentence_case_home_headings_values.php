<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Task 12 (follow-up): the same headings are also stored in frontends.data_values
 * and in translations of other language codes — fix them there too.
 * Only values that still equal the old Title Case text are changed, so edits made
 * in the admin panel in the meantime are kept.
 */
return new class extends Migration
{
    private array $map = [
        'Лучшие Санатории И Курорты Мира' => 'Лучшие санатории Азербайджана и Грузии',
        'Полезные Статьи О Здоровье' => 'Полезные статьи о здоровье',
        'Популярные Направления' => 'Популярные направления',
        'Часто Задаваемые Вопросы' => 'Часто задаваемые вопросы',
    ];

    public function up(): void
    {
        $this->apply($this->map);
    }

    public function down(): void
    {
        $this->apply(array_flip($this->map));
    }

    private function norm(string $v): string
    {
        $v = html_entity_decode(strip_tags($v), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim(preg_replace('/\s+/u', ' ', $v));
    }

    private function fix($values, array $map, bool &$changed)
    {
        if (! is_array($values)) {
            return $values;
        }
        foreach ($values as $k => $v) {
            if (is_string($v) && isset($map[$this->norm($v)])) {
                $values[$k] = $map[$this->norm($v)];
                $changed = true;
            }
        }

        return $values;
    }

    private function apply(array $map): void
    {
        foreach (DB::table('frontends')->get(['id', 'data_values', 'data_translations']) as $row) {
            $update = [];

            $changed = false;
            $values = $this->fix(json_decode((string) $row->data_values, true), $map, $changed);
            if ($changed) {
                $update['data_values'] = json_encode($values, JSON_UNESCAPED_UNICODE);
            }

            $changed = false;
            $tr = json_decode((string) $row->data_translations, true);
            if (is_array($tr)) {
                foreach ($tr as $i => $t) {
                    if (is_array($t) && isset($t['values'])) {
                        $tr[$i]['values'] = $this->fix($t['values'], $map, $changed);
                    }
                }
            }
            if ($changed) {
                $update['data_translations'] = json_encode($tr, JSON_UNESCAPED_UNICODE);
            }

            if ($update) {
                DB::table('frontends')->where('id', $row->id)->update($update);
            }
        }
    }
};
