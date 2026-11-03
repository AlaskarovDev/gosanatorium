@extends('layout_inner_page')

@section('title')
    <title>{{ $disease ? $disease->name . ' | Санатории' : 'Все санатории' }}</title>
    <meta name="title" content="{{ $disease ? $disease->name : 'Все санатории' }}">
    <meta name="description" content="{{ $disease ? \Illuminate\Support\Str::limit(strip_tags($disease->description ?? $disease->name), 160) : 'Каталог санаториев Нафталана и Грузии' }}">
@endsection

@push('style_section')
    <style>
        .tg-page-hero {
            position: relative;
            background-color: #1e293b;
            background-size: cover;
            background-position: center;
            padding: 180px 0 60px;
            color: #fff;
            text-align: center;
        }
        .tg-page-hero .container { position: relative; z-index: 1; }
        .tg-page-hero h1 { text-shadow: 0 2px 8px rgba(0,0,0,.5); }
        .tg-page-hero p { text-shadow: 0 1px 6px rgba(0,0,0,.6); color: rgba(255,255,255,.9); }
        .gs-disease-credit { text-align: center; font-size: 12px; color: #94a3b8; margin-top: 24px; }
        .tg-page-hero h1 { color: #fff; font-size: 38px; margin-bottom: 12px; }
        .tg-page-hero p { color: rgba(255,255,255,.7); max-width: 720px; margin: 0 auto; }
        .tg-page-hero .tg-breadcrumb { margin-bottom: 18px; font-size: 14px; color: rgba(255,255,255,.6); }
        .tg-page-hero .tg-breadcrumb a { color: rgba(255,255,255,.85); }
        .gs-reset-link { color: var(--tg-theme-primary, #1a9e87); font-weight: 600; text-decoration: underline; }
        .gs-filter-empty { text-align: center; padding: 40px 16px; }
        .gs-filter-empty p { font-size: 18px; color: #334155; margin-bottom: 20px; }
        .gs-reset-filters-btn { display: inline-block; padding: 12px 28px; border-radius: 50px; background: var(--tg-theme-primary, #1a9e87); color: #fff; font-weight: 600; }
        .gs-reset-filters-btn:hover { color: #fff; opacity: .9; }
        .tg-page-section { padding: 60px 0 80px; background: #f8fafc; }
        @media (max-width: 767px) { .tg-page-hero { padding: 130px 0 40px; } .tg-page-hero h1 { font-size: 28px; } }
    </style>
@endpush

@section('front-content')
    <section class="tg-page-hero" style="background-image: linear-gradient(rgba(15,23,42,.62), rgba(15,23,42,.72)), url('{{ $disease && $disease->image ? $disease->image_url : asset('images/og-default.jpg') }}');">
        <div class="container">
            <div class="tg-breadcrumb">
                <a href="{{ route('home') }}">{{ __('translate.Home') }}</a>
                <span> / </span>
                <a href="{{ route('diseases.index') }}">{{ \Modules\TourBooking\App\Models\Disease::t('Sanatoriums by Treatment Profile') }}</a>
                <span> / {{ $disease ? $disease->name : 'Все санатории' }}</span>
            </div>
            <h1>{{ $disease ? $disease->name : 'Все санатории' }}</h1>
            @if ($disease && $disease->description)
                <p>{{ \Illuminate\Support\Str::limit(strip_tags($disease->description), 400) }}</p>
            @endif
        </div>
    </section>

    <section class="tg-page-section">
        <div class="container">
            @if (!empty($filtersActive) && $services->count() > 0)
                <div class="text-end mb-3"><a href="{{ localize_front_url('/sanatoriyi') }}" class="gs-reset-link">Сбросить фильтры</a></div>
            @endif
            @if ($services->count() > 0)
                @include('tourbooking::front.services.services-item', [
                    'allServices' => $services,
                    'isListView' => 'false',
                ])
            @else
                @if (!empty($filtersActive))
                    <div class="gs-filter-empty">
                        <p>По вашему запросу ничего не найдено. Попробуйте изменить фильтры.</p>
                        <a href="{{ localize_front_url('/sanatoriyi') }}" class="tg-btn gs-reset-filters-btn">Сбросить фильтры</a>
                    </div>
                @else
                    <div class="gs-disease-empty">{{ \Modules\TourBooking\App\Models\Disease::t('Coming soon') }}</div>
                @endif
            @endif
            @if ($disease && $disease->image_credit)
                <div class="gs-disease-credit">{{ $disease->image_credit }}</div>
            @endif
        </div>
    </section>
@endsection
