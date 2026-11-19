<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Task 12: Russian sentence case for home page section headings that are stored
 * in the CMS (frontends.data_translations, language "ru").
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

    private function apply(array $map): void
    {
        foreach (DB::table('frontends')->whereNotNull('data_translations')->get(['id', 'data_translations']) as $row) {
            $tr = json_decode($row->data_translations, true);
            if (! is_array($tr)) {
                continue;
            }
            $changed = false;
            foreach ($tr as $i => $t) {
                if (($t['language_code'] ?? null) !== 'ru' || ! is_array($t['values'] ?? null)) {
                    continue;
                }
                foreach ($t['values'] as $k => $v) {
                    if (is_string($v) && isset($map[$this->norm($v)])) {
                        $tr[$i]['values'][$k] = $map[$this->norm($v)];
                        $changed = true;
                    }
                }
            }
            if ($changed) {
                DB::table('frontends')->where('id', $row->id)
                    ->update(['data_translations' => json_encode($tr, JSON_UNESCAPED_UNICODE)]);
            }
        }
    }
};
