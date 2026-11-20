<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Round 2, Task 11: About page title/description (Czech Republic is not sold).
 * Only rows that still mention Czechia are changed, so manual admin edits are kept.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('seo_settings')) {
            return;
        }
        $row = DB::table('seo_settings')->where('id', 3)->first();
        if (! $row) {
            return;
        }
        $title = (string) ($row->seo_title ?? '');
        $desc = (string) ($row->seo_description ?? '');
        $update = [];
        if ($title === '' || mb_stripos($title, 'Чехи') !== false) {
            $update['seo_title'] = 'О компании GoSanatorium — бронирование санаториев Азербайджана и Грузии';
        }
        if ($desc === '' || mb_stripos($desc, 'Чехи') !== false) {
            $update['seo_description'] = 'GoSanatorium — сервис подбора и бронирования санаториев Азербайджана и Грузии: без предоплаты, бесплатная консультация врача, трансфер и поддержка на каждом шаге.';
        }
        if ($update) {
            DB::table('seo_settings')->where('id', 3)->update($update);
        }
    }

    public function down(): void
    {
    }
};
