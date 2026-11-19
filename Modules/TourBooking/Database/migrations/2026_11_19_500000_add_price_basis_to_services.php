<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Task 10 — «от $X» price.
 *  - services.price_basis: 'person' («за ночь с человека») or 'room' («за номер (2 гостя) за ночь»).
 *  - Fixed prices in the Russian short description / SEO description («От $59/ночь») become the
 *    {min_price} placeholder, so the text always shows the calculated price.
 *    The old texts are saved to storage/app/backups/task10_service_texts.json.
 */
return new class extends Migration
{
    private const PATTERN = '/([Оо]т)\s*\$\s?\d+(?:[.,]\d{1,2})?/u';

    public function up(): void
    {
        if (! Schema::hasTable('services')) {
            return;
        }
        if (! Schema::hasColumn('services', 'price_basis')) {
            Schema::table('services', function (Blueprint $table) {
                $table->string('price_basis', 20)->nullable();
            });
        }

        if (! Schema::hasTable('service_translations')) {
            return;
        }
        $backup = [];
        $rows = DB::table('service_translations')->where('locale', 'ru')->get(['id', 'service_id', 'short_description', 'seo_description']);
        foreach ($rows as $row) {
            $update = [];
            foreach (['short_description', 'seo_description'] as $col) {
                $text = (string) ($row->{$col} ?? '');
                if ($text === '' || ! preg_match(self::PATTERN, $text)) {
                    continue;
                }
                $update[$col] = preg_replace(self::PATTERN, '$1 {min_price}', $text);
                $backup[] = ['id' => $row->id, 'service_id' => $row->service_id, 'column' => $col, 'text' => $text];
            }
            if ($update) {
                DB::table('service_translations')->where('id', $row->id)->update($update);
            }
        }

        if ($backup) {
            $dir = storage_path('app/backups');
            if (! is_dir($dir)) {
                @mkdir($dir, 0775, true);
            }
            @file_put_contents($dir . '/task10_service_texts.json', json_encode($backup, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
    }

    public function down(): void
    {
        $file = storage_path('app/backups/task10_service_texts.json');
        if (is_file($file)) {
            foreach ((array) json_decode((string) file_get_contents($file), true) as $row) {
                DB::table('service_translations')->where('id', $row['id'])->update([$row['column'] => $row['text']]);
            }
        }
        if (Schema::hasColumn('services', 'price_basis')) {
            Schema::table('services', function (Blueprint $table) {
                $table->dropColumn('price_basis');
            });
        }
    }
};
