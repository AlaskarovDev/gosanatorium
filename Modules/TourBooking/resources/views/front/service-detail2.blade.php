@extends('layout_inner_page2')

@section('title')
    <title>Services</title>
    <meta name="title" content="Services">
    <meta name="description" content="Services">
@endsection

@section('front-content')
<style>
*{box-sizing:border-box}.gstd-wrap{background:#f8fafb}.gstd-breadcrumb{padding:14px 0;font-size:13px;color:#9ca3af}.gstd-breadcrumb a{color:#1a9e87;text-decoration:none}.gstd-breadcrumb span{margin:0 6px}.gstd-htop{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;padding-bottom:16px;flex-wrap:wrap}.gstd-hname{font-size:28px;font-weight:900;color:#1a1a2e;text-transform:none;letter-spacing:-0.5px;line-height:1.2}.gstd-hmeta{display:flex;align-items:center;gap:14px;margin-top:8px;flex-wrap:wrap}.gstd-htype{display:inline-flex;align-items:center;gap:5px;background:#f0faf8;color:#1a9e87;font-size:12px;font-weight:700;padding:4px 12px;border-radius:50px;text-transform:uppercase;letter-spacing:.8px}.gstd-hloc{display:flex;align-items:center;gap:5px;font-size:13px;color:#6b7280}.gstd-hloc i,.gstd-guarantee i,.gstd-room-feat i{color:#1a9e87}.gstd-hstars{color:#f59e0b;font-size:13px}.gstd-hrcnt{font-size:12px;color:#9ca3af}.gstd-hactions{display:flex;gap:10px}.gstd-savebtn{display:flex;align-items:center;gap:6px;padding:9px 18px;border:1.5px solid #e5e7eb;border-radius:50px;background:#fff;color:#6b7280;font-size:13px;font-weight:600;cursor:pointer;transition:all .2s;font-family:inherit}.gstd-savebtn:hover{border-color:#1a9e87;color:#1a9e87}.gstd-gallery{display:grid;grid-template-columns:1fr 320px;gap:8px;border-radius:16px 16px 0 0;overflow:hidden}.gstd-gmain{position:relative;height:400px;overflow:hidden}.gstd-gmain img{width:100%;height:100%;object-fit:cover}.gstd-gside{display:grid;grid-template-rows:repeat(3,1fr);gap:8px;height:400px}.gstd-gside img{width:100%;height:100%;object-fit:cover;cursor:pointer;transition:opacity .2s}.gstd-gside img:hover{opacity:.85}.gstd-gmore{position:absolute;bottom:16px;right:16px;background:rgba(0,0,0,.65);color:#fff;font-size:13px;font-weight:700;padding:8px 16px;border-radius:50px;cursor:pointer;border:none}.gstd-tabs-bar{display:flex;border-bottom:2px solid #e5e7eb;background:#fff;position:sticky;top:0;z-index:100}.gstd-tab{padding:14px 22px;font-size:14px;font-weight:600;color:#6b7280;cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-2px;transition:all .2s;white-space:nowrap;background:none;border-top:none;border-left:none;border-right:none;font-family:inherit}.gstd-tab:hover{color:#1a9e87}.gstd-tab.active{color:#1a9e87;border-bottom-color:#1a9e87}.gstd-body{display:grid;grid-template-columns:1fr 340px;gap:28px;padding:32px 0 60px;align-items:start}.gstd-panel{display:none}.gstd-panel.active{display:block}.gstd-section-title{font-size:20px;font-weight:800;color:#1a1a2e;margin-bottom:20px;text-transform:none;padding-bottom:12px;border-bottom:2px solid #f0faf8}.gstd-about-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:28px}.gstd-about-card{background:#fff;border-radius:14px;padding:18px;text-align:center;box-shadow:0 2px 10px rgba(0,0,0,.06)}.gstd-about-icon{width:42px;height:42px;border-radius:11px;background:linear-gradient(135deg,#1a9e87,#2ec4a9);display:flex;align-items:center;justify-content:center;margin:0 auto 10px}.gstd-about-icon i{color:#fff;font-size:17px}.gstd-about-val{font-size:18px;font-weight:800;color:#1a1a2e}.gstd-about-lbl{font-size:12px;color:#9ca3af;margin-top:3px}.gstd-desc-text{font-size:14px;color:#4b5563;line-height:1.8;text-transform:none}.gstd-desc-text p{margin-bottom:14px}.gstd-highlights{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin:20px 0}.gstd-hl-item{display:flex;align-items:center;gap:10px;background:#f0faf8;border-radius:10px;padding:11px 14px}.gstd-hl-item i{color:#1a9e87;font-size:14px;flex-shrink:0}.gstd-hl-text{font-size:13px;font-weight:600;color:#374151;text-transform:none}.gstd-diag-tags{display:flex;flex-wrap:wrap;gap:8px;margin:16px 0}.gstd-dtag{background:#f0faf8;border:1px solid #d1faf4;color:#1a9e87;font-size:12px;font-weight:600;padding:5px 12px;border-radius:50px}
.gstd-proc-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin:16px 0}.gstd-proc-item{display:flex;align-items:flex-start;gap:10px;background:#fff;border-radius:12px;padding:14px;box-shadow:0 2px 8px rgba(0,0,0,.06)}.gstd-proc-icon{width:36px;height:36px;border-radius:9px;background:linear-gradient(135deg,#e8f7f5,#d0f0eb);display:flex;align-items:center;justify-content:center;flex-shrink:0}.gstd-proc-icon i{color:#1a9e87;font-size:14px}.gstd-proc-name{font-size:13px;font-weight:700;color:#1a1a2e;text-transform:none}.gstd-proc-desc{font-size:12px;color:#6b7280;margin-top:2px}.gstd-pkg-table{width:100%;border-collapse:collapse;margin:16px 0;border-radius:14px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,.07)}.gstd-pkg-table th{background:linear-gradient(135deg,#1a9e87,#2ec4a9);color:#fff;font-size:13px;font-weight:700;padding:13px 16px;text-align:center}.gstd-pkg-table td{padding:12px 16px;font-size:13px;color:#374151;border-bottom:1px solid #f0f0f0;text-align:center;background:#fff}.gstd-pkg-table tr:last-child td{border-bottom:none}.gstd-pkg-table tr:hover td{background:#f9fffe}.gstd-pkg-table td:first-child{text-align:left;font-weight:600;color:#1a1a2e}.gstd-rooms-grid{display:flex;flex-direction:column;gap:16px}.gstd-room-card{background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.07);display:grid;grid-template-columns:220px 1fr;border:1.5px solid transparent;transition:border-color .2s}.gstd-room-card:hover{border-color:#1a9e87}.gstd-room-img{height:160px;overflow:hidden}.gstd-room-img img{width:100%;height:100%;object-fit:cover}.gstd-room-info{padding:18px 20px;display:flex;flex-direction:column;justify-content:space-between}.gstd-room-name{font-size:16px;font-weight:800;color:#1a1a2e;text-transform:none;margin-bottom:6px}.gstd-room-feats{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:12px}.gstd-room-feat{display:flex;align-items:center;gap:5px;font-size:12px;color:#6b7280}.gstd-room-bottom{display:flex;align-items:center;justify-content:space-between}.gstd-room-price{font-size:22px;font-weight:900;color:#1a9e87}.gstd-room-price-note{font-size:11px;color:#9ca3af;margin-top:2px}.gstd-room-btn{padding:10px 22px;background:linear-gradient(135deg,#1a9e87,#2ec4a9);color:#fff;font-size:13px;font-weight:700;border:none;border-radius:50px;cursor:pointer;font-family:inherit;transition:all .2s}.gstd-room-btn:hover{transform:translateY(-1px);box-shadow:0 6px 18px rgba(26,158,135,.35)}.gstd-inc-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}.gstd-inc-item{display:flex;align-items:center;gap:10px;background:#fff;border-radius:12px;padding:13px 15px;box-shadow:0 1px 6px rgba(0,0,0,.05)}.gstd-inc-check{width:24px;height:24px;border-radius:7px;background:linear-gradient(135deg,#1a9e87,#2ec4a9);display:flex;align-items:center;justify-content:center;flex-shrink:0}.gstd-inc-check i{color:#fff;font-size:11px}.gstd-inc-text{font-size:13px;font-weight:600;color:#374151;text-transform:none}.gstd-review-empty{text-align:center;padding:48px 20px;background:#fff;border-radius:16px}.gstd-review-empty i{font-size:42px;color:#e5e7eb;margin-bottom:16px;display:block}.gstd-review-empty p{color:#9ca3af;font-size:14px;margin-bottom:20px}.gstd-rev-btn{padding:11px 28px;background:linear-gradient(135deg,#1a9e87,#2ec4a9);color:#fff;font-size:14px;font-weight:700;border:none;border-radius:50px;cursor:pointer;font-family:inherit}.gstd-price-card{background:#fff;border-radius:20px;padding:26px;box-shadow:0 4px 20px rgba(0,0,0,.09);margin-bottom:20px;position:sticky;top:70px}.gstd-price-from{font-size:12px;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:.8px}.gstd-price-num{font-size:38px;font-weight:900;color:#1a9e87;line-height:1;margin:4px 0}.gstd-price-per{font-size:13px;color:#9ca3af}.gstd-price-divider{height:1px;background:#f0f0f0;margin:18px 0}.gstd-date-group{margin-bottom:14px}.gstd-date-lbl{font-size:12px;font-weight:600;color:#374151;margin-bottom:5px}.gstd-date-inp{width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;color:#1a1a2e;background:#f9fafb;outline:none;font-family:inherit;transition:border-color .2s}.gstd-date-inp:focus{border-color:#1a9e87;background:#fff}.gstd-nights-row{display:flex;align-items:center;justify-content:space-between;background:#f0faf8;border-radius:10px;padding:10px 14px;margin-bottom:14px}.gstd-nights-lbl{font-size:13px;color:#374151;font-weight:600}.gstd-nights-val{font-size:14px;font-weight:800;color:#1a9e87}.gstd-guests-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px}.gstd-guests-lbl{font-size:13px;font-weight:600;color:#374151}.gstd-guests-ctrl{display:flex;align-items:center;gap:10px}.gstd-gcbtn{width:30px;height:30px;border-radius:8px;border:1.5px solid #e5e7eb;background:#fff;font-size:16px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#374151;font-family:inherit;transition:all .2s}.gstd-gcbtn:hover{border-color:#1a9e87;color:#1a9e87}.gstd-gcval{font-size:15px;font-weight:700;color:#1a1a2e;min-width:20px;text-align:center}.gstd-book-btn{width:100%;padding:15px;background:linear-gradient(135deg,#1a9e87,#2ec4a9);color:#fff;font-size:15px;font-weight:700;border:none;border-radius:13px;cursor:pointer;font-family:inherit;box-shadow:0 6px 20px rgba(26,158,135,.35);transition:all .3s;margin-bottom:10px}.gstd-book-btn:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(26,158,135,.45)}.gstd-wa-btn{width:100%;padding:13px;background:#25d366;color:#fff;font-size:14px;font-weight:700;border:none;border-radius:13px;cursor:pointer;font-family:inherit;display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;transition:all .2s}.gstd-wa-btn:hover{background:#20b858;color:#fff;text-decoration:none}.gstd-guarantees{display:flex;flex-direction:column;gap:7px;margin-top:14px}.gstd-guarantee{display:flex;align-items:center;gap:8px;font-size:12px;color:#6b7280}.gstd-consult-card{background:#fff;border-radius:18px;padding:22px;box-shadow:0 4px 16px rgba(0,0,0,.08);text-align:center}.gstd-consult-photo{width:70px;height:70px;border-radius:50%;object-fit:cover;border:3px solid #1a9e87;margin:0 auto 12px}.gstd-consult-name{font-size:15px;font-weight:800;color:#1a1a2e;text-transform:none}.gstd-consult-role{font-size:12px;color:#9ca3af;margin-bottom:14px}.gstd-consult-links{display:flex;flex-direction:column;gap:8px}.gstd-consult-link{display:flex;align-items:center;justify-content:center;gap:8px;padding:10px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;transition:all .2s}.gstd-consult-link.wa{background:#f0fdf4;color:#25d366}.gstd-consult-link.wa:hover{background:#dcfce7;text-decoration:none}.gstd-consult-link.ph{background:#f0faf8;color:#1a9e87}.gstd-consult-link.ph:hover{background:#d0f0eb;text-decoration:none}@media(max-width:1100px){.gstd-body{grid-template-columns:1fr!important}}@media(max-width:900px){.gstd-gallery{grid-template-columns:1fr!important}.gstd-gside{display:none!important}.gstd-about-grid{grid-template-columns:1fr 1fr!important}.gstd-proc-grid{grid-template-columns:1fr!important}}@media(max-width:768px){.gstd-hname{font-size:20px!important}.gstd-tabs-bar{overflow-x:auto!important}.gstd-tab{padding:12px 14px!important;font-size:13px!important}.gstd-room-card{grid-template-columns:1fr!important}.gstd-room-img{height:200px!important}.gstd-inc-grid{grid-template-columns:1fr!important}.gstd-highlights{grid-template-columns:1fr!important}}
</style>
@php
    $tour = $service;
    $gstdTitle = $service?->translation?->title ?? $service->name ?? '';
    $gstdLoc = $service?->destination?->name ?? $service->location ?? '';
    $gstdMedia = $service->media->where('is_thumbnail', 1)->sortBy('display_order')->concat($service->media->where('is_thumbnail', 0)->sortBy('display_order'))->values();
    $gstdMainImg = isset($gstdMedia[0]) ? asset($gstdMedia[0]->file_path) : 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=900&q=80';
@endphp
<div class="gstd-wrap"><div class="container">
<div class="gstd-breadcrumb"><a href="/ru">Главная</a><span>›</span><a href="/ru/hotels">Санатории</a><span>›</span><span>{{ $gstdTitle }}</span></div>
<div class="gstd-htop">
<div><h1 class="gstd-hname">{{ $gstdTitle }}</h1>
<div class="gstd-hmeta">
<span class="gstd-htype"><i class="fas fa-hospital"></i> Санаторий</span>
<span class="gstd-hloc"><i class="fas fa-map-marker-alt"></i> {{ $gstdLoc }}</span>
<span class="gstd-hrating"><span class="gstd-hstars">★★★★★</span><span class="gstd-hrcnt">({{ $service->active_reviews_count ?? 0 }} отзывов)</span></span>
</div></div>
<div class="gstd-hactions"><button class="gstd-savebtn" onclick="addToWishlist({{ $service->id }}, this, 'service')"><i class="far fa-heart"></i> Сохранить</button></div>
</div>
<div class="gstd-gallery">
<div class="gstd-gmain">
<img src="{{ $gstdMainImg }}" alt="{{ $gstdTitle }}">
<button class="gstd-gmore"><i class="fas fa-images"></i> Все фото</button>
</div>
<div class="gstd-gside">
@foreach ($gstdMedia->skip(1)->take(3) as $img)<img src="{{ asset($img->file_path) }}" alt="">@endforeach
</div>
</div>
<div class="gstd-tabs-bar">
<button class="gstd-tab active" onclick="gstdTab('overview',this)">Обзор</button>
<button class="gstd-tab" onclick="gstdTab('treatment',this)">Лечение</button>
<button class="gstd-tab" onclick="gstdTab('rooms',this)">Номера и цены</button>
<button class="gstd-tab" onclick="gstdTab('included',this)">Включено</button>
<button class="gstd-tab" onclick="gstdTab('reviews',this)">Отзывы</button>
</div>
<div class="gstd-body">
<div class="gstd-main">
<div class="gstd-panel active" id="gstd-overview">
<div class="gstd-about-grid">
<div class="gstd-about-card"><div class="gstd-about-icon"><i class="fas fa-map-marker-alt"></i></div><div class="gstd-about-val">{{ $gstdLoc ?: '—' }}</div><div class="gstd-about-lbl">Расположение</div></div>
<div class="gstd-about-card"><div class="gstd-about-icon"><i class="fas fa-moon"></i></div><div class="gstd-about-val">7–14</div><div class="gstd-about-lbl">Дней лечения</div></div>
<div class="gstd-about-card"><div class="gstd-about-icon"><i class="fas fa-procedures"></i></div><div class="gstd-about-val">33–72</div><div class="gstd-about-lbl">Процедур в туре</div></div>
</div>
<h2 class="gstd-section-title">О санатории</h2>
<div class="gstd-desc-text">{!! $service?->translation?->description ?? $service->description ?? '' !!}</div>
<div class="gstd-highlights">
<div class="gstd-hl-item"><i class="fas fa-check-circle"></i><span class="gstd-hl-text">Без предоплаты — оплата при заезде</span></div>
<div class="gstd-hl-item"><i class="fas fa-check-circle"></i><span class="gstd-hl-text">Бесплатная отмена бронирования</span></div>
<div class="gstd-hl-item"><i class="fas fa-check-circle"></i><span class="gstd-hl-text">Трансфер из аэропорта</span></div>
<div class="gstd-hl-item"><i class="fas fa-check-circle"></i><span class="gstd-hl-text">Русскоязычный персонал</span></div>
<div class="gstd-hl-item"><i class="fas fa-check-circle"></i><span class="gstd-hl-text">Поддержка 24/7</span></div>
<div class="gstd-hl-item"><i class="fas fa-check-circle"></i><span class="gstd-hl-text">Медицинская консультация бесплатно</span></div>
</div>
</div>
<div class="gstd-panel" id="gstd-treatment">
<h2 class="gstd-section-title">Показания к лечению</h2>
<div class="gstd-diag-tags">
<span class="gstd-dtag">Заболевания суставов</span><span class="gstd-dtag">Позвоночник</span><span class="gstd-dtag">Кожные заболевания</span><span class="gstd-dtag">Нервная система</span><span class="gstd-dtag">Сердечно-сосудистые</span><span class="gstd-dtag">Органы дыхания</span><span class="gstd-dtag">ЖКТ</span><span class="gstd-dtag">Диабет</span><span class="gstd-dtag">Онкореабилитация</span>
</div>
<h2 class="gstd-section-title" style="margin-top:28px">Процедуры</h2>
<div class="gstd-proc-grid">
<div class="gstd-proc-item"><div class="gstd-proc-icon"><i class="fas fa-water"></i></div><div><div class="gstd-proc-name">Нафталановые ванны</div><div class="gstd-proc-desc">Уникальное нефтяное лечение суставов</div></div></div>
<div class="gstd-proc-item"><div class="gstd-proc-icon"><i class="fas fa-wind"></i></div><div><div class="gstd-proc-name">Дуздаг</div><div class="gstd-proc-desc">Соляные пещеры, лечение дыхания</div></div></div>
<div class="gstd-proc-item"><div class="gstd-proc-icon"><i class="fas fa-bolt"></i></div><div><div class="gstd-proc-name">Физиотерапия</div><div class="gstd-proc-desc">Магнит, лазер, ультразвук, электрофорез</div></div></div>
<div class="gstd-proc-item"><div class="gstd-proc-icon"><i class="fas fa-spa"></i></div><div><div class="gstd-proc-name">Массаж</div><div class="gstd-proc-desc">Лечебный и расслабляющий массаж</div></div></div>
<div class="gstd-proc-item"><div class="gstd-proc-icon"><i class="fas fa-stethoscope"></i></div><div><div class="gstd-proc-name">Диагностика</div><div class="gstd-proc-desc">ЭКГ, УЗИ, анализы крови</div></div></div>
<div class="gstd-proc-item"><div class="gstd-proc-icon"><i class="fas fa-dumbbell"></i></div><div><div class="gstd-proc-name">ЛФК</div><div class="gstd-proc-desc">Лечебная физическая культура</div></div></div>
</div>
<h2 class="gstd-section-title" style="margin-top:28px">Программы лечения</h2>
<table class="gstd-pkg-table">
<thead><tr><th>Программа</th><th>7 ночей</th><th>10 ночей</th><th>14 ночей</th></tr></thead>
<tbody>
<tr><td>Процедур включено</td><td>33</td><td>52</td><td>72</td></tr>
<tr><td>1 человек (AZN)</td><td>875</td><td>1 230</td><td>1 680</td></tr>
<tr><td>2 человека (AZN)</td><td>1 540</td><td>2 160</td><td>2 960</td></tr>
<tr><td>Скидка при 2 чел.</td><td>12%</td><td>12%</td><td>12%</td></tr>
<tr><td>Дети до 12 лет</td><td colspan="3" style="text-align:center">−50% от взрослого тарифа</td></tr>
</tbody>
</table>
<p style="font-size:12px;color:#9ca3af;margin-top:8px">* Без предоплаты. Оплата при заезде.</p>
</div>
</div>
</div>
</div></div>
<script>
function gstdTab(id, btn) {
    document.querySelectorAll('.gstd-panel').forEach(function (p) { p.classList.remove('active'); });
    document.querySelectorAll('.gstd-tab').forEach(function (t) { t.classList.remove('active'); });
    var panel = document.getElementById('gstd-' + id);
    if (panel) panel.classList.add('active');
    if (btn) btn.classList.add('active');
}
</script>
@endsection
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                // Initialize timepicker
                $(".timepicker").flatpickr({
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true
                });

                // Extract available dates from PHP data
                const availabilities = @json($service?->availabilities ?? []);
                const availableDates = availabilities.map(item => item.date);
                const availabilityMap = {};

                // Create a map of date -> availability details for quick lookup
                availabilities.forEach(item => {
                    availabilityMap[item.date] = {
                        spots: item.available_spots,
                        special_price: item.special_price,
                        notes: item.notes,
                        start_time: item.start_time,
                        end_time: item.end_time,
                        is_available: item.is_available
                    };
                });

                // Initialize date picker with available dates only
                const datePicker = flatpickr("input[name='check_in_date']", {
                    dateFormat: "Y-m-d",
                    disableMobile: "true",
                    minDate: "today",
                    enable: availableDates,
                    onChange: function(selectedDates, dateStr) {
                        updateAvailabilityInfo(dateStr);
                    }
                });

                // Function to update availability information when a date is selected
                function updateAvailabilityInfo(dateStr) {
                    const availInfo = $('#availability-info');
                    const bookBtn = $('button[type="submit"]');
                    const availabilityInput = $('#selected-availability-id');

                    if (dateStr && availabilityMap[dateStr]) {
                        const info = availabilityMap[dateStr];
                        const availId = availabilities.find(a => a.date === dateStr)?.id;

                        // Store the selected availability ID
                        availabilityInput.val(availId || '');

                        // Create information display
                        let html = '<div class="alert alert-info mt-2 mb-0">';

                        if (info.spots !== null) {
                            html += `<p class="mb-1"><strong>Available spots:</strong> ${info.spots}</p>`;

                            // Disable booking if no spots available
                            if (info.spots <= 0) {
                                html += '<p class="text-danger mb-0">No spots available for this date!</p>';
                                bookBtn.prop('disabled', true);
                            } else {
                                bookBtn.prop('disabled', false);
                            }
                        } else {
                            html += '<p class="mb-1">Spots available for booking</p>';
                            bookBtn.prop('disabled', false);
                        }

                        if (info.start_time && info.end_time) {
                            html +=
                                `<p class="mb-1"><strong>Time:</strong> ${info.start_time.substring(0,5)} - ${info.end_time.substring(0,5)}</p>`;
                        }

                        if (info.special_price) {
                            html +=
                                `<p class="mb-1"><strong>Special price:</strong> $${info.special_price}</p>`;
                        }

                        if (info.notes) {
                            html += `<p class="mb-0"><strong>Notes:</strong> ${info.notes}</p>`;
                        }

                        html += '</div>';
                        availInfo.html(html).show();
                    } else {
                        availInfo.hide().html('');
                        availabilityInput.val('');
                        bookBtn.prop('disabled', false);
                    }
                }

                // Initial call in case a date is pre-selected
                const initialDate = $('input[name="check_in_date"]').val();
                if (initialDate) {
                    updateAvailabilityInfo(initialDate);
                }

            });
        })(jQuery);
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function reviewForm() {
            return {
                categories: [{
                        name: 'Location',
                        rating: 0
                    },
                    {
                        name: 'Price',
                        rating: 0
                    },
                    {
                        name: 'Amenities',
                        rating: 0
                    },
                    {
                        name: 'Rooms',
                        rating: 0
                    },
                    {
                        name: 'Services',
                        rating: 0
                    },
                ],
                hoverRating: 0,
                hoverIndex: null,
                message: '',
                saveInfo: false,

                setRating(index, rating) {
                    this.categories[index].rating = rating;
                },

                submitForm() {
                    // Collect all form data
                    const data = {
                        service_id: `{{ $service->id }}`,
                        message: this.message,
                        ratings: this.categories.map(c => ({
                            category: c.name,
                            rating: c.rating
                        }))
                    };

                    if (!data.message.trim()) {
                        toastr.error('{{ __('Please write your review before submitting.') }}');
                        return;
                    }

                    if (data.ratings.some(c => c.rating === 0)) {
                        toastr.error('{{ __('Please select a rating before submitting.') }}');
                        return;
                    }

                    // Simulate form submission
                    this.ajaxSubmitForm(data);
                },

                resetForm() {
                    this.name = '';
                    this.email = '';
                    this.message = '';
                    this.saveInfo = false;
                    this.categories.forEach(c => c.rating = 0);
                },

                ajaxSubmitForm(data) {
                    fetch(`{{ route('front.tourbooking.reviews.store') }}`, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                toastr.success(data.message);
                                this.resetForm();
                            } else {
                                toastr.error(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            toastr.error('{{ __('An error occurred. Please try again later.') }}');
                        });
                }
            }
        }

        function bookingForm() {
            return {
                tickets: {
                    person: 1,
                    children: 0
                },
                pricePerPerson: {{ $service->price_per_person }},
                pricePerChild: {{ $service->child_price }},
                extras: {
                    @foreach ($service->extraCharges as $key => $extra)
                        charge_{{ $key }}: false,
                    @endforeach
                },
                extrasPrice: {
                    @foreach ($service->extraCharges as $key => $extra)
                        charge_{{ $key }}: {{ $extra->price }},
                    @endforeach
                },
                get totalCost() {
                    let total = 0;
                    total += this.tickets.person * this.pricePerPerson;
                    total += this.tickets.children * this.pricePerChild;
                    for (let key in this.extras) {
                        if (this.extras[key]) {
                            total += this.extrasPrice[key];
                        }
                    }
                    return total.toFixed(2);
                },
            }
        }
    </script>
@endpush

@push('style_section')
    <style>
        a.tg-listing-item-wishlist.active {
            color: var(--tg-theme-primary);
        }

        .tg-tour-about-cus-review-thumb img {
            height: 128px;
        }

        .tg-tour-details-video-ratings i {
            color: #a6a6a6;
        }

        .tg-tour-details-video-ratings i.active {
            color: var(--tg-common-yellow);
        }

        .tg-tour-details-gallery-thumb-active .tg-tour-details-gallery-thumb img {
            height: 120px;
        }

        .tg-tour-details-gallery-thumb img {
            height: 430px;
        }

        .custom-select {
            min-width: 60px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid #d6d6d6;
            border-radius: 24px;
            padding: 1px 14px;
            font-weight: 400;
            font-size: 16px;
            color: var(--tg-grey-1);
        }

        .custom-select:focus {
            outline: none;
            border-color: #560CE3;
        }

        .calender-active.open .flatpickr-innerContainer .flatpickr-days .flatpickr-day.today,
        .flatpickr-calendar.open .flatpickr-innerContainer .flatpickr-days .flatpickr-day.selected {
            color: var(--tg-common-white) !important;
            background-color: var(--tg-theme-primary) !important;
        }
    </style>
@endpush
