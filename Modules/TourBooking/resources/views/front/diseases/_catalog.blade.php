{{-- Task 28: catalog — filters (left on desktop, bottom sheet on mobile), sorting, map, pagination. --}}
@php
    $f = $f ?? [];
    $sort = $sort ?? 'popular';
    $view = $view ?? 'list';
    $options = $options ?? ['countries' => collect(), 'destinations' => collect(), 'diseases' => collect(), 'price_min' => 0, 'price_max' => 0, 'sorts' => [], 'has_stars' => false];
    $catUrl = url()->current();
    $q = request()->except(['page']);
    $viewUrl = fn ($v) => $catUrl . '?' . http_build_query(array_filter(array_merge($q, ['view' => $v === 'map' ? 'map' : null]), fn ($x) => $x !== null && $x !== ''));
    $total = $services->total();
    $activeCount = collect([
        ($f['disease'] ?? '') !== '', ($f['country'] ?? '') !== '', ($f['destination_id'] ?? 0) > 0,
        ($f['budget_min'] ?? null) !== null || ($f['budget_max'] ?? null) !== null, ($f['stars'] ?? 0) > 0,
        ($f['rating'] ?? null) !== null, ! empty($f['pool']), ! empty($f['transfer']), ($f['child_age'] ?? null) !== null, ($f['nights'] ?? '') !== '',
    ])->filter()->count();
    $pMin = (int) $options['price_min'];
    $pMax = (int) $options['price_max'];
    $curMin = $f['budget_min'] !== null ? max($pMin, (int) floor($f['budget_min'])) : $pMin;
    $curMax = $f['budget_max'] !== null ? min($pMax, (int) ceil($f['budget_max'])) : $pMax;
    $countLabel = trans_choice('санаторий|санатория|санаториев', $total, [], 'ru');
@endphp

<div class="gs-cat" id="gsCatalog">
    <aside class="gs-cat__filters" id="gsCatFilters" aria-label="Фильтры">
        <form method="get" action="{{ $catUrl }}" id="gsCatForm" class="gs-cat__form">
            <div class="gs-cat__sheet-head">
                <strong>Фильтры</strong>
                <button type="button" class="gs-cat__close" data-gs-cat-close aria-label="Закрыть">✕</button>
            </div>
            <input type="hidden" name="sort" value="{{ $sort !== 'popular' ? $sort : '' }}" data-gs-sort-input>
            @if ($view === 'map')<input type="hidden" name="view" value="map">@endif
            @if (($f['nights'] ?? '') !== '')<input type="hidden" name="nights" value="{{ $f['nights'] }}">@endif

            @if ($options['countries']->count() > 1)
                <div class="gs-cat__group">
                    <label class="gs-cat__label" for="gsfCountry">Страна</label>
                    <select id="gsfCountry" name="country" class="gs-cat__select">
                        <option value="">Все страны</option>
                        @foreach ($options['countries'] as $c)
                            <option value="{{ $c }}" @selected(($f['country'] ?? '') === $c)>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if ($options['destinations']->count() > 0)
                <div class="gs-cat__group">
                    <label class="gs-cat__label" for="gsfDest">Курорт / город</label>
                    <select id="gsfDest" name="destination_id" class="gs-cat__select">
                        <option value="">Все курорты</option>
                        @foreach ($options['destinations'] as $d)
                            @php $dName = preg_split('/\s+[\x{2013}\x{2014}-]\s+|\s*,\s*/u', (string) $d->name)[0] ?? $d->name; @endphp
                            <option value="{{ $d->id }}" @selected((int) ($f['destination_id'] ?? 0) === (int) $d->id)>{{ $dName }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="gs-cat__group">
                <label class="gs-cat__label" for="gsfDisease">Заболевание</label>
                <select id="gsfDisease" name="disease" class="gs-cat__select">
                    <option value="">Любое</option>
                    @foreach ($options['diseases'] as $dz)
                        <option value="{{ $dz->slug }}" @selected(($f['disease'] ?? '') === $dz->slug)>{{ $dz->name }}</option>
                    @endforeach
                </select>
            </div>

            @if ($pMax > $pMin)
                <div class="gs-cat__group">
                    <span class="gs-cat__label">Цена «от», USD</span>
                    <div class="gs-cat__range" data-gs-range data-min="{{ $pMin }}" data-max="{{ $pMax }}">
                        <div class="gs-cat__range-vals"><span data-gs-range-lo>${{ $curMin }}</span> — <span data-gs-range-hi>${{ $curMax }}</span></div>
                        <div class="gs-cat__range-track">
                            <input type="range" min="{{ $pMin }}" max="{{ $pMax }}" step="1" value="{{ $curMin }}" name="budget_min" aria-label="Цена от" data-gs-lo>
                            <input type="range" min="{{ $pMin }}" max="{{ $pMax }}" step="1" value="{{ $curMax }}" name="budget_max" aria-label="Цена до" data-gs-hi>
                        </div>
                    </div>
                </div>
            @endif

            @if ($options['has_stars'])
                <div class="gs-cat__group">
                    <span class="gs-cat__label">Звёзды</span>
                    <div class="gs-cat__chips">
                        @foreach ([0 => 'Любые', 3 => '3★+', 4 => '4★+', 5 => '5★'] as $sv => $sl)
                            <label class="gs-cat__chip"><input type="radio" name="stars" value="{{ $sv ?: '' }}" @checked((int) ($f['stars'] ?? 0) === $sv)><span>{{ $sl }}</span></label>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="gs-cat__group">
                <span class="gs-cat__label">Оценка гостей</span>
                <div class="gs-cat__chips">
                    @foreach (['' => 'Любая', '4' => '4+', '4.5' => '4.5+'] as $rv => $rl)
                        <label class="gs-cat__chip"><input type="radio" name="rating" value="{{ $rv }}" @checked((string) ($f['rating'] ?? '') === (string) $rv)><span>{{ $rl }}</span></label>
                    @endforeach
                </div>
            </div>

            <div class="gs-cat__group">
                <label class="gs-cat__check"><input type="checkbox" name="pool" value="1" @checked(! empty($f['pool']))> Бассейн</label>
                <label class="gs-cat__check"><input type="checkbox" name="transfer" value="1" @checked(! empty($f['transfer']))> Бесплатный трансфер</label>
            </div>

            <div class="gs-cat__group">
                <label class="gs-cat__label" for="gsfChild">Лечение детей с возраста</label>
                <select id="gsfChild" name="child_age" class="gs-cat__select">
                    <option value="">Не важно</option>
                    @foreach ([3, 5, 7, 10, 12, 14] as $age)
                        <option value="{{ $age }}" @selected(($f['child_age'] ?? null) === $age)>ребёнку {{ $age }} {{ trans_choice('год|года|лет', $age, [], 'ru') }}</option>
                    @endforeach
                </select>
            </div>

            <div class="gs-cat__actions">
                <button type="submit" class="gs-cat__apply">Показать {{ $total }} {{ $countLabel }}</button>
                @if ($activeCount > 0)
                    <a class="gs-cat__reset" href="{{ $catUrl }}{{ $view === 'map' ? '?view=map' : '' }}">Сбросить</a>
                @endif
            </div>
        </form>
    </aside>
    <div class="gs-cat__backdrop" data-gs-cat-close></div>

    <div class="gs-cat__main">
        <div class="gs-cat__toolbar">
            <div class="gs-cat__count" aria-live="polite">Найдено: <strong>{{ $total }}</strong> {{ $countLabel }}</div>
            <button type="button" class="gs-cat__open" data-gs-cat-open aria-controls="gsCatFilters">
                <i class="fa-regular fa-sliders" aria-hidden="true"></i> Фильтры @if ($activeCount)<span class="gs-cat__badge">{{ $activeCount }}</span>@endif
            </button>
            <label class="gs-cat__sort">
                <span>Сортировка:</span>
                <select data-gs-sort aria-label="Сортировка">
                    @foreach ($options['sorts'] as $sk => $sl)
                        <option value="{{ $sk }}" @selected($sort === $sk)>{{ $sl }}</option>
                    @endforeach
                </select>
            </label>
            <div class="gs-cat__views" role="group" aria-label="Вид">
                <a href="{{ $viewUrl('list') }}" @class(['gs-cat__view', 'is-on' => $view !== 'map']) @if ($view !== 'map') aria-current="true" @endif rel="nofollow"><i class="fa-regular fa-grid-2" aria-hidden="true"></i> Списком</a>
                <a href="{{ $viewUrl('map') }}" @class(['gs-cat__view', 'is-on' => $view === 'map']) @if ($view === 'map') aria-current="true" @endif rel="nofollow"><i class="fa-regular fa-map-location-dot" aria-hidden="true"></i> На карте</a>
            </div>
            <a class="gs-cat__fav gs-fav-link" href="{{ localize_front_url('/izbrannoe') }}">♡ Избранное <span class="gs-fav-count" style="display:none"></span></a>
        </div>

        @if ($view === 'map')
            <div class="gs-cat__map-wrap">
                <div id="gsCatMap" class="gs-cat__map" role="region" aria-label="Карта санаториев"></div>
                @php $noCoords = $total - $mapPoints->count(); @endphp
                @if ($noCoords > 0)
                    <p class="gs-cat__map-note">{{ $noCoords }} {{ trans_choice('санаторий|санатория|санаториев', $noCoords, [], 'ru') }} без координат — {{ $noCoords === 1 ? 'он показан' : 'они показаны' }} в списке ниже.</p>
                @endif
            </div>
        @endif

        @if ($services->count() > 0)
            @include('tourbooking::front.services.gscard-grid', ['allServices' => $services])
            @if ($services->hasPages())
                <nav class="gs-cat__pager" aria-label="Страницы">
                    @if ($services->hasMorePages())
                        <a class="gs-cat__more" href="{{ $services->nextPageUrl() }}">Показать ещё</a>
                    @endif
                    <div class="gs-cat__pages">
                        @if (! $services->onFirstPage())<a href="{{ $services->previousPageUrl() }}" rel="prev" aria-label="Предыдущая страница">←</a>@endif
                        @for ($i = 1; $i <= $services->lastPage(); $i++)
                            @if ($i === $services->currentPage())<span aria-current="page">{{ $i }}</span>@else<a href="{{ $services->url($i) }}">{{ $i }}</a>@endif
                        @endfor
                        @if ($services->hasMorePages())<a href="{{ $services->nextPageUrl() }}" rel="next" aria-label="Следующая страница">→</a>@endif
                    </div>
                </nav>
            @endif
        @else
            <div class="gs-filter-empty">
                <p>По вашему запросу ничего не найдено. Попробуйте изменить фильтры.</p>
                <a href="{{ $catUrl }}" class="tg-btn gs-reset-filters-btn">Сбросить фильтры</a>
            </div>
        @endif
    </div>
</div>

@push('style_section')
<style>
    .gs-cat { display: grid; grid-template-columns: 270px minmax(0, 1fr); gap: 28px; align-items: start; }
    .gs-cat__filters { background: #fff; border: 1px solid #e8eef4; border-radius: 16px; padding: 18px; position: sticky; top: 100px; max-height: calc(100vh - 120px); overflow-y: auto; }
    .gs-cat__sheet-head { display: none; }
    .gs-cat__group { padding: 12px 0; border-bottom: 1px solid #f0f4f8; }
    .gs-cat__group:first-of-type { padding-top: 0; }
    .gs-cat__label { display: block; font-weight: 700; font-size: 14px; color: #1a2540; margin-bottom: 8px; }
    .gs-cat__select { width: 100%; height: 42px; border: 1px solid #d5dee8; border-radius: 10px; padding: 0 10px; background: #fff; font-size: 14px; color: #1a2540; }
    .gs-cat__chips { display: flex; flex-wrap: wrap; gap: 6px; }
    .gs-cat__chip { margin: 0; cursor: pointer; }
    .gs-cat__chip input { position: absolute; opacity: 0; pointer-events: none; }
    .gs-cat__chip span { display: inline-block; padding: 7px 12px; border: 1px solid #d5dee8; border-radius: 20px; font-size: 13px; font-weight: 600; color: #334155; background: #fff; }
    .gs-cat__chip input:checked + span { background: #1a9e87; border-color: #1a9e87; color: #fff; }
    .gs-cat__chip input:focus-visible + span { outline: 2px solid #1a9e87; outline-offset: 2px; }
    .gs-cat__check { display: flex; align-items: center; gap: 8px; font-size: 14px; color: #1a2540; margin: 4px 0; cursor: pointer; min-height: 32px; }
    .gs-cat__check input { width: 18px; height: 18px; accent-color: #1a9e87; }
    .gs-cat__range-vals { font-weight: 700; color: #1a9e87; font-size: 14px; margin-bottom: 6px; }
    .gs-cat__range-track { position: relative; height: 28px; }
    .gs-cat__range-track input[type=range] { position: absolute; left: 0; right: 0; top: 6px; width: 100%; margin: 0; pointer-events: none; background: none; -webkit-appearance: none; appearance: none; height: 16px; }
    .gs-cat__range-track::before { content: ""; position: absolute; left: 0; right: 0; top: 13px; height: 4px; border-radius: 2px; background: #dbe4ec; }
    .gs-cat__range-track input[type=range]::-webkit-slider-thumb { pointer-events: auto; -webkit-appearance: none; width: 20px; height: 20px; border-radius: 50%; background: #1a9e87; border: 3px solid #fff; box-shadow: 0 1px 4px rgba(0,0,0,.3); cursor: pointer; position: relative; z-index: 2; }
    .gs-cat__range-track input[type=range]::-moz-range-thumb { pointer-events: auto; width: 16px; height: 16px; border-radius: 50%; background: #1a9e87; border: 3px solid #fff; box-shadow: 0 1px 4px rgba(0,0,0,.3); cursor: pointer; }
    .gs-cat__range-track input[type=range]::-webkit-slider-runnable-track { background: none; }
    .gs-cat__actions { padding-top: 14px; display: flex; flex-direction: column; gap: 8px; }
    .gs-cat__apply { width: 100%; height: 44px; border: 0; border-radius: 10px; background: #1a9e87; color: #fff; font-weight: 700; font-size: 15px; cursor: pointer; }
    .gs-cat__apply:hover { background: #158573; }
    .gs-cat__reset { text-align: center; color: #1a9e87; font-weight: 600; text-decoration: underline; font-size: 14px; }
    .gs-cat__toolbar { display: flex; flex-wrap: wrap; align-items: center; gap: 10px 16px; margin-bottom: 18px; }
    .gs-cat__count { font-size: 15px; color: #475569; margin-right: auto; }
    .gs-cat__count strong { color: #1a2540; }
    .gs-cat__open { display: none; align-items: center; gap: 6px; height: 40px; padding: 0 14px; border: 1px solid #d5dee8; border-radius: 10px; background: #fff; font-weight: 700; color: #1a2540; }
    .gs-cat__badge { background: #1a9e87; color: #fff; border-radius: 10px; font-size: 12px; padding: 1px 7px; }
    .gs-cat__sort { display: flex; align-items: center; gap: 8px; margin: 0; font-size: 14px; color: #475569; }
    .gs-cat__sort select { height: 40px; border: 1px solid #d5dee8; border-radius: 10px; padding: 0 10px; background: #fff; font-weight: 600; color: #1a2540; }
    .gs-cat__views { display: inline-flex; border: 1px solid #d5dee8; border-radius: 10px; overflow: hidden; background: #fff; }
    .gs-cat__view { display: inline-flex; align-items: center; gap: 6px; height: 38px; padding: 0 12px; font-size: 14px; font-weight: 600; color: #475569; }
    .gs-cat__view.is-on { background: #1a9e87; color: #fff; }
    .gs-cat__view:hover { color: #1a9e87; }
    .gs-cat__view.is-on:hover { color: #fff; }
    .gs-cat__fav { display: inline-flex; align-items: center; gap: 6px; height: 40px; padding: 0 12px; border: 1px solid #fecaca; border-radius: 10px; color: #dc2626; font-weight: 600; font-size: 14px; background: #fff; }
    .gs-fav-count { background: #ef4444; color: #fff; border-radius: 10px; font-size: 12px; padding: 1px 7px; }
    .gs-cat__map-wrap { margin-bottom: 24px; }
    .gs-cat__map { height: 480px; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; z-index: 1; }
    .gs-cat__map-note { font-size: 13px; color: #64748b; margin: 8px 0 0; }
    .gs-pin { background: #1a9e87; color: #fff; font-weight: 800; font-size: 12px; padding: 4px 8px; border-radius: 14px; white-space: nowrap; box-shadow: 0 2px 6px rgba(0,0,0,.3); border: 2px solid #fff; transform: translate(-50%, -50%); display: inline-block; }
    .gs-pin.is-on { background: #0f172a; }
    .gs-pop { width: 210px; font-family: inherit; }
    .gs-pop img { width: 100%; height: 110px; object-fit: cover; border-radius: 8px; margin-bottom: 6px; }
    .gs-pop b { display: block; font-size: 14px; color: #1a2540; margin-bottom: 2px; }
    .gs-pop .gs-pop-price { color: #1a9e87; font-weight: 800; }
    .gs-pop a.gs-pop-go { display: inline-block; margin-top: 6px; background: #1a9e87; color: #fff !important; border-radius: 6px; padding: 5px 10px; font-weight: 600; font-size: 13px; }
    .gs-cat__pager { text-align: center; margin: 36px 0 10px; }
    .gs-cat__more { display: inline-block; padding: 12px 34px; border-radius: 50px; border: 2px solid #1a9e87; color: #1a9e87; font-weight: 700; margin-bottom: 14px; }
    .gs-cat__more:hover { background: #1a9e87; color: #fff; }
    .gs-cat__pages { display: flex; justify-content: center; gap: 6px; flex-wrap: wrap; }
    .gs-cat__pages a, .gs-cat__pages span { min-width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; border: 1px solid #d5dee8; background: #fff; color: #1a2540; font-weight: 600; padding: 0 8px; }
    .gs-cat__pages span[aria-current] { background: #1a9e87; border-color: #1a9e87; color: #fff; }
    .gs-cat__backdrop { display: none; }
    .gs-cat .gscard-grid { grid-template-columns: repeat(3, minmax(0, 1fr)) !important; }
    .gscard-hstars { color: #f59e0b; font-size: 12px; letter-spacing: 1px; margin: -2px 0 6px; }
    @media (max-width: 1200px) { .gs-cat .gscard-grid { grid-template-columns: repeat(2, minmax(0, 1fr)) !important; } }
    @media (max-width: 991px) {
        .gs-cat { grid-template-columns: 1fr; }
        .gs-cat__open { display: inline-flex; }
        .gs-cat__filters { position: fixed; left: 0; right: 0; bottom: 0; top: auto; z-index: 100001; max-height: 85vh; border-radius: 18px 18px 0 0; transform: translateY(105%); transition: transform .25s ease; padding: 0 18px 18px; box-shadow: 0 -8px 30px rgba(0,0,0,.2); }
        .gs-cat.is-sheet .gs-cat__filters { transform: none; }
        .gs-cat__sheet-head { display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; background: #fff; padding: 14px 0 10px; margin-bottom: 4px; z-index: 2; font-size: 17px; }
        .gs-cat__close { border: 0; background: #f1f5f9; width: 36px; height: 36px; border-radius: 50%; font-size: 14px; }
        .gs-cat__actions { position: sticky; bottom: -18px; background: #fff; padding-bottom: 18px; }
        .gs-cat.is-sheet .gs-cat__backdrop { display: block; position: fixed; inset: 0; background: rgba(15,23,42,.45); z-index: 100000; }
        .gs-cat__count { width: 100%; }
        .gs-cat__map { height: 380px; }
    }
    @media (max-width: 767px) { .gs-cat .gscard-grid { grid-template-columns: repeat(1, minmax(0, 1fr)) !important; } .gs-cat__sort span { display: none; } }
</style>
@endpush

@push('js_section')
@if ($view === 'map')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
@endif
<script>
(function () {
    var root = document.getElementById('gsCatalog');
    if (!root) return;
    var form = document.getElementById('gsCatForm');
    var mobile = window.matchMedia('(max-width: 991px)');

    // Range slider (two thumbs)
    var rg = root.querySelector('[data-gs-range]');
    if (rg) {
        var lo = rg.querySelector('[data-gs-lo]'), hi = rg.querySelector('[data-gs-hi]');
        var loT = rg.querySelector('[data-gs-range-lo]'), hiT = rg.querySelector('[data-gs-range-hi]');
        var upd = function (e) {
            if (+lo.value > +hi.value) { if (e && e.target === lo) lo.value = hi.value; else hi.value = lo.value; }
            loT.textContent = '$' + lo.value; hiT.textContent = '$' + hi.value;
        };
        lo.addEventListener('input', upd); hi.addEventListener('input', upd);
    }

    // Clean URL: drop empty values and untouched price bounds
    form.addEventListener('submit', function () {
        if (rg) {
            if (+lo.value <= +rg.getAttribute('data-min')) lo.disabled = true;
            if (+hi.value >= +rg.getAttribute('data-max')) hi.disabled = true;
        }
        Array.prototype.forEach.call(form.elements, function (el) {
            if (el.name && !el.disabled && (el.type !== 'checkbox' && el.type !== 'radio') && el.value === '') el.disabled = true;
            if (el.type === 'radio' && el.checked && el.value === '') el.disabled = true;
        });
    });
    // Desktop: apply at once
    form.addEventListener('change', function () {
        if (!mobile.matches) { if (form.requestSubmit) form.requestSubmit(); else form.submit(); }
    });

    var sortSel = root.querySelector('[data-gs-sort]');
    if (sortSel) sortSel.addEventListener('change', function () {
        form.querySelector('[data-gs-sort-input]').value = sortSel.value === 'popular' ? '' : sortSel.value;
        if (form.requestSubmit) form.requestSubmit(); else form.submit();
    });

    // Mobile bottom sheet
    var open = function () { root.classList.add('is-sheet'); document.body.style.overflow = 'hidden'; };
    var close = function () { root.classList.remove('is-sheet'); document.body.style.overflow = ''; };
    root.addEventListener('click', function (e) {
        if (e.target.closest('[data-gs-cat-open]')) open();
        else if (e.target.closest('[data-gs-cat-close]')) close();
    });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });

    @if ($view === 'map')
    // Map with price pins
    var pts = @json($mapPoints);
    var el = document.getElementById('gsCatMap');
    if (el && window.L) {
        var map = L.map(el, { scrollWheelZoom: false });
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18, attribution: '&copy; OpenStreetMap' }).addTo(map);
        var esc = function (s) { return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; }); };
        var bounds = [];
        pts.forEach(function (p) {
            var label = p.price_text ? 'от ' + p.price_text : '•';
            var m = L.marker([p.lat, p.lng], { icon: L.divIcon({ className: '', html: '<span class="gs-pin">' + esc(label) + '</span>', iconSize: [0, 0] }), title: p.title, keyboard: true });
            m.bindPopup('<div class="gs-pop">' + (p.img ? '<img src="' + esc(p.img) + '" alt="" loading="lazy">' : '') + '<b>' + esc(p.title) + '</b>' + (p.price_text ? '<span class="gs-pop-price">от ' + esc(p.price_text) + '</span>' : '') + '<br><a class="gs-pop-go" href="' + esc(p.url) + '">Подробнее →</a></div>');
            m.addTo(map);
            bounds.push([p.lat, p.lng]);
        });
        if (bounds.length) map.fitBounds(bounds, { padding: [40, 40], maxZoom: 13 }); else map.setView([41.0, 46.5], 6);
    }
    @endif
})();
</script>
@endpush
