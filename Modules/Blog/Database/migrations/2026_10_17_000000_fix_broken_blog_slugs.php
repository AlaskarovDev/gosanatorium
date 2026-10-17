<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $blogs = DB::table('blogs')->get();

        foreach ($blogs as $blog) {
            if (!str_starts_with($blog->slug, '-') && !str_ends_with($blog->slug, '-')) {
                continue;
            }

            $title = DB::table('blog_translations')
                ->where('blog_id', $blog->id)
                ->orderByRaw("lang_code = 'ru' desc")
                ->value('title');

            $slug = Str::slug((string) $title);
            if ($slug === '') {
                continue;
            }

            $base = $slug;
            $i = 2;
            while (DB::table('blogs')->where('slug', $slug)->where('id', '!=', $blog->id)->exists()) {
                $slug = $base . '-' . $i++;
            }

            DB::table('blogs')->where('id', $blog->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
    }
};
