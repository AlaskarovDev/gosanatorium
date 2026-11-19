<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Task 7: one slug list for treatment directions
 * (sustavy, kozha, nevrologiya, ginekologiya, urologiya, zhkt, diabet),
 * a separate gynecology direction and an H1 for every filtered list.
 */
return new class extends Migration
{
    /** slug => [h1_ru, h1_en, h1_az] */
    private const H1 = [
        'sustavy' => ['Санатории для лечения суставов', 'Sanatoriums for joint treatment', 'Oynaqların müalicəsi üçün sanatoriyalar'],
        'kozha' => ['Санатории для лечения кожных заболеваний', 'Sanatoriums for skin diseases', 'Dəri xəstəliklərinin müalicəsi üçün sanatoriyalar'],
        'nevrologiya' => ['Санатории для лечения неврологических заболеваний', 'Sanatoriums for neurological diseases', 'Nevroloji xəstəliklərin müalicəsi üçün sanatoriyalar'],
        'ginekologiya' => ['Санатории для лечения гинекологических заболеваний', 'Sanatoriums for gynecological diseases', 'Ginekoloji xəstəliklərin müalicəsi üçün sanatoriyalar'],
        'urologiya' => ['Санатории для лечения урологических заболеваний', 'Sanatoriums for urological diseases', 'Uroloji xəstəliklərin müalicəsi üçün sanatoriyalar'],
        'zhkt' => ['Санатории для лечения заболеваний ЖКТ', 'Sanatoriums for digestive diseases', 'Mədə-bağırsaq xəstəliklərinin müalicəsi üçün sanatoriyalar'],
        'diabet' => ['Санатории для лечения диабета и нарушений обмена веществ', 'Sanatoriums for diabetes and metabolic disorders', 'Diabet və maddələr mübadiləsi pozğunluqlarının müalicəsi üçün sanatoriyalar'],
        'kardio' => ['Санатории для лечения сердечно-сосудистых заболеваний', 'Sanatoriums for cardiovascular diseases', 'Ürək-damar xəstəliklərinin müalicəsi üçün sanatoriyalar'],
        'lor' => ['Санатории для лечения ЛОР-заболеваний', 'Sanatoriums for ENT diseases', 'LOR xəstəliklərinin müalicəsi üçün sanatoriyalar'],
        'dyhanie' => ['Санатории для лечения органов дыхания', 'Sanatoriums for respiratory diseases', 'Tənəffüs yollarının müalicəsi üçün sanatoriyalar'],
        'pochki' => ['Санатории для лечения заболеваний почек', 'Sanatoriums for kidney diseases', 'Böyrək xəstəliklərinin müalicəsi üçün sanatoriyalar'],
        'allergiya' => ['Санатории для лечения аллергии', 'Sanatoriums for allergy treatment', 'Allergiyanın müalicəsi üçün sanatoriyalar'],
        'oftalmologiya' => ['Санатории для лечения глазных заболеваний', 'Sanatoriums for eye diseases', 'Göz xəstəliklərinin müalicəsi üçün sanatoriyalar'],
    ];

    /** The seven main directions first, in menu order. */
    private const ORDER = ['sustavy', 'kozha', 'nevrologiya', 'ginekologiya', 'urologiya', 'zhkt', 'diabet'];

    public function up(): void
    {
        if (!Schema::hasTable('diseases')) {
            return;
        }

        Schema::table('diseases', function (Blueprint $table) {
            foreach (['ru', 'az', 'en'] as $code) {
                if (!Schema::hasColumn('diseases', 'h1_' . $code)) {
                    $table->string('h1_' . $code)->nullable()->after('name_' . $code);
                }
            }
        });

        // psoriaz -> kozha (the old slug is 301-redirected in the controller).
        if (!DB::table('diseases')->where('slug', 'kozha')->exists()) {
            DB::table('diseases')->where('slug', 'psoriaz')->update(['slug' => 'kozha', 'updated_at' => now()]);
        }

        // urologiya used to be «Урология / гинекология»: split gynecology into its own direction.
        $uro = DB::table('diseases')->where('slug', 'urologiya')->first();
        if ($uro && !DB::table('diseases')->where('slug', 'ginekologiya')->exists()) {
            $gynId = DB::table('diseases')->insertGetId([
                'slug' => 'ginekologiya',
                'name_ru' => 'Гинекология',
                'name_az' => 'Ginekologiya',
                'name_en' => 'Gynecology',
                'image' => $uro->image,
                'description_ru' => 'Лечение хронических воспалительных заболеваний, нарушений цикла и бесплодия: нафталановые и грязевые процедуры, минеральные ванны, физиотерапия под контролем гинеколога.',
                'description_az' => null,
                'description_en' => 'Treatment of chronic inflammatory diseases, cycle disorders and infertility: naftalan and mud procedures, mineral baths and physiotherapy supervised by a gynecologist.',
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            // Start with the sanatoriums of the former combined direction; the admin adjusts the list.
            foreach (DB::table('disease_sanatorium')->where('disease_id', $uro->id)->pluck('sanatorium_id') as $sid) {
                DB::table('disease_sanatorium')->insert([
                    'disease_id' => $gynId,
                    'sanatorium_id' => $sid,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        if ($uro && $uro->name_ru === 'Урология / гинекология') {
            DB::table('diseases')->where('id', $uro->id)->update([
                'name_ru' => 'Урология',
                'name_az' => 'Urologiya',
                'name_en' => 'Urology',
                'updated_at' => now(),
            ]);
        }

        foreach (self::H1 as $slug => [$ru, $en, $az]) {
            DB::table('diseases')->where('slug', $slug)->whereNull('h1_ru')->update(['h1_ru' => $ru, 'h1_en' => $en, 'h1_az' => $az]);
        }

        foreach (self::ORDER as $i => $slug) {
            DB::table('diseases')->where('slug', $slug)->update(['sort_order' => $i + 1]);
        }
        // Other directions after the main seven, keeping their relative order.
        DB::table('diseases')->whereNotIn('slug', self::ORDER)->where('sort_order', '<', 100)
            ->update(['sort_order' => DB::raw('sort_order + 100')]);
    }

    public function down(): void
    {
        Schema::table('diseases', function (Blueprint $table) {
            foreach (['ru', 'az', 'en'] as $code) {
                if (Schema::hasColumn('diseases', 'h1_' . $code)) {
                    $table->dropColumn('h1_' . $code);
                }
            }
        });
    }
};
