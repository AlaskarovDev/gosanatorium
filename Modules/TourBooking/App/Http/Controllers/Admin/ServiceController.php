<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Http\Controllers\Admin;

use App\Enums\Language as EnumsLanguage;
use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Modules\TourBooking\App\Http\Requests\ServiceRequest;
use Modules\TourBooking\App\Models\Availability;
use Modules\TourBooking\App\Models\ExtraCharge;
use Modules\TourBooking\App\Models\Service;
use Modules\TourBooking\App\Models\ServiceMedia;
use Modules\TourBooking\App\Models\ServicePriceList;
use Modules\TourBooking\App\Models\ServiceTranslation;
use Modules\TourBooking\App\Models\ServiceType;
use Modules\TourBooking\App\Models\TourItinerary;
use Modules\TourBooking\App\Repositories\ServiceRepository;
use Modules\TourBooking\App\Repositories\ServiceTypeRepository;
use Modules\Language\App\Models\Language;
use Illuminate\Http\JsonResponse;
use Modules\TourBooking\App\Models\Amenity;
use Modules\TourBooking\App\Models\Destination;
use Modules\TourBooking\App\Models\Review;
use Modules\TourBooking\App\Models\ServiceRoom;
use Modules\TourBooking\App\Models\ServiceRoomMedia;
use Modules\TourBooking\App\Models\ServiceTreatmentItem;

final class ServiceController extends Controller
{
    public function __construct(
        private ServiceRepository $serviceRepository,
        private ServiceTypeRepository $serviceTypeRepository
    ) {}

    /**
     * Clean HTML list content from TinyMCE.
     * Removes empty <li> elements and flattens nested <ul>/<ol> structures.
     */
private function cleanHtmlList($html): ?string
{
    if (empty($html)) {
        return null;
    }

    if (is_array($html)) {
        $html = implode('', $html);
    }

    if (!is_string($html)) {
        return null;
    }

    // Remove li that contains only whitespace, &nbsp;, <br> or non-breaking spaces
    $html = preg_replace('/<li[^>]*>(\s|&nbsp;|&#160;|\xC2\xA0|<br\s*\/?>)*<\/li>/i', '', $html);

    // Flatten nested ul/ol inside li
    $html = preg_replace('/<li[^>]*>\s*(<ul[^>]*>)(.*?)(<\/ul>)\s*<\/li>/is', '$2', $html);
    $html = preg_replace('/<li[^>]*>\s*(<ol[^>]*>)(.*?)(<\/ol>)\s*<\/li>/is', '$2', $html);

    // Flatten double-nested ul/ol
    $html = preg_replace('/<ul[^>]*>\s*<ul[^>]*>/i', '<ul>', $html);
    $html = preg_replace('/<\/ul>\s*<\/ul>/i', '</ul>', $html);

    // Clean again after flattening
    $html = preg_replace('/<li[^>]*>(\s|&nbsp;|&#160;|\xC2\xA0|<br\s*\/?>)*<\/li>/i', '', $html);

    // Remove empty ul/ol
    $html = preg_replace('/<ul[^>]*>\s*<\/ul>/i', '', $html);
    $html = preg_replace('/<ol[^>]*>\s*<\/ol>/i', '', $html);

    $html = trim($html);

    return $html ?: null;
}

    /**
     * Servisin mətnlərini mənbə dildən seçilmiş hədəf dilə Claude ilə tərcümə edir
     * və hədəf dilin tərcümə qeydinə yazır (manual düymə ilə çağırılır).
     */
    public function translateService(Request $request, Service $service): JsonResponse
    {
        $request->validate([
            'source' => 'required|string|max:10',
            'target' => 'required|string|max:10',
        ]);

        if (! \App\Services\ClaudeTranslator::enabled()) {
            return response()->json(['success' => false, 'message' => trans('translate.Translation API not configured')], 422);
        }

        $source = $request->input('source');
        $target = $request->input('target');

        if ($source === $target) {
            return response()->json(['success' => false, 'message' => trans('translate.Source and target languages are the same')], 422);
        }

        $src = ServiceTranslation::where('service_id', $service->id)->where('locale', $source)->first();
        if (! $src) {
            return response()->json(['success' => false, 'message' => trans('translate.No content found in source language')], 422);
        }

        $fields = array_filter([
            'title'             => $src->title,
            'short_description' => $src->short_description,
            'description'       => $src->description,
            'seo_title'         => $src->seo_title,
            'seo_description'   => $src->seo_description,
            'seo_keywords'      => $src->seo_keywords,
        ], fn ($v) => is_string($v) && trim($v) !== '');

        if (empty($fields)) {
            return response()->json(['success' => false, 'message' => trans('translate.Nothing to translate')], 422);
        }

        $tr = \App\Services\ClaudeTranslator::translate($fields, $source, [$target]);

        if (empty($tr[$target]) || ! is_array($tr[$target])) {
            return response()->json(['success' => false, 'message' => trans('translate.Translation failed, please try again')], 500);
        }

        $this->serviceRepository->saveTranslation($service, $target, $tr[$target]);

        return response()->json(['success' => true, 'message' => trans('translate.Translation ready')]);
    }

    /**
     * Display a listing of services.
     */
    public function index(): View
    {
        $services = $this->serviceRepository->getAllFilters();

        return view('tourbooking::admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create(): View
    {

        $amenities = Amenity::where('status', true)->with('translation:id,amenity_id,lang_code,name')->get();

        $serviceTypes = $this->serviceTypeRepository->getActive();

        $enum_languages = EnumsLanguage::cases();

        $destinations = Destination::select('id', 'name')->where('status', true)->get();

        return view('tourbooking::admin.services.create', compact('serviceTypes', 'amenities', 'enum_languages', 'destinations'));
    }
    
    
public function storeRoom(Request $request, Service $service): RedirectResponse
{
    // Validation rules - 'name' istifadə olunur (room_name deyil!)
    $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price_per_night' => 'required|numeric|min:0',
        'discount_price' => 'nullable|numeric|min:0',
        'base_price_for' => 'nullable|integer|min:1|max:50',
        'room_size' => 'nullable|numeric|min:0',
        'max_guests' => 'required|integer|min:1',
        'bed_type' => 'nullable|string|max:100',
        'quantity' => 'required|integer|min:1',
        'low_stock_show' => 'nullable|boolean',
        'low_stock_text' => 'nullable|string|max:255',
'features' => 'nullable|string',
        'features.*' => 'nullable|string|max:255',
        'video_url' => 'nullable|url',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'extras' => 'nullable|array',
        'extras.*.name' => 'nullable|string|max:255',
        'extras.*.type' => 'nullable|in:adult,child,optional',
        'extras.*.price' => 'nullable|numeric|min:0',
    ];

    // Video varsa thumbnail məcburidir
    if ($request->filled('video_url')) {
        $rules['thumbnail'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
    } else {
        $rules['thumbnail'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
    }

    $messages = [
        'name.required' => trans('translate.Room name is required'),
        'thumbnail.required' => trans('translate.Thumbnail is required when adding a video'),
    ];

    $request->validate($rules, $messages);

    $data = $request->except(['_token', 'thumbnail', 'images', 'features', 'extras', 'season_prices']);
    $data['service_id'] = $service->id;
    $data['status'] = $request->boolean('status');
    $data['low_stock_show'] = $request->boolean('low_stock_show');
    $data['base_price_for'] = $request->input('base_price_for') ?: ($request->input('max_guests') ?: 2);

    // Handle features array - boş dəyərləri filtr et
    // Handle features - comma-separated string to JSON array
    if ($request->has('features') && !empty($request->input('features'))) {
        $features = array_values(array_filter(array_map('trim', explode(',', $request->input('features')))));
        $data['features'] = !empty($features) ? json_encode($features) : null;
    } else {
        $data['features'] = null;
    }

    // Handle thumbnail upload
    $thumbnailPath = null;
    if ($request->hasFile('thumbnail')) {
        $thumbnailPath = FileUploadHelper::uploadImage(
            $request->file('thumbnail'),
            'room',
            'uploads/rooms'
        );
        $data['thumbnail'] = $thumbnailPath;
    }

    // Set display order
    $data['display_order'] = ServiceRoom::where('service_id', $service->id)->count() + 1;

    // Create room
    $room = ServiceRoom::create($data);

    // =====================================================
    // THUMBNAIL-ı service_room_media tablosuna da yaz!
    // =====================================================
    if ($thumbnailPath) {
        ServiceRoomMedia::create([
            'service_room_id' => $room->id,
            'file_path' => $thumbnailPath,
            'media_type' => 'image',
            'is_thumbnail' => true,
            'display_order' => 0,
        ]);
    }

    // Handle gallery images
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $index => $image) {
            $path = FileUploadHelper::uploadImage($image, 'room_gallery', 'uploads/rooms/gallery');
            
            ServiceRoomMedia::create([
                'service_room_id' => $room->id,
                'file_path' => $path,
                'media_type' => 'image',
                'is_thumbnail' => false,
                'display_order' => $index + 1,
            ]);
        }
    }

    // Handle video URL - media table-a əlavə et
    if ($request->filled('video_url')) {
        ServiceRoomMedia::create([
            'service_room_id' => $room->id,
            'file_path' => $request->video_url,
            'media_type' => 'video',
            'is_thumbnail' => false,
            'display_order' => 999,
        ]);
    }

    $room->syncSeasonPrices($request->input('season_prices'));
    $this->syncRoomExtras($room, $request->input('extras'));

    $notify_message = trans('translate.Room added successfully');
    return back()->with(['message' => $notify_message, 'alert-type' => 'success']);
}

/**
 * Replace a room's extras with the supplied list.
 */
private function syncRoomExtras(ServiceRoom $room, $payload): void
{
    $room->extras()->delete();
    if (! is_array($payload)) return;

    $sort = 0;
    foreach ($payload as $row) {
        $name = trim((string) ($row['name'] ?? ''));
        $type = $row['type'] ?? null;
        $price = $row['price'] ?? null;
        if ($name === '' || ! in_array($type, ['adult', 'child', 'optional'], true)) continue;

        $room->extras()->create([
            'name'       => $name,
            'type'       => $type,
            'price'      => is_numeric($price) ? (float) $price : 0,
            'status'     => true,
            'sort_order' => $sort++,
        ]);
    }
}

/**
 * Update an existing room.
 */
public function updateRoom(Request $request, ServiceRoom $room): RedirectResponse
{
    // Validation rules - 'name' istifadə olunur
    $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price_per_night' => 'required|numeric|min:0',
        'discount_price' => 'nullable|numeric|min:0',
        'base_price_for' => 'nullable|integer|min:1|max:50',
        'room_size' => 'nullable|numeric|min:0',
        'max_guests' => 'required|integer|min:1',
        'bed_type' => 'nullable|string|max:100',
        'quantity' => 'required|integer|min:1',
        'low_stock_show' => 'nullable|boolean',
        'low_stock_text' => 'nullable|string|max:255',
'features' => 'nullable|string',
        'features.*' => 'nullable|string|max:255',
        'video_url' => 'nullable|url',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'extras' => 'nullable|array',
        'extras.*.name' => 'nullable|string|max:255',
        'extras.*.type' => 'nullable|in:adult,child,optional',
        'extras.*.price' => 'nullable|numeric|min:0',
    ];

    // Video varsa və thumbnail yoxdursa - yeni thumbnail məcburidir
    if ($request->filled('video_url')) {
        $hasExistingThumbnail = !empty($room->thumbnail);
        $removingThumbnail = $request->boolean('remove_thumbnail');
        
        if (!$hasExistingThumbnail || $removingThumbnail) {
            $rules['thumbnail'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
        } else {
            $rules['thumbnail'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
        }
    } else {
        $rules['thumbnail'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
    }

    $messages = [
        'name.required' => trans('translate.Room name is required'),
        'thumbnail.required' => trans('translate.Thumbnail is required when adding a video'),
    ];

    $request->validate($rules, $messages);

    $data = $request->except(['_token', '_method', 'thumbnail', 'images', 'features', 'remove_thumbnail', 'remove_images', 'extras', 'season_prices']);
    $data['status'] = $request->boolean('status');
    $data['low_stock_show'] = $request->boolean('low_stock_show');
    $data['base_price_for'] = $request->input('base_price_for') ?: ($request->input('max_guests') ?: 2);

    // Handle features array
    // Handle features - comma-separated string to JSON array
    if ($request->has('features') && !empty($request->input('features'))) {
        $features = array_values(array_filter(array_map('trim', explode(',', $request->input('features')))));
        $data['features'] = !empty($features) ? json_encode($features) : null;
    } else {
        $data['features'] = null;
    }

    // Handle thumbnail removal
    if ($request->boolean('remove_thumbnail') && $room->thumbnail) {
        // Köhnə thumbnail-ı fayldan sil
        FileUploadHelper::deleteImage($room->thumbnail);
        
        // Media tablosundan da sil
        ServiceRoomMedia::where('service_room_id', $room->id)
            ->where('is_thumbnail', true)
            ->delete();
            
        $data['thumbnail'] = null;
    }

    // Handle new thumbnail upload
    if ($request->hasFile('thumbnail')) {
        // Köhnə thumbnail-ı sil
        if ($room->thumbnail) {
            FileUploadHelper::deleteImage($room->thumbnail);
            
            // Media tablosundan da sil
            ServiceRoomMedia::where('service_room_id', $room->id)
                ->where('is_thumbnail', true)
                ->delete();
        }
        
        // Yeni thumbnail yüklə
        $thumbnailPath = FileUploadHelper::uploadImage(
            $request->file('thumbnail'),
            'room',
            'uploads/rooms'
        );
        $data['thumbnail'] = $thumbnailPath;
        
        // Media tablosuna da əlavə et
        ServiceRoomMedia::create([
            'service_room_id' => $room->id,
            'file_path' => $thumbnailPath,
            'media_type' => 'image',
            'is_thumbnail' => true,
            'display_order' => 0,
        ]);
    }

    // Update room
    $room->update($data);

    // Handle removed images
    if ($request->has('remove_images') && is_array($request->remove_images)) {
        foreach ($request->remove_images as $mediaId) {
            $media = ServiceRoomMedia::find($mediaId);
            if ($media && $media->service_room_id === $room->id) {
                // Faylı sil
                if ($media->media_type === 'image') {
                    FileUploadHelper::deleteImage($media->file_path);
                }
                $media->delete();
            }
        }
    }

    // Handle new gallery images
    if ($request->hasFile('images')) {
        $lastOrder = $room->media()->max('display_order') ?? 0;
        
        foreach ($request->file('images') as $index => $image) {
            $path = FileUploadHelper::uploadImage($image, 'room_gallery', 'uploads/rooms/gallery');
            
            ServiceRoomMedia::create([
                'service_room_id' => $room->id,
                'file_path' => $path,
                'media_type' => 'image',
                'is_thumbnail' => false,
                'display_order' => $lastOrder + $index + 1,
            ]);
        }
    }

    // Handle video URL
    $existingVideo = $room->media()->where('media_type', 'video')->first();
    
    if ($request->filled('video_url')) {
        if ($existingVideo) {
            $existingVideo->update(['file_path' => $request->video_url]);
        } else {
            ServiceRoomMedia::create([
                'service_room_id' => $room->id,
                'file_path' => $request->video_url,
                'media_type' => 'video',
                'is_thumbnail' => false,
                'display_order' => 999,
            ]);
        }
    } else {
        if ($existingVideo) {
            $existingVideo->delete();
        }
    }

    $room->syncSeasonPrices($request->input('season_prices'));
    $this->syncRoomExtras($room, $request->input('extras'));

    $notify_message = trans('translate.Room updated successfully');
    return back()->with(['message' => $notify_message, 'alert-type' => 'success']);
}

/**
 * Delete a room.
 */
public function destroyRoom(ServiceRoom $room): RedirectResponse
{
    // Delete thumbnail file
    if ($room->thumbnail) {
        FileUploadHelper::deleteImage($room->thumbnail);
    }

    // Delete all media files
    foreach ($room->media as $media) {
        if ($media->media_type === 'image') {
            FileUploadHelper::deleteImage($media->file_path);
        }
        $media->delete();
    }

    $room->delete();

    $notify_message = trans('translate.Room deleted successfully');
    return back()->with(['message' => $notify_message, 'alert-type' => 'success']);
}
    
    
    
    
    
    

    /**
     * Store a newly created service in storage.
     */
    public function store(ServiceRequest $request): RedirectResponse
    {

        $data = $request->validated();

        // Handle JSON fields (included & excluded are now rich text HTML, not JSON)
        $jsonFields = ['facilities', 'rules', 'safety', 'social_links'];
        foreach ($jsonFields as $field) {
            if (isset($data[$field]) && is_array($data[$field])) {
                $data[$field] = json_encode($data[$field]);
            }
        }

        // Convert checkbox values
        $booleanFields = ['deposit_required', 'is_featured', 'is_popular', 'show_on_homepage', 'status', 'is_new', 'is_per_person'];
        foreach ($booleanFields as $field) {
            $data[$field] = isset($data[$field]) ? true : false;
        }

        // Create slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title'], '-', 'en');
        }
        $data['slug'] = trim($data['slug'], '-');
        if (empty($data['slug'])) {
            $data['slug'] = 'service-' . time();
        }
        if ($request->hasFile('doctor_image')) {
    $data['doctor_image'] = FileUploadHelper::uploadImage(
        $request->file('doctor_image'), 'doctor', 'uploads/doctors'
    );
} else {
    unset($data['doctor_image']); // update-da köhnə şəkli saxla
}

        // Save the service
        $service = $this->serviceRepository->create($data);

        // Save the translation for current language
        $this->serviceRepository->saveTranslation(
            $service,
            'ru',
            [
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'short_description' => $data['short_description'] ?? null,
                'seo_title' => $data['seo_title'] ?? null,
                'seo_description' => $data['seo_description'] ?? null,
                'seo_keywords' => $data['seo_keywords'] ?? null,
                'included' => $this->cleanHtmlList($data['included'] ?? null),
                'excluded' => $this->cleanHtmlList($data['excluded'] ?? null),
                'amenities' => $data['amenities'] ?? [],
                'facilities' => $data['facilities'] ?? null,
                'rules' => $data['rules'] ?? null,
                'safety' => $data['safety'] ?? null,
                'cancellation_policy' => $data['cancellation_policy'] ?? null,
            ]
        );

        $service->syncSeasonPrices($request->input('season_prices'));

        $notify_message = trans('translate.Created successfully');
        $notify_message = array('message' => $notify_message, 'alert-type' => 'success');

        return redirect()->route('admin.tourbooking.services.edit', ['service' => $service->id, 'lang_code' => 'ru'])->with($notify_message);
    }

    /**
     * Display the specified service.
     */
    public function show(Service $service): View
    {
        $service->load(['translation', 'serviceType', 'media', 'extraCharges', 'availabilities', 'itineraries']);

        return view('tourbooking::admin.services.show', compact('service'));
    }


    /**
     * Show the form for editing the specified service.
     */
    public function edit(Request $request, Service $service): View
    {
        $lang_code = $request->lang_code ?? 'ru';

        $service->load([
            'media',
            'serviceType',
            'extraCharges',
            'availabilities',
            'treatmentItems',
            'itineraries' => function ($query) {
                $query->orderBy('day_number');
            }
        ]);

        $translation = ServiceTranslation::where([
            'service_id' => $service->id,
            'locale' => $lang_code
        ])->first();

        // Convert JSON fields back to newline-separated strings for textarea display
        // (included & excluded are now rich text HTML, no conversion needed)
        $jsonFields = ['facilities', 'rules', 'safety'];

        foreach ($jsonFields as $field) {
            // Check translation first, then service
            $value = $translation->$field ?? $service->$field ?? null;

            if ($value) {
                if (is_array($value)) {
                    // Already an array, convert to newline-separated string
                    if ($translation && isset($translation->$field)) {
                        $translation->$field = implode("\n", $value);
                    } else {
                        $service->$field = implode("\n", $value);
                    }
                } elseif (is_string($value)) {
                    // Try to decode JSON
                    $decoded = json_decode($value, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        // Convert array back to newline-separated string
                        if ($translation && isset($translation->$field)) {
                            $translation->$field = implode("\n", $decoded);
                        } else {
                            $service->$field = implode("\n", $decoded);
                        }
                    }
                    // If it's already a plain string, leave it as is
                }
            }
        }

        // Convert included/excluded from JSON array to HTML (backward compatibility)
        foreach (['included', 'excluded'] as $field) {
            $value = $translation->$field ?? $service->$field ?? null;

            if ($value) {
                if (is_array($value)) {
                    // Array -> convert to HTML list
                    $html = '<ul>' . implode('', array_map(fn($i) => '<li>' . e($i) . '</li>', $value)) . '</ul>';
                    if ($translation && isset($translation->$field)) {
                        $translation->$field = $html;
                    } else {
                        $service->$field = $html;
                    }
                } elseif (is_string($value)) {
                    $decoded = json_decode($value, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        // JSON string -> convert to HTML list
                        $html = '<ul>' . implode('', array_map(fn($i) => '<li>' . e($i) . '</li>', $decoded)) . '</ul>';
                        if ($translation && isset($translation->$field)) {
                            $translation->$field = $html;
                        } else {
                            $service->$field = $html;
                        }
                    }
                    // If it's already HTML string, leave as is
                }
            }
        }

        $serviceTypes = $this->serviceTypeRepository->getActive();

        $amenities = Amenity::where('status', true)->with('translation:id,amenity_id,lang_code,name')->get();

        $enum_languages = EnumsLanguage::cases();

        $destinations = Destination::select('id', 'name')->where('status', true)->get();

        return view('tourbooking::admin.services.edit', compact('service', 'serviceTypes', 'translation', 'lang_code', 'amenities', 'enum_languages', 'destinations'));
    }

    /**
     * Update the specified service in storage.
     */
    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        $data = $request->validated();

        $lang_code = $request->lang_code ?? 'ru';

        // Handle main service update only if we're editing in the primary language (ru)
        if ($lang_code === 'ru') {
            // Handle JSON fields (included & excluded are now rich text HTML, not JSON)
            $jsonFields = ['facilities', 'rules', 'safety', 'social_links'];
            foreach ($jsonFields as $field) {
                if (isset($data[$field])) {
                    if (is_string($data[$field])) {
                        // Check if it's already valid JSON
                        $decoded = json_decode($data[$field], true);
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            // Not JSON, treat as newline-separated string
                            $lines = array_filter(
                                array_map('trim', explode("\n", $data[$field])),
                                function ($line) {
                                    return $line !== '';
                                }
                            );
                            $data[$field] = json_encode(array_values($lines));
                        }
                        // If it's already valid JSON, keep it as is
                    } elseif (is_array($data[$field])) {
                        $data[$field] = json_encode($data[$field]);
                    }
                }
            }

            // Convert checkbox values
            $booleanFields = ['deposit_required', 'is_featured', 'is_popular', 'show_on_homepage', 'status', 'is_new', 'is_per_person'];
            foreach ($booleanFields as $field) {
                $data[$field] = isset($data[$field]) ? true : false;
            }

            $data['languages'] = $request->languages ?? [];
// Doctor image upload
if ($request->hasFile('doctor_image')) {
    $data['doctor_image'] = FileUploadHelper::uploadImage(
        $request->file('doctor_image'), 'doctor', 'uploads/doctors'
    );
} else {
    unset($data['doctor_image']); // update-da köhnə şəkli saxla
}
            // Slug-u normalize et: kənardakı `-` simvollarını sil
            if (isset($data['slug'])) {
                $data['slug'] = trim(Str::slug($data['slug'], '-', 'en'), '-');
                if (empty($data['slug'])) {
                    $data['slug'] = trim(Str::slug($data['title'] ?? '', '-', 'en'), '-');
                }
                if (empty($data['slug'])) {
                    $data['slug'] = 'service-' . $service->id;
                }
            }
            // Update the service
            $this->serviceRepository->update($service, $data);
        }

        // Update or create translation
        $translationData = [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'short_description' => $data['short_description'] ?? null,
            'seo_title' => $data['seo_title'] ?? null,
            'seo_description' => $data['seo_description'] ?? null,
            'seo_keywords' => $data['seo_keywords'] ?? null,
            'included' => $this->cleanHtmlList($data['included'] ?? null),
            'excluded' => $this->cleanHtmlList($data['excluded'] ?? null),
        ];

        // Add JSON fields to translation if they exist in the request
        $translationJsonFields = ['facilities', 'rules', 'safety', 'cancellation_policy'];
        foreach ($translationJsonFields as $field) {
            if (isset($data[$field])) {
                if (is_string($data[$field])) {
                    // Check if it's already valid JSON
                    $decoded = json_decode($data[$field], true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        // Not JSON, treat as newline-separated string
                        $lines = array_filter(
                            array_map('trim', explode("\n", $data[$field])),
                            function ($line) {
                                return $line !== '';
                            }
                        );
                        $translationData[$field] = json_encode(array_values($lines));
                    } else {
                        // Already valid JSON, use as is
                        $translationData[$field] = $data[$field];
                    }
                } else {
                    $translationData[$field] = $data[$field];
                }
            }
        }


        $translationData['amenities'] = $request->amenities ?? [];

        $this->serviceRepository->saveTranslation($service, $lang_code, $translationData);

        $service->syncSeasonPrices($request->input('season_prices'));

        $notify_message = trans('translate.Updated successfully');
        $notify_message = array('message' => $notify_message, 'alert-type' => 'success');

        return back()->with($notify_message);
    }
    /**
     * Remove the specified service from storage.
     */
    public function destroy(Service $service): RedirectResponse
    {

        if ($service->with('bookings')->count() > 0) {
            return redirect()->back()->with('error', trans('translate.Service has bookings. Cannot delete'));
        }

        // Delete all associated media files
        foreach ($service->media as $media) {
            FileUploadHelper::deleteImage($media->file_path);
            $media->delete();
        }

        $this->serviceRepository->delete($service);

        $notify_message = trans('translate.Deleted successfully');
        $notify_message = array('message' => $notify_message, 'alert-type' => 'success');

        return redirect()->route('admin.tourbooking.services.index')->with($notify_message);
    }

    /**
     * Upload and store media for a service.
     */
    public function storeMedia(Request $request, Service $service): RedirectResponse
    {

        $request->validate([
            'files' => 'required|array|max:20',
            'files.*' => 'file|mimes:jpeg,png,jpg,gif,webp,mp4,avi,mov|max:10240',
            'caption' => 'nullable|string|max:255',
        ]);

        $uploadedCount = 0;

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileType = explode('/', $file->getMimeType())[0] === 'video' ? 'video' : 'image';
                $fileName = $file->getClientOriginalName();
                $filePath = FileUploadHelper::uploadImage($file, $service->slug);

                $isThumbnail = $service->media()->count() === 0;

                ServiceMedia::create([
                    'service_id' => $service->id,
                    'file_path' => $filePath,
                    'file_type' => $fileType,
                    'file_name' => $fileName,
                    'caption' => $request->caption,
                    'is_featured' => $isThumbnail,
                    'is_thumbnail' => $isThumbnail,
                    'display_order' => $service->media()->count() + 1,
                ]);

                $uploadedCount++;
            }
        }

        $notify_message = $uploadedCount . ' ' . trans('translate.Media uploaded successfully');
        $notify_message = array('message' => $notify_message, 'alert-type' => 'success');

        return back()->with($notify_message);
    }

    /**
     * Delete a media item.
     */
    public function deleteMedia(ServiceMedia $media): RedirectResponse
    {
        $serviceId = $media->service_id;

        // Check if this is the thumbnail, reassign if needed
        if ($media->is_thumbnail) {
            $newThumbnail = ServiceMedia::where('service_id', $serviceId)
                ->where('id', '!=', $media->id)
                ->where('file_type', 'image')
                ->first();

            if ($newThumbnail) {
                $newThumbnail->update(['is_thumbnail' => true]);
            }
        }

        if ($media?->file_path) {
            FileUploadHelper::deleteImage($media->file_path);
        }

        // Delete the record
        $media->delete();

        $notify_message = trans('translate.Media deleted successfully');
        $notify_message = array('message' => $notify_message, 'alert-type' => 'success');

        return back()->with($notify_message);
    }

    /**
     * Set a media item as the thumbnail.
     */
    public function setThumbnail(ServiceMedia $media): RedirectResponse
    {
        // Ensure this is an image
        if ($media->file_type !== 'image') {
            $notify_message = trans('translate.Only images can be set as thumbnails');
            $notify_message = array('message' => $notify_message, 'alert-type' => 'error');

            return back()->with($notify_message);
        }

        // Reset all thumbnails for this service
        ServiceMedia::where('service_id', $media->service_id)
            ->update(['is_thumbnail' => false]);

        // Set this one as thumbnail
        $media->update(['is_thumbnail' => true]);

        $notify_message = trans('translate.Thumbnail set successfully');
        $notify_message = array('message' => $notify_message, 'alert-type' => 'success');

        return back()->with($notify_message);
    }

    /**
     * Mövcud şəklin caption (alt mətni) sahəsini yeniləyir.
     * Sayt qalereyasında şəklin alt-ı bu caption-dan render olunur.
     */
    public function updateMediaCaption(Request $request, ServiceMedia $media): RedirectResponse
    {
        $request->validate([
            'caption' => 'nullable|string|max:255',
        ]);

        $media->update(['caption' => $request->caption]);

        $notify_message = array(
            'message' => trans('translate.Caption updated successfully'),
            'alert-type' => 'success',
        );

        return back()->with($notify_message);
    }

    /**
     * Servisə yeni qiymət siyahısı (PDF) əlavə edir.
     * Hər PDF-in öz label-i var, sayt tərəfində buttonun üstündə görünür.
     */
    public function storePriceList(Request $request, Service $service): RedirectResponse
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf|max:20480',
        ]);

        $filePath = FileUploadHelper::uploadImage($request->file('file'), $service->slug . '-price', 'uploads/price-lists');

        ServicePriceList::create([
            'service_id' => $service->id,
            'label' => $request->label,
            'file_path' => $filePath,
            'file_name' => $request->file('file')->getClientOriginalName(),
            'display_order' => $service->priceLists()->count() + 1,
        ]);

        $notify_message = array(
            'message' => trans('translate.Price list uploaded successfully'),
            'alert-type' => 'success',
        );

        return back()->with($notify_message);
    }

    /**
     * Qiymət siyahısını (PDF) silir.
     */
    public function deletePriceList(ServicePriceList $priceList): RedirectResponse
    {
        if ($priceList?->file_path) {
            FileUploadHelper::deleteImage($priceList->file_path);
        }

        $priceList->delete();

        $notify_message = array(
            'message' => trans('translate.Price list deleted successfully'),
            'alert-type' => 'success',
        );

        return back()->with($notify_message);
    }

    public function reorderMedia(Request $request, Service $service): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:service_media,id',
        ]);

        foreach ($request->order as $index => $mediaId) {
            ServiceMedia::where('id', $mediaId)
                ->where('service_id', $service->id)
                ->update(['display_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function bulkDeleteMedia(Request $request, Service $service): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:service_media,id',
        ]);

        $mediaItems = ServiceMedia::where('service_id', $service->id)
            ->whereIn('id', $request->ids)
            ->get();

        $hadThumbnail = false;
        foreach ($mediaItems as $media) {
            if ($media->is_thumbnail) $hadThumbnail = true;
            if ($media->file_path) {
                FileUploadHelper::deleteImage($media->file_path);
            }
            $media->delete();
        }

        if ($hadThumbnail) {
            $newThumbnail = ServiceMedia::where('service_id', $service->id)
                ->where('file_type', 'image')
                ->orderBy('display_order')
                ->first();
            if ($newThumbnail) {
                $newThumbnail->update(['is_thumbnail' => true]);
            }
        }

        return response()->json(['success' => true, 'deleted' => count($mediaItems)]);
    }

    /**
     * Show itineraries for a service.
     */
    public function showItineraries(Service $service): View
    {
        $service->load(['itineraries' => function ($query) {
            $query->orderBy('day_number');
        }]);

        return view('tourbooking::admin.services.itineraries', compact('service'));
    }

    /**
     * Store a new itinerary for a service.
     */
    public function storeItinerary(Request $request, Service $service): RedirectResponse
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'day_number' => 'required|integer|min:1',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'meal_included' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->except('_token', 'image');

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = FileUploadHelper::uploadImage($request->file('image'), 'itinerary');
        }

        // Set display order if not provided
        if (!isset($data['display_order'])) {
            $data['display_order'] = $service->itineraries()->count() + 1;
        }

        $data['service_id'] = $service->id;

        TourItinerary::create($data);

        $notify_message = trans('translate.Itinerary added successfully');
        $notify_message = array('message' => $notify_message, 'alert-type' => 'success');

        return back()->with($notify_message);
    }

    /**
     * Update an existing itinerary.
     */
    public function updateItinerary(Request $request, TourItinerary $itinerary): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'day_number' => 'required|integer|min:1',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'meal_included' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->except('_token', '_method', 'image');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($itinerary?->image) {
                FileUploadHelper::deleteImage($itinerary?->image);
            }
            $data['image'] = FileUploadHelper::uploadImage($request->file('image'), 'itinerary');
        }

        $itinerary->update($data);

        $notify_message = trans('translate.Itinerary updated successfully');
        $notify_message = array('message' => $notify_message, 'alert-type' => 'success');

        return back()->with($notify_message);
    }

    /**
     * Delete an itinerary.
     */
    public function deleteItinerary(TourItinerary $itinerary): RedirectResponse
    {
        if ($itinerary?->file_path) {
            FileUploadHelper::deleteImage($itinerary->file_path);
        }

        $itinerary->delete();

        $notify_message = trans('translate.Itinerary deleted successfully');
        $notify_message = array('message' => $notify_message, 'alert-type' => 'success');

        return back()->with($notify_message);
    }

    /**
     * Show extra charges for a service.
     */
    public function showExtraCharges(Service $service): View
    {
        $service->load('extraCharges');

        return view('tourbooking::admin.services.extra_charges', compact('service'));
    }

    /**
     * Store a new extra charge for a service.
     */
    public function storeExtraCharge(Request $request, Service $service): RedirectResponse
    {
        // Transform checkbox values before validation
        $request->merge([
            'is_mandatory' => $request->boolean('is_mandatory'),
            'is_tax' => $request->boolean('is_tax'),
            'status' => $request->boolean('status'),
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'price_type' => 'required|in:per_booking,per_person,per_adult,per_child,per_infant,per_night,flat',
            'is_mandatory' => 'boolean',
            'is_tax' => 'boolean',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
            'max_quantity' => 'nullable|integer|min:1',
            'status' => 'boolean',
        ]);

        $data = $request->all();
        $data['service_id'] = $service->id;

        ExtraCharge::create($data);

        $notify_message = trans('translate.Extra charge added successfully');
        $notify_message = array('message' => $notify_message, 'alert-type' => 'success');

        return back()->with($notify_message);
    }

    /**
     * Update an existing extra charge.
     */
    public function updateExtraCharge(Request $request, ExtraCharge $charge): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'price_type' => 'required|in:per_booking,per_person,per_adult,per_child,per_infant,per_night,flat',
            'is_mandatory' => 'boolean',
            'is_tax' => 'boolean',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
            'max_quantity' => 'nullable|integer|min:1',
            'status' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_mandatory'] = $request->input('is_mandatory') == '1' || $request->input('is_mandatory') === true;
        $data['is_tax'] = $request->input('is_tax') == '1' || $request->input('is_tax') === true;
        $data['status'] = $request->input('status') == '1' || $request->input('status') === true;

        $charge->update($data);

        $notify_message = trans('translate.Extra charge updated successfully');
        $notify_message = array('message' => $notify_message, 'alert-type' => 'success');

        return back()->with($notify_message);
    }

    /**
     * Delete an extra charge.
     */
    public function deleteExtraCharge(ExtraCharge $charge): RedirectResponse
    {
        $charge->delete();

        $notify_message = trans('translate.Extra charge deleted successfully');
        $notify_message = array('message' => $notify_message, 'alert-type' => 'success');

        return back()->with($notify_message);
    }

    /**
     * Show availability for a service.
     */
    public function showAvailability(Service $service): View
    {
        $service->load('availabilities');

        return view('tourbooking::admin.services.availability', compact('service'));
    }

    /**
     * Store a new availability for a service.
     *
     * @return RedirectResponse|JsonResponse
     */
    public function storeAvailability(Request $request, Service $service): RedirectResponse|JsonResponse
    {
        // Check if this is a bulk request with dates array (from AJAX)
        if ($request->has('bulk') && $request->has('dates')) {
            // Validate bulk data
            $request->validate([
                'dates' => 'required|array',
                'dates.*' => 'required|date',
                'start_time' => 'nullable|date_format:H:i',
                'end_time' => 'nullable|date_format:H:i|after_or_equal:start_time',
                'available_spots' => 'nullable|integer|min:1',
                'special_price' => 'nullable|numeric|min:0',
                'is_available' => 'boolean',
                'notes' => 'nullable|string',
            ]);

            $successCount = 0;
            $errorCount = 0;

            foreach ($request->dates as $date) {
                // Check for existing availability on this date
                $existingAvailability = Availability::where('service_id', $service->id)
                    ->where('date', $date)
                    ->where('start_time', $request->start_time ? date('H:i:s', strtotime($request->start_time)) : null)
                    ->first();

                if ($existingAvailability) {
                    $errorCount++;
                    continue;
                }

                Availability::create([
                    'service_id' => $service->id,
                    'date' => $date,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'is_available' => $request->has('is_available') ? true : false,
                    'available_spots' => $request->available_spots,
                    'special_price' => $request->special_price,
                    'notes' => $request->notes,
                ]);

                $successCount++;
            }

            if ($request->ajax()) {
                if ($errorCount > 0) {
                    return response()->json([
                        'success' => true,
                        'message' => trans('translate.Created :success availabilities. :error already existed.', [
                            'success' => $successCount,
                            'error' => $errorCount
                        ])
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => trans('translate.Created :count availabilities successfully', [
                        'count' => $successCount
                    ])
                ]);
            }

            $notify_message = trans('translate.Created :success availabilities. :error already existed.', [
                'success' => $successCount,
                'error' => $errorCount
            ]);
            $notify_type = ($successCount > 0) ? 'success' : 'error';
            $notify_message = array('message' => $notify_message, 'alert-type' => $notify_type);

            return back()->with($notify_message);
        }

        // Single availability creation
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after_or_equal:start_time',
            'is_available' => 'boolean',
            'available_spots' => 'nullable|integer|min:1',
            'special_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['service_id'] = $service->id;
        $data['is_available'] = $request->has('is_available') ? true : false;

        // Check for existing availability on the same date
        $existingAvailability = Availability::where('service_id', $service->id)
            ->where('date', $request->date)
            ->where('start_time', $request->start_time ? date('H:i:s', strtotime($request->start_time)) : null)
            ->first();

        if ($existingAvailability) {
            $notify_message = trans('translate.Availability already exists for this date');
            $notify_message = array('message' => $notify_message, 'alert-type' => 'error');

            return back()->with($notify_message);
        }

        Availability::create($data);

        $notify_message = trans('translate.Availability added successfully');
        $notify_message = array('message' => $notify_message, 'alert-type' => 'success');

        return back()->with($notify_message);
    }

    /**
     * Update an existing availability.
     */
    public function updateAvailability(Request $request, Availability $availability): RedirectResponse
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after_or_equal:start_time',
            'is_available' => 'boolean',
            'available_spots' => 'nullable|integer|min:1',
            'special_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['is_available'] = $request->has('is_available') ? true : false;

        // Check for existing availability on the same date (excluding this one)
        $existingAvailability = Availability::where('service_id', $availability->service_id)
            ->where('date', $request->date)
            ->where('start_time', $request->start_time ?? null)
            ->where('id', '!=', $availability->id)
            ->first();

        if ($existingAvailability) {
            $notify_message = trans('translate.Availability already exists for this date');
            $notify_message = array('message' => $notify_message, 'alert-type' => 'error');

            return back()->with($notify_message);
        }

        $availability->update($data);

        $notify_message = trans('translate.Availability updated successfully');
        $notify_message = array('message' => $notify_message, 'alert-type' => 'success');

        return back()->with($notify_message);
    }

    /**
     * Delete an availability.
     */
    public function deleteAvailability(Availability $availability): RedirectResponse
    {
        $availability->delete();

        $notify_message = trans('translate.Availability deleted successfully');
        $notify_message = array('message' => $notify_message, 'alert-type' => 'success');

        return back()->with($notify_message);
    }

    /**
     * Filter services by type.
     */
    public function getByType(string $type): View
    {
        $serviceType = ServiceType::where('slug', $type)->firstOrFail();

        $services = $this->serviceRepository->getByType($serviceType->id, 0);

        return view('tourbooking::admin.service_types.show', compact('serviceType', 'services'));
    }

    /**
     * Filter services by predefined types.
     */
    public function tours(): View
    {
        return $this->getByType('tours');
    }

    public function hotels(): View
    {
        return $this->getByType('hotels');
    }

    public function restaurants(): View
    {
        return $this->getByType('restaurants');
    }

    public function rentals(): View
    {
        return $this->getByType('rentals');
    }

    public function activities(): View
    {
        return $this->getByType('activities');
    }

    /**
     * Show media management for a service.
     */
    public function showMedia(Service $service): View
    {
        $service->load(['media' => function ($query) {
            $query->orderBy('display_order')->orderBy('created_at', 'desc');
        }]);

        return view('tourbooking::admin.services.media', compact('service'));
    }

    // ==================== TREATMENT ITEMS ====================

    /**
     * Store a new treatment item via AJAX.
     */
    public function storeTreatmentItem(Request $request, Service $service): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = [
            'service_id' => $service->id,
            'title' => $request->title,
            'display_order' => ServiceTreatmentItem::where('service_id', $service->id)->count() + 1,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = FileUploadHelper::uploadImage(
                $request->file('image'),
                'treatment',
                'uploads/services/treatments'
            );
        }

        $item = ServiceTreatmentItem::create($data);

        return response()->json([
            'success' => true,
            'item' => [
                'id' => $item->id,
                'title' => $item->title,
                'image' => $item->image,
                'image_url' => $item->image ? asset($item->image) : null,
            ]
        ]);
    }

    /**
     * Delete a treatment item via AJAX.
     */
    public function deleteTreatmentItem(ServiceTreatmentItem $treatmentItem): JsonResponse
    {
        if ($treatmentItem->image) {
            FileUploadHelper::deleteImage($treatmentItem->image);
        }

        $treatmentItem->delete();

        return response()->json(['success' => true]);
    }

    public function review_list()
    {
        $reviews = Review::with('service')->latest()->get();

        return view('tourbooking::admin.review.index', ['reviews' => $reviews]);
    }

    public function review_detail($id)
    {

        $review = Review::with('service')->findOrFail($id);

        return view('tourbooking::admin.review.details', ['review' => $review]);
    }

    public function review_delete($id)
    {
        Review::findOrFail($id)->delete();
        return redirect()->route('admin.tourbooking.reviews.index')->with('success', 'Review deleted successfully');
    }

    public function review_approve($id)
    {
        Review::findOrFail($id)->update(['status' => 1]);
        return redirect()->route('admin.tourbooking.reviews.index')->with('success', 'Review approved successfully');
    }
}