<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Http\Controllers\Admin;

use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Modules\TourBooking\App\Models\Destination;
use Modules\TourBooking\App\Models\Service;

final class DestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $destinations = Destination::with('translation')
            ->withCount('services')
            ->latest()
            ->paginate(15);

        return view('tourbooking::admin.destinations.index', compact('destinations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('tourbooking::admin.destinations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:destinations,slug|max:255',
            'description' => 'nullable|string',
            'country' => 'required|string|max:100',
            'region' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'latitude' => 'nullable|string|max:30',
            'longitude' => 'nullable|string|max:30',
            'status' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'show_on_homepage' => 'nullable|boolean',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = FileUploadHelper::uploadImage($request->file('image'), 'destination');
        }

        if ($request->hasFile('svg')) {
            $validated['svg_image'] = FileUploadHelper::uploadImage($request->file('svg'), 'destination');
        }

        $validated['status'] = $request->has('status');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['show_on_homepage'] = $request->has('show_on_homepage');
        $validated['user_id'] = auth()->user()->id;
        $validated['meta_title'] = $request->meta_title ?? null;
        $validated['meta_keywords'] = $request->meta_keywords ?? null;
        $validated['meta_description'] = $request->meta_description ?? null;
        $validated['tags'] = $request->tags ?? null;

        Destination::create($validated);

        return redirect()->route('admin.tourbooking.destinations.index')
            ->with('success', 'Destination created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Destination $destination): View
    {
        $destination->load(['services.serviceType']);

        return view('tourbooking::admin.destinations.show', compact('destination'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Destination $destination): View
    {
        return view('tourbooking::admin.destinations.edit', compact('destination'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Destination $destination): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:destinations,slug,' . $destination->id,
            'description' => 'nullable|string',
            'country' => 'required|string|max:100',
            'region' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'latitude' => 'nullable|string|max:30',
            'longitude' => 'nullable|string|max:30',
            'status' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'show_on_homepage' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|string|max:255',
        ]);


        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($destination->image !== null) {
                FileUploadHelper::deleteImage($destination->image);
            }
            $validated['image'] = FileUploadHelper::uploadImage($request->file('image'), 'destination');
        }

        if ($request->hasFile('svg')) {
            // Delete old image if exists
            if ($destination->svg_image !== null) {
                FileUploadHelper::deleteImage($destination->svg_image);
            }
            $validated['svg_image'] = FileUploadHelper::uploadImage($request->file('svg'), 'destination');
        }

        $validated['status'] = $request->has('status');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['show_on_homepage'] = $request->has('show_on_homepage');
        $validated['meta_title'] = $request->meta_title ?? null;
        $validated['meta_keywords'] = $request->meta_keywords ?? null;
        $validated['meta_description'] = $request->meta_description ?? null;
        $validated['tags'] = $request->tags ?? null;

        $this->fillSeoContent($request, $validated, $destination);

        $destination->update($validated);

        return redirect()->route('admin.tourbooking.destinations.index')
            ->with('success', 'Destination updated successfully.');
    }

    /**
     * Task 4: H1, intro text, SEO text, FAQ and hero image of the destination page.
     * Skipped until the migration has added the columns, so the form keeps working.
     *
     * @param array<string, mixed> $validated
     */
    private function fillSeoContent(Request $request, array &$validated, ?Destination $destination = null): void
    {
        if (!Schema::hasColumn('destinations', 'h1')) {
            return;
        }

        $request->validate([
            'h1' => 'nullable|string|max:255',
            'intro_text' => 'nullable|string',
            'seo_text' => 'nullable|string',
            'faq_q' => 'nullable|array',
            'faq_q.*' => 'nullable|string|max:500',
            'faq_a' => 'nullable|array',
            'faq_a.*' => 'nullable|string|max:5000',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $validated['h1'] = trim((string) $request->input('h1', '')) ?: null;
        $validated['intro_text'] = $request->input('intro_text') ?: null;
        $validated['seo_text'] = $request->input('seo_text') ?: null;

        $questions = (array) $request->input('faq_q', []);
        $answers = (array) $request->input('faq_a', []);
        $faq = [];
        foreach ($questions as $i => $q) {
            $q = trim((string) $q);
            $a = trim((string) ($answers[$i] ?? ''));
            if ($q !== '' || $a !== '') {
                $faq[] = ['q' => $q, 'a' => $a];
            }
        }
        $validated['faq'] = $faq ?: null;

        if ($request->hasFile('hero_image')) {
            if ($destination && $destination->hero_image) {
                FileUploadHelper::deleteImage($destination->hero_image);
            }
            $validated['hero_image'] = FileUploadHelper::uploadImage($request->file('hero_image'), 'destination');
        } elseif ($request->boolean('remove_hero_image') && $destination && $destination->hero_image) {
            FileUploadHelper::deleteImage($destination->hero_image);
            $validated['hero_image'] = null;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Destination $destination): RedirectResponse
    {

        // Check if there are any services associated with this destination
        if (Service::where('destination_id', $destination->id)->exists()) {
            return redirect()->route('admin.tourbooking.destinations.index')
                ->with('error', 'Cannot delete destination because it is being used by one or more services.');
        }

        // Delete image if exists
        if ($destination->image !== null) {
            FileUploadHelper::deleteImage($destination->image);
        }

        // Delete image if exists
        if ($destination?->svg_image !== null) {
            FileUploadHelper::deleteImage($destination?->svg_image);
        }

        $destination->delete();

        return redirect()->route('admin.tourbooking.destinations.index')
            ->with('success', 'Destination deleted successfully.');
    }

    public function updateStatus(Destination $destination): RedirectResponse|JsonResponse
    {
        $destination->update(['status' => !$destination->status]);

        $notify_message = trans('translate.Status updated');
        $notify_message = array('message' => $notify_message, 'alert-type' => 'success');

        return response()->json($notify_message);
    }

    public function updateFeatured(Destination $destination): RedirectResponse|JsonResponse
    {
        $destination->update(['is_featured' => !$destination->is_featured]);

        $notify_message = trans('translate.Featured updated');
        $notify_message = array('message' => $notify_message, 'alert-type' => 'success');

        return response()->json($notify_message);
    }
}
