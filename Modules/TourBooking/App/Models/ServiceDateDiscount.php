<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Discount of a sanatorium for a date range: every night inside [start_date, end_date]
 * costs `percentage` % less. Applied automatically by RoomPricing::quote().
 */
final class ServiceDateDiscount extends Model
{
    protected $fillable = [
        'service_id',
        'start_date',
        'end_date',
        'percentage',
        'note',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'percentage' => 'decimal:2',
        'status'     => 'boolean',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /** Other discount of the same sanatorium whose dates cross this one, or null. */
    public function findOverlap(): ?self
    {
        return self::query()
            ->where('service_id', $this->service_id)
            ->when($this->exists, fn ($q) => $q->where('id', '<>', $this->id))
            ->whereDate('start_date', '<=', $this->end_date)
            ->whereDate('end_date', '>=', $this->start_date)
            ->first();
    }

    /** active | upcoming | finished | off */
    public function state(): string
    {
        if (! $this->status) {
            return 'off';
        }
        $today = now()->toDateString();
        if ($this->end_date->toDateString() < $today) {
            return 'finished';
        }

        return $this->start_date->toDateString() > $today ? 'upcoming' : 'active';
    }
}
