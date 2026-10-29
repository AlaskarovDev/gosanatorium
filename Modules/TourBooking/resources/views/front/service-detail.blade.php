@extends('layout_inner_page')

@section('title')
    <title>{{ $service?->translation?->title }} | Услуги</title>
    <meta name="title" content="{{ $service?->translation?->title }}">
    <meta name="description" content="{{ Str::limit(strip_tags($service?->translation?->short_description), 160) }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
@endsection

@push('style_section')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">

    <style>
        /* ═══════════════════════════════════════════════════════════════
           CSS VARIABLES
        ═══════════════════════════════════════════════════════════════ */
        :root {
            --primary: #0f172a;
            --secondary: #1e293b;
            --accent: #3fc8c3;
            --accent-hover: #2ba8a3;
            --accent-light: #a7e8e5;
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
            /* Mobile app-specific */
            --safe-top: env(safe-area-inset-top, 0px);
            --safe-bottom: env(safe-area-inset-bottom, 0px);
            --safe-left: env(safe-area-inset-left, 0px);
            --safe-right: env(safe-area-inset-right, 0px);
            --mobile-nav-height: 64px;
            --mobile-bottom-bar-height: 72px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html {
            -webkit-text-size-adjust: 100%;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            color: var(--text-primary);
            background: var(--bg-secondary);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        html, body {
            overflow-x: clip;
            max-width: 100%;
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
           MOBILE APP - BOTTOM TAB BAR (Native App Style)
        ═══════════════════════════════════════════════════════════════ */
        .mobile-app-tabbar {
            display: none;
        }

        .mobile-bottom-promo {
            display: none !important;
        }

        .mobile-bottom-bar {
            display: none !important;
        }
        /* Chat widgets - tabbar üstündə göstər */
            /* Tawk.to, Jivo, Crisp, LiveChat və s. */
           
        @media (max-width: 991px) {
            .mobile-app-tabbar {
                display: flex;
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 2147483000;
                background: rgba(255, 255, 255, 0.92);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border-top: 1px solid rgba(226, 232, 240, 0.6);
                padding: 0;
                padding-bottom: var(--safe-bottom);
                align-items: flex-end;
                justify-content: space-around;
                box-shadow: 0 -8px 40px rgba(0, 0, 0, 0.08), 0 -1px 3px rgba(0, 0, 0, 0.04);
                height: auto;
                min-height: 68px;
                pointer-events: auto;
            }

            .mobile-app-tabbar .mat-tab,
            .mobile-app-tabbar .mat-tab--center,
            .mobile-app-tabbar .mat-tab--center-inner,
            .mobile-app-tabbar .mat-tab * {
                pointer-events: auto !important;
                position: relative;
                z-index: 2;
            }

            /* Cookie consent mobilde tabbar üstünü örtməsin */
            .cookie_consent_modal {
                bottom: 88px !important;
                left: 12px !important;
                right: 12px !important;
                width: auto !important;
                max-width: calc(100vw - 24px) !important;
            }

            .mat-tab {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 4px;
                padding: 10px 4px 12px;
                flex: 1;
                text-decoration: none;
                color: var(--text-muted);
                font-size: 10px;
                font-weight: 500;
                letter-spacing: 0.3px;
                transition: color 0.35s cubic-bezier(0.4, 0, 0.2, 1), transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
                position: relative;
                -webkit-tap-highlight-color: transparent;
                cursor: pointer;
                border: none;
                background: none;
                outline: none;
                font-family: 'DM Sans', sans-serif;
            }

            .mat-tab i {
                font-size: 22px;
                transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
                line-height: 1;
            }

            .mat-tab span {
                line-height: 1;
                white-space: nowrap;
                transition: all 0.3s ease;
            }

            .mat-tab.active {
                color: var(--accent);
            }

            .mat-tab.active i {
                transform: scale(1.15) translateY(-1px);
            }

            /* Aktiv tab - pill indicator (üstdə) */
            .mat-tab.active::after {
                content: '';
                position: absolute;
                top: 0;
                width: 24px;
                height: 3px;
                border-radius: 0 0 4px 4px;
                background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
                animation: tabSlideIn 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
            }

            @keyframes tabSlideIn {
                from { width: 0; opacity: 0; }
                to { width: 24px; opacity: 1; }
            }

            /* Tap ripple effekti */
            .mat-tab::before {
                content: '';
                position: absolute;
                inset: 6px;
                border-radius: 14px;
                background: currentColor;
                opacity: 0;
                transition: opacity 0.15s ease, transform 0.3s ease;
                transform: scale(0.8);
            }

            .mat-tab:active::before {
                opacity: 0.06;
                transform: scale(1);
            }

            .mat-tab:active {
                transform: scale(0.93);
            }

            /* FAB - Center button */
            .mat-tab--center {
                flex: 1.3;
                padding: 0;
                position: relative;
                color: var(--text-muted);
            }

            .mat-tab--center-inner {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 4px;
                text-decoration: none;
                color: inherit;
                width: 100%;
                padding: 10px 4px 12px;
            }

            .mat-fab {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                background: linear-gradient(145deg, var(--accent) 0%, var(--accent-hover) 60%, #1f8a86 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                font-size: 24px;
                box-shadow: 
                    0 6px 24px rgba(63, 200, 195, 0.45),
                    0 2px 8px rgba(63, 200, 195, 0.25),
                    inset 0 1px 1px rgba(255, 255, 255, 0.2);
                margin-top: -30px;
                position: relative;
                transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
                border: 3.5px solid var(--bg-primary);
            }

            .mat-fab::after {
                content: '';
                position: absolute;
                inset: -7px;
                border-radius: 50%;
                border: 2px solid rgba(63, 200, 195, 0.12);
                animation: fabPulse 3s ease-in-out infinite;
            }

            .mat-fab::before {
                content: '';
                position: absolute;
                inset: -14px;
                border-radius: 50%;
                border: 1.5px solid rgba(63, 200, 195, 0.06);
                animation: fabPulse 3s ease-in-out 0.5s infinite;
            }

            @keyframes fabPulse {
                0%, 100% { transform: scale(1); opacity: 0.7; }
                50% { transform: scale(1.2); opacity: 0; }
            }

            .mat-tab--center span {
                font-size: 10px;
                font-weight: 700;
                color: var(--accent);
                margin-top: 3px;
                letter-spacing: 0.3px;
            }

            .mat-tab--center:active .mat-fab {
                transform: scale(0.88);
                box-shadow: 0 2px 12px rgba(63, 200, 195, 0.35);
            }

            main {
                padding-bottom: calc(80px + var(--safe-bottom)) !important;
            }
            
            /* ═══ Chat widgetləri tabbar-ın üstünə qaldır ═══ */
            /* Tawk.to */
            .tawk-min-container,
            #tawk-bubble-container,
            iframe[title*="chat" i],
            iframe[src*="tawk"],
            div[class*="tawk"] {
                bottom: 76px !important;
            }

            /* YourGood widget */
            iframe[src*="yourgood"],
            div[data-pf-id],
            [id*="yourgood"],
            [class*="yourgood"] {
                bottom: 76px !important;
            }

            /* Telegram / WhatsApp / digər floating buttonlar */
            a.floating-btn,
            .float-button,
            [class*="float-wa"],
            [class*="float-tg"],
            a[href*="t.me"][style*="fixed"],
            a[href*="wa.me"][style*="fixed"] {
                bottom: 76px !important;
            }

            .mobile-tabs {
                display: none !important;
            }

            .mobile-bottom-promo {
                display: none !important;
            }

            .mobile-floating-header {
                z-index: 997;
            }
        }

       @media (max-width: 375px) {
    .mat-tab {
        font-size: 9px;
        gap: 3px;
        padding: 9px 3px 11px;
    }

    .mat-tab i {
        font-size: 20px;
    }

    .mat-fab {
        width: 54px;
        height: 54px;
        font-size: 22px;
        margin-top: -27px;
    }
}

        /* ═══════════════════════════════════════════════════════════════
           MOBILE APP - FLOATING BACK & ACTIONS HEADER
        ═══════════════════════════════════════════════════════════════ */
        .mobile-floating-header {
            display: none;
        }

        @media (max-width: 991px) {
            .mobile-floating-header {
    display: flex;
    position: fixed;
    top: 70px;
    left: 0;
    right: 0;
    z-index: 997;
    padding: 12px 16px;
    padding-top: 12px;
    align-items: center;
    justify-content: space-between;
    transition: background 0.3s ease, box-shadow 0.3s ease, top 0.3s ease;
    pointer-events: none;
    overflow: hidden;
    max-width: 100vw;
}

            .mobile-floating-header > * {
                pointer-events: auto;
            }

            .mobile-floating-header.scrolled {
                background: rgba(255,255,255,0.97);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                box-shadow: 0 2px 12px rgba(0,0,0,0.08);
                pointer-events: auto;
            }

            .mobile-floating-header.scrolled .mfh-btn {
                background: var(--bg-secondary);
                color: var(--text-primary);
                border-color: var(--border);
            }

            .mfh-btn {
                width: 42px;
                height: 42px;
                border-radius: 50%;
                background: rgba(0,0,0,0.35);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border: 1px solid rgba(255,255,255,0.15);
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 16px;
                cursor: pointer;
                transition: var(--transition);
                text-decoration: none;
            }

            .mfh-btn:hover, .mfh-btn:active {
                transform: scale(0.95);
            }

            .mfh-right {
    display: flex;
    gap: 10px;
    flex-shrink: 0;
}

            .mfh-btn.wishlist-active {
                background: var(--accent);
                border-color: var(--accent);
            }

            .mobile-floating-header.scrolled .mfh-title {
                display: block;
            }

            .mfh-title {
    display: none;
    flex: 1;
    min-width: 0;
    font-family: 'DM Sans', sans-serif;
    font-size: 15px;
    font-weight: 600;
    color: var(--text-primary);
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    padding: 0 8px;
}
        }

        /* ═══════════════════════════════════════════════════════════════
           HERO
        ═══════════════════════════════════════════════════════════════ */
        .hero {
            position: relative;
            height: 75vh;
            min-height: 600px;
            display: flex;
            align-items: flex-end;
            overflow: hidden;
        }

        .hero__media { position: absolute; inset: 0; }

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

        .hero__breadcrumb a:hover { color: #fff; }
        .hero__breadcrumb i { color: var(--accent); font-size: 10px; }
        .hero__breadcrumb span { color: rgba(255,255,255,0.5); }

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

        .hero__location i { color: var(--accent); }

        .hero__rating { display: flex; align-items: center; gap: 10px; }
        .hero__stars { display: flex; gap: 3px; }
        .hero__stars i { font-size: 14px; color: rgba(255,255,255,0.3); }
        .hero__stars i.active { color: var(--gold); }
        .hero__reviews { color: rgba(255,255,255,0.7); font-size: 14px; }

        .hero__actions { margin-top: 28px; display: flex; gap: 12px; }

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

        /* Mobile Hero */
        @media (max-width: 991px) {
            .hero {
                height: 50vh;
                min-height: 380px;
                align-items: flex-end;
            }

            .hero__content {
                padding: 0 0 28px;
            }

            .hero__breadcrumb {
                display: none;
            }

            .hero__title {
                font-size: clamp(1.6rem, 6vw, 2.2rem);
                margin-bottom: 12px;
                line-height: 1.25;
            }

            .hero__meta {
                gap: 14px;
            }

            .hero__location {
                font-size: 13px;
            }

            .hero__stars i { font-size: 12px; }
            .hero__reviews { font-size: 12px; }

            /* Hide desktop wishlist button on mobile (moved to floating header) */
            .hero__actions {
                display: none;
            }

            .hero__overlay {
                background: linear-gradient(
                    to top,
                    rgba(15, 23, 42, 0.95) 0%,
                    rgba(15, 23, 42, 0.4) 50%,
                    rgba(15, 23, 42, 0.1) 100%
                );
            }

            .container {
                padding: 0 16px;
            }
        }

        @media (max-width: 575px) {
            .hero {
                height: 45vh;
                min-height: 340px;
            }

            .hero__title {
                font-size: clamp(1.4rem, 5.5vw, 1.8rem);
            }
        }

        /* ═══════════════════════════════════════════════════════════════
           GALLERY
        ═══════════════════════════════════════════════════════════════ */
        .gallery { padding: 40px 0; background: var(--bg-primary); }

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

        .gallery__item:hover img { transform: scale(1.08); }

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

        .gallery__item:hover .gallery__overlay { opacity: 1; }

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

        .gallery__item:hover .gallery__icon { transform: scale(1); }
        .gallery__icon i { font-size: 18px; color: var(--primary); }

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

        .gallery__play i { font-size: 22px; color: var(--accent); margin-left: 3px; }

        .gallery__play:hover {
            transform: translate(-50%, -50%) scale(1.1);
            background: var(--accent);
        }

        .gallery__play:hover i { color: #fff; }

        /* Mobile Gallery - Horizontal scroll strip like an app */
        @media (max-width: 991px) {
            .gallery {
                padding: 0;
                background: var(--bg-primary);
            }

            .gallery > .container {
                padding: 0;
                max-width: 100%;
            }

            .gallery__grid {
                display: flex;
                gap: 4px;
                overflow-x: auto;
                scroll-snap-type: x mandatory;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
                padding: 0;
                grid-template-columns: none;
                grid-template-rows: none;
            }

            .gallery__grid::-webkit-scrollbar { display: none; }

            .gallery__item {
                flex: 0 0 85vw;
                height: 220px;
                border-radius: 0;
                scroll-snap-align: center;
            }

            .gallery__item:first-child {
                grid-column: unset;
                grid-row: unset;
            }

            .gallery__overlay { opacity: 0 !important; }

            .gallery__play {
                width: 56px;
                height: 56px;
            }

            .gallery__play i { font-size: 18px; }
        }

        @media (max-width: 575px) {
            .gallery__item {
                flex: 0 0 92vw;
                height: 200px;
            }
        }

        /* Gallery scroll indicator for mobile */
        .gallery-indicators {
            display: none;
        }

        @media (max-width: 991px) {
            .gallery-indicators {
                display: flex;
                justify-content: center;
                gap: 6px;
                padding: 12px 0 16px;
                background: var(--bg-primary);
            }

            .gallery-indicators__dot {
                width: 6px;
                height: 6px;
                border-radius: 50%;
                background: var(--border);
                transition: var(--transition);
            }

            .gallery-indicators__dot.active {
                width: 20px;
                border-radius: 100px;
                background: var(--accent);
            }
        }

        /* ═══════════════════════════════════════════════════════════════
           FEATURES STRIP
        ═══════════════════════════════════════════════════════════════ */
        .features-strip { background: var(--bg-primary); padding: 0 0 40px; }

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

        .feature-card__icon i { font-size: 20px; color: #fff; }
        .feature-card__content { min-width: 0; }

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

        .feature-card--price .feature-card__icon { background: rgba(255,255,255,0.2); }
        .feature-card--price .feature-card__label,
        .feature-card--price .feature-card__value { color: #fff; }

        .feature-card--price .feature-card__value {
            font-family: 'DM Sans', sans-serif;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        /* Mobile Features - Horizontal scroll chips */
        @media (max-width: 991px) {
            .features-strip {
                padding: 0 0 20px;
            }

            .features-strip > .container {
                padding: 0;
                max-width: 100%;
            }

            .features-strip__inner {
                display: flex;
                gap: 10px;
                overflow-x: auto;
                scroll-snap-type: x proximity;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
                padding: 0 16px;
            }

            .features-strip__inner::-webkit-scrollbar { display: none; }

            .feature-card {
                flex: 0 0 auto;
                min-width: 150px;
                padding: 14px 16px;
                border-radius: var(--radius-md);
                scroll-snap-align: start;
                border: 1px solid var(--border);
            }

            .feature-card:hover {
                transform: none;
            }

            .feature-card__icon {
                width: 38px;
                height: 38px;
            }

            .feature-card__icon i { font-size: 16px; }
            .feature-card__label { font-size: 10px; }
            .feature-card__value { font-size: 13px; }

            .feature-card--price {
                border: none;
            }

            .feature-card--price .feature-card__value {
                font-size: 18px;
            }
        }

        /* ═══════════════════════════════════════════════════════════════
           MAIN CONTENT LAYOUT
        ═══════════════════════════════════════════════════════════════ */
        .main-section { padding: 40px 0 80px; overflow-x: clip; }

        .bento-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 400px;
            gap: 30px;
            min-width: 0;
        }

        @media (max-width: 1199px) {
            .bento-layout { grid-template-columns: minmax(0, 1fr) 360px; gap: 24px; }
        }

        @media (max-width: 991px) {
            .bento-layout {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .main-section {
                padding: 20px 0 40px;
            }
        }

        .bento-grid {
            display: flex;
            flex-direction: column;
            gap: 20px;
            min-width: 0;
            max-width: 100%;
        }

        @media (max-width: 991px) {
            .bento-grid {
                gap: 12px;
            }
        }

        /* ═══════════════════════════════════════════════════════════════
           CARDS
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
            width: 100%;
            min-width: 0;
            max-width: 100%;
            box-sizing: border-box;
        }

        .card__text,
        .card__text * {
            max-width: 100%;
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: break-word;
        }

        .card__text img,
        .card__text video,
        .card__text iframe {
            max-width: 100%;
            height: auto;
            display: block;
        }

        .card__text table {
            display: block;
            max-width: 100%;
            overflow-x: auto;
        }

        .card__text pre {
            max-width: 100%;
            overflow-x: auto;
            white-space: pre-wrap;
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

        .card__icon i { font-size: 24px; color: #fff; }

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
        }

        .card__text p { margin-bottom: 14px; }
        .card__text p:last-child { margin-bottom: 0; }

        /* Mətn içindəki linklər görünsün (accent rəng + alt-xətt) */
        .card__text a {
            color: var(--accent);
            font-weight: 600;
            text-decoration: underline;
            text-underline-offset: 2px;
            text-decoration-thickness: 1px;
            transition: var(--transition);
            word-break: break-word;
        }

        .card__text a:hover {
            color: var(--accent-hover);
            text-decoration-thickness: 2px;
        }

        /* Mobile Cards */
        @media (max-width: 991px) {
           .card {
    border-radius: var(--radius-lg);
    padding: 20px;
    margin: 0;
    border-left: none;
    border-right: none;
    border-radius: 0;
    box-shadow: none;
    border-top: none;
    border-bottom: 1px solid var(--border);
    overflow: hidden;
    max-width: 100vw;
}

            .card:hover {
                box-shadow: none;
                transform: none;
            }

            .card__icon {
                width: 44px;
                height: 44px;
                margin-bottom: 16px;
            }

            .card__icon i { font-size: 20px; }

            .card__title {
                font-size: 20px;
                margin-bottom: 12px;
            }

            .card__text {
    font-size: 14px;
    line-height: 1.7;
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
}

.card__text ul,
.card__text ol {
    padding-left: 20px;
    margin: 0;
}

.card__text li {
    word-break: break-word;
    overflow-wrap: break-word;
}
        }

        /* ═══════════════════════════════════════════════════════════════
           ITINERARY
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

        .itinerary-item:hover { background: var(--bg-tertiary); }
        .itinerary-item.active { box-shadow: var(--shadow-md); }

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

        .itinerary-item.active .itinerary-toggle { background: var(--accent); }
        .itinerary-item.active .itinerary-toggle i { color: #fff; transform: rotate(180deg); }

        .itinerary-body { display: none; padding: 0 22px 22px; }

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
            grid-template-columns: 220px 1fr;
            gap: 24px;
        }

        /* When no image, text takes full width */
        .itinerary-content > .itinerary-details:first-child {
            grid-column: 1 / -1;
        }

        .itinerary-image {
            border-radius: var(--radius-md);
            overflow: hidden;
            aspect-ratio: 4/3;
        }

        .itinerary-image img { width: 100%; height: 100%; object-fit: cover; }

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

        .itinerary-meta-item i { color: var(--accent); }

        .itinerary-meta-item .badge {
            background: var(--success);
            color: #fff;
            padding: 4px 12px;
            border-radius: 100px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Mobile Itinerary */
        @media (max-width: 991px) {
            .itinerary-list {
                gap: 8px;
                margin-top: 14px;
            }

            .itinerary-header {
                padding: 14px 16px;
                gap: 10px;
            }

            .itinerary-day {
                padding: 6px 12px;
                font-size: 11px;
            }

            .itinerary-title { font-size: 14px; }

            .itinerary-toggle {
                width: 30px;
                height: 30px;
            }

            .itinerary-body { padding: 0 16px 16px; }

            .itinerary-content {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .itinerary-image {
                height: 160px;
                aspect-ratio: unset;
            }

            .itinerary-details { font-size: 13px; }

            .itinerary-meta {
                gap: 10px;
                flex-direction: column;
            }

            .itinerary-meta-item { font-size: 13px; }
        }

        /* ═══════════════════════════════════════════════════════════════
           TREATMENT GALLERY
        ═══════════════════════════════════════════════════════════════ */
        .treatment-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 24px;
            margin-top: 24px;
        }

        .treatment-gallery__item {
            text-align: center;
            transition: transform 0.3s ease;
        }

        .treatment-gallery__item:hover { transform: translateY(-6px); }

        .treatment-gallery__circle {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 12px;
            border: 4px solid var(--border);
            position: relative;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .treatment-gallery__item:hover .treatment-gallery__circle {
            border-color: var(--accent);
            box-shadow: 0 8px 25px rgba(63, 200, 195, 0.25);
        }

        .treatment-gallery__circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .treatment-gallery__item:hover .treatment-gallery__circle img { transform: scale(1.1); }

        .treatment-gallery__overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .treatment-gallery__overlay i { color: #fff; font-size: 22px; }
        .treatment-gallery__item:hover .treatment-gallery__overlay { opacity: 1; }

        .treatment-gallery__circle--empty {
            background: var(--bg-tertiary);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .treatment-gallery__circle--empty i { font-size: 36px; color: var(--text-muted); }

        .treatment-gallery__name {
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.3;
            margin: 0;
        }

        .treatment-gallery__link { text-decoration: none; }

        /* Mobile Treatment Gallery - responsive grid */
        @media (max-width: 991px) {
            .treatment-gallery {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 16px;
                overflow-x: visible;
                scroll-snap-type: none;
                padding-bottom: 0;
                margin-top: 16px;
            }

            .treatment-gallery::-webkit-scrollbar { display: none; }

            .treatment-gallery__item {
                flex: none;
                width: auto;
                scroll-snap-align: unset;
            }

            .treatment-gallery__item:hover { transform: none; }

            .treatment-gallery__circle {
                width: 90px;
                height: 90px;
                margin: 0 auto 8px;
                border-width: 3px;
            }

            .treatment-gallery__name { font-size: 12px; }

            .treatment-gallery__overlay { display: none; }
        }

        @media (max-width: 575px) {
            .treatment-gallery {
                grid-template-columns: repeat(3, 1fr);
                gap: 12px;
            }

            .treatment-gallery__circle {
                width: 80px;
                height: 80px;
            }

            .treatment-gallery__name { font-size: 11px; }
        }

        @media (max-width: 400px) {
            .treatment-gallery {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }

            .treatment-gallery__circle {
                width: 100px;
                height: 100px;
            }

            .treatment-gallery__name { font-size: 12px; }
        }

        /* ═══════════════════════════════════════════════════════════════
           MAP
        ═══════════════════════════════════════════════════════════════ */
        .map-frame {
            border-radius: var(--radius-lg);
            overflow: hidden;
            height: 300px;
            margin-top: 20px;
        }

        .map-frame iframe { width: 100%; height: 100%; border: none; }

        @media (max-width: 991px) {
            .map-frame {
                height: 220px;
                margin-top: 14px;
                border-radius: var(--radius-md);
            }
        }

        /* ═══════════════════════════════════════════════════════════════
           REVIEWS
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

        .reviews-score__stars { display: flex; gap: 4px; margin: 10px 0; }
        .reviews-score__stars i { font-size: 16px; color: var(--gold); }
        .reviews-score__count { font-size: 14px; color: var(--text-muted); }

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

        .review-bar__label { font-size: 14px; color: var(--text-secondary); }

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

        .review-item:last-child { border-bottom: none; }

        .review-avatar {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            border: 3px solid var(--accent);
        }

        .review-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .review-content { flex: 1; min-width: 0; }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 10px;
        }

        .review-author { font-size: 16px; font-weight: 600; color: var(--text-primary); margin: 0; }
        .review-date { font-size: 13px; color: var(--text-muted); }
        .review-rating { display: flex; gap: 3px; }
        .review-rating i { font-size: 12px; color: var(--border); }
        .review-rating i.active { color: var(--gold); }
        .review-text { font-size: 15px; color: var(--text-secondary); line-height: 1.7; margin: 0; }

        .review-form {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px dashed var(--border);
        }

        .review-form__title { font-size: 20px; margin-bottom: 24px; }

        .rating-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 18px;
            margin-bottom: 24px;
        }

        .rating-item { display: flex; flex-direction: column; gap: 8px; }
        .rating-item__label { font-size: 13px; font-weight: 500; color: var(--text-secondary); }
        .rating-item__stars { display: flex; gap: 5px; }

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

        .review-form__textarea:focus { outline: none; border-color: var(--accent); }
        .review-form__submit { margin-top: 18px; }

        .no-reviews { text-align: center; padding: 50px 20px; }

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

        .no-reviews__icon i { font-size: 28px; color: var(--text-muted); }
        .no-reviews__text { font-size: 16px; color: var(--text-muted); }

        /* Mobile Reviews */
        @media (max-width: 991px) {
            .reviews-header {
                grid-template-columns: 1fr;
                text-align: center;
                padding: 18px;
                margin: 14px 0;
                gap: 18px;
            }

            .reviews-score__number { font-size: 40px; }
            .reviews-score__stars i { font-size: 14px; }

            .review-bar {
                grid-template-columns: 70px 1fr 35px;
                gap: 10px;
            }

            .review-bar__label { font-size: 12px; }
            .review-bar__value { font-size: 12px; }

            .reviews-list { max-height: 400px; }

            .review-item {
                gap: 12px;
                padding: 16px 0;
            }

            .review-avatar {
                width: 42px;
                height: 42px;
                border-width: 2px;
            }

            .review-author { font-size: 14px; }
            .review-date { font-size: 11px; }
            .review-text { font-size: 13px; }

            .review-form { margin-top: 20px; padding-top: 20px; }
            .review-form__title { font-size: 18px; margin-bottom: 18px; }

            .rating-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 14px;
                margin-bottom: 18px;
            }

            .rating-item__label { font-size: 12px; }
            .rating-item__stars i { font-size: 24px; } /* Bigger tap targets */

            .review-form__textarea {
                min-height: 100px;
                padding: 14px;
                font-size: 14px;
            }

            .no-reviews { padding: 30px 16px; }
        }

        /* ═══════════════════════════════════════════════════════════════
           SIDEBAR - Room Select Button
        ═══════════════════════════════════════════════════════════════ */
        .booking-sidebar { position: sticky; top: 100px; }
        .sidebar-stack { display: flex; flex-direction: column; gap: 20px; }

        /* Hide sidebar on mobile (functionality moves to bottom bar) */
        @media (max-width: 991px) {
            .booking-sidebar {
                position: relative;
                top: 0;
            }

            /* Hide the room select button card on mobile - replaced by bottom bar */
            .room-select-btn {
                display: none !important;
            }

            .sidebar-stack {
                gap: 0;
            }

            .sidebar-included,
            .sidebar-amenities {
                border-radius: 0;
                border-left: none;
                border-right: none;
                box-shadow: none;
                border-top: none;
            }

            .sidebar-contact {
                border-radius: 0;
                border-left: none;
                border-right: none;
            }
        }

        .room-select-btn {
            display: block;
            text-decoration: none !important;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
            border: none;
            border-radius: var(--radius-xl);
            overflow: hidden;
            transition: var(--transition);
            box-shadow: 0 10px 30px -8px rgba(63, 200, 195, 0.45);
            position: relative;
        }

        .room-select-btn::after {
            content: '';
            position: absolute;
            top: -40%;
            right: -10%;
            width: 180px;
            height: 180px;
            background: radial-gradient(circle, rgba(255,255,255,0.18) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        /* Yüngül parıltı keçidi (yalnız hover-də işə düşür, yorucu deyil) */
        .room-select-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -75%;
            width: 50%;
            height: 100%;
            background: linear-gradient(100deg, transparent, rgba(255,255,255,0.25), transparent);
            transform: skewX(-20deg);
            pointer-events: none;
            z-index: 2;
            transition: left 0.7s ease;
        }

        .room-select-btn:hover::before {
            left: 130%;
        }

        .room-select-btn:hover {
            box-shadow: 0 16px 40px -8px rgba(63, 200, 195, 0.55);
            transform: translateY(-3px);
            text-decoration: none !important;
        }

        .room-select-btn__header {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 22px 24px 18px;
            text-align: left;
            position: relative;
            z-index: 1;
        }

        .room-select-btn__icon-wrap {
            width: 48px;
            height: 48px;
            margin: 0;
            background: rgba(255,255,255,0.18);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            flex-shrink: 0;
        }

        .room-select-btn__icon-wrap i { font-size: 22px; color: #fff; }

        .room-select-btn__title {
            font-family: 'DM Sans', sans-serif;
            font-size: 19px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 2px;
            line-height: 1.2;
        }

        .room-select-btn__subtitle { font-size: 13px; color: rgba(255,255,255,0.8); line-height: 1.3; }

        .room-select-btn__body {
            margin: 0 16px 16px;
            padding: 16px 18px;
            background: rgba(255,255,255,0.95);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 1;
        }

        .room-select-btn__price-area { display: flex; flex-direction: column; }

        .room-select-btn__price-label {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .room-select-btn__price-value {
            font-family: 'DM Sans', sans-serif;
            font-size: 26px;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: 0.3px;
        }

        .room-select-btn__price-unit { font-size: 13px; font-weight: 500; color: var(--text-muted); }

        .room-select-btn__arrow {
            width: 46px;
            height: 46px;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(63, 200, 195, 0.35);
            position: relative;
        }

        .room-select-btn__arrow::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 2px solid var(--accent);
            animation: arrowPulse 2.4s ease-out infinite;
            pointer-events: none;
        }

        @keyframes arrowPulse {
            0% { transform: scale(1); opacity: 0.6; }
            100% { transform: scale(1.6); opacity: 0; }
        }

        .room-select-btn__arrow i { font-size: 17px; color: #fff; transition: transform 0.3s ease; }

        .room-select-btn:hover .room-select-btn__arrow {
            transform: scale(1.08);
        }

        .room-select-btn:hover .room-select-btn__arrow i { transform: translateX(3px); }

/* ═══════════════════════════════════════════════════════════════
           SIDEBAR - Price List (PDF) Card (Modern)
        ═══════════════════════════════════════════════════════════════ */
        .pricelist-card {
            background: var(--bg-primary, #fff);
            border: 1px solid var(--border, #e9ecf2);
            border-radius: var(--radius-xl, 20px);
            overflow: hidden;
            box-shadow: var(--shadow-sm, 0 4px 16px rgba(16,24,40,.06));
        }

        .pricelist-card__head {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 20px 22px;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
            color: #fff;
        }

        .pricelist-card__icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,.12);
            font-size: 20px;
            flex-shrink: 0;
        }

        .pricelist-card__title { font-size: 18px; font-weight: 800; line-height: 1.2; }
        .pricelist-card__subtitle { font-size: 13px; opacity: .75; margin-top: 2px; }

        .pricelist-card__body {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 18px;
        }

        .pricelist-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 16px;
            border: 1.5px solid var(--border, #e9ecf2);
            border-radius: 14px;
            background: #fff;
            text-decoration: none;
            transition: all .25s ease;
            position: relative;
        }

        .pricelist-btn:hover {
            border-color: var(--accent, #3fc8c3);
            background: #f0fdfc;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(63,200,195,.12);
        }

        .pricelist-btn__icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #d4f5f3, #b6ece9);
            color: #1f8a86;
            font-size: 17px;
            flex-shrink: 0;
        }

        .pricelist-btn__label {
            flex: 1;
            min-width: 0;
            font-size: 14.5px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.3;
        }

        .pricelist-btn__download {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            color: #64748b;
            font-size: 13px;
            flex-shrink: 0;
            transition: all .25s ease;
        }

        .pricelist-btn:hover .pricelist-btn__download {
            background: var(--accent, #3fc8c3);
            color: #fff;
        }

/* ═══════════════════════════════════════════════════════════════
           SIDEBAR - Doctor/Consultant Card (Modern)
        ═══════════════════════════════════════════════════════════════ */
        .sidebar-video {
            background: var(--bg-primary);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            padding: 20px;
            scroll-margin-top: 120px;
        }
        .sidebar-video:hover { box-shadow: var(--shadow-lg); }
        .sidebar-video__badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #0d9488;
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 5px 14px;
            border-radius: 100px;
            margin-bottom: 14px;
        }
        .sidebar-video__thumb {
            position: relative;
            display: block;
            width: 100%;
            padding: 0;
            border: 0;
            background: #0f172a;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            aspect-ratio: 16 / 9;
        }
        .sidebar-video__thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: filter 0.2s;
        }
        .sidebar-video__thumb:hover img { filter: brightness(0.85); }
        .sidebar-video__play {
            position: absolute;
            top: 50%; left: 50%;
            width: 52px; height: 52px;
            margin: -26px 0 0 -26px;
            border-radius: 50%;
            background: rgba(255,255,255,0.92);
            color: #0d9488;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            padding-left: 3px;
            transition: transform 0.2s;
        }
        .sidebar-video__thumb:hover .sidebar-video__play { transform: scale(1.08); }
        .sidebar-video__caption { font-size: 13px; color: #6b7280; margin: 10px 0 0; }
        .video-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.85);
            z-index: 100000;
            display: none;
            align-items: center;
            justify-content: center;
        }
        .video-modal-overlay.open { display: flex; }
        .video-modal-overlay__frame {
            width: 90vw;
            max-width: 900px;
            aspect-ratio: 16 / 9;
            background: #000;
        }
        .video-modal-overlay__frame iframe { width: 100%; height: 100%; border: 0; display: block; }
        .video-modal-overlay__close {
            position: absolute;
            top: 16px; right: 16px;
            width: 40px; height: 40px;
            border: 0; border-radius: 50%;
            background: rgba(255,255,255,0.15);
            color: #fff;
            font-size: 20px;
            line-height: 1;
            cursor: pointer;
        }
        .video-modal-overlay__close:hover { background: rgba(255,255,255,0.3); }
        @media (max-width: 767px) {
            .sidebar-video__play { width: 44px; height: 44px; margin: -22px 0 0 -22px; font-size: 15px; }
            .video-modal-overlay__frame { width: 100vw; max-width: 100vw; }
        }

        .sidebar-doctor {
            background: var(--bg-primary);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .sidebar-doctor:hover { box-shadow: var(--shadow-lg); }

        .sidebar-doctor__top {
            background: linear-gradient(135deg, #0d9488 0%, #14b8a6 50%, #2dd4bf 100%);
            padding: 24px 24px 42px;
            text-align: center;
            position: relative;
        }

        .sidebar-doctor__top::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 30px;
            background: var(--bg-primary);
            border-radius: 50% 50% 0 0 / 100% 100% 0 0;
        }

        .sidebar-doctor__badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 5px 14px;
            border-radius: 100px;
            margin-bottom: 6px;
        }

        .sidebar-doctor__badge i { font-size: 10px; }

        .sidebar-doctor__heading {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            font-weight: 600;
            color: #fff;
            margin: 0;
        }

        .sidebar-doctor__body {
            padding: 0 24px 24px;
            text-align: center;
            margin-top: -24px;
            position: relative;
            z-index: 1;
        }

        /* Squircle photo - müasir rounded square */
        .sidebar-doctor__photo-wrap {
            width: 100px;
            height: 100px;
            margin: 0 auto 14px;
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            border: 4px solid var(--bg-primary);
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            transform: rotate(0deg);
            transition: var(--transition);
        }

        .sidebar-doctor:hover .sidebar-doctor__photo-wrap {
            box-shadow: 0 8px 28px rgba(13, 148, 136, 0.25);
        }

        .sidebar-doctor__photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sidebar-doctor__photo-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #ccfbf1, #99f6e4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: #0d9488;
        }

        .sidebar-doctor__online {
            position: absolute;
            bottom: 6px;
            right: 6px;
            width: 14px;
            height: 14px;
            background: #22c55e;
            border: 3px solid var(--bg-primary);
            border-radius: 50%;
            z-index: 2;
        }

        .sidebar-doctor__name {
            font-family: 'DM Sans', sans-serif;
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 2px;
        }

        .sidebar-doctor__position {
            font-size: 13px;
            color: #0d9488;
            font-weight: 500;
            margin: 0 0 14px;
        }

        .sidebar-doctor__description {
            font-size: 13px;
            line-height: 1.65;
            color: var(--text-secondary);
            margin: 0 0 20px;
        }

        .sidebar-doctor__actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .sidebar-doctor__btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 13px 20px;
            background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);
            color: #fff;
            border: none;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .sidebar-doctor__btn-primary:hover {
            background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(13, 148, 136, 0.35);
            color: #fff;
            text-decoration: none;
        }

        .sidebar-doctor__btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 12px 20px;
            background: transparent;
            color: var(--text-secondary);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .sidebar-doctor__btn-secondary:hover {
            border-color: #0d9488;
            color: #0d9488;
            background: #f0fdfa;
            text-decoration: none;
        }

        /* ═══ Doctor Contact Modal ═══ */
        .doctor-modal-overlay {
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.5);
    backdrop-filter: blur(4px);
    z-index: 999999;
    justify-content: center;
    align-items: center;
    padding: 20px;
    overflow-y: auto;
}

        .doctor-modal-overlay.active { display: flex; }

        .doctor-modal {
            background: #fff;
            border-radius: var(--radius-xl);
            width: 100%;
            max-width: 440px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 60px rgba(0,0,0,0.2);
            animation: modalSlideUp 0.3s ease;
        }

        @keyframes modalSlideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .doctor-modal__header {
            background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);
            padding: 22px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: var(--radius-xl) var(--radius-xl) 0 0;
        }

        .doctor-modal__header-title {
            color: #fff;
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }

        .doctor-modal__close {
            width: 32px;
            height: 32px;
            background: rgba(255,255,255,0.2);
            border: none;
            border-radius: 50%;
            color: #fff;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .doctor-modal__close:hover { background: rgba(255,255,255,0.35); }

        .doctor-modal__body { padding: 24px; }

        .doctor-modal__field { margin-bottom: 16px; }

        .doctor-modal__label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 6px;
        }

        .doctor-modal__input {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            color: var(--text-primary);
            transition: var(--transition);
            outline: none;
            background: #fff;
        }

        .doctor-modal__input:focus {
            border-color: #14b8a6;
            box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.12);
        }

        .doctor-modal__textarea {
            resize: vertical;
            min-height: 80px;
        }

        /* Phone input with country code */
        .phone-input-group {
            display: flex;
            gap: 8px;
        }

        .phone-country-select {
            width: 120px;
            min-width: 120px;
            padding: 11px 8px 11px 12px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            color: var(--text-primary);
            background: #fff;
            cursor: pointer;
            outline: none;
            transition: var(--transition);
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 8px center;
            padding-right: 24px;
        }

        .phone-country-select:focus {
            border-color: #14b8a6;
            box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.12);
        }

        .phone-number-input {
            flex: 1;
            padding: 11px 14px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            color: var(--text-primary);
            outline: none;
            transition: var(--transition);
            letter-spacing: 0.5px;
        }

        .phone-number-input:focus {
            border-color: #14b8a6;
            box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.12);
        }

        .doctor-modal__submit {
            width: 100%;
            padding: 14px 20px;
            background: linear-gradient(135deg, #0d9488, #14b8a6);
            color: #fff;
            border: none;
            border-radius: var(--radius-md);
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .doctor-modal__submit:hover {
            background: linear-gradient(135deg, #0f766e, #0d9488);
            box-shadow: 0 4px 16px rgba(13, 148, 136, 0.35);
        }

        .doctor-modal__submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        .doctor-modal__submit .spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        .doctor-modal__submit.loading .spinner { display: inline-block; }
        .doctor-modal__submit.loading .btn-text { display: none; }

        @keyframes spin { to { transform: rotate(360deg); } }

        .doctor-modal__error {
            display: none;
            background: #fef2f2;
            color: #dc2626;
            padding: 10px 14px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            margin-bottom: 14px;
            align-items: center;
            gap: 8px;
        }

        .doctor-modal__error.active { display: flex; }

        .doctor-modal__success {
            display: none;
            text-align: center;
            padding: 30px 20px;
        }

        .doctor-modal__success.active { display: block; }

        .doctor-modal__success-icon {
            width: 64px;
            height: 64px;
            background: #dcfce7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 28px;
            color: #16a34a;
        }

        .doctor-modal__success h5 {
            font-size: 18px;
            margin: 0 0 8px;
            color: var(--text-primary);
        }

        .doctor-modal__success p {
            font-size: 14px;
            color: var(--text-secondary);
            margin: 0;
        }

        @media (max-width: 991px) {
            .sidebar-doctor {
                border-radius: 0;
                border-left: none;
                border-right: none;
                box-shadow: none;
                border-top: none;
            }

            .sidebar-doctor__top { padding: 20px 20px 36px; }
            .sidebar-doctor__body { padding: 0 20px 20px; }
            .sidebar-doctor__photo-wrap { width: 84px; height: 84px; border-radius: 20px; }
            .sidebar-doctor__heading { font-size: 16px; }
            .sidebar-doctor__description { font-size: 12px; }

            .doctor-modal {
                max-width: 100%;
                border-radius: var(--radius-lg);
            }

            .phone-country-select { width: 110px; min-width: 110px; font-size: 13px; }
            .phone-number-input { font-size: 13px; }
        }

        /* ═══════════════════════════════════════════════════════════════
           SIDEBAR - What's Included Card
        ═══════════════════════════════════════════════════════════════ */
        .sidebar-included {
            background: var(--bg-primary);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            padding: 28px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .sidebar-included:hover { box-shadow: var(--shadow-md); }

        .sidebar-included__header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-included__icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-included__icon i { font-size: 18px; color: #059669; }

        .sidebar-included__title {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .sidebar-included__section-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            margin-top: 4px;
        }

        .sidebar-included__section-title--yes { color: #059669; }

        .sidebar-included__section-title--no {
            color: #dc2626;
            margin-top: 18px;
            padding-top: 16px;
            border-top: 1px dashed var(--border);
        }

        .sidebar-included__list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .sidebar-included__item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 14px;
            line-height: 1.5;
            padding: 6px 0;
        }

        .sidebar-included__item--yes { color: var(--text-secondary); }

        .sidebar-included__item--no {
            color: var(--text-muted);
            text-decoration: line-through;
            text-decoration-color: rgba(220, 38, 38, 0.3);
        }

        .sidebar-included__check {
            width: 20px;
            height: 20px;
            min-width: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            margin-top: 1px;
        }

        .sidebar-included__html { font-size: 14px; line-height: 1.7; color: var(--text-secondary); }
        .sidebar-included__html ul { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 4px; }
        .sidebar-included__html ul li { position: relative; padding: 6px 0 6px 30px; border-bottom: 1px solid var(--border); }
        .sidebar-included__html ul li:last-child { border-bottom: none; }
        .sidebar-included__html ul li::before { content: '\f00c'; font-family: 'Font Awesome 6 Free'; font-weight: 900; position: absolute; left: 0; top: 8px; font-size: 10px; color: #16a34a; background: #dcfce7; width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .sidebar-included__html--excluded ul li::before { content: '\f00d'; color: #dc2626; background: #fef2f2; }
        .sidebar-included__html--excluded ul li { color: var(--text-muted); }
        .sidebar-included__html p { margin-bottom: 8px; }
        .sidebar-included__html strong { color: var(--text-primary); }
        .sidebar-included__html ul li:empty { display: none !important; }
        .sidebar-included__html ul ul { margin: 0; padding: 0; }

        .sidebar-included__check--yes { background: #dcfce7; color: #16a34a; }
        .sidebar-included__check--no { background: #fef2f2; color: #dc2626; }

        @media (max-width: 991px) {
            .sidebar-included {
                padding: 20px 16px;
            }

            .sidebar-included__header {
                margin-bottom: 14px;
                padding-bottom: 12px;
            }

            .sidebar-included__item { font-size: 13px; }
            .sidebar-included__html { font-size: 13px; }
        }

        /* ═══════════════════════════════════════════════════════════════
           SIDEBAR - Amenities + Contact
        ═══════════════════════════════════════════════════════════════ */
        .sidebar-amenities {
            background: var(--bg-primary);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            padding: 28px;
            box-shadow: var(--shadow-sm);
        }

        .sidebar-amenities__header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-amenities__icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-amenities__icon i { font-size: 18px; color: #2563eb; }

        .sidebar-amenities__title {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .sidebar-amenities__list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .sidebar-amenities__tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 100px;
            font-size: 13px;
            color: var(--text-secondary);
            transition: var(--transition);
        }

        .sidebar-amenities__tag i { color: var(--accent); font-size: 11px; }
        .sidebar-amenities__tag:hover { background: var(--bg-tertiary); border-color: var(--accent); }

        .sidebar-contact {
            background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-tertiary) 100%);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            padding: 24px;
            text-align: center;
        }

        .sidebar-contact__text { font-size: 14px; color: var(--text-secondary); margin-bottom: 12px; }
        .sidebar-contact__text i { color: var(--accent); margin-right: 6px; }

        .sidebar-contact__link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--accent) !important;
            text-decoration: none !important;
            transition: var(--transition);
        }

        .sidebar-contact__link:hover { color: var(--accent-hover) !important; }

        @media (max-width: 991px) {
            .sidebar-amenities {
                padding: 20px 16px;
            }

            .sidebar-amenities__header {
                margin-bottom: 14px;
                padding-bottom: 12px;
            }

            .sidebar-amenities__tag {
                padding: 6px 12px;
                font-size: 12px;
            }

            .sidebar-contact {
                padding: 20px 16px;
                border-radius: 0;
                border-left: none;
                border-right: none;
            }
        }

        /* ═══════════════════════════════════════════════════════════════
           BUTTONS
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
            box-shadow: 0 8px 25px rgba(63, 200, 195, 0.35);
            color: #fff;
        }

        @media (max-width: 991px) {
            .btn {
                padding: 12px 24px;
                font-size: 14px;
                width: 100%;
            }
        }

        /* ═══════════════════════════════════════════════════════════════
           POPULAR SERVICES + RESPONSIVE
        ═══════════════════════════════════════════════════════════════ */
        .popular-section { padding: 70px 0 90px; background: var(--bg-primary); }
        .popular-section__header { text-align: center; margin-bottom: 50px; }
        .popular-section__title { font-size: 40px; margin-bottom: 12px; }
        .popular-section__subtitle { font-size: 16px; color: var(--text-secondary); }

        .availability-box {
            margin-top: 16px;
            padding: 16px;
            border-radius: var(--radius-md);
            font-size: 14px;
        }

        .availability-box--success { background: linear-gradient(135deg, #ecfdf5, #d1fae5); border: 1px solid #a7f3d0; color: #065f46; }
        .availability-box--warning { background: linear-gradient(135deg, #fffbeb, #fef3c7); border: 1px solid #fcd34d; color: #92400e; }
        .availability-box--danger { background: linear-gradient(135deg, #fef2f2, #fecaca); border: 1px solid #fca5a5; color: #991b1b; }

        .availability-box p { margin: 0 0 8px; display: flex; align-items: center; gap: 10px; }
        .availability-box p:last-child { margin-bottom: 0; }

        @media (max-width: 991px) {
            .popular-section {
                padding: 40px 0 50px;
            }

            .popular-section__header {
                margin-bottom: 28px;
            }

            .popular-section__title {
                font-size: 26px;
            }

            .popular-section__subtitle {
                font-size: 14px;
            }
        }

        /* ═══════════════════════════════════════════════════════════════
           MOBILE APP - SMOOTH SECTION TRANSITIONS
        ═══════════════════════════════════════════════════════════════ */
        @media (max-width: 991px) {
            /* Smooth scrolling for the entire page */
            html {
                scroll-behavior: smooth;
                -webkit-overflow-scrolling: touch;
            }

            /* Remove heavy AOS animations on mobile for snappier feel */
            [data-aos] {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }

            /* Disable card hover transforms on mobile (prevents jank) */
            .card:hover,
            .feature-card:hover,
            .sidebar-amenities__tag:hover {
                transform: none;
                box-shadow: none;
            }
        }

        /* ═══════════════════════════════════════════════════════════════
           MOBILE TAB NAVIGATION (Quick jump sections)
        ═══════════════════════════════════════════════════════════════ */
        .mobile-tabs {
            display: none;
        }

        @media (max-width: 991px) {
            .mobile-tabs {
                display: flex;
                position: sticky;
                top: 0;
                z-index: 100;
                background: var(--bg-primary);
                border-bottom: 1px solid var(--border);
                overflow-x: auto;
                scrollbar-width: none;
                -webkit-overflow-scrolling: touch;
                gap: 0;
                box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            }

            .mobile-tabs::-webkit-scrollbar { display: none; }

            .mobile-tabs__item {
                flex: 0 0 auto;
                padding: 14px 20px;
                font-size: 13px;
                font-weight: 600;
                color: var(--text-muted);
                text-decoration: none;
                white-space: nowrap;
                border-bottom: 2px solid transparent;
                transition: var(--transition);
                text-align: center;
            }

            .mobile-tabs__item.active,
            .mobile-tabs__item:active {
                color: var(--accent);
                border-bottom-color: var(--accent);
            }
        }

        /* ═══════════════════════════════════════════════════════════════
           PULL-TO-REFRESH INDICATOR (visual only)
        ═══════════════════════════════════════════════════════════════ */
        @media (max-width: 991px) {
            body {
                overscroll-behavior-y: contain;
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
        $visibleMediaCount = min($allMedia->count(), 6);
    @endphp

    {{-- MOBILE FLOATING HEADER --}}
    <div class="mobile-floating-header" id="mobileFloatingHeader">
        <a href="{{ route('front.tourbooking.services') }}" class="mfh-btn">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
        <span class="mfh-title">{{ $service?->translation?->title }}</span>
        <div class="mfh-right">
            <button class="mfh-btn" onclick="if(navigator.share) navigator.share({title: '{{ $service?->translation?->title }}', url: window.location.href})">
                <i class="fa-solid fa-share-nodes"></i>
            </button>
        </div>
    </div>

    {{-- HERO --}}
    <section class="hero" id="section-hero">
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

                @include('tourbooking::front.services.rating-badges', ['service' => $service])

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
                        data-url="{{ route('user.wishlist.store') }}"
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

    {{-- GALLERY --}}
    <section class="gallery">
        <div class="container">
            <div class="gallery__grid" data-aos="fade-up" id="galleryGrid">
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

                @for($i = 1; $i <= 3; $i++)
                    @if(isset($nonThumbnails[$i]))
                        <a href="{{ asset($nonThumbnails[$i]->file_path) }}" class="gallery__item glightbox" data-gallery="gallery">
                            <img src="{{ asset($nonThumbnails[$i]->file_path) }}" alt="{{ $nonThumbnails[$i]->caption ?: $service?->translation?->title }}">
                            <div class="gallery__overlay">
                                <div class="gallery__icon"><i class="fa-solid fa-expand"></i></div>
                            </div>
                        </a>
                    @endif
                @endfor

                @foreach($allMedia->slice(5) as $media)
                    <a href="{{ asset($media->file_path) }}" class="gallery__item glightbox d-none" data-gallery="gallery"></a>
                @endforeach
            </div>
        </div>
        {{-- Mobile gallery indicators --}}
        <div class="gallery-indicators" id="galleryIndicators">
            @for($i = 0; $i < $visibleMediaCount; $i++)
                <span class="gallery-indicators__dot @if($i === 0) active @endif"></span>
            @endfor
        </div>
    </section>

    {{-- MOBILE TABS (Quick navigation) --}}
    <nav class="mobile-tabs" id="mobileTabs">
        <a href="#section-about" class="mobile-tabs__item active">О туре</a>
        @if($service?->itineraries && $service?->itineraries->count() > 0)
            <a href="#section-itinerary" class="mobile-tabs__item">Программа</a>
        @endif
        @if($service?->google_map_url)
            <a href="#section-map" class="mobile-tabs__item">Карта</a>
        @endif
        <a href="#section-reviews" class="mobile-tabs__item">Отзывы</a>
        @if($service?->included || $service?->excluded)
            <a href="#section-included" class="mobile-tabs__item">Включено</a>
        @endif
    </nav>

    {{-- FEATURES STRIP --}}
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
                                {{ currency($service->current_price_per_person ?? $service?->price_per_person) }}
                            @else
                                {{ currency($service->current_price ?? $service->discount_price ?? $service->full_price) }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <section class="main-section">
        <div class="container">
            <div class="bento-layout">
                {{-- LEFT - Content --}}
                <div class="bento-grid">

                    {{-- About --}}
                    <div class="card" data-aos="fade-up" id="section-about">
                        <div class="card__icon"><i class="fa-regular fa-compass"></i></div>
                        <h3 class="card__title">О туре</h3>
                        <div class="card__text">
                            {!! $service?->translation?->short_description !!}
                        </div>
                    </div>

                    {{-- Description --}}
                    @if($service?->translation?->description)
                        <div class="card" data-aos="fade-up">
                            <div class="card__icon"><i class="fa-regular fa-file-lines"></i></div>
                            <h3 class="card__title">Описание</h3>
                            <div class="card__text">
                                {!! $service?->translation?->description !!}
                            </div>
                        </div>
                    @endif
                    
                     {{-- Treatment Gallery Section --}}
                    @if(isset($service->treatmentItems) && $service->treatmentItems->where('status', true)->count() > 0)
                        @php
                            $treatmentItems = $service->treatmentItems->where('status', true)->sortBy('display_order');
                            $treatmentSectionTitle = $service->treatment_section_title ?? 'Лечебная база';
                        @endphp
                       <div class="card" data-aos="fade-up" id="section-treatment">
                            <div class="card__icon"><i class="fa-regular fa-spa"></i></div>
                            <h3 class="card__title">{{ $treatmentSectionTitle }}</h3>

                            <div class="treatment-gallery">
                                @foreach($treatmentItems as $item)
                                    <div class="treatment-gallery__item" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 80 }}">
                                        @if($item->image)
                                            <a href="{{ asset($item->image) }}" class="treatment-gallery__link glightbox" data-gallery="treatments" data-glightbox="title: {{ $item->title }}">
                                                <div class="treatment-gallery__circle">
                                                    <img src="{{ asset($item->image) }}" alt="{{ $item->title }}" loading="lazy">
                                                    <div class="treatment-gallery__overlay">
                                                        <i class="fa-solid fa-expand"></i>
                                                    </div>
                                                </div>
                                            </a>
                                        @else
                                            <div class="treatment-gallery__circle treatment-gallery__circle--empty">
                                                <i class="fa-solid fa-spa"></i>
                                            </div>
                                        @endif
                                        <p class="treatment-gallery__name">{{ $item->title }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Itinerary --}}
                    @if($service?->itineraries && $service?->itineraries->count() > 0)
                        <div class="card" data-aos="fade-up" id="section-itinerary">
                            <div class="card__icon"><i class="fa-regular fa-map"></i></div>
                            <h3 class="card__title">Программа тура</h3>
                            @if($service?->tour_plan_sub_title)
                                <p class="card__subtitle">{{ $service?->tour_plan_sub_title }}</p>
                            @endif

                            <div class="itinerary-list">
                                @foreach($service?->itineraries as $index => $itinerary)
                                    <div class="itinerary-item @if($loop->first) active @endif">
                                        <div class="itinerary-header" onclick="toggleItinerary(this)">
<span class="itinerary-day">{{ $itinerary?->day_display }}</span>
                                            <h4 class="itinerary-title">{{ $itinerary?->title }}</h4>
                                            <div class="itinerary-toggle"><i class="fa-solid fa-chevron-down"></i></div>
                                        </div>
                                        <div class="itinerary-body">
                                            <div class="itinerary-content">
                                                @if($itinerary?->image)
                                                    <div class="itinerary-image">
                                                        <img src="{{ asset($itinerary->image) }}" alt="{{ $itinerary->title }}" loading="lazy">
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



                    {{-- Reviews --}}
                    <div class="card" data-aos="fade-up" id="section-reviews" x-data="reviewForm()">
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
                                            <img src="{{ asset($review->user->image ?? 'frontend/assets/img/shape/placeholder.png') }}" alt="" loading="lazy">
                                        </div>
                                        <div class="review-content">
                                            <div class="review-header">
                                                <div>
                                                    <h5 class="review-author">{{ $review->user->name }}</h5>
                                                    <span class="review-date">{{ \Carbon\Carbon::parse($review->created_at)->format('d.m.Y') }}</span>
                                                </div>
                                                <div class="review-rating">
                                                    @foreach(range(1, 5) as $star)
                                                        <i class="fa-solid fa-star @if($review->rating >= $star) active @endif"></i>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <p class="review-text">{{ $review->review }}</p>
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

                {{-- ========== SIDEBAR ========== --}}
                <div class="booking-sidebar">
                    <div class="sidebar-stack">

                        {{-- 1. ROOM SELECTION BUTTON --}}
                        @if($hasRooms)
                            <a href="{{ route('front.tourbooking.rooms.selection', $service->slug) }}" class="room-select-btn" data-aos="fade-left">
                                <div class="room-select-btn__header">
                                    <div class="room-select-btn__icon-wrap">
                                        <i class="fa-solid fa-bed"></i>
                                    </div>
                                    <div class="room-select-btn__title">Выбрать номер</div>
                                    <div class="room-select-btn__subtitle">Выберите лучший вариант для вас</div>
                                </div>
                                <div class="room-select-btn__body">
                                    <div class="room-select-btn__price-area">
                                        <span class="room-select-btn__price-label">От</span>
                                        <span class="room-select-btn__price-value">
                                            @if($service?->is_per_person)
                                                {{ currency($service->current_price_per_person ?? $service?->price_per_person) }}
                                            @else
                                                {{ currency($service->current_price ?? $service->discount_price ?? $service->full_price) }}
                                            @endif
                                            <span class="room-select-btn__price-unit">/ ночь</span>
                                        </span>
                                    </div>
                                    <div class="room-select-btn__arrow">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </div>
                                </div>
                            </a>
                        @else
                            <a href="{{ route('front.tourbooking.guest-booking-form', $service->slug) }}" class="room-select-btn" data-aos="fade-left">
                                <div class="room-select-btn__header">
                                    <div class="room-select-btn__icon-wrap">
                                        <i class="fa-solid fa-calendar-check"></i>
                                    </div>
                                    <div class="room-select-btn__title">Забронировать</div>
                                    <div class="room-select-btn__subtitle">Гарантируйте своё место</div>
                                </div>
                                <div class="room-select-btn__body">
                                    <div class="room-select-btn__price-area">
                                        <span class="room-select-btn__price-label">От</span>
                                        <span class="room-select-btn__price-value">
                                            @if($service?->is_per_person)
                                                {{ currency($service->current_price_per_person ?? $service?->price_per_person) }}
                                            @else
                                                {{ currency($service->current_price ?? $service->discount_price ?? $service->full_price) }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="room-select-btn__arrow">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </div>
                                </div>
                            </a>
                        @endif

                        {{-- 1.2 PRICE LIST (PDF) --}}
                        @if($service->priceLists && $service->priceLists->count() > 0)
                            <div class="pricelist-card" data-aos="fade-left" data-aos-delay="40">
                                <div class="pricelist-card__head">
                                    <div class="pricelist-card__icon">
                                        <i class="fa-solid fa-file-invoice-dollar"></i>
                                    </div>
                                    <div>
                                        <div class="pricelist-card__title">Прайс-лист</div>
                                        <div class="pricelist-card__subtitle">Скачайте актуальные цены</div>
                                    </div>
                                </div>
                                <div class="pricelist-card__body">
                                    @foreach($service->priceLists as $priceList)
                                        <a href="{{ asset($priceList->file_path) }}" target="_blank" rel="noopener" class="pricelist-btn">
                                            <span class="pricelist-btn__icon"><i class="fa-solid fa-file-pdf"></i></span>
                                            <span class="pricelist-btn__label">{{ $priceList->label }}</span>
                                            <span class="pricelist-btn__download"><i class="fa-solid fa-download"></i></span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                       {{-- 1.5 DOCTOR / CONSULTANT CARD --}}
                        @if($service->doctor_name)
                            <div class="sidebar-doctor" data-aos="fade-left" data-aos-delay="50">
                                {{-- Top gradient --}}
                                <div class="sidebar-doctor__top">
                                    <div class="sidebar-doctor__badge">
                                        <i class="fa-solid fa-headset"></i> Поддержка
                                    </div>
                                    <h5 class="sidebar-doctor__heading">Служба поддержки клиентов</h5>
                                </div>

                                {{-- Body --}}
                                <div class="sidebar-doctor__body">
                                    <div class="sidebar-doctor__photo-wrap">
                                        @if($service->doctor_image)
                                            <img src="{{ asset($service->doctor_image) }}" alt="{{ $service->doctor_name }}" class="sidebar-doctor__photo">
                                        @else
                                            <div class="sidebar-doctor__photo-placeholder">
                                                <i class="fa-solid fa-user-doctor"></i>
                                            </div>
                                        @endif
                                        <div class="sidebar-doctor__online"></div>
                                    </div>

                                    <h6 class="sidebar-doctor__name">{{ $service->doctor_name }}</h6>

                                    @if($service->doctor_position)
                                        <p class="sidebar-doctor__position">{{ $service->doctor_position }}</p>
                                    @endif

                                    @if($service->doctor_description)
                                        <p class="sidebar-doctor__description">{{ $service->doctor_description }}</p>
                                    @endif

                                    <div class="sidebar-doctor__actions">
                                        <button type="button" class="sidebar-doctor__btn-primary" onclick="openDoctorModal()">
                                            <i class="fa-solid fa-paper-plane"></i>
                                            Написать консультанту
                                        </button>
                                        <a href="{{ $service->consultant_button_url ?? 'https://gosanatorium.com/custom-page/consultant' }}" 
                                           class="sidebar-doctor__btn-secondary" target="_blank">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            Подробнее о консультации
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- Contact Form Modal --}}
                            <div class="doctor-modal-overlay" id="doctorModal">
                                <div class="doctor-modal">
                                    <div class="doctor-modal__header">
                                        <h5 class="doctor-modal__header-title">Связаться с консультантом</h5>
                                        <button class="doctor-modal__close" onclick="closeDoctorModal()">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                    <div class="doctor-modal__body">
                                        {{-- Error --}}
                                        <div class="doctor-modal__error" id="doctorFormError">
                                            <i class="fa-solid fa-circle-exclamation"></i>
                                            <span id="doctorFormErrorText"></span>
                                        </div>

                                        {{-- Form --}}
                                        <div id="doctorFormContent">
                                            <div class="doctor-modal__field">
                                                <label class="doctor-modal__label">Ваше имя *</label>
                                                <input type="text" class="doctor-modal__input" id="doctor_form_name" placeholder="Иван Иванов" required>
                                            </div>

                                            <div class="doctor-modal__field">
                                                <label class="doctor-modal__label">Телефон *</label>
                                                <div class="phone-input-group">
                                                    <select class="phone-country-select" id="doctor_form_country_code">
                                                        <option value="+994">🇦🇿 +994</option>
                                                        <option value="+7">🇷🇺 +7</option>
                                                        <option value="+90">🇹🇷 +90</option>
                                                        <option value="+380">🇺🇦 +380</option>
                                                        <option value="+995">🇬🇪 +995</option>
                                                        <option value="+998">🇺🇿 +998</option>
                                                        <option value="+996">🇰🇬 +996</option>
                                                        <option value="+993">🇹🇲 +993</option>
                                                        <option value="+992">🇹🇯 +992</option>
                                                        <option value="+375">🇧🇾 +375</option>
                                                        <option value="+77">🇰🇿 +77</option>
                                                        <option value="+49">🇩🇪 +49</option>
                                                        <option value="+44">🇬🇧 +44</option>
                                                        <option value="+33">🇫🇷 +33</option>
                                                        <option value="+1">🇺🇸 +1</option>
                                                        <option value="+971">🇦🇪 +971</option>
                                                        <option value="+972">🇮🇱 +972</option>
                                                        <option value="+39">🇮🇹 +39</option>
                                                        <option value="+34">🇪🇸 +34</option>
                                                        <option value="+48">🇵🇱 +48</option>
                                                    </select>
                                                    <input type="tel" class="phone-number-input" id="doctor_form_phone" placeholder="50 123 45 67" autocomplete="tel">
                                                </div>
                                            </div>

                                            <div class="doctor-modal__field">
                                                <label class="doctor-modal__label">Email</label>
                                                <input type="email" class="doctor-modal__input" id="doctor_form_email" placeholder="email@example.com">
                                            </div>

                                            <div class="doctor-modal__field">
                                                <label class="doctor-modal__label">Сообщение</label>
                                                <textarea class="doctor-modal__input doctor-modal__textarea" id="doctor_form_message" placeholder="Помогите подобрать санаторий..."></textarea>
                                            </div>

                                            <button type="button" class="doctor-modal__submit" id="doctorSubmitBtn" onclick="submitDoctorForm()">
                                                <span class="spinner"></span>
                                                <span class="btn-text"><i class="fa-solid fa-paper-plane"></i> Отправить заявку</span>
                                            </button>
                                        </div>

                                        {{-- Success --}}
                                        <div class="doctor-modal__success" id="doctorFormSuccess">
                                            <div class="doctor-modal__success-icon">
                                                <i class="fa-solid fa-check"></i>
                                            </div>
                                            <h5>Заявка отправлена!</h5>
                                            <p>Наш консультант свяжется с вами в ближайшее время.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif


                        {{-- 1.6 VIDEO CARD --}}
                        @php
                            $sbVideoUrl = trim((string) $service->video_url);
                            $sbVideoEmbed = null;
                            $sbVideoThumb = null;
                            if (preg_match('~(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/)|youtu\.be/)([A-Za-z0-9_-]{11})~i', $sbVideoUrl, $m)) {
                                $sbVideoEmbed = 'https://www.youtube.com/embed/' . $m[1];
                                $sbVideoThumb = 'https://img.youtube.com/vi/' . $m[1] . '/maxresdefault.jpg';
                            } elseif (preg_match('~vimeo\.com/(?:video/)?(\d+)~i', $sbVideoUrl, $m)) {
                                $sbVideoEmbed = 'https://player.vimeo.com/video/' . $m[1];
                                $sbVideoThumb = 'https://vumbnail.com/' . $m[1] . '.jpg';
                            }
                        @endphp
                        @if($sbVideoEmbed)
                            <div class="sidebar-video" id="section-video" data-aos="fade-left" data-aos-delay="75">
                                <div class="sidebar-video__badge">🎬 Видео</div>
                                <button type="button" class="sidebar-video__thumb" onclick="openSanVideo('{{ $sbVideoEmbed }}')" aria-label="Смотреть видео">
                                    <img src="{{ $sbVideoThumb }}" alt="Видео" loading="lazy"
                                         onerror="if(this.dataset.fb!=='1'&&this.src.indexOf('maxresdefault')>-1){this.dataset.fb='1';this.src=this.src.replace('maxresdefault','hqdefault');}">
                                    <span class="sidebar-video__play"><i class="fa-solid fa-play"></i></span>
                                </button>
                                <p class="sidebar-video__caption">Виртуальный тур по санаторию</p>
                            </div>
                            <div class="video-modal-overlay" id="sanVideoModal" onclick="if(event.target===this)closeSanVideo()">
                                <button type="button" class="video-modal-overlay__close" onclick="closeSanVideo()" aria-label="Закрыть">✕</button>
                                <div class="video-modal-overlay__frame"><iframe id="sanVideoIframe" src="" allow="autoplay; encrypted-media; fullscreen" allowfullscreen></iframe></div>
                            </div>
                            <script>
                                function openSanVideo(url) {
                                    document.getElementById('sanVideoIframe').src = url + (url.indexOf('?') > -1 ? '&' : '?') + 'autoplay=1';
                                    document.getElementById('sanVideoModal').classList.add('open');
                                }
                                function closeSanVideo() {
                                    document.getElementById('sanVideoModal').classList.remove('open');
                                    document.getElementById('sanVideoIframe').src = '';
                                }
                                document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeSanVideo(); });
                            </script>
                        @endif

                        {{-- 2. ЧТО ВКЛЮЧЕНО --}}
                        @if($service?->included || $service?->excluded)
                            <div class="sidebar-included" data-aos="fade-left" data-aos-delay="100" id="section-included">
                                <div class="sidebar-included__header">
                                    <div class="sidebar-included__icon">
                                        <i class="fa-solid fa-list-check"></i>
                                    </div>
                                    <h5 class="sidebar-included__title">Что включено</h5>
                                </div>
                                <div class="sidebar-included__grid">
                                    @if($service?->included)
                                        <div class="sidebar-included__section-title sidebar-included__section-title--yes">
                                            <i class="fa-solid fa-circle-check"></i> Включено
                                        </div>
                                        @php
                                            $includedData = $translation->included ?? $service->included;
                                            $includedIsArray = false;
                                            $includedArray = [];

                                            if (is_array($includedData)) {
                                                $includedIsArray = true;
                                                $includedArray = $includedData;
                                            } elseif (is_string($includedData)) {
                                                $decoded = json_decode($includedData, true);
                                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                    $includedIsArray = true;
                                                    $includedArray = $decoded;
                                                }
                                            }
                                        @endphp

                                        @if($includedIsArray)
                                            <ul class="sidebar-included__list">
                                                @foreach($includedArray as $item)
                                                    @php
                                                        $cleanItem = is_string($item) ? trim(strip_tags($item)) : '';
                                                        // Remove &nbsp; and other invisible chars
                                                        $cleanItem = preg_replace('/^(\s|\xC2\xA0|&nbsp;|&#160;)*$/u', '', $cleanItem);
                                                    @endphp
                                                    @if(!empty($cleanItem))
                                                        <li class="sidebar-included__item sidebar-included__item--yes">
                                                            <span class="sidebar-included__check sidebar-included__check--yes">
                                                                <i class="fa-solid fa-check"></i>
                                                            </span>
                                                            {!! strip_tags(is_string($item) ? $item : '', '<strong><b><em><i>') !!}
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @else
                                            @php
                                                // Clean TinyMCE HTML: remove empty li, flatten nested ul
                                                $cleanIncluded = $includedData;
                                                if (is_string($cleanIncluded)) {
                                                    // DEBUG: Uncomment next line to see raw HTML
                                                    // echo '<pre style="background:#fff;color:#000;padding:10px;border:2px solid red;font-size:11px;max-height:300px;overflow:auto;">' . htmlspecialchars($cleanIncluded) . '</pre>';

                                                    // Step 1: Remove li with only whitespace/nbsp/br (multi-pass for safety)
                                                    for ($i = 0; $i < 3; $i++) {
                                                        $cleanIncluded = preg_replace('/<li[^>]*>(\s|&nbsp;|&#160;|\xC2\xA0|<br\s*\/?>|\r|\n|\t)*<\/li>/iu', '', $cleanIncluded);
                                                    }
                                                    // Step 2: Unwrap nested li>ul - remove the wrapper li, keep inner ul content
                                                    for ($i = 0; $i < 5; $i++) {
                                                        $before = $cleanIncluded;
                                                        $cleanIncluded = preg_replace('/<li[^>]*>\s*(<ul[^>]*>)(.*?)(<\/ul>)\s*<\/li>/is', '$1$2$3', $cleanIncluded);
                                                        $cleanIncluded = preg_replace('/<li[^>]*>\s*(<ol[^>]*>)(.*?)(<\/ol>)\s*<\/li>/is', '$1$2$3', $cleanIncluded);
                                                        if ($before === $cleanIncluded) break;
                                                    }
                                                    // Step 3: Merge adjacent/nested ul tags
                                                    $cleanIncluded = preg_replace('/<\/ul>\s*<ul[^>]*>/i', '', $cleanIncluded);
                                                    $cleanIncluded = preg_replace('/<ul[^>]*>\s*<ul[^>]*>/i', '<ul>', $cleanIncluded);
                                                    $cleanIncluded = preg_replace('/<\/ul>\s*<\/ul>/i', '</ul>', $cleanIncluded);
                                                    // Step 4: Final cleanup - empty li and empty ul
                                                    for ($i = 0; $i < 3; $i++) {
                                                        $cleanIncluded = preg_replace('/<li[^>]*>(\s|&nbsp;|&#160;|\xC2\xA0|<br\s*\/?>|\r|\n|\t)*<\/li>/iu', '', $cleanIncluded);
                                                    }
                                                    $cleanIncluded = preg_replace('/<ul[^>]*>\s*<\/ul>/i', '', $cleanIncluded);
                                                    $cleanIncluded = preg_replace('/<ol[^>]*>\s*<\/ol>/i', '', $cleanIncluded);
                                                    $cleanIncluded = trim($cleanIncluded);
                                                }
                                            @endphp
                                            <div class="sidebar-included__html">
                                                {!! $cleanIncluded !!}
                                            </div>
                                        @endif
                                    @endif

                                    @if($service?->excluded)
                                        <div class="sidebar-included__section-title sidebar-included__section-title--no">
                                            <i class="fa-solid fa-circle-xmark"></i> Не включено
                                        </div>
                                        @php
                                            $excludedData = $translation->excluded ?? $service->excluded;
                                            $excludedIsArray = false;
                                            $excludedArray = [];

                                            if (is_array($excludedData)) {
                                                $excludedIsArray = true;
                                                $excludedArray = $excludedData;
                                            } elseif (is_string($excludedData)) {
                                                $decoded = json_decode($excludedData, true);
                                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                    $excludedIsArray = true;
                                                    $excludedArray = $decoded;
                                                }
                                            }
                                        @endphp

                                        @if($excludedIsArray)
                                            <ul class="sidebar-included__list">
                                                @foreach($excludedArray as $item)
                                                    @php
                                                        $cleanItem = is_string($item) ? trim(strip_tags($item)) : '';
                                                        $cleanItem = preg_replace('/^(\s|\xC2\xA0|&nbsp;|&#160;)*$/u', '', $cleanItem);
                                                    @endphp
                                                    @if(!empty($cleanItem))
                                                        <li class="sidebar-included__item sidebar-included__item--no">
                                                            <span class="sidebar-included__check sidebar-included__check--no">
                                                                <i class="fa-solid fa-xmark"></i>
                                                            </span>
                                                            {!! strip_tags(is_string($item) ? $item : '', '<strong><b><em><i>') !!}
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @else
                                            @php
                                                $cleanExcluded = $excludedData;
                                                if (is_string($cleanExcluded)) {
                                                    for ($i = 0; $i < 3; $i++) {
                                                        $cleanExcluded = preg_replace('/<li[^>]*>(\s|&nbsp;|&#160;|\xC2\xA0|<br\s*\/?>|\r|\n|\t)*<\/li>/iu', '', $cleanExcluded);
                                                    }
                                                    for ($i = 0; $i < 5; $i++) {
                                                        $before = $cleanExcluded;
                                                        $cleanExcluded = preg_replace('/<li[^>]*>\s*(<ul[^>]*>)(.*?)(<\/ul>)\s*<\/li>/is', '$1$2$3', $cleanExcluded);
                                                        $cleanExcluded = preg_replace('/<li[^>]*>\s*(<ol[^>]*>)(.*?)(<\/ol>)\s*<\/li>/is', '$1$2$3', $cleanExcluded);
                                                        if ($before === $cleanExcluded) break;
                                                    }
                                                    $cleanExcluded = preg_replace('/<\/ul>\s*<ul[^>]*>/i', '', $cleanExcluded);
                                                    $cleanExcluded = preg_replace('/<ul[^>]*>\s*<ul[^>]*>/i', '<ul>', $cleanExcluded);
                                                    $cleanExcluded = preg_replace('/<\/ul>\s*<\/ul>/i', '</ul>', $cleanExcluded);
                                                    for ($i = 0; $i < 3; $i++) {
                                                        $cleanExcluded = preg_replace('/<li[^>]*>(\s|&nbsp;|&#160;|\xC2\xA0|<br\s*\/?>|\r|\n|\t)*<\/li>/iu', '', $cleanExcluded);
                                                    }
                                                    $cleanExcluded = preg_replace('/<ul[^>]*>\s*<\/ul>/i', '', $cleanExcluded);
                                                    $cleanExcluded = preg_replace('/<ol[^>]*>\s*<\/ol>/i', '', $cleanExcluded);
                                                    $cleanExcluded = trim($cleanExcluded);
                                                }
                                            @endphp
                                            <div class="sidebar-included__html sidebar-included__html--excluded">
                                                {!! $cleanExcluded !!}
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- 3. AMENITIES --}}
                        @if(isset($amenities) && count($amenities) > 0)
                            <div class="sidebar-amenities" data-aos="fade-left" data-aos-delay="150">
                                <div class="sidebar-amenities__header">
                                    <div class="sidebar-amenities__icon">
                                        <i class="fa-solid fa-concierge-bell"></i>
                                    </div>
                                    <h5 class="sidebar-amenities__title">Удобства</h5>
                                </div>
                                <ul class="sidebar-amenities__list">
                                    @foreach($amenities as $amenity)
                                        <li class="sidebar-amenities__tag">
                                            <i class="fa-solid fa-check"></i>
                                            {{ $amenity?->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- 3.5 MAP --}}
                        @if($service?->google_map_url)
                            <div class="sidebar-included" data-aos="fade-left" data-aos-delay="170" id="section-map">
                                <div class="sidebar-included__header">
                                    <div class="sidebar-included__icon" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);">
                                        <i class="fa-solid fa-location-dot" style="color: #2563eb;"></i>
                                    </div>
                                    <h5 class="sidebar-included__title">Расположение</h5>
                                </div>
                                @if($service?->google_map_sub_title)
                                    <p style="font-size: 14px; color: var(--text-secondary); margin-bottom: 14px;">{{ $service?->google_map_sub_title }}</p>
                                @endif
                                <div class="map-frame" style="margin-top: 0;">
                                    {!! $service?->google_map_url !!}
                                </div>
                            </div>
                        @endif

                        {{-- 4. CONTACT --}}
                        <div class="sidebar-contact" data-aos="fade-left" data-aos-delay="200">
                            <p class="sidebar-contact__text">
                                <i class="fa-solid fa-headset"></i>
                                Нужна помощь с бронированием?
                            </p>
                            <a href="{{ url('contact-us') }}" class="sidebar-contact__link">
                                <i class="fa-solid fa-envelope"></i>
                                Свяжитесь с нами
                            </a>
                        </div>

                    </div>
                </div>
                {{-- ========== END SIDEBAR ========== --}}

            </div>
        </div>
    </section>

    {{-- POPULAR SERVICES --}}
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

   {{-- MOBILE APP TAB BAR --}}
    <div class="mobile-app-tabbar" id="mobileAppTabbar">
        <button type="button" class="mat-tab active" data-target="#section-about" id="tabAbout">
            <i class="fa-regular fa-compass"></i>
            <span>Описание</span>
        </button>

        @if(isset($service->treatmentItems) && $service->treatmentItems->where('status', true)->count() > 0)
            <button type="button" class="mat-tab" data-target="#section-treatment" id="tabTreatment">
                <i class="fa-regular fa-spa"></i>
                <span>Лечебная</span>
            </button>
        @elseif($service?->itineraries && $service?->itineraries->count() > 0)
            <button type="button" class="mat-tab" data-target="#section-itinerary" id="tabItinerary">
                <i class="fa-regular fa-map"></i>
                <span>Программа</span>
            </button>
        @endif

        <div class="mat-tab mat-tab--center">
            @if($hasRooms)
                <a href="{{ route('front.tourbooking.rooms.selection', $service->slug) }}" class="mat-tab--center-inner">
                    <div class="mat-fab">
                        <i class="fa-solid fa-bed"></i>
                    </div>
                    <span>Бронь</span>
                </a>
            @else
                <a href="{{ route('front.tourbooking.guest-booking-form', $service->slug) }}" class="mat-tab--center-inner">
                    <div class="mat-fab">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <span>Бронь</span>
                </a>
            @endif
        </div>

        @if($service?->included || $service?->excluded)
            <button type="button" class="mat-tab" data-target="#section-included" id="tabIncluded2">
                <i class="fa-regular fa-list-check"></i>
                <span>Что включено</span>
            </button>
        @endif

        @if($service?->google_map_url)
            <button type="button" class="mat-tab" data-target="#section-map" id="tabMap">
                <i class="fa-regular fa-map-location-dot"></i>
                <span>Расположение</span>
            </button>
        @elseif(!($service?->included || $service?->excluded))
            <button type="button" class="mat-tab" data-target="#section-reviews" id="tabReviews">
                <i class="fa-regular fa-star"></i>
                <span>Отзывы</span>
            </button>
        @endif
    </div>

    {{-- ========== STICKY LEFT SIDEBAR NAV - ALWAYS EXPANDED ========== --}}
    <style>
        #stickyLeftNav {
            position: fixed !important;
            left: 0 !important;
            top: 50% !important;
            transform: translateY(-50%) translateX(-10px) !important;
            z-index: 999999 !important;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.4s cubic-bezier(0.4, 0, 0.2, 1), transform 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        #stickyLeftNav.show-nav {
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(-50%) translateX(0) !important;
        }
        #stickyLeftNav ul {
            list-style: none !important;
            padding: 10px 0 !important;
            margin: 0 !important;
            background: rgba(255, 255, 255, 0.97) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            border-radius: 0 14px 14px 0 !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08), 0 1px 4px rgba(0,0,0,0.04) !important;
            border: 1px solid rgba(226, 232, 240, 0.5) !important;
            border-left: none !important;
        }
        #stickyLeftNav ul li { margin: 0 !important; }
        #stickyLeftNav ul li a {
            display: flex !important;
            align-items: center !important;
            padding: 11px 16px !important;
            color: #64748b !important;
            text-decoration: none !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            font-family: 'DM Sans', sans-serif !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            border-left: 3px solid transparent !important;
            white-space: nowrap !important;
        }
        #stickyLeftNav ul li a i,
        #stickyLeftNav ul li a svg {
            font-size: 16px !important;
            min-width: 20px !important;
            width: 20px !important;
            height: 20px !important;
            text-align: center !important;
            flex-shrink: 0 !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        #stickyLeftNav ul li a .nav-label {
            opacity: 1 !important;
            max-width: 200px !important;
            margin-left: 12px !important;
            font-size: 13px !important;
            letter-spacing: 0.01em !important;
        }
        /* Push main content to the right so sidebar doesn't overlap */
        @media (min-width: 992px) {
            .main-section > .container,
            .features-strip > .container,
            .gallery > .container,
            .popular-section > .container {
                margin-left: 180px !important;
                max-width: calc(1400px - 60px) !important;
            }
        }
        @media (min-width: 992px) and (max-width: 1400px) {
            .main-section > .container,
            .features-strip > .container,
            .gallery > .container,
            .popular-section > .container {
                margin-left: 180px !important;
                margin-right: 20px !important;
            }
        }
        #stickyLeftNav ul li a:hover {
            color: #0f766e !important;
            background: linear-gradient(90deg, rgba(13, 148, 136, 0.08), transparent) !important;
            border-left-color: #14b8a6 !important;
        }
        #stickyLeftNav ul li a.active-link {
            color: #0d9488 !important;
            background: linear-gradient(90deg, rgba(13, 148, 136, 0.1), rgba(13, 148, 136, 0.02)) !important;
            border-left-color: #0d9488 !important;
            font-weight: 600 !important;
        }
        #stickyLeftNav ul li a.active-link i,
        #stickyLeftNav ul li a.active-link svg {
            transform: scale(1.1) !important;
            color: #0d9488 !important;
        }
        /* Separator line */
        #stickyLeftNav ul li.nav-separator {
            height: 1px !important;
            margin: 4px 12px !important;
            background: linear-gradient(90deg, transparent, rgba(148, 163, 184, 0.3), transparent) !important;
        }
        @media (max-width: 991px) {
            #stickyLeftNav { display: none !important; }
        }
    </style>

    <div id="stickyLeftNav">
        <ul>
            <li><a href="#section-about" class="active-link" data-target="section-about" data-tooltip="О санатории"><i class="fa-solid fa-compass"></i><span class="nav-label">О санатории</span></a></li>
            <li><a href="#section-treatment" data-target="section-treatment" data-tooltip="Лечение"><i class="fa-solid fa-heart-pulse"></i><span class="nav-label">Лечение</span></a></li>
            @if(!empty($sbVideoEmbed))
            <li><a href="#section-video" data-target="section-video" data-tooltip="Видео"><i class="fa-solid fa-clapperboard"></i><span class="nav-label">Видео</span></a></li>
            @endif
            <li><a href="#section-itinerary" data-target="section-itinerary" data-tooltip="FAQ"><i class="fa-solid fa-circle-question"></i><span class="nav-label">FAQ</span></a></li>
            <li class="nav-separator"></li>
            <li><a href="#section-map" data-target="section-map" data-tooltip="Расположение"><i class="fa-solid fa-map-location-dot"></i><span class="nav-label">Расположение</span></a></li>
            <li><a href="#section-reviews" data-target="section-reviews" data-tooltip="Отзывы"><i class="fa-solid fa-star"></i><span class="nav-label">Отзывы</span></a></li>
            <li><a href="#section-included" data-target="section-included" data-tooltip="Включено"><i class="fa-solid fa-clipboard-check"></i><span class="nav-label">Включено</span></a></li>
        </ul>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var nav = document.getElementById('stickyLeftNav');
        if (!nav) return;

        var allLinks = nav.querySelectorAll('a[data-target]');
        var sectionData = [];
        allLinks.forEach(function(link) {
            var targetId = link.getAttribute('data-target');
            var targetEl = document.getElementById(targetId);
            if (targetEl) sectionData.push({ el: targetEl, link: link });
        });

        function onScroll() {
            if (window.pageYOffset > 250) {
                nav.classList.add('show-nav');
            } else {
                nav.classList.remove('show-nav');
            }
            var current = sectionData[0];
            for (var i = 0; i < sectionData.length; i++) {
                var rect = sectionData[i].el.getBoundingClientRect();
                if (rect.top <= 160) current = sectionData[i];
            }
            allLinks.forEach(function(l) { l.classList.remove('active-link'); });
            if (current) current.link.classList.add('active-link');
        }

        window.addEventListener('scroll', onScroll);
        onScroll();

        allLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                var targetId = this.getAttribute('data-target');
                var target = document.getElementById(targetId);
                if (target) {
                    var y = target.getBoundingClientRect().top + window.pageYOffset - 80;
                    window.scrollTo({ top: y, behavior: 'smooth' });
                }
            });
        });
    });
    </script>
    {{-- ========== END STICKY LEFT SIDEBAR NAV ========== --}}

</main>
@endsection

@push('js_section')
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ru.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        
     (function() {
    "use strict";

    // ===== Phone number auto-format =====
    var phoneInput = document.getElementById('doctor_form_phone');
    var countrySelect = document.getElementById('doctor_form_country_code');

    // Format masks per country code
    var phoneMasks = {
        '+994':  { mask: '## ### ## ##',   maxDigits: 9  },
        '+7':    { mask: '### ###-##-##',  maxDigits: 10 },
        '+90':   { mask: '### ### ## ##',  maxDigits: 10 },
        '+380':  { mask: '## ### ## ##',   maxDigits: 9  },
        '+995':  { mask: '### ## ## ##',   maxDigits: 9  },
        '+998':  { mask: '## ### ## ##',   maxDigits: 9  },
        '+996':  { mask: '### ### ###',    maxDigits: 9  },
        '+993':  { mask: '## ## ## ##',    maxDigits: 8  },
        '+992':  { mask: '## ### ## ##',   maxDigits: 9  },
        '+375':  { mask: '## ###-##-##',   maxDigits: 9  },
        '+77':   { mask: '## ### ## ##',   maxDigits: 9  },
        '+49':   { mask: '### #######',    maxDigits: 10 },
        '+44':   { mask: '#### ######',    maxDigits: 10 },
        '+33':   { mask: '# ## ## ## ##',  maxDigits: 9  },
        '+1':    { mask: '### ###-####',   maxDigits: 10 },
        '+971':  { mask: '## ### ####',    maxDigits: 9  },
        '+972':  { mask: '## ### ####',    maxDigits: 9  },
        '+39':   { mask: '### ### ####',   maxDigits: 10 },
        '+34':   { mask: '### ### ###',    maxDigits: 9  },
        '+48':   { mask: '### ### ###',    maxDigits: 9  },
    };

    function getActiveMask() {
        var code = countrySelect ? countrySelect.value : '+994';
        return phoneMasks[code] || { mask: '## ### ## ##', maxDigits: 9 };
    }

    function formatPhone(value) {
        var digits = value.replace(/\D/g, '');
        var config = getActiveMask();
        digits = digits.substring(0, config.maxDigits);
        
        var mask = config.mask;
        var result = '';
        var digitIndex = 0;

        for (var i = 0; i < mask.length && digitIndex < digits.length; i++) {
            if (mask[i] === '#') {
                result += digits[digitIndex];
                digitIndex++;
            } else {
                result += mask[i];
            }
        }

        return result;
    }

    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            var cursorPos = this.selectionStart;
            var oldVal = this.value;
            var formatted = formatPhone(this.value);
            this.value = formatted;

            // Cursor pozisiyasını düzəlt
            var diff = formatted.length - oldVal.length;
            var newPos = cursorPos + diff;
            if (newPos < 0) newPos = 0;
            this.setSelectionRange(newPos, newPos);
        });

        phoneInput.addEventListener('keydown', function(e) {
            // Yalnız rəqəmlərə, backspace, delete, tab, arrows icazə ver
            var allowed = ['Backspace','Delete','Tab','ArrowLeft','ArrowRight','Home','End'];
            if (allowed.indexOf(e.key) !== -1) return;
            if (e.ctrlKey || e.metaKey) return;
            if (!/^\d$/.test(e.key)) {
                e.preventDefault();
            }
        });

        // Paste zamanı formatla
        phoneInput.addEventListener('paste', function(e) {
            e.preventDefault();
            var pasted = (e.clipboardData || window.clipboardData).getData('text');
            this.value = formatPhone(pasted);
        });
    }

    if (countrySelect) {
        countrySelect.addEventListener('change', function() {
            if (phoneInput) {
                phoneInput.value = formatPhone(phoneInput.value);
                var config = getActiveMask();
                phoneInput.placeholder = config.mask.replace(/#/g, '0');
            }
        });

        // İlkin placeholder set et
        var initConfig = getActiveMask();
        if (phoneInput) phoneInput.placeholder = initConfig.mask.replace(/#/g, '0');
    }

    // ===== Modal functions =====
 window.openDoctorModal = function() {
    window._savedScrollY = window.scrollY;
    document.body.classList.add('doctor-modal-open');
    document.body.style.position = 'fixed';
    document.body.style.top = '-' + window._savedScrollY + 'px';
    document.body.style.left = '0';
    document.body.style.right = '0';
    document.body.style.overflow = 'hidden';
    document.getElementById('doctorModal').classList.add('active');
};

// SCROLL SAFETY NET — body/html heç bir kodla scroll-blocked qalmasın
(function() {
    function ensureScrollable() {
        var body = document.body;
        var html = document.documentElement;
        if (!body.classList.contains('doctor-modal-open') &&
            !body.classList.contains('glightbox-open') &&
            !body.classList.contains('mobile-menu-visible')) {
            if (body.style.position === 'fixed') body.style.position = '';
            if (body.style.overflow === 'hidden') body.style.overflow = '';
            if (body.style.top && body.style.top !== '') body.style.top = '';
            if (html.style.overflow === 'hidden') html.style.overflow = '';
        }
    }
    setInterval(ensureScrollable, 1000);
    document.addEventListener('DOMContentLoaded', ensureScrollable);
    window.addEventListener('load', ensureScrollable);
})();

window.closeDoctorModal = function() {
    document.getElementById('doctorModal').classList.remove('active');
    document.body.classList.remove('doctor-modal-open');
    document.body.style.position = '';
    document.body.style.top = '';
    document.body.style.left = '';
    document.body.style.right = '';
    document.body.style.overflow = '';
    window.scrollTo(0, window._savedScrollY || 0);
};

    // Close on overlay click
    var overlay = document.getElementById('doctorModal');
    if (overlay) {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) closeDoctorModal();
        });
    }

    // Close on Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDoctorModal();
    });

    // ===== Submit form via AJAX =====
    window.submitDoctorForm = function() {
        var name = document.getElementById('doctor_form_name').value.trim();
        var countryCode = document.getElementById('doctor_form_country_code').value;
        var phone = document.getElementById('doctor_form_phone').value.trim();
        var email = document.getElementById('doctor_form_email').value.trim();
        var message = document.getElementById('doctor_form_message').value.trim();
        var errorEl = document.getElementById('doctorFormError');
        var errorText = document.getElementById('doctorFormErrorText');
        var submitBtn = document.getElementById('doctorSubmitBtn');

        // Hide previous error
        errorEl.classList.remove('active');

        // Validation
        if (!name) {
            errorText.textContent = 'Пожалуйста, введите ваше имя';
            errorEl.classList.add('active');
            return;
        }
        if (!phone || phone.replace(/\D/g, '').length < 5) {
            errorText.textContent = 'Пожалуйста, введите корректный номер телефона';
            errorEl.classList.add('active');
            return;
        }

        // Full phone
        var fullPhone = countryCode + ' ' + phone;

        // Loading state
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;

        // CSRF token
        var token = document.querySelector('meta[name="csrf-token"]');
        var csrfToken = token ? token.getAttribute('content') : '';

        fetch('{{ route("front.tourbooking.consultant.contact.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                name: name,
                phone: fullPhone,
                email: email,
                message: message,
                service_name: '{{ $service->translation->title ?? $service->title }}',
                doctor_name: '{{ $service->doctor_name }}'
            })
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;

            if (data.success) {
                document.getElementById('doctorFormContent').style.display = 'none';
                errorEl.classList.remove('active');
                document.getElementById('doctorFormSuccess').classList.add('active');

                setTimeout(function() {
                    closeDoctorModal();
                    setTimeout(function() {
                        document.getElementById('doctorFormContent').style.display = 'block';
                        document.getElementById('doctorFormSuccess').classList.remove('active');
                        document.getElementById('doctor_form_name').value = '';
                        document.getElementById('doctor_form_phone').value = '';
                        document.getElementById('doctor_form_email').value = '';
                        document.getElementById('doctor_form_message').value = '';
                    }, 300);
                }, 3000);
            } else {
                errorText.textContent = data.message || 'Произошла ошибка. Попробуйте позже.';
                errorEl.classList.add('active');
            }
        })
        .catch(function(err) {
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;
            errorText.textContent = 'Ошибка сети. Попробуйте позже.';
            errorEl.classList.add('active');
        });
    };
})();
        // Fix: Clean TinyMCE nested lists in included/excluded sections
        (function() {
            document.querySelectorAll('.sidebar-included__html').forEach(function(container) {
                function hasRealText(el) {
                    var text = (el.textContent || '').replace(/\u00A0/g, ' ').trim();
                    return text.length > 0;
                }
                // 1. Flatten ALL nested ul/ol
                var nested = container.querySelectorAll('ul ul, ol ol');
                for (var i = nested.length - 1; i >= 0; i--) {
                    var nul = nested[i];
                    var pli = nul.parentNode;
                    var pul = pli.parentNode;
                    if (pul) {
                        while (nul.firstChild) pul.insertBefore(nul.firstChild, pli);
                        nul.remove();
                    }
                }
                // 2. Remove empty li (including &nbsp; only)
                container.querySelectorAll('li').forEach(function(li) {
                    if (!li.querySelector('img, svg') && !hasRealText(li)) li.remove();
                });
                // 3. Remove empty ul/ol
                container.querySelectorAll('ul, ol').forEach(function(list) {
                    if (!list.querySelector('li')) list.remove();
                });
            });
        })();

        // AOS Init - disable on mobile for snappy feel
        const isMobile = window.innerWidth <= 991;
        AOS.init({
            duration: isMobile ? 0 : 700,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50,
            disable: isMobile
        });

        // GLightbox with thumbnail strip
        const lightbox = GLightbox({
            touchNavigation: true,
            loop: true,
            autoplayVideos: true
        });

        // Thumbnail strip for lightbox
        (function() {
            var galleryItems = document.querySelectorAll('.gallery__item.glightbox[data-gallery="gallery"]');
            if (galleryItems.length < 2) return;

            // Collect all image URLs (visible + hidden)
            var allUrls = [];
            document.querySelectorAll('.glightbox[data-gallery="gallery"]').forEach(function(el) {
                var href = el.getAttribute('href');
                if (href) allUrls.push(href);
            });
            if (allUrls.length < 2) return;

            // Add CSS for thumbnail strip
            var styleEl = document.createElement('style');
            styleEl.textContent = [
                '#glightbox-thumbs::-webkit-scrollbar{display:none}',
                '#glightbox-thumbs{scrollbar-width:none}',
                '.gslide-description{display:none !important}',
                '.goverlay{background:rgba(0,0,0,0.95) !important}',
                'body.glightbox-open #stickyLeftNav{display:none !important}',
                // Şəkili yuxarı qaldır ki altda thumbnail-a yer olsun
                '.glightbox-clean .gslide-media{max-height:calc(100vh - 120px) !important}',
                '.glightbox-clean .gslide-image img{max-height:calc(100vh - 120px) !important}',
                // Thumbnail wrapper - şəkilin altında, ekranın aşağısında
                '#glightbox-thumbs-wrapper{position:fixed;bottom:12px;left:0;right:0;z-index:1000000;display:flex;justify-content:center;pointer-events:none;transition:opacity 0.15s ease}',
                '#glightbox-thumbs-wrapper>*{pointer-events:auto}'
            ].join(' ');
            document.head.appendChild(styleEl);

            var strip = null;
            var wrapper = null;

            function ensureStrip() {
                if (strip) return;
                strip = document.createElement('div');
                strip.id = 'glightbox-thumbs';
                strip.style.cssText = 'display:flex;gap:6px;padding:8px 14px;background:rgba(255,255,255,0.08);backdrop-filter:blur(16px);border-radius:12px;max-width:88vw;overflow-x:auto;';

                allUrls.forEach(function(url, idx) {
                    var thumb = document.createElement('img');
                    thumb.src = url;
                    thumb.dataset.index = idx;
                    thumb.style.cssText = 'width:64px;height:44px;object-fit:cover;border-radius:6px;cursor:pointer;opacity:0.4;transition:all 0.25s ease;border:2px solid transparent;flex-shrink:0;';
                    thumb.addEventListener('click', function(e) {
                        e.stopPropagation();
                        lightbox.goToSlide(parseInt(this.dataset.index));
                    });
                    strip.appendChild(thumb);
                });

                wrapper = document.createElement('div');
                wrapper.id = 'glightbox-thumbs-wrapper';
                wrapper.addEventListener('click', function(e) { e.stopPropagation(); });
                wrapper.appendChild(strip);
                wrapper.style.opacity = '0';
                document.body.appendChild(wrapper);
            }

            function showStrip() {
                if (!wrapper) return;
                wrapper.style.display = 'flex';
                wrapper.style.opacity = '1';
            }

            function hideStrip() {
                if (!wrapper) return;
                wrapper.style.opacity = '0';
                wrapper.style.display = 'none';
            }

            function updateThumbs(idx) {
                if (!strip) return;
                var thumbs = strip.querySelectorAll('img');
                thumbs.forEach(function(t, i) {
                    if (i === idx) {
                        t.style.opacity = '1';
                        t.style.borderColor = '#3fc8c3';
                        t.style.transform = 'scale(1.12)';
                        t.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                    } else {
                        t.style.opacity = '0.4';
                        t.style.borderColor = 'transparent';
                        t.style.transform = 'scale(1)';
                    }
                });
            }

            lightbox.on('open', function() {
                document.body.classList.add('glightbox-open');
                ensureStrip();
                setTimeout(function() {
                    showStrip();
                    updateThumbs(lightbox.index);
                }, 100);
            });

            lightbox.on('slide_changed', function(data) {
                updateThumbs(data.current.index);
            });

            lightbox.on('close', function() {
                hideStrip();
                document.body.classList.remove('glightbox-open');
            });
        })();

        // Itinerary toggle
        function toggleItinerary(el) {
            const item = el.closest('.itinerary-item');
            document.querySelectorAll('.itinerary-item.active').forEach(i => {
                if (i !== item) i.classList.remove('active');
            });
            item.classList.toggle('active');
        }

        // ═══════════════════════════════════════════════════════════════
        // MOBILE APP BEHAVIORS
        // ═══════════════════════════════════════════════════════════════

        // 1. Floating header scroll behavior
        (function() {
            const header = document.getElementById('mobileFloatingHeader');
            if (!header) return;

            let lastScroll = 0;
            const heroEl = document.querySelector('.hero');

            window.addEventListener('scroll', function() {
                const scrollY = window.scrollY;
                const heroBottom = heroEl ? heroEl.offsetHeight - 80 : 300;

                if (scrollY > heroBottom) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }

                lastScroll = scrollY;
            }, { passive: true });
        })();

        // 2. Gallery horizontal scroll indicators
        (function() {
            const grid = document.getElementById('galleryGrid');
            const indicators = document.getElementById('galleryIndicators');
            if (!grid || !indicators || window.innerWidth > 991) return;

            const dots = indicators.querySelectorAll('.gallery-indicators__dot');
            const items = grid.querySelectorAll('.gallery__item:not(.d-none)');

            grid.addEventListener('scroll', function() {
                const scrollLeft = grid.scrollLeft;
                const itemWidth = items[0]?.offsetWidth || 1;
                const gap = 4;
                const activeIndex = Math.round(scrollLeft / (itemWidth + gap));

                dots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === activeIndex);
                });
            }, { passive: true });
        })();

        // 3. Mobile tabs - active state on scroll
        (function() {
            if (window.innerWidth > 991) return;

            const tabs = document.querySelectorAll('.mobile-tabs__item');
            const sections = [];

            tabs.forEach(tab => {
                const target = tab.getAttribute('href');
                if (target && target.startsWith('#')) {
                    const el = document.querySelector(target);
                    if (el) sections.push({ tab, el });
                }
            });

            // Smooth scroll on tap
            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        const tabsHeight = document.getElementById('mobileTabs')?.offsetHeight || 50;
                        const y = target.getBoundingClientRect().top + window.scrollY - tabsHeight - 10;
                        window.scrollTo({ top: y, behavior: 'smooth' });
                    }
                });
            });

            // Highlight active tab on scroll
            let ticking = false;
            window.addEventListener('scroll', function() {
                if (!ticking) {
                    requestAnimationFrame(function() {
                        const tabsHeight = document.getElementById('mobileTabs')?.offsetHeight || 50;
                        const scrollPos = window.scrollY + tabsHeight + 60;

                        let activeSection = sections[0];
                        for (const section of sections) {
                            if (section.el.offsetTop <= scrollPos) {
                                activeSection = section;
                            }
                        }

                        tabs.forEach(t => t.classList.remove('active'));
                        if (activeSection) activeSection.tab.classList.add('active');

                        ticking = false;
                    });
                    ticking = true;
                }
            }, { passive: true });
        })();

        // 4. Haptic feedback for interactive elements (if available)
        (function() {
            if (!('vibrate' in navigator) || window.innerWidth > 991) return;

            document.querySelectorAll('.mobile-bottom-bar__btn, .mfh-btn, .mobile-tabs__item').forEach(el => {
                el.addEventListener('touchstart', function() {
                    navigator.vibrate(10);
                }, { passive: true });
            });
        })();

        // Review form (Alpine.js)
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
                    // Haptic on rate
                    if ('vibrate' in navigator) navigator.vibrate(15);
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
        
        
        // ═══════════════════════════════════════════════════════════════
// MOBILE: Sidebar-ı xəritədən əvvəl göstər
// ═══════════════════════════════════════════════════════════════
(function() {
    try {
        if (window.innerWidth > 991) return;

        const bentoGrid = document.querySelector('.bento-grid');
        const sidebar = document.querySelector('.booking-sidebar');
        if (!bentoGrid || !sidebar) return;

        // Anchor yalnız bentoGrid-in birbaşa övladı ola bilər
        const reviewsSection = document.getElementById('section-reviews');
        const anchor = (reviewsSection && reviewsSection.parentNode === bentoGrid)
            ? reviewsSection
            : null;

        const sidebarStack = sidebar.querySelector('.sidebar-stack');
        if (sidebarStack) {
            const children = Array.from(sidebarStack.children);
            children.forEach(child => {
                if (child.classList.contains('room-select-btn')) return;
                if (child.classList.contains('doctor-modal-overlay')) return;

                try {
                    if (anchor && anchor.parentNode === bentoGrid) {
                        bentoGrid.insertBefore(child, anchor);
                    } else {
                        bentoGrid.appendChild(child);
                    }
                } catch (err) {
                    // Hər hansı insert xətası qalan JS-i bloklamasın
                    bentoGrid.appendChild(child);
                }
            });
        }

        if (sidebar.querySelector('.sidebar-stack') &&
            sidebar.querySelector('.sidebar-stack').children.length <= 1) {
            sidebar.style.display = 'none';
        }
    } catch (err) {
        console.warn('Sidebar reorder skipped:', err);
    }
})();
       // ═══════════════════════════════════════════════════════════════
// MOBILE APP TAB BAR - Navigation & Active State
// ═══════════════════════════════════════════════════════════════
(function() {
    if (window.innerWidth > 991) return;

    const tabbar = document.getElementById('mobileAppTabbar');
    if (!tabbar) return;

    const tabs = tabbar.querySelectorAll('.mat-tab[data-target]');
    const scrollSections = [];
    const sidebarTargets = ['#section-included', '#section-amenities'];
    let manualActive = null;
    let manualTimer = null;

    tabs.forEach(tab => {
        const target = tab.getAttribute('data-target');
        if (target) {
            const el = document.querySelector(target);
            if (el) {
                // Sidebar-dakı section-ları scroll tracking-dən xaric et
                if (sidebarTargets.indexOf(target) === -1) {
                    scrollSections.push({ tab, el });
                }
            }
        }
    });

    // Event delegation - tabbar-a click və touchend dinləyicisi
    function handleTabInteraction(e) {
        const tab = e.target.closest('.mat-tab[data-target]');
        if (!tab || !tabbar.contains(tab)) return;

        e.preventDefault();
        e.stopPropagation();

        const targetSel = tab.getAttribute('data-target');
        const target = targetSel ? document.querySelector(targetSel) : null;
        if (!target) return;

        if ('vibrate' in navigator) {
            try { navigator.vibrate(10); } catch(_) {}
        }

        const y = target.getBoundingClientRect().top + window.scrollY - 20;
        window.scrollTo({ top: y, behavior: 'smooth' });

        tabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        manualActive = tab;
        clearTimeout(manualTimer);
        manualTimer = setTimeout(function() {
            manualActive = null;
        }, 2000);
    }

    tabbar.addEventListener('click', handleTabInteraction);
    tabbar.addEventListener('touchend', handleTabInteraction, { passive: false });

    // Active state on scroll
    let ticking = false;
    window.addEventListener('scroll', function() {
        if (!ticking) {
            requestAnimationFrame(function() {
                // Manual click aktiv olsa scroll override etməsin
                if (manualActive) { ticking = false; return; }

                const scrollPos = window.scrollY + 120;
                let activeSection = scrollSections[0];
                for (const section of scrollSections) {
                    if (section.el.offsetTop <= scrollPos) {
                        activeSection = section;
                    }
                }
                tabs.forEach(t => t.classList.remove('active'));
                if (activeSection) activeSection.tab.classList.add('active');
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true });
})();
    </script>
@endpush