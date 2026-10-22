<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('consultants')) {
            Schema::create('consultants', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('role');
                $table->string('phone', 50)->nullable();
                $table->string('email')->nullable();
                $table->string('photo')->nullable();
                $table->string('whatsapp', 50)->nullable();
                $table->string('button_text')->default('Получить консультацию →');
                $table->string('button_url')->default('/ru/custom-page/consultant');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (DB::table('consultants')->count() === 0) {
            DB::table('consultants')->insert([
                'name' => 'Муртузаев Рамиль',
                'role' => 'Эксперт по санаториям Азербайджана и Грузии',
                'phone' => '+994 51 915 88 94',
                'email' => 'info@gosanatorium.com',
                'whatsapp' => '994519158894',
                'photo' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('consultants');
    }
};
