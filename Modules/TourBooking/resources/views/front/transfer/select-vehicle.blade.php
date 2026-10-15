@extends('layout_inner_page')

@section('title')
    <title>Выбор транспорта - {{ $route->from_location }} → {{ $route->to_location }}</title>
@endsection

@push('style_section')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0f172a;
            --secondary: #1e293b;
            --accent: #008080;
            --accent-hover: #006666;
            --accent-light: #e0f2f1;
            --gold: #eab308;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --border: #e2e8f0;
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            color: var(--text-primary);
            background: var(--bg-secondary);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }

        /* ====== ШАПКА ====== */
        .transfer-header-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            padding: 25px 0 30px;
            position: relative;
        }

        .transfer-header-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }

        .transfer-header-section .container {
            position: relative;
            z-index: 1;
        }

        .header-title {
            color: #fff;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .header-subtitle {
            color: rgba(255,255,255,0.85);
            font-size: 16px;
            margin-bottom: 0;
        }

        .header-info-alert {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 8px;
            color: #fff;
            padding: 12px 20px;
            margin-top: 20px;
            font-size: 14px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 20px;
        }

        .header-route-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.15);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 14px;
            color: #fff;
        }

        .header-route-badge i {
            color: var(--accent);
        }

        .hero-change-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.1);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.2);
            padding: 10px 20px;
            border-radius: var(--radius-sm);
            text-decoration: none;
            font-size: 14px;
            transition: var(--transition);
            margin-top: 20px;
        }

        .hero-change-btn:hover {
            background: rgba(255,255,255,0.2);
            color: #fff;
        }

        /* ====== MAIN CONTENT ====== */
        .transfer-content {
            padding: 40px 0 60px;
            background: #f5f7fa;
        }

        .section-title {
            font-size: 1.75rem;
            margin-bottom: 30px;
            color: var(--text-primary);
        }

        /* ====== VEHICLE CARD ====== */
        .vehicle-card {
            background: #fff;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            margin-bottom: 24px;
            transition: var(--transition);
            border: 1px solid #eee;
        }

        .vehicle-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--accent);
        }

        .vehicle-card-inner {
            display: flex;
            flex-wrap: wrap;
        }

        .vehicle-image-section {
            width: 320px;
            flex-shrink: 0;
        }

        .vehicle-main-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .vehicle-image-placeholder {
            width: 100%;
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        }

        .vehicle-image-placeholder i {
            font-size: 64px;
            color: #cbd5e1;
        }

        .vehicle-gallery {
            display: flex;
            gap: 6px;
            padding: 8px;
            background: #f8fafc;
        }

        .vehicle-gallery-thumb {
            width: 60px;
            height: 45px;
            object-fit: cover;
            border-radius: 4px;
            cursor: pointer;
            opacity: 0.7;
            transition: var(--transition);
        }

        .vehicle-gallery-thumb:hover {
            opacity: 1;
        }

        .vehicle-details-section {
            flex: 1;
            padding: 24px;
            min-width: 300px;
        }

        .vehicle-name {
            font-size: 1.5rem;
            margin-bottom: 12px;
            color: var(--text-primary);
        }

        .vehicle-specs {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 16px;
        }

        .spec-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--accent-light);
            color: var(--accent);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .vehicle-features {
            margin-bottom: 16px;
        }

        .vehicle-features-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }

        .features-list {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .features-list li {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .features-list li i {
            color: var(--success);
            font-size: 12px;
        }

        .vehicle-description {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .vehicle-price-section {
            width: 220px;
            flex-shrink: 0;
            padding: 24px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            border-left: 1px solid var(--border);
        }

        .price-breakdown {
            margin-bottom: 16px;
        }

        .price-item {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        .price-total-label {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 4px;
        }

        .price-total-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--accent);
            font-family: 'Playfair Display', serif;
        }

        .btn-select-vehicle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 14px 24px;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
        }

        .btn-select-vehicle:hover {
            background: var(--accent-hover);
            color: #fff;
            transform: translateY(-2px);
        }

        /* ====== EMPTY STATE ====== */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: #fff;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
        }

        .empty-state-icon {
            width: 100px;
            height: 100px;
            background: #fef3c7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }

        .empty-state-icon i {
            font-size: 48px;
            color: #f59e0b;
        }

        .empty-state h4 {
            font-size: 1.5rem;
            margin-bottom: 12px;
        }

        .empty-state p {
            color: var(--text-muted);
            margin-bottom: 24px;
        }

        /* ====== RESPONSIVE ====== */
        @media (max-width: 991px) {
            .vehicle-image-section {
                width: 100%;
            }
            
            .vehicle-main-image {
                height: 250px;
            }
            
            .vehicle-price-section {
                width: 100%;
                border-left: none;
                border-top: 1px solid var(--border);
            }
            
            .header-title {
                font-size: 24px;
            }
        }

        @media (max-width: 575px) {
            .header-info-alert {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            
            .vehicle-details-section {
                padding: 20px;
            }
        }
    </style>
@endpush

@section('front-content')
    {{-- Breadcrumb --}}
    <div class="tg-breadcrumb-spacing-3 include-bg p-relative fix"
        data-background="{{ asset($general_setting->secondary_breadcrumb_image ?? $general_setting->breadcrumb_image) }}">
        <div class="tg-hero-top-shadow"></div>
    </div>
    <div class="tg-breadcrumb-list-2-wrap">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="tg-breadcrumb-list-2">
                        <ul>
                            <li><a href="{{ url('/') }}">Главная</a></li>
                            <li><i class="fa-sharp fa-solid fa-angle-right"></i></li>
                            <li><a href="{{ route('front.tourbooking.home') }}">Трансфер</a></li>
                            <li><i class="fa-sharp fa-solid fa-angle-right"></i></li>
                            <li><span>Выбор транспорта</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Шапка --}}
    <section class="transfer-header-section">
        <div class="container">
            <h1 class="header-title">Выберите транспорт</h1>
            <p class="header-subtitle">
                <i class="fas fa-map-marker-alt" style="color: var(--accent);"></i>
                {{ $route->from_location }}
                <i class="fas fa-long-arrow-alt-right" style="margin: 0 8px; opacity: 0.5;"></i>
                {{ $route->to_location }}
            </p>
            
            <div class="header-info-alert">
                <div class="header-route-badge">
                    <i class="fas fa-calendar-alt"></i>
                    {{ \Carbon\Carbon::parse($transferDate)->format('d.m.Y') }}
                </div>
                
                <div class="header-route-badge">
                    <i class="fas fa-users"></i>
                    {{ $passengers }} пассажир(ов)
                </div>
                
                @if($route->duration_minutes)
                    <div class="header-route-badge">
                        <i class="fas fa-clock"></i>
                        ~{{ $route->duration_minutes }} мин
                    </div>
                @endif
            </div>
            
            <a href="{{ route('front.tourbooking.home') }}" class="hero-change-btn">
                <i class="fas fa-search"></i>
                Изменить поиск
            </a>
        </div>
    </section>

    <!-- Main Content -->
    <section class="transfer-content">
        <div class="container">
            @if($vehicles->count() > 0)
                <h2 class="section-title">Доступный транспорт ({{ $vehicles->count() }})</h2>
                
                @foreach($vehicles as $vehicle)
                    <div class="vehicle-card">
                        <div class="vehicle-card-inner">
                            <!-- Image Section -->
                            <div class="vehicle-image-section">
                                @if($vehicle->image)
                                    <img src="{{ asset($vehicle->image) }}" alt="{{ $vehicle->name }}" class="vehicle-main-image">
                                @else
                                    <div class="vehicle-image-placeholder">
                                        <i class="fas fa-car"></i>
                                    </div>
                                @endif
                                
                                @if($vehicle->gallery && count($vehicle->gallery) > 0)
                                    <div class="vehicle-gallery">
                                        @foreach(array_slice($vehicle->gallery, 0, 4) as $galleryImg)
                                            <img src="{{ asset($galleryImg) }}" alt="{{ $vehicle->name }}" class="vehicle-gallery-thumb">
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Details Section -->
                            <div class="vehicle-details-section">
                                <h3 class="vehicle-name">{{ $vehicle->name }}</h3>
                                
                                <div class="vehicle-specs">
                                    <span class="spec-badge">
                                        <i class="fas fa-users"></i>
                                        {{ $vehicle->passenger_capacity }} пассажиров
                                    </span>
                                    <span class="spec-badge">
                                        <i class="fas fa-suitcase"></i>
                                        {{ $vehicle->luggage_capacity }} багаж
                                    </span>
                                </div>
                                
                                @if($vehicle->features && count($vehicle->features) > 0)
                                    <div class="vehicle-features">
                                        <p class="vehicle-features-title">Особенности:</p>
                                        <ul class="features-list">
                                            @foreach($vehicle->features as $feature)
                                                <li><i class="fas fa-check"></i> {{ $feature }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                
                                @if($vehicle->description)
                                    <p class="vehicle-description">{{ Str::limit($vehicle->description, 150) }}</p>
                                @endif
                            </div>
                            
                            <!-- Price Section -->
                            <div class="vehicle-price-section">
                                <div class="price-breakdown">
                                    @if($route->base_price > 0)
                                        <p class="price-item">Маршрут: {{ currency($route->base_price) }}</p>
                                    @endif
                                    <p class="price-item">Транспорт: {{ currency($vehicle->base_price) }}</p>
                                </div>
                                
                                <p class="price-total-label">Итого:</p>
                                <p class="price-total-value">{{ currency((float)$route->base_price + (float)$vehicle->base_price) }}</p>
                                
                                <a href="{{ route('front.tourbooking.transfer.checkout', [
                                    'route_id' => $route->id,
                                    'vehicle_id' => $vehicle->id,
                                    'transfer_date' => $transferDate,
                                    'passengers' => $passengers
                                ]) }}" class="btn-select-vehicle">
                                    <i class="fas fa-check"></i>
                                    Выбрать
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h4>Транспорт не найден</h4>
                    <p>К сожалению, для выбранного количества пассажиров ({{ $passengers }}) нет доступного транспорта.</p>
                    <a href="{{ route('front.tourbooking.home') }}" class="btn-select-vehicle" style="width: auto; display: inline-flex;">
                        <i class="fas fa-arrow-left"></i>
                        Изменить поиск
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection