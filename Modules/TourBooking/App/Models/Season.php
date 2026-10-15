<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Season extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'percentage',
        'color',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'percentage' => 'decimal:2',
        'status'     => 'boolean',
    ];

    public function prices(): HasMany
    {
        return $this->hasMany(SeasonPrice::class);
    }

    /**
     * Find a season that contains the given date (status=true only).
     */
    public static function findForDate(Carbon $date): ?self
    {
        return self::query()
            ->where('status', true)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->orderBy('start_date')
            ->first();
    }

    /**
     * Check whether this season range overlaps with any other season.
     * Returns the conflicting season or null.
     */
    public function findOverlap(): ?self
    {
        return self::query()
            ->when($this->exists, fn ($q) => $q->where('id', '<>', $this->id))
            ->where(function ($q) {
                $q->whereDate('start_date', '<=', $this->end_date)
                  ->whereDate('end_date', '>=', $this->start_date);
            })
            ->first();
    }
}
