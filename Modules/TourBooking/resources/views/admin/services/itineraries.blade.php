@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Itineraries') }} - {{ $service->translation->title ?? $service->title }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Itineraries') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Tour Booking') }} >> {{ __('translate.Services') }} >>
        {{ __('translate.Itineraries') }}</p>
@endsection

@push('style_section')
    <link rel="stylesheet" href="{{ asset('global/select2/select2.min.css') }}">
    <style>
        .itinerary-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .itinerary-card .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
            padding: 15px;
        }

        .itinerary-card .card-body {
            padding: 20px;
        }

        .itinerary-image {
            max-width: 100%;
            height: auto;
            border-radius: 6px;
        }

        .itinerary-day-badge {
            font-size: 14px;
            padding: 5px 10px;
            background: #4e73df;
            color: white;
            border-radius: 20px;
            margin-right: 10px;
        }

        /* Inline Edit Form */
        .itinerary-edit-form {
            display: none;
            background: #fafbfc;
            border-top: 2px solid #4e73df;
            padding: 25px 20px;
        }

        .itinerary-edit-form.active {
            display: block;
        }

        .itinerary-view-content {
            display: block;
        }

        .itinerary-view-content.hidden {
            display: none;
        }

        .btn-group-actions .crancy-btn {
            padding: 6px 14px;
            font-size: 13px;
            margin-left: 5px;
        }

        .delete_danger_btn {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
            color: #fff !important;
        }

        .crancy-btn__secondary {
            background-color: #6c757d !important;
            border-color: #6c757d !important;
            color: #fff !important;
        }

        .current-image-box {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 8px;
            background: #fff;
            display: inline-block;
        }

        .current-image-box img {
            max-width: 150px;
            max-height: 100px;
            border-radius: 4px;
        }

        .edit-section-title {
            color: #4e73df;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .day-label-hint {
            font-size: 12px;
            color: #888;
            margin-top: 4px;
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
                                            <h4 class="crancy-product-card__title">{{ __('translate.Itineraries for') }}:
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

                                        {{-- ==================== ADD NEW ITINERARY ==================== --}}
                                        <div class="row mg-top-30">
                                            <div class="col-12">
                                                <div class="accordion" id="itineraryAccordion">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingOne">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                                aria-expanded="false" aria-controls="collapseOne">
                                                                <i class="fa fa-plus me-2"></i> {{ __('translate.Add New Itinerary') }}
                                                            </button>
                                                        </h2>
                                                        <div id="collapseOne" class="accordion-collapse collapse"
                                                            aria-labelledby="headingOne"
                                                            data-bs-parent="#itineraryAccordion">
                                                            <div class="accordion-body">
                                                                <form
                                                                    action="{{ route('admin.tourbooking.services.itineraries.store', $service->id) }}"
                                                                    method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="row">
                                                                        <div class="col-lg-4 col-md-4 col-12">
                                                                            <div class="crancy__item-form--group mg-top-form-20">
                                                                                <label class="crancy__item-label">{{ __('translate.Title') }} *</label>
                                                                                <input class="crancy__item-input"
                                                                                    type="text" name="title"
                                                                                    value="{{ old('title') }}" required>
                                                                                @error('title')
                                                                                    <span class="text-danger">{{ $message }}</span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-2 col-md-2 col-12">
                                                                            <div class="crancy__item-form--group mg-top-form-20">
                                                                                <label class="crancy__item-label">{{ __('translate.Day Number') }} *</label>
                                                                                <input class="crancy__item-input"
                                                                                    type="number" min="1"
                                                                                    name="day_number"
                                                                                    value="{{ old('day_number', $service->itineraries->count() + 1) }}"
                                                                                    required>
                                                                                @error('day_number')
                                                                                    <span class="text-danger">{{ $message }}</span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>

                                                                        {{-- DAY LABEL - custom text for badge --}}
                                                                        <div class="col-lg-3 col-md-3 col-12">
                                                                            <div class="crancy__item-form--group mg-top-form-20">
                                                                                <label class="crancy__item-label">{{ __('translate.Day Label') }}</label>
                                                                                <input class="crancy__item-input"
                                                                                    type="text" name="day_label"
                                                                                    value="{{ old('day_label') }}"
                                                                                    placeholder="{{ __('translate.e.g. Test, Arrival, Day 1') }}">
                                                                                <div class="day-label-hint">{{ __('translate.Leave empty to show') }} "{{ __('translate.Day') }} X"</div>
                                                                                @error('day_label')
                                                                                    <span class="text-danger">{{ $message }}</span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-3 col-md-3 col-12">
                                                                            <div class="crancy__item-form--group mg-top-form-20">
                                                                                <label class="crancy__item-label">{{ __('translate.Duration') }}</label>
                                                                                <input class="crancy__item-input"
                                                                                    type="text" name="duration"
                                                                                    value="{{ old('duration') }}"
                                                                                    placeholder="e.g. 2 hours, Full day">
                                                                                @error('duration')
                                                                                    <span class="text-danger">{{ $message }}</span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-12">
                                                                            <div class="crancy__item-form--group mg-top-form-20">
                                                                                <label class="crancy__item-label">{{ __('translate.Description') }} *</label>
                                                                                <textarea class="crancy__item-input summernote-add" name="description" rows="5">{{ old('description') }}</textarea>
                                                                                @error('description')
                                                                                    <span class="text-danger">{{ $message }}</span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-6 col-md-6 col-12">
                                                                            <div class="crancy__item-form--group mg-top-form-20">
                                                                                <label class="crancy__item-label">{{ __('translate.Image') }}</label>
                                                                                <input class="crancy__item-input"
                                                                                    type="file" name="image"
                                                                                    accept="image/*">
                                                                                @error('image')
                                                                                    <span class="text-danger">{{ $message }}</span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-6 col-md-6 col-12">
                                                                            <div class="crancy__item-form--group mg-top-form-20">
                                                                                <label class="crancy__item-label">{{ __('translate.Meal Included') }}</label>
                                                                                <input class="crancy__item-input"
                                                                                    type="text" name="meal_included"
                                                                                    value="{{ old('meal_included') }}"
                                                                                    placeholder="e.g. Breakfast, Lunch, Dinner">
                                                                                @error('meal_included')
                                                                                    <span class="text-danger">{{ $message }}</span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-12">
                                                                            <div class="crancy__item-form--group mg-top-form-20">
                                                                                <label class="crancy__item-label">{{ __('translate.Location') }}</label>
                                                                                <input class="crancy__item-input"
                                                                                    type="text" name="location"
                                                                                    value="{{ old('location') }}"
                                                                                    placeholder="e.g. Eiffel Tower, Paris">
                                                                                @error('location')
                                                                                    <span class="text-danger">{{ $message }}</span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-12 mg-top-30">
                                                                            <button type="submit"
                                                                                class="crancy-btn">{{ __('translate.Add Itinerary') }}</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- ==================== EXISTING ITINERARIES ==================== --}}
                                        <div class="row mg-top-30">
                                            <div class="col-12">
                                                <h4 class="crancy-product-card__title">
                                                    {{ __('translate.Existing Itineraries') }}
                                                    @if ($service->itineraries->count() > 0)
                                                        <span class="badge bg-primary ms-2">{{ $service->itineraries->count() }}</span>
                                                    @endif
                                                </h4>

                                                @if ($service->itineraries->count() > 0)
                                                    @foreach ($service->itineraries->sortBy('day_number') as $itinerary)
                                                        <div class="itinerary-card mg-top-20" id="itinerary-card-{{ $itinerary->id }}">

                                                            {{-- ===== CARD HEADER - shows day_label or "Day X" ===== --}}
                                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <span class="itinerary-day-badge">{{ $itinerary->day_display }}</span>
                                                                    <strong>{{ $itinerary->title }}</strong>
                                                                    @if ($itinerary->duration)
                                                                        <span class="badge bg-info ms-2">{{ $itinerary->duration }}</span>
                                                                    @endif
                                                                </div>
                                                                <div class="btn-group-actions">
                                                                    <button type="button" class="crancy-btn btn-toggle-edit"
                                                                        data-id="{{ $itinerary->id }}">
                                                                        <i class="fa fa-edit"></i>
                                                                        {{ __('translate.Edit') }}
                                                                    </button>
                                                                    <button type="button"
                                                                        class="crancy-btn delete_danger_btn"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#deleteModal{{ $itinerary->id }}">
                                                                        <i class="fa fa-trash"></i>
                                                                        {{ __('translate.Delete') }}
                                                                    </button>
                                                                </div>
                                                            </div>

                                                            {{-- ===== VIEW MODE ===== --}}
                                                            <div class="card-body itinerary-view-content" id="view-{{ $itinerary->id }}">
                                                                <div class="row">
                                                                    @if ($itinerary->image)
                                                                        <div class="col-md-3">
                                                                            <img src="{{ asset($itinerary->image) }}"
                                                                                alt="{{ $itinerary->title }}"
                                                                                class="itinerary-image">
                                                                        </div>
                                                                        <div class="col-md-9">
                                                                    @else
                                                                        <div class="col-12">
                                                                    @endif
                                                                        <div>
                                                                            {!! $itinerary->description !!}
                                                                        </div>

                                                                        @if ($itinerary->location)
                                                                            <div class="mt-3">
                                                                                <strong><i class="fa fa-map-marker"></i>
                                                                                    {{ __('translate.Location') }}:</strong>
                                                                                {{ $itinerary->location }}
                                                                            </div>
                                                                        @endif

                                                                        @if ($itinerary->meal_included)
                                                                            <div class="mt-2">
                                                                                <strong><i class="fa fa-utensils"></i>
                                                                                    {{ __('translate.Meal Included') }}:</strong>
                                                                                <span class="badge bg-success">{{ $itinerary->meal_included }}</span>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            {{-- ===== EDIT MODE (inline) ===== --}}
                                                            <div class="itinerary-edit-form" id="edit-form-{{ $itinerary->id }}">
                                                                <form action="{{ route('admin.tourbooking.services.itineraries.update', $itinerary->id) }}"
                                                                    method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('PUT')

                                                                    <div class="row">
                                                                        <div class="col-12 mb-3">
                                                                            <h5 class="edit-section-title">
                                                                                <i class="fa fa-edit me-1"></i>
                                                                                {{ __('translate.Edit') }}: {{ $itinerary->day_display }} - {{ $itinerary->title }}
                                                                            </h5>
                                                                            <hr>
                                                                        </div>

                                                                        {{-- Title --}}
                                                                        <div class="col-lg-4 col-md-4 col-12">
                                                                            <div class="crancy__item-form--group">
                                                                                <label class="crancy__item-label">{{ __('translate.Title') }} *</label>
                                                                                <input class="crancy__item-input"
                                                                                    type="text" name="title"
                                                                                    value="{{ $itinerary->title }}" required>
                                                                            </div>
                                                                        </div>

                                                                        {{-- Day Number --}}
                                                                        <div class="col-lg-2 col-md-2 col-12">
                                                                            <div class="crancy__item-form--group">
                                                                                <label class="crancy__item-label">{{ __('translate.Day Number') }} *</label>
                                                                                <input class="crancy__item-input"
                                                                                    type="number" min="1"
                                                                                    name="day_number"
                                                                                    value="{{ $itinerary->day_number }}"
                                                                                    required>
                                                                            </div>
                                                                        </div>

                                                                        {{-- Day Label --}}
                                                                        <div class="col-lg-3 col-md-3 col-12">
                                                                            <div class="crancy__item-form--group">
                                                                                <label class="crancy__item-label">{{ __('translate.Day Label') }}</label>
                                                                                <input class="crancy__item-input"
                                                                                    type="text" name="day_label"
                                                                                    value="{{ $itinerary->day_label }}"
                                                                                    placeholder="{{ __('translate.e.g. Test, Arrival, Day 1') }}">
                                                                                <div class="day-label-hint">{{ __('translate.Leave empty to show') }} "{{ __('translate.Day') }} {{ $itinerary->day_number }}"</div>
                                                                            </div>
                                                                        </div>

                                                                        {{-- Duration --}}
                                                                        <div class="col-lg-3 col-md-3 col-12">
                                                                            <div class="crancy__item-form--group">
                                                                                <label class="crancy__item-label">{{ __('translate.Duration') }}</label>
                                                                                <input class="crancy__item-input"
                                                                                    type="text" name="duration"
                                                                                    value="{{ $itinerary->duration }}">
                                                                            </div>
                                                                        </div>

                                                                        {{-- Description --}}
                                                                        <div class="col-12">
                                                                            <div class="crancy__item-form--group mg-top-form-20">
                                                                                <label class="crancy__item-label">{{ __('translate.Description') }} *</label>
                                                                                <textarea class="crancy__item-input edit-description"
                                                                                    id="edit-desc-{{ $itinerary->id }}"
                                                                                    name="description" rows="8">{{ $itinerary->description }}</textarea>
                                                                            </div>
                                                                        </div>

                                                                        {{-- Image --}}
                                                                        <div class="col-lg-6 col-md-6 col-12">
                                                                            <div class="crancy__item-form--group mg-top-form-20">
                                                                                <label class="crancy__item-label">{{ __('translate.Image') }}</label>
                                                                                @if ($itinerary->image)
                                                                                    <div class="current-image-box mb-2">
                                                                                        <img src="{{ asset($itinerary->image) }}"
                                                                                            alt="{{ $itinerary->title }}">
                                                                                        <div class="mt-1">
                                                                                            <small class="text-muted">{{ __('translate.Current image') }}</small>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                                <input class="crancy__item-input"
                                                                                    type="file" name="image"
                                                                                    accept="image/*">
                                                                                <small class="text-muted">{{ __('translate.Leave empty to keep current image') }}</small>
                                                                            </div>
                                                                        </div>

                                                                        {{-- Meal Included --}}
                                                                        <div class="col-lg-6 col-md-6 col-12">
                                                                            <div class="crancy__item-form--group mg-top-form-20">
                                                                                <label class="crancy__item-label">{{ __('translate.Meal Included') }}</label>
                                                                                <input class="crancy__item-input"
                                                                                    type="text" name="meal_included"
                                                                                    value="{{ $itinerary->meal_included }}">
                                                                            </div>
                                                                        </div>

                                                                        {{-- Location --}}
                                                                        <div class="col-12">
                                                                            <div class="crancy__item-form--group mg-top-form-20">
                                                                                <label class="crancy__item-label">{{ __('translate.Location') }}</label>
                                                                                <input class="crancy__item-input"
                                                                                    type="text" name="location"
                                                                                    value="{{ $itinerary->location }}">
                                                                            </div>
                                                                        </div>

                                                                        {{-- Buttons --}}
                                                                        <div class="col-12 mg-top-30">
                                                                            <button type="submit" class="crancy-btn">
                                                                                <i class="fa fa-save me-1"></i>
                                                                                {{ __('translate.Update') }}
                                                                            </button>
                                                                            <button type="button" class="crancy-btn crancy-btn__secondary ms-2 btn-cancel-edit"
                                                                                data-id="{{ $itinerary->id }}">
                                                                                <i class="fa fa-times me-1"></i>
                                                                                {{ __('translate.Cancel') }}
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>

                                                            {{-- ===== DELETE MODAL ===== --}}
                                                            <div class="modal fade" id="deleteModal{{ $itinerary->id }}" tabindex="-1"
                                                                aria-labelledby="deleteModalLabel{{ $itinerary->id }}"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="deleteModalLabel{{ $itinerary->id }}">
                                                                                {{ __('translate.Confirm Delete') }}</h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p>{{ __('translate.Are you sure you want to delete this itinerary?') }}</p>
                                                                            <div class="alert alert-warning">
                                                                                <strong>{{ $itinerary->day_display }}:</strong> {{ $itinerary->title }}
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="crancy-btn crancy-btn__secondary"
                                                                                data-bs-dismiss="modal">{{ __('translate.Cancel') }}</button>
                                                                            <form
                                                                                action="{{ route('admin.tourbooking.services.itineraries.destroy', $itinerary->id) }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="crancy-btn delete_danger_btn">
                                                                                    <i class="fa fa-trash me-1"></i>
                                                                                    {{ __('translate.Delete') }}
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="alert alert-info mg-top-20">
                                                        <i class="fa fa-info-circle me-2"></i>
                                                        {{ __('translate.No itineraries found. Add your first itinerary using the form above.') }}
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
    </section>
@endsection

@push('js_section')
    <script src="{{ asset('global/select2/select2.min.js') }}"></script>
    <script src="{{ asset('global/tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <script>
        (function($) {
            "use strict"
            $(document).ready(function() {

                var tinymceConfig = {
                    height: 250,
                    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                };

                // TinyMCE for ADD form
                tinymce.init($.extend({}, tinymceConfig, {
                    selector: '.summernote-add',
                    height: 200,
                }));

                // EDIT button
                $(document).on('click', '.btn-toggle-edit', function() {
                    var id = $(this).data('id');
                    var $view = $('#view-' + id);
                    var $editForm = $('#edit-form-' + id);
                    var editorId = 'edit-desc-' + id;

                    $view.addClass('hidden');
                    $editForm.addClass('active');

                    if (!tinymce.get(editorId)) {
                        tinymce.init($.extend({}, tinymceConfig, {
                            selector: '#' + editorId,
                        }));
                    }

                    $('html, body').animate({
                        scrollTop: $('#itinerary-card-' + id).offset().top - 20
                    }, 300);
                });

                // CANCEL button
                $(document).on('click', '.btn-cancel-edit', function() {
                    var id = $(this).data('id');
                    var $view = $('#view-' + id);
                    var $editForm = $('#edit-form-' + id);
                    var editorId = 'edit-desc-' + id;

                    if (tinymce.get(editorId)) {
                        tinymce.get(editorId).remove();
                    }

                    $view.removeClass('hidden');
                    $editForm.removeClass('active');
                });

            });
        })(jQuery);
    </script>
@endpush