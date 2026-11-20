<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Bug 4: stay discount rule of a sanatorium. A rule applies when the number of nights is inside
 * [min_nights, max_nights] (each bound optional) and the booking is made at least min_days_before days
 * before arrival (optional). All matching active rules add up (5% for 6–12 nights + 5% early booking = 10%).
 *
 * @property int $service_id
 * @property int|null $min_nights
 * @property int|null $max_nights
 * @property int|null $min_days_before
 * @property float $percentage
 * @property bool $status
 */
final class ServiceStayDiscount extends Model
{
    protected $table = 'service_stay_discounts';

    protected $fillable = ['service_id', 'min_nights', 'max_nights', 'min_days_before', 'percentage', 'note', 'status'];

    protected $casts = [
        'service_id'      => 'integer',
        'min_nights'      => 'integer',
        'max_nights'      => 'integer',
        'min_days_before' => 'integer',
        'percentage'      => 'decimal:2',
        'status'          => 'boolean',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function matches(int $nights, int $daysBefore): bool
    {
        if ($this->min_nights !== null && $nights < $this->min_nights) {
            return false;
        }
        if ($this->max_nights !== null && $nights > $this->max_nights) {
            return false;
        }

        return $this->min_days_before === null || $daysBefore >= $this->min_days_before;
    }

    /** «6–12 ночей · за 30+ дней до заезда» */
    public function label(): string
    {
        $parts = [];
        if ($this->min_nights !== null || $this->max_nights !== null) {
            $parts[] = $this->max_nights === null
                ? 'от ' . $this->min_nights . ' ночей'
                : (($this->min_nights ?? 1) . '–' . $this->max_nights . ' ночей');
        }
        if ($this->min_days_before !== null) {
            $parts[] = 'раннее бронирование за ' . $this->min_days_before . '+ дней';
        }

        return implode(' · ', $parts) ?: 'любое бронирование';
    }
}
