<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\TourBooking\App\Models\Service;
use Modules\TourBooking\App\Models\ServiceDateDiscount;
use Modules\TourBooking\App\Services\RoomPricing;

/**
 * "Скидки по датам": each sanatorium has its own list of date ranges with a percent.
 * Room prices inside a range drop by that percent automatically (RoomPricing::quote()).
 */
final class DateDiscountController extends Controller
{
    public function index(Request $request): View
    {
        $ready = RoomPricing::discountsReady();

        $services = Service::query()
            ->with('translation:id,service_id,locale,title')
            ->whereHas('rooms')
            ->orderBy('title')
            ->get(['id', 'title']);

        $service = null;
        if ($request->filled('service_id')) {
            $service = $services->firstWhere('id', (int) $request->input('service_id'));
        }

        $discounts = collect();
        $counts = collect();
        if ($ready) {
            $counts = ServiceDateDiscount::query()
                ->where('status', true)
                ->whereDate('end_date', '>=', now()->toDateString())
                ->selectRaw('service_id, count(*) as c')
                ->groupBy('service_id')
                ->pluck('c', 'service_id');
            if ($service) {
                $discounts = ServiceDateDiscount::query()
                    ->where('service_id', $service->id)
                    ->orderByDesc('start_date')
                    ->get();
            }
        }

        $editing = null;
        if ($service && $request->filled('edit')) {
            $editing = $discounts->firstWhere('id', (int) $request->input('edit'));
        }

        return view('tourbooking::admin.date-discounts.index', compact('ready', 'services', 'service', 'discounts', 'counts', 'editing'));
    }

    public function store(Request $request, Service $service): RedirectResponse
    {
        $discount = new ServiceDateDiscount(['service_id' => $service->id]);

        return $this->save($request, $service, $discount, __('translate.Discount added.'));
    }

    public function update(Request $request, ServiceDateDiscount $discount): RedirectResponse
    {
        return $this->save($request, $discount->service()->firstOrFail(), $discount, __('translate.Discount updated.'));
    }

    public function destroy(ServiceDateDiscount $discount): RedirectResponse
    {
        $serviceId = $discount->service_id;
        $discount->delete();

        return redirect()->route('admin.tourbooking.date-discounts.index', ['service_id' => $serviceId])
            ->with('success', __('translate.Discount deleted.'));
    }

    private function save(Request $request, Service $service, ServiceDateDiscount $discount, string $message): RedirectResponse
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'percentage' => 'required|numeric|min:0.01|max:100',
            'note'       => 'nullable|string|max:255',
        ]);

        $discount->fill($validated);
        $discount->service_id = $service->id;
        $discount->status = $request->boolean('status');

        if ($overlap = $discount->findOverlap()) {
            return back()->withInput()->withErrors([
                'start_date' => __('translate.Dates overlap with another discount of this sanatorium') . ': '
                    . $overlap->start_date->format('d.m.Y') . ' — ' . $overlap->end_date->format('d.m.Y')
                    . ' (' . rtrim(rtrim((string) $overlap->percentage, '0'), '.') . '%).',
            ]);
        }

        $discount->save();

        return redirect()->route('admin.tourbooking.date-discounts.index', ['service_id' => $service->id])
            ->with('success', $message);
    }
}
