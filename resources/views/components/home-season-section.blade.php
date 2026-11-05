@php
    $gsSeasons = [
        ['months' => 'Январь–Февраль',   'season' => 'Низкий сезон',  'price' => 'От $80/ночь',  'load' => 'Свободно',     'level' => 1],
        ['months' => 'Март–Апрель',      'season' => 'Средний сезон', 'price' => 'От $95/ночь',  'load' => 'Умеренно',     'level' => 2],
        ['months' => 'Май–Июнь',         'season' => 'Высокий сезон', 'price' => 'От $110/ночь', 'load' => 'Занято',       'level' => 3],
        ['months' => 'Июль–Август',      'season' => 'Пик сезона',    'price' => 'От $130/ночь', 'load' => 'Очень занято', 'level' => 4],
        ['months' => 'Сентябрь–Октябрь', 'season' => 'Высокий сезон', 'price' => 'От $110/ночь', 'load' => 'Занято',       'level' => 3],
        ['months' => 'Ноябрь–Декабрь',   'season' => 'Средний сезон', 'price' => 'От $95/ночь',  'load' => 'Умеренно',     'level' => 2],
    ];
@endphp
<section class="gsseason-section" id="section-season">
<style>
.gsseason-section{background:#f1f8f6;padding:80px 0;font-family:'Inter',system-ui,sans-serif}
.gsseason-container{max-width:1100px;margin:0 auto;padding:0 20px}
.gsseason-label{font-size:13px;text-transform:uppercase;letter-spacing:3px;color:#1a9e87;font-weight:700;margin:0 0 12px}
.gsseason-title{font-family:'Manrope',system-ui,sans-serif;font-size:36px;font-weight:800;color:#1a2540;margin:0;line-height:1.2}
.gsseason-title:after{content:'';display:block;width:60px;height:4px;background:linear-gradient(135deg,#1a9e87,#2ec4a9);border-radius:2px;margin:16px 0 20px}
.gsseason-sub{font-size:16px;line-height:1.6;color:#64748b;margin:0 0 36px;max-width:720px}
.gsseason-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
.gsseason-card{background:#fff;border-radius:12px;border-top:4px solid #1a9e87;box-shadow:0 4px 20px rgba(0,0,0,.08);padding:24px}
.gsseason-card.lvl2{border-top-color:#2ec4a9}
.gsseason-card.lvl3{border-top-color:#e0a21a}
.gsseason-card.lvl4{border-top-color:#d9534f}
.gsseason-months{font-size:20px;font-weight:800;color:#1a2540;margin:0 0 4px}
.gsseason-name{font-size:14px;font-weight:600;color:#1a9e87;margin:0 0 16px}
.gsseason-price{font-size:24px;font-weight:800;color:#1a2540;margin:0 0 14px}
.gsseason-load{display:flex;align-items:center;gap:10px;font-size:14px;color:#64748b}
.gsseason-load span.t{font-weight:600;color:#1a2540}
.gsseason-bars{display:inline-flex;gap:3px}
.gsseason-bars i{width:14px;height:8px;border-radius:3px;background:#dde8e5;display:block}
.gsseason-bars i.on{background:linear-gradient(135deg,#1a9e87,#2ec4a9)}
.lvl3 .gsseason-bars i.on{background:#e0a21a}
.lvl4 .gsseason-bars i.on{background:#d9534f}
@media(max-width:900px){.gsseason-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:560px){.gsseason-grid{grid-template-columns:1fr}.gsseason-title{font-size:28px}.gsseason-section{padding:56px 0}}
</style>
  <div class="gsseason-container">
    <div class="gsseason-label">GOSANATORIUM</div>
    <h2 class="gsseason-title">Когда лучше ехать в Нафталан?</h2>
    <p class="gsseason-sub">Нафталан принимает гостей круглый год. Выбирайте месяц для лечения и отдыха с учётом цен и загруженности санаториев.</p>
    <div class="gsseason-grid">
      @foreach($gsSeasons as $s)
      <div class="gsseason-card lvl{{ $s['level'] }}">
        <h3 class="gsseason-months">{{ $s['months'] }}</h3>
        <div class="gsseason-name">{{ $s['season'] }}</div>
        <div class="gsseason-price">{{ $s['price'] }}</div>
        <div class="gsseason-load">
          <span class="gsseason-bars">@for($i=1;$i<=4;$i++)<i class="{{ $i <= $s['level'] ? 'on' : '' }}"></i>@endfor</span>
          <span class="t">{{ $s['load'] }}</span>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
