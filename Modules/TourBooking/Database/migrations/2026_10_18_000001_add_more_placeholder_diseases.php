<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('diseases')) {
            return;
        }

        $seed = [
            ['bolezni-nervnoj-sistemy', 'Болезни нервной системы', 'Sinir sistemi xəstəlikləri', 'Nervous System Diseases', 4],
            ['bolezni-organov-pishchevareniya', 'Болезни органов пищеварения', 'Həzm sistemi xəstəlikləri', 'Digestive System Diseases', 5],
            ['bolezni-oporno-dvigatel-nogo', 'Болезни опорно-двигательного аппарата', 'Dayaq-hərəkət sistemi xəstəlikləri', 'Musculoskeletal Diseases', 6],
        ];

        foreach ($seed as [$slug, $ru, $az, $en, $order]) {
            if (DB::table('diseases')->where('slug', $slug)->exists()) {
                continue;
            }
            DB::table('diseases')->insert([
                'slug' => $slug,
                'name_ru' => $ru,
                'name_az' => $az,
                'name_en' => $en,
                'sort_order' => $order,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('diseases')) {
            DB::table('diseases')->whereIn('slug', [
                'bolezni-nervnoj-sistemy',
                'bolezni-organov-pishchevareniya',
                'bolezni-oporno-dvigatel-nogo',
            ])->delete();
        }
    }
};
