<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Services;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Modules\TourBooking\App\Models\RoomOccupancyPrice;
use Modules\TourBooking\App\Models\Season;
use Modules\TourBooking\App\Models\SeasonPrice;
use Modules\TourBooking\App\Models\ServiceDateDiscount;
use Modules\TourBooking\App\Models\ServiceRoom;

/**
 * Room price for a stay: per room, per season, per number of adults.
 *
 * Prices live in room_occupancy_prices (season_id null = base price for nights outside every season).
 * Each night takes the price of the season it belongs to, then the room discount
 * (discount_price vs price_per_night) is applied, then the sanatorium's date discount for that night
 * (service_date_discounts, admin "Скидки по датам"). A missing price means "price on request".
 *
 * Rooms without any occupancy rows (or before the migration ran) fall back to the old
 * single price: price_per_night for 2 adults (1 for single rooms) with the old season rules.
 */
final class RoomPricing
{
    public const BASE = 'base';

    /** Grid column for the price of one child per night (stored as adults = 0). */
    public const CHILD = RoomOccupancyPrice::CHILD;

    private static ?bool $tableReady = null;

    private static ?bool $discountsReady = null;

    /** @var array<int, Collection<int, ServiceDateDiscount>> active date discounts per service id */
    private array $dateDiscounts = [];

    /** @var Collection<int, Season> all seasons, keyed by id */
    private Collection $seasons;

    /** @var Collection<int, Season> active seasons ordered by start date (lookup order of Season::findForDate) */
    private Collection $activeSeasons;

    /** @var array<int, array> grid cache per room id */
    private array $grids = [];

    public function __construct(?Collection $seasons = null)
    {
        $this->seasons = ($seasons ?? Season::query()->orderBy('start_date')->get())->keyBy('id');
        $this->activeSeasons = $this->seasons->filter(fn (Season $s) => (bool) $s->status)->sortBy('start_date')->values();
    }

    public static function tableReady(): bool
    {
        if (self::$tableReady === null) {
            try {
                self::$tableReady = Schema::hasTable('room_occupancy_prices');
            } catch (\Throwable) {
                self::$tableReady = false;
            }
        }

        return self::$tableReady;
    }

    public static function discountsReady(): bool
    {
        if (self::$discountsReady === null) {
            try {
                self::$discountsReady = Schema::hasTable('service_date_discounts');
            } catch (\Throwable) {
                self::$discountsReady = false;
            }
        }

        return self::$discountsReady;
    }

    /** Date discount (percent) of the sanatorium for one night, 0 when none. */
    public function dateDiscountFor(int $serviceId, CarbonInterface $date): float
    {
        if (! self::discountsReady()) {
            return 0.0;
        }
        if (! isset($this->dateDiscounts[$serviceId])) {
            $this->dateDiscounts[$serviceId] = ServiceDateDiscount::query()
                ->where('service_id', $serviceId)
                ->where('status', true)
                ->orderBy('start_date')
                ->get();
        }
        $d = $date->toDateString();
        foreach ($this->dateDiscounts[$serviceId] as $discount) {
            if ($discount->start_date->toDateString() <= $d && $discount->end_date->toDateString() >= $d) {
                return max(0.0, min(100.0, (float) $discount->percentage));
            }
        }

        return 0.0;
    }

    /** Relations to eager load on ServiceRoom before calling grid()/quote() for many rooms. */
    public static function eagerLoads(): array
    {
        return self::tableReady() ? ['occupancyPrices', 'seasonPrices'] : ['seasonPrices'];
    }

    public function seasons(): Collection
    {
        return $this->seasons;
    }

    public function activeSeasons(): Collection
    {
        return $this->activeSeasons;
    }

    public function seasonForDate(CarbonInterface $date): ?Season
    {
        $d = $date->toDateString();
        foreach ($this->activeSeasons as $season) {
            if ($season->start_date && $season->end_date
                && $season->start_date->toDateString() <= $d
                && $season->end_date->toDateString() >= $d) {
                return $season;
            }
        }

        return null;
    }

    /** True when the room has its own price grid (not the old single price). */
    public function hasOwnGrid(ServiceRoom $room): bool
    {
        return self::tableReady() && $room->occupancyPrices->isNotEmpty();
    }

    /**
     * Price grid of a room: [ 'base' | seasonId => [ adults => list price per night ] ].
     * Column 0 (self::CHILD) is the price of one child per night.
     */
    public function grid(ServiceRoom $room): array
    {
        if (isset($this->grids[$room->id])) {
            return $this->grids[$room->id];
        }

        $grid = [];
        if ($this->hasOwnGrid($room)) {
            foreach ($room->occupancyPrices as $row) {
                if ((float) $row->price <= 0) {
                    continue;
                }
                $grid[$row->season_id ?? self::BASE][(int) $row->adults] = round((float) $row->price, 2);
            }
        } else {
            $grid = self::legacyGrid($room, $this->seasons);
        }

        return $this->grids[$room->id] = $grid;
    }

    /**
     * Old pricing expressed as a grid: price_per_night for 2 adults (1 adult for single rooms),
     * season rows built with the old season rules. Also used by the migration that fills the table.
     */
    public static function legacyGrid(ServiceRoom $room, Collection $seasons): array
    {
        $list = (float) $room->price_per_night;
        $ratio = self::ratioFor($room);
        if ($list <= 0) {
            $list = (float) $room->discount_price;
            $ratio = 1.0;
        }

        $bases = [];
        if ($list > 0) {
            $bases[((int) $room->max_guests === 1) ? 1 : 2] = $list;
        }
        // Earlier single-occupancy price (if a price_single column exists) becomes the 1-adult price.
        $single = (float) ($room->getAttributes()['price_single'] ?? 0);
        if ($single > 0) {
            $bases[1] = $single;
        }

        $rules = $room->relationLoaded('seasonPrices') ? $room->seasonPrices : $room->seasonPrices()->get();
        $rules = $rules->keyBy('season_id');

        $grid = [];
        foreach ($bases as $adults => $base) {
            $grid[self::BASE][$adults] = round($base, 2);
            foreach ($seasons as $season) {
                /** @var SeasonPrice|null $rule */
                $rule = $rules->get($season->id);
                if ($rule && $rule->mode === SeasonPrice::MODE_FIXED && $rule->custom_price !== null) {
                    // Old fixed season price was the final price: keep it final after the discount.
                    $price = $ratio > 0 ? (float) $rule->custom_price / $ratio : (float) $rule->custom_price;
                } elseif ($rule && $rule->mode === SeasonPrice::MODE_PERCENTAGE) {
                    $price = $base * (1 + ((float) ($rule->custom_percentage ?? 0)) / 100);
                } else {
                    $price = $base * (1 + ((float) $season->percentage) / 100);
                }
                $grid[$season->id][$adults] = round($price, 2);
            }
        }

        return $grid;
    }

    /** Share of the list price the guest pays (1.0 = no discount). */
    public static function ratioFor(ServiceRoom $room): float
    {
        $list = (float) $room->price_per_night;
        $disc = (float) $room->discount_price;
        if ($list > 0 && $disc > 0 && $disc < $list) {
            return $disc / $list;
        }

        return 1.0;
    }

    public static function discountPercent(ServiceRoom $room): int
    {
        $ratio = self::ratioFor($room);

        return $ratio < 1 ? (int) round((1 - $ratio) * 100) : 0;
    }

    /**
     * Price of a stay, night by night. Returns null when a night has no price for these adults
     * ("price on request"). Children are added with the room's child price of that season
     * (base child price when the season has none); without any child price children are not charged
     * and child_priced is false (the site then says the manager will tell the child price).
     *
     * @return array{nights:int,total:float,list_total:float,per_night:float,has_discount:bool,discount_percent:int,adults:int,nights_detail:array}|null
     */
    public function quote(ServiceRoom $room, CarbonInterface $checkIn, CarbonInterface $checkOut, int $adults, int $children = 0): ?array
    {
        $checkIn = Carbon::parse($checkIn->toDateString());
        $checkOut = Carbon::parse($checkOut->toDateString());
        $nights = (int) $checkIn->diffInDays($checkOut, false);
        if ($nights < 1) {
            return null;
        }

        $grid = $this->grid($room);
        $ratio = self::ratioFor($room);

        $children = max(0, $children);
        $childPriced = $children > 0;
        $hasDateDiscount = false;
        $total = 0.0;
        $listTotal = 0.0;
        $detail = [];
        for ($i = 0; $i < $nights; $i++) {
            $night = $checkIn->copy()->addDays($i);
            $season = $this->seasonForDate($night);
            $key = $season ? $season->id : self::BASE;
            $list = $grid[$key][$adults] ?? null;
            if ($list === null) {
                return null;
            }
            $childList = 0.0;
            if ($children > 0) {
                $one = $grid[$key][self::CHILD] ?? $grid[self::BASE][self::CHILD] ?? null;
                if ($one === null) {
                    $childPriced = false;
                } else {
                    $childList = $one * $children;
                }
            }
            $list += $childList;
            $datePct = $this->dateDiscountFor((int) $room->service_id, $night);
            if ($datePct > 0) {
                $hasDateDiscount = true;
            }
            $pay = round($list * $ratio * (1 - $datePct / 100), 2);
            $listTotal += $list;
            $total += $pay;
            $detail[] = [
                'date'   => $night->toDateString(),
                'season' => $season?->name,
                'list'   => $list,
                'children_list' => $childList,
                'date_discount' => $datePct,
                'price'  => $pay,
            ];
        }

        $total = round($total, 2);
        $listTotal = round($listTotal, 2);

        return [
            'nights'           => $nights,
            'adults'           => $adults,
            'children'         => $children,
            'child_priced'     => $childPriced,
            'total'            => $total,
            'list_total'       => $listTotal,
            'per_night'        => round($total / $nights, 2),
            'has_discount'     => ($ratio < 1 || $hasDateDiscount) && $listTotal > $total,
            // With a date discount the badge shows the real saving of the whole stay.
            'discount_percent' => $hasDateDiscount && $listTotal > 0
                ? (int) round((1 - $total / $listTotal) * 100)
                : self::discountPercent($room),
            'date_discount'    => $hasDateDiscount,
            'nights_detail'    => $detail,
        ];
    }

    /**
     * Save the admin price grid: payload [ 'base' | seasonId => [ adults => price|'' ] ].
     * Empty cell = no price. Seasons that are not in the payload are left untouched.
     */
    public static function syncGrid(ServiceRoom $room, mixed $payload): void
    {
        if (! self::tableReady() || ! is_array($payload)) {
            return;
        }

        foreach ($payload as $key => $row) {
            if (! is_array($row)) {
                continue;
            }
            $seasonId = $key === self::BASE ? null : (int) $key;
            if ($seasonId !== null && $seasonId <= 0) {
                continue;
            }
            for ($adults = self::CHILD; $adults <= RoomOccupancyPrice::MAX_ADULTS; $adults++) {
                if (! array_key_exists($adults, $row)) {
                    continue;
                }
                $raw = is_string($row[$adults]) ? str_replace([' ', ','], ['', '.'], trim($row[$adults])) : $row[$adults];
                $query = RoomOccupancyPrice::query()
                    ->where('room_id', $room->id)
                    ->where('adults', $adults)
                    ->when($seasonId === null, fn ($q) => $q->whereNull('season_id'), fn ($q) => $q->where('season_id', $seasonId));

                if ($raw === null || $raw === '' || ! is_numeric($raw) || (float) $raw <= 0) {
                    $query->delete();
                    continue;
                }

                $price = round((float) $raw, 2);
                $existing = $query->first();
                if ($existing) {
                    $existing->update(['price' => $price]);
                } else {
                    RoomOccupancyPrice::create([
                        'room_id'   => $room->id,
                        'season_id' => $seasonId,
                        'adults'    => $adults,
                        'price'     => $price,
                    ]);
                }
            }
        }

        $room->unsetRelation('occupancyPrices');
    }
}
