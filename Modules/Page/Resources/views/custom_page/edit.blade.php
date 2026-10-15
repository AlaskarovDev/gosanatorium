@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Edit Custom Page') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Edit Custom Page') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Manage Pages') }} >> {{ __('translate.Edit Custom Page') }}</p>
@endsection

@section('body-content')

    <!-- crancy Dashboard -->
    <section class="crancy-adashboard crancy-show language_box">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <!-- Dashboard Inner -->
                        <div class="crancy-dsinner">
                            <div class="row">
                                <div class="col-12 mg-top-30">
                                    <!-- Product Card -->
                                    <div class="crancy-product-card translation_main_box">

                                        <div class="crancy-customer-filter">
                                            <div class="crancy-customer-filter__single crancy-customer-filter__single--csearch">
                                                <div class="crancy-header__form crancy-header__form--customer">
                                                    <h4 class="crancy-product-card__title">{{ __('translate.Switch to language translation') }}</h4>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="translation_box">
                                            <ul >
                                                @foreach ($language_list as $language)
                                                <li><a href="{{ route('admin.custom-page.edit', ['custom_page' => $custom_page->id, 'lang_code' => $language->lang_code] ) }}">
                                                    @if (request()->get('lang_code') == $language->lang_code)
                                                        <i class="fas fa-eye"></i>
                                                    @else
                                                        <i class="fas fa-edit"></i>
                                                    @endif

                                                    {{ $language->lang_name }}</a></li>
                                                @endforeach
                                            </ul>

                                            <div class="alert alert-secondary" role="alert">

                                                @php
                                                    $edited_language = $language_list->where('lang_code', request()->get('lang_code'))->first()
                                                        ?? $language_list->where('is_default', 'yes')->first()
                                                        ?? $language_list->first();
                                                @endphp

                                            <p>{{ __('translate.Your editing mode') }} : <b>{{ $edited_language?->lang_name }}</b></p>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- End Product Card -->
                                </div>
                            </div>
                        </div>
                        <!-- End Dashboard Inner -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End crancy Dashboard -->

    <!-- crancy Dashboard -->
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <!-- Dashboard Inner -->
                        <div class="crancy-dsinner">
                            <form action="{{ route('admin.custom-page.update', $custom_page->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                @method('PUT')

                                <input type="hidden" name="translate_id" value="{{ $translate->id }}">
                                <input type="hidden" name="lang_code" value="{{ request()->get('lang_code') }}">

                                <div class="row">
                                    <div class="col-12 mg-top-30">
                                        <!-- Product Card -->
                                        <div class="crancy-product-card">
                                            <div class="create_new_btn_inline_box">
                                                <h4 class="crancy-product-card__title">{{ __('translate.Edit Custom Page') }}</h4>

                                                <a href="{{ route('admin.custom-page.index') }}" class="crancy-btn "><i class="fa fa-list"></i> {{ __('translate.Custom Page List') }}</a>
                                            </div>


                                            <div class="row mg-top-30">

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Page Name') }} * </label>
                                                        <input class="crancy__item-input" type="text" name="page_name" id="page_name" value="{{ $translate->page_name }}">
                                                    </div>
                                                </div>

                                                @if (admin_lang() == request()->get('lang_code'))
                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Slug') }} * </label>
                                                        <input class="crancy__item-input" type="text" name="slug" id="slug" value="{{ $custom_page->slug }}">
                                                    </div>
                                                </div>
                                                @endif

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Description') }} * </label>
                                                        <textarea class="crancy__item-input crancy__item-textarea summernote"  name="description" id="description">{!! clean($translate->description) !!}</textarea>
                                                    </div>
                                                </div>

                                                @if (admin_lang() == request()->get('lang_code'))
                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Visibility Status') }} </label>
                                                        <div class="crancy-ptabs__notify-switch  crancy-ptabs__notify-switch--two">
                                                            <label class="crancy__item-switch">
                                                            <input {{ $custom_page->status == 1 ? 'checked' : '' }} name="status" type="checkbox" >
                                                            <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                            </div>

                                            {{-- ============================================= --}}
                                            {{-- VIDEO BANNER SECTION --}}
                                            {{-- ============================================= --}}
                                            
                                            @if (admin_lang() == request()->get('lang_code'))
                                            <div class="row mg-top-30">
                                                <div class="col-12">
                                                    <h4 class="crancy-product-card__title" style="border-bottom: 1px solid #eee; padding-bottom: 15px;">
                                                        <i class="fas fa-video"></i> {{ __('translate.Video Banner Settings') }}
                                                    </h4>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Show Video Banner') }} </label>
                                                        <div class="crancy-ptabs__notify-switch crancy-ptabs__notify-switch--two">
                                                            <label class="crancy__item-switch">
                                                                <input {{ $custom_page->show_video_banner == 1 ? 'checked' : '' }} name="show_video_banner" type="checkbox">
                                                                <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Video URL') }} (YouTube/Vimeo)</label>
                                                        <input class="crancy__item-input" type="text" name="video_url" value="{{ $custom_page->video_url }}" placeholder="https://www.youtube.com/watch?v=xxxxx">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Doctor Name') }}</label>
                                                        <input class="crancy__item-input" type="text" name="doctor_name" value="{{ $custom_page->doctor_name }}" placeholder="Dr. John Smith">
                                                    </div>
                                                </div>

                                                <div class="col-md-6"></div>

                                                {{-- Banner Image --}}
                                                <div class="col-md-4">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Banner Image') }}</label>
                                                        <div class="crancy-product-card__upload crancy-product-card__upload--border">
                                                            <input type="file" class="btn-check" name="banner_image" id="banner_image" autocomplete="off" onchange="previewBannerImage(event)">
                                                            <label class="crancy-image-video-upload__label" for="banner_image">
                                                                @if($custom_page->banner_image)
                                                                    <img id="view_banner_img" src="{{ asset($custom_page->banner_image) }}" style="max-width: 100%; height: auto;">
                                                                @else
                                                                    <img id="view_banner_img" src="{{ asset('backend/img/placeholder.png') }}" style="max-width: 100%; height: auto;">
                                                                @endif
                                                                <h4 class="crancy-image-video-upload__title">{{ __('translate.Click here to') }} <span class="crancy-primary-color">{{ __('translate.Choose File') }}</span> {{ __('translate.and upload') }}</h4>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Doctor Image --}}
                                                <div class="col-md-4">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Doctor Image') }}</label>
                                                        <div class="crancy-product-card__upload crancy-product-card__upload--border">
                                                            <input type="file" class="btn-check" name="doctor_image" id="doctor_image" autocomplete="off" onchange="previewDoctorImage(event)">
                                                            <label class="crancy-image-video-upload__label" for="doctor_image">
                                                                @if($custom_page->doctor_image)
                                                                    <img id="view_doctor_img" src="{{ asset($custom_page->doctor_image) }}" style="max-width: 100%; height: auto;">
                                                                @else
                                                                    <img id="view_doctor_img" src="{{ asset('backend/img/placeholder.png') }}" style="max-width: 100%; height: auto;">
                                                                @endif
                                                                <h4 class="crancy-image-video-upload__title">{{ __('translate.Click here to') }} <span class="crancy-primary-color">{{ __('translate.Choose File') }}</span> {{ __('translate.and upload') }}</h4>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Video Thumbnail --}}
                                                <div class="col-md-4">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Video Thumbnail') }}</label>
                                                        <div class="crancy-product-card__upload crancy-product-card__upload--border">
                                                            <input type="file" class="btn-check" name="video_thumbnail" id="video_thumbnail" autocomplete="off" onchange="previewThumbnailImage(event)">
                                                            <label class="crancy-image-video-upload__label" for="video_thumbnail">
                                                                @if($custom_page->video_thumbnail)
                                                                    <img id="view_thumbnail_img" src="{{ asset($custom_page->video_thumbnail) }}" style="max-width: 100%; height: auto;">
                                                                @else
                                                                    <img id="view_thumbnail_img" src="{{ asset('backend/img/placeholder.png') }}" style="max-width: 100%; height: auto;">
                                                                @endif
                                                                <h4 class="crancy-image-video-upload__title">{{ __('translate.Click here to') }} <span class="crancy-primary-color">{{ __('translate.Choose File') }}</span> {{ __('translate.and upload') }}</h4>
                                                            </label>
                                                        </div>
                                                        <small class="text-muted">{{ __('translate.Image shown in video area on transfer page') }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            {{-- Banner Translatable Fields --}}
                                            <div class="row mg-top-20">
                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Banner Title') }}</label>
                                                        <input class="crancy__item-input" type="text" name="banner_title" value="{{ $translate->banner_title ?? '' }}" placeholder="{{ __('translate.Enter banner title') }}">
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Banner Description') }}</label>
                                                        <textarea class="crancy__item-input crancy__item-textarea" name="banner_description" rows="3" placeholder="{{ __('translate.Enter banner description') }}">{{ $translate->banner_description ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- ============================================= --}}
                                            {{-- FEATURES SECTION (4 Feature) --}}
                                            {{-- ============================================= --}}

                                            <div class="row mg-top-30">
                                                <div class="col-12">
                                                    <h4 class="crancy-product-card__title" style="border-bottom: 1px solid #eee; padding-bottom: 15px;">
                                                        <i class="fas fa-star"></i> {{ __('translate.Features Section') }}
                                                        <small class="text-muted" style="font-size: 12px; font-weight: normal;">({{ __('translate.Shown on transfer page layout') }})</small>
                                                    </h4>
                                                </div>
                                            </div>

                                            {{-- Feature 1 --}}
                                            <div class="row mg-top-20" style="background: #f9fafb; padding: 15px; border-radius: 8px; margin-left: 0; margin-right: 0;">
                                                <div class="col-12">
                                                    <h5 style="color: #2c5aa0; font-size: 15px; margin-bottom: 10px;">
                                                        <i class="{{ $custom_page->feature1_icon ?? 'fas fa-clock' }}"></i>
                                                        {{ __('translate.Feature') }} 1
                                                    </h5>
                                                </div>
                                                @if (admin_lang() == request()->get('lang_code'))
                                                <div class="col-md-4">
                                                    <div class="crancy__item-form--group">
                                                        <label class="crancy__item-label">{{ __('translate.Icon Class') }}</label>
                                                        <input class="crancy__item-input" type="text" name="feature1_icon" value="{{ $custom_page->feature1_icon ?? 'fas fa-clock' }}" placeholder="fas fa-clock">
                                                        <small class="text-muted"><a href="https://fontawesome.com/icons" target="_blank">FontAwesome Icons</a></small>
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="{{ admin_lang() == request()->get('lang_code') ? 'col-md-8' : 'col-12' }}">
                                                    <div class="crancy__item-form--group">
                                                        <label class="crancy__item-label">{{ __('translate.Title') }}</label>
                                                        <input class="crancy__item-input" type="text" name="feature1_title" value="{{ $translate->feature1_title ?? '' }}" placeholder="{{ __('translate.Punctuality') }}">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-10">
                                                        <label class="crancy__item-label">{{ __('translate.Description') }}</label>
                                                        <textarea class="crancy__item-input crancy__item-textarea" name="feature1_description" rows="2">{{ $translate->feature1_description ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Feature 2 --}}
                                            <div class="row mg-top-20" style="background: #f9fafb; padding: 15px; border-radius: 8px; margin-left: 0; margin-right: 0;">
                                                <div class="col-12">
                                                    <h5 style="color: #2c5aa0; font-size: 15px; margin-bottom: 10px;">
                                                        <i class="{{ $custom_page->feature2_icon ?? 'fas fa-user-tie' }}"></i>
                                                        {{ __('translate.Feature') }} 2
                                                    </h5>
                                                </div>
                                                @if (admin_lang() == request()->get('lang_code'))
                                                <div class="col-md-4">
                                                    <div class="crancy__item-form--group">
                                                        <label class="crancy__item-label">{{ __('translate.Icon Class') }}</label>
                                                        <input class="crancy__item-input" type="text" name="feature2_icon" value="{{ $custom_page->feature2_icon ?? 'fas fa-user-tie' }}" placeholder="fas fa-user-tie">
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="{{ admin_lang() == request()->get('lang_code') ? 'col-md-8' : 'col-12' }}">
                                                    <div class="crancy__item-form--group">
                                                        <label class="crancy__item-label">{{ __('translate.Title') }}</label>
                                                        <input class="crancy__item-input" type="text" name="feature2_title" value="{{ $translate->feature2_title ?? '' }}" placeholder="{{ __('translate.Professionalism') }}">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-10">
                                                        <label class="crancy__item-label">{{ __('translate.Description') }}</label>
                                                        <textarea class="crancy__item-input crancy__item-textarea" name="feature2_description" rows="2">{{ $translate->feature2_description ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Feature 3 --}}
                                            <div class="row mg-top-20" style="background: #f9fafb; padding: 15px; border-radius: 8px; margin-left: 0; margin-right: 0;">
                                                <div class="col-12">
                                                    <h5 style="color: #2c5aa0; font-size: 15px; margin-bottom: 10px;">
                                                        <i class="{{ $custom_page->feature3_icon ?? 'fas fa-car' }}"></i>
                                                        {{ __('translate.Feature') }} 3
                                                    </h5>
                                                </div>
                                                @if (admin_lang() == request()->get('lang_code'))
                                                <div class="col-md-4">
                                                    <div class="crancy__item-form--group">
                                                        <label class="crancy__item-label">{{ __('translate.Icon Class') }}</label>
                                                        <input class="crancy__item-input" type="text" name="feature3_icon" value="{{ $custom_page->feature3_icon ?? 'fas fa-car' }}" placeholder="fas fa-car">
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="{{ admin_lang() == request()->get('lang_code') ? 'col-md-8' : 'col-12' }}">
                                                    <div class="crancy__item-form--group">
                                                        <label class="crancy__item-label">{{ __('translate.Title') }}</label>
                                                        <input class="crancy__item-input" type="text" name="feature3_title" value="{{ $translate->feature3_title ?? '' }}" placeholder="{{ __('translate.Comfort') }}">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-10">
                                                        <label class="crancy__item-label">{{ __('translate.Description') }}</label>
                                                        <textarea class="crancy__item-input crancy__item-textarea" name="feature3_description" rows="2">{{ $translate->feature3_description ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Feature 4 --}}
                                            <div class="row mg-top-20" style="background: #f9fafb; padding: 15px; border-radius: 8px; margin-left: 0; margin-right: 0;">
                                                <div class="col-12">
                                                    <h5 style="color: #2c5aa0; font-size: 15px; margin-bottom: 10px;">
                                                        <i class="{{ $custom_page->feature4_icon ?? 'fas fa-thumbs-up' }}"></i>
                                                        {{ __('translate.Feature') }} 4
                                                    </h5>
                                                </div>
                                                @if (admin_lang() == request()->get('lang_code'))
                                                <div class="col-md-4">
                                                    <div class="crancy__item-form--group">
                                                        <label class="crancy__item-label">{{ __('translate.Icon Class') }}</label>
                                                        <input class="crancy__item-input" type="text" name="feature4_icon" value="{{ $custom_page->feature4_icon ?? 'fas fa-thumbs-up' }}" placeholder="fas fa-thumbs-up">
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="{{ admin_lang() == request()->get('lang_code') ? 'col-md-8' : 'col-12' }}">
                                                    <div class="crancy__item-form--group">
                                                        <label class="crancy__item-label">{{ __('translate.Title') }}</label>
                                                        <input class="crancy__item-input" type="text" name="feature4_title" value="{{ $translate->feature4_title ?? '' }}" placeholder="{{ __('translate.Responsibility') }}">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-10">
                                                        <label class="crancy__item-label">{{ __('translate.Description') }}</label>
                                                        <textarea class="crancy__item-input crancy__item-textarea" name="feature4_description" rows="2">{{ $translate->feature4_description ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mg-top-10">
                                                <div class="col-12">
                                                    <div class="alert alert-info" style="font-size: 13px;">
                                                        <i class="fas fa-info-circle"></i>
                                                        {{ __('translate.Common icons') }}: <code>fas fa-clock</code>, <code>fas fa-user-tie</code>, <code>fas fa-car</code>, <code>fas fa-thumbs-up</code>, <code>fas fa-shield-alt</code>, <code>fas fa-map-marker-alt</code>, <code>fas fa-headset</code>, <code>fas fa-star</code>
                                                    </div>
                                                </div>
                                            </div>
@include('page::custom_page.video_gallery_admin')


                                            {{-- ============================================= --}}
                                            {{-- CONSULTATION FORM SECTION --}}
                                            {{-- ============================================= --}}

                                            @if (admin_lang() == request()->get('lang_code'))
                                            <div class="row mg-top-30">
                                                <div class="col-12">
                                                    <h4 class="crancy-product-card__title" style="border-bottom: 1px solid #eee; padding-bottom: 15px;">
                                                        <i class="fas fa-wpforms"></i> {{ __('translate.Consultation Form Settings') }}
                                                    </h4>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Show Consultation Form') }} </label>
                                                        <div class="crancy-ptabs__notify-switch crancy-ptabs__notify-switch--two">
                                                            <label class="crancy__item-switch">
                                                                <input {{ $custom_page->show_consultation_form == 1 ? 'checked' : '' }} name="show_consultation_form" type="checkbox">
                                                                <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            {{-- Form Translatable Fields --}}
                                            <div class="row mg-top-20">
                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Form Title') }}</label>
                                                        <input class="crancy__item-input" type="text" name="form_title" value="{{ $translate->form_title ?? '' }}" placeholder="{{ __('translate.Ask a Question to Doctor') }}">
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Form Description') }}</label>
                                                        <textarea class="crancy__item-input crancy__item-textarea" name="form_description" rows="2" placeholder="{{ __('translate.Enter form description') }}">{{ $translate->form_description ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <button class="crancy-btn mg-top-25" type="submit">{{ __('translate.Update') }}</button>

                                        </div>
                                        <!-- End Product Card -->
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- End Dashboard Inner -->
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End crancy Dashboard -->
@endsection


@push('style_section')
    <style>
        .tox .tox-promotion,
        .tox-statusbar__branding{
            display: none !important;
        }
        .crancy-product-card__upload {
            min-height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .crancy-product-card__upload img {
            max-height: 200px;
            object-fit: contain;
        }
    </style>
@endpush

@push('js_section')

    <script src="{{ asset('global/tinymce/js/tinymce/tinymce.min.js') }}"></script>

    <script>
        (function($) {
            "use strict"
            $(document).ready(function () {

                $("#page_name").on("keyup",function(e){
                    let inputValue = $(this).val();
                    let slug = inputValue.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'-');
                    $("#slug").val(slug);
                })

                tinymce.init({
                    selector: '.summernote',
                    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                    tinycomments_mode: 'embedded',
                    tinycomments_author: 'Author name',
                    mergetags_list: [
                        { value: 'First.Name', title: 'First Name' },
                        { value: 'Email', title: 'Email' },
                    ]
                });

            });
        })(jQuery);

        // Preview Banner Image
        function previewBannerImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('view_banner_img');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        // Preview Doctor Image
        function previewDoctorImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('view_doctor_img');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        // Preview Thumbnail Image
        function previewThumbnailImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('view_thumbnail_img');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endpush