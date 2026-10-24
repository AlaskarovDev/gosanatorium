@extends('layout_inner_page')

@section('title')
    <title>{{ $seo_setting->seo_title }}</title>
    <meta name="title" content="{{ $seo_setting->seo_title }}">
    <meta name="description" content="{!! strip_tags(clean($seo_setting->seo_description)) !!}">
@endsection

@section('front-content')
<style>
*{box-sizing:border-box}
.gsfaq-hero{background:linear-gradient(135deg,#0d7a68 0%,#1a9e87 50%,#2ec4a9 100%);padding:72px 0 60px;position:relative;overflow:hidden}
.gsfaq-hero::before{content:'';position:absolute;top:-80px;right:-80px;width:400px;height:400px;background:rgba(255,255,255,0.05);border-radius:50%}
.gsfaq-hero::after{content:'';position:absolute;bottom:-100px;left:-60px;width:300px;height:300px;background:rgba(255,255,255,0.04);border-radius:50%}
.gsfaq-hero-inner{position:relative;z-index:2;text-align:center}
.gsfaq-badge{display:inline-block;background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3);border-radius:50px;padding:7px 20px;font-size:12px;font-weight:700;color:#fff;letter-spacing:1.5px;text-transform:uppercase;margin-bottom:20px}
.gsfaq-hero-title{font-size:46px;font-weight:900;color:#fff;margin-bottom:14px;text-transform:none;letter-spacing:-1px;line-height:1.1}
.gsfaq-hero-sub{font-size:17px;color:rgba(255,255,255,0.82);max-width:520px;margin:0 auto 36px;line-height:1.6;text-transform:none}
.gsfaq-search-wrap{max-width:520px;margin:0 auto;position:relative}
.gsfaq-search{width:100%;padding:16px 56px 16px 22px;border:none;border-radius:50px;font-size:15px;color:#1a1a2e;background:#fff;box-shadow:0 8px 32px rgba(0,0,0,0.15);outline:none;font-family:inherit}
.gsfaq-search-icon{position:absolute;right:20px;top:50%;transform:translateY(-50%);color:#1a9e87;font-size:18px;pointer-events:none}
.gsfaq-stats{display:flex;justify-content:center;gap:40px;margin-top:36px;flex-wrap:wrap}
.gsfaq-stat{text-align:center}
.gsfaq-stat-num{font-size:28px;font-weight:900;color:#fff}
.gsfaq-stat-lbl{font-size:12px;color:rgba(255,255,255,0.72);text-transform:uppercase;letter-spacing:1px}
.gsfaq-main{padding:72px 0 80px;background:#f8fafb}
.gsfaq-layout{display:grid;grid-template-columns:260px 1fr;gap:40px;align-items:start}
.gsfaq-sidebar{position:sticky;top:24px}
.gsfaq-cat-list{background:#fff;border-radius:18px;padding:8px;box-shadow:0 2px 16px rgba(0,0,0,0.07)}
.gsfaq-cat-btn{display:flex;align-items:center;gap:10px;width:100%;padding:12px 16px;border:none;background:transparent;border-radius:12px;font-size:14px;font-weight:600;color:#6b7280;cursor:pointer;text-align:left;transition:all 0.2s;font-family:inherit}
.gsfaq-cat-btn:hover{background:#f0faf8;color:#1a9e87}
.gsfaq-cat-btn.active{background:linear-gradient(135deg,#1a9e87,#2ec4a9);color:#fff}
.gsfaq-cat-btn.active .gsfaq-cat-count{background:rgba(255,255,255,0.25);color:#fff}
.gsfaq-cat-icon{font-size:16px;flex-shrink:0}
.gsfaq-cat-text{flex:1}
.gsfaq-cat-count{background:#f0faf8;color:#1a9e87;font-size:11px;font-weight:700;padding:2px 8px;border-radius:50px;min-width:24px;text-align:center}
.gsfaq-content-area{}
.gsfaq-group{margin-bottom:40px}
.gsfaq-group-header{display:flex;align-items:center;gap:12px;margin-bottom:20px}
.gsfaq-group-icon{width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#1a9e87,#2ec4a9);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.gsfaq-group-icon i{color:#fff;font-size:17px}
.gsfaq-group-title{font-size:20px;font-weight:800;color:#1a1a2e;text-transform:none;letter-spacing:-0.3px}
.gsfaq-accordion{display:flex;flex-direction:column;gap:10px}
.gsfaq-item{background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.06);border:1.5px solid transparent;transition:border-color 0.25s}
.gsfaq-item.open{border-color:#1a9e87}
.gsfaq-q{display:flex;align-items:center;gap:14px;padding:18px 20px;cursor:pointer;transition:background 0.2s}
.gsfaq-q:hover{background:#f9fffe}
.gsfaq-qnum{width:28px;height:28px;border-radius:8px;background:#f0faf8;color:#1a9e87;font-size:12px;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.gsfaq-item.open .gsfaq-qnum{background:linear-gradient(135deg,#1a9e87,#2ec4a9);color:#fff}
.gsfaq-qtext{flex:1;font-size:15px;font-weight:700;color:#1a1a2e;text-transform:none;line-height:1.4}
.gsfaq-qarrow{width:28px;height:28px;border-radius:8px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all 0.25s}
.gsfaq-item.open .gsfaq-qarrow{background:linear-gradient(135deg,#1a9e87,#2ec4a9);transform:rotate(180deg)}
.gsfaq-qarrow i{color:#6b7280;font-size:12px;transition:color 0.25s}
.gsfaq-item.open .gsfaq-qarrow i{color:#fff}
.gsfaq-ans{display:none;padding:0 20px 20px 62px;font-size:14px;color:#4b5563;line-height:1.75;text-transform:none}
.gsfaq-item.open .gsfaq-ans{display:block}
.gsfaq-cta{background:linear-gradient(135deg,#0d7a68,#1a9e87 60%,#2ec4a9);padding:64px 0;text-align:center;position:relative;overflow:hidden}
.gsfaq-cta::before{content:'';position:absolute;top:-100px;right:-100px;width:400px;height:400px;background:rgba(255,255,255,0.05);border-radius:50%}
.gsfaq-cta-inner{position:relative;z-index:2}
.gsfaq-cta-title{font-size:32px;font-weight:800;color:#fff;margin-bottom:12px;text-transform:none}
.gsfaq-cta-sub{font-size:16px;color:rgba(255,255,255,0.82);margin-bottom:32px;text-transform:none}
.gsfaq-cta-btns{display:flex;justify-content:center;gap:16px;flex-wrap:wrap}
.gsfaq-btn-white{display:inline-flex;align-items:center;gap:8px;padding:15px 32px;background:#fff;color:#1a9e87;font-size:15px;font-weight:700;border-radius:50px;text-decoration:none;box-shadow:0 6px 24px rgba(0,0,0,0.15);transition:all 0.3s}
.gsfaq-btn-white:hover{transform:translateY(-2px);box-shadow:0 12px 32px rgba(0,0,0,0.2);text-decoration:none;color:#1a9e87}
.gsfaq-btn-outline{display:inline-flex;align-items:center;gap:8px;padding:15px 32px;background:transparent;color:#fff;font-size:15px;font-weight:700;border-radius:50px;border:2px solid rgba(255,255,255,0.5);text-decoration:none;transition:all 0.3s}
.gsfaq-btn-outline:hover{background:rgba(255,255,255,0.12);text-decoration:none;color:#fff}
@media(max-width:900px){.gsfaq-layout{grid-template-columns:1fr!important}.gsfaq-sidebar{position:static!important}.gsfaq-hero-title{font-size:30px!important}}
@media(max-width:576px){.gsfaq-stats{gap:20px!important}.gsfaq-cta-title{font-size:24px!important}}
</style>

    @include('breadcrumb')

    <!-- tg-faq-area-start -->
    <div class="tg-pricing-area pb-120 pt-125 p-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="tg-faq-content-wrap">
                        <div class="tg-faq-section-title text-center mb-40">
                            <h5 class="tg-section-subtitle mb-15 wow fadeInUp" data-wow-delay=".3s" data-wow-duration=".9s">
                                {{ getTranslatedValue($faq, 'section_subtitle') }}
                            </h5>
                            <h2 class="mb-15 wow fadeInUp" data-wow-delay=".4s" data-wow-duration=".9s">
                                {{ getTranslatedValue($faq, 'section_title') }}
                            </h2>
                        </div>
                        @if (!empty($faq?->data_values['slides']) && count($faq->data_values['slides']) > 0)
                            <div class="tg-faq-content">
                                <div class="accordion tg-custom-accordion" id="accordionExample">
                                    @foreach ($faq->data_values['slides'] as $key => $slide)
                                        <div @class([
                                            'accordion-item mb-10 wow fadeInUp',
                                            'tg-faq-active' => $key == 0,
                                        ]) data-wow-delay=".3s" data-wow-duration=".9s">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button {{ $key != 0 ? 'collapsed' : '' }}"
                                                    type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapse_{{ $key }}"
                                                    aria-expanded="{{ $key == 0 ? 'true' : 'false' }}"
                                                    aria-controls="collapse_{{ $key }}">
                                                    {{ $slide['title'] ?? 'No question provided' }}
                                                </button>
                                            </h2>
                                            <div id="collapse_{{ $key }}" @class(['accordion-collapse collapse', 'show' => $key == 0])
                                                data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <p class="mb-0">
                                                        {!! strip_tags(clean($slide['description'] ?? 'No answer provided')) !!}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- tg-faq-area-end -->

    <!-- tg-cta-area-start -->
    <div class="tg-cta-area-area tg-cta-space pt-125 z-index-9 p-relative">
        <img class="tg-cta-price-shape d-none d-xl-block" src="{{ asset('frontend/assets/img/shape/hill.png') }}" alt="">
        <img class="tg-cta-price-shape-2 d-none d-xl-block" src="{{ asset('frontend/assets/img/shape/tree.png') }}" alt="">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="tg-cta-wrap include-bg"
                        data-background="{{ asset('frontend/assets/img/shape/cta-banner.jpg') }}">
                        <div class="row align-items-end">
                            <div class="col-lg-3 d-none d-lg-block">
                                <div class="tg-cta-thumb pt-50 ml-60">
                                    <img src="{{ asset(getSingleImage($footer_cta, 'image')) }}" alt="">
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-6">
                                <div class="tg-cta-content">
                                    <h5 class="tg-section-subtitle text-white mb-10">
                                        {{ getTranslatedValue($footer_cta, 'subtitle') }}</h5>
                                    <h2 class="mb-15 tg-cta-title text-white">
                                        {!! strip_tags(clean(getTranslatedValue($footer_cta, 'title'))) !!}
                                    </h2>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="tg-cta-apps">
                                    <a class="mb-20 d-inline-block mr-5"
                                        href="{{ getTranslatedValue($footer_cta, 'apple_store_link') }}">
                                        <img src="{{ asset('frontend/assets/img/shape/google.png') }}" alt="">
                                    </a>
                                    <a class="mb-20 d-inline-block"
                                        href="{{ getTranslatedValue($footer_cta, 'google_play_link') }}">
                                        <img src="{{ asset('frontend/assets/img/shape/app.png') }}" alt="">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- tg-cta-area-end -->
@endsection
