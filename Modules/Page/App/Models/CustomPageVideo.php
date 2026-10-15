<?php

namespace Modules\Page\App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomPageVideo extends Model
{
    protected $fillable = [
        'custom_page_id',
        'title',
        'video_url',
        'thumbnail',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function customPage()
    {
        return $this->belongsTo(CustomPage::class);
    }

    /**
     * Get embed URL from video link (YouTube, Vimeo)
     */
    public function getEmbedUrlAttribute(): string
    {
        $url = $this->video_url;

        // YouTube
        if (str_contains($url, 'youtube.com/watch')) {
            $id = explode('v=', $url)[1] ?? '';
            $id = explode('&', $id)[0];
            return 'https://www.youtube.com/embed/' . $id;
        }
        if (str_contains($url, 'youtu.be/')) {
            $id = explode('youtu.be/', $url)[1] ?? '';
            $id = explode('?', $id)[0];
            return 'https://www.youtube.com/embed/' . $id;
        }

        // Vimeo
        if (str_contains($url, 'vimeo.com/')) {
            $id = explode('vimeo.com/', $url)[1] ?? '';
            $id = explode('?', $id)[0];
            return 'https://player.vimeo.com/video/' . $id;
        }

        return $url;
    }

    /**
     * Auto-generate YouTube thumbnail if none uploaded
     */
    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return asset($this->thumbnail);
        }

        // Try to get YouTube thumbnail
        $url = $this->video_url;
        $videoId = null;

        if (str_contains($url, 'youtube.com/watch')) {
            $videoId = explode('v=', $url)[1] ?? '';
            $videoId = explode('&', $videoId)[0];
        } elseif (str_contains($url, 'youtu.be/')) {
            $videoId = explode('youtu.be/', $url)[1] ?? '';
            $videoId = explode('?', $videoId)[0];
        }

        if ($videoId) {
            return 'https://img.youtube.com/vi/' . $videoId . '/hqdefault.jpg';
        }

        return asset('global/img/video-placeholder.png');
    }
}