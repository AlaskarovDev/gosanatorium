<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('consultants')->where('id', 1)->update([
            'role' => 'Эксперт по санаториям Азербайджана и Грузии',
            'button_url' => 'https://wa.me/994519158894',
            'button_text' => 'Написать в WhatsApp →',
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
    }
};
