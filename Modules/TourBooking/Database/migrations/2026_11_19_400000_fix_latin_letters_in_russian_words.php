<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Task 8: Latin look-alike letters inside Russian words
 * («Cанаторий» with a Latin C, «Делюкc» with a Latin c, ...).
 *
 * Only words that mix Cyrillic and Latin letters are touched, and only when every
 * Latin letter in the word has a Cyrillic twin (C→С, c→с, a→а, e→е, o→о, p→р ...).
 * Every changed value is saved in latin_letter_fixes first, so down() restores it.
 */
return new class extends Migration
{
    private const LOG_TABLE = 'latin_letter_fixes';

    /** Latin letter => Cyrillic look-alike. */
    private const MAP = [
        'A' => 'А', 'B' => 'В', 'C' => 'С', 'E' => 'Е', 'H' => 'Н', 'K' => 'К', 'M' => 'М',
        'O' => 'О', 'P' => 'Р', 'T' => 'Т', 'X' => 'Х', 'Y' => 'У',
        'a' => 'а', 'c' => 'с', 'e' => 'е', 'o' => 'о', 'p' => 'р', 'x' => 'х', 'y' => 'у',
    ];

    /** Tables never touched: system tables and customer / personal data. */
    private const SKIP_TABLES = [
        'migrations', 'jobs', 'failed_jobs', 'job_batches', 'sessions', 'cache', 'cache_locks',
        'password_resets', 'password_reset_tokens', 'personal_access_tokens', 'users', 'admins',
        'bookings', 'orders', 'order_items', 'payments', 'transactions', 'contact_messages',
        'consultant_contacts', 'newsletters', 'subscribers', 'carts', 'wishlists', 'settings',
        'latin_letter_fixes',
    ];

    public function up(): void
    {
        if (!Schema::hasTable(self::LOG_TABLE)) {
            Schema::create(self::LOG_TABLE, function (Blueprint $table) {
                $table->id();
                $table->string('table_name');
                $table->string('column_name');
                $table->unsignedBigInteger('row_id');
                $table->longText('old_value')->nullable();
                $table->timestamp('created_at')->nullable();
            });
        }

        $database = DB::getDatabaseName();
        $columns = DB::select(
            "SELECT TABLE_NAME AS t, COLUMN_NAME AS c FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = ? AND DATA_TYPE IN ('char', 'varchar', 'tinytext', 'text', 'mediumtext', 'longtext')",
            [$database]
        );

        $byTable = [];
        foreach ($columns as $col) {
            $byTable[$col->t][] = $col->c;
        }

        foreach ($byTable as $table => $cols) {
            if (in_array($table, self::SKIP_TABLES, true) || !Schema::hasColumn($table, 'id')) {
                continue;
            }
            foreach ($cols as $column) {
                $this->fixColumn($table, $column);
            }
        }
    }

    private function fixColumn(string $table, string $column): void
    {
        $query = DB::table($table)->select(['id', $column])->whereNotNull($column);

        try {
            $rows = (clone $query)
                ->whereRaw("`{$column}` REGEXP '[А-яЁё][A-Za-z]|[A-Za-z][А-яЁё]'")
                ->get();
        } catch (\Throwable $e) {
            // REGEXP without Unicode support: check the rows in PHP.
            $rows = collect();
            $query->orderBy('id')->chunk(500, function ($chunk) use (&$rows) {
                $rows = $rows->merge($chunk);
            });
        }

        foreach ($rows as $row) {
            $old = $row->{$column};
            if (!is_string($old) || $old === '') {
                continue;
            }
            $new = $this->fixText($old);
            if ($new === $old) {
                continue;
            }

            DB::table(self::LOG_TABLE)->insert([
                'table_name' => $table,
                'column_name' => $column,
                'row_id' => $row->id,
                'old_value' => $old,
                'created_at' => now(),
            ]);
            DB::table($table)->where('id', $row->id)->update([$column => $new]);
        }
    }

    private function fixText(string $text): string
    {
        $latin = implode('', array_keys(self::MAP));

        return preg_replace_callback('/[A-Za-zА-Яа-яЁё]+/u', function ($m) use ($latin) {
            $word = $m[0];
            if (!preg_match('/[А-Яа-яЁё]/u', $word) || !preg_match('/[A-Za-z]/', $word)) {
                return $word;
            }
            // Only when every Latin letter has a Cyrillic twin and Cyrillic dominates.
            if (preg_match('/[^' . $latin . 'А-Яа-яЁё]/u', $word)) {
                return $word;
            }
            preg_match_all('/[A-Za-z]/', $word, $lat);
            preg_match_all('/[А-Яа-яЁё]/u', $word, $cyr);
            if (count($cyr[0]) <= count($lat[0])) {
                return $word;
            }

            return strtr($word, self::MAP);
        }, $text) ?? $text;
    }

    public function down(): void
    {
        if (!Schema::hasTable(self::LOG_TABLE)) {
            return;
        }

        DB::table(self::LOG_TABLE)->orderByDesc('id')->get()->each(function ($fix) {
            if (Schema::hasTable($fix->table_name)) {
                DB::table($fix->table_name)->where('id', $fix->row_id)
                    ->update([$fix->column_name => $fix->old_value]);
            }
        });

        Schema::drop(self::LOG_TABLE);
    }
};
