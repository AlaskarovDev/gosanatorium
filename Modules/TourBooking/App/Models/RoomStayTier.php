<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Bug 4: price per room per night for a season (null = base), a number of adults (0 = one child)
 * and a range of nights of the whole booking (max_nights null = no upper limit).
 *
 * @property int $room_id
 * @property int|null $season_id
 * @property int $adults
 * @property int $min_nights
 * @property int|null $max_nights
 * @property float $price
 */
final class RoomStayTier extends Model
{
    protected $table = 'room_stay_tiers';

    protected $fillable = ['room_id', 'season_id', 'adults', 'min_nights', 'max_nights', 'price'];

    protected $casts = [
        'room_id'    => 'integer',
        'season_id'  => 'integer',
        'adults'     => 'integer',
        'min_nights' => 'integer',
        'max_nights' => 'integer',
        'price'      => 'decimal:2',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(ServiceRoom::class, 'room_id');
    }

    public function covers(int $nights): bool
    {
        return $nights >= $this->min_nights && ($this->max_nights === null || $nights <= $this->max_nights);
    }

    /** «3–5», «11+» */
    public function rangeLabel(): string
    {
        return $this->max_nights === null ? $this->min_nights . '+' : $this->min_nights . '–' . $this->max_nights;
    }
}
