<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoMeta extends Model
{
    protected $table = 'seo_meta';

    protected $fillable = [
        'page_key', 'seo_title', 'seo_description', 'seo_keywords',
        'og_title', 'og_description', 'og_image', 'canonical_url', 'robots',
    ];
}
