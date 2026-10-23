<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-NKJSXJL4');</script>
    <!-- End Google Tag Manager -->

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon Icon -->
    <link rel="shortcut icon" href="{{ asset($general_setting->favicon) }}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Site Title -->
    @include('partials.seo_head')

    <!-- Müasir Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/flatpicker.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/odometer.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/default.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/dev.css') }}?v={{ @filemtime(public_path('frontend/assets/css/dev.css')) }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/cookie_consent.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/custom.css') }}?v={{ @filemtime(public_path('frontend/assets/css/custom.css')) }}">

    <link rel="stylesheet" href="{{ asset('global/toastr/toastr.min.css') }}">

    {{-- ========== CUSTOM HEADER CSS ========== --}}
    <style>
    /* ===== Logo-Menyu boşluğunu azaltma ===== */
    .tgmenu__nav.tgmenu-1-space.ml-30 {
        margin-left: 30px !important;
    }

    /* ===== Menyu yazılarına müasir font ===== */
    .tgmenu__main-menu li a,
    .tgmenu__navbar-wrap li a {
        font-family: 'Poppins', sans-serif !important;
        font-weight: 600 !important;
        letter-spacing: 0.3px;
        text-transform: none;
    }

    /* Social Contact Icons Container */
    .tg-header-social-contacts {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-right: 14px;
        padding-right: 14px;
        border-right: 1px solid rgba(0, 0, 0, 0.1);
    }

    /* Individual Social Icon Base */
    .tg-social-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        transition: all 0.3s ease;
        text-decoration: none !important;
        font-size: 15px;
        color: #fff !important;
    }

    .tg-social-icon:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
    }

    /* WhatsApp */
    .tg-social-icon.tg-whatsapp { background: #25D366 !important; }
    .tg-social-icon.tg-whatsapp:hover { background: #1da851 !important; }

    /* Telegram */
    .tg-social-icon.tg-telegram { background: #0088cc !important; }
    .tg-social-icon.tg-telegram:hover { background: #006699 !important; }

    /* VKontakte */
    .tg-social-icon.tg-vk { background: #4C75A3 !important; }
    .tg-social-icon.tg-vk:hover { background: #3b6291 !important; }

    /* OK (Odnoklassniki) */
    .tg-social-icon.tg-ok { background: #EE8208 !important; }
    .tg-social-icon.tg-ok:hover { background: #d67205 !important; }

    /* Email */
    .tg-social-icon.tg-email { background: #EA4335 !important; }
    .tg-social-icon.tg-email:hover { background: #d33426 !important; }

    /* ===== RESPONSIVE — keep all elements, just compact spacing ===== */
    .tgmenu__main-menu,
    .tgmenu__main-menu .navigation,
    .tgmenu__navbar-wrap ul {
        flex-wrap: nowrap !important;
        white-space: nowrap !important;
    }
    .tgmenu__main-menu li,
    .tgmenu__navbar-wrap li {
        flex-shrink: 0;
        white-space: nowrap;
    }

    /* 1699px and below — tighter menu spacing for 15" / smaller HD laptops */
    @media (max-width: 1699px) {
        .tgmenu__main-menu li,
        .tgmenu__navbar-wrap li { margin-right: 6px !important; }
        .tgmenu__main-menu li a,
        .tgmenu__navbar-wrap li a { padding: 0 8px !important; }
    }

    /* 1499px and below — slightly smaller icons + tighter menu */
    @media (max-width: 1499px) {
        .tg-header-social-contacts { gap: 5px; margin-right: 10px; padding-right: 10px; }
        .tg-social-icon { width: 32px; height: 32px; font-size: 14px; }
        .tgmenu__nav.tgmenu-1-space.ml-30 { margin-left: 14px !important; }
        .tgmenu__main-menu li,
        .tgmenu__navbar-wrap li { margin-right: 4px !important; }
        .tgmenu__main-menu li a,
        .tgmenu__navbar-wrap li a { font-size: 14px !important; padding: 0 6px !important; }
        .tg-header-contact-number { font-size: 13px; }
        .tg-header-contact-number span { font-size: 11px; }
        .tg-currency-select, .tg-language-select { padding: 6px 22px 6px 8px; font-size: 13px; }
        .tg-header-btn .tg-btn-header { padding: 8px 14px; font-size: 13px; }
    }

    /* 1366px and below — tighter still, all elements remain visible */
    @media (max-width: 1366px) {
        .tg-header-social-contacts { gap: 4px; margin-right: 8px; padding-right: 8px; }
        .tg-social-icon { width: 28px; height: 28px; font-size: 12px; }
        .tgmenu__nav.tgmenu-1-space.ml-30 { margin-left: 10px !important; }
        .tgmenu__main-menu li,
        .tgmenu__navbar-wrap li { margin-right: 4px !important; padding: 0 !important; }
        .tgmenu__main-menu li a,
        .tgmenu__navbar-wrap li a {
            font-size: 13px !important;
            letter-spacing: 0 !important;
            padding: 0 6px !important;
        }
        .tg-header-contact-icon { display: none !important; }
        .tg-header-contact-number { font-size: 12px; line-height: 1.2; }
        .tg-header-contact-number span { font-size: 10px; }
        .tg-currency-select, .tg-language-select { padding: 5px 18px 5px 6px; font-size: 12px; min-width: 60px; }
        .tg-header-btn .tg-btn-header { padding: 7px 12px; font-size: 12px; }
        .tg-header-btn.ml-20 { margin-left: 10px !important; }
        .tg-header-menu-bar.ml-20.pl-20 { margin-left: 10px !important; padding-left: 10px !important; }
        .tg-currency-switcher.ml-15 { margin-left: 8px !important; }
        .tg-language-switcher.ml-10 { margin-left: 6px !important; }
    }

    /* Below 1200px (XL breakpoint) — mobile menu kicks in, hide socials and phone */
    @media (max-width: 1199px) {
        .tg-header-social-contacts { display: none !important; }
    }

    /* Give the left column more space on mid-XL screens */
    @media (min-width: 1200px) and (max-width: 1499px) {
        .tg-header__area .row > .col-lg-7 { flex: 0 0 58.333% !important; max-width: 58.333% !important; }
        .tg-header__area .row > .col-lg-5 { flex: 0 0 41.666% !important; max-width: 41.666% !important; }
    }

    /* Tooltip on hover */
    .tg-social-icon[title] { position: relative; }
    .tg-social-icon[title]:hover::after {
        content: attr(title);
        position: absolute;
        bottom: -30px;
        left: 50%;
        transform: translateX(-50%);
        background: #333;
        color: #fff;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        white-space: nowrap;
        z-index: 100;
    }
    .tg-social-icon[title]:hover::before {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        border: 5px solid transparent;
        border-bottom-color: #333;
        z-index: 100;
    }

    /* ===== Currency & Language Switcher — Shared Base ===== */
    .tg-currency-switcher,
    .tg-language-switcher {
        position: relative;
    }

    .tg-currency-select,
    .tg-language-select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background: #1a56db;
        border: 2px solid #1a56db;
        border-radius: 8px;
        padding: 8px 32px 8px 14px;
        font-size: 14px;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        color: #fff;
        cursor: pointer;
        outline: none;
        transition: all 0.3s ease;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
    }

    .tg-currency-select { min-width: 95px; }
    .tg-language-select { min-width: 75px; }

    .tg-currency-select option,
    .tg-language-select option {
        background: #fff;
        color: #333;
        font-weight: 600;
    }

    .tg-currency-select:hover,
    .tg-language-select:hover {
        background: #1648c0;
        border-color: #1648c0;
    }

    .tg-currency-select:focus,
    .tg-language-select:focus {
        background: #1648c0;
        border-color: #1648c0;
        box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.3);
    }

    /* Transparent + Sticky header */
    .tg-transparent .tg-currency-select,
    .tg-transparent .tg-language-select,
    #header-sticky.tg-sticky-active .tg-currency-select,
    #header-sticky.tg-sticky-active .tg-language-select {
        background: #1a56db;
        color: #fff;
        border-color: #1a56db;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
    }

    /* ===== MOBILE RESPONSIVE ===== */
    @media (max-width: 1199px) {
        .tg-currency-switcher,
        .tg-language-switcher {
            margin-left: 6px !important;
        }
        .tg-currency-select,
        .tg-language-select {
            padding: 5px 26px 5px 8px;
            font-size: 12px;
            min-width: 70px;
            border-radius: 6px;
        }
        .tg-language-select { min-width: 58px; }
    }

    @media (max-width: 767px) {
        .tg-currency-select,
        .tg-language-select {
            padding: 4px 22px 4px 6px;
            font-size: 11px;
            min-width: 60px;
        }
        .tg-language-select { min-width: 52px; }
        .tg-currency-switcher,
        .tg-language-switcher {
            margin-left: 4px !important;
        }
    }

    @media (max-width: 480px) {
        .tg-currency-select,
        .tg-language-select {
            padding: 3px 20px 3px 5px;
            font-size: 10px;
            min-width: 52px;
            border-radius: 5px;
        }
        .tg-language-select { min-width: 46px; }
    }
    </style>
    {{-- ========== END CUSTOM HEADER CSS ========== --}}

    @stack('style_section')

    <script>(function () { var widget = document.createElement('script'); widget.defer = true; widget.dataset.pfId = '8719c067-19f4-4029-b81e-a90b282e3a7f'; widget.src = 'https://widget.yourgood.app/script/widget.js?id=8719c067-19f4-4029-b81e-a90b282e3a7f&now='+Date.now(); document.head.appendChild(widget); })()</script>

    @if ($general_setting->google_analytic_status == 1)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $general_setting->google_analytic_id }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', '{{ $general_setting->google_analytic_id }}');
        </script>
    @endif


    @if ($general_setting->pixel_status == 1)
        <script>
            ! function(f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function() {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $general_setting->pixel_app_id }}');
            fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none"
                src="https://www.facebook.com/tr?id={{ $general_setting->pixel_app_id }}&ev=PageView&noscript=1" /></noscript>
    @endif

    @include('partials.hreflang')
    @include('partials.canonical')

    {{-- Google Ads (gtag.js) — AW-18190005715 --}}
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-18190005715"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'AW-18190005715');
    </script>

    {{-- Yandex.Metrika counter --}}
    <script type="text/javascript">
        (function(m,e,t,r,i,k,a){
            m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();
            for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
            k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
        })(window, document,'script','https://mc.webvisor.org/metrika/tag_ww.js?id=109569026', 'ym');

        ym(109569026, 'init', {ssr:true, webvisor:true, trackHash:true, clickmap:true, ecommerce:"dataLayer", referrer: document.referrer, url: location.href, accurateTrackBounce:true, trackLinks:true});
    </script>
    <noscript><div><img src="https://mc.webvisor.org/watch/109569026" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    {{-- /Yandex.Metrika counter --}}
</head>

<body class="td_theme_2">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NKJSXJL4"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    @if ($general_setting->preloader_status == 'enable')
        <!-- Start Preloader -->
        <div id="loading">
            <div class="loader"></div>
        </div>
        <!-- End Preloader -->
    @endif

    @if ($general_setting->preloader_status == 'enable')
        <!-- Scroll-top -->
        <button class="scroll__top scroll-to-target" data-target="html">
            <i class="fa-sharp fa-regular fa-arrow-up"></i>
        </button>
        <!-- Scroll-top-end-->
    @endif


    <!-- header-area -->
    <header class="tg-header-height">
        <div class="tg-header__area tg-header-lg-space z-index-999 tg-transparent" id="header-sticky">
            <div class="container-fluid container-1860">
                <div class="row align-items-center">
                    <div class="col-lg-7 col-5">
                        <div class="tgmenu__wrap d-flex align-items-center">
                            <div class="logo">
                                <a class="logo-1" href="{{ route('home') }}"><img
                                        src="{{ asset($general_setting->logo) }}" alt="Logo"></a>
                                <a class="logo-2 d-none" href="{{ route('home') }}"><img
                                        src="{{ asset($general_setting->secondary_logo) }}" alt="Logo"></a>
                            </div>
                            <nav class="tgmenu__nav tgmenu-1-space ml-30">
                                <div class="tgmenu__navbar-wrap tgmenu__main-menu d-none d-xl-flex">
                                    @include('components.common_navitems')
                                </div>
                            </nav>
                        </div>
                    </div>
                    <div class="col-lg-5 col-7">
                        <div class="tg-menu-right-action d-flex align-items-center justify-content-end">
                            
                            {{-- ========== SOCIAL CONTACT ICONS ========== --}}
                            @php
                                $phoneClean = $footer->phone ? preg_replace('/[^0-9]/', '', $footer->phone) : null;
                            @endphp
                            <div class="tg-header-social-contacts d-none d-xl-flex align-items-center">
                                @if($phoneClean)
                                <a href="https://wa.me/{{ $phoneClean }}" class="tg-social-icon tg-whatsapp" target="_blank" title="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                @endif
                                @if($phoneClean)
                                <a href="https://t.me/+{{ $phoneClean }}" class="tg-social-icon tg-telegram" target="_blank" title="Telegram">
                                    <i class="fab fa-telegram-plane"></i>
                                </a>
                                @endif
                                <a href="https://vk.com/gosanatorium" class="tg-social-icon tg-vk" target="_blank" rel="noopener" title="VK">
                                    <i class="fab fa-vk"></i>
                                </a>
                            </div>
                            {{-- ========== END SOCIAL CONTACT ICONS ========== --}}

                            <div class="tg-header-contact-info d-flex align-items-center">
                                <span class="tg-header-contact-icon mr-5 d-none d-xl-block">
                                    @include('svg.search_phone')
                                </span>
                                <div class="tg-header-contact-number d-none d-xl-block">
                                    <span>{{ __('translate.Call Us') }}:</span>
                                    <a href="tel:{{ $footer->phone }}">{{ $footer->phone }}</a>
                                </div>
                            </div>

                            {{-- ========== CURRENCY & LANGUAGE SWITCHERS ========== --}}
                            @php
                                $activeCurrencies = \Modules\Currency\App\Models\Currency::where('status', 'active')->get();
                                $sessionCurrency = session('currency');
                                $currentCode = $sessionCurrency ? $sessionCurrency['currency_code'] : null;
                                
                                if (!$currentCode) {
                                    $defaultCurr = $activeCurrencies->where('is_default', 'yes')->first();
                                    $currentCode = $defaultCurr ? $defaultCurr->currency_code : 'USD';
                                }

                                $activeLanguages = \Modules\Language\App\Models\Language::where('status', 1)->get();
                                $currentLang = session('lang_code', config('app.locale', 'en'));
                            @endphp

                            @if($activeCurrencies->count() > 1)
                            <div class="tg-currency-switcher d-flex align-items-center ml-15">
                                <select class="currency_code tg-currency-select" aria-label="{{ __('translate.Currency') }}">
                                    @foreach($activeCurrencies as $curr)
                                        <option value="{{ $curr->currency_code }}" 
                                            {{ $currentCode == $curr->currency_code ? 'selected' : '' }}>
                                            {{ $curr->currency_icon }} {{ $curr->currency_code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            @if($activeLanguages->count() > 1)
                            <div class="tg-language-switcher d-flex align-items-center ml-10">
                                <select class="language_code tg-language-select" aria-label="{{ __('translate.Language') }}">
                                    @foreach($activeLanguages as $lang)
                                        <option value="{{ $lang->lang_code }}" 
                                            {{ $currentLang == $lang->lang_code ? 'selected' : '' }}>
                                            {{ strtoupper($lang->lang_code) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            {{-- ========== END CURRENCY & LANGUAGE SWITCHERS ========== --}}
                        
                            <div class="tg-header-btn ml-20 d-none d-sm-block">
                                @auth('web')
                                    <a class="tg-btn-header"
                                        href="{{ Auth::guard('web')->user()->is_seller == 1 ? route('agency.dashboard') : route('user.dashboard') }}">
                                        <span>
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M1.7 17.2C1.5 17.2 1.3 17.1 1.2 17C1.1 16.8 1 16.7 1 16.5C1 15.1 1.4 13.7 2.1 12.4C2.8 11.2 3.9 10.1 5.1 9.4C4.6 8.8 4.2 8 4 7.2C3.9 6.4 3.9 5.5 4.1 4.8C4.3 4 4.8 3.2 5.3 2.6C5.9 2 6.6 1.5 7.3 1.3C7.9 1.1 8.5 1 9.1 1C9.3 1 9.6 1 9.8 1C10.6 1.1 11.4 1.4 12.1 1.9C12.8 2.4 13.3 3 13.7 3.7C14.1 4.4 14.3 5.2 14.3 6.1C14.3 7.3 13.9 8.5 13.1 9.4C13.7 9.8 14.3 10.2 14.9 10.7C15.7 11.5 16.2 12.3 16.7 13.3C17.1 14.3 17.3 15.3 17.3 16.4C17.3 16.6 17.2 16.8 17.1 16.9C17 17 16.8 17.1 16.6 17.1C16.5 17.1 16.4 17.1 16.3 17C16.2 17 16.1 16.9 16.1 16.8C16 16.7 16 16.7 15.9 16.6C15.9 16.5 15.8 16.4 15.8 16.3C15.8 15.4 15.6 14.6 15.3 13.8C15 13 14.5 12.3 13.8 11.7C13.2 11.2 12.6 10.7 11.9 10.4C11.1 10.9 10.2 11.2 9.1 11.2C8.1 11.2 7.1 10.9 6.3 10.4C5.2 10.9 4.2 11.7 3.5 12.8C2.8 13.9 2.4 15.1 2.4 16.4C2.4 16.6 2.3 16.8 2.2 16.9C2.1 17.1 1.9 17.2 1.7 17.2ZM9.1 2.5C8.4 2.5 7.7 2.7 7.1 3.1C6.4 3.5 6 4.1 5.7 4.7C5.4 5.4 5.3 6.1 5.5 6.9C5.6 7.6 6 8.3 6.5 8.8C7 9.3 7.7 9.7 8.4 9.8C8.6 9.8 8.9 9.9 9.1 9.9C9.6 9.9 10.1 9.8 10.5 9.6C11.2 9.3 11.7 8.9 12.2 8.2C12.6 7.6 12.8 6.9 12.8 6.2C12.8 5.2 12.4 4.3 11.7 3.6C11 2.8 10.1 2.5 9.1 2.5Z"
                                                    fill="currentColor" />
                                            </svg>
                                        </span>
                                        {{ __('translate.Dashboard') }}
                                    </a>
                                @endauth
                            </div>
                            <div class="tg-header-menu-bar lh-1 p-relative ml-20 pl-20">
                                <span class="tg-header-border d-none d-xl-block"></span>
                                <button class="tgmenu-offcanvas-open-btn menu-tigger d-none d-xl-block">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </button>
                                <button class="tgmenu-offcanvas-open-btn mobile-nav-toggler d-block d-xl-none">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu  -->
        @include('components.common_mobile_menu')
        <!-- End Mobile Menu -->
        @include('components.common_offcanvas')
    </header>
    <!-- header-area-end -->
    @yield('front-content')


    <!-- footer-area-start -->
    @include('partials.site_footer')
    <!-- footer-area-end -->

    {{-- Mobil alt menyu --}}
    @include('partials.mobile-bottom-nav')

    @if ($general_setting->tawk_status == 1)
        <script type="text/javascript">
            var Tawk_API = Tawk_API || {},
                Tawk_LoadStart = new Date();
            // Mobildə chat düyməsini alt menyunun üstünə qaldır (kompüterdə dəyişmir)
            Tawk_API.customStyle = {
                visibility: {
                    mobile: {
                        position: 'bl',
                        xOffset: 12,
                        yOffset: 74
                    }
                }
            };
            (function() {
                var s1 = document.createElement("script"),
                    s0 = document.getElementsByTagName("script")[0];
                s1.async = true;
                s1.src = '{{ $general_setting->tawk_chat_link }}';
                s1.charset = 'UTF-8';
                s1.setAttribute('crossorigin', '*');
                s0.parentNode.insertBefore(s1, s0);
            })();
        </script>
    @endif



    @if ($general_setting->cookie_consent_status == 1)
        <!-- common-modal start  -->
        <div class="common-modal cookie_consent_modal d-none bg-white">
            <button type="button" class="btn-close cookie_consent_close_btn" aria-label="Close"></button>

            <h5>{{ __('translate.Cookies') }}</h5>
            <p>{{ $general_setting->cookie_consent_message }}</p>


            <a href="javascript:;"
                class="td_btn td_style_1 td_type_3 td_radius_30 td_medium td_fs_14 report-modal-btn cookie_consent_accept_btn">
                <span class="td_btn_in td_accent_color">
                    <span>{{ __('translate.Accept') }}</span>
                </span>
            </a>

        </div>
        <!-- common-modal end  -->
    @endif


    <!-- Script -->
    <script src="{{ asset('global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jquery.odometer.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jquery.appear.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/flatpickr.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/nice-select.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/ajax-form.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/cart.js') }}?v={{ @filemtime(public_path('frontend/assets/js/cart.js')) }}"></script>
    <script src="{{ asset('frontend/assets/js/main.js') }}"></script>
    <script src="{{ asset('global/toastr/toastr.min.js') }}"></script>

    <script>
        (function($) {
            "use strict"
            $(document).ready(function() {

                const session_notify_message = @json(Session::get('message'));
                const demo_mode_message = @json(Session::get('demo_mode'));

                if (session_notify_message != null) {
                    const session_notify_type = @json(Session::get('alert-type', 'info'));
                    switch (session_notify_type) {
                        case 'info':
                            toastr.info(session_notify_message);
                            break;
                        case 'success':
                            toastr.success(session_notify_message);
                            break;
                        case 'warning':
                            toastr.warning(session_notify_message);
                            break;
                        case 'error':
                            toastr.error(session_notify_message);
                            break;
                    }
                }

                if (demo_mode_message != null) {
                    toastr.warning(
                        "{{ __('translate.All Language keywords are not implemented in the demo mode') }}"
                    );
                    toastr.info("{{ __('translate.Admin can translate every word from the admin panel') }}");
                }

                const validation_errors = @json($errors->all());

                if (validation_errors.length > 0) {
                    validation_errors.forEach(error => toastr.error(error));
                }

                if (localStorage.getItem('tourex-cookie') != '1') {
                    $('.cookie_consent_modal').removeClass('d-none');
                }

                $('.cookie_consent_close_btn').on('click', function() {
                    $('.cookie_consent_modal').addClass('d-none');
                });

                $('.cookie_consent_accept_btn').on('click', function() {
                    localStorage.setItem('tourex-cookie', '1');
                    $('.cookie_consent_modal').addClass('d-none');
                });

                $('.before_auth_wishlist').on("click", function() {
                    toastr.error("{{ __('translate.Please login first') }}")
                });

                $(".currency_code").on('change', function() {
                    var currency_code = $(this).val();

                    window.location.href = "{{ route('currency-switcher') }}" + "?currency_code=" +
                        currency_code;
                });

                $(".language_code").on('change', function() {
                    var language_code = $(this).val();

                    window.location.href = "{{ route('language-switcher') }}" + "?lang_code=" +
                        language_code;
                });

            });
        })(jQuery);
    </script>


    @stack('js_section')

{{-- Chat widget mobil fix - tabbar ilə overlap olmasın --}}
<style>
@media (max-width: 991px) {
    /* YourGood widget - force lift */
    div[data-pf-id],
    div[data-pf-id] > *,
    iframe[src*="yourgood"] {
        bottom: 80px !important;
    }
}
</style>

{{-- ═══ MOBILE: Chat widgetləri tabbar üstünə qaldır (Tawk + YourGood) ═══ --}}
<script>
(function() {
    if (window.innerWidth > 991) return;

    var OFFSET = 80;
    var SKIP_IDS = ['mobileAppTabbar','doctorModal','header-sticky','mobileTabs','mobileFloatingHeader','loading'];
    var SKIP_CLASSES = ['mobile-app-tabbar','mobile-floating-header','tg-header__area','tgmenu-offcanvas','cookie_consent_modal','doctor-modal-overlay','tg-header-height'];

    function shouldSkip(el) {
        if (!el || !el.tagName) return true;
        var tag = el.tagName.toLowerCase();
        if (tag === 'header' || tag === 'nav' || tag === 'script' || tag === 'link' || tag === 'style') return true;
        if (SKIP_IDS.indexOf(el.id) !== -1) return true;
        for (var i = 0; i < SKIP_CLASSES.length; i++) {
            if (el.classList && el.classList.contains(SKIP_CLASSES[i])) return true;
        }
        return false;
    }

    function liftElement(el) {
        if (shouldSkip(el)) return;
        try {
            var cs = window.getComputedStyle(el);
            if (cs.position === 'fixed') {
                var bot = parseInt(cs.bottom);
                if (!isNaN(bot) && bot >= 0 && bot < OFFSET) {
                    el.style.setProperty('bottom', OFFSET + 'px', 'important');
                }
            }
        } catch(e) {}
    }

    // === TAWK.TO — API ilə pozisiyanı dəyiş ===
    if (typeof window.Tawk_API !== 'undefined' || true) {
        window.Tawk_API = window.Tawk_API || {};
        
        // Tawk widgetinin hər açılıb-bağlanmasında yenidən qaldır
        window.Tawk_API.onLoad = function() {
            fixTawk();
        };
        window.Tawk_API.onChatMinimized = function() {
            // Chat bağlananda Tawk pozisiyanı resetləyir, 
            // ona görə delay ilə bir neçə dəfə düzəldirik
            setTimeout(fixTawk, 100);
            setTimeout(fixTawk, 300);
            setTimeout(fixTawk, 600);
            setTimeout(fixTawk, 1000);
            setTimeout(fixTawk, 2000);
        };
        window.Tawk_API.onChatMaximized = function() {
            // Chat açılarkən də yoxla
            setTimeout(fixTawk, 200);
        };
        window.Tawk_API.onChatHidden = function() {
            setTimeout(fixTawk, 100);
            setTimeout(fixTawk, 500);
        };
    }

    function fixTawk() {
        // Tawk-ın bütün fixed containerlarını tap
        var tawkEls = document.querySelectorAll(
            '[class*="tawk"], iframe[title*="chat" i], iframe[src*="tawk"]'
        );
        tawkEls.forEach(function(el) { liftElement(el); });
        
        // Tawk parent containerları da yoxla
        var iframes = document.querySelectorAll('iframe');
        iframes.forEach(function(iframe) {
            var src = iframe.src || '';
            var title = iframe.title || '';
            if (src.indexOf('tawk') > -1 || title.toLowerCase().indexOf('chat') > -1) {
                liftElement(iframe);
                // Parent da fixed ola bilər
                if (iframe.parentElement) liftElement(iframe.parentElement);
                if (iframe.parentElement && iframe.parentElement.parentElement) {
                    liftElement(iframe.parentElement.parentElement);
                }
            }
        });
    }

    // === YOURGOOD widget ===
    function fixYourGood() {
        // data-pf-id atributu olan elementlər
        var pfEls = document.querySelectorAll('[data-pf-id]');
        pfEls.forEach(function(el) { liftElement(el); });
        
        // yourgood iframe
        var ygIframes = document.querySelectorAll('iframe[src*="yourgood"]');
        ygIframes.forEach(function(el) {
            liftElement(el);
            if (el.parentElement) liftElement(el.parentElement);
        });

        // yourgood class/id olan elementlər
        document.querySelectorAll('[class*="yourgood"], [id*="yourgood"], [class*="pf-widget"]').forEach(function(el) {
            liftElement(el);
        });
    }

    // === GENERIC: Bütün fixed bottom elementləri ===
    function fixAllWidgets() {
        fixTawk();
        fixYourGood();
        
        // Body-nin birbaşa uşaqları - fixed olanlar
        var children = document.body.children;
        for (var i = 0; i < children.length; i++) {
            liftElement(children[i]);
        }
    }

    // Widgetlər müxtəlif vaxtlarda yüklənir
    [800, 1500, 2500, 4000, 6000, 8000, 12000].forEach(function(t) {
        setTimeout(fixAllWidgets, t);
    });

    // Hər 3 saniyədə yoxla (Tawk kimi widgetlər pozisiyanı dəyişə bilər)
    setInterval(fixAllWidgets, 3000);

    // DOM dəyişikliklərini izlə
    var observer = new MutationObserver(function(mutations) {
        // Yalnız əhəmiyyətli dəyişikliklərdə işlət
        var dominated = false;
        for (var i = 0; i < mutations.length; i++) {
            if (mutations[i].addedNodes.length > 0) {
                dominated = true;
                break;
            }
        }
        if (dominated) {
            setTimeout(fixAllWidgets, 300);
            setTimeout(fixAllWidgets, 800);
        }
    });
    observer.observe(document.body, { childList: true, subtree: true });

    // Scroll zamanı da yoxla (bəzi widgetlər scroll-da pozisiya dəyişir)
    var scrollTick = false;
    window.addEventListener('scroll', function() {
        if (!scrollTick) {
            scrollTick = true;
            setTimeout(function() {
                fixAllWidgets();
                scrollTick = false;
            }, 500);
        }
    }, { passive: true });

})();
</script>
@include('partials.whatsapp-float')
</body>

</html>