<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\TourBooking\App\Models\Concerns\HasSeasonalPricing;

/**
 * Transfer Route Model
 * 
 * @property int $id
 * @property string $from_location
 * @property string $to_location
 * @property string|null $description
 * @property float|null $base_price
 * @property int|null $duration_minutes
 * @property bool $status
 * @property int $display_order
 */
final class TransferRoute extends Model
{
    use HasFactory;
    use HasSeasonalPricing;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transfer_routes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'from_location',
        'to_location',
        'description',
        'base_price',
        'duration_minutes',
        'status',
        'display_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'base_price' => 'decimal:2',
        'duration_minutes' => 'integer',
        'status' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get the full route name.
     *
     * @return string
     */
    public function getRouteNameAttribute(): string
    {
        return $this->from_location . ' → ' . $this->to_location;
    }

    /**
     * Get active routes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Get routes ordered by display order.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc');
    }
}