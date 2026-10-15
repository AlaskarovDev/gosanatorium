@extends('layout_inner_page')

@php
    $destName = $destination?->translation?->name ?? $destination?->name;
    $destDesc = $destination?->translation?->description ?? $destination?->description;
@endphp

@section('title')
    <title>{{ $destName }} | Санатории</title>
    <meta name="title" content="{{ $destName }}">
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($destDesc ?? $destName), 160) }}">
@endsection

@push('style_section')
    <style>
        .tg-page-hero {
            position: relative;
            padding: 180px 0 60px;
            color: #fff;
            text-align: center;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #334155 100%);
        }
        .tg-page-hero.has-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(15,23,42,.55), rgba(15,23,42,.85));
        }
        .tg-page-hero .container { position: relative; z-index: 2; }
        .tg-page-hero h1 { color: #fff; font-size: 40px; margin-bottom: 12px; }
        .tg-page-hero p { color: rgba(255,255,255,.8); max-width: 760px; margin: 0 auto; }
        .tg-page-hero .tg-breadcrumb { margin-bottom: 18px; font-size: 14px; color: rgba(255,255,255,.7); }
        .tg-page-hero .tg-breadcrumb a { color: rgba(255,255,255,.9); }
        .tg-page-section { padding: 60px 0 80px; background: #f8fafc; }
        @media (max-width: 767px) { .tg-page-hero { padding: 130px 0 40px; } .tg-page-hero h1 { font-size: 28px; } }
    </style>
@endpush

@section('front-content')
    <section class="tg-page-hero @if($destination?->image) has-bg @endif"
        @if($destination?->image) style="background: url('{{ asset($destination->image) }}') center/cover no-repeat;" @endif>
        <div class="container">
            <div class="tg-breadcrumb">
                <a href="{{ route('home') }}">{{ __('translate.Home') }}</a>
                <span> / </span>
                <a href="{{ route('front.tourbooking.destinations') }}">{{ __('translate.Destinations') }}</a>
                <span> / {{ $destName }}</span>
            </div>
            <h1>{{ $destName }}</h1>
            @if ($destDesc)
                <p>{{ \Illuminate\Support\Str::limit(strip_tags($destDesc), 240) }}</p>
            @endif
        </div>
    </section>

    <section class="tg-page-section">
        <div class="container">
            @include('tourbooking::front.services.services-item', [
                'allServices' => $services,
                'isListView' => 'false',
            ])
        </div>
    </section>
@endsection
