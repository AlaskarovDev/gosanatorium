    <header class="tg-header-height">
        <div class="tg-header__area tg-header-lg-space z-index-999 tg-transparent" id="header-sticky">
            <div class="container-fluid container-1860">
                <div class="row align-items-center">
                    <div class="col-lg-7 col-5">
                        <div class="tgmenu__wrap d-flex align-items-center">
                            <div class="logo">
                                <a class="logo-1" href="{{ route('home') }}">
                                    <img src="{{ asset($general_setting->logo) }}" alt="Logo">
                                </a>
                                <a class="logo-2 d-none" href="{{ route('home') }}">
                                    <img src="{{ asset($general_setting->secondary_logo) }}" alt="Logo">  
                                </a>
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
                                {{-- WhatsApp --}}
                                @if($phoneClean)
                                <a href="https://wa.me/{{ $phoneClean }}" 
                                class="tg-social-icon tg-whatsapp" 
                                target="_blank" 
                                title="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                @endif

                                {{-- Telegram --}}
                                @if($phoneClean)
                                <a href="https://t.me/+{{ $phoneClean }}" 
                                class="tg-social-icon tg-telegram" 
                                target="_blank" 
                                title="Telegram">
                                    <i class="fab fa-telegram-plane"></i>
                                </a>
                                @endif

                                {{-- VK --}}
                                <a href="https://vk.com/gosanatorium" 
                                class="tg-social-icon tg-vk" 
                                target="_blank" 
                                rel="noopener" 
                                title="VK">
                                    <i class="fab fa-vk"></i>
                                </a>

                            </div>
                            {{-- ========== END SOCIAL CONTACT ICONS ========== --}}

                            <div class="tg-header-contact-info d-flex align-items-center">
                                <span class="tg-header-contact-icon mr-5 d-none d-xl-block">
                                    <svg width="21" height="21" viewBox="0 0 21 21" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M17.5747 15.8619L15.8138 17.6228C15.7656 17.6732 15.7236 17.7026 15.6627 17.7362C13.1757 19.0753 8.40326 16.5734 6.21009 14.2626C6.18698 14.2374 6.16809 14.2185 6.14502 14.1954C3.83427 12.0021 1.33257 7.22927 2.67157 4.7421C2.70515 4.68124 2.73453 4.64134 2.78491 4.5931L4.54573 2.83006C4.67586 2.69992 4.82067 2.64116 5.00114 2.64116H5.01583C5.20471 2.64327 5.35163 2.71044 5.47965 2.84895L7.75047 5.30044C7.98973 5.55651 7.98131 5.95109 7.73368 6.19877L6.26666 7.66589C5.85321 8.08148 5.67271 8.62926 5.75877 9.20856C5.94134 10.428 6.55419 11.574 7.63293 12.7095C7.65603 12.7326 7.67489 12.7536 7.69799 12.7746C8.83342 13.8534 9.97723 14.4663 11.1966 14.6488C11.7779 14.7349 12.3257 14.5544 12.7412 14.1388L14.2062 12.6738C14.4538 12.4261 14.8484 12.4177 15.1065 12.6549L17.5578 14.9259C17.6963 15.0539 17.7614 15.2008 17.7656 15.3897C17.7698 15.5785 17.709 15.7276 17.5747 15.8619ZM18.3428 14.0779L15.8914 11.8069C15.1779 11.1457 14.0781 11.1667 13.3897 11.8552L11.9227 13.3223C11.7695 13.4755 11.5827 13.5364 11.3687 13.5049C10.3907 13.358 9.45254 12.8459 8.49341 11.9349C8.485 11.9287 8.47872 11.9202 8.47031 11.9118C7.56155 10.9547 7.04946 10.0144 6.90257 9.03849C6.87109 8.82439 6.93195 8.6376 7.08518 8.48229L8.5522 7.01728C9.2406 6.32883 9.2616 5.22902 8.59837 4.51331L6.32966 2.06182C5.98758 1.69451 5.54055 1.49304 5.03893 1.48462C4.53735 1.47624 4.08401 1.65672 3.72725 2.01354L1.96638 3.77452C1.83836 3.90256 1.73971 4.0348 1.65368 4.19431C1.24444 4.95199 1.08073 5.8776 1.16679 6.93962C1.24023 7.85682 1.49628 8.86008 1.92863 9.91793C2.70726 11.8279 3.9854 13.742 5.34746 15.035C5.35794 15.0434 5.36425 15.0497 5.37263 15.0581C6.66546 16.4202 8.57737 17.7006 10.4872 18.4792C11.5471 18.9095 12.5482 19.1656 13.4653 19.2411C13.6479 19.2558 13.8263 19.2621 14.0005 19.2621C14.8421 19.2621 15.5829 19.0921 16.2105 18.7542C16.37 18.6681 16.5043 18.5695 16.6323 18.4415L18.3931 16.6784C18.7478 16.3237 18.9304 15.8704 18.922 15.3687C18.9115 14.8649 18.7122 14.42 18.3428 14.0779Z"
                                            fill="currentColor" />
                                    </svg>
                                </span>
                                <div class="tg-header-contact-number d-none d-xl-block">
                                    <span>{{ __('translate.Call Us') }}:</span>
                                    <a href="tel:{{ $footer->phone }}">{{ $footer->phone }}</a>
                                </div>
                            </div>
                        
                            {{-- ========== CURRENCY SWITCHER ========== --}}
                            @php
                                $activeCurrencies = \Modules\Currency\App\Models\Currency::where('status', 'active')->get();
                                $sessionCurrency = session('currency');
                                $currentCode = $sessionCurrency ? $sessionCurrency['currency_code'] : null;
                                
                                if (!$currentCode) {
                                    $defaultCurr = $activeCurrencies->where('is_default', 'yes')->first();
                                    $currentCode = $defaultCurr ? $defaultCurr->currency_code : 'USD';
                                }
                            @endphp

                            @if($activeCurrencies->count() > 1)
                            <div class="tg-currency-switcher d-flex align-items-center ml-15">
                                <select class="currency_code tg-currency-select notranslate" translate="no" aria-label="{{ __('translate.Currency') }}">
                                    @foreach($activeCurrencies as $curr)
                                        <option value="{{ $curr->currency_code }}" 
                                            {{ $currentCode == $curr->currency_code ? 'selected' : '' }}>
                                            {{ $curr->currency_icon }} {{ $curr->currency_code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            {{-- ========== END CURRENCY SWITCHER ========== --}}

                            {{-- ========== LANGUAGE SWITCHER ========== --}}
                            @php
                                $activeLanguages = \Modules\Language\App\Models\Language::where('status', 1)->get();
                                $currentLang = session('lang_code', config('app.locale', 'en'));
                            @endphp

                            @if($activeLanguages->count() > 1)
                            <div class="tg-language-switcher d-flex align-items-center ml-10">
                                <select class="language_code tg-language-select notranslate" translate="no" aria-label="{{ __('translate.Language') }}">
                                    @foreach($activeLanguages as $lang)
                                        <option value="{{ $lang->lang_code }}" 
                                            {{ $currentLang == $lang->lang_code ? 'selected' : '' }}>
                                            {{ strtoupper($lang->lang_code) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            {{-- ========== END LANGUAGE SWITCHER ========== --}}

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
    <script>(function () { var widget = document.createElement('script'); widget.defer = true; widget.dataset.pfId = '8719c067-19f4-4029-b81e-a90b282e3a7f'; widget.src = 'https://widget.yourgood.app/script/widget.js?id=8719c067-19f4-4029-b81e-a90b282e3a7f&now='+Date.now(); document.head.appendChild(widget); })()</script>
        <!-- offCanvas-menu -->
        @include('components.common_offcanvas')
        <!-- offCanvas-menu-end -->

    </header>

    {{-- ========== CUSTOM HEADER CSS ========== --}}
    <style>
    /* ===== Logo-Menyu boşluğunu azaltma ===== */
    .tgmenu__nav.tgmenu-1-space.ml-30 {
        margin-left: 30px !important;
    }

    /* ===== Müasir font import ===== */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&display=swap');

    /* ===== Menyu yazılarına müasir font ===== */
    .tgmenu__main-menu li a,
    .tgmenu__navbar-wrap li a {
        font-family: 'Poppins', sans-serif !important;
        font-weight: 600 !important;
        letter-spacing: 0.3px;
        text-transform: capitalize;
    }

    /* Larger menu text and spacing on wide screens (smaller screens keep the compact rules below) */
    @media (min-width: 1700px) {
        .tgmenu__main-menu li a,
        .tgmenu__navbar-wrap li a {
            font-size: 16px !important;
            font-weight: 600 !important;
            letter-spacing: 0.3px !important;
            padding-left: 18px !important;
            padding-right: 18px !important;
        }
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
    .tg-social-icon.tg-whatsapp {
        background: #25D366 !important;
        color: #fff !important;
    }
    .tg-social-icon.tg-whatsapp:hover {
        background: #1da851 !important;
    }

    /* Telegram */
    .tg-social-icon.tg-telegram {
        background: #0088cc !important;
        color: #fff !important;
    }
    .tg-social-icon.tg-telegram:hover {
        background: #006699 !important;
    }

    /* VKontakte */
    .tg-social-icon.tg-vk {
        background: #4C75A3 !important;
        color: #fff !important;
    }
    .tg-social-icon.tg-vk:hover {
        background: #3b6291 !important;
    }

    /* OK (Odnoklassniki) */
    .tg-social-icon.tg-ok {
        background: #EE8208 !important;
        color: #fff !important;
    }
    .tg-social-icon.tg-ok:hover {
        background: #d67205 !important;
    }

    /* Email */
    .tg-social-icon.tg-email {
        background: #EA4335 !important;
        color: #fff !important;
    }
    .tg-social-icon.tg-email:hover {
        background: #d33426 !important;
    }

    /* ===== RESPONSIVE — menyu öz sütununda qalır: əvvəlcə kichil, sonra wrap ===== */

    /* Menyu DEFAULT olaraq TƏK SƏTİR (nowrap) — yer varsa heç vaxt aşağı düşmür.
    (Əvvəl flex-wrap:wrap idi və ul öz məzmununa görə kichildiyindən boş yer
    ola-ola son element aşağı düşürdü.) Yer çatmayanda JS font-u kichildir;
    yalnız min ölçüdə də sığmasa JS son çarə kimi wrap-a keçir. */
    .tgmenu__main-menu,
    .tgmenu__main-menu .navigation,
    .tgmenu__navbar-wrap ul {
        flex-wrap: nowrap !important;
    }
    .tgmenu__main-menu li,
    .tgmenu__navbar-wrap li {
        flex-shrink: 0;
    }
    /* Hər linkin yazısı bütöv qalsın (söz ortadan bölünməsin). */
    .tgmenu__main-menu li a,
    .tgmenu__navbar-wrap li a {
        white-space: nowrap;
        line-height: 1.25;
    }

    /* Wrap olanda header çox böyüməsin: inline menyu linklərinin şaquli
    padding-i azaldılır və header hündürlüyü min-height ilə sabitlənir.
    Tək sətirdə görünüş indiki kimi qalır; 2-ci sətir də bu hündürlüyə sığır. */
    @media (min-width: 1200px) {
        .tgmenu__wrap { min-height: 90px; }

        /* KRİTİK: flexbox-da wrap yalnız konteynerin eni MƏHDUD olanda işləyir.
        Default min-width:auto elementlərin kichilməsinə imkan vermir, ona görə
        menyu uzun yazıda sığmır və sağdakı bloka daşır. Aşağıdakı min-width:0 +
        flex:1 nav-a qalan boş sahənin enini verir və siyahı həmin enə görə
        wrap olur (col-lg-7-dən çıxmır). */
        .tgmenu__nav.tgmenu-1-space {
            flex: 1 1 0 !important;
            min-width: 0 !important;
        }
        .tgmenu__main-menu {
            min-width: 0 !important;
            width: 100%;
        }
        .tgmenu__main-menu .navigation {
            flex: 1 1 auto !important;
            min-width: 0 !important;
            margin: 0 !important;
            justify-content: flex-start !important;
        }

        .tgmenu__main-menu .navigation > li > a {
            padding-top: 14px !important;
            padding-bottom: 14px !important;
        }
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

    /* KÖK HƏLL: tərcümədən sonra sağ blok (sosial + valyuta + telefon + Giriş)
    genişlənir və sabit 7/5 sütunda 41.6%-ə sığmayıb SOLA daşır, menyunun
    üstünə gəlir. Sütunları sabit saxlamaq əvəzinə:
      - sol sütun (logo+menyu): çevik, qalan yeri tutur, lazım olsa kichilir → menyu wrap olur
      - sağ sütun: öz məzmununun eninə görə (content-width) → HEÇ VAXT sola daşmır
    Sətir wrap olmasın deyə row nowrap edilir. */
    @media (min-width: 1200px) {
        .tg-header__area .row { flex-wrap: nowrap !important; }
        .tg-header__area .row > .col-lg-7 {
            flex: 1 1 auto !important;
            width: auto !important;
            max-width: none !important;
            min-width: 0 !important;
        }
        .tg-header__area .row > .col-lg-5 {
            flex: 0 0 auto !important;
            width: auto !important;
            max-width: none !important;
        }
    }

    /* ===== MOBILE RESPONSIVE ===== */
    @media (max-width: 1199px) {
        .tg-header-social-contacts {
            display: none !important;
        }
        .tg-currency-switcher {
            margin-left: 8px !important;
        }
        .tg-currency-select {
            padding: 5px 26px 5px 8px;
            font-size: 12px;
            min-width: 75px;
            border-radius: 6px;
        }
        .tg-language-switcher {
            margin-left: 5px !important;
        }
        .tg-language-select {
            padding: 5px 26px 5px 8px;
            font-size: 12px;
            min-width: 60px;
            border-radius: 6px;
        }
    }

    @media (max-width: 767px) {
        .tg-currency-select {
            padding: 4px 22px 4px 6px;
            font-size: 11px;
            min-width: 65px;
        }
        .tg-language-select {
            padding: 4px 22px 4px 6px;
            font-size: 11px;
            min-width: 50px;
        }
    }

    /* Tooltip on hover */
    .tg-social-icon[title] {
        position: relative;
    }

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

    /* ===== Currency Switcher ===== */
    .tg-currency-switcher {
        position: relative;
    }

    .tg-currency-select {
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
        min-width: 95px;
    }

    .tg-currency-select option {
        background: #fff;
        color: #333;
        font-weight: 600;
    }

    .tg-currency-select:hover {
        background: #1648c0;
        border-color: #1648c0;
    }

    .tg-currency-select:focus {
        background: #1648c0;
        border-color: #1648c0;
        box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.3);
    }

    /* ===== Language Switcher ===== */
    .tg-language-switcher {
        position: relative;
    }

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
        min-width: 75px;
    }

    .tg-language-select option {
        background: #fff;
        color: #333;
        font-weight: 600;
    }

    .tg-language-select:hover {
        background: #1648c0;
        border-color: #1648c0;
    }

    .tg-language-select:focus {
        background: #1648c0;
        border-color: #1648c0;
        box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.3);
    }
    </style>

    {{-- ========== MENYU AVTOMATIK FİT (font kichilt, aşağı keçmə) ========== --}}
    {{-- Tərcümədən sonra menyu yazıları uzanırsa: AŞAĞI keçmək əvəzinə font-size
         avtomatik kichilir ki, tək sətirdə qalsın. Yalnız ən kiçik ölçüdə (11px)
         belə sığmasa, son çarə kimi aşağı keçir — heç vaxt sağ bloka daşmır. --}}
    <script>
    (function () {
        function getUl(){ return document.querySelector('.tgmenu__main-menu .navigation'); }

        function fit(){
            var ul = getUl();
            if (!ul) return;
            var lis = [], links = [];
            Array.prototype.forEach.call(ul.children, function (li) {
                if (li.tagName === 'LI') { lis.push(li); var a = li.querySelector('a'); if (a) links.push(a); }
            });
            if (!links.length) return;

            function setFont(px){
                links.forEach(function (a) {
                    if (px) a.style.setProperty('font-size', px + 'px', 'important');
                    else a.style.removeProperty('font-size');
                });
            }
            function mode(single){
                // CSS-də nowrap !important var; wrap-a keçmək üçün də !important lazımdır.
                ul.style.setProperty('flex-wrap', single ? 'nowrap' : 'wrap', 'important');
                lis.forEach(function (li) { li.style.flexShrink = single ? '0' : '1'; });
                links.forEach(function (a) { a.style.whiteSpace = single ? 'nowrap' : 'normal'; });
            }

            // <1200px: mobil menyu — toxunma
            if (window.innerWidth < 1200) { setFont(null); mode(false); return; }

            setFont(null);
            mode(true); // tək sətir — real ölçmək üçün

            // SAĞ BLOKUN ən sol GÖRÜNƏN elementinin sol kənarı (qutu deyil — boş yer
            // problemini həll edir). Menyunun son elementinin sağ kənarı bundan
            // keçirsə → daşır.
            var rightBlock = document.querySelector('.tg-menu-right-action');
            function rightStart(){
                if (rightBlock) {
                    var min = Infinity;
                    Array.prototype.forEach.call(rightBlock.children, function (ch) {
                        var r = ch.getBoundingClientRect();
                        if (r.width > 0 && r.height > 0 && r.left < min) min = r.left;
                    });
                    if (min !== Infinity) return min;
                    return rightBlock.getBoundingClientRect().left;
                }
                var col = ul.closest('.col-lg-7');
                return col ? col.getBoundingClientRect().right : window.innerWidth;
            }
            function overflowing(){
                var lastRight = links[links.length - 1].getBoundingClientRect().right;
                return lastRight > rightStart() - 18; // 18px boşluq: menyu ilə ikonlar arasında
            }

            var base = parseFloat(getComputedStyle(links[0]).fontSize) || 15;
            var size = base, min = 10, guard = 0;
            while (overflowing() && size > min && guard < 40) {
                size -= 0.5; setFont(size); guard++;
            }
            // AŞAĞI KEÇMƏ (wrap) YOXDUR — istifadəçi yalnız font kichilməsini istəyir.
            // Menyu həmişə tək sətir qalır (CSS-də nowrap). Min font-da da sığmasa
            // belə wrap-a keçmir.
        }

        var t;
        function schedule(){ clearTimeout(t); t = setTimeout(fit, 60); }

        function init(){
            fit();
            var ul = getUl();
            // Google tərcüməsi menyu mətnini sonradan dəyişir — izlə
            if (ul && window.MutationObserver) {
                new MutationObserver(schedule).observe(ul, { childList: true, subtree: true, characterData: true });
            }
            if (window.MutationObserver) {
                new MutationObserver(schedule).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
            }
        }

        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
        else init();
        window.addEventListener('resize', schedule);
        // Bütün şəkillər/fontlar yüklənəndən sonra yenidən (Poppins gec yüklənsə mətn enlənir)
        window.addEventListener('load', fit);
        if (document.fonts && document.fonts.ready) { document.fonts.ready.then(fit); }
        // əlavə təhlükəsizlik: ilk saniyələrdə bir neçə dəfə (tərcümə gec gəlsə)
        [400, 1200, 2500].forEach(function (ms) { setTimeout(fit, ms); });
    })();
    </script>