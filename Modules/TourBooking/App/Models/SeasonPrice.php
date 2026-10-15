<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class SeasonPrice extends Model
{
    public const MODE_DEFAULT    = 'default';
    public const MODE_PERCENTAGE = 'percentage';
    public const MODE_FIXED      = 'fixed';

    protected $fillable = [
        'season_id',
        'priceable_id',
        'priceable_type',
        'mode',
        'custom_percentage',
        'custom_price',
    ];

    protected $casts = [
        'custom_percentage' => 'decimal:2',
        'custom_price'      => 'decimal:2',
    ];

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function priceable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Apply this rule to a base price and return the adjusted price for the season.
     * The Season model is provided so we can fall back to its default percentage.
     */
    public function apply(float $basePrice, Season $season): float
    {
        return match ($this->mode) {
            self::MODE_FIXED      => (float) ($this->custom_price ?? $basePrice),
            self::MODE_PERCENTAGE => round($basePrice * (1 + ((float) ($this->custom_percentage ?? 0)) / 100), 2),
            default               => round($basePrice * (1 + ((float) $season->percentage) / 100), 2),
        };
    }
}
