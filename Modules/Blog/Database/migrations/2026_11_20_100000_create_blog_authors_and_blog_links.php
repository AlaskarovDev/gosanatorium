<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Task 20: blog authors / medical experts, and per-post author, expert and linked sanatorium.
 * The default author is created from the active consultant (Ramil) — name, role and photo as entered in admin.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('blog_authors')) {
            Schema::create('blog_authors', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('photo')->nullable();
                $table->string('role')->nullable();
                $table->string('specialty')->nullable();
                $table->text('bio')->nullable();
                $table->string('profile_url')->nullable();
                $table->boolean('is_medical')->default(false);
                $table->boolean('is_default')->default(false);
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
        }

        Schema::table('blogs', function (Blueprint $table) {
            if (!Schema::hasColumn('blogs', 'author_id')) {
                $table->unsignedBigInteger('author_id')->nullable()->after('admin_id');
            }
            if (!Schema::hasColumn('blogs', 'expert_id')) {
                $table->unsignedBigInteger('expert_id')->nullable()->after('author_id');
            }
            if (!Schema::hasColumn('blogs', 'service_id')) {
                $table->unsignedBigInteger('service_id')->nullable()->after('expert_id');
            }
        });

        if (DB::table('blog_authors')->count() === 0 && Schema::hasTable('consultants')) {
            $c = DB::table('consultants')->where('is_active', 1)->orderBy('id')->first();
            if ($c && $c->name) {
                // «Муртузаев Рамиль» → «Рамиль Муртузаев»
                $parts = preg_split('/\s+/u', trim($c->name));
                $name = count($parts) === 2 ? $parts[1] . ' ' . $parts[0] : trim($c->name);
                DB::table('blog_authors')->insert([
                    'name'       => $name,
                    'photo'      => $c->photo ? 'images/consultants/' . $c->photo : null,
                    'role'       => $c->role,
                    'is_medical' => false,
                    'is_default' => true,
                    'status'     => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            foreach (['author_id', 'expert_id', 'service_id'] as $col) {
                if (Schema::hasColumn('blogs', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
        Schema::dropIfExists('blog_authors');
    }
};
