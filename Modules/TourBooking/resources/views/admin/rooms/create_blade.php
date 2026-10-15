@extends('admin.master_layout')

@section('title')
    <title>{{ __('translate.Create Room') }} - {{ $service->name }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Create Room') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Add new room for') }}: {{ $service->name }}</p>
@endsection

@section('body-content')
    <section class="crancy-addsection crancy-show">
        <div class="row">
            <div class="col-12">
                <div class="crancy-body">
                    <div class="crancy-dsinner">
                        <form id="roomForm" action="{{ route('admin.tourbooking.services.rooms.store', $service->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <!-- Left Column - Basic Info -->
                                <div class="col-lg-8">
                                    <!-- Basic Information Card -->
                                    <div class="crancy-ptabs__form-main crancy-ptabs__form-main--update mb-4">
                                        <div class="crancy-ptabs__form-title">
                                            <h4 class="crancy-ptabs__form-title__text">
                                                <i class="fa fa-info-circle me-2"></i>{{ __('translate.Basic Information') }}
                                            </h4>
                                        </div>

                                        <div class="row">
                                            <!-- Room Name -->
                                            <div class="col-md-8">
                                                <div class="crancy__item-form--group mg-top-20">
                                                    <label class="crancy__item-label">
                                                        {{ __('translate.Room Name') }} <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" 
                                                           name="name" 
                                                           class="crancy__item-input @error('name') is-invalid @enderror"
                                                           value="{{ old('name') }}"
                                                           placeholder="{{ __('translate.Enter room name') }}"
                                                           required>
                                                    @error('name')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Status -->
                                            <div class="col-md-4">
                                                <div class="crancy__item-form--group mg-top-20">
                                                    <label class="crancy__item-label">{{ __('translate.Status') }}</label>
                                                    <select name="status" class="crancy__item-input">
                                                        <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>
                                                            {{ __('translate.Active') }}
                                                        </option>
                                                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>
                                                            {{ __('translate.Inactive') }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Low Stock Badge -->
                                            <div class="col-md-4">
                                                <div class="crancy__item-form--group mg-top-20">
                                                    <label class="crancy__item-label">{{ __('translate.Low Stock Badge') }}</label>
                                                    <select name="low_stock_show" class="crancy__item-input">
                                                        <option value="0" {{ old('low_stock_show', 0) == 0 ? 'selected' : '' }}>
                                                            {{ __('translate.Hidden') }}
                                                        </option>
                                                        <option value="1" {{ old('low_stock_show') == 1 ? 'selected' : '' }}>
                                                            {{ __('translate.Visible') }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Low Stock Text -->
                                            <div class="col-md-8">
                                                <div class="crancy__item-form--group mg-top-20">
                                                    <label class="crancy__item-label">{{ __('translate.Badge text') }}</label>
                                                    <input type="text" name="low_stock_text"
                                                           class="crancy__item-input @error('low_stock_text') is-invalid @enderror"
                                                           value="{{ old('low_stock_text') }}"
                                                           placeholder="Осталось только 1 номер!">
                                                    @error('low_stock_text')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Description -->
                                            <div class="col-12">
                                                <div class="crancy__item-form--group mg-top-20">
                                                    <label class="crancy__item-label">{{ __('translate.Description') }}</label>
                                                    <textarea name="description"
                                                              class="crancy__item-input crancy__item-textarea @error('description') is-invalid @enderror"
                                                              rows="4"
                                                              placeholder="{{ __('translate.Enter room description') }}">{{ old('description') }}</textarea>
                                                    @error('description')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pricing Card -->
                                    <div class="crancy-ptabs__form-main crancy-ptabs__form-main--update mb-4">
                                        <div class="crancy-ptabs__form-title">
                                            <h4 class="crancy-ptabs__form-title__text">
                                                <i class="fa fa-money-bill me-2"></i>{{ __('translate.Pricing') }}
                                            </h4>
                                        </div>

                                        <div class="row">
                                            <!-- Price Per Night -->
                                            <div class="col-md-4">
                                                <div class="crancy__item-form--group mg-top-20">
                                                    <label class="crancy__item-label">
                                                        {{ __('translate.Price Per Night') }} <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">{{ default_currency()['currency_icon'] }}</span>
                                                        <input type="number" 
                                                               name="price_per_night" 
                                                               class="form-control @error('price_per_night') is-invalid @enderror"
                                                               value="{{ old('price_per_night') }}"
                                                               step="0.01"
                                                               min="0"
                                                               placeholder="0.00"
                                                               required>
                                                    </div>
                                                    @error('price_per_night')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Discount Price -->
                                            <div class="col-md-4">
                                                <div class="crancy__item-form--group mg-top-20">
                                                    <label class="crancy__item-label">{{ __('translate.Discount Price') }}</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">{{ default_currency()['currency_icon'] }}</span>
                                                        <input type="number" 
                                                               name="discount_price" 
                                                               class="form-control @error('discount_price') is-invalid @enderror"
                                                               value="{{ old('discount_price') }}"
                                                               step="0.01"
                                                               min="0"
                                                               placeholder="0.00">
                                                    </div>
                                                    @error('discount_price')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror
                                                    <small class="text-muted">{{ __('translate.Leave empty for no discount') }}</small>
                                                </div>
                                            </div>

                                            <!-- Total Rooms -->
                                            <div class="col-md-4">
                                                <div class="crancy__item-form--group mg-top-20">
                                                    <label class="crancy__item-label">
                                                        {{ __('translate.Total Rooms') }} <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="number" 
                                                           name="total_rooms" 
                                                           class="crancy__item-input @error('total_rooms') is-invalid @enderror"
                                                           value="{{ old('total_rooms', 1) }}"
                                                           min="1"
                                                           required>
                                                    @error('total_rooms')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Room Details Card -->
                                    <div class="crancy-ptabs__form-main crancy-ptabs__form-main--update mb-4">
                                        <div class="crancy-ptabs__form-title">
                                            <h4 class="crancy-ptabs__form-title__text">
                                                <i class="fa fa-bed me-2"></i>{{ __('translate.Room Details') }}
                                            </h4>
                                        </div>

                                        <div class="row">
                                            <!-- Max Adults -->
                                            <div class="col-md-3">
                                                <div class="crancy__item-form--group mg-top-20">
                                                    <label class="crancy__item-label">
                                                        {{ __('translate.Max Adults') }} <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="number" 
                                                           name="max_adults" 
                                                           class="crancy__item-input @error('max_adults') is-invalid @enderror"
                                                           value="{{ old('max_adults', 2) }}"
                                                           min="1"
                                                           max="20"
                                                           required>
                                                    @error('max_adults')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Max Children -->
                                            <div class="col-md-3">
                                                <div class="crancy__item-form--group mg-top-20">
                                                    <label class="crancy__item-label">{{ __('translate.Max Children') }}</label>
                                                    <input type="number" 
                                                           name="max_children" 
                                                           class="crancy__item-input @error('max_children') is-invalid @enderror"
                                                           value="{{ old('max_children', 0) }}"
                                                           min="0"
                                                           max="10">
                                                    @error('max_children')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Room Size -->
                                            <div class="col-md-3">
                                                <div class="crancy__item-form--group mg-top-20">
                                                    <label class="crancy__item-label">{{ __('translate.Room Size') }}</label>
                                                    <div class="input-group">
                                                        <input type="number" 
                                                               name="room_size" 
                                                               class="form-control @error('room_size') is-invalid @enderror"
                                                               value="{{ old('room_size') }}"
                                                               min="0"
                                                               placeholder="25">
                                                        <span class="input-group-text">m²</span>
                                                    </div>
                                                    @error('room_size')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Bed Count -->
                                            <div class="col-md-3">
                                                <div class="crancy__item-form--group mg-top-20">
                                                    <label class="crancy__item-label">{{ __('translate.Bed Count') }}</label>
                                                    <input type="number" 
                                                           name="bed_count" 
                                                           class="crancy__item-input @error('bed_count') is-invalid @enderror"
                                                           value="{{ old('bed_count', 1) }}"
                                                           min="1"
                                                           max="10">
                                                    @error('bed_count')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Bed Type -->
                                            <div class="col-md-6">
                                                <div class="crancy__item-form--group mg-top-20">
                                                    <label class="crancy__item-label">{{ __('translate.Bed Type') }}</label>
                                                    <select name="bed_type" class="crancy__item-input">
                                                        <option value="">{{ __('translate.Select Bed Type') }}</option>
                                                        <option value="single" {{ old('bed_type') == 'single' ? 'selected' : '' }}>
                                                            {{ __('translate.Single Bed') }}
                                                        </option>
                                                        <option value="double" {{ old('bed_type') == 'double' ? 'selected' : '' }}>
                                                            {{ __('translate.Double Bed') }}
                                                        </option>
                                                        <option value="queen" {{ old('bed_type') == 'queen' ? 'selected' : '' }}>
                                                            {{ __('translate.Queen Bed') }}
                                                        </option>
                                                        <option value="king" {{ old('bed_type') == 'king' ? 'selected' : '' }}>
                                                            {{ __('translate.King Bed') }}
                                                        </option>
                                                        <option value="twin" {{ old('bed_type') == 'twin' ? 'selected' : '' }}>
                                                            {{ __('translate.Twin Beds') }}
                                                        </option>
                                                        <option value="sofa_bed" {{ old('bed_type') == 'sofa_bed' ? 'selected' : '' }}>
                                                            {{ __('translate.Sofa Bed') }}
                                                        </option>
                                                        <option value="bunk" {{ old('bed_type') == 'bunk' ? 'selected' : '' }}>
                                                            {{ __('translate.Bunk Bed') }}
                                                        </option>
                                                    </select>
                                                    @error('bed_type')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Amenities Card -->
                                    <div class="crancy-ptabs__form-main crancy-ptabs__form-main--update mb-4">
                                        <div class="crancy-ptabs__form-title">
                                            <h4 class="crancy-ptabs__form-title__text">
                                                <i class="fa fa-concierge-bell me-2"></i>{{ __('translate.Room Amenities') }}
                                            </h4>
                                        </div>

                                        <div class="row mg-top-20">
                                            @php
                                                $amenities = [
                                                    'wifi' => ['icon' => 'fa-wifi', 'label' => 'Free WiFi'],
                                                    'ac' => ['icon' => 'fa-snowflake', 'label' => 'Air Conditioning'],
                                                    'tv' => ['icon' => 'fa-tv', 'label' => 'Flat Screen TV'],
                                                    'minibar' => ['icon' => 'fa-wine-bottle', 'label' => 'Minibar'],
                                                    'safe' => ['icon' => 'fa-lock', 'label' => 'In-Room Safe'],
                                                    'hairdryer' => ['icon' => 'fa-wind', 'label' => 'Hair Dryer'],
                                                    'iron' => ['icon' => 'fa-tshirt', 'label' => 'Iron & Board'],
                                                    'coffee' => ['icon' => 'fa-coffee', 'label' => 'Coffee Maker'],
                                                    'balcony' => ['icon' => 'fa-door-open', 'label' => 'Balcony'],
                                                    'bathtub' => ['icon' => 'fa-bath', 'label' => 'Bathtub'],
                                                    'shower' => ['icon' => 'fa-shower', 'label' => 'Shower'],
                                                    'desk' => ['icon' => 'fa-desktop', 'label' => 'Work Desk'],
                                                    'wardrobe' => ['icon' => 'fa-door-closed', 'label' => 'Wardrobe'],
                                                    'telephone' => ['icon' => 'fa-phone', 'label' => 'Telephone'],
                                                    'slippers' => ['icon' => 'fa-socks', 'label' => 'Slippers'],
                                                    'toiletries' => ['icon' => 'fa-pump-soap', 'label' => 'Toiletries'],
                                                    'room_service' => ['icon' => 'fa-concierge-bell', 'label' => 'Room Service'],
                                                    'sea_view' => ['icon' => 'fa-water', 'label' => 'Sea View'],
                                                ];
                                                $oldAmenities = old('amenities', []);
                                            @endphp

                                            @foreach($amenities as $key => $amenity)
                                                <div class="col-md-3 col-6 mb-3">
                                                    <div class="form-check">
                                                        <input type="checkbox" 
                                                               name="amenities[]" 
                                                               value="{{ $key }}"
                                                               class="form-check-input"
                                                               id="amenity_{{ $key }}"
                                                               {{ in_array($key, $oldAmenities) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="amenity_{{ $key }}">
                                                            <i class="fa {{ $amenity['icon'] }} me-1 text-primary"></i>
                                                            {{ __('translate.' . $amenity['label']) }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Features Card -->
                                    <div class="crancy-ptabs__form-main crancy-ptabs__form-main--update mb-4">
                                        <div class="crancy-ptabs__form-title">
                                            <h4 class="crancy-ptabs__form-title__text">
                                                <i class="fa fa-list-check me-2"></i>{{ __('translate.Additional Features') }}
                                            </h4>
                                        </div>

                                        <div class="mg-top-20" id="features-container">
                                            <div class="feature-item row mb-2" data-index="0">
                                                <div class="col-10">
                                                    <input type="text" 
                                                           name="features[]" 
                                                           class="crancy__item-input"
                                                           placeholder="{{ __('translate.Enter feature') }}">
                                                </div>
                                                <div class="col-2">
                                                    <button type="button" class="btn btn-danger btn-sm remove-feature" style="display: none;">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-feature">
                                            <i class="fa fa-plus me-1"></i> {{ __('translate.Add Feature') }}
                                        </button>
                                    </div>
                                </div>

                                <!-- Right Column - Media -->
                                <div class="col-lg-4">
                                    {{-- ==================== VIDEO & THUMBNAIL SECTION START ==================== --}}
                                    <div class="crancy-ptabs__form-main crancy-ptabs__form-main--update mb-4">
                                        <div class="crancy-ptabs__form-title" style="background: linear-gradient(135deg, #17a2b8, #138496); padding: 15px; border-radius: 8px 8px 0 0; margin: -20px -20px 20px -20px;">
                                            <h4 class="crancy-ptabs__form-title__text text-white mb-0">
                                                <i class="fa fa-video me-2"></i>{{ __('translate.Video & Thumbnail') }}
                                            </h4>
                                        </div>

                                        {{-- Thumbnail Image --}}
                                        <div class="crancy__item-form--group mb-4">
                                            <label class="crancy__item-label">
                                                {{ __('translate.Thumbnail Image') }}
                                                <span class="text-danger thumbnail-required-star" id="thumbnailStar" style="display: none;">
                                                    * {{ __('translate.Required') }}
                                                </span>
                                            </label>
                                            <div class="crancy-wc__form-upload crancy-wc__form-file">
                                                <input type="file" 
                                                       name="thumbnail" 
                                                       id="thumbnail"
                                                       class="btn-check @error('thumbnail') is-invalid @enderror"
                                                       accept="image/jpeg,image/png,image/jpg,image/webp">
                                                <label class="crancy-wc__form-upload--label" for="thumbnail">
                                                    <span>
                                                        <i class="fa fa-image me-2"></i>
                                                        {{ __('translate.Click to upload thumbnail') }}
                                                    </span>
                                                </label>
                                            </div>
                                            @error('thumbnail')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                            <small class="text-muted d-block mt-2">
                                                <i class="fa fa-info-circle me-1"></i>
                                                {{ __('translate.Recommended: 800x600px. Formats: JPG, PNG, WebP') }}
                                            </small>

                                            {{-- Video warning message --}}
                                            <div class="alert alert-warning mt-3 video-thumbnail-warning" id="videoThumbnailWarning" style="display: none;">
                                                <i class="fa fa-exclamation-triangle me-2"></i>
                                                <strong>{{ __('translate.Thumbnail Required!') }}</strong>
                                                <br><small>{{ __('translate.Please upload a thumbnail when adding a video.') }}</small>
                                            </div>

                                            {{-- Thumbnail Preview --}}
                                            <div id="thumbnailPreview" class="mt-3" style="display: none;">
                                                <label class="d-block mb-2"><strong>{{ __('translate.Preview') }}:</strong></label>
                                                <img id="thumbnailPreviewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 150px;">
                                            </div>
                                        </div>

                                        {{-- Video URL --}}
                                        <div class="crancy__item-form--group">
                                            <label class="crancy__item-label">{{ __('translate.Video URL') }}</label>
                                            <input type="url" 
                                                   name="video_url"
                                                   id="video_url" 
                                                   class="crancy__item-input @error('video_url') is-invalid @enderror"
                                                   value="{{ old('video_url') }}"
                                                   placeholder="https://www.youtube.com/watch?v=...">
                                            @error('video_url')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                            <small class="text-muted d-block mt-1">
                                                <i class="fa fa-info-circle me-1"></i>
                                                {{ __('translate.YouTube or Vimeo URL') }}
                                            </small>

                                            {{-- Video Preview --}}
                                            <div id="videoPreviewContainer" class="mt-3" style="display: none;">
                                                <label class="d-block mb-2"><strong>{{ __('translate.Video Preview') }}:</strong></label>
                                                <div class="ratio ratio-16x9 rounded overflow-hidden">
                                                    <iframe id="videoPreviewIframe" src="" allowfullscreen></iframe>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Info Box --}}
                                        <div class="alert alert-info mt-3 mb-0">
                                            <i class="fa fa-lightbulb me-2"></i>
                                            <small>{{ __('translate.Video helps guests understand your room better. Thumbnail is required with video.') }}</small>
                                        </div>
                                    </div>
                                    {{-- ==================== VIDEO & THUMBNAIL SECTION END ==================== --}}

                                    <!-- Room Images Card - Enhanced Multi Upload -->
                                    <div class="crancy-ptabs__form-main crancy-ptabs__form-main--update mb-4">
                                        <div class="crancy-ptabs__form-title" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); padding: 15px; border-radius: 8px 8px 0 0; margin: -20px -20px 20px -20px;">
                                            <h4 class="crancy-ptabs__form-title__text text-white mb-0">
                                                <i class="fa fa-images me-2"></i>{{ __('translate.Room Gallery Images') }}
                                            </h4>
                                        </div>

                                        <div class="mg-top-10">
                                            {{-- Drag & Drop Zone --}}
                                            <div class="gallery-dropzone" id="galleryDropzone">
                                                <input type="file" 
                                                       name="images[]" 
                                                       id="room_images"
                                                       class="gallery-file-input @error('images') is-invalid @enderror"
                                                       accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                                       multiple>
                                                <div class="gallery-dropzone-content">
                                                    <div class="gallery-dropzone-icon">
                                                        <i class="fa fa-cloud-arrow-up"></i>
                                                    </div>
                                                    <h5 class="gallery-dropzone-title">{{ __('translate.Drag & drop images here') }}</h5>
                                                    <p class="gallery-dropzone-text">{{ __('translate.or click to browse') }}</p>
                                                    <span class="gallery-dropzone-formats">JPEG, PNG, JPG, GIF, WEBP — max 2MB {{ __('translate.per file') }}</span>
                                                </div>
                                            </div>
                                            @error('images')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                            @error('images.*')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror

                                            {{-- Selected Images Preview Grid --}}
                                            <div id="galleryPreviewGrid" class="gallery-preview-grid mt-3"></div>

                                            {{-- Selected Count --}}
                                            <div id="galleryCount" class="gallery-count" style="display:none;">
                                                <i class="fa fa-images me-1"></i>
                                                <span id="galleryCountText">0</span> {{ __('translate.images selected') }}
                                                <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="clearAllGalleryImages()">
                                                    <i class="fa fa-trash me-1"></i>{{ __('translate.Clear All') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quick Info Card -->
                                    <div class="crancy-ptabs__form-main crancy-ptabs__form-main--update mb-4">
                                        <div class="crancy-ptabs__form-title">
                                            <h4 class="crancy-ptabs__form-title__text">
                                                <i class="fa fa-info me-2"></i>{{ __('translate.Quick Info') }}
                                            </h4>
                                        </div>

                                        <div class="mg-top-20">
                                            <div class="alert alert-info">
                                                <i class="fa fa-lightbulb me-2"></i>
                                                <strong>{{ __('translate.Tips') }}:</strong>
                                                <ul class="mb-0 mt-2 ps-3">
                                                    <li>{{ __('translate.Upload high quality room images') }}</li>
                                                    <li>{{ __('translate.Set accurate room capacity') }}</li>
                                                    <li>{{ __('translate.Select all available amenities') }}</li>
                                                    <li>{{ __('translate.Add unique features to stand out') }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="crancy-ptabs__form-main crancy-ptabs__form-main--update">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="{{ route('admin.tourbooking.services.rooms.index', $service->id) }}" 
                                               class="btn btn-outline-secondary">
                                                <i class="fa fa-arrow-left me-2"></i>{{ __('translate.Back') }}
                                            </a>
                                            <div>
                                                <button type="reset" class="btn btn-outline-warning me-2">
                                                    <i class="fa fa-redo me-2"></i>{{ __('translate.Reset') }}
                                                </button>
                                                <button type="submit" class="crancy-btn">
                                                    <i class="fa fa-save me-2"></i>{{ __('translate.Create Room') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js_section')
<script>
    (function($) {
        "use strict";

        $(document).ready(function() {
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
                const hasNewThumbnail = thumbnailInput && thumbnailInput.files && thumbnailInput.files.length > 0;
                
                if (hasVideo && !hasNewThumbnail) {
                    // Video var amma thumbnail yoxdur - məcburi et
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
            
            // Form submit validation
            if (roomForm) {
                roomForm.addEventListener('submit', function(e) {
                    const hasVideo = videoUrlInput && videoUrlInput.value.trim() !== '';
                    const hasNewThumbnail = thumbnailInput && thumbnailInput.files && thumbnailInput.files.length > 0;
                    
                    if (hasVideo && !hasNewThumbnail) {
                        e.preventDefault();
                        alert('{{ __("translate.Please upload a thumbnail image when adding a video") }}');
                        thumbnailInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return false;
                    }

                    // Check price validation
                    const price = parseFloat($('[name="price_per_night"]').val());
                    const discount = parseFloat($('[name="discount_price"]').val());

                    if (discount && discount >= price) {
                        e.preventDefault();
                        alert('{{ __("translate.Discount price must be less than regular price") }}');
                        return false;
                    }
                });
            }
            
            // Initial check
            checkVideoUrl();

            // ==================== FEATURE MANAGEMENT ====================
            let featureIndex = 0;

            $('#add-feature').on('click', function() {
                featureIndex++;
                const newFeature = `
                    <div class="feature-item row mb-2" data-index="${featureIndex}">
                        <div class="col-10">
                            <input type="text" 
                                   name="features[]" 
                                   class="crancy__item-input"
                                   placeholder="{{ __('translate.Enter feature') }}">
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-danger btn-sm remove-feature">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                `;
                $('#features-container').append(newFeature);
                updateRemoveButtons();
            });

            $(document).on('click', '.remove-feature', function() {
                $(this).closest('.feature-item').remove();
                updateRemoveButtons();
            });

            function updateRemoveButtons() {
                const items = $('.feature-item');
                if (items.length > 1) {
                    $('.remove-feature').show();
                } else {
                    $('.remove-feature').hide();
                }
            }

            // ==================== ENHANCED GALLERY IMAGE UPLOAD ====================
            const galleryDropzone = document.getElementById('galleryDropzone');
            const galleryInput = document.getElementById('room_images');
            const galleryPreviewGrid = document.getElementById('galleryPreviewGrid');
            const galleryCount = document.getElementById('galleryCount');
            const galleryCountText = document.getElementById('galleryCountText');
            let galleryDataTransfer = new DataTransfer();

            // Drag & Drop
            if (galleryDropzone) {
                ['dragenter', 'dragover'].forEach(evt => {
                    galleryDropzone.addEventListener(evt, e => {
                        e.preventDefault(); e.stopPropagation();
                        galleryDropzone.classList.add('dragover');
                    });
                });
                ['dragleave', 'drop'].forEach(evt => {
                    galleryDropzone.addEventListener(evt, e => {
                        e.preventDefault(); e.stopPropagation();
                        galleryDropzone.classList.remove('dragover');
                    });
                });
                galleryDropzone.addEventListener('drop', e => {
                    addGalleryFiles(e.dataTransfer.files);
                });
            }

            if (galleryInput) {
                galleryInput.addEventListener('change', function() {
                    addGalleryFiles(this.files);
                });
            }

            function addGalleryFiles(files) {
                if (!files || files.length === 0) return;
                const validTypes = ['image/jpeg','image/png','image/jpg','image/gif','image/webp'];
                
                Array.from(files).forEach(file => {
                    if (!validTypes.includes(file.type)) {
                        toastr ? toastr.error(file.name + ': {{ __("translate.Invalid file type") }}') : alert(file.name + ': Invalid type');
                        return;
                    }
                    if (file.size > 2 * 1024 * 1024) {
                        toastr ? toastr.error(file.name + ': {{ __("translate.File too large (max 2MB)") }}') : alert(file.name + ': Too large');
                        return;
                    }
                    galleryDataTransfer.items.add(file);

                    const reader = new FileReader();
                    const idx = galleryDataTransfer.files.length - 1;
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'gallery-preview-item';
                        div.dataset.idx = idx;
                        div.innerHTML = `
                            <div class="gallery-preview-img-wrap">
                                <img src="${e.target.result}" alt="">
                                <div class="gallery-preview-overlay">
                                    <button type="button" class="gallery-preview-remove" data-idx="${idx}">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <span class="gallery-preview-name">${file.name.length > 15 ? file.name.substring(0,12) + '...' : file.name}</span>
                        `;
                        galleryPreviewGrid.appendChild(div);
                        
                        div.querySelector('.gallery-preview-remove').addEventListener('click', function() {
                            removeGalleryFile(parseInt(this.dataset.idx));
                        });
                    };
                    reader.readAsDataURL(file);
                });

                galleryInput.files = galleryDataTransfer.files;
                updateGalleryCount();
            }

            function removeGalleryFile(idx) {
                const newDT = new DataTransfer();
                Array.from(galleryDataTransfer.files).forEach((f, i) => {
                    if (i !== idx) newDT.items.add(f);
                });
                galleryDataTransfer = newDT;
                galleryInput.files = galleryDataTransfer.files;

                // Rebuild preview
                galleryPreviewGrid.innerHTML = '';
                Array.from(galleryDataTransfer.files).forEach((file, i) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'gallery-preview-item';
                        div.dataset.idx = i;
                        div.innerHTML = `
                            <div class="gallery-preview-img-wrap">
                                <img src="${e.target.result}" alt="">
                                <div class="gallery-preview-overlay">
                                    <button type="button" class="gallery-preview-remove" data-idx="${i}">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <span class="gallery-preview-name">${file.name.length > 15 ? file.name.substring(0,12) + '...' : file.name}</span>
                        `;
                        galleryPreviewGrid.appendChild(div);
                        div.querySelector('.gallery-preview-remove').addEventListener('click', function() {
                            removeGalleryFile(parseInt(this.dataset.idx));
                        });
                    };
                    reader.readAsDataURL(file);
                });
                updateGalleryCount();
            }

            window.clearAllGalleryImages = function() {
                galleryDataTransfer = new DataTransfer();
                galleryInput.files = galleryDataTransfer.files;
                galleryPreviewGrid.innerHTML = '';
                updateGalleryCount();
            };

            function updateGalleryCount() {
                const count = galleryDataTransfer.files.length;
                if (count > 0) {
                    galleryCount.style.display = 'flex';
                    galleryCountText.textContent = count;
                } else {
                    galleryCount.style.display = 'none';
                }
            }
        });
    })(jQuery);
</script>
@endpush

@push('css_section')
<style>
    .form-check-input:checked {
        background-color: var(--crancy-primary);
        border-color: var(--crancy-primary);
    }
    
    .feature-item {
        align-items: center;
    }
    
    .remove-feature {
        height: 38px;
        width: 100%;
    }
    
    .crancy-wc__form-upload--label {
        border: 2px dashed #e0e0e0;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .crancy-wc__form-upload--label:hover {
        border-color: var(--crancy-primary);
        background-color: rgba(var(--crancy-primary-rgb), 0.05);
    }
    
    .video-thumbnail-warning {
        background-color: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 5px;
        padding: 10px;
        font-size: 13px;
    }

    .thumbnail-required-star {
        color: #dc3545;
        font-weight: bold;
        font-size: 12px;
    }

    /* ===== Gallery Dropzone ===== */
    .gallery-dropzone {
        position: relative;
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 35px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fafbfc;
    }
    .gallery-dropzone:hover, .gallery-dropzone.dragover {
        border-color: #6366f1;
        background: #f0f0ff;
    }
    .gallery-dropzone.dragover { border-style: solid; }
    .gallery-file-input {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        z-index: 10;
    }
    .gallery-dropzone-icon {
        font-size: 40px;
        color: #94a3b8;
        margin-bottom: 8px;
        transition: color 0.3s;
    }
    .gallery-dropzone:hover .gallery-dropzone-icon { color: #6366f1; }
    .gallery-dropzone-title { font-size: 16px; font-weight: 600; color: #334155; margin-bottom: 4px; }
    .gallery-dropzone-text { font-size: 14px; color: #64748b; margin-bottom: 8px; }
    .gallery-dropzone-formats { font-size: 12px; color: #94a3b8; background: #f1f5f9; padding: 4px 14px; border-radius: 20px; }

    /* ===== Gallery Preview Grid ===== */
    .gallery-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 10px;
    }
    .gallery-preview-grid:empty { display: none; }
    .gallery-preview-item {
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    .gallery-preview-item:hover { border-color: #6366f1; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .gallery-preview-img-wrap {
        position: relative;
        aspect-ratio: 4/3;
        overflow: hidden;
    }
    .gallery-preview-img-wrap img { width: 100%; height: 100%; object-fit: cover; }
    .gallery-preview-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.45);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.25s;
    }
    .gallery-preview-item:hover .gallery-preview-overlay { opacity: 1; }
    .gallery-preview-remove {
        width: 32px; height: 32px;
        background: #ef4444; border: none; border-radius: 50%;
        color: #fff; font-size: 13px; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: transform 0.2s;
    }
    .gallery-preview-remove:hover { transform: scale(1.15); background: #dc2626; }
    .gallery-preview-name {
        display: block;
        text-align: center;
        padding: 4px 6px;
        font-size: 11px;
        color: #64748b;
        background: #f8fafc;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .gallery-count {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 10px 16px;
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #0369a1;
        margin-top: 12px;
    }
</style>
@endpush