@extends('layout_inner_page')

@section('title')
    <title>Сравнение санаториев | Gosanatorium</title>
    <meta name="title" content="Сравнение санаториев">
    <meta name="description" content="Сравните санатории по цене, рейтингу, процедурам, питанию и расстоянию от аэропорта.">
    <meta name="robots" content="noindex, follow">
@endsection

@push('style_section')
    <style>
        .gs-cmp-hero { background: #1e293b; padding: 150px 0 40px; color: #fff; text-align: center; }
        .gs-cmp-hero h1 { color: #fff; font-size: 34px; margin: 0; }
        .gs-cmp-section { padding: 40px 0 80px; background: #f8fafc; }
        .gs-cmp-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; background: #fff; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,.08); }
        .gs-cmp-table { border-collapse: collapse; width: 100%; min-width: 640px; }
        .gs-cmp-table th, .gs-cmp-table td { padding: 14px 16px; border-bottom: 1px solid #eef2f6; vertical-align: middle; text-align: center; font-size: 15px; color: #1a2540; }
        .gs-cmp-table th.gs-cmp-param { text-align: left; background: #f8fafc; color: #475569; font-weight: 600; min-width: 150px; position: sticky; left: 0; z-index: 1; }
        .gs-cmp-table td { min-width: 200px; }
        .gs-cmp-photo { width: 100%; max-width: 260px; height: 160px; object-fit: cover; border-radius: 10px; }
        .gs-cmp-name { font-weight: 700; color: #1a2540; }
        .gs-cmp-yes { color: #16a34a; font-weight: 700; }
        .gs-cmp-no { color: #dc2626; font-weight: 700; }
        .gs-cmp-na { color: #94a3b8; }
        .gs-cmp-star { color: #f59e0b; }
        .gs-cmp-cta { display: inline-block; background: #1a9e87; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 600; }
        .gs-cmp-cta:hover { background: #158573; color: #fff; }
        .gs-cmp-remove { display: block; margin: 6px auto 0; font-size: 12px; color: #94a3b8; background: none; border: 0; cursor: pointer; }
        .gs-cmp-empty { text-align: center; padding: 60px 16px; font-size: 18px; color: #334155; }
        .gs-cmp-actions { text-align: center; margin-top: 20px; }
        .gs-cmp-share { background: #fff; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 18px; cursor: pointer; font-weight: 600; color: #334155; }
        @media (max-width: 767px) { .gs-cmp-hero { padding: 120px 0 30px; } .gs-cmp-hero h1 { font-size: 26px; } .gs-cmp-table td { min-width: 170px; } }
    </style>
@endpush

@section('front-content')
    <section class="gs-cmp-hero">
        <div class="container"><h1>Сравнение санаториев</h1></div>
    </section>
    <section class="gs-cmp-section">
        <div class="container">
            @if ($rows->count() < 1)
                <div class="gs-cmp-empty" id="gs-cmp-empty">
                    <p>Вы ещё не выбрали санатории для сравнения.</p>
                    <a class="gs-cmp-cta" href="{{ localize_front_url('/sanatoriyi') }}">Выбрать санатории</a>
                </div>
            @else
                @php
                    $yn = function ($v) {
                        if ($v === true) return '<span class="gs-cmp-yes">✓ Да</span>';
                        if ($v === false) return '<span class="gs-cmp-no">✕ Нет</span>';
                        return '<span class="gs-cmp-na">—</span>';
                    };
                    $dash = fn ($v) => $v !== null && $v !== '' ? e($v) : '<span class="gs-cmp-na">—</span>';
                @endphp
                <div class="gs-cmp-scroll">
                    <table class="gs-cmp-table">
                        <tbody>
                            <tr>
                                <th class="gs-cmp-param">Фото</th>
                                @foreach ($rows as $r)
                                    <td>
                                        @if ($r['photo'])
                                            <a href="{{ $r['url'] }}"><img class="gs-cmp-photo" src="{{ $r['photo'] }}" alt="{{ $r['title'] }}"></a>
                                        @else <span class="gs-cmp-na">—</span> @endif
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <th class="gs-cmp-param">Название</th>
                                @foreach ($rows as $r)
                                    <td>
                                        <a class="gs-cmp-name" href="{{ $r['url'] }}">{{ $r['title'] }}</a>
                                        <button type="button" class="gs-cmp-remove" data-gs-cmp-remove="{{ $r['slug'] }}">Убрать из сравнения</button>
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <th class="gs-cmp-param">Цена за сутки</th>
                                @foreach ($rows as $r)<td>{!! $dash($r['price']) !!}</td>@endforeach
                            </tr>
                            <tr>
                                <th class="gs-cmp-param">Рейтинг</th>
                                @foreach ($rows as $r)
                                    <td>
                                        @if ($r['rating'] !== null)
                                            <span class="gs-cmp-star">★</span> <strong>{{ number_format($r['rating'], 1) }}</strong>
                                            <span class="gs-cmp-na">({{ $r['reviews'] }})</span>
                                        @else <span class="gs-cmp-na">Нет отзывов</span> @endif
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <th class="gs-cmp-param">Процедуры в день</th>
                                @foreach ($rows as $r)<td>{!! $dash($r['procedures']) !!}</td>@endforeach
                            </tr>
                            <tr>
                                <th class="gs-cmp-param">Питание</th>
                                @foreach ($rows as $r)<td>{!! $yn($r['meals']) !!}</td>@endforeach
                            </tr>
                            <tr>
                                <th class="gs-cmp-param">Анализы при заезде</th>
                                @foreach ($rows as $r)<td>{!! $yn($r['tests']) !!}</td>@endforeach
                            </tr>
                            <tr>
                                <th class="gs-cmp-param">Осмотр врача</th>
                                @foreach ($rows as $r)<td>{!! $yn($r['doctor']) !!}</td>@endforeach
                            </tr>
                            <tr>
                                <th class="gs-cmp-param">Расстояние от аэропорта</th>
                                @foreach ($rows as $r)<td>{!! $r['airport'] !== null ? e($r['airport']) . ' км' : '<span class="gs-cmp-na">—</span>' !!}</td>@endforeach
                            </tr>
                            <tr>
                                <th class="gs-cmp-param">Детям</th>
                                @foreach ($rows as $r)<td>{!! $dash($r['child']) !!}</td>@endforeach
                            </tr>
                            <tr>
                                <th class="gs-cmp-param">Ссылка</th>
                                @foreach ($rows as $r)<td><a class="gs-cmp-cta" href="{{ $r['url'] }}">Забронировать</a></td>@endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="gs-cmp-actions">
                    <button type="button" class="gs-cmp-share" id="gs-cmp-share">Скопировать ссылку на сравнение</button>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('js_section')
<script>
    window.GS_COMPARE_PAGE = {!! json_encode($rows->map(fn ($r) => ['slug' => $r['slug'], 'title' => $r['title']])->values()) !!};
</script>
@endpush
