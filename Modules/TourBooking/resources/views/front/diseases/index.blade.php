@extends('layout_inner_page')

@section('title')
    <title>{{ \Modules\TourBooking\App\Models\Disease::t('Sanatoriums by Treatment Profile') }} | Санатории</title>
    <meta name="title" content="{{ \Modules\TourBooking\App\Models\Disease::t('Sanatoriums by Treatment Profile') }}">
    <meta name="description" content="{{ \Modules\TourBooking\App\Models\Disease::t('Sanatoriums by Treatment Profile') }}">
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
        @media (max-width: 767px) { .tg-page-hero { padding: 130px 0 40px; } .tg-page-hero h1 { font-size: 28px; } }
    </style>
@endpush

@section('front-content')
    <section class="tg-page-hero">
        <div class="container">
            <div class="tg-breadcrumb">
                <a href="{{ route('home') }}">{{ __('translate.Home') }}</a>
                <span> / {{ \Modules\TourBooking\App\Models\Disease::t('Sanatoriums by Treatment Profile') }}</span>
            </div>
            <h1>{{ \Modules\TourBooking\App\Models\Disease::t('Sanatoriums by Treatment Profile') }}</h1>
        </div>
    </section>

    <section class="tg-page-section">
        <div class="container">
            @if ($diseases->count() > 0)
                <div class="diseases-grid">
                    @foreach ($diseases as $disease)
                        <a href="{{ route('diseases.show', ['slug' => $disease->slug]) }}" class="disease-card{{ $disease->image ? '' : ' no-image' }}">
                            @if ($disease->image)
                                <img src="{{ asset($disease->image) }}" alt="{{ $disease->name }}" class="card-img" loading="lazy">
                            @endif
                            <div class="card-overlay"></div>
                            <span class="card-title">{{ $disease->name }}</span>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="gs-disease-empty">{{ \Modules\TourBooking\App\Models\Disease::t('Coming soon') }}</div>
            @endif
        </div>
    </section>
@endsection
