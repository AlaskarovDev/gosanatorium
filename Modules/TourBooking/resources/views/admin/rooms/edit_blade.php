@extends('admin.master_layout')

@section('title')
    <title>{{ isset($room) ? __('translate.Edit Room') : __('translate.Add New Room') }} - {{ $service->title }}</title>
@endsection

@push('style_section')
<style>
    .video-thumbnail-warning {
        background-color: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 5px;
        padding: 10px;
        font-size: 13px;
    }
    .video-preview {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .thumbnail-required-star {
        color: #dc3545;
        font-weight: bold;
    }
    .media-card {
        transition: all 0.3s ease;
    }
    .media-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .current-thumbnail-box {
        border: 2px solid #007bff;
        border-radius: 8px;
        padding: 10px;
        background: #f8f9fa;
    }
</style>
@endpush

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between">
                <h1>{{ isset($room) ? __('translate.Edit Room') : __('translate.Add New Room') }}</h1>
                <div>
                    <a href="{{ route('admin.tourbooking.services.rooms.index', $service->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('translate.Back to Rooms') }}
                    </a>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <form id="roomForm"
                            action="{{ isset($room) ? route('admin.tourbooking.services.rooms.update', [$service->id, $room->id]) : route('admin.tourbooking.services.rooms.store', $service->id) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @if (isset($room))
                                @method('PUT')
                            @endif

                            <!-- Basic Information -->
                            <div class="card">
                                <div class="card-header">
                                    <h4>{{ __('translate.Basic Information') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="name">{{ __('translate.Room Name') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="name" id="name"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    value="{{ old('name', $room->name ?? '') }}"
                                                    placeholder="{{ __('translate.e.g., Deluxe Double Room') }}" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="display_order">{{ __('translate.Display Order') }}</label>
                                                <input type="number" name="display_order" id="display_order"
                                                    class="form-control @error('display_order') is-invalid @enderror"
                                                    value="{{ old('display_order', $room->display_order ?? 0) }}" min="0">
                                                @error('display_order')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="short_description">{{ __('translate.Short Description') }}</label>
                                        <textarea name="short_description" id="short_description" rows="2"
                                            class="form-control @error('short_description') is-invalid @enderror"
                                            placeholder="{{ __('translate.Brief description for room cards') }}">{{ old('short_description', $room->short_description ?? '') }}</textarea>
                                        @error('short_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="description">{{ __('translate.Full Description') }}</label>
                                        <textarea name="description" id="description" rows="4"
                                            class="form-control summernote @error('description') is-invalid @enderror"
                                            placeholder="{{ __('translate.Detailed room description') }}">{{ old('description', $room->description ?? '') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Pricing -->
                            <div class="card">
                                <div class="card-header">
                                    <h4>{{ __('translate.Pricing') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="price_per_night">{{ __('translate.Price Per Night') }} <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">{{ default_currency()['currency_icon'] }}</span>
                                                    </div>
                                                    <input type="number" name="price_per_night" id="price_per_night"
                                                        class="form-control @error('price_per_night') is-invalid @enderror"
                                                        value="{{ old('price_per_night', $room->price_per_night ?? '') }}"
                                                        step="0.01" min="0" required>
                                                </div>
                                                @error('price_per_night')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="discount_price">{{ __('translate.Discount Price') }}</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">{{ default_currency()['currency_icon'] }}</span>
                                                    </div>
                                                    <input type="number" name="discount_price" id="discount_price"
                                                        class="form-control @error('discount_price') is-invalid @enderror"
                                                        value="{{ old('discount_price', $room->discount_price ?? '') }}"
                                                        step="0.01" min="0">
                                                </div>
                                                @error('discount_price')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="total_rooms">{{ __('translate.Total Rooms Available') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" name="total_rooms" id="total_rooms"
                                                    class="form-control @error('total_rooms') is-invalid @enderror"
                                                    value="{{ old('total_rooms', $room->total_rooms ?? 1) }}" min="1"
                                                    required>
                                                @error('total_rooms')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Room Details -->
                            <div class="card">
                                <div class="card-header">
                                    <h4>{{ __('translate.Room Details') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="max_adults">{{ __('translate.Max Adults') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" name="max_adults" id="max_adults"
                                                    class="form-control @error('max_adults') is-invalid @enderror"
                                                    value="{{ old('max_adults', $room->max_adults ?? 2) }}" min="1"
                                                    max="20" required>
                                                @error('max_adults')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="max_children">{{ __('translate.Max Children') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" name="max_children" id="max_children"
                                                    class="form-control @error('max_children') is-invalid @enderror"
                                                    value="{{ old('max_children', $room->max_children ?? 1) }}" min="0"
                                                    max="10" required>
                                                @error('max_children')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="room_size">{{ __('translate.Room Size (m²)') }}</label>
                                                <input type="number" name="room_size" id="room_size"
                                                    class="form-control @error('room_size') is-invalid @enderror"
                                                    value="{{ old('room_size', $room->room_size ?? '') }}" min="1">
                                                @error('room_size')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="bed_count">{{ __('translate.Bed Count') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" name="bed_count" id="bed_count"
                                                    class="form-control @error('bed_count') is-invalid @enderror"
                                                    value="{{ old('bed_count', $room->bed_count ?? 1) }}" min="1"
                                                    max="10" required>
                                                @error('bed_count')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="bed_type">{{ __('translate.Bed Type') }}</label>
                                                <select name="bed_type" id="bed_type"
                                                    class="form-control @error('bed_type') is-invalid @enderror">
                                                    <option value="">{{ __('translate.Select Bed Type') }}</option>
                                                    <option value="single"
                                                        {{ old('bed_type', $room->bed_type ?? '') == 'single' ? 'selected' : '' }}>
                                                        {{ __('translate.Single Bed') }}</option>
                                                    <option value="double"
                                                        {{ old('bed_type', $room->bed_type ?? '') == 'double' ? 'selected' : '' }}>
                                                        {{ __('translate.Double Bed') }}</option>
                                                    <option value="queen"
                                                        {{ old('bed_type', $room->bed_type ?? '') == 'queen' ? 'selected' : '' }}>
                                                        {{ __('translate.Queen Size Bed') }}</option>
                                                    <option value="king"
                                                        {{ old('bed_type', $room->bed_type ?? '') == 'king' ? 'selected' : '' }}>
                                                        {{ __('translate.King Size Bed') }}</option>
                                                    <option value="twin"
                                                        {{ old('bed_type', $room->bed_type ?? '') == 'twin' ? 'selected' : '' }}>
                                                        {{ __('translate.Twin Beds') }}</option>
                                                    <option value="bunk"
                                                        {{ old('bed_type', $room->bed_type ?? '') == 'bunk' ? 'selected' : '' }}>
                                                        {{ __('translate.Bunk Beds') }}</option>
                                                </select>
                                                @error('bed_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ==================== VIDEO & THUMBNAIL SECTION START ==================== --}}
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h4 class="mb-0"><i class="fas fa-video me-2"></i>{{ __('translate.Video & Thumbnail') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        {{-- Thumbnail Image --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="thumbnail" class="form-label">
                                                    {{ __('translate.Thumbnail Image') }}
                                                    <span class="text-danger thumbnail-required-star" id="thumbnailStar" style="display: none;">* {{ __('translate.Required') }}</span>
                                                </label>
                                                <input type="file" 
                                                       class="form-control @error('thumbnail') is-invalid @enderror" 
                                                       id="thumbnail" 
                                                       name="thumbnail" 
                                                       accept="image/*">
                                                @error('thumbnail')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                
                                                <small class="text-muted d-block mt-1">
                                                    <i class="fas fa-info-circle"></i> {{ __('translate.Recommended size: 800x600px. Formats: JPG, PNG, WebP') }}
                                                </small>
                                                
                                                {{-- Video warning message --}}
                                                <div class="alert alert-warning mt-2 video-thumbnail-warning" id="videoThumbnailWarning" style="display: none;">
                                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                                    <strong>{{ __('translate.Thumbnail is required when adding a video!') }}</strong>
                                                    <br><small>{{ __('translate.Please upload a thumbnail image to display in room listings.') }}</small>
                                                </div>
                                                
                                                {{-- Current thumbnail --}}
                                                @if(isset($room) && $room->thumbnail)
                                                    <div class="mt-3 current-thumbnail-box">
                                                        <label class="d-block mb-2"><strong>{{ __('translate.Current Thumbnail') }}:</strong></label>
                                                        <img src="{{ asset($room->thumbnail) }}" alt="Current Thumbnail" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                                                        <div class="form-check mt-2">
                                                            <input class="form-check-input" type="checkbox" name="remove_thumbnail" id="remove_thumbnail" value="1">
                                                            <label class="form-check-label text-danger" for="remove_thumbnail">
                                                                <i class="fas fa-trash"></i> {{ __('translate.Remove current thumbnail') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endif

                                                {{-- New thumbnail preview --}}
                                                <div id="thumbnailPreview" class="mt-3" style="display: none;">
                                                    <label class="d-block mb-2"><strong>{{ __('translate.New Thumbnail Preview') }}:</strong></label>
                                                    <img id="thumbnailPreviewImg" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        {{-- Video URL --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="video_url" class="form-label">{{ __('translate.Video URL') }}</label>
                                                <input type="url" 
                                                       class="form-control @error('video_url') is-invalid @enderror" 
                                                       id="video_url" 
                                                       name="video_url" 
                                                       value="{{ old('video_url', $room->video_url ?? '') }}"
                                                       placeholder="https://www.youtube.com/watch?v=...">
                                                @error('video_url')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                
                                                <small class="text-muted d-block mt-1">
                                                    <i class="fas fa-info-circle"></i> {{ __('translate.Supports YouTube and Vimeo links') }}
                                                </small>
                                                
                                                {{-- Video preview --}}
                                                <div class="video-preview mt-3" id="videoPreviewContainer" style="display: none;">
                                                    <label class="d-block mb-2"><strong>{{ __('translate.Video Preview') }}:</strong></label>
                                                    <div class="ratio ratio-16x9">
                                                        <iframe id="videoPreviewIframe" src="" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Info Box --}}
                                    <div class="alert alert-info mt-3">
                                        <i class="fas fa-lightbulb me-2"></i>
                                        <strong>{{ __('translate.Tip') }}:</strong> 
                                        {{ __('translate.Adding a video helps guests better understand the room. When you add a video, a thumbnail image is required to display in room listings.') }}
                                    </div>
                                </div>
                            </div>
                            {{-- ==================== VIDEO & THUMBNAIL SECTION END ==================== --}}

                            <!-- Amenities -->
                            <div class="card">
                                <div class="card-header">
                                    <h4>{{ __('translate.Room Amenities') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach ($amenities as $amenity)
                                            <div class="col-md-3 col-sm-4 col-6 mb-3">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                        name="amenities[]" value="{{ $amenity->id }}"
                                                        id="amenity_{{ $amenity->id }}"
                                                        {{ in_array($amenity->id, old('amenities', $room->amenities ?? [])) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="amenity_{{ $amenity->id }}">
                                                        {{ $amenity->translation->name ?? $amenity->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Status Options -->
                            <div class="card">
                                <div class="card-header">
                                    <h4>{{ __('translate.Status Options') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" name="status"
                                                    id="status" value="1"
                                                    {{ old('status', $room->status ?? true) ? 'checked' : '' }}>
                                                <label class="custom-control-label"
                                                    for="status">{{ __('translate.Active') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" name="is_featured"
                                                    id="is_featured" value="1"
                                                    {{ old('is_featured', $room->is_featured ?? false) ? 'checked' : '' }}>
                                                <label class="custom-control-label"
                                                    for="is_featured">{{ __('translate.Featured') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Low Stock Badge -->
                            <div class="card">
                                <div class="card-header">
                                    <h4>{{ __('translate.Low Stock Badge') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="custom-control custom-switch mb-3">
                                        <input type="checkbox" class="custom-control-input" name="low_stock_show"
                                            id="low_stock_show" value="1"
                                            {{ old('low_stock_show', $room->low_stock_show ?? false) ? 'checked' : '' }}>
                                        <label class="custom-control-label"
                                            for="low_stock_show">{{ __('translate.Show low stock badge on this room') }}</label>
                                    </div>
                                    <div class="form-group">
                                        <label for="low_stock_text">{{ __('translate.Badge text') }}</label>
                                        <input type="text" name="low_stock_text" id="low_stock_text"
                                            class="form-control @error('low_stock_text') is-invalid @enderror"
                                            value="{{ old('low_stock_text', $room->low_stock_text ?? '') }}"
                                            placeholder="Осталось только 1 номер!">
                                        <small class="text-muted">{{ __('translate.Leave empty to auto-show remaining quantity') }}</small>
                                        @error('low_stock_text')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="card">
                                <div class="card-body">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save me-2"></i>
                                        {{ isset($room) ? __('translate.Update Room') : __('translate.Save Room') }}
                                    </button>
                                </div>
                            </div>
                        </form>

                        @if (isset($room))
                            <!-- Room Media Section -->
                            <div class="card">
                                <div class="card-header">
                                    <h4>{{ __('translate.Room Media Gallery') }}</h4>
                                </div>
                                <div class="card-body">
                                    <!-- Upload Section -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('translate.Upload Additional Images') }}</label>
                                                <input type="file" id="imageUpload" class="form-control"
                                                    accept="image/*" multiple>
                                                <small class="text-muted">{{ __('translate.Supported: JPG, PNG, GIF, WebP. Max: 5MB') }}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('translate.Add Additional Video URL') }}</label>
                                                <div class="input-group">
                                                    <input type="url" id="additionalVideoUrl" class="form-control"
                                                        placeholder="https://youtube.com/watch?v=...">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-primary" onclick="addVideoUrl()">
                                                            <i class="fas fa-plus"></i> {{ __('translate.Add') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Media Gallery -->
                                    <div class="row" id="mediaGallery">
                                        @foreach ($room->media as $media)
                                            <div class="col-md-3 col-sm-4 mb-3" data-media-id="{{ $media->id }}">
                                                <div class="card media-card {{ $media->is_thumbnail ? 'border-primary' : '' }}">
                                                    <div class="position-relative">
                                                        @if ($media->media_type == 'video')
                                                            <div class="video-thumbnail d-flex align-items-center justify-content-center bg-dark"
                                                                style="height: 150px;">
                                                                <i class="fas fa-play-circle fa-3x text-white"></i>
                                                            </div>
                                                        @else
                                                            <img src="{{ asset($media->file_path) }}"
                                                                class="card-img-top" alt="{{ $media->caption }}"
                                                                style="height: 150px; object-fit: cover;">
                                                        @endif
                                                        @if ($media->is_thumbnail)
                                                            <span class="badge badge-primary position-absolute"
                                                                style="top: 5px; left: 5px;">
                                                                {{ __('translate.Thumbnail') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="card-body p-2">
                                                        <div class="btn-group btn-group-sm w-100">
                                                            @if (!$media->is_thumbnail && $media->media_type == 'image')
                                                                <button type="button" class="btn btn-outline-primary"
                                                                    onclick="setAsThumbnail({{ $media->id }})"
                                                                    title="{{ __('translate.Set as Thumbnail') }}">
                                                                    <i class="fas fa-star"></i>
                                                                </button>
                                                            @endif
                                                            <button type="button" class="btn btn-outline-danger"
                                                                onclick="deleteMedia({{ $media->id }})"
                                                                title="{{ __('translate.Delete') }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ==================== VIDEO THUMBNAIL VALIDATION ====================
    const videoUrlInput = document.getElementById('video_url');
    const thumbnailInput = document.getElementById('thumbnail');
    const thumbnailStar = document.getElementById('thumbnailStar');
    const videoThumbnailWarning = document.getElementById('videoThumbnailWarning');
    const videoPreviewContainer = document.getElementById('videoPreviewContainer');
    const videoPreviewIframe = document.getElementById('videoPreviewIframe');
    const thumbnailPreview = document.getElementById('thumbnailPreview');
    const thumbnailPreviewImg = document.getElementById('thumbnailPreviewImg');
    const roomForm = document.getElementById('roomForm');
    
    // Convert YouTube/Vimeo URL to embed URL
    function getEmbedUrl(url) {
        if (!url) return null;
        
        // YouTube
        const youtubeMatch = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
        if (youtubeMatch) {
            return 'https://www.youtube.com/embed/' + youtubeMatch[1];
        }
        
        // Vimeo
        const vimeoMatch = url.match(/(?:vimeo\.com\/)(\d+)/);
        if (vimeoMatch) {
            return 'https://player.vimeo.com/video/' + vimeoMatch[1];
        }
        
        return null;
    }
    
    // Check video URL and update thumbnail requirement
    function checkVideoUrl() {
        const hasVideo = videoUrlInput && videoUrlInput.value.trim() !== '';
        const hasExistingThumbnail = document.querySelector('.current-thumbnail-box img') !== null;
        const removeThumbnailChecked = document.getElementById('remove_thumbnail')?.checked || false;
        const hasNewThumbnail = thumbnailInput && thumbnailInput.files && thumbnailInput.files.length > 0;
        
        if (hasVideo && !hasExistingThumbnail && !hasNewThumbnail) {
            // Video var amma thumbnail yoxdur - məcburi et
            if (thumbnailStar) thumbnailStar.style.display = 'inline';
            if (videoThumbnailWarning) videoThumbnailWarning.style.display = 'block';
            if (thumbnailInput) thumbnailInput.setAttribute('required', 'required');
        } else if (hasVideo && hasExistingThumbnail && removeThumbnailChecked && !hasNewThumbnail) {
            // Video var, thumbnail var amma silinmək istənir - yeni thumbnail tələb et
            if (thumbnailStar) thumbnailStar.style.display = 'inline';
            if (videoThumbnailWarning) videoThumbnailWarning.style.display = 'block';
            if (thumbnailInput) thumbnailInput.setAttribute('required', 'required');
        } else {
            // Video yoxdur və ya thumbnail var
            if (thumbnailStar) thumbnailStar.style.display = 'none';
            if (videoThumbnailWarning) videoThumbnailWarning.style.display = 'none';
            if (thumbnailInput) thumbnailInput.removeAttribute('required');
        }
        
        // Video preview
        if (hasVideo) {
            const embedUrl = getEmbedUrl(videoUrlInput.value);
            if (embedUrl && videoPreviewContainer && videoPreviewIframe) {
                videoPreviewIframe.src = embedUrl;
                videoPreviewContainer.style.display = 'block';
            }
        } else {
            if (videoPreviewContainer) videoPreviewContainer.style.display = 'none';
            if (videoPreviewIframe) videoPreviewIframe.src = '';
        }
    }
    
    // Thumbnail preview
    if (thumbnailInput) {
        thumbnailInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    if (thumbnailPreviewImg) thumbnailPreviewImg.src = event.target.result;
                    if (thumbnailPreview) thumbnailPreview.style.display = 'block';
                };
                reader.readAsDataURL(e.target.files[0]);
            } else {
                if (thumbnailPreview) thumbnailPreview.style.display = 'none';
            }
            checkVideoUrl();
        });
    }
    
    // Event listeners
    if (videoUrlInput) {
        videoUrlInput.addEventListener('input', checkVideoUrl);
        videoUrlInput.addEventListener('change', checkVideoUrl);
    }
    
    // Remove thumbnail checkbox listener
    const removeThumbnailCheckbox = document.getElementById('remove_thumbnail');
    if (removeThumbnailCheckbox) {
        removeThumbnailCheckbox.addEventListener('change', checkVideoUrl);
    }
    
    // Form submit validation
    if (roomForm) {
        roomForm.addEventListener('submit', function(e) {
            const hasVideo = videoUrlInput && videoUrlInput.value.trim() !== '';
            const hasNewThumbnail = thumbnailInput && thumbnailInput.files && thumbnailInput.files.length > 0;
            const hasExistingThumbnail = document.querySelector('.current-thumbnail-box img') !== null;
            const removeThumbnailChecked = document.getElementById('remove_thumbnail')?.checked || false;
            
            if (hasVideo) {
                // Video var - thumbnail olmalıdır
                if (!hasExistingThumbnail && !hasNewThumbnail) {
                    e.preventDefault();
                    alert('{{ __("translate.Please upload a thumbnail image when adding a video") }}');
                    thumbnailInput.focus();
                    thumbnailInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }
                
                if (hasExistingThumbnail && removeThumbnailChecked && !hasNewThumbnail) {
                    e.preventDefault();
                    alert('{{ __("translate.Please upload a new thumbnail image or uncheck remove thumbnail") }}');
                    thumbnailInput.focus();
                    thumbnailInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }
            }
        });
    }
    
    // Initial check
    checkVideoUrl();

    // ==================== MEDIA UPLOAD FUNCTIONS ====================
    @if (isset($room))
    // Image Upload
    document.getElementById('imageUpload').addEventListener('change', function(e) {
        const files = e.target.files;
        for (let i = 0; i < files.length; i++) {
            uploadMedia(files[i], 'image');
        }
        e.target.value = '';
    });

    window.uploadMedia = function(file, type) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('media_type', type);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route('admin.tourbooking.services.rooms.media.upload', [$service->id, $room->id]) }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success(data.message);
                    location.reload();
                } else {
                    toastr.error(data.message || 'Upload failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('An error occurred during upload');
            });
    }

    window.addVideoUrl = function() {
        const videoUrl = document.getElementById('additionalVideoUrl').value;
        if (!videoUrl) {
            toastr.error('Please enter a video URL');
            return;
        }

        fetch('{{ route('admin.tourbooking.services.rooms.media.video', [$service->id, $room->id]) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    video_url: videoUrl
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success(data.message);
                    location.reload();
                } else {
                    toastr.error(data.message || 'Failed to add video');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('An error occurred');
            });
    }

    window.setAsThumbnail = function(mediaId) {
        fetch(`/admin/tourbooking/room-media/${mediaId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    is_thumbnail: true
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success(data.message);
                    location.reload();
                } else {
                    toastr.error(data.message || 'Failed to set thumbnail');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('An error occurred');
            });
    }

    window.deleteMedia = function(mediaId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '{{ __('translate.Are you sure?') }}',
                text: '{{ __('translate.This media will be deleted permanently.') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '{{ __('translate.Yes, delete it!') }}',
                cancelButtonText: '{{ __('translate.Cancel') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    performDeleteMedia(mediaId);
                }
            });
        } else {
            if (confirm('{{ __("translate.Are you sure you want to delete this media?") }}')) {
                performDeleteMedia(mediaId);
            }
        }
    }

    function performDeleteMedia(mediaId) {
        fetch(`/admin/tourbooking/room-media/${mediaId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success(data.message);
                    document.querySelector(`[data-media-id="${mediaId}"]`).remove();
                } else {
                    toastr.error(data.message || 'Failed to delete media');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('An error occurred');
            });
    }
    @endif
});
</script>
@endpush