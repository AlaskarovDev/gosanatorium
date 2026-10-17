<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('diseases')) {
            Schema::create('diseases', function (Blueprint $table) {
                $table->id();
                $table->string('slug')->unique();
                $table->string('name_ru');
                $table->string('name_az')->nullable();
                $table->string('name_en')->nullable();
                $table->string('image')->nullable();
                $table->text('description_ru')->nullable();
                $table->text('description_az')->nullable();
                $table->text('description_en')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Sanatoriums are stored in the existing "services" table.
        if (!Schema::hasTable('disease_sanatorium')) {
            Schema::create('disease_sanatorium', function (Blueprint $table) {
                $table->id();
                $table->foreignId('disease_id')->constrained('diseases')->onDelete('cascade');
                $table->foreignId('sanatorium_id')->constrained('services')->onDelete('cascade');
                $table->timestamps();

                $table->unique(['disease_id', 'sanatorium_id']);
            });
        }

        // Placeholder data (admin will replace it later)
        $seed = [
            ['psoriaz', 'Псориаз', 'Psoriaz', 'Psoriasis'],
            ['bolezni-sustavov', 'Болезни суставов', 'Oynaq xəstəlikləri', 'Joint Diseases'],
            ['bolezni-kozhi', 'Болезни кожи', 'Dəri xəstəlikləri', 'Skin Diseases'],
        ];

        foreach ($seed as $i => [$slug, $ru, $az, $en]) {
            if (DB::table('diseases')->where('slug', $slug)->exists()) {
                continue;
            }
            DB::table('diseases')->insert([
                'slug' => $slug,
                'name_ru' => $ru,
                'name_az' => $az,
                'name_en' => $en,
                'sort_order' => $i + 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('disease_sanatorium');
        Schema::dropIfExists('diseases');
    }
};
