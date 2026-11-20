<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Services;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Modules\TourBooking\App\Models\RoomStopSale;

/**
 * Task 42: room availability for the rooms search.
 * A stay (check-in .. check-out, check-out night not counted) is closed when any night is in a stop-sale period;
 * «rooms left» is the smallest rooms_left over the nights.
 */
final class StopSales
{
    private static ?bool $ready = null;

    public static function ready(): bool
    {
        if (self::$ready === null) {
            try {
                self::$ready = Schema::hasTable('room_stop_sales');
            } catch (\Throwable) {
                self::$ready = false;
            }
        }

        return self::$ready;
    }

    /**
     * @param  array<int, int>  $roomIds
     * @return array<int, array{closed: bool, left: int|null, alt: array{checkin: string, checkout: string}|null}>
     */
    public static function forStay(array $roomIds, CarbonInterface $checkIn, CarbonInterface $checkOut): array
    {
        if (! self::ready() || $roomIds === []) {
            return [];
        }
        $nights = max(1, (int) $checkIn->diffInDays($checkOut));
        $lastNight = $checkOut->copy()->subDay()->toDateString();
        // load a window big enough to also look for other dates (60 days after check-in)
        $horizon = $checkIn->copy()->addDays(60 + $nights)->toDateString();
        $periods = RoomStopSale::query()
            ->whereIn('service_room_id', $roomIds)
            ->where('date_to', '>=', $checkIn->toDateString())
            ->where('date_from', '<=', $horizon)
            ->get()
            ->groupBy('service_room_id');

        $out = [];
        foreach ($roomIds as $id) {
            $list = $periods->get($id, collect());
            if ($list->isEmpty()) {
                continue;
            }
            [$closed, $left] = self::state($list, $checkIn->toDateString(), $lastNight);
            $alt = null;
            if ($closed) {
                $alt = self::nextFree($list, $checkIn, $nights);
            }
            if ($closed || $left !== null) {
                $out[$id] = ['closed' => $closed, 'left' => $left, 'alt' => $alt];
            }
        }

        return $out;
    }

    /** @return array{0: bool, 1: int|null} [closed, smallest rooms_left] */
    private static function state(Collection $list, string $first, string $last): array
    {
        $closed = false;
        $left = null;
        foreach ($list as $p) {
            if ($p->date_from->toDateString() > $last || $p->date_to->toDateString() < $first) {
                continue;
            }
            if ($p->isStopSale()) {
                $closed = true;
            } elseif ($left === null || $p->rooms_left < $left) {
                $left = (int) $p->rooms_left;
            }
        }

        return [$closed, $left];
    }

    /** First free stay of the same length within 60 days after the requested check-in (later dates first, then earlier). */
    private static function nextFree(Collection $list, CarbonInterface $checkIn, int $nights): ?array
    {
        $tomorrow = Carbon::now('Asia/Baku')->startOfDay()->addDay();
        $candidates = [];
        for ($i = 1; $i <= 60; $i++) {
            $candidates[] = $checkIn->copy()->addDays($i);
            $back = $checkIn->copy()->subDays($i);
            if ($i <= 14 && $back->gte($tomorrow)) {
                $candidates[] = $back;
            }
        }
        foreach ($candidates as $in) {
            $out = $in->copy()->addDays($nights);
            [$closed] = self::state($list, $in->toDateString(), $out->copy()->subDay()->toDateString());
            if (! $closed) {
                return ['checkin' => $in->toDateString(), 'checkout' => $out->toDateString()];
            }
        }

        return null;
    }
}
