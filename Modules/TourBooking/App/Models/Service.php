<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Wishlist\App\Models\Wishlist;
use Modules\TourBooking\App\Models\ServiceRoomMedia;
use Modules\TourBooking\App\Models\ServiceRoom;
use Modules\TourBooking\App\Models\ServiceTreatmentItem;
use Modules\TourBooking\App\Models\Concerns\HasSeasonalPricing;



final class Service extends Model
{
    use HasFactory;
    use HasSeasonalPricing;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'doctor_name',
'doctor_position', 
'doctor_description',
'doctor_image',
'doctor_video_url',
'consultant_button_url',
        'description',
        'treatment_section_title',
        'short_description',
        'slug',
        'location',
        'latitude',
        'longitude',
        'service_type_id',
        'destination_id',
        'price_per_person',
        'full_price',
        'discount_price',
        'child_price',
        'infant_price',
        'security_deposit',
        'deposit_required',
        'deposit_percentage',
        'included',
        'excluded',
        'duration',
        'group_size',
        'languages',
        'check_in_time',
        'check_out_time',
        'ticket',
        'amenities',
        'facilities',
        'rules',
        'safety',
        'cancellation_policy',
        'meta',
        'is_featured',
        'is_popular',
        'badges',
        'show_on_homepage',
        'status',
        'video_url',
        'address',
        'email',
        'phone',
        'website',
        'social_links',
        'user_id',
        'is_new',
        'room_count',
        'adult_count',
        'children_count',
        'tour_plan_sub_title',
        'google_map_sub_title',
        'google_map_url',
        'is_per_person'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'included' => 'json',
        'excluded' => 'json',
        'languages' => 'array',
        'amenities' => 'array',
        'facilities' => 'json',
        'rules' => 'json',
        'safety' => 'json',
        'cancellation_policy' => 'json',
        'meta' => 'json',
        'social_links' => 'json',
        'price_per_person' => 'decimal:2',
        'full_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'child_price' => 'decimal:2',
        'infant_price' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'deposit_required' => 'boolean',
        'is_featured' => 'boolean',
        'is_popular' => 'boolean',
        'badges' => 'array',
        'show_on_homepage' => 'boolean',
        'status' => 'boolean',
        'is_new' => 'boolean',
        'is_per_person' => 'boolean'
    ];

    protected static function booted(): void
    {
        // Slug dəyişəndə köhnə slug-u tarixçəyə yaz — SEO üçün köhnə URL 301 redirect alacaq
        static::updating(function (Service $service): void {
            if ($service->isDirty('slug')) {
                $oldSlug = $service->getOriginal('slug');
                if (!empty($oldSlug) && $oldSlug !== $service->slug) {
                    ServiceSlugHistory::updateOrCreate(
                        ['slug' => $oldSlug],
                        ['service_id' => $service->id]
                    );
                }
            }
        });
    }

    public function slugHistories(): HasMany
    {
        return $this->hasMany(ServiceSlugHistory::class);
    }

    /**
     * Get the service type of this service.
     */
    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    /**
     * Get the destination of this service.
     */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    /**
     * Get the user (owner) of this service.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the media items for this service.
     */
    public function media(): HasMany
    {
        return $this->hasMany(ServiceMedia::class);
    }

    /**
     * Get the price list (PDF) items for this service.
     */
    public function priceLists(): HasMany
    {
        return $this->hasMany(ServicePriceList::class)->orderBy('display_order');
    }
public function rooms(): HasMany
{
    return $this->hasMany(ServiceRoom::class, 'service_id')->orderBy('display_order');
}

/**
 * Get active rooms for the service.
 */
public function activeRooms(): HasMany
{
    return $this->hasMany(ServiceRoom::class, 'service_id')
                ->where('status', true)
                ->orderBy('display_order');
}

/**
 * Check if service has rooms.
 */
public function availableRooms(): HasMany
{
    return $this->hasMany(ServiceRoom::class, 'service_id')
                ->where('status', true)
                ->where('quantity', '>', 0)
                ->orderBy('display_order');
}

/**
 * Check if service has rooms.
 */
public function hasRooms(): bool
{
    return $this->rooms()->exists();
}

public function getCheapestRoomPriceAttribute(): ?float
{
    $room = $this->activeRooms()->orderBy('price_per_night', 'asc')->first();
    return $room ? ($room->discount_price ?? $room->price_per_night) : null;
}

/**
 * Today's effective price after any active seasonal rule.
 * Falls back to discount_price / full_price / price_per_person.
 */
public function getCurrentPriceAttribute(): ?float
{
    return $this->priceForDate(null);
}

/**
 * Today's per-person price after any active seasonal rule.
 */
public function getCurrentPricePerPersonAttribute(): ?float
{
    return $this->priceForDate(null, $this->price_per_person ? (float) $this->price_per_person : null);
}
public function getMinRoomPriceAttribute(): ?float
{
    $room = $this->rooms()
        ->where('status', true)
        ->selectRaw('COALESCE(NULLIF(discount_price, 0), price_per_night) as effective_price')
        ->orderBy('effective_price')
        ->first();
        
    return $room ? (float) $room->effective_price : null;
}
    /**
     * Get the featured image for this service.
     */
    public function thumbnail(): HasOne
    {
        return $this->hasOne(ServiceMedia::class)
            ->where('is_thumbnail', true)
            ->withDefault();
    }

    /**
     * Get the reviews for this service.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function activeReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', 1);
    }

    /**
     * Get the extra charges for this service.
     */
    public function extraCharges(): HasMany
    {
        return $this->hasMany(ExtraCharge::class);
    }

    /**
     * Get the availabilities for this service.
     */
    public function availabilities(): HasMany
    {
        return $this->hasMany(Availability::class);
    }

    /**
     * Get the bookings for this service.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the itineraries for this service (if it's a tour).
     */
    public function itineraries(): HasMany
    {
        return $this->hasMany(TourItinerary::class)->orderBy('day_number');
    }

    /**
     * Get the translation for current locale.
     */
    public function translation(): HasOne
    {
        return $this->hasOne(ServiceTranslation::class)
            ->where('locale', app()->getLocale())
            ->withDefault();
    }

    /**
     * Get all translations for this service.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(ServiceTranslation::class);
    }

    /**
     * Get the computed discounted price attribute.
     */
    protected function discountedPrice(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!empty($this->discount_price)) {
                    return $this->discount_price;
                }

                if (!empty($this->full_price)) {
                    return $this->full_price;
                }

                return $this->price_per_person;
            }
        );
    }

    /**
     * Get the computed discount percentage attribute.
     */
    protected function discountPercentage(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (empty($this->full_price) || empty($this->discount_price)) {
                    return 0;
                }

                return round((($this->full_price - $this->discount_price) / $this->full_price) * 100);
            }
        );
    }

    /**
     * Get the computed average rating attribute.
     */
    protected function averageRating(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->reviews()->where('status', true)->avg('rating') ?? 0;
            }
        );
    }

    /**
     * Get the computed review count attribute.
     */
    protected function reviewCount(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->reviews()->where('status', true)->count();
            }
        );
    }

    /**
     * Featured services scope.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Popular services scope.
     */
    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    /**
     * Homepage services scope.
     */
    public function scopeShowOnHomepage($query)
    {
        return $query->where('show_on_homepage', true);
    }

    /**
     * Active services scope.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Service type scope.
     */
    public function scopeOfType($query, $typeId)
    {
        return $query->where('service_type_id', $typeId);
    }

    /**
     * By price range scope.
     */
    public function scopeByPriceRange($query, $min, $max)
    {
        return $query->whereBetween('discounted_price', [$min, $max]);
    }

    /**
     * Search by location scope.
     */
    public function scopeByLocation($query, $location)
    {
        return $query->where('location', 'like', "%{$location}%");
    }

    public function getPriceDisplayAttribute()
    {
        if ($this->is_per_person) {
            return currency($this->price_per_person);
        }

        return $this->discount_price
            ? '<del>' . currency($this->full_price) . '</del> ' . currency($this->discount_price)
            : currency($this->full_price);
    }

    public function wishlists()
    {
        return $this->morphMany(Wishlist::class, 'wishable');
    }

    public function myWishlist()
    {
        return $this->hasOne(Wishlist::class, 'wishable_id', 'id')->where('user_id', auth()->id());
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id', 'name', 'email', 'image', 'username');
    }
    public function treatmentItems(): HasMany
{
    return $this->hasMany(ServiceTreatmentItem::class)->orderBy('display_order');
}
}
