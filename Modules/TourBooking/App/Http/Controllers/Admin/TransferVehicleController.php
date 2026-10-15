<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Modules\TourBooking\App\Models\TransferVehicle;

final class TransferVehicleController extends Controller
{
    public function index(): View
    {
        $vehicles = TransferVehicle::orderBy('display_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('tourbooking::admin.transfer-vehicles.index', compact('vehicles'));
    }

    public function create(): View
    {
        return view('tourbooking::admin.transfer-vehicles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'passenger_capacity' => 'required|integer|min:1|max:50',
            'luggage_capacity' => 'required|integer|min:0|max:20',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'base_price' => 'required|numeric|min:0',
            'price_per_km' => 'nullable|numeric|min:0',
            'status' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = $request->has('status');
        $validated['display_order'] = $validated['display_order'] ?? 0;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/transfer-vehicles'), $imageName);
            $validated['image'] = 'uploads/transfer-vehicles/' . $imageName;
        }

        if ($request->hasFile('gallery')) {
            $galleryPaths = [];
            foreach ($request->file('gallery') as $galleryImage) {
                $galleryName = time() . '_' . Str::random(10) . '.' . $galleryImage->getClientOriginalExtension();
                $galleryImage->move(public_path('uploads/transfer-vehicles/gallery'), $galleryName);
                $galleryPaths[] = 'uploads/transfer-vehicles/gallery/' . $galleryName;
            }
            $validated['gallery'] = $galleryPaths;
        }

        if (isset($validated['features'])) {
            $validated['features'] = array_filter($validated['features']);
        }

        $vehicle = TransferVehicle::create($validated);
        $vehicle->syncSeasonPrices($request->input('season_prices'));

        return redirect()
            ->route('admin.tourbooking.transfer-vehicles.index')
            ->with(['message' => 'Транспорт успешно создан', 'alert-type' => 'success']);
    }

    public function edit(TransferVehicle $transferVehicle): View
    {
        return view('tourbooking::admin.transfer-vehicles.edit', compact('transferVehicle'));
    }

    public function update(Request $request, TransferVehicle $transferVehicle): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'passenger_capacity' => 'required|integer|min:1|max:50',
            'luggage_capacity' => 'required|integer|min:0|max:20',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'base_price' => 'required|numeric|min:0',
            'price_per_km' => 'nullable|numeric|min:0',
            'status' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = $request->has('status');
        $validated['display_order'] = $validated['display_order'] ?? 0;

        if ($request->hasFile('image')) {
            if ($transferVehicle->image && file_exists(public_path($transferVehicle->image))) {
                unlink(public_path($transferVehicle->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/transfer-vehicles'), $imageName);
            $validated['image'] = 'uploads/transfer-vehicles/' . $imageName;
        }

        if ($request->hasFile('gallery')) {
            $galleryPaths = $transferVehicle->gallery ?? [];
            foreach ($request->file('gallery') as $galleryImage) {
                $galleryName = time() . '_' . Str::random(10) . '.' . $galleryImage->getClientOriginalExtension();
                $galleryImage->move(public_path('uploads/transfer-vehicles/gallery'), $galleryName);
                $galleryPaths[] = 'uploads/transfer-vehicles/gallery/' . $galleryName;
            }
            $validated['gallery'] = $galleryPaths;
        }

        if (isset($validated['features'])) {
            $validated['features'] = array_filter($validated['features']);
        }

        $transferVehicle->update($validated);
        $transferVehicle->syncSeasonPrices($request->input('season_prices'));

        return redirect()
            ->route('admin.tourbooking.transfer-vehicles.index')
            ->with(['message' => 'Транспорт успешно обновлён', 'alert-type' => 'success']);
    }

    public function destroy(TransferVehicle $transferVehicle): RedirectResponse
    {
        if ($transferVehicle->image && file_exists(public_path($transferVehicle->image))) {
            unlink(public_path($transferVehicle->image));
        }
        
        if ($transferVehicle->gallery) {
            foreach ($transferVehicle->gallery as $galleryImage) {
                if (file_exists(public_path($galleryImage))) {
                    unlink(public_path($galleryImage));
                }
            }
        }

        $transferVehicle->delete();

        return redirect()
            ->route('admin.tourbooking.transfer-vehicles.index')
            ->with(['message' => 'Транспорт успешно удалён', 'alert-type' => 'success']);
    }

    public function updateStatus(TransferVehicle $transferVehicle): RedirectResponse
    {
        $transferVehicle->update(['status' => !$transferVehicle->status]);

        return redirect()->back()->with(['message' => 'Статус успешно обновлён', 'alert-type' => 'success']);
    }

    public function deleteGalleryImage(Request $request, TransferVehicle $transferVehicle): RedirectResponse
    {
        $imageIndex = $request->input('image_index');
        $gallery = $transferVehicle->gallery ?? [];
        
        if (isset($gallery[$imageIndex])) {
            if (file_exists(public_path($gallery[$imageIndex]))) {
                unlink(public_path($gallery[$imageIndex]));
            }
            
            unset($gallery[$imageIndex]);
            $transferVehicle->update(['gallery' => array_values($gallery)]);
        }

        return redirect()->back()->with(['message' => 'Изображение удалено', 'alert-type' => 'success']);
    }
}
