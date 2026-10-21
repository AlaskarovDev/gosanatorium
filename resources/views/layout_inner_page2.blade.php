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

    <style>
        /* ===== HEADER RESPONSIVE — keep all elements, just compact spacing ===== */
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

        @media (max-width: 1699px) {
            .tgmenu__main-menu li,
            .tgmenu__navbar-wrap li { margin-right: 6px !important; }
            .tgmenu__main-menu li a,
            .tgmenu__navbar-wrap li a { padding: 0 8px !important; }
        }

        @media (max-width: 1499px) {
            .tgmenu__nav.tgmenu-1-space.ml-30 { margin-left: 14px !important; }
            .tgmenu__main-menu li,
            .tgmenu__navbar-wrap li { margin-right: 4px !important; }
            .tgmenu__main-menu li a,
            .tgmenu__navbar-wrap li a { font-size: 14px !important; padding: 0 6px !important; }
        }

        @media (max-width: 1366px) {
            .tgmenu__nav.tgmenu-1-space.ml-30 { margin-left: 10px !important; }
            .tgmenu__main-menu li,
            .tgmenu__navbar-wrap li { margin-right: 4px !important; padding: 0 !important; }
            .tgmenu__main-menu li a,
            .tgmenu__navbar-wrap li a {
                font-size: 13px !important;
                letter-spacing: 0 !important;
                padding: 0 6px !important;
            }
        }

        @media (min-width: 1200px) and (max-width: 1499px) {
            .tg-header__area .row > .col-lg-7,
            .tg-header-4-bootom .row > .col-lg-7 { flex: 0 0 58.333% !important; max-width: 58.333% !important; }
            .tg-header__area .row > .col-lg-5,
            .tg-header-4-bootom .row > .col-lg-5 { flex: 0 0 41.666% !important; max-width: 41.666% !important; }
        }
    </style>

    @stack('style_section')


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

    <!-- header-search -->
    <div class="search__popup">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="search__wrapper">
                        <div class="search__close">
                            <button type="button" class="search-close-btn">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17 1L1 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <path d="M1 1L17 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="search__form">
                            <form action="{{ route('front.tourbooking.services') }}" method="GET">
                                <div class="search__input">
                                    <input class="search-input-field" type="text"
                                        value="{{ request()->get('search') }}" name="search"
                                        placeholder="Type keywords here">
                                    <span class="search-focus-border"></span>
                                    <button type="submit">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9.55 18.1C14.272 18.1 18.1 14.272 18.1 9.55C18.1 4.82797 14.272 1 9.55 1C4.82797 1 1 4.82797 1 9.55C1 14.272 4.82797 18.1 9.55 18.1Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                            <path d="M19.0002 19.0002L17.2002 17.2002" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="search-popup-overlay"></div>
    <!-- header-search-end -->

    <header class="tg-header-height">
        <div class="tg-header__area">
            <div class="tg-header-top tg-header-top-space tg-primary-bg d-none d-lg-block">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            @if ($footer->address || $footer->email)
                                <div class="tg-header-top-info d-flex align-items-center">
                                    <a href="{{ $footer->address_url }}"><i
                                            class="mr-5 fa-regular fa-location-dot"></i> {{ $footer->address }}</a>
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <div class="tg-header-top-info d-flex align-items-center justify-content-end">
                                <a href="tel:{{ $footer->phone }}"><i class="fa-sharp fa-regular fa-phone"></i>
                                    {{ $footer->phone }}</a>
                                @auth('web')
                                    <a
                                        href="{{ Auth::guard('web')->user()->is_seller == 1 ? route('agency.dashboard') : route('user.dashboard') }}">
                                        <span>
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M1.7 17.2C1.5 17.2 1.3 17.1 1.2 17C1.1 16.8 1 16.7 1 16.5C1 15.1 1.4 13.7 2.1 12.4C2.8 11.2 3.9 10.1 5.1 9.4C4.6 8.8 4.2 8 4 7.2C3.9 6.4 3.9 5.5 4.1 4.8C4.3 4 4.8 3.2 5.3 2.6C5.9 2 6.6 1.5 7.3 1.3C7.9 1.1 8.5 1 9.1 1C9.3 1 9.6 1 9.8 1C10.6 1.1 11.4 1.4 12.1 1.9C12.8 2.4 13.3 3 13.7 3.7C14.1 4.4 14.3 5.2 14.3 6.1C14.3 7.3 13.9 8.5 13.1 9.4C13.7 9.8 14.3 10.2 14.9 10.7C15.7 11.5 16.2 12.3 16.7 13.3C17.1 14.3 17.3 15.3 17.3 16.4C17.3 16.6 17.2 16.8 17.1 16.9C17 17 16.8 17.1 16.6 17.1C16.5 17.1 16.4 17.1 16.3 17C16.2 17 16.1 16.9 16.1 16.8C16 16.7 16 16.7 15.9 16.6C15.9 16.5 15.8 16.4 15.8 16.3C15.8 15.4 15.6 14.6 15.3 13.8C15 13 14.5 12.3 13.8 11.7C13.2 11.2 12.6 10.7 11.9 10.4C11.1 10.9 10.2 11.2 9.1 11.2C8.1 11.2 7.1 10.9 6.3 10.4C5.2 10.9 4.2 11.7 3.5 12.8C2.8 13.9 2.4 15.1 2.4 16.4C2.4 16.6 2.3 16.8 2.2 16.9C2.1 17.1 1.9 17.2 1.7 17.2ZM9.1 2.5C8.4 2.5 7.7 2.7 7.1 3.1C6.4 3.5 6 4.1 5.7 4.7C5.4 5.4 5.3 6.1 5.5 6.9C5.6 7.6 6 8.3 6.5 8.8C7 9.3 7.7 9.7 8.4 9.8C8.6 9.8 8.9 9.9 9.1 9.9C9.6 9.9 10.1 9.8 10.5 9.6C11.2 9.3 11.7 8.9 12.2 8.2C12.6 7.6 12.8 6.9 12.8 6.2C12.8 5.2 12.4 4.3 11.7 3.6C11 2.8 10.1 2.5 9.1 2.5Z"
                                                    fill="currentColor" />
                                            </svg>
                                        </span> {{ __('translate.Dashboard') }}
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tg-header-4-bootom tg-header-lg-space" id="header-sticky">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-8 col-5">
                            <div class="tgmenu__wrap d-flex align-items-center">
                                <div class="logo flex-auto">
                                    <a href="{{ route('home') }}"><img src="{{ asset($general_setting->secondary_logo) }}"
                                            alt="Logo">
                                    </a>
                                </div>
                                <nav class="tgmenu__nav  ml-90 d-none d-xl-block">
                                    <div
                                        class="tgmenu__navbar-wrap tgmenu__main-menu tgmenu__navbar-wrap-4 d-none d-xl-flex">
                                        @include('components.common_navitems')
                                    </div>
                                </nav>
                            </div>
                        </div>
                        <div class="col-lg-4 col-7">
                            <div
                                class="tg-menu-right-action tg-menu-right-action-3 tg-menu-4-right-action d-flex align-items-center justify-content-end">
                                <button class="search-button search-open-btn">
                                    <svg width="19" height="18" viewBox="0 0 19 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M17.3047 16.8044L13.8294 13.3291M15.9857 8.14485C15.9857 12.1989 12.6992 15.4854 8.64519 15.4854C4.59114 15.4854 1.30469 12.1989 1.30469 8.14485C1.30469 4.09081 4.59114 0.804352 8.64519 0.804352C12.6992 0.804352 15.9857 4.09081 15.9857 8.14485Z"
                                            stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </button>
                                <div class="tg-header-cart p-relative d-none d-xl-block">
                                    @include('components.cart')
                                </div>
                                <div class="tg-header-menu-bar lh-1 p-relative ml-10">
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
        </div>

        <!-- Mobile Menu  -->
        <div class="tgmobile__menu">
            <nav class="tgmobile__menu-box">
                <div class="close-btn"><i class="fa-solid fa-xmark"></i></div>
                <div class="nav-logo">
                    <a href="{{ route('home') }}"><img src="{{ asset($general_setting->secondary_logo) }}"
                            alt="logo"></a>
                </div>
                <div class="tgmobile__menu-outer">
                    <!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header-->
                </div>
                <div class="social-links">
                    <ul class="list-wrap">
                        @if ($footer->facebook)
                            <li><a href="{{ $footer->facebook }}"><i class="fab fa-facebook-f"></i></a></li>
                        @endif
                        @if ($footer->twitter)
                            <li><a href="{{ $footer->twitter }}"><i class="fab fa-twitter"></i></a></li>
                        @endif
                        @if ($footer->instagram)
                            <li><a href="{{ $footer->instagram }}"><i class="fab fa-instagram"></i></a></li>
                        @endif
                        @if ($footer->linkedin)
                            <li><a href="{{ $footer->linkedin }}"><i class="fab fa-linkedin-in"></i></a></li>
                        @endif
                        @if ($footer->youtube)
                            <li><a href="{{ $footer->youtube }}"><i class="fab fa-youtube"></i></a></li>
                        @endif
                    </ul>
                </div>
            </nav>
        </div>
        <div class="tgmobile__menu-backdrop"></div>
        <!-- End Mobile Menu -->
        @include('components.common_offcanvas')

    </header>

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


@include('partials.whatsapp-float')
</body>

</html>
