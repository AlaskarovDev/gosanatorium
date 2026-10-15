@extends('layout_inner_page')

@php
    $typeName = $serviceType?->translation?->name ?? $serviceType?->name ?? ($title ?? '');
@endphp

@section('title')
    <title>{{ $typeName }} | Санатории</title>
    <meta name="title" content="{{ $typeName }}">
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($serviceType?->translation?->description ?? $serviceType?->description ?? $typeName), 160) }}">
@endsection

@push('style_section')
    <style>
        .tg-page-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #334155 100%);
            padding: 180px 0 60px;
            color: #fff;
            text-align: center;
        }
        .tg-page-hero h1 { color: #fff; font-size: 38px; margin-bottom: 12px; }
        .tg-page-hero p { color: rgba(255,255,255,.7); max-width: 720px; margin: 0 auto; }
        .tg-page-hero .tg-breadcrumb { margin-bottom: 18px; font-size: 14px; color: rgba(255,255,255,.6); }
        .tg-page-hero .tg-breadcrumb a { color: rgba(255,255,255,.85); }
        .tg-page-section { padding: 60px 0 80px; background: #f8fafc; }
        @media (max-width: 767px) { .tg-page-hero { padding: 130px 0 40px; } .tg-page-hero h1 { font-size: 28px; } }
    </style>
@endpush

@section('front-content')
    <section class="tg-page-hero">
        <div class="container">
            <div class="tg-breadcrumb">
                <a href="{{ route('home') }}">{{ __('translate.Home') }}</a>
                <span> / {{ $typeName }}</span>
            </div>
            <h1>{{ $typeName }}</h1>
            @if ($serviceType?->translation?->description ?? $serviceType?->description)
                <p>{{ \Illuminate\Support\Str::limit(strip_tags($serviceType?->translation?->description ?? $serviceType?->description), 220) }}</p>
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
