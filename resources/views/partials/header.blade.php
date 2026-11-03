{{-- Shared site header (two bars). Used by layout_inner_page and the active CMS theme.
     TOP BAR: phone left, currency + language right. MAIN NAV: logo, links, CTA; burger only on mobile. --}}
@php
    $gsHdrRu = session('lang_code', config('app.locale', 'en')) === 'ru';
    $gsHdrNav = [
        [route('sanatoriums.list'), $gsHdrRu ? 'Санатории' : 'Sanatoriums'],
        [url('/hotels'), $gsHdrRu ? 'Направления' : 'Destinations'],
        [route('akcii.frontend.index'), $gsHdrRu ? 'Акции' : 'Offers'],
        [route('blogs'), $gsHdrRu ? 'Блоги' : 'Blog'],
        [route('faq'), 'FAQ'],
        [route('about-us'), $gsHdrRu ? 'О нас' : 'About us'],
    ];
@endphp
<header class="site-header tg-header-height">
    <div class="tg-header__area tg-header-lg-space z-index-999 tg-transparent" id="header-sticky" data-gs-header>

        <!-- TOP BAR -->
        <div class="header-top gs-topbar">
            <div class="container-fluid container-1860">
                <div class="gs-topbar__row">
                    <div class="gs-topbar__left">
                        @php $gsHdrPhone = !empty($footer->phone) ? $footer->phone : '+994519158894'; @endphp
                        <a href="tel:{{ preg_replace('/[^\d+]/', '', $gsHdrPhone) }}" class="phone">📞 {{ $gsHdrPhone }}</a>
                    </div>
                    <div class="controls gs-topbar__right">
                        @include('partials.header-topbar-controls')
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN NAV -->
        <div class="container-fluid container-1860 gs-mainbar">
            <div class="header-main gs-mainbar__row">
                <div class="logo">
                    <a class="logo-1 header-logo" href="{{ route('home') }}">
                        <img src="{{ asset($general_setting->logo) }}" alt="GoSanatorium">
                    </a>
                    <a class="logo-2 d-none" href="{{ route('home') }}">
                        <img src="{{ asset($general_setting->secondary_logo) }}" alt="GoSanatorium">
                    </a>
                </div>
                <nav class="header-nav tgmenu__nav gs-mainnav">
                    <div class="tgmenu__navbar-wrap tgmenu__main-menu">
                        <ul class="navigation" id="main-nav">
                            @foreach($gsHdrNav as [$gsHdrUrl, $gsHdrLabel])
                                <li class="{{ rtrim(url()->current(), '/') === rtrim($gsHdrUrl, '/') ? 'active' : '' }}"><a href="{{ $gsHdrUrl }}">{{ $gsHdrLabel }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </nav>
                <a href="{{ url('/hotels') }}" class="header-cta">{{ $gsHdrRu ? 'Забронировать' : 'Book now' }}</a>
                <div class="header-burger gs-burger">
                    <button class="tgmenu-offcanvas-open-btn mobile-nav-toggler" aria-label="Menu">
                        <span></span><span></span><span></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('components.common_mobile_menu')
    @include('components.common_offcanvas')
</header>
