<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\TourBooking\App\Models\RoomStayTier;
use Modules\TourBooking\App\Models\Season;
use Modules\TourBooking\App\Models\Service;
use Modules\TourBooking\App\Models\ServiceRoom;
use Modules\TourBooking\App\Models\ServiceStayDiscount;
use Modules\TourBooking\App\Services\FromPrice;
use Modules\TourBooking\App\Services\RoomPricing;

/**
 * Round 2, Bug 4 — «Цены по длительности и скидки» per sanatorium:
 *  - length-of-stay prices per room: season × adults × nights range → price per room per night;
 *  - stay discount rules: nights range and/or early booking (days before arrival) → percent, rules add up.
 */
final class StayPricingController extends Controller
{
    public function index(Request $request): View
    {
        $ready = RoomPricing::stayReady();

        $services = Service::query()
            ->with('translation:id,service_id,locale,title')
            ->whereHas('rooms')
            ->orderBy('title')
            ->get(['id', 'title']);

        $service = $request->filled('service_id') ? $services->firstWhere('id', (int) $request->input('service_id')) : null;

        $counts = collect();
        $rooms = collect();
        $rules = collect();
        $seasons = Season::query()->where('status', true)->orderBy('start_date')->get();
        if ($ready) {
            $tierRooms = RoomStayTier::query()->selectRaw('room_id')->distinct()->pluck('room_id');
            $tierServices = ServiceRoom::query()->whereIn('id', $tierRooms)->pluck('service_id');
            $ruleServices = ServiceStayDiscount::query()->where('status', true)->pluck('service_id');
            $counts = $tierServices->concat($ruleServices)->countBy();
            if ($service) {
                $rooms = ServiceRoom::query()->where('service_id', $service->id)->with('stayTiers')->orderBy('display_order')->get();
                $rules = ServiceStayDiscount::query()->where('service_id', $service->id)->orderByRaw('min_days_before is not null')->orderBy('min_nights')->get();
            }
        }

        return view('tourbooking::admin.stay-pricing.index', compact('ready', 'services', 'service', 'rooms', 'rules', 'seasons', 'counts'));
    }

    /** One form row = one nights range for one room and season, with prices for 1–4 adults and a child. */
    public function storeTier(Request $request, Service $service): RedirectResponse
    {
        $data = $request->validate([
            'room_id'    => 'required|integer',
            'season_id'  => 'nullable|integer',
            'min_nights' => 'required|integer|min:1|max:365',
            'max_nights' => 'nullable|integer|min:1|max:365|gte:min_nights',
            'price'      => 'required|array',
            'price.*'    => 'nullable|numeric|min:0|max:1000000',
        ]);
        $room = ServiceRoom::query()->where('service_id', $service->id)->findOrFail((int) $data['room_id']);
        $seasonId = ! empty($data['season_id']) ? (int) $data['season_id'] : null;
        $min = (int) $data['min_nights'];
        $max = isset($data['max_nights']) && $data['max_nights'] !== null && $data['max_nights'] !== '' ? (int) $data['max_nights'] : null;

        $prices = [];
        foreach ([1, 2, 3, 4, 0] as $adults) {
            $raw = $data['price'][$adults] ?? null;
            if ($raw !== null && $raw !== '' && (float) $raw > 0) {
                $prices[$adults] = round((float) $raw, 2);
            }
        }
        if (! $prices) {
            return back()->withInput()->withErrors(['price' => 'Укажите хотя бы одну цену.']);
        }

        // Overlaps are blocked; gaps are allowed (a night count without a range = «цену уточнит менеджер»)
        foreach ($prices as $adults => $price) {
            $overlap = RoomStayTier::query()
                ->where('room_id', $room->id)
                ->where('adults', $adults)
                ->when($seasonId === null, fn ($q) => $q->whereNull('season_id'), fn ($q) => $q->where('season_id', $seasonId))
                ->where('min_nights', '<=', $max ?? 100000)
                ->where(fn ($q) => $q->whereNull('max_nights')->orWhere('max_nights', '>=', $min))
                ->first();
            if ($overlap) {
                return back()->withInput()->withErrors(['min_nights' => 'Диапазон ночей пересекается с уже введённым: ' . $overlap->rangeLabel() . ' ночей (' . ($adults === 0 ? 'ребёнок' : $adults . ' взр.') . ').']);
            }
        }

        foreach ($prices as $adults => $price) {
            RoomStayTier::create([
                'room_id' => $room->id, 'season_id' => $seasonId, 'adults' => $adults,
                'min_nights' => $min, 'max_nights' => $max, 'price' => $price,
            ]);
        }
        FromPrice::flush();

        return redirect()->route('admin.tourbooking.stay-pricing.index', ['service_id' => $service->id])->with('success', 'Цены по длительности сохранены.');
    }

    /** Deletes one nights range (all occupancies) of a room and season. */
    public function destroyTier(Request $request, Service $service): RedirectResponse
    {
        $data = $request->validate([
            'room_id' => 'required|integer', 'season_id' => 'nullable|integer',
            'min_nights' => 'required|integer', 'max_nights' => 'nullable|integer',
        ]);
        $room = ServiceRoom::query()->where('service_id', $service->id)->findOrFail((int) $data['room_id']);
        RoomStayTier::query()->where('room_id', $room->id)
            ->when(empty($data['season_id']), fn ($q) => $q->whereNull('season_id'), fn ($q) => $q->where('season_id', (int) $data['season_id']))
            ->where('min_nights', (int) $data['min_nights'])
            ->when(empty($data['max_nights']), fn ($q) => $q->whereNull('max_nights'), fn ($q) => $q->where('max_nights', (int) $data['max_nights']))
            ->delete();
        FromPrice::flush();

        return redirect()->route('admin.tourbooking.stay-pricing.index', ['service_id' => $service->id])->with('success', 'Диапазон удалён.');
    }

    public function storeRule(Request $request, Service $service): RedirectResponse
    {
        $data = $request->validate([
            'min_nights'      => 'nullable|integer|min:1|max:365',
            'max_nights'      => 'nullable|integer|min:1|max:365',
            'min_days_before' => 'nullable|integer|min:1|max:730',
            'percentage'      => 'required|numeric|min:0.01|max:100',
            'note'            => 'nullable|string|max:255',
        ]);
        if (empty($data['min_nights']) && empty($data['max_nights']) && empty($data['min_days_before'])) {
            return back()->withInput()->withErrors(['min_nights' => 'Укажите ночи и/или раннее бронирование.']);
        }
        if (! empty($data['min_nights']) && ! empty($data['max_nights']) && $data['max_nights'] < $data['min_nights']) {
            return back()->withInput()->withErrors(['max_nights' => '«До» должно быть не меньше «от».']);
        }
        ServiceStayDiscount::create([
            'service_id' => $service->id,
            'min_nights' => $data['min_nights'] ?? null,
            'max_nights' => $data['max_nights'] ?? null,
            'min_days_before' => $data['min_days_before'] ?? null,
            'percentage' => $data['percentage'],
            'note' => $data['note'] ?? null,
            'status' => $request->boolean('status', true),
        ]);

        return redirect()->route('admin.tourbooking.stay-pricing.index', ['service_id' => $service->id])->with('success', 'Скидка добавлена.');
    }

    public function toggleRule(ServiceStayDiscount $rule): RedirectResponse
    {
        $rule->update(['status' => ! $rule->status]);

        return redirect()->route('admin.tourbooking.stay-pricing.index', ['service_id' => $rule->service_id]);
    }

    public function destroyRule(ServiceStayDiscount $rule): RedirectResponse
    {
        $serviceId = $rule->service_id;
        $rule->delete();

        return redirect()->route('admin.tourbooking.stay-pricing.index', ['service_id' => $serviceId])->with('success', 'Скидка удалена.');
    }
}
