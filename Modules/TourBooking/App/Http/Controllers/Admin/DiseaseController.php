<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Http\Controllers\Admin;

use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Modules\TourBooking\App\Models\Disease;
use Modules\TourBooking\App\Models\Service;

final class DiseaseController extends Controller
{
    private const IMAGE_FOLDER = 'images/diseases';

    public function index(): View
    {
        $diseases = Disease::withCount('sanatoriums')->ordered()->paginate(30);

        return view('tourbooking::admin.diseases.index', compact('diseases'));
    }

    public function create(): View
    {
        $services = Service::orderBy('title')->get(['id', 'title']);

        return view('tourbooking::admin.diseases.create', compact('services'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['slug'] = $this->makeSlug($validated['slug'] ?? null, $validated['name_ru']);

        if ($request->hasFile('image')) {
            $validated['image'] = FileUploadHelper::uploadImage($request->file('image'), 'disease', self::IMAGE_FOLDER);
        }

        $sanatoriums = $validated['sanatoriums'] ?? [];
        unset($validated['sanatoriums']);

        $disease = Disease::create($validated);
        $disease->sanatoriums()->sync($sanatoriums);

        return redirect()->route('admin.tourbooking.diseases.index')
            ->with('success', __('translate.Disease created successfully'));
    }

    public function edit(Disease $disease): View
    {
        $services = Service::orderBy('title')->get(['id', 'title']);
        $assigned = $disease->sanatoriums()->pluck('services.id')->all();

        return view('tourbooking::admin.diseases.edit', compact('disease', 'services', 'assigned'));
    }

    public function update(Request $request, Disease $disease): RedirectResponse
    {
        $validated = $this->validated($request, $disease);
        $validated['slug'] = $this->makeSlug($validated['slug'] ?? null, $validated['name_ru'], $disease->id);

        if ($request->hasFile('image')) {
            if ($disease->image) {
                FileUploadHelper::deleteImage($disease->image);
            }
            $validated['image'] = FileUploadHelper::uploadImage($request->file('image'), 'disease', self::IMAGE_FOLDER);
        }

        $sanatoriums = $validated['sanatoriums'] ?? [];
        unset($validated['sanatoriums']);

        $disease->update($validated);
        $disease->sanatoriums()->sync($sanatoriums);

        return redirect()->route('admin.tourbooking.diseases.index')
            ->with('success', __('translate.Disease updated successfully'));
    }

    public function destroy(Disease $disease): RedirectResponse
    {
        if ($disease->image) {
            FileUploadHelper::deleteImage($disease->image);
        }

        $disease->delete();

        return redirect()->route('admin.tourbooking.diseases.index')
            ->with('success', __('translate.Disease deleted successfully'));
    }

    private function validated(Request $request, ?Disease $disease = null): array
    {
        $validated = $request->validate([
            'slug' => 'nullable|string|max:255|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            'name_ru' => 'required|string|max:255',
            'name_az' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description_ru' => 'nullable|string|max:5000',
            'description_az' => 'nullable|string|max:5000',
            'description_en' => 'nullable|string|max:5000',
            'sort_order' => 'nullable|integer|min:0|max:65535',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sanatoriums' => 'nullable|array',
            'sanatoriums.*' => 'integer|exists:services,id',
        ]);

        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['is_active'] = $request->has('is_active');

        return $validated;
    }

    /**
     * URL-safe slug: the entered one, otherwise generated from the Russian name; always unique.
     */
    private function makeSlug(?string $slug, string $nameRu, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug ?: $nameRu, '-', 'ru') ?: 'disease';
        $candidate = $base;
        $i = 2;

        while (Disease::where('slug', $candidate)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $candidate = $base . '-' . $i++;
        }

        return $candidate;
    }
}
