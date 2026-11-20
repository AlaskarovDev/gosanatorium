@extends('layout_inner_page')

@section('title')
    <title>Избранные санатории | Gosanatorium</title>
    <meta name="title" content="Избранные санатории">
    <meta name="description" content="Санатории, которые вы отметили ♡. Список хранится в вашем браузере.">
    <meta name="robots" content="noindex, follow">
@endsection

@push('style_section')
    <style>
        .gs-fav-hero { background: #1e293b; padding: 150px 0 40px; color: #fff; text-align: center; }
        .gs-fav-hero h1 { color: #fff; font-size: 34px; margin: 0 0 8px; }
        .gs-fav-hero p { color: rgba(255,255,255,.75); margin: 0; }
        .gs-fav-section { padding: 40px 0 80px; background: #f8fafc; }
        .gs-fav-empty { text-align: center; padding: 60px 16px; font-size: 18px; color: #334155; }
        .gs-fav-empty .gs-fav-heart { font-size: 44px; color: #ef4444; line-height: 1; margin-bottom: 12px; }
        .gs-fav-cta { display: inline-block; background: #1a9e87; color: #fff; border-radius: 8px; padding: 12px 24px; font-weight: 600; margin-top: 12px; }
        .gs-fav-cta:hover { background: #158573; color: #fff; }
        .gs-fav-tip { text-align: center; color: #64748b; font-size: 14px; margin-top: 24px; }
        @media (max-width: 767px) { .gs-fav-hero { padding: 120px 0 30px; } .gs-fav-hero h1 { font-size: 26px; } }
    </style>
@endpush

@section('front-content')
    <section class="gs-fav-hero">
        <div class="container">
            <h1>Избранные санатории</h1>
            <p>Отмечайте ♡ понравившиеся санатории — список сохранится на этом устройстве.</p>
        </div>
    </section>
    <section class="gs-fav-section">
        <div class="container">
            @if ($services->isEmpty())
                <div class="gs-fav-empty">
                    <div class="gs-fav-heart" aria-hidden="true">♡</div>
                    <p>В избранном пока пусто.</p>
                    <a class="gs-fav-cta" href="{{ localize_front_url('/sanatoriyi') }}">Смотреть санатории</a>
                </div>
            @else
                @include('tourbooking::front.services.gscard-grid', ['allServices' => $services, 'gsTitleTag' => 'h2'])
                <p class="gs-fav-tip">Отметьте «Сравнить» у 2–3 санаториев, чтобы увидеть их в таблице.</p>
            @endif
        </div>
    </section>
@endsection

@push('js_section')
<script>window.GS_FAV_PAGE = true;</script>
@endpush
