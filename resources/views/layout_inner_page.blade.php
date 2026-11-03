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
    @include('partials.header')
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


    <script src="{{ asset('frontend/assets/js/gs-compare.js') }}?v={{ @filemtime(public_path('frontend/assets/js/gs-compare.js')) }}" defer></script>
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