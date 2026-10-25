@extends('layout_inner_page')

@section('title')
    <title>{{ $service?->translation?->title }} | Услуги</title>
    <meta name="title" content="{{ $service?->translation?->title }}">
    <meta name="description" content="{{ Str::limit(strip_tags($service?->translation?->short_description), 160) }}">
@endsection

@push('style_section')
    {{-- Подключение Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    {{-- Библиотека анимаций AOS --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    {{-- GLightbox для галереи изображений --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
    
    {{-- Flatpickr для выбора даты --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">
    
    <style>
        .gs-translate-btn{display:inline-block;margin-top:10px;padding:5px 14px;font-size:12px;font-weight:600;color:#1a9e87;background:#f0faf8;border:1px solid #d0f0eb;border-radius:50px;cursor:pointer;transition:all .2s}
        .gs-translate-btn:hover{background:#1a9e87;color:#fff}
        .gs-translated-text{margin-top:8px;font-size:14px;color:#374151;font-style:italic;display:none;padding:8px 12px;background:#f9fffe;border-left:3px solid #1a9e87;border-radius:0 8px 8px 0}
        /* ═══════════════════════════════════════════════════════════════
           КОРНЕВЫЕ CSS ПЕРЕМЕННЫЕ
        ═══════════════════════════════════════════════════════════════ */
        :root {
            --primary: #0f172a;
            --secondary: #1e293b;
            --accent: #f43f5e;
            --accent-hover: #e11d48;
            --accent-light: #fda4af;
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
            --bg-tertiary: #f1f5f9;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            color: var(--text-primary);
            background: var(--bg-secondary);
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            line-height: 1.3;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* ═══════════════════════════════════════════════════════════════
           СЕКЦИЯ ГЕРОЯ
        ═══════════════════════════════════════════════════════════════ */
        .hero {
            position: relative;
            height: 75vh;
            min-height: 600px;
            display: flex;
            align-items: flex-end;
            overflow: hidden;
        }

        .hero__media {
            position: absolute;
            inset: 0;
        }

        .hero__media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            animation: slowZoom 30s ease-in-out infinite alternate;
        }

        @keyframes slowZoom {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }

        .hero__overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to top,
                rgba(15, 23, 42, 0.95) 0%,
                rgba(15, 23, 42, 0.6) 40%,
                rgba(15, 23, 42, 0.3) 100%
            );
        }

        .hero__content {
            position: relative;
            z-index: 10;
            width: 100%;
            padding: 0 0 60px;
        }

        .hero__breadcrumb {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .hero__breadcrumb a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: var(--transition);
        }

        .hero__breadcrumb a:hover {
            color: #fff;
        }

        .hero__breadcrumb i {
            color: var(--accent);
            font-size: 10px;
        }

        .hero__breadcrumb span {
            color: rgba(255,255,255,0.5);
        }

        .hero__title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            color: #fff;
            margin-bottom: 20px;
            max-width: 800px;
        }

        .hero__meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 24px;
        }

        .hero__location {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,0.9);
            font-size: 15px;
        }

        .hero__location i {
            color: var(--accent);
        }

        .hero__rating {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .hero__stars {
            display: flex;
            gap: 3px;
        }

        .hero__stars i {
            font-size: 14px;
            color: rgba(255,255,255,0.3);
        }

        .hero__stars i.active {
            color: var(--gold);
        }

        .hero__reviews {
            color: rgba(255,255,255,0.7);
            font-size: 14px;
        }

        .hero__actions {
            margin-top: 28px;
            display: flex;
            gap: 12px;
        }

        .btn-glass {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 100px;
            color: #fff;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
        }

        .btn-glass:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
            color: #fff;
        }

        .btn-glass.active {
            background: var(--accent);
            border-color: var(--accent);
        }

        /* ═══════════════════════════════════════════════════════════════
           ГАЛЕРЕЯ
        ═══════════════════════════════════════════════════════════════ */
        .gallery {
            padding: 40px 0;
            background: var(--bg-primary);
        }

        .gallery__grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: repeat(2, 200px);
            gap: 12px;
        }

        .gallery__item {
            position: relative;
            border-radius: var(--radius-lg);
            overflow: hidden;
            cursor: pointer;
        }

        .gallery__item:first-child {
            grid-column: span 2;
            grid-row: span 2;
        }

        .gallery__item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .gallery__item:hover img {
            transform: scale(1.08);
        }

        .gallery__overlay {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: var(--transition);
        }

        .gallery__item:hover .gallery__overlay {
            opacity: 1;
        }

        .gallery__icon {
            width: 50px;
            height: 50px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: scale(0.8);
            transition: var(--transition);
        }

        .gallery__item:hover .gallery__icon {
            transform: scale(1);
        }

        .gallery__icon i {
            font-size: 18px;
            color: var(--primary);
        }

        .gallery__play {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70px;
            height: 70px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-xl);
            transition: var(--transition);
            z-index: 5;
        }

        .gallery__play::after {
            content: '';
            position: absolute;
            inset: -10px;
            border: 2px solid rgba(255,255,255,0.5);
            border-radius: 50%;
            animation: ripple 2s infinite;
        }

        @keyframes ripple {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(1.4); opacity: 0; }
        }

        .gallery__play i {
            font-size: 22px;
            color: var(--accent);
            margin-left: 3px;
        }

        .gallery__play:hover {
            transform: translate(-50%, -50%) scale(1.1);
            background: var(--accent);
        }

        .gallery__play:hover i {
            color: #fff;
        }

        @media (max-width: 991px) {
            .gallery__grid {
                grid-template-columns: repeat(2, 1fr);
                grid-template-rows: 280px repeat(2, 180px);
            }
            .gallery__item:first-child {
                grid-column: span 2;
                grid-row: span 1;
            }
        }

        @media (max-width: 575px) {
            .gallery__grid {
                grid-template-columns: 1fr;
                grid-template-rows: repeat(4, 200px);
            }
            .gallery__item:first-child {
                grid-column: span 1;
            }
        }

        /* ═══════════════════════════════════════════════════════════════
           ПОЛОСА ХАРАКТЕРИСТИК
        ═══════════════════════════════════════════════════════════════ */
        .features-strip {
            background: var(--bg-primary);
            padding: 0 0 40px;
        }

        .features-strip__inner {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
        }

        .feature-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 20px;
            background: var(--bg-secondary);
            border-radius: var(--radius-lg);
            transition: var(--transition);
            border: 1px solid transparent;
        }

        .feature-card:hover {
            background: var(--bg-primary);
            border-color: var(--border);
            box-shadow: var(--shadow-md);
            transform: translateY(-4px);
        }

        .feature-card__icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .feature-card__icon i {
            font-size: 20px;
            color: #fff;
        }

        .feature-card__content {
            min-width: 0;
        }

        .feature-card__label {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .feature-card__value {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .feature-card--price {
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
            border: none;
        }

        .feature-card--price:hover {
            background: linear-gradient(135deg, var(--accent-hover) 0%, var(--accent) 100%);
            transform: translateY(-4px);
        }

        .feature-card--price .feature-card__icon {
            background: rgba(255,255,255,0.2);
        }

        .feature-card--price .feature-card__label,
        .feature-card--price .feature-card__value {
            color: #fff;
        }

        .feature-card--price .feature-card__value {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
        }

        /* ═══════════════════════════════════════════════════════════════
           ОСНОВНОЙ КОНТЕНТ
        ═══════════════════════════════════════════════════════════════ */
        .main-section {
            padding: 40px 0 80px;
        }

        .bento-layout {
            display: grid;
            /* minmax(0, 1fr) — sol sütunun kontent min-ölçüsündən aşağı daralmasına
               icazə verir; əks halda geniş/sınmayan kontent 400px sidebar-ı sağ
               kənardan kənara itələyir (horizontal overflow). */
            grid-template-columns: minmax(0, 1fr) 400px;
            gap: 30px;
        }

        @media (max-width: 1199px) {
            .bento-layout {
                grid-template-columns: minmax(0, 1fr) 360px;
                gap: 24px;
            }
        }

        @media (max-width: 991px) {
            .bento-layout {
                grid-template-columns: 1fr;
            }
        }

        /* Сетка карточек - ОДНА КОЛОНКА для полной ширины */
        .bento-grid {
            display: flex;
            flex-direction: column;
            gap: 20px;
            min-width: 0; /* qrid trekinin daralmasına imkan ver — overflow-un qarşısını alır */
        }

        /* ═══════════════════════════════════════════════════════════════
           КАРТОЧКИ
        ═══════════════════════════════════════════════════════════════ */
        .card {
            background: var(--bg-primary);
            border-radius: var(--radius-xl);
            padding: 32px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
        }

        .card__icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
        }

        .card__icon i {
            font-size: 24px;
            color: #fff;
        }

        .card__title {
            font-size: 26px;
            color: var(--text-primary);
            margin-bottom: 16px;
        }

        .card__subtitle {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 16px;
        }

        .card__text {
            font-size: 16px;
            color: var(--text-secondary);
            line-height: 1.8;
            overflow-wrap: break-word; /* uzun/sınmayan mətn və URL-lər kartdan daşmasın */
            word-break: break-word;
        }

        /* Rich-HTML kontentdəki geniş elementlər (şəkil, cədvəl, iframe) konteynerə sığsın */
        .card__text img,
        .card__text iframe,
        .card__text table,
        .card__text video {
            max-width: 100%;
            height: auto;
        }

        .card__text p {
            margin-bottom: 14px;
        }

        .card__text p:last-child {
            margin-bottom: 0;
        }

        /* ═══════════════════════════════════════════════════════════════
           ВКЛЮЧЕНО/НЕ ВКЛЮЧЕНО
        ═══════════════════════════════════════════════════════════════ */
        .includes-wrapper {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-top: 20px;
        }

        @media (max-width: 767px) {
            .includes-wrapper {
                grid-template-columns: 1fr;
            }
        }

        .includes-box {
            padding: 24px;
            border-radius: var(--radius-lg);
        }

        .includes-box--yes {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 1px solid #a7f3d0;
        }

        .includes-box--no {
            background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%);
            border: 1px solid #fca5a5;
        }

        .includes-box__title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 18px;
        }

        .includes-box--yes .includes-box__title {
            color: #065f46;
        }

        .includes-box--no .includes-box__title {
            color: #991b1b;
        }

        .includes-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .includes-list li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 0;
            font-size: 15px;
            border-bottom: 1px dashed rgba(0,0,0,0.1);
        }

        .includes-list li:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .includes-box--yes .includes-list li {
            color: #047857;
        }

        .includes-box--no .includes-list li {
            color: #b91c1c;
        }

        .includes-list li i {
            margin-top: 4px;
            flex-shrink: 0;
        }

        /* ═══════════════════════════════════════════════════════════════
           ПРОГРАММА ТУРА
        ═══════════════════════════════════════════════════════════════ */
        .itinerary-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 20px;
        }

        .itinerary-item {
            background: var(--bg-secondary);
            border-radius: var(--radius-md);
            overflow: hidden;
            transition: var(--transition);
        }

        .itinerary-item:hover {
            background: var(--bg-tertiary);
        }

        .itinerary-item.active {
            box-shadow: var(--shadow-md);
        }

        .itinerary-header {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 18px 22px;
            cursor: pointer;
        }

        .itinerary-day {
            padding: 8px 16px;
            background: var(--accent);
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            border-radius: 100px;
            white-space: nowrap;
        }

        .itinerary-title {
            flex: 1;
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .itinerary-toggle {
            width: 34px;
            height: 34px;
            background: var(--bg-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .itinerary-toggle i {
            font-size: 12px;
            color: var(--text-secondary);
            transition: var(--transition);
        }

        .itinerary-item.active .itinerary-toggle {
            background: var(--accent);
        }

        .itinerary-item.active .itinerary-toggle i {
            color: #fff;
            transform: rotate(180deg);
        }

        .itinerary-body {
            display: none;
            padding: 0 22px 22px;
        }

        .itinerary-item.active .itinerary-body {
            display: block;
            animation: fadeSlideDown 0.3s ease;
        }

        @keyframes fadeSlideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .itinerary-content {
            display: grid;
            grid-template-columns: 180px 1fr;
            gap: 20px;
        }

        @media (max-width: 575px) {
            .itinerary-content {
                grid-template-columns: 1fr;
            }
        }

        .itinerary-image {
            border-radius: var(--radius-md);
            overflow: hidden;
            aspect-ratio: 4/3;
        }

        .itinerary-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .itinerary-details {
            font-size: 15px;
            color: var(--text-secondary);
            line-height: 1.7;
        }

        .itinerary-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px dashed var(--border);
        }

        .itinerary-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--text-primary);
        }

        .itinerary-meta-item i {
            color: var(--accent);
        }

        .itinerary-meta-item .badge {
            background: var(--success);
            color: #fff;
            padding: 4px 12px;
            border-radius: 100px;
            font-size: 12px;
            font-weight: 600;
        }

        /* ═══════════════════════════════════════════════════════════════
           КАРТА
        ═══════════════════════════════════════════════════════════════ */
        .map-frame {
            border-radius: var(--radius-lg);
            overflow: hidden;
            height: 300px;
            margin-top: 20px;
        }

        .map-frame iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* ═══════════════════════════════════════════════════════════════
           ОТЗЫВЫ
        ═══════════════════════════════════════════════════════════════ */
        .reviews-header {
            display: grid;
            grid-template-columns: 160px 1fr;
            gap: 30px;
            padding: 24px;
            background: var(--bg-secondary);
            border-radius: var(--radius-lg);
            margin: 20px 0;
        }

        @media (max-width: 575px) {
            .reviews-header {
                grid-template-columns: 1fr;
                text-align: center;
            }
        }

        .reviews-score {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .reviews-score__number {
            font-family: 'Playfair Display', serif;
            font-size: 52px;
            font-weight: 700;
            color: var(--accent);
            line-height: 1;
        }

        .reviews-score__stars {
            display: flex;
            gap: 4px;
            margin: 10px 0;
        }

        .reviews-score__stars i {
            font-size: 16px;
            color: var(--gold);
        }

        .reviews-score__count {
            font-size: 14px;
            color: var(--text-muted);
        }

        .reviews-bars {
            display: flex;
            flex-direction: column;
            gap: 12px;
            justify-content: center;
        }

        .review-bar {
            display: grid;
            grid-template-columns: 90px 1fr 45px;
            gap: 14px;
            align-items: center;
        }

        .review-bar__label {
            font-size: 14px;
            color: var(--text-secondary);
        }

        .review-bar__track {
            height: 8px;
            background: var(--border);
            border-radius: 100px;
            overflow: hidden;
        }

        .review-bar__fill {
            height: 100%;
            background: linear-gradient(90deg, var(--accent) 0%, var(--accent-light) 100%);
            border-radius: 100px;
            transition: width 1s ease;
        }

        .review-bar__value {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            text-align: right;
        }

        .reviews-list {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 500px;
            overflow-y: auto;
        }

        .review-item {
            display: flex;
            gap: 18px;
            padding: 22px 0;
            border-bottom: 1px solid var(--border);
        }

        .review-item:last-child {
            border-bottom: none;
        }

        .review-avatar {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            border: 3px solid var(--accent);
        }

        .review-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .review-content {
            flex: 1;
            min-width: 0;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 10px;
        }

        .review-author {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .review-date {
            font-size: 13px;
            color: var(--text-muted);
        }

        .review-rating {
            display: flex;
            gap: 3px;
        }

        .review-rating i {
            font-size: 12px;
            color: var(--border);
        }

        .review-rating i.active {
            color: var(--gold);
        }

        .review-text {
            font-size: 15px;
            color: var(--text-secondary);
            line-height: 1.7;
            margin: 0;
        }

        .review-form {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px dashed var(--border);
        }

        .review-form__title {
            font-size: 20px;
            margin-bottom: 24px;
        }

        .rating-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 18px;
            margin-bottom: 24px;
        }

        .rating-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .rating-item__label {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .rating-item__stars {
            display: flex;
            gap: 5px;
        }

        .rating-item__stars i {
            font-size: 20px;
            color: var(--border);
            cursor: pointer;
            transition: var(--transition);
        }

        .rating-item__stars i:hover,
        .rating-item__stars i.active {
            color: var(--gold);
            transform: scale(1.15);
        }

        .review-form__textarea {
            width: 100%;
            min-height: 140px;
            padding: 18px;
            border: 2px solid var(--border);
            border-radius: var(--radius-md);
            font-family: inherit;
            font-size: 15px;
            resize: vertical;
            transition: var(--transition);
        }

        .review-form__textarea:focus {
            outline: none;
            border-color: var(--accent);
        }

        .review-form__submit {
            margin-top: 18px;
        }

        .no-reviews {
            text-align: center;
            padding: 50px 20px;
        }

        .no-reviews__icon {
            width: 70px;
            height: 70px;
            background: var(--bg-secondary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
        }

        .no-reviews__icon i {
            font-size: 28px;
            color: var(--text-muted);
        }

        .no-reviews__text {
            font-size: 16px;
            color: var(--text-muted);
        }

        /* ═══════════════════════════════════════════════════════════════
           САЙДБАР БРОНИРОВАНИЯ
        ═══════════════════════════════════════════════════════════════ */
        .booking-sidebar {
            position: sticky;
            top: 100px;
        }

        .booking-card {
            background: var(--bg-primary);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .booking-card__header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            padding: 32px;
            text-align: center;
        }

        .booking-card__title {
            font-size: 26px;
            color: #fff;
            margin-bottom: 8px;
        }

        .booking-card__subtitle {
            font-size: 14px;
            color: rgba(255,255,255,0.7);
        }

        .booking-card__price {
            margin-top: 24px;
        }

        .booking-card__price-label {
            font-size: 13px;
            color: rgba(255,255,255,0.6);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .booking-card__price-value {
            font-family: 'Playfair Display', serif;
            font-size: 40px;
            font-weight: 700;
            color: #fff;
        }

        .booking-card__price-unit {
            font-size: 15px;
            color: rgba(255,255,255,0.7);
        }

        .booking-card__body {
            padding: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .form-input {
            width: 100%;
            padding: 16px 18px;
            border: 2px solid var(--border);
            border-radius: var(--radius-md);
            font-family: inherit;
            font-size: 15px;
            transition: var(--transition);
            background: var(--bg-primary);
            cursor: pointer;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--accent);
        }

        .form-input:read-only {
            cursor: pointer;
            background: var(--bg-primary);
        }

        /* Datepicker wrapper styles for flatpickr wrap mode */
        .datepicker-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .datepicker-wrapper .form-input {
            padding-right: 50px;
        }

        .datepicker-wrapper .datepicker-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            cursor: pointer;
            z-index: 10;
            padding: 5px;
            background: none;
            border: none;
        }

        .datepicker-wrapper .datepicker-toggle:hover {
            color: var(--accent);
        }

        /* Flatpickr стили */
        .flatpickr-calendar {
            box-shadow: var(--shadow-xl) !important;
            border-radius: var(--radius-md) !important;
            border: 1px solid var(--border) !important;
        }

        .flatpickr-day.selected {
            background: var(--accent) !important;
            border-color: var(--accent) !important;
        }

        .flatpickr-day:hover {
            background: var(--bg-secondary) !important;
        }

        .time-pills {
            display: flex;
            gap: 12px;
        }

        .time-pill {
            flex: 1;
            position: relative;
        }

        .time-pill input {
            position: absolute;
            opacity: 0;
        }

        .time-pill__label {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 14px;
            background: var(--bg-secondary);
            border: 2px solid transparent;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: var(--transition);
        }

        .time-pill input:checked + .time-pill__label {
            background: rgba(244, 63, 94, 0.1);
            border-color: var(--accent);
        }

        .time-pill__time {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .time-pill__period {
            font-size: 12px;
            color: var(--text-muted);
        }

        .tickets-section {
            padding: 20px 0;
            border-top: 1px dashed var(--border);
            border-bottom: 1px dashed var(--border);
            margin: 20px 0;
        }

        .tickets-section__title {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            text-transform: uppercase;
            margin-bottom: 16px;
        }

        .ticket-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
        }

        .ticket-info {
            display: flex;
            flex-direction: column;
        }

        .ticket-type {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .ticket-age {
            font-size: 12px;
            color: var(--text-muted);
        }

        .ticket-price {
            font-size: 15px;
            font-weight: 600;
            color: var(--accent);
            margin-top: 3px;
        }

        .ticket-counter {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .ticket-btn {
            width: 36px;
            height: 36px;
            border: 2px solid var(--border);
            background: var(--bg-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            font-size: 14px;
            color: var(--text-secondary);
        }

        .ticket-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .ticket-btn:active {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        .ticket-value {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            min-width: 28px;
            text-align: center;
        }

        .extras-section {
            margin: 20px 0;
        }

        .extras-section__title {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .extra-item {
            display: flex;
            align-items: center;
            padding: 14px 16px;
            background: var(--bg-secondary);
            border-radius: var(--radius-md);
            margin-bottom: 10px;
            cursor: pointer;
            transition: var(--transition);
        }

        .extra-item:hover {
            background: var(--bg-tertiary);
        }

        .extra-item input {
            display: none;
        }

        .extra-checkbox {
            width: 22px;
            height: 22px;
            border: 2px solid var(--border);
            border-radius: 6px;
            margin-right: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            flex-shrink: 0;
        }

        .extra-item input:checked + .extra-checkbox {
            background: var(--accent);
            border-color: var(--accent);
        }

        .extra-checkbox i {
            font-size: 11px;
            color: #fff;
            opacity: 0;
            transform: scale(0.5);
            transition: var(--transition);
        }

        .extra-item input:checked + .extra-checkbox i {
            opacity: 1;
            transform: scale(1);
        }

        .extra-name {
            flex: 1;
            font-size: 14px;
            color: var(--text-primary);
        }

        .extra-price {
            font-size: 14px;
            font-weight: 600;
            color: var(--accent);
        }

        .booking-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            border-top: 2px dashed var(--border);
            margin-top: 20px;
        }

        .booking-total__label {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .booking-total__value {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 700;
            color: var(--accent);
        }

        .btn-book {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 18px 28px;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .btn-book::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }

        .btn-book:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(244, 63, 94, 0.4);
            color: #fff;
        }

        .btn-book:hover::before {
            left: 100%;
        }

        /* ═══════════════════════════════════════════════════════════════
           MEGA SELECT ROOM BUTTON - Premium Animasyonlu Dizayn
        ═══════════════════════════════════════════════════════════════ */
        .booking-card--minimal {
            background: transparent;
            box-shadow: none;
            padding: 0;
        }

        .booking-card--minimal .booking-card__body {
            padding: 0;
        }

        .select-room-mega-btn {
            position: relative;
            display: block;
            width: 100%;
            padding: 40px 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f43f5e 100%);
            background-size: 200% 200%;
            border-radius: 24px;
            text-decoration: none;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: gradientShift 4s ease infinite;
            box-shadow: 
                0 15px 50px rgba(102, 126, 234, 0.5),
                0 0 0 2px rgba(255, 255, 255, 0.15) inset;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .select-room-mega-btn:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 
                0 20px 60px rgba(102, 126, 234, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.2) inset,
                0 0 80px rgba(244, 63, 94, 0.3);
        }

        .select-room-mega-btn:active {
            transform: translateY(-4px) scale(1.01);
        }

        /* Glow Effect */
        .mega-btn__glow {
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 60%);
            opacity: 0;
            transition: opacity 0.4s ease;
            pointer-events: none;
        }

        .select-room-mega-btn:hover .mega-btn__glow {
            opacity: 1;
            animation: glowPulse 2s ease infinite;
        }

        @keyframes glowPulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        /* Shimmer Effect */
        .mega-btn__shimmer {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent 0%,
                rgba(255, 255, 255, 0.1) 20%,
                rgba(255, 255, 255, 0.4) 50%,
                rgba(255, 255, 255, 0.1) 80%,
                transparent 100%
            );
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 200%; }
        }

        /* Content Layout */
        .mega-btn__content {
            position: relative;
            z-index: 10;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* Icon Wrapper */
        .mega-btn__icon-wrapper {
            position: relative;
            width: 90px;
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .mega-btn__icon-bg {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            backdrop-filter: blur(10px);
            animation: iconPulse 2s ease-in-out infinite;
        }

        @keyframes iconPulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .mega-btn__icon {
            position: relative;
            z-index: 1;
            font-size: 38px;
            color: #fff;
            filter: drop-shadow(0 4px 12px rgba(0,0,0,0.3));
            animation: iconBounce 2s ease-in-out infinite;
        }

        @keyframes iconBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        /* Text Content */
        .mega-btn__text {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .mega-btn__title {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.5px;
            text-shadow: 0 3px 15px rgba(0,0,0,0.3);
        }

        .mega-btn__subtitle {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            letter-spacing: 0.3px;
            margin-top: 4px;
        }

        /* Arrow */
        .mega-btn__arrow {
            width: 65px;
            height: 65px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 50%;
            backdrop-filter: blur(10px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            flex-shrink: 0;
        }

        .mega-btn__arrow i {
            font-size: 26px;
            color: #fff;
            transition: transform 0.4s ease;
        }

        .select-room-mega-btn:hover .mega-btn__arrow {
            background: rgba(255, 255, 255, 0.35);
            transform: scale(1.1);
        }

        .select-room-mega-btn:hover .mega-btn__arrow i {
            transform: translateX(5px);
            animation: arrowBounce 0.6s ease infinite;
        }

        @keyframes arrowBounce {
            0%, 100% { transform: translateX(5px); }
            50% { transform: translateX(10px); }
        }

        /* Floating Particles */
        .mega-btn__particles {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .mega-btn__particles span {
            position: absolute;
            width: 6px;
            height: 6px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            animation: particleFloat 4s ease-in-out infinite;
        }

        .mega-btn__particles span:nth-child(1) {
            left: 10%;
            top: 20%;
            animation-delay: 0s;
            animation-duration: 3s;
        }

        .mega-btn__particles span:nth-child(2) {
            left: 30%;
            top: 70%;
            animation-delay: 0.5s;
            animation-duration: 3.5s;
        }

        .mega-btn__particles span:nth-child(3) {
            left: 60%;
            top: 30%;
            animation-delay: 1s;
            animation-duration: 4s;
        }

        .mega-btn__particles span:nth-child(4) {
            left: 80%;
            top: 60%;
            animation-delay: 1.5s;
            animation-duration: 3.2s;
        }

        .mega-btn__particles span:nth-child(5) {
            left: 90%;
            top: 15%;
            animation-delay: 2s;
            animation-duration: 4.5s;
        }

        @keyframes particleFloat {
            0%, 100% {
                transform: translateY(0) scale(1);
                opacity: 0.6;
            }
            50% {
                transform: translateY(-20px) scale(1.2);
                opacity: 1;
            }
        }

        /* Mobile Responsive */
        @media (max-width: 575px) {
            .select-room-mega-btn {
                padding: 30px 20px;
            }

            .mega-btn__icon-wrapper {
                width: 70px;
                height: 70px;
            }

            .mega-btn__icon {
                font-size: 28px;
            }

            .mega-btn__title {
                font-size: 24px;
            }

            .mega-btn__subtitle {
                font-size: 14px;
            }

            .mega-btn__arrow {
                width: 50px;
                height: 50px;
            }

            .mega-btn__arrow i {
                font-size: 20px;
            }
        }

        .availability-box {
            margin-top: 16px;
            padding: 16px;
            border-radius: var(--radius-md);
            font-size: 14px;
        }

        .availability-box--success {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        .availability-box--warning {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border: 1px solid #fcd34d;
            color: #92400e;
        }

        .availability-box--danger {
            background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%);
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        .availability-box p {
            margin: 0 0 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .availability-box p:last-child {
            margin-bottom: 0;
        }

        /* ═══════════════════════════════════════════════════════════════
           КНОПКИ
        ═══════════════════════════════════════════════════════════════ */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 28px;
            font-size: 15px;
            font-weight: 600;
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
        }

        .btn--primary {
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
            color: #fff;
        }

        .btn--primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(244, 63, 94, 0.35);
            color: #fff;
        }

        /* ═══════════════════════════════════════════════════════════════
           ПОПУЛЯРНЫЕ УСЛУГИ
        ═══════════════════════════════════════════════════════════════ */
        .popular-section {
            padding: 70px 0 90px;
            background: var(--bg-primary);
        }

        .popular-section__header {
            text-align: center;
            margin-bottom: 50px;
        }

        .popular-section__title {
            font-size: 40px;
            margin-bottom: 12px;
        }

        .popular-section__subtitle {
            font-size: 16px;
            color: var(--text-secondary);
        }

        /* ═══════════════════════════════════════════════════════════════
           АДАПТИВНОСТЬ
        ═══════════════════════════════════════════════════════════════ */
        @media (max-width: 991px) {
            .hero {
                height: 60vh;
                min-height: 500px;
            }
            
            .booking-sidebar {
                position: relative;
                top: 0;
            }
        }

        @media (max-width: 767px) {
            .hero {
                height: 55vh;
                min-height: 450px;
            }
            
            .card {
                padding: 24px;
            }
        }
    </style>
@endpush

@section('front-content')
<main>
    @php
        $thumbnails = $service->media->where('is_thumbnail', 1)->sortBy('display_order')->values();
        $nonThumbnails = $service->media->where('is_thumbnail', 0)->sortBy('display_order')->values();
        $allMedia = $thumbnails->merge($nonThumbnails);
        $hasRooms = $service->rooms()->where('status', true)->exists();
    @endphp

    {{-- СЕКЦИЯ ГЕРОЯ --}}
    <section class="hero">
        <div class="hero__media">
            @if(isset($thumbnails[0]))
                <img src="{{ asset($thumbnails[0]->file_path) }}" alt="{{ $service?->translation?->title }}">
            @else
                <img src="{{ asset('frontend/assets/img/shape/placeholder.png') }}" alt="default">
            @endif
        </div>
        <div class="hero__overlay"></div>
        
        <div class="hero__content">
            <div class="container">
                <nav class="hero__breadcrumb" data-aos="fade-up" data-aos-delay="100">
                    <a href="{{ url('home') }}">Главная</a>
                    <i class="fa-solid fa-chevron-right"></i>
                    <a href="{{ route('front.tourbooking.services') }}">Услуги</a>
                    <i class="fa-solid fa-chevron-right"></i>
                    <span>{{ $service?->translation?->title }}</span>
                </nav>

                <h1 class="hero__title" data-aos="fade-up" data-aos-delay="200">
                    {{ $service?->translation?->title }}
                </h1>

                <div class="hero__meta" data-aos="fade-up" data-aos-delay="300">
                    @if($service?->location)
                        <div class="hero__location">
                            <i class="fa-regular fa-location-dot"></i>
                            {{ $service?->location }}
                        </div>
                    @endif

                    <div class="hero__rating">
                        <div class="hero__stars">
                            @foreach(range(1, 5) as $star)
                                <i class="fa-solid fa-star @if($avgRating >= $star) active @endif"></i>
                            @endforeach
                        </div>
                        <span class="hero__reviews">
                            ({{ $reviews->count() }} {{ $reviews->count() > 1 ? 'отзывов' : 'отзыв' }})
                        </span>
                    </div>
                </div>

                <div class="hero__actions" data-aos="fade-up" data-aos-delay="400">
                    <button class="btn-glass @if($service?->my_wishlist_exists == 1) active @endif" 
                            onclick="addToWishlist({{ $service->id }}, this, 'service')">
                        <i class="fa-solid fa-heart"></i>
                        <span class="wishlist_change_text">
                            @if($service?->my_wishlist_exists == 1) Сохранено @else Сохранить @endif
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- ГАЛЕРЕЯ --}}
    <section class="gallery">
        <div class="container">
            <div class="gallery__grid" data-aos="fade-up">
                @if(isset($thumbnails[0]))
                    <a href="{{ asset($thumbnails[0]->file_path) }}" class="gallery__item glightbox" data-gallery="gallery">
                        <img src="{{ asset($thumbnails[0]->file_path) }}" alt="{{ $thumbnails[0]->caption ?: $service?->translation?->title }}">
                        <div class="gallery__overlay">
                            <div class="gallery__icon"><i class="fa-solid fa-expand"></i></div>
                        </div>
                    </a>
                @endif

                @if(isset($nonThumbnails[0]) && $service->video_url)
                    <div class="gallery__item">
                        <img src="{{ asset($nonThumbnails[0]->file_path) }}" alt="{{ $nonThumbnails[0]->caption ?: $service?->translation?->title }}">
                        <a href="{{ $service->video_url }}" class="gallery__play glightbox" data-gallery="video">
                            <i class="fa-solid fa-play"></i>
                        </a>
                    </div>
                @elseif(isset($nonThumbnails[0]))
                    <a href="{{ asset($nonThumbnails[0]->file_path) }}" class="gallery__item glightbox" data-gallery="gallery">
                        <img src="{{ asset($nonThumbnails[0]->file_path) }}" alt="{{ $nonThumbnails[0]->caption ?: $service?->translation?->title }}">
                        <div class="gallery__overlay">
                            <div class="gallery__icon"><i class="fa-solid fa-expand"></i></div>
                        </div>
                    </a>
                @endif

                @for($i = 1; $i <= 2; $i++)
                    @if(isset($nonThumbnails[$i]))
                        <a href="{{ asset($nonThumbnails[$i]->file_path) }}" class="gallery__item glightbox" data-gallery="gallery">
                            <img src="{{ asset($nonThumbnails[$i]->file_path) }}" alt="{{ $nonThumbnails[$i]->caption ?: $service?->translation?->title }}">
                            <div class="gallery__overlay">
                                <div class="gallery__icon"><i class="fa-solid fa-expand"></i></div>
                            </div>
                        </a>
                    @endif
                @endfor

                @foreach($allMedia->slice(4) as $media)
                    <a href="{{ asset($media->file_path) }}" class="gallery__item glightbox d-none" data-gallery="gallery"></a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ПОЛОСА ХАРАКТЕРИСТИК --}}
    <section class="features-strip">
        <div class="container">
            <div class="features-strip__inner" data-aos="fade-up">
                @if($service?->duration)
                    <div class="feature-card">
                        <div class="feature-card__icon"><i class="fa-regular fa-clock"></i></div>
                        <div class="feature-card__content">
                            <div class="feature-card__label">Длительность</div>
                            <div class="feature-card__value">{{ $service?->duration }}</div>
                        </div>
                    </div>
                @endif

                @if($service?->serviceType?->name)
                    <div class="feature-card">
                        <div class="feature-card__icon"><i class="fa-regular fa-cube"></i></div>
                        <div class="feature-card__content">
                            <div class="feature-card__label">Тип</div>
                            <div class="feature-card__value">{{ $service?->serviceType?->name }}</div>
                        </div>
                    </div>
                @endif

                @if($service?->group_size)
                    <div class="feature-card">
                        <div class="feature-card__icon"><i class="fa-regular fa-users"></i></div>
                        <div class="feature-card__content">
                            <div class="feature-card__label">Размер группы</div>
                            <div class="feature-card__value">{{ $service?->group_size }}</div>
                        </div>
                    </div>
                @endif

                @if($service?->languages && is_array($service?->languages) && count($service?->languages) > 0)
                    <div class="feature-card">
                        <div class="feature-card__icon"><i class="fa-regular fa-globe"></i></div>
                        <div class="feature-card__content">
                            <div class="feature-card__label">Языки</div>
                            <div class="feature-card__value">{{ implode(', ', $service?->languages) }}</div>
                        </div>
                    </div>
                @endif

                <div class="feature-card feature-card--price">
                    <div class="feature-card__icon"><i class="fa-regular fa-tag"></i></div>
                    <div class="feature-card__content">
                        <div class="feature-card__label">От</div>
                        <div class="feature-card__value">
                            @if($service?->is_per_person)
                                {{ currency($service?->price_per_person) }}
                            @else
                                {{ currency($service->discount_price ?? $service->full_price) }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ОСНОВНОЙ КОНТЕНТ --}}
    <section class="main-section">
        <div class="container">
            <div class="bento-layout">
                {{-- Левая часть - Контент --}}
                <div class="bento-grid">
                    
                    {{-- О туре - ПОЛНАЯ ШИРИНА --}}
                    <div class="card" data-aos="fade-up">
                        <div class="card__icon"><i class="fa-regular fa-compass"></i></div>
                        <h3 class="card__title">О туре</h3>
                        <div class="card__text">
                            {!! $service?->translation?->short_description !!}
                        </div>
                    </div>

                    {{-- Описание --}}
                    @if($service?->translation?->description)
                        <div class="card" data-aos="fade-up">
                            <div class="card__icon"><i class="fa-regular fa-file-lines"></i></div>
                            <h3 class="card__title">Описание</h3>
                            <div class="card__text">
                                {!! $service?->translation?->description !!}
                            </div>
                        </div>
                    @endif

                    {{-- Включено / Не включено --}}
                    @if($service?->included || $service?->excluded)
                        <div class="card" data-aos="fade-up">
                            <div class="card__icon"><i class="fa-regular fa-list-check"></i></div>
                            <h3 class="card__title">Что включено</h3>
                            
                            <div class="includes-wrapper">
                                @if($service?->included)
                                    <div class="includes-box includes-box--yes">
                                        <div class="includes-box__title">
                                            <i class="fa-solid fa-circle-check"></i> Включено
                                        </div>
                                        <ul class="includes-list">
                                            @foreach(json_decode($service?->included) as $item)
                                                <li><i class="fa-solid fa-check"></i> {{ $item }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if($service?->excluded)
                                    <div class="includes-box includes-box--no">
                                        <div class="includes-box__title">
                                            <i class="fa-solid fa-circle-xmark"></i> Не включено
                                        </div>
                                        <ul class="includes-list">
                                            @foreach(json_decode($service?->excluded) as $item)
                                                <li><i class="fa-solid fa-xmark"></i> {{ $item }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Программа тура --}}
                    @if($service?->itineraries && $service?->itineraries->count() > 0)
                        <div class="card" data-aos="fade-up">
                            <div class="card__icon"><i class="fa-regular fa-map"></i></div>
                            <h3 class="card__title">Программа тура</h3>
                            @if($service?->tour_plan_sub_title)
                                <p class="card__subtitle">{{ $service?->tour_plan_sub_title }}</p>
                            @endif

                            <div class="itinerary-list">
                                @foreach($service?->itineraries as $index => $itinerary)
                                    <div class="itinerary-item @if($loop->first) active @endif">
                                        <div class="itinerary-header" onclick="toggleItinerary(this)">
                                            <span class="itinerary-day">День {{ $itinerary?->day_number }}</span>
                                            <h4 class="itinerary-title">{{ $itinerary?->title }}</h4>
                                            <div class="itinerary-toggle"><i class="fa-solid fa-chevron-down"></i></div>
                                        </div>
                                        <div class="itinerary-body">
                                            <div class="itinerary-content">
                                                @if($itinerary?->image)
                                                    <div class="itinerary-image">
                                                        <img src="{{ asset($itinerary->image) }}" alt="{{ $itinerary->title }}">
                                                    </div>
                                                @endif
                                                <div class="itinerary-details">
                                                    @if($itinerary?->description)
                                                        {!! $itinerary?->description !!}
                                                    @endif

                                                    @if($itinerary?->location || $itinerary?->duration || $itinerary?->meal_included)
                                                        <div class="itinerary-meta">
                                                            @if($itinerary?->location)
                                                                <div class="itinerary-meta-item">
                                                                    <i class="fa-solid fa-location-dot"></i>
                                                                    {{ $itinerary?->location }}
                                                                </div>
                                                            @endif
                                                            @if($itinerary?->duration)
                                                                <div class="itinerary-meta-item">
                                                                    <i class="fa-solid fa-clock"></i>
                                                                    {{ $itinerary?->duration }}
                                                                </div>
                                                            @endif
                                                            @if($itinerary?->meal_included)
                                                                <div class="itinerary-meta-item">
                                                                    <i class="fa-solid fa-utensils"></i>
                                                                    <span class="badge">{{ $itinerary?->meal_included }}</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Карта --}}
                    @if($service?->google_map_url)
                        <div class="card" data-aos="fade-up">
                            <div class="card__icon"><i class="fa-regular fa-location-dot"></i></div>
                            <h3 class="card__title">Расположение</h3>
                            @if($service?->google_map_sub_title)
                                <p class="card__subtitle">{{ $service?->google_map_sub_title }}</p>
                            @endif
                            <div class="map-frame">
                                {!! $service?->google_map_url !!}
                            </div>
                        </div>
                    @endif

                    {{-- Отзывы --}}
                    <div class="card" data-aos="fade-up" x-data="reviewForm()">
                        <div class="card__icon"><i class="fa-regular fa-star"></i></div>
                        <h3 class="card__title">Отзывы</h3>

                        @if($reviews->count() > 0)
                            <div class="reviews-header">
                                <div class="reviews-score">
                                    <div class="reviews-score__number">{{ number_format($avgRating, 1) }}</div>
                                    <div class="reviews-score__stars">
                                        @foreach(range(1, 5) as $star)
                                            <i class="fa-solid fa-star"></i>
                                        @endforeach
                                    </div>
                                    <div class="reviews-score__count">{{ $reviews->count() }} отзывов</div>
                                </div>
                                <div class="reviews-bars">
                                    @foreach($averageRatings as $item)
                                        <div class="review-bar">
                                            <span class="review-bar__label">{{ $item['category'] }}</span>
                                            <div class="review-bar__track">
                                                <div class="review-bar__fill" style="width: {{ $item['percent'] }}%"></div>
                                            </div>
                                            <span class="review-bar__value">{{ $item['average'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <ul class="reviews-list">
                                @foreach($paginatedReviews as $review)
                                    <li class="review-item">
                                        <div class="review-avatar">
                                            <img src="{{ asset($review->user->image ?? 'frontend/assets/img/shape/placeholder.png') }}" alt="">
                                        </div>
                                        <div class="review-content">
                                            <div class="review-header">
                                                <div>
                                                    <h5 class="review-author">{{ $review->user->name }}@if(!empty($review->user->country)), {{ $review->user->country }}@endif</h5>
                                                    <span class="review-date">{{ \Carbon\Carbon::parse($review->created_at)->format('d.m.Y') }}</span>
                                                </div>
                                                <div class="review-rating">
                                                    @foreach(range(1, 5) as $star)
                                                        <i class="fa-solid fa-star @if($review->rating >= $star) active @endif"></i>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <p class="review-text">{{ $review->review }}</p>
                                            <span class="gs-translate-btn" onclick="gsTranslate(this)">🌐 Перевести</span>
                                            <div class="gs-translated-text"></div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="no-reviews">
                                <div class="no-reviews__icon"><i class="fa-regular fa-comments"></i></div>
                                <p class="no-reviews__text">Отзывов пока нет</p>
                            </div>
                        @endif

                        <div class="review-form">
                            <h4 class="review-form__title">Написать отзыв</h4>
                            
                            <div class="rating-grid">
                                <template x-for="(category, index) in categories" :key="category.name">
                                    <div class="rating-item">
                                        <label class="rating-item__label" x-text="category.label"></label>
                                        <div class="rating-item__stars">
                                            <template x-for="star in 5" :key="star">
                                                <i class="fa-solid fa-star"
                                                   :class="star <= (hoverIndex === index ? hoverRating : category.rating) ? 'active' : ''"
                                                   @click="setRating(index, star)"
                                                   @mouseover="hoverRating = star; hoverIndex = index"
                                                   @mouseleave="hoverRating = 0; hoverIndex = null"></i>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <textarea class="review-form__textarea" x-model="message" placeholder="Поделитесь своими впечатлениями..."></textarea>

                            <div class="review-form__submit">
                                <button type="button" class="btn btn--primary" @click="submitForm">
                                    <i class="fa-solid fa-paper-plane"></i>
                                    Отправить
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- САЙДБАР БРОНИРОВАНИЯ - YENİ DİZAYN --}}
                <div class="booking-sidebar">
                    <div class="booking-card booking-card--minimal">
                        <div class="booking-card__body">
                            {{-- Select Room Mega Button --}}
                            @if($hasRooms)
                                <a href="{{ route('front.tourbooking.rooms.selection', $service->slug) }}" class="select-room-mega-btn">
                                    <div class="mega-btn__glow"></div>
                                    <div class="mega-btn__shimmer"></div>
                                    <div class="mega-btn__content">
                                        <div class="mega-btn__icon-wrapper">
                                            <div class="mega-btn__icon-bg"></div>
                                            <i class="fa-solid fa-bed mega-btn__icon"></i>
                                        </div>
                                        <div class="mega-btn__text">
                                            <span class="mega-btn__title">Выбрать номер</span>
                                            <span class="mega-btn__subtitle">Выберите лучший номер для вас</span>
                                        </div>
                                        <div class="mega-btn__arrow">
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </div>
                                    </div>
                                    <div class="mega-btn__particles">
                                        <span></span><span></span><span></span><span></span><span></span>
                                    </div>
                                </a>
                            @else
                                <a href="{{ route('front.tourbooking.guest-booking-form', $service->slug) }}" class="select-room-mega-btn">
                                    <div class="mega-btn__glow"></div>
                                    <div class="mega-btn__shimmer"></div>
                                    <div class="mega-btn__content">
                                        <div class="mega-btn__icon-wrapper">
                                            <div class="mega-btn__icon-bg"></div>
                                            <i class="fa-solid fa-calendar-check mega-btn__icon"></i>
                                        </div>
                                        <div class="mega-btn__text">
                                            <span class="mega-btn__title">Забронировать</span>
                                            <span class="mega-btn__subtitle">Гарантируйте своё место</span>
                                        </div>
                                        <div class="mega-btn__arrow">
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </div>
                                    </div>
                                    <div class="mega-btn__particles">
                                        <span></span><span></span><span></span><span></span><span></span>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ПОПУЛЯРНЫЕ УСЛУГИ --}}
    <section class="popular-section">
        <div class="container">
            <div class="popular-section__header" data-aos="fade-up">
                <h2 class="popular-section__title">Вам также понравится</h2>
                <p class="popular-section__subtitle">Откройте для себя похожие туры</p>
            </div>
            @include('tourbooking::front.services.popular-services')
        </div>
    </section>

    @if($hasRooms)
        @include('tourbooking::front.services._room_selection_modal')
    @endif
</main>
@endsection

@push('js_section')
    <script>
function gsTranslate(btn){
  if(!btn)return;
  var textEl=btn.previousElementSibling;
  var outEl=btn.nextElementSibling;
  if(!textEl||!outEl)return;
  if(outEl.style.display==='block'){outEl.style.display='none';btn.textContent='🌐 Перевести';return;}
  var text=textEl.textContent.trim();
  if(!text)return;
  btn.textContent='⏳ Перевод...';
  fetch('https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=ru&dt=t&q='+encodeURIComponent(text))
  .then(function(r){return r.json();})
  .then(function(d){
    var result=d[0].map(function(x){return x[0];}).join('');
    outEl.textContent=result;
    outEl.style.display='block';
    btn.textContent='✕ Скрыть перевод';
  })
  .catch(function(){btn.textContent='🌐 Перевести';alert('Ошибка перевода');});
}
    </script>
    {{-- AOS --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    {{-- GLightbox --}}
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    {{-- Flatpickr --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ru.js"></script>
    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        // Инициализация AOS
        AOS.init({ 
            duration: 700, 
            easing: 'ease-out-cubic', 
            once: true, 
            offset: 50 
        });

        // Инициализация лайтбокса
        const lightbox = GLightbox({ 
            touchNavigation: true, 
            loop: true, 
            autoplayVideos: true 
        });

        // Переключение аккордеона
        function toggleItinerary(el) {
            const item = el.closest('.itinerary-item');
            document.querySelectorAll('.itinerary-item.active').forEach(i => { 
                if (i !== item) i.classList.remove('active'); 
            });
            item.classList.toggle('active');
        }

        // Инициализация календаря
        (function($) {
            $(document).ready(function() {
                // Данные о доступности
                const availabilities = @json($service?->availabilities ?? []);
                const availableDates = availabilities.map(i => i.date);
                const availabilityMap = {};
                
                availabilities.forEach(i => { 
                    availabilityMap[i.date] = { ...i }; 
                });

                // Flatpickr wrap mode ilə inisializasiya
                const datepickerWrapper = document.getElementById('datepicker-wrapper');
                
                if (datepickerWrapper && typeof flatpickr !== 'undefined') {
                    flatpickr(datepickerWrapper, {
                        dateFormat: "Y-m-d",
                        minDate: "today",
                        locale: "ru",
                        disableMobile: true,
                        allowInput: false,
                        clickOpens: true,
                        wrap: true,
                        enable: availableDates.length > 0 ? availableDates : undefined,
                        onChange: function(selectedDates, dateStr) {
                            updateAvailabilityInfo(dateStr);
                        }
                    });
                }

                // Обновление информации о доступности
                function updateAvailabilityInfo(dateStr) {
                    const $box = $('#availability-info');
                    const $btn = $('.btn-book');
                    const $input = $('#selected-availability-id');

                    if (dateStr && availabilityMap[dateStr]) {
                        const info = availabilityMap[dateStr];
                        $input.val(info.id || '');
                        let html = '';
                        let cls = 'availability-box--success';

                        if (info.available_spots !== null) {
                            if (info.available_spots <= 0) {
                                cls = 'availability-box--danger';
                                html += '<p><i class="fa-solid fa-circle-xmark"></i> Нет мест</p>';
                                $btn.prop('disabled', true);
                            } else if (info.available_spots <= 5) {
                                cls = 'availability-box--warning';
                                html += '<p><i class="fa-solid fa-circle-exclamation"></i> Осталось ' + info.available_spots + ' мест</p>';
                                $btn.prop('disabled', false);
                            } else {
                                html += '<p><i class="fa-solid fa-circle-check"></i> ' + info.available_spots + ' мест доступно</p>';
                                $btn.prop('disabled', false);
                            }
                        }
                        
                        if (info.start_time && info.end_time) {
                            html += '<p><i class="fa-regular fa-clock"></i> ' + info.start_time.substring(0,5) + ' - ' + info.end_time.substring(0,5) + '</p>';
                        }
                        
                        if (info.special_price) {
                            html += '<p><i class="fa-solid fa-tag"></i> Спеццена: {{ default_currency()["currency_icon"] }}' + info.special_price + '</p>';
                        }
                        
                        if (info.notes) {
                            html += '<p><i class="fa-regular fa-note-sticky"></i> ' + info.notes + '</p>';
                        }

                        $box.removeClass('availability-box--success availability-box--warning availability-box--danger')
                            .addClass('availability-box ' + cls)
                            .html(html)
                            .slideDown(200);
                    } else {
                        $box.slideUp(200).html('');
                        $input.val('');
                        $btn.prop('disabled', false);
                    }
                }

                // Проверяем начальную дату
                const initialInput = datepickerWrapper ? datepickerWrapper.querySelector('[data-input]') : null;
                const initialDate = initialInput ? initialInput.value : null;
                if (initialDate) {
                    updateAvailabilityInfo(initialDate);
                }
            });
        })(jQuery);

        // Форма отзыва
        function reviewForm() {
            return {
                categories: [
                    { name: 'Location', label: 'Расположение', rating: 0 },
                    { name: 'Price', label: 'Цена', rating: 0 },
                    { name: 'Amenities', label: 'Удобства', rating: 0 },
                    { name: 'Rooms', label: 'Номера', rating: 0 },
                    { name: 'Services', label: 'Сервис', rating: 0 }
                ],
                hoverRating: 0,
                hoverIndex: null,
                message: '',
                
                setRating(i, r) { 
                    this.categories[i].rating = r; 
                },
                
                submitForm() {
                    const data = { 
                        service_id: '{{ $service->id }}', 
                        message: this.message, 
                        ratings: this.categories.map(c => ({ 
                            category: c.name, 
                            rating: c.rating 
                        })) 
                    };
                    
                    if (!data.message.trim()) { 
                        toastr.error('Пожалуйста, напишите отзыв'); 
                        return; 
                    }
                    if (data.ratings.some(c => c.rating === 0)) { 
                        toastr.error('Пожалуйста, оцените все категории'); 
                        return; 
                    }
                    
                    fetch('{{ route("front.tourbooking.reviews.store") }}', {
                        method: 'POST',
                        headers: { 
                            'X-Requested-With': 'XMLHttpRequest', 
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 
                            'Content-Type': 'application/json' 
                        },
                        body: JSON.stringify(data)
                    })
                    .then(r => r.json())
                    .then(d => { 
                        if (d.success) { 
                            toastr.success(d.message); 
                            this.message = ''; 
                            this.categories.forEach(c => c.rating = 0); 
                            setTimeout(() => location.reload(), 1500); 
                        } else { 
                            toastr.error(d.message); 
                        } 
                    })
                    .catch(() => toastr.error('Произошла ошибка'));
                }
            };
        }

        // Форма бронирования
        function bookingForm() {
            return {
                tickets: { 
                    person: 1, 
                    children: 0 
                },
                pricePerPerson: {{ $service->price_per_person ?? 0 }},
                pricePerChild: {{ $service->child_price ?? 0 }},
                extras: { 
                    @foreach($service->extraCharges as $key => $extra) 
                        charge_{{ $key }}: false, 
                    @endforeach 
                },
                extrasPrice: { 
                    @foreach($service->extraCharges as $key => $extra) 
                        charge_{{ $key }}: {{ $extra->price ?? 0 }}, 
                    @endforeach 
                },
                
                get totalCost() {
                    let t = this.tickets.person * this.pricePerPerson + this.tickets.children * this.pricePerChild;
                    for (let k in this.extras) {
                        if (this.extras[k]) t += this.extrasPrice[k];
                    }
                    return t.toFixed(2);
                }
            };
        }
    </script>
@endpush