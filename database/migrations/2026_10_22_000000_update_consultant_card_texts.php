<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('consultants')) {
            return;
        }

        DB::table('consultants')
            ->where('name', 'Муртузаев Рамил')
            ->update(['name' => 'Муртузаев Рамиль']);

        DB::table('consultants')
            ->where('role', 'Эксперт по санаториям Нафталана')
            ->update(['role' => 'Эксперт по санаториям Азербайджана и Грузии']);

        DB::table('consultants')
            ->where('button_text', 'like', 'Получить консультацию%')
            ->update(['button_url' => 'https://wa.me/994519158894']);
    }

    public function down(): void
    {
    }
};
