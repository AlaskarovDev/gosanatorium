@php
    $ph = !empty($item->is_placeholder);
    $svc = $item->service;
    $img = $item->image ?: ($svc?->thumbnail?->file_path);
    $img = is_string($img) && $img !== '' ? $img : null;
    $name = $item->sanatorium_name ?: ($svc?->translation?->title);
    // Rating only from published reviews (5-point scale)
    $rvAvg = $svc ? \Modules\TourBooking\App\Models\Review::where('service_id', $svc->id)->where('status', true)->avg('rating') : null;
    $rating = $rvAvg ? number_format((float) $rvAvg, 1) : null;
    $pct = $item->discount_label;
    $countdown = $ph ? null : $item->countdown_label;
    $sanUrl = !$ph && $svc && $svc->slug ? $item->url : null;
@endphp
<div class="gsp-card promo-card {{ $ph ? 'gsp-card--placeholder' : '' }}"
     @if (!$ph && $item->valid_until) data-gsp-deadline="{{ \Carbon\Carbon::parse($item->valid_until->toDateString(), 'Asia/Baku')->endOfDay()->toIso8601String() }}" @endif>
    <div class="gsp-media promo-card__media">
        @if ($img)
            <img class="gsp-img promo-card__image" src="{{ asset($img) }}" alt="{{ $name ?: $item->title }}" loading="lazy">
        @else
            <span class="gsp-media-fallback">{{ $pct ? $pct . '%' : '%' }}</span>
        @endif
        @if ($ph)
            <span class="gsp-flag gsp-flag--season">Сезонное предложение</span>
        @elseif ($item->is_hot && ($item->badge_text ?? '') !== '🔥')
            <span class="gsp-flag gsp-flag--hot">Горящее предложение 🔥</span>
        @endif
        @if (!empty($item->badge_text))
            <span class="gsp-pct promo-card__badge">{{ $item->badge_text }}</span>
        @elseif ($pct)
            <span class="gsp-pct promo-card__badge">−{{ $pct }}%</span>
        @endif
    </div>
    <div class="gsp-body promo-card__body">
        @if ($name)
            <div class="gsp-san promo-card__sanatorium">@if ($sanUrl)<a href="{{ $sanUrl }}">{{ $name }}</a>@else{{ $name }}@endif @if ($rating)<span class="gsp-rating">★ {{ $rating }}</span>@endif</div>
        @endif
        <div class="gsp-title promo-card__title">{{ $item->title }}</div>
        @if ($item->description)
            <p class="gsp-desc promo-card__desc">{{ $ph ? $item->description : $item->shortText(110) }}</p>
        @endif
        @if (!$ph && ($item->original_price || $item->discounted_price))
            <div class="gsp-prices">
                @if ($item->original_price && $item->discounted_price)
                    <s class="gsp-old">${{ rtrim(rtrim(number_format($item->original_price, 2, '.', ''), '0'), '.') }}/ночь</s>
                @endif
                <span class="gsp-new">${{ rtrim(rtrim(number_format($item->discounted_price ?: $item->original_price, 2, '.', ''), '0'), '.') }}/ночь</span>
            </div>
        @endif
        @if (!$ph && $item->valid_until)
            <div class="gsp-valid promo-card__date">До {{ $item->valid_until->locale('ru')->translatedFormat('j F Y') }}</div>
        @endif
        @if ($countdown)
            <div class="gsp-timer">{{ $countdown }}</div>
        @endif
        <a class="gsp-btn promo-card__btn" href="{{ !$ph && $item->slug ? $item->detail_url : url('/akcii') }}">Подробнее →</a>
    </div>
</div>
