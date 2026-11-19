@php
    // Rating and count come only from published reviews (status = 1), 5-point scale.
    // No published reviews → no badge (no made-up numbers).
    $realCount = $service->active_reviews_count ?? null;
    $realAvg = $service->active_reviews_avg_rating ?? null;
    if ($realCount === null || ($realCount > 0 && $realAvg === null)) {
        $realCount = \Modules\TourBooking\App\Models\Review::where('service_id', $service->id ?? 0)->where('status', true)->count();
        $realAvg = $realCount > 0 ? \Modules\TourBooking\App\Models\Review::where('service_id', $service->id)->where('status', true)->avg('rating') : null;
    }
    $useReal = (int) $realCount > 0 && $realAvg !== null;
    $rGeneral = $useReal ? number_format((float) $realAvg, 1) : null;
    $rCount = (int) $realCount;
    $rCountLabel = \Modules\TourBooking\App\Models\Review::countLabel($rCount);
@endphp
@if ($useReal)
@if (str_contains($class ?? '', 'gs-rating-badges--card'))
<div style="position:absolute;top:10px;right:10px;z-index:10;display:flex;flex-direction:column;align-items:center;gap:2px;">
    <div style="display:flex;gap:4px;">
        <div style="background:#4CAF50;color:#fff;border-radius:6px;width:48px;padding:6px 4px;text-align:center;line-height:1.2;">
            <div style="font-size:18px;font-weight:700;">{{ $rGeneral }}</div>
            <div style="font-size:10px;opacity:0.9;">Общий</div>
        </div>
    </div>
    <div style="font-size:11px;color:#fff;background:rgba(0,0,0,0.45);border-radius:4px;padding:2px 5px;margin-top:2px;">{{ $rCountLabel }} · из 5</div>
</div>
@else
<div class="gs-rating-badges {{ $class ?? '' }}">
    <div style="display:flex;gap:4px;">
        <div style="background:#4CAF50;color:#fff;border-radius:6px;width:48px;padding:6px 4px;text-align:center;line-height:1.2;">
            <div style="font-size:20px;font-weight:700;">{{ $rGeneral }}</div>
            <div style="font-size:11px;opacity:0.9;">Общий</div>
        </div>
    </div>
    <div style="font-size:12px;color:#888;margin-top:4px;text-align:center;">Отзывы гостей: {{ $rCount }} · из 5</div>
</div>
@endif
@endif
