@extends('frontend.layouts.master')

@section('title')
    <title>{{ $custom_page->page_name }}</title>
@endsection

@section('content')

{{-- ================================================================ --}}
{{-- TRANSFER PAGE LAYOUT (slug == 'transfer') --}}
{{-- ================================================================ --}}
@if($custom_page->slug == 'transfer')

    {{-- Transfer Hero Section - Şəkildəki dizayn --}}
    <section class="transfer-hero-section">
        <div class="transfer-hero-wrapper">
            <div class="container">
                <div class="row align-items-stretch">

                    {{-- Sol tərəf: Başlıq + 4 Feature (2x2 grid) --}}
                    <div class="col-lg-7">
                        <div class="transfer-hero-left">

                            {{-- Səhifə Başlığı --}}
                            <h1 class="transfer-main-title">{{ $custom_page->page_name }}</h1>

                            {{-- Features Grid 2x2 --}}
                            <div class="transfer-features-grid">
                                <div class="row">

                                    {{-- Feature 1 --}}
                                    @if($custom_page->feature1_title)
                                    <div class="col-sm-6">
                                        <div class="transfer-feature-item">
                                            <div class="transfer-feature-icon">
                                                <i class="{{ $custom_page->feature1_icon ?? 'fas fa-clock' }}"></i>
                                            </div>
                                            <div class="transfer-feature-content">
                                                <h4 class="transfer-feature-title">{{ $custom_page->feature1_title }}</h4>
                                                <p class="transfer-feature-desc">{{ $custom_page->feature1_description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    {{-- Feature 2 --}}
                                    @if($custom_page->feature2_title)
                                    <div class="col-sm-6">
                                        <div class="transfer-feature-item">
                                            <div class="transfer-feature-icon">
                                                <i class="{{ $custom_page->feature2_icon ?? 'fas fa-user-tie' }}"></i>
                                            </div>
                                            <div class="transfer-feature-content">
                                                <h4 class="transfer-feature-title">{{ $custom_page->feature2_title }}</h4>
                                                <p class="transfer-feature-desc">{{ $custom_page->feature2_description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    {{-- Feature 3 --}}
                                    @if($custom_page->feature3_title)
                                    <div class="col-sm-6">
                                        <div class="transfer-feature-item">
                                            <div class="transfer-feature-icon">
                                                <i class="{{ $custom_page->feature3_icon ?? 'fas fa-car' }}"></i>
                                            </div>
                                            <div class="transfer-feature-content">
                                                <h4 class="transfer-feature-title">{{ $custom_page->feature3_title }}</h4>
                                                <p class="transfer-feature-desc">{{ $custom_page->feature3_description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    {{-- Feature 4 --}}
                                    @if($custom_page->feature4_title)
                                    <div class="col-sm-6">
                                        <div class="transfer-feature-item">
                                            <div class="transfer-feature-icon">
                                                <i class="{{ $custom_page->feature4_icon ?? 'fas fa-thumbs-up' }}"></i>
                                            </div>
                                            <div class="transfer-feature-content">
                                                <h4 class="transfer-feature-title">{{ $custom_page->feature4_title }}</h4>
                                                <p class="transfer-feature-desc">{{ $custom_page->feature4_description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Sağ tərəf: Şəkil + Video Play Button --}}
                    <div class="col-lg-5">
                        <div class="transfer-hero-right">
                            <div class="transfer-video-wrapper">
                                @if($custom_page->video_thumbnail)
                                    <img src="{{ asset($custom_page->video_thumbnail) }}"
                                         alt="{{ $custom_page->page_name }}"
                                         class="transfer-video-thumb">
                                @elseif($custom_page->banner_image)
                                    <img src="{{ asset($custom_page->banner_image) }}"
                                         alt="{{ $custom_page->page_name }}"
                                         class="transfer-video-thumb">
                                @else
                                    <div class="transfer-video-placeholder">
                                        <i class="fas fa-car"></i>
                                    </div>
                                @endif

                                @if($custom_page->video_url)
                                    <button class="transfer-play-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#transferVideoModal">
                                        <i class="fas fa-play"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{-- Video Modal --}}
    @if($custom_page->video_url)
    <div class="modal fade" id="transferVideoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="background: #000; border: none; border-radius: 12px; overflow: hidden;">
                <div class="modal-header" style="background: #1a2b4a; border: none; padding: 10px 20px;">
                    <h5 class="modal-title" style="color: #fff; font-size: 15px;">{{ $custom_page->page_name }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="transfer-video-container">
                        <iframe id="transferVideoIframe" src="" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Səhifə Məzmunu --}}
    @if($custom_page->description)
    <section class="transfer-content-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="transfer-content-body">
                        {!! clean($custom_page->description) !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Konsultasiya Formu --}}
    @if($custom_page->show_consultation_form)
    <section class="transfer-form-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="transfer-form-wrapper">

                        <h2 class="transfer-form-title">
                            {{ $custom_page->form_title ?? __('translate.Ask a Question') }}
                        </h2>

                        @if($custom_page->form_description)
                            <p class="transfer-form-desc">{{ $custom_page->form_description }}</p>
                        @endif

                        <form action="{{ route('consultation.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="custom_page_id" value="{{ $custom_page->id }}">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="transfer-form-label">{{ __('translate.First Name') }} *</label>
                                        <input type="text" name="first_name" class="form-control transfer-form-input"
                                               value="{{ old('first_name') }}" placeholder="{{ __('translate.Ivan') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="transfer-form-label">{{ __('translate.Last Name') }} *</label>
                                        <input type="text" name="last_name" class="form-control transfer-form-input"
                                               value="{{ old('last_name') }}" placeholder="{{ __('translate.Petrov') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="transfer-form-label">{{ __('translate.Email Address') }} *</label>
                                        <input type="email" name="email" class="form-control transfer-form-input"
                                               value="{{ old('email') }}" placeholder="your@email.com" required>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="transfer-form-label">{{ __('translate.Country & Phone') }} *</label>
                                        <div class="input-group">
                                            <select name="country" class="form-select transfer-form-select" id="transferCountrySelect">
                                                <option value="AZ" data-code="+994">🇦🇿 AZ</option>
                                                <option value="RU" data-code="+7">🇷🇺 RU</option>
                                                <option value="TR" data-code="+90">🇹🇷 TR</option>
                                                <option value="UA" data-code="+380">🇺🇦 UA</option>
                                                <option value="KZ" data-code="+7">🇰🇿 KZ</option>
                                                <option value="GE" data-code="+995">🇬🇪 GE</option>
                                            </select>
                                            <input type="text" name="phone" class="form-control transfer-form-input"
                                                   value="{{ old('phone') }}" placeholder="+994" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label class="transfer-form-label">{{ __('translate.Message') }} *</label>
                                        <textarea name="message" class="form-control transfer-form-input" rows="5"
                                                  placeholder="{{ __('translate.Your question to the doctor') }}" required>{{ old('message') }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12 mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" name="marketing_consent" class="form-check-input" id="transferMarketingConsent" value="1">
                                        <label class="form-check-label" for="transferMarketingConsent" style="font-size: 13px; color: #6c7a8d;">
                                            {{ __('translate.I agree to the use of my personal data for marketing purposes. You can unsubscribe at any time.') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn transfer-submit-btn">
                                        {{ __('translate.Send Question to Doctor') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

{{-- ================================================================ --}}
{{-- DEFAULT LAYOUT (bütün digər custom page-lər) --}}
{{-- ================================================================ --}}
@else

    {{-- Video Banner Section --}}
    @if($custom_page->show_video_banner)
    <section class="consultation-banner">
        <div class="banner-wrapper" style="background-image: url('{{ asset($custom_page->banner_image) }}');">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="banner-content">
                            <h2 class="banner-title">{{ $custom_page->banner_title }}</h2>
                            <p class="banner-text">{{ $custom_page->banner_description }}</p>
                        </div>
                    </div>
                    <div class="col-lg-3 text-center">
                        <button class="video-play-btn" data-bs-toggle="modal" data-bs-target="#videoModal">
                            <i class="fas fa-play"></i>
                            <span>{{ __('translate.Watch Video') }}</span>
                        </button>
                    </div>
                    <div class="col-lg-3">
                        @if($custom_page->doctor_image)
                        <div class="doctor-image">
                            <img src="{{ asset($custom_page->doctor_image) }}" alt="{{ $custom_page->doctor_name }}">
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Modal -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="video-container">
                        <iframe id="videoIframe" src="" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Page Content --}}
    <section class="page-content py-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    {!! clean($custom_page->description) !!}
                </div>
            </div>
        </div>
    </section>

    {{-- Consultation Form Section --}}
    @if($custom_page->show_consultation_form)
    <section class="consultation-form-section py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="form-wrapper bg-white p-4 p-md-5 rounded shadow-sm">
                        <h2 class="form-title mb-4">{{ $custom_page->form_title ?? __('translate.Ask a Question to Doctor') }}</h2>
                        
                        @if($custom_page->form_description)
                        <p class="form-description mb-4">{{ $custom_page->form_description }}</p>
                        @endif

                        <form action="{{ route('consultation.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="custom_page_id" value="{{ $custom_page->id }}">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="form-label">{{ __('translate.First Name') }} *</label>
                                        <input type="text" name="first_name" class="form-control" placeholder="{{ __('translate.Ivan') }}" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="form-label">{{ __('translate.Last Name') }} *</label>
                                        <input type="text" name="last_name" class="form-control" placeholder="{{ __('translate.Petrov') }}" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="form-label">{{ __('translate.Email Address') }} *</label>
                                        <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="form-label">{{ __('translate.Country & Phone') }} *</label>
                                        <div class="input-group">
                                            <select name="country" class="form-select" style="max-width: 150px;">
                                                <option value="AZ" data-code="+994">{{ __('translate.Azerbaijan') }}</option>
                                                <option value="RU" data-code="+7">{{ __('translate.Russia') }}</option>
                                                <option value="TR" data-code="+90">{{ __('translate.Turkey') }}</option>
                                                <option value="UA" data-code="+380">{{ __('translate.Ukraine') }}</option>
                                                <option value="KZ" data-code="+7">{{ __('translate.Kazakhstan') }}</option>
                                                <option value="GE" data-code="+995">{{ __('translate.Georgia') }}</option>
                                            </select>
                                            <input type="text" name="phone" class="form-control" placeholder="+994" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label class="form-label">{{ __('translate.Message') }} *</label>
                                        <textarea name="message" class="form-control" rows="5" placeholder="{{ __('translate.Your question to the doctor') }}" required></textarea>
                                    </div>
                                </div>
                                
                                <div class="col-12 mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" name="marketing_consent" class="form-check-input" id="marketingConsent" value="1">
                                        <label class="form-check-label" for="marketingConsent">
                                            {{ __('translate.I agree to the use of my personal data for marketing purposes. You can unsubscribe at any time.') }}
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success btn-lg w-100">
                                        {{ __('translate.Send Question to Doctor') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

@endif
{{-- END LAYOUT SWITCH --}}

@endsection


@push('css')
<style>
/* ===================================================================
   TRANSFER PAGE STYLES
   =================================================================== */

/* Hero Section */
.transfer-hero-section {
    background-color: #e8eaed;
    overflow: hidden;
}
.transfer-hero-wrapper {
    padding: 0;
}
.transfer-hero-left {
    padding: 40px 0 40px 0;
}

/* Başlıq */
.transfer-main-title {
    font-size: 34px;
    font-weight: 800;
    color: #1a2b4a;
    margin-bottom: 30px;
    position: relative;
    display: inline-block;
}
.transfer-main-title::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -6px;
    width: 100%;
    height: 3px;
    background: #2c5aa0;
    border-radius: 2px;
}

/* Features Grid */
.transfer-features-grid {
    margin-top: 10px;
}
.transfer-feature-item {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 18px 8px;
    border-radius: 6px;
    transition: background 0.3s;
}
.transfer-feature-item:hover {
    background: rgba(255,255,255,0.5);
}
.transfer-feature-icon {
    flex-shrink: 0;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: rgba(44,90,160,0.08);
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid rgba(44,90,160,0.12);
}
.transfer-feature-icon i {
    font-size: 19px;
    color: #2c5aa0;
}
.transfer-feature-title {
    font-size: 16px;
    font-weight: 700;
    color: #1a2b4a;
    margin-bottom: 5px;
}
.transfer-feature-desc {
    font-size: 13.5px;
    color: #5a6a7e;
    line-height: 1.55;
    margin-bottom: 0;
}

/* Sağ tərəf - Video/Şəkil */
.transfer-hero-right {
    height: 100%;
    display: flex;
    align-items: flex-end;
    justify-content: flex-end;
}
.transfer-video-wrapper {
    position: relative;
    width: 100%;
    height: 100%;
    min-height: 350px;
    overflow: hidden;
}
.transfer-video-thumb {
    width: 100%;
    height: 100%;
    min-height: 350px;
    object-fit: cover;
    object-position: center top;
    display: block;
}
.transfer-video-placeholder {
    width: 100%;
    height: 100%;
    min-height: 350px;
    background: linear-gradient(135deg, #2c5aa0, #1a3a6e);
    display: flex;
    align-items: center;
    justify-content: center;
}
.transfer-video-placeholder i {
    font-size: 70px;
    color: rgba(255,255,255,0.25);
}

/* Play Button */
.transfer-play-btn {
    position: absolute;
    top: 50%;
    right: 40px;
    transform: translateY(-50%);
    width: 65px;
    height: 65px;
    border-radius: 50%;
    background: rgba(0,0,0,0.5);
    border: 3px solid rgba(255,255,255,0.85);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    z-index: 2;
}
.transfer-play-btn:hover {
    background: rgba(44,90,160,0.85);
    border-color: #fff;
    transform: translateY(-50%) scale(1.07);
}
.transfer-play-btn i {
    font-size: 20px;
    color: #fff;
    margin-left: 3px;
}

/* Video Container (Modal) */
.transfer-video-container {
    position: relative;
    padding-bottom: 56.25%;
    height: 0;
    overflow: hidden;
}
.transfer-video-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

/* Content Section */
.transfer-content-section {
    padding: 50px 0;
    background: #fff;
}
.transfer-content-body {
    font-size: 15px;
    line-height: 1.75;
    color: #444;
}
.transfer-content-body img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
}

/* Form Section */
.transfer-form-section {
    padding: 50px 0;
    background: #f5f7fa;
}
.transfer-form-wrapper {
    background: #fff;
    border-radius: 10px;
    padding: 35px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.05);
    border: 1px solid #e5e9ee;
}
.transfer-form-title {
    font-size: 24px;
    font-weight: 400;
    color: #333;
    margin-bottom: 8px;
}
.transfer-form-desc {
    font-size: 14px;
    color: #6c7a8d;
    margin-bottom: 20px;
}
.transfer-form-label {
    font-size: 12px;
    font-weight: 600;
    color: #2c5aa0;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.transfer-form-input {
    border: 1px solid #dde2e8;
    border-radius: 5px;
    padding: 11px 14px;
    font-size: 14px;
    color: #333;
}
.transfer-form-input:focus {
    border-color: #2c5aa0;
    box-shadow: 0 0 0 3px rgba(44,90,160,0.08);
}
.transfer-form-select {
    max-width: 110px;
    border: 1px solid #dde2e8;
    padding: 11px 8px;
    font-size: 14px;
}
.transfer-form-select:focus {
    border-color: #2c5aa0;
    box-shadow: none;
}
.transfer-submit-btn {
    width: 100%;
    background: linear-gradient(135deg, #2c5aa0, #1a3a6e);
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 14px 30px;
    font-size: 17px;
    font-weight: 600;
    transition: all 0.3s;
}
.transfer-submit-btn:hover {
    background: linear-gradient(135deg, #1a3a6e, #0f2a52);
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(44,90,160,0.3);
}

/* Responsive */
@media (max-width: 991.98px) {
    .transfer-main-title { font-size: 28px; }
    .transfer-hero-right { margin-top: 20px; }
    .transfer-video-wrapper { min-height: 280px; border-radius: 10px; overflow: hidden; }
    .transfer-video-thumb { min-height: 280px; border-radius: 10px; }
    .transfer-video-placeholder { min-height: 280px; border-radius: 10px; }
    .transfer-play-btn {
        right: 50%;
        transform: translate(50%, -50%);
    }
    .transfer-play-btn:hover {
        transform: translate(50%, -50%) scale(1.07);
    }
}
@media (max-width: 575.98px) {
    .transfer-main-title { font-size: 24px; }
    .transfer-feature-icon { width: 40px; height: 40px; }
    .transfer-feature-icon i { font-size: 16px; }
    .transfer-feature-title { font-size: 14px; }
    .transfer-feature-desc { font-size: 12.5px; }
    .transfer-video-wrapper { min-height: 200px; }
    .transfer-video-thumb { min-height: 200px; }
    .transfer-video-placeholder { min-height: 200px; }
    .transfer-form-wrapper { padding: 22px 18px; }
}


/* ===================================================================
   DEFAULT PAGE STYLES (mövcud dizayn)
   =================================================================== */
.consultation-banner {
    position: relative;
}
.banner-wrapper {
    background-size: cover;
    background-position: center;
    padding: 60px 0;
    position: relative;
}
.banner-wrapper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
}
.banner-wrapper .container {
    position: relative;
    z-index: 1;
}
.banner-content {
    color: #fff;
}
.banner-title {
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 15px;
}
.banner-text {
    font-size: 16px;
    line-height: 1.6;
}
.video-play-btn {
    background: transparent;
    border: 3px solid #fff;
    border-radius: 50%;
    width: 80px;
    height: 80px;
    color: #fff;
    font-size: 24px;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.video-play-btn:hover {
    background: #fff;
    color: #333;
}
.video-play-btn span {
    position: absolute;
    bottom: -30px;
    white-space: nowrap;
    font-size: 14px;
}
.doctor-image img {
    max-width: 200px;
    border-radius: 10px;
}
.video-container {
    position: relative;
    padding-bottom: 56.25%;
    height: 0;
    overflow: hidden;
}
.video-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
.form-wrapper {
    border: 1px solid #e0e0e0;
}
.form-title {
    font-size: 24px;
    font-weight: 400;
    color: #333;
}
.consultation-form-section .form-control,
.consultation-form-section .form-select {
    border: 1px solid #e0e0e0;
    padding: 12px 15px;
    font-size: 14px;
}
.consultation-form-section .form-control:focus,
.consultation-form-section .form-select:focus {
    border-color: #4CAF50;
    box-shadow: none;
}
.consultation-form-section .form-label {
    color: #4CAF50;
    font-size: 12px;
    margin-bottom: 5px;
}
.consultation-form-section .btn-success {
    background: #4CAF50;
    border: none;
    padding: 15px 30px;
    font-size: 18px;
}
.consultation-form-section .btn-success:hover {
    background: #43A047;
}
</style>
@endpush


@push('js')
<script>
(function() {
    'use strict';

    var videoUrl = @json($custom_page->video_url ?? '');
    var slug = @json($custom_page->slug);

    // ===== TRANSFER PAGE VIDEO =====
    if (slug === 'transfer') {
        var transferModal = document.getElementById('transferVideoModal');
        var transferIframe = document.getElementById('transferVideoIframe');

        if (transferModal && transferIframe) {
            transferModal.addEventListener('show.bs.modal', function () {
                transferIframe.src = convertToEmbedUrl(videoUrl);
            });
            transferModal.addEventListener('hide.bs.modal', function () {
                transferIframe.src = '';
            });
        }

        // Phone country code
        var countrySelect = document.getElementById('transferCountrySelect');
        if (countrySelect) {
            countrySelect.addEventListener('change', function() {
                var code = this.options[this.selectedIndex].getAttribute('data-code');
                var phoneInput = this.closest('.input-group').querySelector('input[name="phone"]');
                if (phoneInput && code) {
                    phoneInput.placeholder = code;
                }
            });
        }

    // ===== DEFAULT PAGE VIDEO =====
    } else {
        var videoModal = document.getElementById('videoModal');
        var videoIframe = document.getElementById('videoIframe');

        if (videoModal && videoIframe) {
            videoModal.addEventListener('show.bs.modal', function () {
                videoIframe.src = convertToEmbedUrl(videoUrl);
            });
            videoModal.addEventListener('hide.bs.modal', function () {
                videoIframe.src = '';
            });
        }

        // Phone country code (default)
        var defaultCountry = document.querySelector('select[name="country"]');
        if (defaultCountry) {
            defaultCountry.addEventListener('change', function() {
                var code = this.options[this.selectedIndex].dataset.code;
                document.querySelector('input[name="phone"]').placeholder = code;
            });
        }
    }

    /**
     * Video URL-ni embed formatına çevir
     */
    function convertToEmbedUrl(url) {
        if (!url) return '';

        // YouTube: youtube.com/watch?v=xxx
        if (url.includes('youtube.com/watch')) {
            var match = url.match(/[?&]v=([^&]+)/);
            if (match) return 'https://www.youtube.com/embed/' + match[1] + '?autoplay=1&rel=0';
        }
        // YouTube: youtu.be/xxx
        if (url.includes('youtu.be/')) {
            var id = url.split('youtu.be/')[1];
            if (id) {
                id = id.split('?')[0].split('#')[0];
                return 'https://www.youtube.com/embed/' + id + '?autoplay=1&rel=0';
            }
        }
        // YouTube: already embed
        if (url.includes('youtube.com/embed/')) {
            return url + (url.includes('?') ? '&' : '?') + 'autoplay=1';
        }
        // Vimeo
        if (url.includes('vimeo.com/')) {
            var vid = url.split('vimeo.com/')[1];
            if (vid) {
                vid = vid.split('?')[0].split('/')[0];
                return 'https://player.vimeo.com/video/' + vid + '?autoplay=1';
            }
        }
        return url;
    }

})();
</script>
@endpush