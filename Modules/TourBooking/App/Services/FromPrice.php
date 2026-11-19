<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Modules\TourBooking\App\Models\Service;
use Modules\TourBooking\App\Models\ServiceRoom;

/**
 * Task 10: the «от $X» price of a sanatorium, calculated from room prices (no manual field).
 *
 * From price = the cheapest active room price of the current or an upcoming season
 * (and the base price when some date of the next 12 months is outside every season),
 * after the room discount. Date discounts («Скидки по датам») are not counted: they are temporary
 * and the room search shows them.
 *
 * Price basis (admin, per sanatorium, services.price_basis):
 *  - person: «за ночь с человека» = cheapest double-room price / 2 (1-adult price for single rooms);
 *  - room:   «за номер (2 гостя) за ночь» = cheapest room price for 2 adults (1 for single rooms).
 *
 * Sanatoriums without priced rooms keep the old service price (price_per_person / discount / full).
 * Results are cached per day; the cache is dropped whenever a room, price, season or sanatorium is saved.
 */
final class FromPrice
{
    public const BASIS_PERSON = 'person';

    public const BASIS_ROOM = 'room';

    public const DEFAULT_BASIS = self::BASIS_PERSON;

    public const PLACEHOLDER = '{min_price}';

    private const VERSION_KEY = 'gs_from_price_version';

    /** @var array<int, float|null>|null per-request copy of the cached map */
    private static ?array $map = null;

    private static ?bool $basisReady = null;

    /** @return array<string, string> basis => Russian label */
    public static function bases(): array
    {
        return [
            self::BASIS_PERSON => 'за ночь с человека',
            self::BASIS_ROOM   => 'за номер (2 гостя) за ночь',
        ];
    }

    public static function basisReady(): bool
    {
        if (self::$basisReady === null) {
            try {
                self::$basisReady = Schema::hasColumn('services', 'price_basis');
            } catch (\Throwable) {
                self::$basisReady = false;
            }
        }

        return self::$basisReady;
    }

    public static function basis(?Service $service): string
    {
        $basis = $service && self::basisReady() ? (string) ($service->getAttributes()['price_basis'] ?? '') : '';

        return array_key_exists($basis, self::bases()) ? $basis : self::DEFAULT_BASIS;
    }

    /** Label shown under every «от $X», in the page language. */
    public static function label(?Service $service): string
    {
        $basis = self::basis($service);
        if (app()->getLocale() === 'ru') {
            return self::bases()[$basis];
        }

        return $basis === self::BASIS_ROOM ? 'per room (2 guests) per night' : 'per person per night';
    }

    /** Short label for tight places (cards): «с человека / ночь», «за номер / ночь». */
    public static function shortLabel(?Service $service): string
    {
        $room = self::basis($service) === self::BASIS_ROOM;
        if (app()->getLocale() === 'ru') {
            return $room ? 'за номер (2 гостя) / ночь' : 'с человека / ночь';
        }

        return $room ? 'per room (2 guests) / night' : 'per person / night';
    }

    /** From price in USD (site base currency), null when the sanatorium has no price at all. */
    public static function for(?Service $service): ?float
    {
        if (! $service || ! $service->id) {
            return null;
        }
        $map = self::map();
        if (array_key_exists($service->id, $map)) {
            return $map[$service->id];
        }

        // Not in the cached map (e.g. inactive sanatorium): compute just this one.
        return self::compute([$service->id])[$service->id] ?? null;
    }

    /** Replace {min_price} in an admin text with the formatted from price. */
    public static function fill(?string $text, ?Service $service): string
    {
        $text = (string) $text;
        if ($text === '' || ! str_contains($text, self::PLACEHOLDER)) {
            return $text;
        }
        $price = self::for($service);
        $formatted = $price !== null ? currency($price) : '';

        return str_replace(self::PLACEHOLDER, $formatted, $text);
    }

    /** Drop the cache: next request recalculates every sanatorium. */
    public static function flush(): void
    {
        self::$map = null;
        try {
            Cache::forever(self::VERSION_KEY, (int) Cache::get(self::VERSION_KEY, 0) + 1);
        } catch (\Throwable) {
            // cache unavailable: values are recalculated per request anyway
        }
    }

    /** @return array<int, float|null> service id => from price */
    public static function map(): array
    {
        if (self::$map !== null) {
            return self::$map;
        }

        $compute = fn () => self::compute(Service::query()->where('status', true)->pluck('id')->all());
        try {
            $key = 'gs_from_price:v2:' . (int) Cache::get(self::VERSION_KEY, 0) . ':' . self::today()->toDateString();

            return self::$map = Cache::remember($key, now()->addHours(6), $compute);
        } catch (\Throwable) {
            return self::$map = $compute();
        }
    }

    /**
     * @param  array<int, int>  $serviceIds
     * @return array<int, float|null>
     */
    public static function compute(array $serviceIds): array
    {
        if ($serviceIds === []) {
            return [];
        }

        $services = Service::query()->whereIn('id', $serviceIds)->get()->keyBy('id');
        $rooms = ServiceRoom::query()
            ->whereIn('service_id', $serviceIds)
            ->where('status', true)
            ->with(RoomPricing::eagerLoads())
            ->get()
            ->groupBy('service_id');

        $pricing = new RoomPricing();
        $keys = self::upcomingKeys($pricing);

        $out = [];
        foreach ($serviceIds as $id) {
            $service = $services->get($id);
            if (! $service) {
                continue;
            }
            $basis = self::basis($service);
            $min = null;
            foreach ($rooms->get($id, collect()) as $room) {
                $price = self::roomFrom($pricing, $room, $keys, $basis);
                if ($price !== null && ($min === null || $price < $min)) {
                    $min = $price;
                }
            }
            if ($min === null) {
                $fallback = (float) ($service->is_per_person
                    ? $service->price_per_person
                    : ($service->discount_price ?: $service->full_price));
                $min = $fallback > 0 ? $fallback : null;
            }
            $out[$id] = $min !== null ? round($min, 2) : null;
        }

        return $out;
    }

    /** Cheapest price of one room over the given grid rows, after the room discount. */
    private static function roomFrom(RoomPricing $pricing, ServiceRoom $room, array $keys, string $basis): ?float
    {
        $grid = $pricing->grid($room);
        $ratio = RoomPricing::ratioFor($room);
        $min = null;
        foreach ($keys as $key) {
            $row = $grid[$key] ?? [];
            $candidates = [];
            if ($basis === self::BASIS_ROOM) {
                $price = $row[2] ?? $row[1] ?? null;
                if ($price !== null) {
                    $candidates[] = (float) $price;
                }
            } else {
                // Per person = double-room price / 2 (1-adult price for single rooms). Prices for
                // 3+ adults are not divided: an extra-bed share is not a price a guest can book.
                if (isset($row[2]) && (float) $row[2] > 0) {
                    $candidates[] = (float) $row[2] / 2;
                } elseif (isset($row[1]) && (float) $row[1] > 0) {
                    $candidates[] = (float) $row[1];
                }
            }
            foreach ($candidates as $c) {
                $c *= $ratio;
                if ($c > 0 && ($min === null || $c < $min)) {
                    $min = $c;
                }
            }
        }

        return $min;
    }

    /**
     * Grid rows that apply from today on: active seasons that have not ended yet
     * (and start within a year), plus the base row when some night of the next 12 months has no season.
     *
     * @return array<int, int|string>
     */
    private static function upcomingKeys(RoomPricing $pricing): array
    {
        $today = self::today();
        $horizon = $today->copy()->addYear();
        $keys = [];
        foreach ($pricing->activeSeasons() as $season) {
            if (! $season->start_date || ! $season->end_date) {
                continue;
            }
            if ($season->end_date->toDateString() >= $today->toDateString()
                && $season->start_date->toDateString() <= $horizon->toDateString()) {
                $keys[] = $season->id;
            }
        }

        for ($d = $today->copy(); $d->lt($horizon); $d->addDay()) {
            if ($pricing->seasonForDate($d) === null) {
                $keys[] = RoomPricing::BASE;
                break;
            }
        }

        return $keys;
    }

    /**
     * Cheapest price per person per night of the given sanatoriums for nights between $from and $to
     * (both inclusive). Used by the homepage «Когда лучше ехать» block. Null when there is no price.
     *
     * @param  array<int, int>  $serviceIds
     */
    public static function periodMinPerPerson(array $serviceIds, Carbon $from, Carbon $to): ?float
    {
        if ($serviceIds === []) {
            return null;
        }
        $pricing = new RoomPricing();
        $keys = [];
        foreach ($pricing->activeSeasons() as $season) {
            if ($season->start_date && $season->end_date
                && $season->start_date->toDateString() <= $to->toDateString()
                && $season->end_date->toDateString() >= $from->toDateString()) {
                $keys[] = $season->id;
            }
        }
        for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
            if ($pricing->seasonForDate($d) === null) {
                $keys[] = RoomPricing::BASE;
                break;
            }
        }

        $rooms = ServiceRoom::query()
            ->whereIn('service_id', $serviceIds)
            ->where('status', true)
            ->with(RoomPricing::eagerLoads())
            ->get();
        $min = null;
        foreach ($rooms as $room) {
            $price = self::roomFrom($pricing, $room, $keys, self::BASIS_PERSON);
            if ($price !== null && ($min === null || $price < $min)) {
                $min = $price;
            }
        }

        return $min !== null ? round($min, 2) : null;
    }

    /**
     * Homepage season block: cheapest price per person for each two-month window of the
     * sanatoriums of one destination, next occurrence of each window. Cached like map().
     *
     * @param  array<int, array{0:int,1:int}>  $windows  [startMonth, endMonth] pairs
     * @return array<int, float|null>
     */
    public static function seasonBlock(string $destinationSlugPrefix, array $windows): array
    {
        $compute = function () use ($destinationSlugPrefix, $windows) {
            $ids = Service::query()
                ->where('status', true)
                ->whereHas('destination', fn ($q) => $q->where('slug', 'like', $destinationSlugPrefix . '%'))
                ->pluck('id')
                ->all();
            $today = self::today();
            $out = [];
            foreach ($windows as $i => [$m1, $m2]) {
                $from = Carbon::create($today->year, $m1, 1, 0, 0, 0, 'Asia/Baku');
                $to = Carbon::create($today->year, $m2, 1, 0, 0, 0, 'Asia/Baku')->endOfMonth()->startOfDay();
                if ($to->lt($today)) {
                    $from->addYear();
                    $to = Carbon::create($today->year + 1, $m2, 1, 0, 0, 0, 'Asia/Baku')->endOfMonth()->startOfDay();
                }
                if ($from->lt($today)) {
                    $from = $today->copy();
                }
                $out[$i] = self::periodMinPerPerson($ids, $from, $to);
            }

            return $out;
        };

        try {
            $key = 'gs_season_block:v2:' . md5($destinationSlugPrefix . json_encode($windows)) . ':'
                . (int) Cache::get(self::VERSION_KEY, 0) . ':' . self::today()->toDateString();

            return Cache::remember($key, now()->addHours(6), $compute);
        } catch (\Throwable) {
            return $compute();
        }
    }

    private static function today(): Carbon
    {
        return Carbon::now('Asia/Baku')->startOfDay();
    }
}
