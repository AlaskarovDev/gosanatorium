@extends('layout_inner_page')

{{-- Task 4: destination page /hotels/{slug} — server-rendered cards, SEO texts, filters, FAQ --}}

@section('title')
    <title>{{ $seo['title'] }}</title>
    <meta name="title" content="{{ $seo['title'] }}">
    <meta name="description" content="{{ $seo['description'] }}">
    <meta property="og:title" content="{{ $seo['title'] }}">
    <meta property="og:description" content="{{ $seo['description'] }}">
    <meta property="og:type" content="website">
    @if ($destination->hero_image || $destination->image)
        <meta property="og:image" content="{{ asset($destination->hero_image ?: $destination->image) }}">
    @endif
@endsection

@php
    $heroImg = $destination->hero_image ?: $destination->image;
    $sanatoriyiUrl = localize_front_url('/sanatoriyi');
    $pageUrl = url()->current();
    $resetUrl = $pageUrl;
    $f = $filters;
@endphp

@push('style_section')
    <style>
        .gsd-hero { position: relative; background-color: #1e293b; background-size: cover; background-position: center; padding: 180px 0 56px; color: #fff; }
        .gsd-hero .container { position: relative; z-index: 1; }
        .gsd-crumbs { font-size: 14px; color: rgba(255,255,255,.7); margin: 0 0 16px; padding: 0; list-style: none; display: flex; flex-wrap: wrap; gap: 6px; }
        .gsd-crumbs li + li::before { content: "/"; margin-right: 6px; color: rgba(255,255,255,.5); }
        .gsd-crumbs a { color: rgba(255,255,255,.9); }
        .gsd-hero h1 { color: #fff; font-size: 40px; line-height: 1.2; margin: 0 0 10px; text-shadow: 0 2px 8px rgba(0,0,0,.45); text-transform: none; }
        .gsd-hero-sub { color: rgba(255,255,255,.85); font-size: 16px; margin: 0; }
        .gsd-section { padding: 40px 0 70px; background: #f8fafc; }
        .gsd-intro { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 22px 26px; margin-bottom: 26px; color: #334155; font-size: 16px; line-height: 1.7; }
        .gsd-intro > :last-child, .gsd-seo > :last-child { margin-bottom: 0; }
        .gsd-filters { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px 18px; margin-bottom: 22px; display: flex; flex-wrap: wrap; gap: 12px 16px; align-items: flex-end; }
        .gsd-f { display: flex; flex-direction: column; gap: 4px; min-width: 0; }
        .gsd-f label, .gsd-f .gsd-f-label { font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .4px; margin: 0; }
        .gsd-f select, .gsd-f input[type=number] { height: 42px; border: 1px solid #cbd5e1; border-radius: 10px; padding: 0 10px; font-size: 14px; color: #0f172a; background: #fff; }
        .gsd-f input[type=number] { width: 96px; }
        .gsd-price { display: flex; align-items: center; gap: 6px; }
        .gsd-checks { display: flex; gap: 14px; align-items: center; height: 42px; flex-wrap: wrap; }
        .gsd-checks label { display: inline-flex; align-items: center; gap: 6px; font-size: 14px; color: #0f172a; text-transform: none; letter-spacing: 0; font-weight: 500; cursor: pointer; }
        .gsd-checks input { width: 18px; height: 18px; accent-color: #1a9e87; }
        .gsd-actions { display: flex; gap: 10px; align-items: center; margin-left: auto; }
        .gsd-btn { height: 42px; padding: 0 22px; border: 0; border-radius: 10px; background: #1a9e87; color: #fff; font-weight: 700; font-size: 14px; cursor: pointer; }
        .gsd-btn:hover { opacity: .92; }
        .gsd-reset { font-size: 14px; color: #1a9e87; font-weight: 600; text-decoration: underline; }
        .gsd-count { color: #475569; font-size: 15px; margin: 0 0 14px; }
        .gsd-empty { text-align: center; padding: 40px 16px; background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; }
        .gsd-empty p { font-size: 17px; color: #334155; margin-bottom: 16px; }
        .gsd-seo { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 26px 28px; margin-top: 34px; color: #334155; font-size: 16px; line-height: 1.75; }
        .gsd-seo h2, .gsd-faq h2 { font-size: 26px; margin: 0 0 14px; color: #0f172a; text-transform: none; }
        .gsd-seo h3 { font-size: 20px; margin: 18px 0 10px; color: #0f172a; text-transform: none; }
        .gsd-seo table { width: 100%; border-collapse: collapse; margin: 12px 0; }
        .gsd-seo td, .gsd-seo th { border: 1px solid #e2e8f0; padding: 8px 10px; }
        .gsd-faq { margin-top: 34px; }
        .gsd-faq details { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 10px; }
        .gsd-faq summary { cursor: pointer; list-style: none; padding: 16px 48px 16px 20px; font-weight: 700; font-size: 16px; color: #0f172a; position: relative; }
        .gsd-faq summary::-webkit-details-marker { display: none; }
        .gsd-faq summary::after { content: "+"; position: absolute; right: 20px; top: 50%; transform: translateY(-50%); font-size: 22px; color: #1a9e87; font-weight: 400; }
        .gsd-faq details[open] summary::after { content: "−"; }
        .gsd-faq .gsd-faq-a { padding: 0 20px 16px; color: #334155; line-height: 1.7; }
        @media (max-width: 991px) {
            .gsd-actions { margin-left: 0; }
        }
        @media (max-width: 767px) {
            .gsd-hero { padding: 130px 0 36px; }
            .gsd-hero h1 { font-size: 28px; }
            .gsd-filters { padding: 14px; }
            .gsd-f { flex: 1 1 calc(50% - 8px); }
            .gsd-f select { width: 100%; }
            .gsd-f.gsd-f--price, .gsd-f.gsd-f--checks, .gsd-actions { flex: 1 1 100%; }
            .gsd-price { width: 100%; }
            .gsd-price input[type=number] { flex: 1 1 0; min-width: 0; width: auto; }
            .gsd-actions .gsd-btn { flex: 1; }
            .gsd-intro, .gsd-seo { padding: 18px; }
        }
    </style>
@endpush

@section('front-content')
    <section class="gsd-hero" @if ($heroImg) style="background-image: linear-gradient(rgba(15,23,42,.6), rgba(15,23,42,.72)), url('{{ asset($heroImg) }}');" @endif>
        <div class="container">
            <ol class="gsd-crumbs">
                <li><a href="{{ localize_front_url('/') }}">Главная</a></li>
                <li><a href="{{ $sanatoriyiUrl }}">Санатории</a></li>
                <li aria-current="page">{{ $listing->city() }}</li>
            </ol>
            <h1>{{ $seo['h1'] }}</h1>
            <p class="gsd-hero-sub">
                {{ $totalCount }} {{ \Modules\TourBooking\App\Services\DestinationListing::plural($totalCount, 'санаторий', 'санатория', 'санаториев') }}@if ($listing->country() !== '') · {{ $listing->country() }}@endif
            </p>
        </div>
    </section>

    <section class="gsd-section">
        <div class="container">
            @if (filled(strip_tags((string) $destination->intro_text)))
                <div class="gsd-intro">{!! clean($destination->intro_text) !!}</div>
            @endif

            <form class="gsd-filters" method="GET" action="{{ $pageUrl }}">
                <div class="gsd-f">
                    <label for="gsdSort">Сортировка</label>
                    <select id="gsdSort" name="sort" onchange="this.form.submit()">
                        @foreach ($sorts as $key => $label)
                            <option value="{{ $key }}" @selected($f['sort'] === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                @if ($f['range_min'] !== null)
                    <div class="gsd-f gsd-f--price">
                        <span class="gsd-f-label">Цена за ночь, $</span>
                        <div class="gsd-price">
                            <input type="number" name="price_min" min="0" step="1" inputmode="numeric" aria-label="Цена от"
                                   placeholder="{{ $f['range_min'] }}" value="{{ $f['price_min'] !== null ? (int) $f['price_min'] : '' }}">
                            <span>—</span>
                            <input type="number" name="price_max" min="0" step="1" inputmode="numeric" aria-label="Цена до"
                                   placeholder="{{ $f['range_max'] }}" value="{{ $f['price_max'] !== null ? (int) $f['price_max'] : '' }}">
                        </div>
                    </div>
                @endif

                <div class="gsd-f">
                    <label for="gsdRating">Рейтинг</label>
                    <select id="gsdRating" name="rating">
                        <option value="">Любой</option>
                        @foreach ([4.5, 4, 3.5] as $r)
                            <option value="{{ $r }}" @selected((string) $f['rating'] === (string) $r)>от {{ str_replace('.', ',', (string) $r) }}</option>
                        @endforeach
                    </select>
                </div>

                @if ($f['diseases']->isNotEmpty())
                    <div class="gsd-f">
                        <label for="gsdDisease">Лечение</label>
                        <select id="gsdDisease" name="disease">
                            <option value="">Все направления</option>
                            @foreach ($f['diseases'] as $d)
                                <option value="{{ $d->slug }}" @selected($f['disease'] === $d->slug)>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if ($f['has_pool'] || $f['has_transfer'])
                    <div class="gsd-f gsd-f--checks">
                        <span class="gsd-f-label">Удобства</span>
                        <div class="gsd-checks">
                            @if ($f['has_pool'])
                                <label><input type="checkbox" name="pool" value="1" @checked($f['pool'])> Бассейн</label>
                            @endif
                            @if ($f['has_transfer'])
                                <label><input type="checkbox" name="transfer" value="1" @checked($f['transfer'])> Трансфер</label>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="gsd-actions">
                    <button type="submit" class="gsd-btn">Показать</button>
                    @if ($filtersActive)
                        <a class="gsd-reset" href="{{ $resetUrl }}">Сбросить</a>
                    @endif
                </div>
            </form>

            <p class="gsd-count">
                Найдено: <strong>{{ $services->total() }}</strong>
                {{ \Modules\TourBooking\App\Services\DestinationListing::plural($services->total(), 'санаторий', 'санатория', 'санаториев') }}
            </p>

            @if ($services->count() > 0)
                @include('tourbooking::front.services.gscard-grid', ['allServices' => $services, 'gsRoomCta' => true, 'gsTitleTag' => 'h2'])
                @if ($services->hasPages())
                    <div class="text-center mt-50 mb-30">
                        @include('components.front.custom-pagination', ['items' => $services])
                    </div>
                @endif
            @else
                <div class="gsd-empty">
                    @if ($filtersActive)
                        <p>По вашему запросу ничего не найдено. Попробуйте изменить фильтры.</p>
                        <a href="{{ $resetUrl }}" class="gsd-btn" style="display:inline-flex;align-items:center">Сбросить фильтры</a>
                    @else
                        <p>Санатории этого направления скоро появятся.</p>
                        <a href="{{ $sanatoriyiUrl }}" class="gsd-btn" style="display:inline-flex;align-items:center">Все санатории</a>
                    @endif
                </div>
            @endif

            {{-- Task 34: average of the sanatoriums' minimum prices per month --}}
            @php
                $pcIds = \Modules\TourBooking\App\Models\Service::where('status', true)->where('destination_id', $destination->id)->pluck('id')->map(fn ($i) => (int) $i)->all();
                foreach (['person', 'room'] as $pcBasis) {
                    $pcData = \Modules\TourBooking\App\Services\FromPrice::monthlyMins($pcIds, 12, $pcBasis);
                    $pcAvg = [];
                    foreach ($pcData['months'] as $pcI => $pcMo) {
                        $pcVals = collect($pcData['prices'])->map(fn ($row) => $row[$pcI] ?? null)->filter(fn ($v) => $v !== null && $v > 0);
                        $pcAvg[$pcI] = $pcVals->isNotEmpty() ? round($pcVals->avg(), 2) : null;
                    }
                    if (count(array_filter($pcAvg)) >= 2) {
                        break;
                    }
                }
            @endphp
            <div class="mt-40">
                @include('tourbooking::front.services.price-calendar', [
                    'pcMonths' => $pcData['months'],
                    'pcPrices' => $pcAvg,
                    'pcUrl' => fn ($y, $m) => null,
                    'pcClickable' => false,
                    'pcLabel' => \Modules\TourBooking\App\Services\FromPrice::bases()[$pcBasis] ?? 'за ночь',
                    'pcNote' => 'Средняя из минимальных цен санаториев направления, ориентировочно.',
                ])
            </div>

            @if (filled(strip_tags((string) $destination->seo_text)))
                <div class="gsd-seo">{!! clean($destination->seo_text) !!}</div>
            @endif

            @if (count($faqItems))
                <div class="gsd-faq">
                    <h2>Частые вопросы</h2>
                    @foreach ($faqItems as $i => $item)
                        <details @if ($i === 0) open @endif>
                            <summary>{{ $item['q'] }}</summary>
                            <div class="gsd-faq-a">{!! nl2br(e($item['a'])) !!}</div>
                        </details>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @php
        $gsdSchemas = [[
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Главная', 'item' => \App\Support\StructuredData::abs(localize_front_url('/'))],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Санатории', 'item' => \App\Support\StructuredData::abs($sanatoriyiUrl)],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $listing->city(), 'item' => $pageUrl],
            ],
        ], [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => $seo['h1'],
            'itemListElement' => $services->values()->map(fn ($s, $i) => [
                '@type' => 'ListItem',
                'position' => ($services->firstItem() ?? 1) + $i,
                'url' => route('front.tourbooking.services.show', ['slug' => $s->slug]),
                'name' => (string) $s->translation?->title,
            ])->all(),
        ]];
        if (count($faqItems)) {
            $gsdSchemas[] = [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => array_map(fn ($item) => [
                    '@type' => 'Question',
                    'name' => $item['q'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a']],
                ], $faqItems),
            ];
        }
    @endphp
    @foreach ($gsdSchemas as $schema)
        <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
    @endforeach
@endsection
