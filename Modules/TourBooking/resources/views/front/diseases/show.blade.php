@extends('layout_inner_page')

@section('title')
    <title>{{ $disease->name }} | Санатории</title>
    <meta name="title" content="{{ $disease->name }}">
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($disease->description ?? $disease->name), 160) }}">
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
        .tg-page-section { padding: 60px 0 80px; background: #f8fafc; }
        @media (max-width: 767px) { .tg-page-hero { padding: 130px 0 40px; } .tg-page-hero h1 { font-size: 28px; } }
    </style>
@endpush

@section('front-content')
    <section class="tg-page-hero" style="background-image: linear-gradient(rgba(15,23,42,.62), rgba(15,23,42,.72)), url('{{ $disease->image ? $disease->image_url : asset('images/og-default.jpg') }}');">
        <div class="container">
            <div class="tg-breadcrumb">
                <a href="{{ route('home') }}">{{ __('translate.Home') }}</a>
                <span> / </span>
                <a href="{{ route('diseases.index') }}">{{ \Modules\TourBooking\App\Models\Disease::t('Sanatoriums by Treatment Profile') }}</a>
                <span> / {{ $disease->name }}</span>
            </div>
            <h1>{{ $disease->name }}</h1>
            @if ($disease->description)
                <p>{{ \Illuminate\Support\Str::limit(strip_tags($disease->description), 400) }}</p>
            @endif
        </div>
    </section>

    <section class="tg-page-section">
        <div class="container">
            @if ($services->count() > 0)
                @include('tourbooking::front.services.services-item', [
                    'allServices' => $services,
                    'isListView' => 'false',
                ])
            @else
                <div class="gs-disease-empty">{{ \Modules\TourBooking\App\Models\Disease::t('Coming soon') }}</div>
            @endif
            @if ($disease->image_credit)
                <div class="gs-disease-credit">{{ $disease->image_credit }}</div>
            @endif
        </div>
    </section>
@endsection
