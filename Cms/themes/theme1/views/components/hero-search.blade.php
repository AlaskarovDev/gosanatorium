@php
    // Yeni dizayn: slider (karusel) əvəzinə statik fon şəkli + minimalist "pill" axtarış paneli.
    // FORMA seçimləri KÖHNƏSİ ilə eynidir — theme1-in öz booking-form komponenti
    // (tab-lar: Санаторий/Отель/Трансферы, autocomplete "Курорт, СПА Отель, Диагноз",
    // "Заезд, Выезд" tarix aralığı, "Поиск" düyməsi). Yalnız GÖRÜNÜŞ dəyişir:
    // hamısı tək, yığcam, yuvarlaq ağ pill-in içində; tab-lar isə fon üstündə üzən
    // minimalist pill-düymələr kimidir.
    $home1_hero_section = getContent('theme1_slider.content', true);
    $translatedSlides = getTranslatedSlides($home1_hero_section, 'slides');
    $hero_bg = count($translatedSlides) > 0 ? ($translatedSlides[0]['background_image'] ?? null) : null;

@endphp

@if ($home1_hero_section)
    <!-- tg-hero-static-area-start -->
    {{-- Fon şəkli qəsdən YOXDUR: yalnız yaşıl gradient fon. --}}
    <div class="tg-hero-static p-relative">
        <div class="tg-hero-static-overlay"></div>

        @php $hero_ru = app()->getLocale() === 'ru'; @endphp
        <div class="hero-headline hero-text-block p-relative z-index-1">
            <h1 class="hero-title">{{ $hero_ru ? 'Забронируйте санаторий — оплатите по прибытии' : 'Book a sanatorium — pay on arrival' }}</h1>
            <p class="hero-subtitle">{{ $hero_ru ? 'Нафталан · Боржоми · Цхалтубо · Саирме — бронирование бесплатно, консультация врача бесплатно' : 'Naftalan · Borjomi · Tskaltubo · Sairme — free booking, free doctor consultation' }}</p>
        </div>

        {{-- Axtarış forması (bütün seçimlər) — minimalist pill görünüşündə --}}
        <div class="tg-hero-static-form p-relative z-index-1">
            @include('theme::components.booking-form')
        </div>
    </div>
    <!-- tg-hero-static-area-end -->
@endif

@push('style_section')
<style>
    /* ============================================================
       STATİK HERO (slider əvəzinə)
       ============================================================ */
    .tg-hero-static {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 680px;     /* hero sahəsi kiçilməsin — aşağıdakı bölmələr yuxarı qalxmasın */
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background: linear-gradient(135deg, #1a9e87 0%, #2ec4a9 50%, #17b09a 100%) !important;
        padding-top: 165px;    /* header + formanı bir az aşağı sal */
        padding-bottom: 60px;
        overflow: visible;
    }
    .tg-hero-static-form { width: 100%; }
    .tg-hero-static .hero-headline { text-align: center; color: #fff; margin: 0 auto; max-width: 720px; padding: 48px 16px 28px 16px; }
    .hero-badge { display: inline-block; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: #fff; font-size: 13px; padding: 6px 16px; border-radius: 20px; margin-bottom: 16px; letter-spacing: 0.5px; }
    .tg-hero-static .hero-title { display: block; font-size: 36px !important; font-weight: 800; line-height: 1.25; margin-bottom: 14px; color: #ffffff; text-shadow: 0 2px 10px rgba(0,0,0,0.18); }
    .tg-hero-static .hero-subtitle { display: block; font-size: 17px; font-weight: 700; line-height: 1.6; color: #ffffff; margin-bottom: 0; text-shadow: 0 1px 6px rgba(0,0,0,0.15); }
    .hero-trust { display: flex; gap: 24px; justify-content: center; flex-wrap: wrap; }
    .hero-trust span { font-size: 14px; color: rgba(255,255,255,0.9); font-weight: 500; }
    @media (max-width: 768px) {
        .tg-hero-static .hero-title { font-size: 24px !important; }
        .tg-hero-static .hero-subtitle { font-size: 15px; }
        .tg-hero-static .hero-headline { padding: 36px 16px 20px 16px; }
        .hero-trust { gap: 12px; }
    }
    @media (max-width: 480px) {
        .tg-hero-static .hero-title { font-size: 22px !important; }
        .hero-trust span { font-size: 12px; }
    }
    .tg-hero-static-overlay {
        position: absolute;
        inset: 0;
        background: transparent;
        z-index: 1;
    }
    .tg-hero-static .z-index-1 { z-index: 2; }
    .tg-hero-static .tg-hero-title,
    .tg-hero-static .tg-hero-subtitle,
    .tg-hero-static .tg-hero-price-wrap p { color: #fff; }
    /* Üstdəki mətn daha kiçik, az yer tutsun; panel yuxarı qalxsın */
    .tg-hero-static .tg-hero-title { font-size: 42px !important; line-height: 1.15; margin-bottom: 6px; }
    .tg-hero-static .tg-hero-subtitle { font-size: 15px; }
    .tg-hero-static-price { margin-top: 6px; margin-bottom: 16px; }

    /* ============================================================
       KÖHNƏ FORMANI MİNİMALİST PILL-Ə ÇEVİR
       ============================================================ */

    /* Ağ kart / kölgə / padding — hamısını sil (yanlardakı ağ boşluqlar getsin) */
    .tg-hero-static .tg-booking-form-area { padding-bottom: 0 !important; }
    .tg-hero-static .tg-booking-form-space { margin-top: 0 !important; z-index: 5 !important; }
    .tg-hero-static .tg-booking-form-wrap {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
    }

    /* ---- Tab-lar: fon üstündə üzən minimalist pill-düymələr ---- */
    .tg-hero-static .tg-booking-form-tabs {
        border-bottom: none !important;
        background: transparent !important;
        margin-bottom: 16px !important;
        padding: 0 !important;
    }
    .tg-hero-static .tg-booking-form-tabs .nav-tab {
        justify-content: center;
        gap: 8px;
    }
    .tg-hero-static .tg-booking-form-tabs .nav-links {
        padding: 8px 20px !important;
        border-radius: 30px;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.35);
        color: #fff !important;
        font-weight: 500;
        font-size: 14px;
        transition: all .25s ease;
    }
    .tg-hero-static .tg-booking-form-tabs .nav-links:hover {
        background: rgba(255, 255, 255, 0.28);
    }
    .tg-hero-static .tg-booking-form-tabs .nav-links.active {
        background: #fff;
        color: var(--tg-theme-primary) !important;
        border-color: #fff;
    }
    .tg-hero-static .tg-booking-form-tabs .nav-links .borders { display: none !important; }
    .tg-hero-static .tg-booking-form-tabs .nav-links .icon { margin-right: 6px; }

    /* ---- Axtarış sahələri: tək ağ pill ---- */
    .tg-hero-static .tg-booking-form-item {
        background: #fff;
        border-radius: 55px;
        padding: 6px 8px 6px 10px;
        box-shadow: 0 14px 40px rgba(0, 0, 0, 0.22);
        max-width: 1040px;
        margin: 0 auto;
    }
    .tg-hero-static .tg-booking-form-input-group {
        flex-wrap: nowrap !important;
        align-items: center !important;
        justify-content: flex-start !important;
        margin: 0 !important;
    }

    /* Hər sahə — sərhədsiz, şəffaf, ayırıcı ilə */
    .tg-hero-static .tg-booking-form-parent-inner {
        margin: 0 !important;
        padding: 6px 24px;
        flex: 1 1 auto;
        min-width: 0;
        max-width: none !important;
    }
    .tg-hero-static .tg-autocomplete-parent { flex: 1.5 1 0; }
    .tg-hero-static .tg-daterange-parent {
        flex: 1 1 0;
        /* ayırıcı xətt silindi — sərhədsiz görünüş */
    }
    .tg-hero-static .tg-autocomplete-field,
    .tg-hero-static .tg-autocomplete-field:hover,
    .tg-hero-static .tg-autocomplete-field:focus-within,
    .tg-hero-static .tg-date-field,
    .tg-hero-static .tg-date-field:hover,
    .tg-hero-static .tg-date-field:focus-within,
    .tg-hero-static .tg-booking-form-parent-inner input,
    .tg-hero-static .tg-autocomplete-input,
    .tg-hero-static .daterange-picker,
    .tg-hero-static .single-date-picker {
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        background: transparent !important;
    }
    .tg-hero-static .tg-autocomplete-input,
    .tg-hero-static .daterange-picker,
    .tg-hero-static .single-date-picker {
        height: 30px !important;
        padding: 0 25px 0 0 !important;
        font-size: 15px;
    }
    .tg-hero-static .tg-date-icon { right: 0; }
    .tg-hero-static .tg-autocomplete-arrow { right: 0; }
    .tg-hero-static .tg-booking-form-title {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 1px !important;
    }

    /* ---- "Поиск" düyməsi: bənövşəyi yuvarlaq ---- */
    .tg-hero-static .tg-booking-form-search-btn {
        margin: 0 !important;
        flex: 0 0 auto;
    }
    .tg-hero-static .bk-search-button {
        background: var(--tg-theme-primary) !important;
        border-radius: 50px !important;
        padding: 16px 34px !important;
        font-size: 15px;
        white-space: nowrap;
    }

    /* Autocomplete dropdown pill-in altında düzgün yerləşsin */
    .tg-hero-static .tg-autocomplete-dropdown { top: calc(100% + 12px); }

    /* ============================================================
       RESPONSIVE
       ============================================================ */
    @media (max-width: 991px) {
        .tg-hero-static { min-height: 580px; padding-top: 130px; padding-bottom: 50px; }
    }
    @media (max-width: 767px) {
        .tg-hero-static { min-height: auto; }
        .tg-hero-static .tg-booking-form-item {
            border-radius: 22px;
            padding: 14px;
        }
        .tg-hero-static .tg-booking-form-input-group { flex-wrap: wrap !important; }
        .tg-hero-static .tg-booking-form-parent-inner {
            flex: 1 1 100% !important;
            padding: 12px 14px;
            border-left: none !important;
            border-bottom: 1px solid #eee;
        }
        .tg-hero-static .tg-daterange-parent { border-left: none !important; }
        .tg-hero-static .tg-booking-form-search-btn {
            flex: 1 1 100%;
            padding: 12px 14px 0 !important;
        }
        .tg-hero-static .bk-search-button { width: 100%; }
    }
    @media (max-width: 575px) {
        .tg-hero-static { padding-top: 120px; }
        .tg-hero-static .tg-hero-title { font-size: 28px !important; }
        .tg-hero-static .tg-booking-form-tabs .nav-links {
            padding: 7px 16px !important;
            font-size: 13px;
        }
    }
</style>
@endpush
