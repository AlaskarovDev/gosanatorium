<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * ServiceRoomMedia Model
 * 
 * Otaq şəkilləri və videoları üçün media model
 * 
 * @property int $id
 * @property int $service_room_id
 * @property string $file_path
 * @property string $media_type (image, video)
 * @property bool $is_thumbnail
 * @property int $display_order
 */
class ServiceRoomMedia extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'service_room_media';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'service_room_id',
        'file_path',
        'media_type',
        'is_thumbnail',
        'display_order',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_thumbnail' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get the room that owns the media.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(ServiceRoom::class, 'service_room_id');
    }

    /**
     * Scope a query to only include thumbnails.
     */
    public function scopeThumbnail($query)
    {
        return $query->where('is_thumbnail', true);
    }

    /**
     * Scope a query to only include images.
     */
    public function scopeImages($query)
    {
        return $query->where('media_type', 'image');
    }

    /**
     * Scope a query to only include videos.
     */
    public function scopeVideos($query)
    {
        return $query->where('media_type', 'video');
    }

    /**
     * Scope a query to only include gallery images (non-thumbnails).
     */
    public function scopeGallery($query)
    {
        return $query->where('media_type', 'image')
                     ->where('is_thumbnail', false)
                     ->orderBy('display_order');
    }

    /**
     * Get the full URL to the media file.
     */
    public function getUrlAttribute(): string
    {
        if ($this->media_type === 'video') {
            return $this->file_path; // Video URL-ləri olduğu kimi qalır
        }
        
        return asset($this->file_path);
    }

    /**
     * Check if this is a video.
     */
    public function isVideo(): bool
    {
        return $this->media_type === 'video';
    }

    /**
     * Check if this is an image.
     */
    public function isImage(): bool
    {
        return $this->media_type === 'image';
    }
}