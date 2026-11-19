@extends('layout_inner_page')

@section('title')
    <title>{{ $disease ? $disease->h1 : 'Все санатории' }}</title>
    <meta name="title" content="{{ $disease ? $disease->h1 : 'Все санатории' }}">
    <meta name="description" content="{{ $disease ? \Illuminate\Support\Str::limit(strip_tags($disease->description ?? $disease->name), 160) : 'Все санатории Нафталана и Грузии на одном сайте. Сравните цены, процедуры и отзывы. ' . \App\Services\CompanyFacts::plural(company_facts()->partnersCount(), 'санаторий', 'санатория', 'санаториев') . ' в каталоге. Бронирование без предоплаты.' }}">
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
                @if ($disease)
                    <a href="{{ localize_front_url('/napravleniya-lecheniya') }}">{{ (request()->attributes->get('display_lang') ?: app()->getLocale()) === 'ru' ? 'Направления лечения' : 'Treatment directions' }}</a>
                    <span> / {{ $disease->name }}</span>
                @else
                    <span>Все санатории</span>
                @endif
            </div>
            <h1>{{ $disease ? $disease->h1 : 'Все санатории' }}</h1>
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
                @include('tourbooking::front.services.gscard-grid', ['allServices' => $services])
                <div class="text-center mt-50 mb-30">
                    @include('components.front.custom-pagination', ['items' => $services])
                </div>
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

    {{-- Task 14: ItemList + BreadcrumbList JSON-LD --}}
    @php
        $sdCrumbs = [\App\Support\Breadcrumbs::home()];
        if ($disease) {
            $sdCrumbs[] = ['name' => 'Направления лечения', 'url' => localize_front_url('/napravleniya-lecheniya')];
            $sdCrumbs[] = ['name' => (string) $disease->name, 'url' => null];
        } else {
            $sdCrumbs[] = ['name' => 'Санатории', 'url' => null];
        }
        $sdOffset = method_exists($services, 'firstItem') ? ($services->firstItem() ?? 1) : 1;
    @endphp
    @include('partials.breadcrumb_trail', ['crumbs' => $sdCrumbs])
    {!! \App\Support\StructuredData::script(\App\Support\StructuredData::itemList($disease ? (string) $disease->name : 'Санатории', method_exists($services, 'getCollection') ? $services->getCollection() : $services, $sdOffset)) !!}
@endsection
