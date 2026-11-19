<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Per-night price of a room for a given season and number of adults.
 * season_id = null is the base price used for nights outside every season.
 * adults = 0 (CHILD) is the price of one child per night.
 *
 * @property int $room_id
 * @property int|null $season_id
 * @property int $adults
 * @property float $price
 */
final class RoomOccupancyPrice extends Model
{
    public const MAX_ADULTS = 4;

    /** adults = 0 row holds the price of one child per night. */
    public const CHILD = 0;

    protected $table = 'room_occupancy_prices';

    protected $fillable = [
        'room_id',
        'season_id',
        'adults',
        'price',
    ];

    protected $casts = [
        'room_id'   => 'integer',
        'season_id' => 'integer',
        'adults'    => 'integer',
        'price'     => 'decimal:2',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(ServiceRoom::class, 'room_id');
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }
}
