@extends('layout_inner_page')

@section('title')
    <title>{{ __('translate.Destinations') }} | Санатории</title>
    <meta name="title" content="{{ __('translate.Destinations') }}">
    <meta name="description" content="{{ __('translate.Destinations') }}">
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

        .tg-dest-card {
            display: block;
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            height: 280px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,.1);
            transition: transform .35s ease, box-shadow .35s ease;
        }
        .tg-dest-card:hover { transform: translateY(-6px); box-shadow: 0 20px 25px -5px rgba(0,0,0,.18); }
        .tg-dest-card img { width: 100%; height: 100%; object-fit: cover; transition: transform .6s ease; }
        .tg-dest-card:hover img { transform: scale(1.08); }
        .tg-dest-card__placeholder {
            width: 100%; height: 100%;
            background: linear-gradient(135deg, #334155, #0f172a);
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.4); font-size: 40px;
        }
        .tg-dest-card__overlay {
            position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0) 35%, rgba(0,0,0,.8) 100%);
            display: flex; flex-direction: column; justify-content: flex-end;
            padding: 22px;
        }
        .tg-dest-card__title { color: #fff; font-size: 22px; font-weight: 600; margin: 0 0 4px; }
        .tg-dest-card__meta { color: rgba(255,255,255,.8); font-size: 13px; }
        @media (max-width: 767px) { .tg-page-hero { padding: 130px 0 40px; } .tg-page-hero h1 { font-size: 28px; } }
    </style>
@endpush

@section('front-content')
    <section class="tg-page-hero">
        <div class="container">
            <div class="tg-breadcrumb">
                <a href="{{ route('home') }}">{{ __('translate.Home') }}</a>
                <span> / {{ __('translate.Destinations') }}</span>
            </div>
            <h1>{{ __('translate.Destinations') }}</h1>
        </div>
    </section>

    <section class="tg-page-section">
        <div class="container">
            @if ($destinations->count() > 0)
                <div class="row g-4">
                    @foreach ($destinations as $destination)
                        @php $name = $destination?->translation?->name ?? $destination?->name; @endphp
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                            <a class="tg-dest-card" href="{{ route('front.tourbooking.destinations.show', ['slug' => $destination->slug]) }}">
                                @if ($destination->image)
                                    <img src="{{ asset($destination->image) }}" alt="{{ $name }}">
                                @else
                                    <div class="tg-dest-card__placeholder"><i class="fa-solid fa-location-dot"></i></div>
                                @endif
                                <div class="tg-dest-card__overlay">
                                    <h3 class="tg-dest-card__title">{{ $name }}</h3>
                                    @if ($destination->country || $destination->city)
                                        <span class="tg-dest-card__meta">
                                            <i class="fa-solid fa-location-dot"></i>
                                            {{ collect([$destination->city, $destination->country])->filter()->implode(', ') }}
                                        </span>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-50">
                    @include('components.front.custom-pagination', ['items' => $destinations])
                </div>
            @else
                <div class="text-center py-5">
                    <p style="color:#94a3b8;">{{ __('translate.No Data Found') }}</p>
                </div>
            @endif
        </div>
    </section>
@endsection
