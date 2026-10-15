@extends('layout_inner_page')

@section('title')
    <title>{{ __('translate.Service Types') }} | Санатории</title>
    <meta name="title" content="{{ __('translate.Service Types') }}">
    <meta name="description" content="{{ __('translate.Service Types') }}">
@endsection

@push('style_section')
    <style>
        .tg-page-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #334155 100%);
            padding: 180px 0 60px;
            color: #fff;
            text-align: center;
        }
        .tg-page-hero h1 { color: #fff; font-size: 38px; margin-bottom: 12px; }
        .tg-page-hero .tg-breadcrumb { margin-bottom: 18px; font-size: 14px; color: rgba(255,255,255,.6); }
        .tg-page-hero .tg-breadcrumb a { color: rgba(255,255,255,.85); }
        .tg-page-section { padding: 60px 0 80px; background: #f8fafc; }

        .tg-type-card {
            display: block;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            height: 100%;
            transition: transform .35s ease, box-shadow .35s ease;
        }
        .tg-type-card:hover { transform: translateY(-6px); box-shadow: 0 20px 25px -5px rgba(0,0,0,.12); }
        .tg-type-card__thumb { height: 180px; overflow: hidden; background: #f1f5f9; }
        .tg-type-card__thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform .6s ease; }
        .tg-type-card:hover .tg-type-card__thumb img { transform: scale(1.08); }
        .tg-type-card__placeholder {
            width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            color: #cbd5e1; font-size: 40px;
        }
        .tg-type-card__body { padding: 20px 22px 24px; }
        .tg-type-card__title { font-size: 20px; font-weight: 600; color: #0f172a; margin: 0 0 6px; }
        .tg-type-card__count { font-size: 13px; color: #f43f5e; font-weight: 600; }
        @media (max-width: 767px) { .tg-page-hero { padding: 130px 0 40px; } .tg-page-hero h1 { font-size: 28px; } }
    </style>
@endpush

@section('front-content')
    <section class="tg-page-hero">
        <div class="container">
            <div class="tg-breadcrumb">
                <a href="{{ route('home') }}">{{ __('translate.Home') }}</a>
                <span> / {{ __('translate.Service Types') }}</span>
            </div>
            <h1>{{ __('translate.Service Types') }}</h1>
        </div>
    </section>

    <section class="tg-page-section">
        <div class="container">
            @if ($serviceTypes->count() > 0)
                <div class="row g-4">
                    @foreach ($serviceTypes as $serviceType)
                        @php $name = $serviceType?->translation?->name ?? $serviceType?->name; @endphp
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                            <a class="tg-type-card" href="{{ route('front.tourbooking.service-types.show', ['slug' => $serviceType->slug]) }}">
                                <div class="tg-type-card__thumb">
                                    @if ($serviceType->image)
                                        <img src="{{ asset($serviceType->image) }}" alt="{{ $name }}">
                                    @else
                                        <div class="tg-type-card__placeholder">
                                            @if ($serviceType->icon)
                                                <i class="{{ $serviceType->icon }}"></i>
                                            @else
                                                <i class="fa-solid fa-layer-group"></i>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <div class="tg-type-card__body">
                                    <h3 class="tg-type-card__title">{{ $name }}</h3>
                                    <span class="tg-type-card__count">
                                        {{ $serviceType->services_count ?? 0 }} {{ __('translate.Services') }}
                                    </span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <p style="color:#94a3b8;">{{ __('translate.No Data Found') }}</p>
                </div>
            @endif
        </div>
    </section>
@endsection
