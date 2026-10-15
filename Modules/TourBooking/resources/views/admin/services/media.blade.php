@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Media Gallery') }} - {{ $service->translation->title ?? $service->title }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Media Gallery') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Tour Booking') }} >> {{ __('translate.Services') }} >>
        {{ __('translate.Media Gallery') }}</p>
@endsection

@push('style_section')
    <style>
        .media-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .media-item {
            position: relative;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .media-item img,
        .media-item video {
            width: 100%;
            height: 180px;
            object-fit: cover;
            display: block;
        }

        .media-item {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: grab;
        }
        .media-item.sortable-ghost {
            opacity: 0.4;
            transform: scale(0.95);
        }
        .media-item.sortable-drag {
            box-shadow: 0 12px 40px rgba(0,0,0,0.2);
            transform: rotate(2deg) scale(1.05);
            z-index: 999;
        }
        .media-item.sortable-chosen {
            cursor: grabbing;
        }

        .media-item-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 5px;
        }

        .media-item-checkbox {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 5;
        }
        .media-item-checkbox input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #f43f5e;
        }
        .media-item.selected {
            border-color: #f43f5e;
            box-shadow: 0 0 0 2px rgba(244, 63, 94, 0.3);
        }
        .media-order-badge {
            position: absolute;
            bottom: 50px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .bulk-actions-bar {
            display: none;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            margin-bottom: 16px;
        }
        .bulk-actions-bar.active { display: flex; }

        .media-item-footer {
            padding: 10px;
            background-color: #f8f9fa;
            border-top: 1px solid #e0e0e0;
        }

        .media-caption {
            font-size: 13px;
            color: #666;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .thumbnail-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #28a745;
            color: white;
            border-radius: 3px;
            padding: 3px 6px;
            font-size: 12px;
        }

        .media-upload-card {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            background-color: #f9f9f9;
        }

        .uploader-icon {
            font-size: 48px;
            color: #aaa;
            margin-bottom: 15px;
        }

        .media-type-badge {
            position: absolute;
            bottom: 10px;
            left: 10px;
            border-radius: 3px;
            padding: 3px 6px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .media-type-badge.image {
            background-color: #007bff;
            color: white;
        }

        .media-type-badge.video {
            background-color: #dc3545;
            color: white;
        }
    </style>
@endpush

@section('body-content')
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">
                            <div class="row">
                                <div class="col-12 mg-top-30">
                                    <div class="crancy-product-card">
                                        <div class="create_new_btn_inline_box">
                                            <h4 class="crancy-product-card__title">{{ __('translate.Media Gallery for') }}:
                                                {{ $service->translation->title ?? $service->title }}</h4>
                                            <div>
                                                <a href="{{ route('admin.tourbooking.services.edit', ['service' => $service->id, 'lang_code' => 'ru']) }}"
                                                    class="crancy-btn"><i class="fa fa-edit"></i>
                                                    {{ __('translate.Edit Service') }}</a>
                                                <a href="{{ route('admin.tourbooking.services.index') }}"
                                                    class="crancy-btn"><i class="fa fa-list"></i>
                                                    {{ __('translate.Service List') }}</a>
                                            </div>
                                        </div>

                                        <div class="row mg-top-30">
                                            <div class="col-12">
                                                <div class="accordion" id="mediaAccordion">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingOne">
                                                            <button class="accordion-button" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                                aria-expanded="true" aria-controls="collapseOne">
                                                                {{ __('translate.Upload New Media') }}
                                                            </button>
                                                        </h2>
                                                        <div id="collapseOne" class="accordion-collapse collapse show"
                                                            aria-labelledby="headingOne" data-bs-parent="#mediaAccordion">
                                                            <div class="accordion-body">
                                                                <form
                                                                    action="{{ route('admin.tourbooking.services.media.store', $service->id) }}"
                                                                    method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="row">
                                                                        <div class="col-lg-6 col-md-6 col-12">
                                                                            <div
                                                                                class="crancy__item-form--group mg-top-form-20">
                                                                                <label
                                                                                    class="crancy__item-label">{{ __('translate.Media File') }}
                                                                                    *</label>
                                                                                <div
                                                                                    class="crancy-product-card__upload crancy-product-card__upload--border">
                                                                                    <input type="file" class="btn-check"
                                                                                        name="files[]" id="input-media"
                                                                                        autocomplete="off"
                                                                                        onchange="previewMedia(event)"
                                                                                        multiple
                                                                                        required>
                                                                                    <label
                                                                                        class="crancy-image-video-upload__label"
                                                                                        for="input-media">
                                                                                        <img id="view_media"
                                                                                            src="{{ asset($general_setting->placeholder_image ?? 'admin/img/img-placeholder.jpg') }}">
                                                                                        <h4
                                                                                            class="crancy-image-video-upload__title">
                                                                                            {{ __('translate.Click here to') }}
                                                                                            <span
                                                                                                class="crancy-primary-color">{{ __('translate.Choose File') }}</span>
                                                                                            {{ __('translate.and upload') }}
                                                                                        </h4>
                                                                                    </label>
                                                                                </div>
                                                                                <small
                                                                                    class="form-text text-muted">{{ __('translate.Supported files: jpg, jpeg, png, gif, webp, mp4, avi, mov (Max: 10MB)') }}</small>
                                                                                @error('files')
                                                                                    <span
                                                                                        class="text-danger">{{ $message }}</span>
                                                                                @enderror
                                                                                @error('files.*')
                                                                                    <span
                                                                                        class="text-danger">{{ $message }}</span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-6 col-md-6 col-12">
                                                                            <div
                                                                                class="crancy__item-form--group mg-top-form-20">
                                                                                <label
                                                                                    class="crancy__item-label">{{ __('translate.Caption') }}</label>
                                                                                <input class="crancy__item-input"
                                                                                    type="text" name="caption"
                                                                                    value="{{ old('caption') }}">
                                                                                @error('caption')
                                                                                    <span
                                                                                        class="text-danger">{{ $message }}</span>
                                                                                @enderror

                                                                                <div class="mg-top-30">
                                                                                    <button type="submit"
                                                                                        class="crancy-btn">{{ __('translate.Upload Media') }}</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- ========== BULK UPLOAD SECTION ========== --}}
                                        <div class="row mg-top-30">
                                            <div class="col-12">
                                                <div class="accordion" id="bulkUploadAccordion">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingBulk">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapseBulk"
                                                                aria-expanded="false" aria-controls="collapseBulk">
                                                                <i class="fa fa-images me-2"></i> Toplu Yükləmə (20-yə qədər şəkil)
                                                            </button>
                                                        </h2>
                                                        <div id="collapseBulk" class="accordion-collapse collapse"
                                                            aria-labelledby="headingBulk">
                                                            <div class="accordion-body">
                                                                <form id="bulkUploadForm"
                                                                    action="{{ route('admin.tourbooking.services.media.store', $service->id) }}"
                                                                    method="POST" enctype="multipart/form-data">
                                                                    @csrf

                                                                    {{-- Drop Zone --}}
                                                                    <div id="bulkDropZone" style="border: 3px dashed #d1d5db; border-radius: 16px; padding: 40px 20px; text-align: center; cursor: pointer; transition: all 0.3s ease; background: #f9fafb; position: relative; overflow: hidden;">
                                                                        <input type="file" id="bulkFileInput" name="files[]" multiple accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,video/mp4,video/avi,video/quicktime" style="position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 10;">
                                                                        <div>
                                                                            <i class="fa fa-cloud-arrow-up" style="font-size: 48px; color: #9ca3af; margin-bottom: 12px; display: block;"></i>
                                                                            <h5 style="color: #374151; margin-bottom: 6px;">Şəkilləri bura sürükləyin və ya klikləyin</h5>
                                                                            <p style="color: #9ca3af; font-size: 13px; margin: 0;">Maksimum 20 fayl, hər biri 10MB-a qədər (jpg, png, gif, webp, mp4)</p>
                                                                        </div>
                                                                    </div>

                                                                    {{-- Preview Grid --}}
                                                                    <div id="bulkPreviewGrid" style="display: none; margin-top: 20px;">
                                                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                                                            <span id="bulkFileCount" style="font-weight: 600; color: #374151;"></span>
                                                                            <button type="button" id="bulkClearBtn" style="background: none; border: 1px solid #ef4444; color: #ef4444; padding: 6px 16px; border-radius: 8px; cursor: pointer; font-size: 13px;">
                                                                                <i class="fa fa-trash"></i> Hamısını sil
                                                                            </button>
                                                                        </div>
                                                                        <div id="bulkThumbnails" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 12px;"></div>
                                                                    </div>

                                                                    {{-- Progress --}}
                                                                    <div id="bulkProgress" style="display: none; margin-top: 16px;">
                                                                        <div style="background: #e5e7eb; border-radius: 100px; height: 8px; overflow: hidden;">
                                                                            <div id="bulkProgressBar" style="background: linear-gradient(90deg, #10b981, #059669); height: 100%; width: 0%; transition: width 0.3s ease; border-radius: 100px;"></div>
                                                                        </div>
                                                                        <p id="bulkProgressText" style="font-size: 13px; color: #6b7280; margin-top: 6px; text-align: center;"></p>
                                                                    </div>

                                                                    {{-- Submit --}}
                                                                    <div id="bulkSubmitArea" style="display: none; margin-top: 16px; text-align: right;">
                                                                        <button type="submit" id="bulkSubmitBtn" class="crancy-btn" style="padding: 12px 32px;">
                                                                            <i class="fa fa-upload"></i> Hamısını yüklə
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mg-top-30">
                                            <div class="col-12">
                                                <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
                                                    <h4 class="crancy-product-card__title mb-0">{{ __('translate.Existing Media') }} ({{ $service->media->count() }})</h4>
                                                    @if ($service->media->count() > 0)
                                                        <div style="display:flex;gap:8px;align-items:center;">
                                                            <label style="font-size:13px;cursor:pointer;user-select:none;">
                                                                <input type="checkbox" id="selectAllMedia" style="accent-color:#f43f5e;width:16px;height:16px;margin-right:4px;vertical-align:middle;">
                                                                Hamısını seç
                                                            </label>
                                                            <small style="color:#94a3b8;"><i class="fa fa-grip-dots-vertical"></i> Sürüşdürüb sıranı dəyişin</small>
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Bulk Actions Bar --}}
                                                <div class="bulk-actions-bar" id="bulkActionsBar">
                                                    <span id="selectedCount" style="font-weight:600;color:#dc2626;">0 seçildi</span>
                                                    <button type="button" id="bulkDeleteBtn" class="btn btn-danger btn-sm" style="border-radius:8px;">
                                                        <i class="fa fa-trash"></i> Seçilənləri sil
                                                    </button>
                                                    <button type="button" id="deselectAllBtn" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">
                                                        Seçimi ləğv et
                                                    </button>
                                                </div>

                                                @if ($service->media->count() > 0)
                                                    <div class="media-gallery" id="sortableMediaGallery">
                                                        @foreach ($service->media as $index => $media)
                                                            <div class="media-item" data-id="{{ $media->id }}">
                                                                <div class="media-item-checkbox">
                                                                    <input type="checkbox" class="media-checkbox" value="{{ $media->id }}">
                                                                </div>

                                                                @if ($media->is_thumbnail)
                                                                    <span class="thumbnail-badge">{{ __('translate.Thumbnail') }}</span>
                                                                @endif

                                                                @if ($media->file_type == 'image')
                                                                    <img src="{{ asset($media->file_path) }}" alt="{{ $media->caption ?? $media->file_name }}">
                                                                    <span class="media-type-badge image">{{ __('translate.Image') }}</span>
                                                                @else
                                                                    <video controls muted>
                                                                        <source src="{{ asset($media->file_path) }}" type="video/mp4">
                                                                    </video>
                                                                    <span class="media-type-badge video">{{ __('translate.Video') }}</span>
                                                                @endif

                                                                <span class="media-order-badge">{{ $index + 1 }}</span>

                                                                <div class="media-item-actions">
                                                                    @if ($media->file_type == 'image' && !$media->is_thumbnail)
                                                                        <form action="{{ route('admin.tourbooking.services.media.set-thumbnail', $media->id) }}" method="POST" class="d-inline">
                                                                            @csrf
                                                                            <button type="submit" class="btn btn-sm btn-primary" title="{{ __('translate.Set as Thumbnail') }}"><i class="fa fa-star"></i></button>
                                                                        </form>
                                                                    @endif
                                                                    <form action="{{ route('admin.tourbooking.services.media.destroy', $media->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu şəkili silmək istədiyinizdən əminsiniz?')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-sm btn-danger" title="{{ __('translate.Delete') }}"><i class="fa fa-trash"></i></button>
                                                                    </form>
                                                                </div>

                                                                <div class="media-item-footer">
                                                                    @if ($media->file_type == 'image')
                                                                        <form action="{{ route('admin.tourbooking.services.media.caption', $media->id) }}" method="POST" class="media-caption-form d-flex align-items-center gap-1 mb-1">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <input type="text" name="caption" class="form-control form-control-sm"
                                                                                value="{{ $media->caption }}"
                                                                                placeholder="{{ __('translate.Alt text') }}" maxlength="255">
                                                                            <button type="submit" class="btn btn-sm btn-success" title="{{ __('translate.Save') }}"><i class="fa fa-check"></i></button>
                                                                        </form>
                                                                    @elseif ($media->caption)
                                                                        <div class="media-caption" title="{{ $media->caption }}">{{ $media->caption }}</div>
                                                                    @endif
                                                                    <small class="text-muted">{{ \Carbon\Carbon::parse($media->created_at)->format('M d, Y') }}</small>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="alert alert-info mg-top-20">
                                                        {{ __('translate.No media found. Add your first media item using the form above.') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- ========== PRICE LIST (PDF) SECTION ========== --}}
                                        <div class="row mg-top-30">
                                            <div class="col-12">
                                                <div class="pricelist-admin">
                                                    <h4 class="crancy-product-card__title mb-2">
                                                        <i class="fa fa-file-pdf" style="color:#dc2626;"></i>
                                                        {{ __('translate.Price list') }} (PDF)
                                                    </h4>
                                                    <p style="color:#94a3b8;font-size:13px;margin-bottom:16px;">
                                                        {{ __('translate.Upload price list PDFs. Each gets a label shown above its download button on the site.') }}
                                                    </p>

                                                    {{-- Upload form --}}
                                                    <form action="{{ route('admin.tourbooking.services.price-lists.store', $service->id) }}"
                                                        method="POST" enctype="multipart/form-data"
                                                        style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:12px;padding:18px;margin-bottom:20px;">
                                                        @csrf
                                                        <div class="row align-items-end">
                                                            <div class="col-lg-5 col-md-6 col-12">
                                                                <div class="crancy__item-form--group">
                                                                    <label class="crancy__item-label">{{ __('translate.Label') }} *</label>
                                                                    <input class="crancy__item-input" type="text" name="label"
                                                                        value="{{ old('label') }}"
                                                                        placeholder="{{ __('translate.e.g. Price list 2026') }}" required>
                                                                    @error('label')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-5 col-md-6 col-12">
                                                                <div class="crancy__item-form--group">
                                                                    <label class="crancy__item-label">{{ __('translate.PDF File') }} *</label>
                                                                    <input class="crancy__item-input" type="file" name="file"
                                                                        accept="application/pdf" required>
                                                                    @error('file')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2 col-12">
                                                                <div class="crancy__item-form--group">
                                                                    <button type="submit" class="crancy-btn w-100">
                                                                        <i class="fa fa-upload"></i> {{ __('translate.Upload') }}
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <small class="text-muted">{{ __('translate.Only PDF files (Max: 20MB)') }}</small>
                                                    </form>

                                                    {{-- Existing price lists --}}
                                                    @if ($service->priceLists->count() > 0)
                                                        <div style="display:flex;flex-direction:column;gap:10px;">
                                                            @foreach ($service->priceLists as $priceList)
                                                                <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:12px 16px;">
                                                                    <div style="display:flex;align-items:center;gap:12px;min-width:0;">
                                                                        <i class="fa fa-file-pdf" style="font-size:22px;color:#dc2626;flex-shrink:0;"></i>
                                                                        <div style="min-width:0;">
                                                                            <div style="font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $priceList->label }}</div>
                                                                            <small style="color:#94a3b8;">{{ $priceList->file_name }}</small>
                                                                        </div>
                                                                    </div>
                                                                    <div style="display:flex;align-items:center;gap:6px;flex-shrink:0;">
                                                                        <a href="{{ asset($priceList->file_path) }}" target="_blank"
                                                                            class="btn btn-sm btn-outline-primary" title="{{ __('translate.View') }}">
                                                                            <i class="fa fa-eye"></i>
                                                                        </a>
                                                                        <form action="{{ route('admin.tourbooking.services.price-lists.destroy', $priceList->id) }}"
                                                                            method="POST" class="d-inline"
                                                                            onsubmit="return confirm('{{ __('translate.Are you sure?') }}')">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="btn btn-sm btn-danger" title="{{ __('translate.Delete') }}">
                                                                                <i class="fa fa-trash"></i>
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="alert alert-info mb-0">
                                                            {{ __('translate.No price list uploaded yet.') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js_section')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
    <script>
        function previewMedia(event) {
            var files = event.target.files;
            var output = document.getElementById('view_media');
            var titleEl = document.querySelector('.crancy-image-video-upload__title');

            if (files.length > 1) {
                if (titleEl) titleEl.innerHTML = '<strong>' + files.length + ' файл выбрано</strong>';
                // Show first image as preview
                for (var i = 0; i < files.length; i++) {
                    if (files[i].type.includes('image/')) {
                        var reader = new FileReader();
                        reader.onload = function() { output.src = reader.result; };
                        reader.readAsDataURL(files[i]);
                        break;
                    }
                }
            } else if (files.length === 1) {
                var file = files[0];
                if (file.type.includes('image/')) {
                    var reader = new FileReader();
                    reader.onload = function() { output.src = reader.result; };
                    reader.readAsDataURL(file);
                } else if (file.type.includes('video/')) {
                    output.src = "{{ asset('admin/img/video-placeholder.jpg') }}";
                }
                if (titleEl) titleEl.innerHTML = '{{ __("translate.Click here to") }} <span class="crancy-primary-color">{{ __("translate.Choose File") }}</span> {{ __("translate.and upload") }}';
            }
        };
    </script>

    {{-- Bulk Upload JS --}}
    <script>
    (function() {
        var dropZone = document.getElementById('bulkDropZone');
        var fileInput = document.getElementById('bulkFileInput');
        var previewGrid = document.getElementById('bulkPreviewGrid');
        var thumbnails = document.getElementById('bulkThumbnails');
        var fileCount = document.getElementById('bulkFileCount');
        var clearBtn = document.getElementById('bulkClearBtn');
        var submitArea = document.getElementById('bulkSubmitArea');
        var submitBtn = document.getElementById('bulkSubmitBtn');
        var progressDiv = document.getElementById('bulkProgress');
        var progressBar = document.getElementById('bulkProgressBar');
        var progressText = document.getElementById('bulkProgressText');
        var form = document.getElementById('bulkUploadForm');

        if (!dropZone || !fileInput) return;

        var selectedFiles = [];
        var MAX_FILES = 20;
        var MAX_SIZE = 10 * 1024 * 1024;

        // Drag & drop styling
        ['dragenter', 'dragover'].forEach(function(evt) {
            dropZone.addEventListener(evt, function(e) {
                e.preventDefault();
                dropZone.style.borderColor = '#10b981';
                dropZone.style.background = '#ecfdf5';
            });
        });
        ['dragleave', 'drop'].forEach(function(evt) {
            dropZone.addEventListener(evt, function(e) {
                e.preventDefault();
                dropZone.style.borderColor = '#d1d5db';
                dropZone.style.background = '#f9fafb';
            });
        });

        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            handleFiles(e.dataTransfer.files);
        });

        fileInput.addEventListener('change', function() {
            handleFiles(this.files);
        });

        function handleFiles(files) {
            var arr = Array.from(files);
            var rejected = 0;

            arr.forEach(function(file) {
                if (selectedFiles.length >= MAX_FILES) { rejected++; return; }
                if (file.size > MAX_SIZE) { rejected++; return; }
                if (!file.type.match(/^(image\/(jpeg|png|gif|webp)|video\/(mp4|avi|quicktime))$/)) { rejected++; return; }
                selectedFiles.push(file);
            });

            if (rejected > 0) {
                alert(rejected + ' fayl rədd edildi (limit: ' + MAX_FILES + ' fayl, hər biri max 10MB)');
            }

            updatePreview();
        }

        function updatePreview() {
            thumbnails.innerHTML = '';

            if (selectedFiles.length === 0) {
                previewGrid.style.display = 'none';
                submitArea.style.display = 'none';
                return;
            }

            previewGrid.style.display = 'block';
            submitArea.style.display = 'block';
            fileCount.textContent = selectedFiles.length + ' fayl seçildi (max ' + MAX_FILES + ')';

            selectedFiles.forEach(function(file, idx) {
                var item = document.createElement('div');
                item.style.cssText = 'position:relative;border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;background:#f9fafb;';

                if (file.type.startsWith('image/')) {
                    var img = document.createElement('img');
                    img.style.cssText = 'width:100%;height:100px;object-fit:cover;display:block;';
                    var reader = new FileReader();
                    reader.onload = function(e) { img.src = e.target.result; };
                    reader.readAsDataURL(file);
                    item.appendChild(img);
                } else {
                    var vid = document.createElement('div');
                    vid.style.cssText = 'width:100%;height:100px;display:flex;align-items:center;justify-content:center;background:#1e293b;color:#fff;font-size:24px;';
                    vid.innerHTML = '<i class="fa fa-video"></i>';
                    item.appendChild(vid);
                }

                // File name
                var name = document.createElement('div');
                name.style.cssText = 'padding:6px 8px;font-size:11px;color:#6b7280;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;';
                name.textContent = file.name;
                item.appendChild(name);

                // Remove button
                var removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.style.cssText = 'position:absolute;top:4px;right:4px;width:24px;height:24px;border-radius:50%;background:rgba(239,68,68,0.9);color:#fff;border:none;cursor:pointer;font-size:11px;display:flex;align-items:center;justify-content:center;';
                removeBtn.innerHTML = '<i class="fa fa-xmark"></i>';
                removeBtn.dataset.index = idx;
                removeBtn.addEventListener('click', function() {
                    selectedFiles.splice(parseInt(this.dataset.index), 1);
                    updatePreview();
                });
                item.appendChild(removeBtn);

                thumbnails.appendChild(item);
            });

            // Rebuild the file input with a DataTransfer
            syncFileInput();
        }

        function syncFileInput() {
            var dt = new DataTransfer();
            selectedFiles.forEach(function(f) { dt.items.add(f); });
            fileInput.files = dt.files;
        }

        clearBtn.addEventListener('click', function() {
            selectedFiles = [];
            updatePreview();
        });

        // Form submit with progress
        form.addEventListener('submit', function(e) {
            if (selectedFiles.length === 0) {
                e.preventDefault();
                alert('Zəhmət olmasa ən azı 1 fayl seçin');
                return;
            }

            e.preventDefault();
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Yüklənir...';
            progressDiv.style.display = 'block';

            var formData = new FormData(form);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);

            var csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken.content);

            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    var percent = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percent + '%';
                    progressText.textContent = percent + '% yükləndi (' + selectedFiles.length + ' fayl)';
                }
            });

            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 400) {
                    progressBar.style.width = '100%';
                    progressText.textContent = 'Uğurla yükləndi!';
                    setTimeout(function() { location.reload(); }, 1000);
                } else {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fa fa-upload"></i> Hamısını yüklə';
                    progressText.textContent = 'Xəta baş verdi. Yenidən cəhd edin.';
                    progressText.style.color = '#ef4444';
                }
            };

            xhr.onerror = function() {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa fa-upload"></i> Hamısını yüklə';
                progressText.textContent = 'Şəbəkə xətası. Yenidən cəhd edin.';
                progressText.style.color = '#ef4444';
            };

            xhr.send(formData);
        });
    })();
    </script>

    {{-- Sortable + Bulk Delete --}}
    <script>
    (function() {
        var gallery = document.getElementById('sortableMediaGallery');
        if (!gallery) return;

        var csrfToken = document.querySelector('meta[name="csrf-token"]');
        var serviceId = {{ $service->id }};
        var reorderUrl = '{{ route("admin.tourbooking.services.media.reorder", $service->id) }}';
        var bulkDeleteUrl = '{{ route("admin.tourbooking.services.media.bulk-delete", $service->id) }}';

        // ═══ SORTABLE (Drag & Drop Reorder) ═══
        var sortable = new Sortable(gallery, {
            animation: 250,
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            chosenClass: 'sortable-chosen',
            handle: '.media-item',
            filter: 'input, button, a, .btn',
            preventOnFilter: false,
            onEnd: function() {
                var items = gallery.querySelectorAll('.media-item');
                var order = [];
                items.forEach(function(item, idx) {
                    order.push(parseInt(item.dataset.id));
                    var badge = item.querySelector('.media-order-badge');
                    if (badge) badge.textContent = idx + 1;
                });

                fetch(reorderUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken ? csrfToken.content : '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ order: order })
                }).then(function(r) { return r.json(); }).then(function(data) {
                    if (data.success) {
                        toastr.success('Sıra uğurla yeniləndi');
                    }
                }).catch(function() {
                    toastr.error('Sıranı yeniləmək mümkün olmadı');
                });
            }
        });

        // ═══ CHECKBOX SELECTION + BULK DELETE ═══
        var selectAllCb = document.getElementById('selectAllMedia');
        var bulkBar = document.getElementById('bulkActionsBar');
        var selectedCountEl = document.getElementById('selectedCount');
        var bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        var deselectAllBtn = document.getElementById('deselectAllBtn');

        function getCheckboxes() {
            return gallery.querySelectorAll('.media-checkbox');
        }

        function getSelected() {
            var ids = [];
            getCheckboxes().forEach(function(cb) {
                if (cb.checked) ids.push(parseInt(cb.value));
            });
            return ids;
        }

        function updateBulkBar() {
            var selected = getSelected();
            if (selected.length > 0) {
                bulkBar.classList.add('active');
                selectedCountEl.textContent = selected.length + ' seçildi';
            } else {
                bulkBar.classList.remove('active');
            }
            if (selectAllCb) {
                var all = getCheckboxes();
                selectAllCb.checked = all.length > 0 && selected.length === all.length;
            }
        }

        gallery.addEventListener('change', function(e) {
            if (e.target.classList.contains('media-checkbox')) {
                var item = e.target.closest('.media-item');
                if (e.target.checked) {
                    item.classList.add('selected');
                } else {
                    item.classList.remove('selected');
                }
                updateBulkBar();
            }
        });

        if (selectAllCb) {
            selectAllCb.addEventListener('change', function() {
                var checked = this.checked;
                getCheckboxes().forEach(function(cb) {
                    cb.checked = checked;
                    var item = cb.closest('.media-item');
                    if (checked) item.classList.add('selected');
                    else item.classList.remove('selected');
                });
                updateBulkBar();
            });
        }

        if (deselectAllBtn) {
            deselectAllBtn.addEventListener('click', function() {
                getCheckboxes().forEach(function(cb) {
                    cb.checked = false;
                    cb.closest('.media-item').classList.remove('selected');
                });
                if (selectAllCb) selectAllCb.checked = false;
                updateBulkBar();
            });
        }

        if (bulkDeleteBtn) {
            bulkDeleteBtn.addEventListener('click', function() {
                var ids = getSelected();
                if (ids.length === 0) return;
                if (!confirm(ids.length + ' şəkil silinəcək. Əminsiniz?')) return;

                bulkDeleteBtn.disabled = true;
                bulkDeleteBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Silinir...';

                fetch(bulkDeleteUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken ? csrfToken.content : '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ ids: ids })
                }).then(function(r) { return r.json(); }).then(function(data) {
                    if (data.success) {
                        toastr.success(data.deleted + ' şəkil silindi');
                        setTimeout(function() { location.reload(); }, 800);
                    }
                }).catch(function() {
                    toastr.error('Silmək mümkün olmadı');
                    bulkDeleteBtn.disabled = false;
                    bulkDeleteBtn.innerHTML = '<i class="fa fa-trash"></i> Seçilənləri sil';
                });
            });
        }
    })();
    </script>
@endpush
