@extends('layout_inner_page')

@php
    $isRu = (request()->attributes->get('display_lang') ?: app()->getLocale()) === 'ru';
    $pageTitle = $isRu ? 'Направления лечения' : 'Treatment directions';
@endphp

@section('title')
    <title>{{ $isRu ? 'Направления лечения в санаториях Азербайджана и Грузии' : 'Treatment directions in sanatoriums of Azerbaijan and Georgia' }}</title>
    <meta name="title" content="{{ $pageTitle }}">
    <meta name="description" content="{{ $isRu ? 'Направления лечения: суставы, кожа, неврология, гинекология, урология, ЖКТ, диабет и другие. Выберите направление и подберите санаторий.' : 'Treatment directions: joints, skin, neurology, gynecology, urology, digestive system, diabetes and more. Choose a direction and find a sanatorium.' }}">
@endsection

@push('style_section')
    <style>
        .tg-page-hero { background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #334155 100%); padding: 180px 0 60px; color: #fff; text-align: center; }
        .tg-page-hero h1 { color: #fff; font-size: 38px; margin-bottom: 12px; }
        .tg-page-hero p { color: rgba(255,255,255,.85); max-width: 720px; margin: 0 auto; font-size: 16px; }
        .tg-page-hero .tg-breadcrumb { margin-bottom: 18px; font-size: 14px; color: rgba(255,255,255,.6); }
        .tg-page-hero .tg-breadcrumb a { color: rgba(255,255,255,.85); }
        .tg-page-section { padding: 50px 0 80px; background: #f8fafc; }
        .nl-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; max-width: 1100px; margin: 0 auto; padding: 0 16px; }
        .nl-card { display: flex; flex-direction: column; background: #fff; border: 1px solid #e5e8eb; border-radius: 14px; overflow: hidden; text-decoration: none; transition: box-shadow .25s ease, border-color .25s ease, transform .25s ease; }
        .nl-card:hover { border-color: #1a5c36; box-shadow: 0 8px 24px rgba(26,92,54,.14); transform: translateY(-2px); }
        .nl-img { height: 150px; background: #1a5c36; }
        .nl-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .nl-body { display: flex; flex-direction: column; flex: 1; padding: 18px 20px 20px; }
        .nl-name { font-size: 18px; font-weight: 700; color: #1a1a1a; line-height: 1.3; margin: 0 0 8px; }
        .nl-text { font-size: 14px; color: #64748b; line-height: 1.5; margin: 0 0 14px; }
        .nl-meta { margin-top: auto; display: flex; justify-content: space-between; align-items: center; gap: 10px; font-size: 14px; }
        .nl-count { color: #64748b; }
        .nl-link { font-weight: 600; color: #1a5c36; }
        @media (max-width: 1023px) { .nl-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 767px) { .tg-page-hero { padding: 130px 0 40px; } .tg-page-hero h1 { font-size: 28px; } .nl-grid { grid-template-columns: 1fr; } .nl-img { height: 130px; } }
    </style>
@endpush

@section('front-content')
    <section class="tg-page-hero">
        <div class="container">
            <div class="tg-breadcrumb">
                <a href="{{ route('home') }}">{{ $isRu ? 'Главная' : __('translate.Home') }}</a>
                <span> / {{ $pageTitle }}</span>
            </div>
            <h1>{{ $pageTitle }}</h1>
            <p>{{ $isRu ? 'Выберите направление лечения, чтобы увидеть санатории, где его проводят.' : 'Choose a treatment direction to see the sanatoriums that offer it.' }}</p>
        </div>
    </section>

    <section class="tg-page-section">
        <div class="nl-grid">
            @foreach ($diseases as $disease)
                @php $count = $disease->sanatoriums()->where('services.status', true)->count(); @endphp
                <a href="{{ localize_front_url('/sanatoriyi?disease=' . $disease->slug) }}" class="nl-card">
                    <div class="nl-img">
                        @if ($disease->image)
                            <img src="{{ $disease->image_url }}" alt="{{ $disease->name }}" loading="lazy">
                        @endif
                    </div>
                    <div class="nl-body">
                        <h2 class="nl-name">{{ $disease->name }}</h2>
                        @if ($disease->description)
                            <p class="nl-text">{{ \Illuminate\Support\Str::words(strip_tags($disease->description), 22, '…') }}</p>
                        @endif
                        <div class="nl-meta">
                            <span class="nl-count">
                                @if ($isRu)
                                    {{ $count }} {{ trans_choice('санаторий|санатория|санаториев', $count, [], 'ru') }}
                                @else
                                    {{ $count }} {{ $count === 1 ? 'sanatorium' : 'sanatoriums' }}
                                @endif
                            </span>
                            <span class="nl-link">{{ $isRu ? 'Смотреть санатории' : 'View sanatoriums' }} →</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endsection
