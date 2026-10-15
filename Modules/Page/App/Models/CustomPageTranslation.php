<?php

namespace Modules\Page\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Page\Database\factories\CustomPageTranslationFactory;

class CustomPageTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'lang_code',
        'custom_page_id',
        'page_name',
        'description',
        'banner_title',
        'banner_description',
        'form_title',
        'form_description',
        'feature1_title',
        'feature1_description',
        'feature2_title',
        'feature2_description',
        'feature3_title',
        'feature3_description',
        'feature4_title',
        'feature4_description',
    ];
}