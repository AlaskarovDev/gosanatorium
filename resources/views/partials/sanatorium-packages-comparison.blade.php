@php
    $cmpSlug = (string) ($service->slug ?? '');
    // Per-sanatorium package data. 'vip' => null means only one package type (single column).
    $cmpSets = [
        'park-naftalan' => [
            'standard' => 'Стандарт',
            'vip' => 'VIP',
            'rows' => [
                ['Нафталановые ванны', true, true],
                ['Физиотерапия', 'до 48 сеансов', 'до 90 сеансов'],
                ['Массаж', '10 мин', '30 мин'],
                ['Озонотерапия', false, true],
                ['Лимфодренаж', false, true],
                ['Иглотерапия', false, true],
                ['Гидроколонотерапия', false, true],
                ['Ударно-волновая терапия', false, true],
                ['Анализов', '11', '25'],
                ['Визиты к терапевту', '1', '2'],
            ],
        ],
    ];
    $cmp = null;
    foreach ($cmpSets as $k => $set) {
        if (str_contains($cmpSlug, $k)) { $cmp = $set; break; }
    }
    $cmpVal = fn ($v) => $v === true ? '<span class="pkgc-yes">✓</span>' : ($v === false ? '<span class="pkgc-no">✗</span>' : e($v));
@endphp
@if($cmp)
<style>
.pkgc{margin:0 0 28px}
.pkgc table{width:100%;border-collapse:separate;border-spacing:0;border-radius:16px;overflow:hidden;border:1.5px solid #e5e7eb;font-size:14px}
.pkgc th,.pkgc td{padding:12px 14px;text-align:center;border-bottom:1px solid #eef0f2}
.pkgc th:first-child,.pkgc td:first-child{text-align:left;font-weight:600;color:#374151}
.pkgc thead th{font-size:16px;font-weight:800;border-bottom:none}
.pkgc .pkgc-std{background:#f3f4f6;color:#4b5563}
.pkgc .pkgc-vip{background:#fff8dc;color:#7a5b00;box-shadow:inset 3px 0 0 #f5b800,inset -3px 0 0 #f5b800}
.pkgc thead .pkgc-vip{background:linear-gradient(135deg,#f9d44a,#f5b800);color:#3d2d00}
.pkgc tbody tr:last-child td{border-bottom:none}
.pkgc tbody tr:last-child .pkgc-vip{box-shadow:inset 3px 0 0 #f5b800,inset -3px 0 0 #f5b800,inset 0 -3px 0 #f5b800}
.pkgc-yes{color:#16a34a;font-weight:800;font-size:18px}
.pkgc-no{color:#d1d5db;font-weight:800;font-size:18px}
.pkgc-btn{display:inline-block;background:#f5b800;color:#3d2d00;font-weight:800;padding:10px 22px;border-radius:10px;text-decoration:none}
.pkgc-btn:hover{background:#e0a800;color:#3d2d00}
@media(max-width:576px){.pkgc th,.pkgc td{padding:10px 6px;font-size:13px}}
</style>
<div class="pkgc">
<table>
<thead><tr><th></th><th class="pkgc-std">{{ $cmp['standard'] }}</th>@if($cmp['vip'])<th class="pkgc-vip">⭐ {{ $cmp['vip'] }}</th>@endif</tr></thead>
<tbody>
@foreach($cmp['rows'] as $row)
<tr><td>{{ $row[0] }}</td><td class="pkgc-std">{!! $cmpVal($row[1]) !!}</td>@if($cmp['vip'])<td class="pkgc-vip">{!! $cmpVal($row[2]) !!}</td>@endif</tr>
@endforeach
<tr><td></td><td class="pkgc-std"></td>@if($cmp['vip'])<td class="pkgc-vip"><a class="pkgc-btn" href="https://wa.me/994519158894?text={{ rawurlencode('Здравствуйте! Хочу выбрать VIP-пакет: '.($service->translation?->title ?? $service->title ?? '')) }}" target="_blank" rel="noopener">Выбрать VIP</a></td>@endif</tr>
</tbody>
</table>
</div>
@endif
