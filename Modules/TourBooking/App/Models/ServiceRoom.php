<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\TourBooking\App\Models\Concerns\HasSeasonalPricing;

/**
 * ServiceRoom Model
 * 
 * Hotel/Accommodation otaqları üçün model
 * 
 * @property int $id
 * @property int $service_id
 * @property string $name
 * @property string|null $description
 * @property float $price_per_night
 * @property float|null $discount_price
 * @property int|null $max_guests
 * @property int|null $room_size
 * @property string|null $bed_type
 * @property int $quantity
 * @property bool $status
 * @property string|null $features (JSON)
 * @property string|null $thumbnail
 * @property string|null $video_url
 * @property int $display_order
 */
class ServiceRoom extends Model
{
    use HasFactory;
    use HasSeasonalPricing;

    /**
     * The table associated with the model.
     */
    protected $table = 'service_rooms';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'service_id',
        'name',
        'description',
        'price_per_night',
        'discount_price',
        'base_price_for',
        'max_guests',
        'room_size',
        'bed_type',
        'quantity',
        'low_stock_show',
        'low_stock_text',
        'status',
        'features',
        'thumbnail',
        'video_url',
        'display_order',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'price_per_night' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'base_price_for' => 'integer',
        'max_guests' => 'integer',
        'room_size' => 'integer',
        'quantity' => 'integer',
        'low_stock_show' => 'boolean',
        'status' => 'boolean',
        'features' => 'array',
        'display_order' => 'integer',
    ];

    /**
     * Get the service that owns the room.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Per-room extras (extra adult / extra child / optional add-ons).
     */
    public function extras(): HasMany
    {
        return $this->hasMany(RoomExtra::class, 'room_id')->orderBy('sort_order');
    }

    public function activeExtras(): HasMany
    {
        return $this->extras()->where('status', true);
    }

    /**
     * Today's effective per-night price after any active seasonal rule.
     */
    public function getCurrentPricePerNightAttribute(): ?float
    {
        $base = $this->discount_price && (float) $this->discount_price > 0
            ? (float) $this->discount_price
            : ((float) $this->price_per_night ?: null);
        if ($base === null) return null;

        return $this->priceForDate(null, $base);
    }

    /**
     * Get all media for the room.
     */
    public function media(): HasMany
    {
        return $this->hasMany(ServiceRoomMedia::class, 'service_room_id');
    }

    /**
     * Get the thumbnail media.
     */
    public function thumbnailMedia(): HasOne
    {
        return $this->hasOne(ServiceRoomMedia::class, 'service_room_id')
                    ->where('is_thumbnail', true);
    }

    /**
     * Get gallery images (non-thumbnail images).
     */
    public function gallery(): HasMany
    {
        return $this->hasMany(ServiceRoomMedia::class, 'service_room_id')
                    ->where('media_type', 'image')
                    ->where('is_thumbnail', false)
                    ->orderBy('display_order');
    }

    /**
     * Get all images including thumbnail.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ServiceRoomMedia::class, 'service_room_id')
                    ->where('media_type', 'image')
                    ->orderBy('display_order');
    }

    /**
     * Get the video media.
     */
    public function video(): HasOne
    {
        return $this->hasOne(ServiceRoomMedia::class, 'service_room_id')
                    ->where('media_type', 'video');
    }

    /**
     * Get the thumbnail URL.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        // Əvvəlcə thumbnail sütununa bax
        if ($this->thumbnail) {
            return asset($this->thumbnail);
        }
        
        // Sonra media tablosundan thumbnail tap
        $thumbnailMedia = $this->media()->where('is_thumbnail', true)->first();
        if ($thumbnailMedia) {
            return asset($thumbnailMedia->file_path);
        }
        
        // Heç biri yoxdursa ilk şəkilə bax
        $firstImage = $this->media()->where('media_type', 'image')->first();
        if ($firstImage) {
            return asset($firstImage->file_path);
        }
        
        return null;
    }

    /**
     * Get the effective price (with discount if available).
     */
    public function getEffectivePriceAttribute(): float
    {
        if ($this->discount_price && $this->discount_price > 0) {
            return (float) $this->discount_price;
        }
        return (float) $this->price_per_night;
    }

    /**
     * Get the discount percentage.
     */
    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->discount_price || $this->discount_price <= 0 || $this->price_per_night <= 0) {
            return 0;
        }
        
        return (int) round((($this->price_per_night - $this->discount_price) / $this->price_per_night) * 100);
    }

    /**
     * Check if room has discount.
     */
    public function hasDiscount(): bool
    {
        return $this->discount_price && $this->discount_price > 0 && $this->discount_price < $this->price_per_night;
    }
    
    public function thumbnail()
{
    return $this->hasOne(ServiceRoomMedia::class, 'service_room_id')
                ->where('is_thumbnail', true);
}

    /**
     * Check if room is available.
     */
    public function isAvailable(): bool
    {
        return $this->status && $this->quantity > 0;
    }

    /**
     * Get features as array.
     */
    public function getFeaturesListAttribute(): array
    {
        if (is_array($this->features)) {
            return $this->features;
        }
        
        if (is_string($this->features)) {
            $decoded = json_decode($this->features, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        return [];
    }

    /**
     * Scope a query to only include active rooms.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query to only include available rooms.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', true)->where('quantity', '>', 0);
    }

    /**
     * Scope a query to order by display order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}