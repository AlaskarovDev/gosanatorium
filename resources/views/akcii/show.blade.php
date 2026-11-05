@extends('layout_inner_page')

@php
    $svc = $akcia->service;
    $img = $akcia->image ?: ($svc?->thumbnail?->file_path);
    $sanName = $akcia->sanatorium_name ?: ($svc?->translation?->title);
    $pct = $akcia->discount_label;
    $expired = $akcia->valid_until && $akcia->valid_until->isPast() && !$akcia->valid_until->isToday();
    $sanUrl = $svc && $svc->slug ? route('front.tourbooking.services.show', ['slug' => $svc->slug]) : null;
@endphp

@section('title')
    <title>{{ $akcia->title }} | GoSanatorium.com</title>
    <meta name="title" content="{{ $akcia->title }} | GoSanatorium.com">
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags((string) ($akcia->description ?: $akcia->title)), 160) }}">
@endsection

@push('style_section')
<style>
    .akcia-page { padding: 50px 0 70px; background: #fff; }
    .akcia-img { width: 100%; max-height: 480px; object-fit: cover; border-radius: 12px; display: block; margin-bottom: 28px; }
    .akcia-page h1 { font-size: 32px; font-weight: 700; color: #1f2d3d; margin: 0 0 16px; }
    .akcia-meta { color: #6b7280; font-size: 15px; margin-bottom: 8px; }
    .akcia-meta a { color: #1a9e87; font-weight: 600; }
    .akcia-badge { display: inline-block; background: #e5383b; color: #fff; font-weight: 700; padding: 4px 12px; border-radius: 6px; margin-bottom: 12px; }
    .akcia-text { font-size: 16px; line-height: 1.7; color: #374151; margin: 20px 0 28px; }
    .akcia-cta { display: inline-block; background: #1a9e87; color: #fff; padding: 14px 28px; border-radius: 8px; font-weight: 700; text-decoration: none; }
    .akcia-cta:hover { background: #158572; color: #fff; }
    .akcia-back { display: inline-block; margin-left: 16px; color: #1a9e87; font-weight: 600; }
</style>
@endpush

@section('front-content')
    <section class="akcia-page">
        <div class="container">
            @if ($img)
                <img class="akcia-img" src="{{ asset($img) }}" alt="{{ $akcia->title }}">
            @endif
            @if (!empty($akcia->badge_text))
                <div class="akcia-badge">{{ $akcia->badge_text }}</div>
            @elseif ($pct)
                <div class="akcia-badge">−{{ $pct }}%</div>
            @endif
            <h1>{{ $akcia->title }}</h1>
            @if ($akcia->valid_until)
                <div class="akcia-meta">
                    @if ($expired)
                        Акция завершилась {{ $akcia->valid_until->locale('ru')->translatedFormat('j F Y') }}
                    @else
                        Действует до: {{ $akcia->valid_until->locale('ru')->translatedFormat('j F Y') }}
                    @endif
                </div>
            @endif
            @if ($sanName)
                <div class="akcia-meta">Санаторий:
                    @if ($sanUrl)<a href="{{ $sanUrl }}">{{ $sanName }}</a>@else{{ $sanName }}@endif
                </div>
            @endif
            @if ($akcia->discounted_price || $akcia->original_price)
                <div class="akcia-meta">
                    Цена:
                    @if ($akcia->original_price && $akcia->discounted_price)<s>${{ rtrim(rtrim(number_format($akcia->original_price, 2, '.', ''), '0'), '.') }}</s>@endif
                    <b>${{ rtrim(rtrim(number_format($akcia->discounted_price ?: $akcia->original_price, 2, '.', ''), '0'), '.') }}/ночь</b>
                </div>
            @endif
            <div class="akcia-text">
                @if ($akcia->content)
                    {!! clean($akcia->content) !!}
                @elseif ($akcia->description)
                    {!! nl2br(e($akcia->description)) !!}
                @endif
            </div>
            <a class="akcia-cta" href="{{ $sanUrl ?: route('front.tourbooking.services') }}">Забронировать со скидкой</a>
            <a class="akcia-back" href="/ru/aktsii">← Все акции</a>
        </div>
    </section>
@endsection
