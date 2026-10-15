<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

final class RoomMedia extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'room_media';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'room_id',
        'file_path',
        'media_type',
        'video_url',
        'caption',
        'alt_text',
        'is_thumbnail',
        'display_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_thumbnail' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get the room that this media belongs to.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Check if this media is an image.
     */
    public function isImage(): bool
    {
        return $this->media_type === 'image';
    }

    /**
     * Check if this media is a video.
     */
    public function isVideo(): bool
    {
        return $this->media_type === 'video';
    }

    /**
     * Get the full URL for this media.
     */
    public function getUrlAttribute(): string
    {
        if ($this->isVideo() && $this->video_url) {
            return $this->video_url;
        }

        return asset($this->file_path);
    }
}