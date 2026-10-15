<?php

namespace Modules\Page\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Page\App\Models\CustomPageTranslation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Page\Database\factories\CustomPageFactory;

class CustomPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'status',
        'banner_image',
        'video_url',
        'video_thumbnail',
        'doctor_name',
        'doctor_image',
        'show_consultation_form',
        'show_video_banner',
        'feature1_icon',
        'feature2_icon',
        'feature3_icon',
        'feature4_icon',
    ];

    protected $hidden = ['front_translate'];

    protected $appends = [
        'page_name', 'description',
        'banner_title', 'banner_description',
        'form_title', 'form_description',
        'feature1_title', 'feature1_description',
        'feature2_title', 'feature2_description',
        'feature3_title', 'feature3_description',
        'feature4_title', 'feature4_description',
    ];

    public function translate(){
        return $this->belongsTo(CustomPageTranslation::class, 'id', 'custom_page_id')->where('lang_code', admin_lang());
    }

    public function front_translate(){
        return $this->belongsTo(CustomPageTranslation::class, 'id', 'custom_page_id')->where('lang_code', front_lang());
    }

    public function getPageNameAttribute(){
        return $this->front_translate?->page_name;
    }

    public function getDescriptionAttribute(){
        return $this->front_translate?->description;
    }

    public function getBannerTitleAttribute(){
        return $this->front_translate?->banner_title;
    }

    public function getBannerDescriptionAttribute(){
        return $this->front_translate?->banner_description;
    }

    public function getFormTitleAttribute(){
        return $this->front_translate?->form_title;
    }

    public function getFormDescriptionAttribute(){
        return $this->front_translate?->form_description;
    }

    // Feature 1
    public function getFeature1TitleAttribute(){
        return $this->front_translate?->feature1_title;
    }

    public function getFeature1DescriptionAttribute(){
        return $this->front_translate?->feature1_description;
    }

    // Feature 2
    public function getFeature2TitleAttribute(){
        return $this->front_translate?->feature2_title;
    }

    public function getFeature2DescriptionAttribute(){
        return $this->front_translate?->feature2_description;
    }

    // Feature 3
    public function getFeature3TitleAttribute(){
        return $this->front_translate?->feature3_title;
    }

    public function getFeature3DescriptionAttribute(){
        return $this->front_translate?->feature3_description;
    }

    // Feature 4
    public function getFeature4TitleAttribute(){
        return $this->front_translate?->feature4_title;
    }

    public function getFeature4DescriptionAttribute(){
        return $this->front_translate?->feature4_description;
    }

    public function consultationRequests()
    {
        return $this->hasMany(ConsultationRequest::class);
    }
    
    
    public function videos()
{
    return $this->hasMany(\Modules\Page\App\Models\CustomPageVideo::class)->orderBy('sort_order');
}
}