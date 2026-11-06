<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('services')) {
            return;
        }

        $known = [
            'room_count' => 0,
            'adult_count' => 1,
            'children_count' => 0,
            'child_count' => 0,
            'min_nights' => 1,
            'max_nights' => 30,
        ];

        $columns = DB::select(
            "SELECT COLUMN_NAME, COLUMN_TYPE, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_NAME = 'services' AND TABLE_SCHEMA = DATABASE()
             AND IS_NULLABLE = 'NO' AND COLUMN_DEFAULT IS NULL AND EXTRA NOT LIKE '%auto_increment%'"
        );

        foreach ($columns as $col) {
            $name = $col->COLUMN_NAME;
            $type = strtolower($col->DATA_TYPE);
            if (! in_array($type, ['int', 'tinyint', 'smallint', 'mediumint', 'bigint', 'decimal', 'float', 'double'], true)) {
                continue; // title, slug, service_type_id etc. must stay required
            }
            if ($name === 'service_type_id') {
                continue;
            }
            $default = $known[$name] ?? 0;
            DB::statement("ALTER TABLE `services` MODIFY COLUMN `{$name}` {$col->COLUMN_TYPE} NOT NULL DEFAULT {$default}");
        }
    }

    public function down(): void
    {
        // defaults are harmless; nothing to revert
    }
};
