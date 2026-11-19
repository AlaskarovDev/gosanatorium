@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Edit Destination') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Edit Destination') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Tour Booking') }} >> {{ __('translate.Edit Destination') }}</p>
@endsection

@push('style_section')
    <link rel="stylesheet" href="{{ asset('global/select2/select2.min.css') }}">
    <style>
        /* Currency Input Field Styling */
        .crancy__item-form--currency {
            position: relative;
            display: flex;
            align-items: center;
        }

        .crancy__item-form--currency .crancy__item-input {
            width: 100%;
            padding-right: 40px;
            /* Add space for the currency icon */
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px 40px 10px 12px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .crancy__item-form--currency .crancy__item-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .crancy__currency-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            /* Prevents icon from interfering with input clicks */
            z-index: 2;
        }

        .crancy__currency-icon span {
            font-size: 14px;
            color: #666;
            font-weight: 500;
        }

        /* Optional: Style for better visual hierarchy */
        .crancy__item-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        .mg-top-form-20 {
            margin-top: 20px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .crancy__item-form--currency .crancy__item-input {
                padding-right: 35px;
            }

            .crancy__currency-icon {
                right: 10px;
            }

            .crancy__currency-icon span {
                font-size: 13px;
            }
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
                            <form action="{{ route('admin.tourbooking.destinations.update', $destination) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-12 mg-top-30">
                                        <div class="crancy-product-card">
                                            <div class="create_new_btn_inline_box">
                                                <h4 class="crancy-product-card__title">
                                                    {{ __('translate.Basic Information') }}</h4>
                                                <a href="{{ route('admin.tourbooking.destinations.index') }}"
                                                    class="crancy-btn"><i class="fa fa-list"></i>
                                                    {{ __('translate.Destination List') }}</a>
                                            </div>

                                            <div class="row mg-top-30">
                                                <div class="col-lg-6 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Name') }}
                                                            *</label>
                                                        <input class="crancy__item-input" type="text" name="name"
                                                            id="name" value="{{ old('name', $destination->name) }}"
                                                            required>
                                                        @error('name')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Slug') }}
                                                            *</label>
                                                        <input required class="crancy__item-input" type="text"
                                                            name="slug" id="slug"
                                                            value="{{ old('slug', $destination->slug) }}">
                                                        @error('slug')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label
                                                            class="crancy__item-label">{{ __('translate.Country') }}*</label>
                                                        <input required class="crancy__item-input" type="text"
                                                            name="country" id="country"
                                                            value="{{ old('country', $destination->country) }}">
                                                        @error('country')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label
                                                            class="crancy__item-label">{{ __('translate.Destination tags') }}</label>
                                                        <input
                                                            placeholder="{{ __('translate.Separator by comma. Ex: 01 Tour, 02 Hotel') }}"
                                                            class="crancy__item-input" type="text" name="tags"
                                                            id="tags" value="{{ old('tags', $destination->tags) }}">
                                                        @error('tags')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label
                                                            class="crancy__item-label">{{ __('translate.Description') }}</label>
                                                        <textarea class="crancy__item-input summernote" name="description" rows="6">{{ old('description', $destination->description) }}</textarea>
                                                        @error('description')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mg-top-30">
                                                <div class="col-sm-2">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label
                                                            class="crancy__item-label">{{ __('translate.Status') }}</label>
                                                        <div
                                                            class="crancy-ptabs__notify-switch crancy-ptabs__notify-switch--two">
                                                            <label class="crancy__item-switch">
                                                                <input name="status" type="checkbox"
                                                                    {{ old('status', $destination->status) ? 'checked' : '' }}
                                                                    value="1">
                                                                <span
                                                                    class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Featured') }}
                                                            *</label>
                                                        <div
                                                            class="crancy-ptabs__notify-switch crancy-ptabs__notify-switch--two">
                                                            <label class="crancy__item-switch">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="is_featured" name="is_featured" value="1"
                                                                    {{ old('is_featured', $destination->is_featured) ? 'checked' : '' }}>
                                                                <span
                                                                    class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-5">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label
                                                            class="crancy__item-label">{{ __('translate.SVG Image') }}</label>
                                                        <div
                                                            class="crancy-product-card__upload crancy-product-card__upload--border">
                                                            <input accept="image/*" type="file" class="btn-check"
                                                                name="svg" id="svg_img" autocomplete="off"
                                                                onchange="previewImageSVG(event)">
                                                            <label class="crancy-image-video-upload__label" for="svg_img">
                                                                <img id="view_img_svg"
                                                                    src="{{ isset($destination) && $destination->svg_image ? asset($destination->svg_image) : asset($general_setting->placeholder_image) }}">
                                                                <h4 class="crancy-image-video-upload__title">
                                                                    {{ __('translate.Click here to') }}
                                                                    <span
                                                                        class="crancy-primary-color">{{ __('translate.Choose File') }}</span>
                                                                    {{ __('translate.and upload') }}
                                                                </h4>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-5">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label
                                                            class="crancy__item-label">{{ __('translate.Featured Image') }}
                                                            * </label>
                                                        <div
                                                            class="crancy-product-card__upload crancy-product-card__upload--border">
                                                            <input accept="image/*" type="file" class="btn-check"
                                                                name="image" id="input-img1" autocomplete="off"
                                                                onchange="previewImage(event)">
                                                            <label class="crancy-image-video-upload__label"
                                                                for="input-img1">
                                                                <img id="view_img"
                                                                    src="{{ isset($destination) && $destination->image ? asset($destination->image) : asset($general_setting->placeholder_image) }}">
                                                                <h4 class="crancy-image-video-upload__title">
                                                                    {{ __('translate.Click here to') }}
                                                                    <span
                                                                        class="crancy-primary-color">{{ __('translate.Choose File') }}</span>
                                                                    {{ __('translate.and upload') }}
                                                                </h4>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mg-top-30">
                                        <div class="crancy-product-card">
                                            <h4 class="crancy-product-card__title">{{ __('translate.SEO Information') }}
                                            </h4>

                                            <div class="row mg-top-30">
                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label
                                                            class="crancy__item-label">{{ __('translate.SEO Title') }}</label>
                                                        <input class="crancy__item-input" type="text"
                                                            name="meta_title"
                                                            value="{{ old('meta_title', $destination->meta_title) }}">
                                                        @error('meta_title')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label
                                                            class="crancy__item-label">{{ __('translate.SEO Description') }}</label>
                                                        <textarea class="crancy__item-input" name="meta_description" rows="3">{{ old('meta_description', strip_tags(html_entity_decode((string) $destination->meta_description))) }}</textarea>
                                                        <small class="text-muted">{{ __('translate.Empty: generated automatically (city, number of sanatoriums, minimum price).') }}</small>
                                                        @error('meta_description')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if (\Illuminate\Support\Facades\Schema::hasColumn('destinations', 'h1'))
                                    @php
                                        $faqRows = old('faq_q')
                                            ? collect(old('faq_q'))->map(fn ($q, $i) => ['q' => $q, 'a' => old('faq_a')[$i] ?? ''])->all()
                                            : ((array) ($destination->faq ?? []));
                                        if (count($faqRows) === 0) { $faqRows = [['q' => '', 'a' => '']]; }
                                    @endphp
                                    <div class="col-12 mg-top-30">
                                        <div class="crancy-product-card">
                                            <h4 class="crancy-product-card__title">{{ __('translate.Destination page content') }}</h4>
                                            <p class="text-muted mb-0">{{ __('translate.Shown on the destination page (list of sanatoriums of this city). Empty fields use automatic texts.') }}</p>

                                            <div class="row mg-top-30">
                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">H1</label>
                                                        <input class="crancy__item-input" type="text" name="h1" maxlength="255"
                                                            placeholder="{{ __('translate.Example') }}: Санатории Нафталана"
                                                            value="{{ old('h1', $destination->h1) }}">
                                                        @error('h1')<span class="text-danger">{{ $message }}</span>@enderror
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Intro text (above the list)') }}</label>
                                                        <textarea class="crancy__item-input summernote" name="intro_text" rows="5">{{ old('intro_text', $destination->intro_text) }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.SEO text (below the list)') }}</label>
                                                        <textarea class="crancy__item-input summernote" name="seo_text" rows="8">{{ old('seo_text', $destination->seo_text) }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Hero image') }}</label>
                                                        @if ($destination->hero_image)
                                                            <div class="mb-2">
                                                                <img src="{{ asset($destination->hero_image) }}" alt="" style="max-height:120px;border-radius:8px">
                                                                <label class="ms-3"><input type="checkbox" name="remove_hero_image" value="1"> {{ __('translate.Delete') }}</label>
                                                            </div>
                                                        @endif
                                                        <input class="crancy__item-input" type="file" name="hero_image" accept="image/*">
                                                        @error('hero_image')<span class="text-danger">{{ $message }}</span>@enderror
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">FAQ</label>
                                                        <div id="gsFaqRows">
                                                            @foreach ($faqRows as $row)
                                                                <div class="gs-faq-row" style="border:1px solid #e5e7eb;border-radius:8px;padding:12px;margin-bottom:10px">
                                                                    <input class="crancy__item-input mb-2" type="text" name="faq_q[]" maxlength="500"
                                                                        placeholder="{{ __('translate.Question') }}" value="{{ $row['q'] ?? '' }}">
                                                                    <textarea class="crancy__item-input" name="faq_a[]" rows="3"
                                                                        placeholder="{{ __('translate.Answer') }}">{{ $row['a'] ?? '' }}</textarea>
                                                                    <button type="button" class="btn btn-sm btn-outline-danger mt-2 gs-faq-del">{{ __('translate.Delete') }}</button>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <button type="button" class="crancy-btn mt-2" id="gsFaqAdd"><i class="fa fa-plus"></i> {{ __('translate.Add question') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <div class="col-12 mg-top-30">
                                        <div class="alert alert-warning mb-0">{{ __('translate.Destination page fields are not enabled yet: the database update must be applied.') }}</div>
                                    </div>
                                    @endif

                                    <div class="col-12 mg-top-30">
                                        <button class="crancy-btn"
                                            type="submit">{{ __('translate.Update Destination') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
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
                $("#name").on("keyup", function(e) {
                    let inputValue = $(this).val();
                    let slug = inputValue.toLowerCase().replace(/[^\w ]+/g, '').replace(/ +/g, '-');
                    $("#slug").val(slug);
                });

                $('.select2').select2({
                    tags: true,
                    tokenSeparators: [',', ' ']
                });

                tinymce.init({
                    selector: '.summernote',
                    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                    tinycomments_mode: 'embedded',
                    tinycomments_author: 'Author name',
                    mergetags_list: [{
                            value: 'First.Name',
                            title: 'First Name'
                        },
                        {
                            value: 'Email',
                            title: 'Email'
                        },
                    ]
                });
            });
        })(jQuery);

        (function () {
            var wrap = document.getElementById('gsFaqRows');
            var add = document.getElementById('gsFaqAdd');
            if (!wrap || !add) return;
            add.addEventListener('click', function () {
                var first = wrap.querySelector('.gs-faq-row');
                var row = first.cloneNode(true);
                row.querySelectorAll('input, textarea').forEach(function (el) { el.value = ''; });
                wrap.appendChild(row);
            });
            wrap.addEventListener('click', function (e) {
                if (!e.target.classList.contains('gs-faq-del')) return;
                var rows = wrap.querySelectorAll('.gs-faq-row');
                var row = e.target.closest('.gs-faq-row');
                if (rows.length > 1) { row.remove(); }
                else { row.querySelectorAll('input, textarea').forEach(function (el) { el.value = ''; }); }
            });
        })();

        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('view_img');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        };

        function previewImageSVG(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('view_img_svg');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        };
    </script>
@endpush
