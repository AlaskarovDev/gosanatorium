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
<header class="site-header">

    <!-- TOP BAR -->
    <div class="header-top">
        @php $gsHdrPhone = !empty($footer->phone) ? $footer->phone : '+994519158894'; @endphp
        <a href="tel:{{ preg_replace('/[^\d+]/', '', $gsHdrPhone) }}" class="phone">📞 {{ $gsHdrPhone }}</a>
        <div class="controls">
            @include('partials.header-topbar-controls')
        </div>
    </div>

    <!-- MAIN NAV -->
    <div class="header-main">
        <a class="header-logo" href="{{ route('home') }}">
            <img src="{{ asset($general_setting->logo) }}" alt="GoSanatorium">
        </a>
        <nav class="header-nav">
            <div class="tgmenu__main-menu">
                <ul class="navigation" id="main-nav">
                    @foreach($gsHdrNav as [$gsHdrUrl, $gsHdrLabel])
                        <li class="{{ rtrim(url()->current(), '/') === rtrim($gsHdrUrl, '/') ? 'active' : '' }}"><a href="{{ $gsHdrUrl }}">{{ $gsHdrLabel }}</a></li>
                    @endforeach
                </ul>
            </div>
        </nav>
        <a href="{{ url('/hotels') }}" class="header-cta">{{ $gsHdrRu ? 'Забронировать' : 'Book now' }}</a>
        <div class="header-burger">
            <button class="tgmenu-offcanvas-open-btn mobile-nav-toggler" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>

    @include('components.common_mobile_menu')
    @include('components.common_offcanvas')
</header>
