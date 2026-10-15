{{-- ===== Mobil alt menyu (yalnız telefonda/mobil görünür) ===== --}}
@php
    $__mbnServices = url('tour-booking/services') . '?service_type_ids%5B%5D=10';
    $__mbnConsult  = url('custom-page/consultant');
@endphp
<nav class="tg-mobile-bottom-nav">
    <a href="{{ $__mbnServices }}" class="tg-mbn-item">
        <i class="fa-solid fa-hospital"></i>
        <span>Санатории</span>
    </a>
    <a href="{{ $__mbnServices }}" class="tg-mbn-item tg-mbn-center">
        <span class="tg-mbn-center-btn"><i class="fa-solid fa-calendar-check"></i></span>
        <span>Забронировать</span>
    </a>
    <a href="{{ $__mbnConsult }}" class="tg-mbn-item">
        <i class="fa-solid fa-headset"></i>
        <span>Консультация</span>
    </a>
</nav>
<style>
    .tg-mobile-bottom-nav { display: none; }
    @media (max-width: 991px) {
        .tg-mobile-bottom-nav {
            display: flex;
            position: fixed;
            left: 0; right: 0; bottom: 0;
            height: 62px;
            background: #fff;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.12);
            z-index: 9998;
            align-items: center;
            justify-content: space-around;
            padding: 0 8px;
        }
        .tg-mbn-item {
            flex: 1 1 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 3px;
            color: #555 !important;
            text-decoration: none !important;
            font-size: 11px;
            line-height: 1.1;
            text-align: center;
        }
        .tg-mbn-item i { font-size: 18px; }
        .tg-mbn-item span { font-size: 11px; }
        .tg-mbn-item:active { opacity: .7; }

        /* Ortadakı "Забронировать" — böyük dairəvi, yuxarı çıxan düymə */
        .tg-mbn-center { position: relative; }
        .tg-mbn-center-btn {
            width: 56px; height: 56px;
            border-radius: 50%;
            background: var(--tg-theme-primary, #560ce3);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            margin-top: -34px;
            margin-bottom: 2px;
            box-shadow: 0 6px 18px rgba(86, 12, 227, 0.45);
            border: 4px solid #fff;
        }
        .tg-mbn-center-btn i { font-size: 22px; color: #fff; }
        .tg-mbn-center > span:last-child {
            color: var(--tg-theme-primary, #560ce3) !important;
            font-weight: 600;
        }

        /* Alt menyu səhifə içeriyini örtməsin */
        body { padding-bottom: 62px !important; }
        /* Yuxarı-qayıt düyməsi menyudan yuxarı olsun */
        .scroll__top { bottom: 74px !important; }
    }
</style>
