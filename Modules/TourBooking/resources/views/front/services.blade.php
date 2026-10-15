@extends('layout_inner_page')

@section('title')
    <title>{{ __('translate.Services') }} | Санатории</title>
    <meta name="title" content="{{ __('translate.Services') }}">
    <meta name="description" content="{{ __('translate.Services') }}">
@endsection

@push('style_section')
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    {{-- AOS --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    {{-- Flatpickr --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">

    <style>
        :root {
            --primary: #0f172a;
            --secondary: #1e293b;
            --accent: #f43f5e;
            --accent-hover: #e11d48;
            --accent-light: #fda4af;
            --gold: #eab308;
            --success: #10b981;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --border: #e2e8f0;
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #f1f5f9;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.08);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0,0,0,0.1);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .svc-page-body {
            font-family: 'DM Sans', sans-serif;
            color: var(--text-primary);
            background: var(--bg-secondary);
            line-height: 1.6;
        }

        .svc-page-body h1, .svc-page-body h2, .svc-page-body h3,
        .svc-page-body h4, .svc-page-body h5, .svc-page-body h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            line-height: 1.3;
        }

        .svc-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* HERO */
        .svc-hero {
            position: relative;
            min-height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: visible;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 60%, #334155 100%);
            padding-top: 200px;
            padding-bottom: 50px;
        }

        .svc-hero__bg {
            position: absolute;
            inset: 0;
            opacity: 0.08;
            background: url("{{ asset('frontend/assets/img/shape/hill-4.png') }}") center / cover no-repeat;
        }

        .svc-hero__content {
            position: relative;
            z-index: 10;
            width: 100%;
        }

        /* SEARCH FORM */
        .sf-card {
            background: rgba(255,255,255,0.07);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: var(--radius-xl);
            padding: 24px 28px;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 50;
        }

        .sf-grid {
            display: grid;
            grid-template-columns: 1.3fr 1fr 1fr 1.3fr auto;
            gap: 14px;
            align-items: end;
        }

        @media (max-width: 1199px) {
            .sf-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
        }

        @media (max-width: 575px) {
            .sf-grid { grid-template-columns: 1fr; }
        }

        .sf-group { display: flex; flex-direction: column; gap: 6px; }

        .sf-label {
            font-size: 11px;
            font-weight: 700;
            color: rgba(255,255,255,0.6);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding-left: 2px;
        }

        .sf-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .sf-input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--accent);
            font-size: 14px;
            pointer-events: none;
            z-index: 2;
            width: 16px;
            text-align: center;
        }

        .sf-input {
            width: 100%;
            padding: 14px 16px 14px 60px !important;
            background: rgba(255,255,255,0.1);
            border: 1.5px solid rgba(255,255,255,0.12);
            border-radius: var(--radius-md);
            color: #fff;
            font-family: inherit;
            font-size: 14px;
            transition: var(--transition);
            cursor: pointer;
            height: 50px;
        }

        .sf-input::placeholder { color: rgba(255,255,255,0.4); }

        .sf-input:focus {
            outline: none;
            border-color: var(--accent);
            background: rgba(255,255,255,0.14);
        }

        /* Destination Dropdown */
        .sf-dest-wrap { position: relative; }

        .sf-dest-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            background: var(--bg-primary);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-xl);
            border: 1px solid var(--border);
            max-height: 320px;
            overflow-y: auto;
            z-index: 999;
        }

        .sf-dest-wrap.open .sf-dest-dropdown {
            display: block;
            animation: sfDropIn 0.2s ease;
        }

        @keyframes sfDropIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .sf-dest-dropdown ul { list-style: none; padding: 6px; margin: 0; }

        .sf-dest-dropdown li {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 14px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: var(--transition);
            color: var(--text-primary);
            font-size: 14px;
        }

        .sf-dest-dropdown li:hover {
            background: var(--bg-secondary);
            color: var(--accent);
        }

        .sf-dest-dropdown li i {
            color: var(--accent);
            font-size: 13px;
            width: 16px;
            text-align: center;
            flex-shrink: 0;
        }

        .sf-dest-dropdown .sf-dest-section {
            padding: 8px 14px 4px;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: default;
        }

        .sf-dest-dropdown .sf-dest-section:hover {
            background: transparent;
            color: var(--text-muted);
        }

        /* Guest Dropdown */
        .sf-guest-wrap { position: relative; }

        .sf-guest-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            min-width: 280px;
            background: var(--bg-primary);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-xl);
            border: 1px solid var(--border);
            z-index: 999;
            padding: 18px 20px;
        }

        .sf-guest-wrap.open .sf-guest-dropdown {
            display: block;
            animation: sfDropIn 0.2s ease;
        }

        .sf-guest-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--border);
        }

        .sf-guest-row:last-child { border-bottom: none; padding-bottom: 0; }

        .sf-guest-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .sf-counter {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sf-counter-btn {
            width: 34px;
            height: 34px;
            border: 2px solid var(--border);
            background: var(--bg-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            font-size: 12px;
            color: var(--text-secondary);
        }

        .sf-counter-btn:hover { border-color: var(--accent); color: var(--accent); }

        .sf-counter-val {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-primary);
            min-width: 24px;
            text-align: center;
        }

        /* Search Button */
        .sf-search-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 28px;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
            height: 50px;
        }

        .sf-search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(244, 63, 94, 0.4);
        }

        @media (max-width: 1199px) {
            .sf-search-btn { grid-column: span 2; }
        }

        @media (max-width: 575px) {
            .sf-search-btn { grid-column: span 1; }
        }

        /* FLATPICKR FIX */
        .flatpickr-calendar {
            box-shadow: var(--shadow-xl) !important;
            border-radius: var(--radius-md) !important;
            border: 1px solid var(--border) !important;
            z-index: 9999 !important;
        }

        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange {
            background: var(--accent) !important;
            border-color: var(--accent) !important;
            color: #fff !important;
        }

        .flatpickr-day:hover {
            background: var(--bg-tertiary) !important;
            border-color: var(--border) !important;
            color: var(--text-primary) !important;
        }

        .flatpickr-day.today {
            border-color: var(--accent) !important;
        }

        /* LISTING */
        .svc-listing { padding: 50px 0 80px; }

        .svc-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 28px;
        }

        @media (max-width: 991px) {
            .svc-layout { grid-template-columns: 1fr; }
        }

        /* FILTER SIDEBAR */
        .fl-sidebar { position: sticky; top: 100px; }

        .fl-card {
            background: var(--bg-primary);
            border-radius: var(--radius-xl);
            padding: 24px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
        }

        .fl-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .fl-header h3 { font-size: 20px; margin: 0; }

        .fl-reset {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            background: rgba(244, 63, 94, 0.08);
            color: var(--accent);
            font-size: 12px;
            font-weight: 700;
            border: none;
            border-radius: 100px;
            cursor: pointer;
            transition: var(--transition);
        }

        .fl-reset:hover { background: var(--accent); color: #fff; }

        .fl-divider { height: 1px; background: var(--border); margin: 18px 0; }

        .fl-search { position: relative; margin-bottom: 2px; }

        .fl-search input {
            width: 100%;
            padding: 12px 16px 12px 40px;
            border: 2px solid var(--border);
            border-radius: var(--radius-md);
            font-family: inherit;
            font-size: 13px;
            transition: var(--transition);
            background: var(--bg-secondary);
        }

        .fl-search input:focus {
            outline: none;
            border-color: var(--accent);
            background: var(--bg-primary);
        }

        .fl-search i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 13px;
            pointer-events: none;
        }

        .fl-title {
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-primary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .fl-list { list-style: none; padding: 0; margin: 0; }
        .fl-list li { margin-bottom: 4px; }

        .fl-check {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 7px 10px;
            border-radius: var(--radius-sm);
            transition: var(--transition);
        }

        .fl-check:hover { background: var(--bg-secondary); }
        .fl-check input[type="checkbox"] { display: none; }

        .fl-box {
            width: 18px;
            height: 18px;
            border: 2px solid var(--border);
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            flex-shrink: 0;
        }

        .fl-check input:checked + .fl-box {
            background: var(--accent);
            border-color: var(--accent);
        }

        .fl-box i { font-size: 9px; color: #fff; opacity: 0; transition: var(--transition); }
        .fl-check input:checked + .fl-box i { opacity: 1; }

        .fl-check-text {
            font-size: 13px;
            color: var(--text-secondary);
            flex: 1;
            line-height: 1.3;
        }

        .fl-price-row { display: flex; align-items: center; gap: 10px; }

        .fl-price-input {
            flex: 1;
            padding: 10px 12px;
            border: 2px solid var(--border);
            border-radius: var(--radius-md);
            font-family: inherit;
            font-size: 13px;
            background: var(--bg-secondary);
            -moz-appearance: textfield;
            transition: var(--transition);
        }

        .fl-price-input::-webkit-outer-spin-button,
        .fl-price-input::-webkit-inner-spin-button { -webkit-appearance: none; }

        .fl-price-input:focus {
            outline: none;
            border-color: var(--accent);
            background: var(--bg-primary);
        }

        .fl-stars { display: flex; gap: 2px; margin-left: 2px; }
        .fl-stars i { font-size: 13px; color: var(--border); }
        .fl-stars i.active { color: var(--gold); }

        .fl-more {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 4px 0;
            margin-top: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 700;
            color: var(--accent);
        }

        .fl-more:hover { color: var(--accent-hover); }
        .fl-more i { font-size: 9px; }

        /* TOOLBAR */
        .svc-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 14px;
        }

        .svc-count { font-size: 14px; color: var(--text-secondary); }
        .svc-toolbar-actions { display: flex; align-items: center; gap: 12px; }

        .svc-sort {
            padding: 9px 34px 9px 12px;
            border: 2px solid var(--border);
            border-radius: var(--radius-md);
            font-family: inherit;
            font-size: 13px;
            color: var(--text-primary);
            background: var(--bg-primary);
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 10 10'%3E%3Cpath d='M5 7L1 3h8z' fill='%2394a3b8'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            transition: var(--transition);
        }

        .svc-sort:focus { outline: none; border-color: var(--accent); }

        .svc-view-toggle {
            display: flex;
            gap: 3px;
            background: var(--bg-secondary);
            border-radius: var(--radius-sm);
            padding: 3px;
        }

        .svc-view-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            border-radius: 6px;
            color: var(--text-muted);
            cursor: pointer;
            transition: var(--transition);
            font-size: 14px;
        }

        .svc-view-btn.active {
            background: var(--bg-primary);
            color: var(--accent);
            box-shadow: var(--shadow-sm);
        }

        /* RESULTS */
        .svc-results { min-height: 300px; }
        .item_loading { top: 20px; position: relative; }

        .tg-listing-card-thumb {
            height: 200px;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
            overflow: hidden;
        }

        .tg-listing-card-thumb img {
            height: 100%;
            width: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .tg-listing-card-thumb:hover img { transform: scale(1.06); }

        .list-card.list-card-open .tg-grid-full .tg-listing-card-thumb { max-width: 288px; }

        .list-card.list-card-open .tg-listing-card-thumb img {
            max-width: 317px;
            min-width: 317px;
        }

        .tg-listing-item-wishlist.active svg { color: var(--accent); }

        /* MOBILE */
        @media (max-width: 991px) {
            .fl-sidebar { position: relative; top: 0; order: 2; }
            .svc-content-area { order: 1; }
            .svc-hero { padding-top: 130px; padding-bottom: 40px; }
        }

        @media (max-width: 575px) {
            .sf-card { padding: 18px 16px; }
            .svc-hero { padding-top: 110px; }
        }
    </style>
@endpush

@section('front-content')
    <main class="svc-page-body">
        <div x-data="servicesPageData()">

            {{-- HERO + SEARCH --}}
            <section class="svc-hero">
                <div class="svc-hero__bg"></div>
                <div class="svc-hero__content">
                    <div class="svc-container">
                        <div class="sf-card" data-aos="fade-up">
                            <div class="sf-grid">

                                {{-- SANATORIYA dropdown --}}
                                <div class="sf-group">
                                    <label class="sf-label">{{ __('translate.Destinations:') }}</label>
                                    <div class="sf-dest-wrap" x-data="{ open: false }"
                                         @click.away="open = false"
                                         :class="{ 'open': open }">
                                        <div class="sf-input-wrap" @click="open = !open">
                                            <i class="fa-regular fa-location-dot sf-input-icon"></i>
                                            <input type="text" class="sf-input" readonly
                                                   :value="bookingForm.destination || '{{ __('translate.Where are you going . . .') }}'">
                                        </div>
                                        <div class="sf-dest-dropdown">
                                            <ul>
                                                <li class="sf-dest-section">Санатории</li>
                                                @foreach ($allServicesList as $svc)
                                                    <li @click="selectService(`{{ $svc->id }}`, `{{ $svc->translation?->title }}`); open = false">
                                                        <i class="fa-regular fa-building"></i>
                                                        <span>{{ $svc->translation?->title }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                {{-- CHECK IN --}}
                                <div class="sf-group">
                                    <label class="sf-label">{{ __('translate.Check in:') }}</label>
                                    <div class="sf-input-wrap">
                                        <i class="fa-regular fa-calendar sf-input-icon"></i>
                                        <input x-model="bookingForm.checkIn" class="sf-input js-flatpickr"
                                               type="text" placeholder="Дата заезда" readonly>
                                    </div>
                                </div>

                                {{-- CHECK OUT --}}
                                <div class="sf-group">
                                    <label class="sf-label">{{ __('translate.Check Out:') }}</label>
                                    <div class="sf-input-wrap">
                                        <i class="fa-regular fa-calendar-check sf-input-icon"></i>
                                        <input x-model="bookingForm.checkOut" class="sf-input js-flatpickr"
                                               type="text" placeholder="Дата выезда" readonly>
                                    </div>
                                </div>

                                {{-- GUESTS --}}
                                <div class="sf-group">
                                    <label class="sf-label">{{ __('translate.Guest:') }}</label>
                                    <div class="sf-guest-wrap" x-data="{ open: false }"
                                         @click.away="open = false"
                                         :class="{ 'open': open }">
                                        <div class="sf-input-wrap" @click="open = !open">
                                            <i class="fa-regular fa-user-group sf-input-icon"></i>
                                            <input type="text" class="sf-input" readonly
                                                   :value="guestSummary">
                                        </div>
                                        <div class="sf-guest-dropdown">
                                            <div class="sf-guest-row">
                                                <span class="sf-guest-label">{{ __('translate.Rooms') }}</span>
                                                <div class="sf-counter">
                                                    <button type="button" class="sf-counter-btn" @click="decrement('rooms')">
                                                        <i class="fa-solid fa-minus"></i>
                                                    </button>
                                                    <span class="sf-counter-val" x-text="bookingForm.rooms || 0"></span>
                                                    <button type="button" class="sf-counter-btn" @click="increment('rooms')">
                                                        <i class="fa-solid fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="sf-guest-row">
                                                <span class="sf-guest-label">{{ __('translate.Adults') }}</span>
                                                <div class="sf-counter">
                                                    <button type="button" class="sf-counter-btn" @click="decrement('adults')">
                                                        <i class="fa-solid fa-minus"></i>
                                                    </button>
                                                    <span class="sf-counter-val" x-text="bookingForm.adults || 0"></span>
                                                    <button type="button" class="sf-counter-btn" @click="increment('adults')">
                                                        <i class="fa-solid fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="sf-guest-row">
                                                <span class="sf-guest-label">{{ __('translate.Children') }}</span>
                                                <div class="sf-counter">
                                                    <button type="button" class="sf-counter-btn" @click="decrement('children')">
                                                        <i class="fa-solid fa-minus"></i>
                                                    </button>
                                                    <span class="sf-counter-val" x-text="bookingForm.children || 0"></span>
                                                    <button type="button" class="sf-counter-btn" @click="increment('children')">
                                                        <i class="fa-solid fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- SEARCH BTN --}}
                                <button @click="searchServices" class="sf-search-btn" type="button">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    {{ __('translate.Search') }}
                                </button>

                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- LISTING --}}
            <section class="svc-listing">
                <div class="svc-container">
                    <div class="svc-layout">

                        {{-- SIDEBAR --}}
                        <aside class="fl-sidebar" data-aos="fade-right">
                            <div class="fl-card">

                                <div class="fl-header">
                                    <h3>Фильтры</h3>
                                    <button class="fl-reset"
                                            x-show="isFilterChanged || isBookingFilterChanged"
                                            @click="resetFilters()">
                                        <i class="fa-solid fa-rotate-left"></i> Сбросить
                                    </button>
                                </div>

                                <div class="fl-search">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    <input x-model.debounce="filters.search" type="text"
                                           placeholder="{{ __('translate.Search here...') }}">
                                </div>

                                <div class="fl-divider"></div>

                                {{-- Property Type --}}
                                <div x-data="{ show: false }">
                                    <div class="fl-title">Тип объекта</div>
                                    <ul class="fl-list">
                                        @foreach ($serviceTypes as $key => $serviceType)
                                            <li x-show="show || {{ $key }} < 4" x-transition>
                                                <label class="fl-check">
                                                    <input value="{{ $serviceType?->id }}"
                                                           x-model="filters.service_type_ids" type="checkbox">
                                                    <span class="fl-box"><i class="fa-solid fa-check"></i></span>
                                                    <span class="fl-check-text">{{ $serviceType?->name }}</span>
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                    @if (count($serviceTypes) > 4)
                                        <div class="fl-more" @click="show = !show">
                                            <i :class="show ? 'fa-solid fa-minus' : 'fa-solid fa-plus'"></i>
                                            <span x-text="show ? 'Свернуть' : 'Показать ещё'"></span>
                                        </div>
                                    @endif
                                </div>

                                <div class="fl-divider"></div>

                                {{-- Price --}}
                                <div class="fl-title">Цена</div>
                                <div class="fl-price-row">
                                    <input class="fl-price-input" x-model="filters.min_price" type="number" placeholder="Мин">
                                    <span style="color: var(--text-muted); font-weight: 600;">—</span>
                                    <input class="fl-price-input" x-model="filters.max_price" type="number" placeholder="Макс">
                                </div>

                                <div class="fl-divider"></div>

                                {{-- Amenities --}}
                                <div x-data="{ show: false }">
                                    <div class="fl-title">Удобства</div>
                                    <ul class="fl-list">
                                        @foreach ($amenities as $key => $amenity)
                                            <li x-show="show || {{ $key }} < 4" x-transition>
                                                <label class="fl-check">
                                                    <input value="{{ $amenity?->translation?->id }}"
                                                           x-model="filters.amenity_ids" type="checkbox">
                                                    <span class="fl-box"><i class="fa-solid fa-check"></i></span>
                                                    <span class="fl-check-text">{{ $amenity?->translation?->name }}</span>
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                    @if (count($amenities) > 4)
                                        <div class="fl-more" @click="show = !show">
                                            <i :class="show ? 'fa-solid fa-minus' : 'fa-solid fa-plus'"></i>
                                            <span x-text="show ? 'Свернуть' : 'Показать ещё'"></span>
                                        </div>
                                    @endif
                                </div>

                                <div class="fl-divider"></div>

                                {{-- Ratings --}}
                                <div class="fl-title">Рейтинг</div>
                                <ul class="fl-list">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <li>
                                            <label class="fl-check">
                                                <input x-model="filters.ratings" type="checkbox" value="{{ $i }}">
                                                <span class="fl-box"><i class="fa-solid fa-check"></i></span>
                                                <div class="fl-stars">
                                                    @for ($j = 1; $j <= 5; $j++)
                                                        <i class="fa-solid fa-star @if($j <= $i) active @endif"></i>
                                                    @endfor
                                                </div>
                                            </label>
                                        </li>
                                    @endfor
                                </ul>

                                <div class="fl-divider"></div>

                                {{-- Languages --}}
                                <div x-data="{ show: false }">
                                    <div class="fl-title">Язык</div>
                                    <ul class="fl-list">
                                        @foreach ($languages as $key => $language)
                                            <li x-show="show || {{ $key }} < 4" x-transition>
                                                <label class="fl-check">
                                                    <input value="{{ $language?->name }}"
                                                           x-model="filters.languages" type="checkbox">
                                                    <span class="fl-box"><i class="fa-solid fa-check"></i></span>
                                                    <span class="fl-check-text">{{ $language?->value }}</span>
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                    @if (count($languages) > 4)
                                        <div class="fl-more" @click="show = !show">
                                            <i :class="show ? 'fa-solid fa-minus' : 'fa-solid fa-plus'"></i>
                                            <span x-text="show ? 'Свернуть' : 'Показать ещё'"></span>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </aside>

                        {{-- CONTENT --}}
                        <div class="svc-content-area">
                            <div class="svc-toolbar" data-aos="fade-up">
                                <div class="svc-count">
                                    <span class="custom_pagination_count"></span>
                                </div>
                                <div class="svc-toolbar-actions">
                                    <select id="sortSelect" class="svc-sort">
                                        <option value="default">По умолчанию</option>
                                        <option value="latest">Новые</option>
                                        <option value="oldest">Старые</option>
                                        <option value="price_low">Цена ↑</option>
                                        <option value="price_high">Цена ↓</option>
                                        <option value="trending">Популярные</option>
                                        <option value="popular">Рейтинг</option>
                                        <option value="location_asc">Локация A-Z</option>
                                        <option value="location_desc">Локация Z-A</option>
                                    </select>
                                    <div class="svc-view-toggle d-none d-sm-flex">
                                        <button @click="isListView = false" class="svc-view-btn"
                                                :class="{ 'active': !isListView }">
                                            <i class="fa-solid fa-grid-2"></i>
                                        </button>
                                        <button @click="isListView = true" class="svc-view-btn"
                                                :class="{ 'active': isListView }">
                                            <i class="fa-solid fa-list"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div id="filter_data" class="svc-results"></div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main>
@endsection

@push('js_section')
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ru.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

    <script>
        AOS.init({ duration: 600, easing: 'ease-out-cubic', once: true, offset: 40 });

        (function($) {
            "use strict";
            $(document).ready(function() {
                $(".js-flatpickr").flatpickr({
                    dateFormat: "Y-m-d",
                    minDate: "today",
                    locale: "ru",
                    disableMobile: true,
                    allowInput: false
                });
            });
        })(jQuery);
    </script>

    <script>
        function servicesPageData() {
            return {
                page: 1,
                isListView: false,
                style: 'style1',

                defaultBookingForm: {
                    destination_id: '',
                    destination: '',
                    selected_service_id: '',
                    checkIn: '',
                    checkOut: '',
                    rooms: 0,
                    adults: 0,
                    children: 0
                },
                bookingForm: {
                    destination_id: `{{ request('destination_id', isset($selectedDestination) ? $selectedDestination->id : '') }}`,
                    destination: `{{ request('destination', isset($selectedDestination) ? $selectedDestination->name : '') }}`,
                    selected_service_id: `{{ request('selected_service_id', '') }}`,
                    checkIn: `{{ request('checkIn', '') }}`,
                    checkOut: `{{ request('checkOut', '') }}`,
                    rooms: parseInt(`{{ request('rooms', '0') }}`) || 0,
                    adults: parseInt(`{{ request('adults', '0') }}`) || 0,
                    children: parseInt(`{{ request('children', '0') }}`) || 0,
                },

                get guestSummary() {
                    let parts = [];
                    if (this.bookingForm.rooms) parts.push(this.bookingForm.rooms + ' Ком.');
                    if (this.bookingForm.adults) parts.push(this.bookingForm.adults + ' Взр.');
                    if (this.bookingForm.children) parts.push(this.bookingForm.children + ' Дет.');
                    return parts.length > 0 ? parts.join(', ') : '{{ __("translate.+ Add Guests") }}';
                },

                increment(field) { this.bookingForm[field]++; },
                decrement(field) { if (this.bookingForm[field] > 0) this.bookingForm[field]--; },

                selectDestination(id, name) {
                    this.bookingForm.destination_id = id;
                    this.bookingForm.selected_service_id = '';
                    this.bookingForm.destination = name;
                },

                selectService(id, name) {
                    this.bookingForm.selected_service_id = id;
                    this.bookingForm.destination_id = '';
                    this.bookingForm.destination = name;
                },

                filters: {
                    search: `{{ request('search', '') }}`,
                    service_type_ids: {!! json_encode(request('service_type_ids', [])) !!},
                    max_price: `{{ request('max_price', '') }}`,
                    min_price: `{{ request('min_price', '') }}`,
                    amenity_ids: {!! json_encode(request('amenity_ids', [])) !!},
                    languages: {!! json_encode(request('languages', [])) !!},
                    sort_by: `{{ request('sort_by', '') }}`,
                    ratings: {!! json_encode(request('ratings', [])) !!},
                },
                defaultFilters: {
                    search: '',
                    service_type_ids: [],
                    max_price: '',
                    min_price: '',
                    amenity_ids: [],
                    languages: [],
                    sort_by: '',
                    ratings: []
                },

                get isFilterChanged() {
                    return JSON.stringify(this.filters) !== JSON.stringify(this.defaultFilters);
                },
                get isBookingFilterChanged() {
                    return JSON.stringify(this.bookingForm) !== JSON.stringify(this.defaultBookingForm);
                },

                updateURL(value) {
                    let base = window.location.protocol + "//" + window.location.host + window.location.pathname;
                    let params = [];
                    for (const [key, val] of Object.entries(value)) {
                        if (Array.isArray(val)) {
                            val.forEach(item => {
                                if (item !== '' && item !== null) {
                                    params.push(`${encodeURIComponent(key)}%5B%5D=${encodeURIComponent(item)}`);
                                }
                            });
                        } else if (val !== null && val !== undefined && val !== '' && val !== 0) {
                            params.push(`${encodeURIComponent(key)}=${encodeURIComponent(val)}`);
                        }
                    }
                    let qs = params.length > 0 ? '?' + params.join('&') : '';
                    window.history.pushState({ path: base + qs }, '', base + qs);
                },

                resetFilters() {
                    this.filters = JSON.parse(JSON.stringify(this.defaultFilters));
                    this.bookingForm = JSON.parse(JSON.stringify({
                        destination_id: '', destination: '', selected_service_id: '',
                        checkIn: '', checkOut: '',
                        rooms: 0, adults: 0, children: 0
                    }));
                    document.querySelectorAll('.js-flatpickr').forEach(el => {
                        if (el._flatpickr) el._flatpickr.clear();
                    });
                    this.$nextTick(() => {
                        let sortEl = document.getElementById('sortSelect');
                        if (sortEl) sortEl.value = 'default';
                    });
                },

                init() {
                    this.$watch('filters', () => {
                        this.page = 1;
                        this.fetchServices();
                        this.updateURL({ ...this.bookingForm, ...this.filters });
                    });
                    this.$watch('isListView', () => {
                        this.fetchServices();
                    });
                    this.initializeAll();
                },

                fetchServices() {
                    const that = this;
                    this.loadingOverlay("show");

                    $.ajax({
                        url: `{{ route('front.tourbooking.services.load.ajax') }}`,
                        method: 'GET',
                        data: {
                            ...this.filters,
                            ...this.bookingForm,
                            page: this.page,
                            isListView: this.isListView,
                            style: this.style
                        },
                        success: function(response) {
                            if (response.view) {
                                $('#filter_data').html(response.view);
                            }
                            if (response.customPaginationCount) {
                                $('.custom_pagination_count').html(response.customPaginationCount);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Fetch error:', error);
                            $('#filter_data').html('<div class="text-center py-5"><p style="color: var(--text-muted);">Ошибка загрузки данных</p></div>');
                        },
                        complete: function() {
                            that.loadingOverlay("hide");
                        }
                    });
                },

                searchServices() {
                    this.page = 1;
                    this.fetchServices();
                    this.updateURL({ ...this.bookingForm, ...this.filters });
                },

                initializeAll() {
                    $(document).on('click', '.pagination a', (event) => {
                        event.preventDefault();
                        const page = $(event.target).attr('href').split('page=')[1];
                        this.page = page;
                        this.fetchServices();
                        $("html, body").animate({ scrollTop: 0 }, 500);
                    });

                    this.$nextTick(() => {
                        let sortEl = document.getElementById('sortSelect');
                        if (sortEl) {
                            sortEl.value = this.filters.sort_by || 'default';
                            sortEl.addEventListener('change', (e) => {
                                this.filters.sort_by = e.target.value;
                            });
                        }
                    });

                    this.loadingOverlay("show");
                    this.fetchServices();
                },

                loadingOverlay(action = 'show', target = false) {
                    const opts = { size: 50, maxSize: 50, minSize: 50 };
                    if (target && typeof target === 'string') {
                        $(target).LoadingOverlay(action, opts);
                    } else {
                        $.LoadingOverlay(action, opts);
                    }
                }
            };
        }
    </script>
@endpush