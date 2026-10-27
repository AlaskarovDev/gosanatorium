@php
    $slugRatings = [
        'chinar-hotel-spa-naftalan-2026' => [9.6, 9.7, 50],
        'garabagh-resort-spa-naftalan-2026' => [9.4, 9.5, 38],
        'gashalti-health-hotel-naftalan-2026' => [9.3, 9.4, 31],
        'sun-city-hotel-spa-naftalan-lechenie-2026' => [9.1, 9.2, 24],
        'naftalan-central-wellness-sanatorii' => [8.9, 9.0, 19],
        'borjomi-palace-sanatoriy-2026' => [9.2, 9.3, 22],
        'tskaltubo-spa-resort' => [9.0, 9.1, 17],
        'maricel-naftalan-center-baku-lechenie-2026' => [8.8, 9.0, 12],
    ];
    $ratingData = $slugRatings[$service->slug ?? ''] ?? [8.8, 9.0, 12];
    $rGeneral = number_format($ratingData[0], 1);
    $rTreat = number_format($ratingData[1], 1);
    $rCount = $ratingData[2];
@endphp
<div class="gs-rating-badges {{ $class ?? '' }}">
    <div style="display:flex;gap:4px;">
        <div style="background:#4CAF50;color:#fff;border-radius:6px;width:48px;padding:6px 4px;text-align:center;line-height:1.2;">
            <div style="font-size:20px;font-weight:700;">{{ $rGeneral }}</div>
            <div style="font-size:11px;opacity:0.9;">Общий</div>
        </div>
        <div style="background:#26A69A;color:#fff;border-radius:6px;width:48px;padding:6px 4px;text-align:center;line-height:1.2;">
            <div style="font-size:20px;font-weight:700;">{{ $rTreat }}</div>
            <div style="font-size:11px;opacity:0.9;">Лечение</div>
        </div>
    </div>
    <div style="font-size:12px;color:#888;margin-top:4px;text-align:center;">Отзывы гостей: {{ $rCount }}</div>
</div>
