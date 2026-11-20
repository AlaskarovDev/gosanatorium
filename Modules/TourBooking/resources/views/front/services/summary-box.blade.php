{{-- Task 30: «Коротко» box under the gallery + mobile sticky booking bar. --}}
@php
    $gsSumPrice = \Modules\TourBooking\App\Services\FromPrice::for($service);
    $gsSumBasis = \Modules\TourBooking\App\Services\FromPrice::label($service);
    $gsSumShort = \Modules\TourBooking\App\Services\FromPrice::shortLabel($service);
    $gsSumRoomsUrl = $hasRooms
        ? route('front.tourbooking.rooms.selection', $service->slug)
        : route('front.tourbooking.guest-booking-form', $service->slug);
    $gsSumName = (string) ($service->translation->title ?? $service->title ?? '');
    $gsSumWa = company_fact('whatsapp_url') . '?text=' . rawurlencode('Здравствуйте! Интересует санаторий «' . $gsSumName . '». Подскажите, пожалуйста, цены и свободные даты.');
    $gsSumMeal = trim((string) ($service->meal_plan ?? '')) ?: '3-разовое питание';
    $gsSumMinStay = (int) ($service->min_stay_nights ?? 0);
    $gsSumTransfer = trim((string) ($service->transfer_info ?? ''));
    $gsSumCount = \Modules\TourBooking\App\Models\Review::where('service_id', $service->id)->where('status', true)->count();
    $gsSumAvg = $gsSumCount > 0 ? number_format((float) \Modules\TourBooking\App\Models\Review::where('service_id', $service->id)->where('status', true)->avg('rating'), 1) : null;
@endphp
<div class="gs-summary-wrap">
        <div class="gs-summary" id="section-summary">
            <div class="gs-summary__head">
                <h2 class="gs-summary__title">Коротко</h2>
                @if ($gsSumAvg !== null)
                    <a href="#section-reviews" class="gs-summary__rating">
                        <span class="gs-summary__score">★ {{ $gsSumAvg }}</span>
                        <span>{{ \Modules\TourBooking\App\Models\Review::countLabel($gsSumCount) }}</span>
                    </a>
                @endif
            </div>
            <div class="gs-summary__grid">
                @if ($gsSumPrice !== null)
                    <div class="gs-summary__price">
                        <span class="gs-summary__from">от</span>
                        <span class="gs-summary__amount">{!! currency_span($gsSumPrice) !!}</span>
                        <span class="gs-summary__basis">{{ $gsSumBasis }}</span>
                    </div>
                @endif
                <ul class="gs-summary__inc" aria-label="Что входит">
                    <li><i class="fa-regular fa-bed" aria-hidden="true"></i>Проживание</li>
                    <li><i class="fa-regular fa-utensils" aria-hidden="true"></i>{{ $gsSumMeal }}</li>
                    <li><i class="fa-regular fa-spa" aria-hidden="true"></i>Лечение</li>
                    <li><i class="fa-regular fa-user-doctor" aria-hidden="true"></i>Врач</li>
                </ul>
                <dl class="gs-summary__facts">
                    @if ($gsSumMinStay > 0)
                        <div><dt><i class="fa-regular fa-moon" aria-hidden="true"></i>Минимальный срок</dt><dd>от {{ $gsSumMinStay }} {{ trans_choice('ночи|ночей|ночей', $gsSumMinStay, [], 'ru') }}</dd></div>
                    @endif
                    @if ($gsSumTransfer !== '')
                        <div><dt><i class="fa-regular fa-van-shuttle" aria-hidden="true"></i>Трансфер</dt><dd>{{ $gsSumTransfer }}</dd></div>
                    @endif
                    <div><dt><i class="fa-regular fa-wallet" aria-hidden="true"></i>Оплата</dt><dd>{{ company_fact('prepayment_policy') }}</dd></div>
                </dl>
                <div class="gs-summary__cta">
                    <a href="{{ $gsSumRoomsUrl }}" class="gs-summary__btn gs-summary__btn--main">Выбрать номер</a>
                    <a href="{{ $gsSumWa }}" class="gs-summary__btn gs-summary__btn--wa" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i>WhatsApp</a>
                </div>
            </div>
        </div>
</div>

{{-- Mobile sticky bottom bar (≤991px). Replaces the floating WhatsApp button on this page. --}}
<div class="gs-stickybook" id="gsStickyBook" aria-label="Бронирование">
    @if ($gsSumPrice !== null)
        <div class="gs-stickybook__price">
            <span class="gs-stickybook__amount">от {!! currency_span($gsSumPrice) !!}</span>
            <span class="gs-stickybook__basis">{{ $gsSumShort }}</span>
        </div>
    @endif
    <a href="{{ $gsSumRoomsUrl }}" class="gs-stickybook__btn">Выбрать номер</a>
    <a href="{{ $gsSumWa }}" class="gs-stickybook__wa" target="_blank" rel="noopener" aria-label="Написать в WhatsApp"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i></a>
</div>
<script>
(function () {
    var bar = document.getElementById('gsStickyBook');
    if (!bar) return;
    document.body.classList.add('gs-has-stickybook');
    // Hide while any modal / overlay locks the page (booking modal, gallery, doctor modal, menu).
    function modalOpen() {
        var b = document.body, h = document.documentElement;
        if (b.style.overflow === 'hidden' || h.style.overflow === 'hidden') return true;
        return /(^|\s)(modal-open|gmodal-open|doctor-modal-open|rs-modal-open|mobile-menu-visible)(\s|$)/.test(b.className);
    }
    function tick() { bar.classList.toggle('is-hidden', modalOpen()); }
    new MutationObserver(tick).observe(document.body, { attributes: true, attributeFilter: ['class', 'style'] });
    new MutationObserver(tick).observe(document.documentElement, { attributes: true, attributeFilter: ['class', 'style'] });
    tick();

    // Sticky section menu (mobile): fixed under the floating header once the summary box is reached.
    var nav = document.getElementById('mobileTabs');
    var start = document.getElementById('section-summary');
    if (!nav || !start) return;
    var ph = document.createElement('div');
    nav.parentNode.insertBefore(ph, nav);
    function headerBottom() {
        var hb = 0;
        ['mobileFloatingHeader', 'header-sticky'].forEach(function (id) {
            var el = document.getElementById(id);
            if (!el) return;
            var r = el.getBoundingClientRect(), cs = getComputedStyle(el);
            if (cs.position === 'fixed' && cs.display !== 'none' && cs.visibility !== 'hidden' && r.height > 0 && r.top < 200 && r.bottom > hb) hb = r.bottom;
        });
        return Math.round(hb);
    }
    var raf = 0;
    function place() {
        raf = 0;
        if (window.innerWidth > 991) { nav.classList.remove('is-fixed'); nav.style.top = ''; return; }
        var top = headerBottom();
        var fix = start.getBoundingClientRect().top < top + 10;
        var wasFixed = nav.classList.contains('is-fixed');
        if (!wasFixed) ph.style.height = '0px';
        var h = wasFixed ? parseInt(ph.style.height, 10) || 0 : nav.offsetHeight;
        var on = fix && !bar.classList.contains('is-hidden');
        nav.classList.toggle('is-fixed', on);
        ph.style.height = on ? h + 'px' : '0px';
        nav.style.top = fix ? top + 'px' : '';
    }
    window.addEventListener('scroll', function () { if (!raf) raf = requestAnimationFrame(place); }, { passive: true });
    window.addEventListener('resize', place);
    place();
})();
</script>
