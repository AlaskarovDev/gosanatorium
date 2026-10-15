<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

final class Room extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'service_id',
        'name',
        'description',
        'short_description',
        'price_per_night',
        'discount_price',
        'max_adults',
        'max_children',
        'room_size',
        'bed_type',
        'bed_count',
        'total_rooms',
        'low_stock_show',
        'low_stock_text',
        'amenities',
        'features',
        'is_featured',
        'status',
        'display_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price_per_night' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'max_adults' => 'integer',
        'max_children' => 'integer',
        'room_size' => 'integer',
        'bed_count' => 'integer',
        'total_rooms' => 'integer',
        'low_stock_show' => 'boolean',
        'amenities' => 'array',
        'features' => 'array',
        'is_featured' => 'boolean',
        'status' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get the service (hotel) that this room belongs to.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the media items for this room.
     */
    public function media(): HasMany
    {
        return $this->hasMany(RoomMedia::class)->orderBy('display_order');
    }

    /**
     * Get the images for this room.
     */
    public function images(): HasMany
    {
        return $this->hasMany(RoomMedia::class)
            ->where('media_type', 'image')
            ->orderBy('display_order');
    }

    /**
     * Get the videos for this room.
     */
    public function videos(): HasMany
    {
        return $this->hasMany(RoomMedia::class)
            ->where('media_type', 'video')
            ->orderBy('display_order');
    }

    /**
     * Get the thumbnail for this room.
     */
    public function thumbnail(): HasOne
    {
        return $this->hasOne(RoomMedia::class)
            ->where('is_thumbnail', true)
            ->withDefault();
    }

    /**
     * Get the bookings for this room.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the effective price (discounted if available).
     */
    protected function effectivePrice(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->discount_price ?? $this->price_per_night;
            }
        );
    }

    /**
     * Get the discount percentage.
     */
    protected function discountPercentage(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (empty($this->price_per_night) || empty($this->discount_price)) {
                    return 0;
                }

                return round((($this->price_per_night - $this->discount_price) / $this->price_per_night) * 100);
            }
        );
    }

    /**
     * Get bed type display text.
     */
    protected function bedTypeDisplay(): Attribute
    {
        return Attribute::make(
            get: function () {
                $types = [
                    'single' => 'Single Bed',
                    'double' => 'Double Bed',
                    'king' => 'King Size Bed',
                    'queen' => 'Queen Size Bed',
                    'twin' => 'Twin Beds',
                    'bunk' => 'Bunk Beds',
                ];

                return $types[$this->bed_type] ?? $this->bed_type;
            }
        );
    }

    /**
     * Active rooms scope.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Featured rooms scope.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Order by display order scope.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    /**
     * Check if rooms are available for specific dates.
     */
    public function getAvailableRoomsForDates($checkIn, $checkOut): int
    {
        $bookedRooms = $this->bookings()
            ->where('booking_status', '!=', 'cancelled')
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in_date', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in_date', '<=', $checkIn)
                            ->where('check_out_date', '>=', $checkOut);
                    });
            })
            ->count();

        return max(0, $this->total_rooms - $bookedRooms);
    }

    /**
     * Check if room is available for specific dates.
     */
    public function isAvailableForDates($checkIn, $checkOut): bool
    {
        return $this->getAvailableRoomsForDates($checkIn, $checkOut) > 0;
    }
}