<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\TourBooking\App\Models\Concerns\HasSeasonalPricing;

/**
 * Transfer Vehicle Model
 * Admin paneldə əlavə edilən maşınlar
 */
final class TransferVehicle extends Model
{
    use HasFactory;
    use HasSeasonalPricing;

    protected $table = 'transfer_vehicles';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'gallery',
        'passenger_capacity',
        'luggage_capacity',
        'features',
        'base_price',
        'price_per_km',
        'status',
        'display_order',
    ];

    protected $casts = [
        'gallery' => 'array',
        'features' => 'array',
        'passenger_capacity' => 'integer',
        'luggage_capacity' => 'integer',
        'base_price' => 'decimal:2',
        'price_per_km' => 'decimal:2',
        'status' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get vehicle bookings
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(TransferBooking::class, 'vehicle_id');
    }

    /**
     * Calculate price for a route
     */
    public function calculatePrice($routePrice = 0): float
    {
        return (float) $this->base_price + (float) $routePrice;
    }

    /**
     * Scope: Active vehicles
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope: Ordered
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc');
    }

    /**
     * Get features as formatted list
     */
    public function getFeaturesListAttribute(): array
    {
        return $this->features ?? [];
    }
}
