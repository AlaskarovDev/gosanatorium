<?php

namespace Modules\Blog\App\Models;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Modules\Blog\Database\factories\BlogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected $hidden = ['front_translate'];

    protected $appends = ['title', 'description', 'seo_title', 'seo_description', 'total_comment', 'short_description'];

    protected static function newFactory(): BlogFactory{}

    public function author(){
        return $this->belongsTo(Admin::class, 'admin_id', 'id')->select('id', 'name', 'image', 'about_me');
    }

    /** Task 20: post author (blog_authors); falls back to the default author. */
    public function writer(){
        return $this->belongsTo(BlogAuthor::class, 'author_id');
    }

    /** Task 20: «Проверено врачом» */
    public function expert(){
        return $this->belongsTo(BlogAuthor::class, 'expert_id');
    }

    /** Task 20: sanatorium the post is about (CTA card). */
    public function linkedService(){
        return $this->belongsTo(\Modules\TourBooking\App\Models\Service::class, 'service_id');
    }

    public function displayAuthor(): ?BlogAuthor
    {
        $a = $this->writer;

        return ($a && $a->status) ? $a : BlogAuthor::defaultAuthor();
    }

    /** Task 20: reading time = words / 180, rounded up (Cyrillic-aware). */
    public function getReadingMinutesAttribute(): int
    {
        $text = html_entity_decode(strip_tags((string) $this->description), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $words = preg_match_all('/[\p{L}\p{N}]+/u', $text);

        return max(1, (int) ceil($words / 180));
    }

    public function category(){
        return $this->belongsTo(BlogCategory::class, 'blog_category_id', 'id');
    }

    public function translate(){
        return $this->belongsTo(BlogTranslation::class, 'id', 'blog_id')->where('lang_code', admin_lang());
    }

    public function front_translate(){
        return $this->belongsTo(BlogTranslation::class, 'id', 'blog_id')->where('lang_code', front_lang());
    }

    public function comments(){
        return $this->hasMany(BlogComment::class)->where('status', 1);
    }

    public function getTotalCommentAttribute(){
        return $this->comments->count();
    }

    public function getTitleAttribute(){
        return $this->front_translate?->title;
    }

    public function getDescriptionAttribute(){
        return $this->front_translate?->description;
    }

    public function getShortDescriptionAttribute(){
        return $this->front_translate?->short_description;
    }



    public function getSeoTitleAttribute(){
        return $this->front_translate?->seo_title;
    }

    public function getSeoDescriptionAttribute(){
        return $this->front_translate?->seo_description;
    }







}
