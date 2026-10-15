<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Http\Controllers\Admin;

use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\TourBooking\App\Models\Room;
use Modules\TourBooking\App\Models\RoomMedia;
use Modules\TourBooking\App\Models\Service;
use Modules\TourBooking\App\Models\Amenity;

final class RoomController extends Controller
{
    /**
     * Display a listing of rooms for a service.
     */
    public function index(Service $service): View
    {
        $rooms = $service->rooms()
            ->with(['thumbnail', 'media'])
            ->ordered()
            ->get();

        return view('tourbooking::admin.rooms.index', compact('service', 'rooms'));
    }

    /**
     * Show the form for creating a new room.
     */
    public function create(Service $service): View
    {
        $amenities = Amenity::where('status', true)
            ->with('translation:id,amenity_id,lang_code,name')
            ->get();

        return view('tourbooking::admin.rooms.create', compact('service', 'amenities'));
    }

    /**
     * Store a newly created room.
     */
    public function store(Request $request, Service $service): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:5000',
            'price_per_night' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'max_adults' => 'required|integer|min:1|max:20',
            'max_children' => 'required|integer|min:0|max:10',
            'room_size' => 'nullable|integer|min:1',
            'bed_type' => 'nullable|string',
            'bed_count' => 'required|integer|min:1|max:10',
            'total_rooms' => 'required|integer|min:1',
            'amenities' => 'nullable|array',
'features' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
            // Thumbnail validation
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            // Multiple gallery images validation
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            // Video URL
            'video_url' => 'nullable|url',
        ]);

        $validated['service_id'] = $service->id;
        $validated['is_featured'] = $request->has('is_featured');
        $validated['status'] = $request->has('status');
        $validated['display_order'] = $validated['display_order'] ?? 0;

        // Convert comma-separated features string to array
        if (!empty($validated['features'])) {
            $validated['features'] = array_values(array_filter(array_map('trim', explode(',', $validated['features']))));
        } else {
            $validated['features'] = [];
        }

        // Remove file fields from validated data before creating room
        unset($validated['thumbnail'], $validated['images'], $validated['video_url']);

        $room = Room::create($validated);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $this->uploadThumbnail($request->file('thumbnail'), $room);
        }

        // Handle multiple gallery images upload
        if ($request->hasFile('images')) {
            $this->uploadGalleryImages($request->file('images'), $room);
        }

        // Handle video URL
        if ($request->filled('video_url')) {
            $this->addVideoUrlToRoom($request->video_url, $room);
        }

        $notify_message = trans('translate.Room created successfully');
        $notify_message = array('message' => $notify_message, 'alert-type' => 'success');

        return redirect()->route('admin.tourbooking.services.rooms.edit', [$service->id, $room->id])
            ->with($notify_message);
    }

    /**
     * Show the form for editing the specified room.
     */
    public function edit(Service $service, Room $room): View
    {
        $room->load(['media']);

        $amenities = Amenity::where('status', true)
            ->with('translation:id,amenity_id,lang_code,name')
            ->get();

        return view('tourbooking::admin.rooms.edit', compact('service', 'room', 'amenities'));
    }

    /**
     * Update the specified room.
     */
    public function update(Request $request, Service $service, Room $room): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:5000',
            'price_per_night' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'max_adults' => 'required|integer|min:1|max:20',
            'max_children' => 'required|integer|min:0|max:10',
            'room_size' => 'nullable|integer|min:1',
            'bed_type' => 'nullable|string',
            'bed_count' => 'required|integer|min:1|max:10',
            'total_rooms' => 'required|integer|min:1',
            'amenities' => 'nullable|array',
            'features' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
            // Thumbnail validation
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            // Multiple gallery images validation
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            // Remove images
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'integer|exists:room_media,id',
            // Video URL
            'video_url' => 'nullable|url',
        ]);

        $validated['is_featured'] = $request->has('is_featured');
        $validated['status'] = $request->has('status');

        // Convert comma-separated features string to array
        if (!empty($validated['features'])) {
            $validated['features'] = array_values(array_filter(array_map('trim', explode(',', $validated['features']))));
        } else {
            $validated['features'] = [];
        }

        // Remove file fields from validated data before updating room
        unset($validated['thumbnail'], $validated['images'], $validated['remove_images'], $validated['video_url']);

        $room->update($validated);

        // Handle thumbnail upload (replace existing)
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            $oldThumbnail = $room->media()->where('is_thumbnail', true)->first();
            if ($oldThumbnail) {
                if ($oldThumbnail->file_path && file_exists(public_path($oldThumbnail->file_path))) {
                    unlink(public_path($oldThumbnail->file_path));
                }
                $oldThumbnail->delete();
            }
            $this->uploadThumbnail($request->file('thumbnail'), $room);
        }

        // Handle removing existing gallery images
        if ($request->has('remove_images') && is_array($request->remove_images)) {
            foreach ($request->remove_images as $mediaId) {
                $media = RoomMedia::where('id', $mediaId)->where('room_id', $room->id)->first();
                if ($media) {
                    if ($media->file_path && file_exists(public_path($media->file_path))) {
                        unlink(public_path($media->file_path));
                    }
                    $media->delete();
                }
            }
        }

        // Handle multiple gallery images upload (append to existing)
        if ($request->hasFile('images')) {
            $this->uploadGalleryImages($request->file('images'), $room);
        }

        $notify_message = trans('translate.Room updated successfully');
        $notify_message = array('message' => $notify_message, 'alert-type' => 'success');

        return redirect()->route('admin.tourbooking.services.rooms.edit', [$service->id, $room->id])
            ->with($notify_message);
    }

    /**
     * Remove the specified room.
     */
    public function destroy(Service $service, Room $room): RedirectResponse
    {
        // Delete associated media files
        foreach ($room->media as $media) {
            if ($media->file_path && file_exists(public_path($media->file_path))) {
                unlink(public_path($media->file_path));
            }
        }

        $room->delete();

        $notify_message = trans('translate.Room deleted successfully');
        $notify_message = array('message' => $notify_message, 'alert-type' => 'success');

        return redirect()->route('admin.tourbooking.services.rooms.index', $service->id)
            ->with($notify_message);
    }

    /**
     * Upload thumbnail image for a room.
     */
    private function uploadThumbnail($file, Room $room): RoomMedia
    {
        $uploadPath = 'uploads/rooms/thumbnails/';
        $fileName = time() . '_thumb_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Ensure directory exists
        if (!file_exists(public_path($uploadPath))) {
            mkdir(public_path($uploadPath), 0755, true);
        }

        $file->move(public_path($uploadPath), $fileName);
        $filePath = $uploadPath . $fileName;

        // Mark all existing media as not thumbnail
        $room->media()->update(['is_thumbnail' => false]);

        return RoomMedia::create([
            'room_id' => $room->id,
            'file_path' => $filePath,
            'media_type' => 'image',
            'is_thumbnail' => true,
            'display_order' => 0,
        ]);
    }

    /**
     * Upload multiple gallery images for a room.
     */
    private function uploadGalleryImages(array $files, Room $room): void
    {
        $uploadPath = 'uploads/rooms/images/';

        // Ensure directory exists
        if (!file_exists(public_path($uploadPath))) {
            mkdir(public_path($uploadPath), 0755, true);
        }

        // Get current max display order
        $maxOrder = $room->media()->max('display_order') ?? 0;

        foreach ($files as $index => $file) {
            $fileName = time() . '_gallery_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path($uploadPath), $fileName);
            $filePath = $uploadPath . $fileName;

            $maxOrder++;

            RoomMedia::create([
                'room_id' => $room->id,
                'file_path' => $filePath,
                'media_type' => 'image',
                'is_thumbnail' => false,
                'display_order' => $maxOrder,
            ]);
        }
    }

    /**
     * Add a video URL to a room.
     */
    private function addVideoUrlToRoom(string $videoUrl, Room $room): RoomMedia
    {
        $maxOrder = $room->media()->max('display_order') ?? 0;

        return RoomMedia::create([
            'room_id' => $room->id,
            'file_path' => 'uploads/rooms/videos/placeholder.jpg',
            'media_type' => 'video',
            'video_url' => $videoUrl,
            'display_order' => $maxOrder + 1,
        ]);
    }

    /**
     * Upload media for a room (AJAX endpoint).
     */
    public function uploadMedia(Request $request, Service $service, Room $room): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,jpg,png,gif,webp,mp4,webm|max:51200',
            'media_type' => 'required|in:image,video',
            'is_thumbnail' => 'nullable|boolean',
        ]);

        $file = $request->file('file');
        $mediaType = $request->media_type;

        // Determine upload path based on media type
        $uploadPath = $mediaType === 'video' 
            ? 'uploads/rooms/videos/' 
            : 'uploads/rooms/images/';

        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($uploadPath), $fileName);
        $filePath = $uploadPath . $fileName;

        // If this is set as thumbnail, remove thumbnail flag from other media
        if ($request->is_thumbnail) {
            $room->media()->update(['is_thumbnail' => false]);
        }

        // Get max display order
        $maxOrder = $room->media()->max('display_order') ?? 0;

        $media = RoomMedia::create([
            'room_id' => $room->id,
            'file_path' => $filePath,
            'media_type' => $mediaType,
            'is_thumbnail' => $request->is_thumbnail ?? false,
            'display_order' => $maxOrder + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => trans('translate.Media uploaded successfully'),
            'media' => $media,
        ]);
    }

    /**
     * Add video URL to room (AJAX endpoint).
     */
    public function addVideoUrl(Request $request, Service $service, Room $room): JsonResponse
    {
        $request->validate([
            'video_url' => 'required|url',
            'caption' => 'nullable|string|max:255',
        ]);

        // Get max display order
        $maxOrder = $room->media()->max('display_order') ?? 0;

        // Create a placeholder image path for video
        $media = RoomMedia::create([
            'room_id' => $room->id,
            'file_path' => 'uploads/rooms/videos/placeholder.jpg',
            'media_type' => 'video',
            'video_url' => $request->video_url,
            'caption' => $request->caption,
            'display_order' => $maxOrder + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => trans('translate.Video added successfully'),
            'media' => $media,
        ]);
    }

    /**
     * Update media details.
     */
    public function updateMedia(Request $request, RoomMedia $media): JsonResponse
    {
        $request->validate([
            'caption' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'is_thumbnail' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
        ]);

        // If this is set as thumbnail, remove thumbnail flag from other media
        if ($request->is_thumbnail) {
            RoomMedia::where('room_id', $media->room_id)
                ->where('id', '!=', $media->id)
                ->update(['is_thumbnail' => false]);
        }

        $media->update($request->only(['caption', 'alt_text', 'is_thumbnail', 'display_order']));

        return response()->json([
            'success' => true,
            'message' => trans('translate.Media updated successfully'),
            'media' => $media,
        ]);
    }

    /**
     * Delete media.
     */
    public function deleteMedia(RoomMedia $media): JsonResponse
    {
        // Delete file if exists
        if ($media->file_path && file_exists(public_path($media->file_path))) {
            unlink(public_path($media->file_path));
        }

        $media->delete();

        return response()->json([
            'success' => true,
            'message' => trans('translate.Media deleted successfully'),
        ]);
    }

    /**
     * Reorder media.
     */
    public function reorderMedia(Request $request, Room $room): JsonResponse
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:room_media,id',
        ]);

        foreach ($request->order as $index => $mediaId) {
            RoomMedia::where('id', $mediaId)
                ->where('room_id', $room->id)
                ->update(['display_order' => $index]);
        }

        return response()->json([
            'success' => true,
            'message' => trans('translate.Media order updated successfully'),
        ]);
    }

    /**
     * Get rooms for a service (API endpoint for frontend).
     */
    public function getRooms(Service $service): JsonResponse
    {
        $rooms = $service->rooms()
            ->with(['thumbnail', 'images', 'videos'])
            ->active()
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'rooms' => $rooms,
        ]);
    }

    /**
     * Check room availability for dates.
     */
    public function checkAvailability(Request $request, Room $room): JsonResponse
    {
        $request->validate([
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
        ]);

        $checkIn = $request->check_in_date;
        $checkOut = $request->check_out_date;

        $availableRooms = $room->getAvailableRoomsForDates($checkIn, $checkOut);
        $nights = \Carbon\Carbon::parse($checkIn)->diffInDays(\Carbon\Carbon::parse($checkOut));
        $totalPrice = $room->effective_price * $nights;

        return response()->json([
            'success' => true,
            'available' => $availableRooms > 0,
            'available_rooms' => $availableRooms,
            'nights' => $nights,
            'price_per_night' => $room->effective_price,
            'total_price' => $totalPrice,
        ]);
    }
}