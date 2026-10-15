<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\TourBooking\App\Models\TransferRoute;

final class TransferRouteController extends Controller
{
    /**
     * Display a listing of transfer routes.
     */
    public function index(): View
    {
        $routes = TransferRoute::orderBy('display_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('tourbooking::admin.transfer-routes.index', compact('routes'));
    }

    /**
     * Show the form for creating a new transfer route.
     */
    public function create(): View
    {
        return view('tourbooking::admin.transfer-routes.create');
    }

    /**
     * Store a newly created transfer route.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'from_location' => 'required|string|max:255',
            'to_location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'nullable|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:0',
            'status' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);

        $validated['status'] = $request->has('status');
        $validated['display_order'] = $validated['display_order'] ?? 0;

        $route = TransferRoute::create($validated);
        $route->syncSeasonPrices($request->input('season_prices'));

        $notification = [
            'message' => __('translate.Transfer route created successfully'),
            'alert-type' => 'success'
        ];

        return redirect()
            ->route('admin.tourbooking.transfer-routes.index')
            ->with($notification);
    }

    /**
     * Show the form for editing the specified transfer route.
     */
    public function edit(TransferRoute $transferRoute): View
    {
        return view('tourbooking::admin.transfer-routes.edit', compact('transferRoute'));
    }

    /**
     * Update the specified transfer route.
     */
    public function update(Request $request, TransferRoute $transferRoute): RedirectResponse
    {
        $validated = $request->validate([
            'from_location' => 'required|string|max:255',
            'to_location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'nullable|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:0',
            'status' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);

        $validated['status'] = $request->has('status');
        $validated['display_order'] = $validated['display_order'] ?? 0;

        $transferRoute->update($validated);
        $transferRoute->syncSeasonPrices($request->input('season_prices'));

        $notification = [
            'message' => __('translate.Transfer route updated successfully'),
            'alert-type' => 'success'
        ];

        return redirect()
            ->route('admin.tourbooking.transfer-routes.index')
            ->with($notification);
    }

    /**
     * Remove the specified transfer route.
     */
    public function destroy(TransferRoute $transferRoute): RedirectResponse
    {
        $transferRoute->delete();

        $notification = [
            'message' => __('translate.Transfer route deleted successfully'),
            'alert-type' => 'success'
        ];

        return redirect()
            ->route('admin.tourbooking.transfer-routes.index')
            ->with($notification);
    }

    /**
     * Update status of transfer route.
     */
    public function updateStatus(Request $request, TransferRoute $transferRoute): RedirectResponse
    {
        $transferRoute->update([
            'status' => !$transferRoute->status
        ]);

        $notification = [
            'message' => __('translate.Status updated successfully'),
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    /**
     * Get active transfer routes for API/AJAX.
     */
    public function getActiveRoutes()
    {
        $routes = TransferRoute::active()
            ->ordered()
            ->select('id', 'from_location', 'to_location', 'base_price', 'duration_minutes')
            ->get()
            ->map(function($route) {
                return [
                    'id' => $route->id,
                    'name' => $route->route_name,
                    'from' => $route->from_location,
                    'to' => $route->to_location,
                    'price' => $route->base_price,
                    'duration' => $route->duration_minutes,
                ];
            });

        return response()->json([
            'success' => true,
            'routes' => $routes
        ]);
    }
}