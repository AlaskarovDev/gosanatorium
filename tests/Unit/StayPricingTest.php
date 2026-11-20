<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Modules\TourBooking\App\Models\RoomStayTier;
use Modules\TourBooking\App\Models\Season;
use Modules\TourBooking\App\Models\ServiceRoom;
use Modules\TourBooking\App\Models\ServiceStayDiscount;
use Modules\TourBooking\App\Services\RoomPricing;
use Tests\TestCase;

/**
 * Round 2, Bug 4: length-of-stay tiers, seasons per night, stay discount rules, precision.
 * Runs without a database: relations and rules are preloaded.
 */
class StayPricingTest extends TestCase
{
    private RoomPricing $pricing;

    protected function setUp(): void
    {
        parent::setUp();
        RoomPricing::setReady(false, false, true);
        $low = (new Season())->forceFill(['id' => 1, 'name' => 'Ноябрь–апрель', 'start_date' => '2026-11-01', 'end_date' => '2027-04-30', 'percentage' => 0, 'status' => true]);
        $high = (new Season())->forceFill(['id' => 2, 'name' => 'Май–октябрь', 'start_date' => '2027-05-01', 'end_date' => '2027-10-31', 'percentage' => 0, 'status' => true]);
        $this->pricing = new RoomPricing(collect([$low, $high]));
        $this->pricing->today = Carbon::parse('2027-01-01');
    }

    protected function tearDown(): void
    {
        RoomPricing::setReady(null, null, null);
        parent::tearDown();
    }

    /** Maricel-like room: per room per night, Nov–Apr 2 adults 3–5: 300, 7–10: 250, 11+: 220. */
    private function maricel(): ServiceRoom
    {
        $tiers = [
            // [season, adults, min, max, price]
            [1, 2, 3, 5, 300], [1, 2, 7, 10, 250], [1, 2, 11, null, 220],
            [2, 2, 3, 5, 350], [2, 2, 7, 10, 300], [2, 2, 11, null, 270],
            [1, 1, 3, 5, 200], [1, 1, 7, 10, 170], [1, 1, 11, null, 150],
            [1, 0, 1, null, 50],
        ];

        return $this->room(5, 10, $tiers);
    }

    private function room(int $serviceId, int $roomId, array $tiers): ServiceRoom
    {
        $room = (new ServiceRoom())->forceFill(['id' => $roomId, 'service_id' => $serviceId, 'price_per_night' => 0, 'discount_price' => 0, 'max_guests' => 3]);
        $room->setRelation('occupancyPrices', collect());
        $room->setRelation('seasonPrices', collect());
        $room->setRelation('stayTiers', collect(array_map(fn ($t) => new RoomStayTier([
            'room_id' => $roomId, 'season_id' => $t[0], 'adults' => $t[1], 'min_nights' => $t[2], 'max_nights' => $t[3], 'price' => $t[4],
        ]), $tiers)));
        $this->pricing->setStayDiscounts($serviceId, collect());

        return $room;
    }

    private function total(ServiceRoom $room, string $in, int $nights, int $adults = 2, int $children = 0): ?float
    {
        $q = $this->pricing->quote($room, Carbon::parse($in), Carbon::parse($in)->addDays($nights), $adults, $children);

        return $q ? $q['total'] : null;
    }

    public function test_maricel_tiers_in_march(): void
    {
        $room = $this->maricel();
        $this->assertSame(1500.0, $this->total($room, '2027-03-01', 5));
        $this->assertSame(1750.0, $this->total($room, '2027-03-01', 7));
        $this->assertSame(2500.0, $this->total($room, '2027-03-01', 10));
        $this->assertSame(2420.0, $this->total($room, '2027-03-01', 11));
        $this->assertSame(2640.0, $this->total($room, '2027-03-01', 12));
    }

    public function test_six_nights_is_a_gap_for_the_manager(): void
    {
        $room = $this->maricel();
        $this->assertNull($this->total($room, '2027-03-01', 6));
        $this->assertSame(6, $this->pricing->tierGaps[10] ?? null);
        // the next search clears the gap
        $this->total($room, '2027-03-01', 7);
        $this->assertArrayNotHasKey(10, $this->pricing->tierGaps);
    }

    public function test_breakdown_lines(): void
    {
        $q = $this->pricing->quote($this->maricel(), Carbon::parse('2027-03-01'), Carbon::parse('2027-03-11'), 2);
        $this->assertSame([['nights' => 10, 'price' => 250.0, 'amount' => 2500.0]], array_map(fn ($l) => array_intersect_key($l, ['nights' => 1, 'price' => 1, 'amount' => 1]), $q['lines']));
        $this->assertTrue($q['tier_price']);
    }

    public function test_stay_across_two_seasons_uses_the_tier_of_the_whole_stay(): void
    {
        // 28.04 → 05.05: 7 nights → tier 7–10; 3 nights low season (250) + 4 nights high season (300)
        $q = $this->pricing->quote($this->maricel(), Carbon::parse('2027-04-28'), Carbon::parse('2027-05-05'), 2);
        $this->assertSame(1950.0, $q['total']);
        $this->assertCount(2, $q['lines']);
    }

    public function test_one_adult_and_child(): void
    {
        $room = $this->maricel();
        $this->assertSame(1700.0, $this->total($room, '2027-03-01', 10, 1));
        $q = $this->pricing->quote($room, Carbon::parse('2027-03-01'), Carbon::parse('2027-03-11'), 2, 1);
        $this->assertSame(3000.0, $q['total']);
        $this->assertTrue($q['child_priced']);
    }

    public function test_cheaper_longer_hint(): void
    {
        $room = $this->maricel();
        $hint = $this->pricing->cheaperLonger($room, Carbon::parse('2027-03-01'), 10, 2, 0, 2500.0);
        $this->assertSame(['nights' => 11, 'total' => 2420.0], $hint);
        $this->assertNull($this->pricing->cheaperLonger($room, Carbon::parse('2027-03-01'), 11, 2, 0, 2420.0));
    }

    public function test_ensana_length_and_early_booking_discounts_add_up(): void
    {
        $room = $this->room(6, 20, [[1, 2, 1, null, 100]]);
        $rules = collect([
            new ServiceStayDiscount(['service_id' => 6, 'min_nights' => 6, 'max_nights' => 12, 'percentage' => 5, 'status' => true]),
            new ServiceStayDiscount(['service_id' => 6, 'min_nights' => 13, 'max_nights' => 19, 'percentage' => 10, 'status' => true]),
            new ServiceStayDiscount(['service_id' => 6, 'min_nights' => 20, 'max_nights' => null, 'percentage' => 15, 'status' => true]),
            new ServiceStayDiscount(['service_id' => 6, 'min_days_before' => 30, 'percentage' => 5, 'status' => true]),
        ]);
        $this->pricing->setStayDiscounts(6, $rules);

        // today 01.01.2027, arrival 01.03.2027 = 59 days → early booking
        $this->assertSame(630.0, $this->total($room, '2027-03-01', 7));   // 700 − 10%
        $this->assertSame(1190.0, $this->total($room, '2027-03-01', 14)); // 1400 − 15%
        $this->pricing->today = Carbon::parse('2027-02-20');               // 9 days before → no early booking
        $this->assertSame(665.0, $this->total($room, '2027-03-01', 7));   // 700 − 5%
        $this->assertSame(1260.0, $this->total($room, '2027-03-01', 14)); // 1400 − 10%
        $q = $this->pricing->quote($room, Carbon::parse('2027-03-01'), Carbon::parse('2027-03-08'), 2);
        $this->assertSame(700.0, $q['subtotal']);
        $this->assertSame(35.0, $q['stay_discount_amount']);
    }

    public function test_exact_decimals(): void
    {
        $room = $this->room(7, 30, [[1, 2, 1, null, 117.60]]);
        $this->assertSame(352.8, $this->total($room, '2027-03-01', 3));
    }

    public function test_room_without_tiers_keeps_the_normal_price(): void
    {
        $room = $this->room(8, 40, []);
        $this->assertSame([], $this->pricing->tiers($room));
        $this->assertNull(RoomPricing::tierPrice([], 1, 2, 10));
    }
}
