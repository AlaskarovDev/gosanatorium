{{--
    Task 34: «Когда ехать выгоднее?» — minimum price per person per night for the next 12 months.
    Params: $pcMonths (list of ['y','m']), $pcPrices (index => price|null), $pcUrl (callable(y, m): ?string),
            $pcNote (string), $pcId (string, optional), $pcClickable (bool).
--}}
@php
    $pcNames = ['', 'Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'];
    $pcFull = ['', 'январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь'];
    $pcVals = array_values(array_filter($pcPrices, fn ($p) => $p !== null && $p > 0));
    $pcMin = $pcVals ? min($pcVals) : null;
    $pcMax = $pcVals ? max($pcVals) : null;
    // «Выгодно»: months within 3% of the cheapest month, only when prices really differ
    $pcCheap = fn ($p) => $p !== null && $pcMin !== null && $pcMax > $pcMin * 1.03 && $p <= $pcMin * 1.03;
@endphp
@if (count($pcVals) >= 2)
<div class="gs-pc" id="{{ $pcId ?? 'section-price-calendar' }}">
    <h2 class="gs-pc__title">Когда ехать выгоднее?</h2>
    <p class="gs-pc__sub">Минимальная цена по месяцам ({{ $pcLabel ?? 'за ночь с человека' }}). {{ $pcNote ?? '' }}</p>
    <div class="gs-pc__strip" role="list">
        @foreach ($pcMonths as $i => $mo)
            @php
                $p = $pcPrices[$i] ?? null;
                $h = ($p !== null && $pcMax > 0) ? max(14, (int) round(100 * $p / $pcMax)) : 0;
                $url = $p !== null ? $pcUrl($mo['y'], $mo['m']) : null;
                $cheap = $pcCheap($p);
                $tag = $url ? 'a' : 'div';
            @endphp
            <{{ $tag }} role="listitem" @if ($url) href="{{ $url }}" rel="nofollow" @endif
                @class(['gs-pc__m', 'is-cheap' => $cheap, 'is-empty' => $p === null])
                aria-label="{{ $pcFull[$mo['m']] }} {{ $mo['y'] }}: {{ $p !== null ? 'от ' . currency($p) : 'нет цены' }}{{ $cheap ? ', выгодно' : '' }}">
                <span class="gs-pc__price">@if ($p !== null){!! currency_span($p) !!}@else—@endif</span>
                <span class="gs-pc__bar"><span style="height: {{ $h }}%"></span></span>
                <span class="gs-pc__name">{{ $pcNames[$mo['m']] }}<small>@if ($mo['m'] === 1 || $i === 0){{ $mo['y'] }}@else&nbsp;@endif</small></span>
                @if ($cheap)<span class="gs-pc__tag">Выгодно</span>@endif
            </{{ $tag }}>
        @endforeach
    </div>
    @if (! empty($pcClickable))
        <p class="gs-pc__hint">Нажмите на месяц — покажем свободные номера на 7 ночей.</p>
    @endif
</div>
@once
@push('style_section')
<style>
    .gs-pc { background: #fff; border: 1px solid #e8eef4; border-radius: 16px; padding: 22px; margin: 0 0 24px; }
    .gs-pc__title { font-size: 22px; font-weight: 800; color: #1a2540; margin: 0 0 4px; }
    .gs-pc__sub { font-size: 14px; color: #64748b; margin: 0 0 16px; }
    .gs-pc__strip { display: grid; grid-template-columns: repeat(12, minmax(0, 1fr)); gap: 6px; align-items: end; }
    .gs-pc__m { position: relative; display: flex; flex-direction: column; align-items: center; gap: 6px; padding: 26px 2px 8px; border-radius: 10px; color: #1a2540; text-decoration: none; transition: background .15s; min-width: 0; }
    a.gs-pc__m:hover, a.gs-pc__m:focus-visible { background: #f0fdfa; color: #1a2540; }
    .gs-pc__price { font-size: 12px; font-weight: 700; white-space: nowrap; color: #334155; }
    .gs-pc__bar { display: flex; align-items: flex-end; width: 70%; max-width: 34px; height: 90px; background: #f1f5f9; border-radius: 6px; overflow: hidden; }
    .gs-pc__bar span { display: block; width: 100%; background: #94a3b8; border-radius: 6px 6px 0 0; }
    .gs-pc__m.is-cheap .gs-pc__bar span { background: #1a9e87; }
    .gs-pc__m.is-cheap .gs-pc__price { color: #1a9e87; }
    .gs-pc__name { font-size: 13px; font-weight: 600; color: #475569; text-align: center; line-height: 1.1; }
    .gs-pc__name small { display: block; font-size: 10px; color: #94a3b8; font-weight: 500; }
    .gs-pc__tag { position: absolute; top: 4px; left: 50%; transform: translateX(-50%); background: #1a9e87; color: #fff; font-size: 10px; font-weight: 700; border-radius: 8px; padding: 1px 6px; white-space: nowrap; }
    .gs-pc__m.is-empty { opacity: .5; }
    .gs-pc__hint { font-size: 13px; color: #64748b; margin: 10px 0 0; }
    @media (max-width: 991px) {
        .gs-pc { padding: 16px; }
        .gs-pc__strip { display: flex; overflow-x: auto; scroll-snap-type: x proximity; -webkit-overflow-scrolling: touch; padding-bottom: 6px; }
        .gs-pc__m { flex: 0 0 66px; scroll-snap-align: start; }
        .gs-pc__title { font-size: 19px; }
    }
</style>
@endpush
@endonce
@endif
