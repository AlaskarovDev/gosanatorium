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
<meta name="google-site-verification" content="yes3uk7pra_WQiN8EZ4HCLLh-Vry5c-hv3Y_U44oLHs" />

    <!-- Site Title -->
    @include('partials.seo_head')

    {{-- Performance: preconnect / dns-prefetch external origins --}}
    <link rel="dns-prefetch" href="https://widget.yourgood.app">
    <link rel="preconnect" href="https://widget.yourgood.app" crossorigin>
    @if ($general_setting->google_analytic_status == 1)
        <link rel="dns-prefetch" href="https://www.googletagmanager.com">
        <link rel="preconnect" href="https://www.googletagmanager.com" crossorigin>
    @endif

    {{-- Critical inline CSS to avoid blank/grey screen while stylesheets load --}}
    <style>
        html { background: #fff; }
        body { background: #fff; }
        /* ═══ Branded loading splash — GoSanatorium.com (Kinetic Dots) ═══ */
        #initial-splash {
            position: fixed; inset: 0; z-index: 99999;
            display: flex; align-items: center; justify-content: center;
            background: radial-gradient(circle at 50% 38%, #1b2740 0%, #0e1424 72%);
            transition: opacity .5s ease;
        }
        #initial-splash.is-hidden { opacity: 0; pointer-events: none; }
        #initial-splash .splash-inner {
            display: flex; flex-direction: column; align-items: center; gap: 34px; text-align: center;
        }
        /* Müasir CSS brend nişanı (logo şəkli yoxdur) */
        #initial-splash .splash-mark {
            position: relative; width: 112px; height: 112px; border-radius: 30px;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #1a56db 0%, #14b8a6 100%);
            box-shadow: 0 18px 55px rgba(20,184,166,.45), inset 0 0 0 1px rgba(255,255,255,.18);
            opacity: 0; transform: translateY(18px) scale(.9);
            animation: splashMarkIn .9s cubic-bezier(.2,.7,.2,1) forwards,
                       splashFloat 3s ease-in-out 1s infinite;
        }
        #initial-splash .splash-mark::before {
            content: ""; position: absolute; inset: -10px; border-radius: 38px;
            border: 2px solid rgba(94,234,212,.55);
            animation: splashRing 2s ease-out infinite;
        }
        #initial-splash .splash-mark__icon { position: relative; width: 48px; height: 48px; }
        #initial-splash .splash-mark__icon::before,
        #initial-splash .splash-mark__icon::after {
            content: ""; position: absolute; background: #fff; border-radius: 7px;
            box-shadow: 0 2px 10px rgba(0,0,0,.15);
        }
        #initial-splash .splash-mark__icon::before { left: 50%; top: 0; transform: translateX(-50%); width: 15px; height: 100%; }
        #initial-splash .splash-mark__icon::after  { top: 50%; left: 0; transform: translateY(-50%); width: 100%; height: 15px; }

        #initial-splash .splash-word {
            font-family: 'Poppins', 'DM Sans', -apple-system, Segoe UI, sans-serif;
            font-weight: 700; font-size: clamp(26px, 5vw, 44px); letter-spacing: .3px; line-height: 1;
            color: #e8eefc; opacity: 0;
            animation: splashTextIn .7s ease .35s forwards;
        }
        #initial-splash .splash-word span {
            background: linear-gradient(90deg,#60a5fa,#5eead4,#fda4af,#5eead4,#60a5fa);
            background-size: 200% auto;
            -webkit-background-clip: text; background-clip: text;
            -webkit-text-fill-color: transparent; color: transparent;
            animation: splashShimmer 2.6s linear .35s infinite;
        }
        #initial-splash .splash-spinner { display: none; }

        @keyframes splashMarkIn { to { opacity: 1; transform: translateY(0) scale(1); } }
        @keyframes splashRing { 0% { transform: scale(.9); opacity: .6; } 70% { transform: scale(1.28); opacity: 0; } 100% { opacity: 0; } }

        /* ── Kinetic Dots Loader (CSS port) ── */
        .kdl { display: flex; align-items: center; justify-content: center; }
        .kdl-row { display: flex; gap: 20px; }
        .kdl-col {
            position: relative; display: flex; flex-direction: column;
            align-items: center; justify-content: flex-end; height: 80px; width: 24px;
        }
        .kdl-dot {
            position: relative; width: 22px; height: 22px; z-index: 10; will-change: transform;
            animation: gravity-bounce 1.4s cubic-bezier(0.45,0.05,0.55,0.95) infinite;
        }
        .kdl-ball {
            display: block; width: 100%; height: 100%; border-radius: 50%; will-change: transform;
            background: linear-gradient(to bottom, #67e8f9, #2563eb);
            box-shadow: 0 0 15px rgba(6,182,212,0.6);
            animation: rubber-morph 1.4s linear infinite;
        }
        .kdl-hl {
            position: absolute; top: 4px; left: 4px; width: 7px; height: 7px;
            background: rgba(255,255,255,0.6); border-radius: 50%; filter: blur(0.5px);
        }
        .kdl-ripple {
            position: absolute; bottom: 0; width: 42px; height: 12px;
            border: 1px solid rgba(6,182,212,0.3); border-radius: 100%; opacity: 0;
            animation: ripple-expand 1.4s linear infinite;
        }
        .kdl-shadow {
            position: absolute; bottom: -4px; width: 22px; height: 6px; border-radius: 100%;
            background: rgba(6,182,212,0.4); filter: blur(4px);
            animation: shadow-breathe 1.4s cubic-bezier(0.45,0.05,0.55,0.95) infinite;
        }

        @keyframes splashLogoIn { to { opacity: 1; transform: translateY(0) scale(1); } }
        @keyframes splashTextIn { to { opacity: 1; } }
        @keyframes splashFloat { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
        @keyframes splashShimmer { to { background-position: 200% center; } }
        @keyframes splash-spin { to { transform: rotate(360deg); } }

        @keyframes gravity-bounce {
            0%   { transform: translateY(0);     animation-timing-function: cubic-bezier(0.33,1,0.68,1); }
            50%  { transform: translateY(-44px); animation-timing-function: cubic-bezier(0.32,0,0.67,0); }
            100% { transform: translateY(0); }
        }
        @keyframes rubber-morph {
            0%   { transform: scale(1.4, 0.6); }
            5%   { transform: scale(0.9, 1.1); }
            15%  { transform: scale(1, 1); }
            50%  { transform: scale(1, 1); }
            85%  { transform: scale(0.9, 1.1); }
            100% { transform: scale(1.4, 0.6); }
        }
        @keyframes shadow-breathe {
            0%   { transform: scale(1.4); opacity: 0.6; }
            50%  { transform: scale(0.5); opacity: 0.1; }
            100% { transform: scale(1.4); opacity: 0.6; }
        }
        @keyframes ripple-expand {
            0%   { transform: scale(0.5); opacity: 0; border-width: 4px; }
            5%   { opacity: 0.8; }
            30%  { transform: scale(1.5); opacity: 0; border-width: 0px; }
            100% { transform: scale(1.5); opacity: 0; }
        }
    </style>

    {{-- Preload critical CSS bundles so they parallelize --}}
    <link rel="preload" href="{{ asset('frontend/assets/css/bootstrap.min.css') }}" as="style">
    <link rel="preload" href="{{ asset('frontend/assets/css/main.css') }}" as="style">
    <link rel="preload" href="{{ asset('frontend/assets/css/fontawesome-all.min.css') }}" as="style">

    <link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/flatpicker.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/odometer.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/default.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/dev.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/cookie_consent.css') }}?v={{ @filemtime(public_path('frontend/assets/css/cookie_consent.css')) ?: '1' }}">
    <link rel="stylesheet" href="{{ asset('global/toastr/toastr.min.css') }}">

    {{-- Hero fon şəkli artıq göstərilmir (yalnız video) — şəkil preload-u söndürüldü ki,
         yükləmə prioriteti tam videoya getsin. --}}

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
        <noscript><img height="1" width="1" style="display:none"
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
    {{-- Açılış splash animasiyası istəyə görə söndürüldü (display:none). --}}
    <div id="initial-splash" style="display:none !important;">
        <div class="splash-inner">
            {{-- Müasir CSS brend nişanı --}}
            <div class="splash-mark"><span class="splash-mark__icon"></span></div>
            <div class="splash-word">Go<span>Sanatorium</span></div>

            {{-- Kinetic Dots Loader --}}
            <div class="kdl">
                <div class="kdl-row">
                    @for ($i = 0; $i < 4; $i++)
                        @php $d = $i * 0.15; @endphp
                        <div class="kdl-col">
                            <div class="kdl-dot" style="animation-delay: {{ $d }}s">
                                <span class="kdl-ball" style="animation-delay: {{ $d }}s"></span>
                                <span class="kdl-hl"></span>
                            </div>
                            <div class="kdl-ripple" style="animation-delay: {{ $d }}s"></div>
                            <div class="kdl-shadow" style="animation-delay: {{ $d }}s"></div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
    <script>
        (function () {
            var START = Date.now();
            var MIN = 2400;   // animasiya tam bitənə qədər minimum vaxt (fill 2.2s + buffer)
            var MAX = 6000;   // təhlükəsizlik: nə olursa olsun bu vaxtdan sonra gizlət
            var hidden = false;

            function hideSplash() {
                if (hidden) return;
                hidden = true;
                var s = document.getElementById('initial-splash');
                if (!s) return;
                s.classList.add('is-hidden');
                setTimeout(function () { if (s && s.parentNode) s.remove(); }, 500);
            }

            // Animasiya bitsin (MIN), VƏ səhifə yüklənsin — sonra gizlət
            function whenReady() {
                var elapsed = Date.now() - START;
                setTimeout(hideSplash, Math.max(MIN - elapsed, 0));
            }
            if (document.readyState === 'complete') {
                whenReady();
            } else {
                window.addEventListener('load', whenReady);
            }
            // Təhlükəsizlik klapanı
            setTimeout(hideSplash, MAX);
        })();
    </script>

    @if ($general_setting->preloader_status == 'enable')
        <!-- Start Preloader -->
        <div id="loading">
            <div class="loader"></div>
        </div>
        <!-- End Preloader -->
    @endif

    <!-- Start Header Section -->
    @include('theme::components.header')
    <!-- End Header Section -->

    @yield('front-content')

    <!-- Start Footer Section -->
    @include('theme::components.footer')
    <!-- End Footer Section -->

    {{-- Mobil alt menyu (partials/mobile-bottom-nav.blade.php) --}}
    @include('partials.mobile-bottom-nav')

    <!-- Start Scroll Up Button -->
    <style>
        .scroll__top {
            bottom: 100px !important;
            opacity: 0 !important;
            visibility: hidden !important;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        .scroll__top.show {
            opacity: 1 !important;
            visibility: visible !important;
        }
    </style>
    <button class="scroll__top scroll-to-target" data-target="html">
        <i class="fa-sharp fa-regular fa-arrow-up"></i>
    </button>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var scrollBtn = document.querySelector('.scroll__top');
            window.addEventListener('scroll', function() {
                var scrollPercent = (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100;
                if (scrollPercent >= 30) {
                    scrollBtn.classList.add('show');
                } else {
                    scrollBtn.classList.remove('show');
                }
            });
        });
    </script>
    <!-- End Scroll Up Button -->


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
                        "{{ __('translate.All Language keywords are not implemented in the demo mode') }}");
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

    {{-- Yourgood widget — moved out of <head> and deferred for fast first paint --}}
    <script>
        window.addEventListener('load', function () {
            var w = document.createElement('script');
            w.defer = true;
            w.dataset.pfId = '8719c067-19f4-4029-b81e-a90b282e3a7f';
            w.src = 'https://widget.yourgood.app/script/widget.js?id=8719c067-19f4-4029-b81e-a90b282e3a7f&now=' + Date.now();
            document.body.appendChild(w);
        });
    </script>

@include('partials.whatsapp-float')
</body>

</html>