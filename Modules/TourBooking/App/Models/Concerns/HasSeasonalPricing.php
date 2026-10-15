<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Models\Concerns;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\TourBooking\App\Models\Season;
use Modules\TourBooking\App\Models\SeasonPrice;

/**
 * Adds polymorphic season-aware pricing.
 *
 * The model implementing this trait must have a numeric base-price.
 * Override `getBaseSeasonalPrice()` if it isn't named `price` or `base_price`.
 */
trait HasSeasonalPricing
{
    public function seasonPrices(): MorphMany
    {
        return $this->morphMany(SeasonPrice::class, 'priceable');
    }

    /**
     * Resolve the base price the seasonal modifier should apply to.
     * Subclasses can override.
     */
    public function getBaseSeasonalPrice(): ?float
    {
        foreach (['discount_price', 'price_per_person', 'full_price', 'price_per_night', 'base_price', 'price'] as $field) {
            if (isset($this->{$field}) && $this->{$field} !== null && (float) $this->{$field} > 0) {
                return (float) $this->{$field};
            }
        }
        return null;
    }

    /**
     * Get the price for a specific date, applying any matching season rule.
     * Falls back to base price when no season covers the date.
     */
    public function priceForDate(Carbon|string|null $date = null, ?float $base = null): ?float
    {
        $base ??= $this->getBaseSeasonalPrice();
        if ($base === null) {
            return null;
        }

        $date = $date ? Carbon::parse($date) : Carbon::now();
        $season = Season::findForDate($date);
        if (! $season) {
            return $base;
        }

        $rule = $this->seasonPrices()->where('season_id', $season->id)->first();
        if (! $rule) {
            return round($base * (1 + ((float) $season->percentage) / 100), 2);
        }

        return $rule->apply($base, $season);
    }

    /**
     * Sync season-price rules from the request payload.
     *
     * Expected shape: ['season_prices' => [seasonId => ['mode' => 'default|percentage|fixed', 'custom_percentage' => ?, 'custom_price' => ?]]]
     * Rules with mode=default and no values are removed (treated as "no override").
     */
    public function syncSeasonPrices(?array $payload): void
    {
        if (! is_array($payload) || empty($payload)) {
            $this->seasonPrices()->delete();
            return;
        }

        $seenSeasonIds = [];
        foreach ($payload as $seasonId => $row) {
            $seasonId = (int) $seasonId;
            if ($seasonId <= 0) continue;

            $mode = in_array($row['mode'] ?? 'default', ['default', 'percentage', 'fixed'], true)
                ? $row['mode']
                : 'default';
            $customPct   = $row['custom_percentage'] ?? null;
            $customPrice = $row['custom_price'] ?? null;

            if ($mode === 'default') {
                // No override needed — drop any existing record.
                $this->seasonPrices()->where('season_id', $seasonId)->delete();
                continue;
            }

            $this->seasonPrices()->updateOrCreate(
                ['season_id' => $seasonId],
                [
                    'mode'              => $mode,
                    'custom_percentage' => $mode === 'percentage' ? ($customPct !== '' ? (float) $customPct : null) : null,
                    'custom_price'      => $mode === 'fixed'      ? ($customPrice !== '' ? (float) $customPrice : null) : null,
                ]
            );
            $seenSeasonIds[] = $seasonId;
        }
    }
}
