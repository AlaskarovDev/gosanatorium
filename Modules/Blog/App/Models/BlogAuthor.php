<?php

namespace Modules\Blog\App\Models;

use Illuminate\Database\Eloquent\Model;

/** Task 20: blog author or medical expert (shown in the post header). */
class BlogAuthor extends Model
{
    protected $table = 'blog_authors';

    protected $fillable = ['name', 'photo', 'role', 'specialty', 'bio', 'profile_url', 'is_medical', 'is_default', 'status'];

    protected $casts = ['is_medical' => 'boolean', 'is_default' => 'boolean', 'status' => 'boolean'];

    public static function defaultAuthor(): ?self
    {
        static $cache = false;
        if ($cache === false) {
            $cache = static::where('status', 1)->where('is_default', 1)->first()
                ?? static::where('status', 1)->where('is_medical', 0)->orderBy('id')->first();
        }

        return $cache;
    }

    /** Initials for a photo-less avatar: «Рамиль Муртузаев» → «РМ». */
    public function getInitialsAttribute(): string
    {
        $parts = preg_split('/\s+/u', trim((string) $this->name));
        $letters = array_map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)), array_slice(array_filter($parts), 0, 2));

        return implode('', $letters);
    }
}
