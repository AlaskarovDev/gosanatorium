@extends('layout_inner_page')

@section('title')
    <title>Отзывы о GoSanatorium — клиенты о бронировании санаториев</title>
    <meta name="title" content="Отзывы о GoSanatorium">
    <meta name="description" content="Отзывы клиентов о работе GoSanatorium: подбор санатория, бронирование, трансфер. Оставьте свой отзыв.">
    @if (method_exists($reviews, 'currentPage') && $reviews->currentPage() > 1)
        <meta name="robots" content="noindex, follow">
    @endif
@endsection

@push('style_section')
<style>
    .gscr-hero { background: #1e293b; padding: 150px 0 40px; color: #fff; text-align: center; }
    .gscr-hero h1 { color: #fff; font-size: 34px; margin: 0 0 10px; }
    .gscr-hero p { color: rgba(255,255,255,.75); margin: 0 auto; max-width: 640px; }
    .gscr-section { padding: 40px 0 80px; background: #f8fafc; }
    .gscr-grid { display: grid; grid-template-columns: minmax(0, 1fr) 380px; gap: 28px; align-items: start; }
    .gscr-summary { display: flex; align-items: center; gap: 14px; background: #fff; border: 1px solid #e8eef4; border-radius: 14px; padding: 16px 20px; margin-bottom: 18px; }
    .gscr-summary__score { font-size: 34px; font-weight: 800; color: #1a2540; line-height: 1; }
    .gscr-stars { color: #f59e0b; letter-spacing: 1px; }
    .gscr-stars .is-off { color: #e2e8f0; }
    .gscr-item { background: #fff; border: 1px solid #e8eef4; border-radius: 14px; padding: 18px 20px; margin-bottom: 14px; }
    .gscr-item__head { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 6px 12px; margin-bottom: 8px; }
    .gscr-item__name { font-weight: 700; color: #1a2540; }
    .gscr-item__meta { font-size: 13px; color: #94a3b8; }
    .gscr-item__text { color: #334155; line-height: 1.6; white-space: pre-line; overflow-wrap: anywhere; }
    .gscr-verified { display: inline-block; background: #dcfce7; color: #166534; border-radius: 10px; font-size: 12px; font-weight: 700; padding: 2px 8px; margin-left: 6px; }
    .gscr-reply { margin-top: 12px; background: #f0fdfa; border-left: 3px solid #1a9e87; padding: 10px 12px; border-radius: 6px; font-size: 14px; color: #334155; }
    .gscr-reply b { color: #0f766e; }
    .gscr-empty { background: #fff; border: 1px dashed #cbd5e1; border-radius: 14px; padding: 30px; text-align: center; color: #475569; }
    .gscr-form { background: #fff; border: 1px solid #e8eef4; border-radius: 16px; padding: 22px; position: sticky; top: 100px; }
    .gscr-form h2 { font-size: 22px; font-weight: 800; margin: 0 0 14px; color: #1a2540; }
    .gscr-form label { display: block; font-weight: 600; font-size: 14px; color: #1a2540; margin: 10px 0 4px; }
    .gscr-form input[type=text], .gscr-form textarea { width: 100%; border: 1px solid #d5dee8; border-radius: 10px; padding: 10px 12px; font-size: 15px; background: #fff; }
    .gscr-form textarea { min-height: 130px; resize: vertical; }
    .gscr-rate { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 4px; }
    .gscr-rate input { position: absolute; opacity: 0; pointer-events: none; }
    .gscr-rate label { margin: 0; font-size: 30px; line-height: 1; color: #e2e8f0; cursor: pointer; }
    .gscr-rate input:checked ~ label, .gscr-rate label:hover, .gscr-rate label:hover ~ label { color: #f59e0b; }
    .gscr-rate input:focus-visible + label { outline: 2px solid #1a9e87; border-radius: 4px; }
    .gscr-hp { position: absolute; left: -9999px; width: 1px; height: 1px; overflow: hidden; }
    .gscr-submit { width: 100%; height: 48px; border: 0; border-radius: 10px; background: #1a9e87; color: #fff; font-weight: 700; font-size: 16px; margin-top: 16px; cursor: pointer; }
    .gscr-submit:hover { background: #158573; }
    .gscr-note { font-size: 12px; color: #94a3b8; margin: 10px 0 0; }
    .gscr-ok { background: #dcfce7; color: #166534; border-radius: 10px; padding: 12px 14px; font-weight: 600; margin-bottom: 10px; }
    .gscr-err { color: #b91c1c; font-size: 13px; margin-top: 4px; }
    @media (max-width: 991px) { .gscr-grid { grid-template-columns: 1fr; } .gscr-form { position: static; } }
    @media (max-width: 767px) { .gscr-hero { padding: 120px 0 30px; } .gscr-hero h1 { font-size: 26px; } }
</style>
@endpush

@section('front-content')
    <section class="gscr-hero">
        <div class="container">
            <h1>Отзывы о GoSanatorium</h1>
            <p>Что клиенты говорят о нашей работе: подбор санатория, бронирование, связь с менеджером. Отзывы о самих санаториях — на их страницах.</p>
        </div>
    </section>

    @include('partials.rated_by')

    <section class="gscr-section">
        <div class="container">
            <div class="gscr-grid">
                <div>
                    @if ($count > 0)
                        <div class="gscr-summary">
                            <span class="gscr-summary__score">{{ number_format($avg, 1) }}</span>
                            <div>
                                <div class="gscr-stars" aria-hidden="true">@for ($i = 1; $i <= 5; $i++)<span @class(['is-off' => $i > round($avg)])>★</span>@endfor</div>
                                <div>{{ $count }} {{ trans_choice('отзыв|отзыва|отзывов', $count, [], 'ru') }} о компании</div>
                            </div>
                        </div>
                        @foreach ($reviews as $r)
                            <article class="gscr-item">
                                <div class="gscr-item__head">
                                    <div>
                                        <span class="gscr-item__name">{{ $r->name }}</span>@if ($r->country)<span class="gscr-item__meta">, {{ $r->country }}</span>@endif
                                        @if ($r->is_verified)<span class="gscr-verified">✓ Бронировал через нас</span>@endif
                                    </div>
                                    <div>
                                        <span class="gscr-stars" aria-label="Оценка {{ $r->rating }} из 5">@for ($i = 1; $i <= 5; $i++)<span @class(['is-off' => $i > $r->rating])>★</span>@endfor</span>
                                        <span class="gscr-item__meta">{{ $r->created_at->format('d.m.Y') }}</span>
                                    </div>
                                </div>
                                <div class="gscr-item__text">{{ $r->text }}</div>
                                @if ($r->reply)
                                    <div class="gscr-reply"><b>Ответ GoSanatorium:</b> {{ $r->reply }}</div>
                                @endif
                            </article>
                        @endforeach
                        @if (method_exists($reviews, 'hasPages') && $reviews->hasPages())
                            <div class="text-center mt-4">{{ $reviews->links() }}</div>
                        @endif
                    @else
                        <div class="gscr-empty">Здесь будут отзывы о нашей работе. Станьте первым — расскажите, как прошло бронирование.</div>
                    @endif
                </div>

                <aside id="review-form">
                    <form class="gscr-form" method="POST" action="{{ localize_front_url('/otzyvy') }}" novalidate>
                        @csrf
                        <h2>Оставить отзыв</h2>
                        @if (session('review_sent'))
                            <div class="gscr-ok" role="status">Спасибо! Отзыв появится на сайте после проверки модератором.</div>
                        @endif
                        <label for="crName">Имя *</label>
                        <input id="crName" type="text" name="name" maxlength="80" required value="{{ old('name') }}" autocomplete="name">
                        @error('name')<div class="gscr-err">{{ $message }}</div>@enderror

                        <label for="crCountry">Страна</label>
                        <input id="crCountry" type="text" name="country" maxlength="80" value="{{ old('country') }}" placeholder="Казахстан" autocomplete="country-name">

                        <label>Оценка *</label>
                        <div class="gscr-rate" role="radiogroup" aria-label="Оценка">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="crR{{ $i }}" name="rating" value="{{ $i }}" @checked((int) old('rating', 5) === $i)><label for="crR{{ $i }}" title="{{ $i }}">★</label>
                            @endfor
                        </div>
                        @error('rating')<div class="gscr-err">{{ $message }}</div>@enderror

                        <label for="crText">Отзыв *</label>
                        <textarea id="crText" name="text" maxlength="3000" required placeholder="Как прошёл подбор и бронирование? Что понравилось, что улучшить?">{{ old('text') }}</textarea>
                        @error('text')<div class="gscr-err">{{ $message }}</div>@enderror

                        <label for="crCode">Код бронирования (необязательно)</label>
                        <input id="crCode" type="text" name="booking_code" maxlength="40" value="{{ old('booking_code') }}">

                        <div class="gscr-hp" aria-hidden="true"><label for="crWeb">Сайт</label><input id="crWeb" type="text" name="website" tabindex="-1" autocomplete="off"></div>

                        <button type="submit" class="gscr-submit">Отправить отзыв</button>
                        <p class="gscr-note">Отзыв публикуется после модерации. Мы не публикуем телефоны и e-mail.</p>
                    </form>
                </aside>
            </div>
        </div>
    </section>
@endsection
