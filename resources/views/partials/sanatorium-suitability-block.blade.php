@php
    $suitableFor = array_values(array_filter((array) ($service->suitable_for ?? []), fn ($r) => trim((string) $r) !== ''));
    $notSuitableFor = array_values(array_filter((array) ($service->not_suitable_for ?? []), fn ($r) => trim((string) $r) !== ''));
@endphp
@if(count($suitableFor) || count($notSuitableFor))
<style>
.suit-block{display:grid;grid-template-columns:1fr 1fr;gap:18px;margin:0 0 28px}
.suit-col{border-radius:16px;padding:20px 22px}
.suit-col h3{font-size:18px;font-weight:800;margin:0 0 14px;text-transform:none}
.suit-col ul{list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:10px}
.suit-col li{display:flex;align-items:flex-start;gap:10px;font-size:14px;line-height:1.5;text-transform:none}
.suit-yes{background:#f0fdf4;border:1.5px solid #86efac}
.suit-yes h3{color:#14532d}
.suit-yes li{color:#166534;font-weight:600}
.suit-yes .suit-ico{background:#16a34a;color:#fff}
.suit-no{background:#f3f4f6;border:1.5px solid #e5e7eb}
.suit-no h3{color:#4b5563}
.suit-no li{color:#4b5563}
.suit-no .suit-ico{background:#9ca3af;color:#fff;font-style:italic;font-family:Georgia,serif}
.suit-ico{flex:0 0 22px;width:22px;height:22px;border-radius:50%;font-size:13px;display:flex;align-items:center;justify-content:center}
@media(max-width:768px){.suit-block{grid-template-columns:1fr}}
</style>
<div class="suit-block">
  @if(count($suitableFor))
  <div class="suit-col suit-yes">
    <h3>Подходит вам если...</h3>
    <ul>@foreach($suitableFor as $item)<li><span class="suit-ico">✓</span><span>{{ $item }}</span></li>@endforeach</ul>
  </div>
  @endif
  @if(count($notSuitableFor))
  <div class="suit-col suit-no">
    <h3>Возможно, не подходит если...</h3>
    <ul>@foreach($notSuitableFor as $item)<li><span class="suit-ico">i</span><span>{{ $item }}</span></li>@endforeach</ul>
  </div>
  @endif
</div>
@endif
