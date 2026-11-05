@php
    $ph = !empty($item->is_placeholder);
    $svc = $item->service;
    $img = $item->image ?: ($svc?->thumbnail?->file_path);
    $name = $item->sanatorium_name ?: ($svc?->translation?->title);
    $rating = $svc && (float) $svc->rating_general > 0 ? number_format((float) $svc->rating_general, 1) : null;
    $pct = $item->discount_label;
    $showTimer = !$ph && $item->is_hot && $item->valid_until;
@endphp
<div class="gsp-card {{ $ph ? 'gsp-card--placeholder' : '' }}"
     @if ($showTimer) data-gsp-deadline="{{ $item->valid_until->copy()->endOfDay()->toIso8601String() }}" @endif>
    <div class="gsp-media">
        @if ($img)
            <img class="gsp-img" src="{{ asset($img) }}" alt="{{ $name ?: $item->title }}" loading="lazy">
        @else
            <span class="gsp-media-fallback">{{ $pct ? $pct . '%' : '%' }}</span>
        @endif
        @if ($ph)
            <span class="gsp-flag gsp-flag--season">Сезонное предложение</span>
        @elseif ($item->is_hot && ($item->badge_text ?? '') !== '🔥')
            <span class="gsp-flag gsp-flag--hot">Горящее предложение 🔥</span>
        @endif
        @if (!empty($item->badge_text))
            <span class="gsp-pct">{{ $item->badge_text }}</span>
        @elseif ($pct)
            <span class="gsp-pct">−{{ $pct }}%</span>
        @endif
    </div>
    <div class="gsp-body">
        @if ($name)
            <div class="gsp-san">{{ $name }} @if ($rating)<span class="gsp-rating">★ {{ $rating }}</span>@endif</div>
        @endif
        <div class="gsp-title">{{ $item->title }}</div>
        @if ($item->description)
            <p class="gsp-desc" style="white-space:pre-line">{{ $item->description }}</p>
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
            <div class="gsp-valid">Действует до {{ $item->valid_until->locale('ru')->translatedFormat('j F Y') }}</div>
        @endif
        @if ($showTimer)
            <div class="gsp-timer">Осталось: <b data-gsp-timer>…</b></div>
        @endif
        <div class="gsp-expired">Предложение истекло</div>
        <a class="gsp-btn" href="/ru/aktsii">Подробнее</a>
    </div>
</div>
