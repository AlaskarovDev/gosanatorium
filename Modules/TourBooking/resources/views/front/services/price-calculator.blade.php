@php
    $_calcSlug = $service->slug ?? '';
    $_calcRu = app()->getLocale() !== 'en';
    $_calcL = $_calcRu ? [
        'nights' => 'Количество ночей', 'room' => 'Номер', 'pack' => 'Пакет',
        'Standard' => 'Стандарт', 'Superior' => 'Супериор', 'Suite' => 'Люкс',
        'Basic' => 'Базовый', 'Premium' => 'Премиум', 'Therapeutic' => 'Лечебный',
        'title' => 'Рассчитайте стоимость', 'total' => 'Итого', 'for' => 'за', 'nl' => 'ночей', 'ppl' => '(2 человека)',
        'ins' => '+ страховка: 2 AZN × 2 чел ×', 'insn' => 'ночей', 'note' => '+ 2 AZN страховка в сутки на человека',
        'book' => 'Забронировать', 'wa' => 'Здравствуйте! Хочу забронировать', 'season_l' => 'Низкий сезон (ноябрь–апрель)', 'season_h' => 'Высокий сезон (май–октябрь)',
    ] : [
        'nights' => 'Nights', 'room' => 'Room', 'pack' => 'Package',
        'Standard' => 'Standard', 'Superior' => 'Superior', 'Suite' => 'Suite',
        'Basic' => 'Basic', 'Premium' => 'Premium', 'Therapeutic' => 'Therapeutic',
        'title' => 'Calculate your stay', 'total' => 'Total', 'for' => 'for', 'nl' => 'nights', 'ppl' => '(2 people)',
        'ins' => '+ insurance: 2 AZN × 2 people ×', 'insn' => 'nights', 'note' => '+ 2 AZN insurance per day per person',
        'book' => 'Book now', 'wa' => 'Hello! I would like to book', 'season_l' => 'Low season (Nov–Apr)', 'season_h' => 'High season (May–Oct)',
    ];
    $_calcPrices = [
        'chinar-hotel-spa-naftalan-2026' => ['Standard' => [182, 220], 'Superior' => [210, 260], 'Suite' => [280, 340]],
        'garabagh-resort-spa-naftalan-2026' => ['Standard' => [195, 235], 'Superior' => [225, 275], 'Suite' => [295, 355]],
        'gashalti-health-hotel-naftalan-2026' => ['Standard' => [170, 205], 'Superior' => [195, 240], 'Suite' => [260, 315]],
        'sun-city-hotel-spa-naftalan-lechenie-2026' => ['Standard' => [160, 195], 'Superior' => [185, 225], 'Suite' => [245, 295]],
        'naftalan-central-wellness-sanatorii' => ['Standard' => [150, 185], 'Superior' => [175, 215], 'Suite' => [235, 280]],
        'naftalan-central-wellness-resort-2026' => ['Standard' => [150, 185], 'Superior' => [175, 215], 'Suite' => [235, 280]],
        'borjomi-palace-sanatoriy-2026' => ['Standard' => [165, 200], 'Superior' => [190, 235], 'Suite' => [255, 305]],
        'tskaltubo-spa-resort' => ['Standard' => [140, 170], 'Superior' => [165, 200], 'Suite' => [220, 265]],
    ];
    $_calcDefault = $_calcPrices['chinar-hotel-spa-naftalan-2026'];
    $_calcTitle = $service?->translation?->title ?? '';
@endphp
<style>
    #gs-calc{background:#fff;border:1px solid #e3e8e5;border-radius:14px;padding:16px 18px;margin:14px 0 6px;max-width:640px;color:#222;box-shadow:0 2px 10px rgba(0,0,0,.06);text-align:left;position:relative;z-index:5}
    #gs-calc .gc-title{font-weight:700;font-size:16px;margin-bottom:8px;color:#222}
    #gs-calc .gc-label{font-size:12px;color:#666;margin:8px 0 4px;text-transform:uppercase;letter-spacing:.3px}
    #gs-calc .gc-row{display:flex;flex-wrap:wrap;gap:6px}
    #gs-calc .gc-btn{flex:1 1 0;min-width:64px;min-height:44px;padding:8px 10px;border:1px solid #d5dbd8;background:#fff;color:#222;border-radius:10px;font-size:15px;cursor:pointer;transition:all .15s}
    #gs-calc .gc-btn.active{background:#4CAF50;border-color:#4CAF50;color:#fff;font-weight:600}
    #gs-calc .gc-result{margin-top:12px;padding:10px 12px;background:#f1f9f1;border-radius:10px;border:1px solid #cfe8d0}
    #gs-calc .gc-total{font-size:20px;font-weight:700;color:#2e7d32}
    #gs-calc .gc-sub{font-size:13px;color:#555;margin-top:2px}
    #gs-calc .gc-book{display:inline-block;margin-top:8px;min-height:44px;line-height:44px;padding:0 22px;background:#4CAF50;color:#fff!important;border-radius:10px;font-weight:600;text-decoration:none}
    @media (max-width:480px){#gs-calc .gc-btn{font-size:14px;padding:8px 4px}#gs-calc .gc-total{font-size:18px}}
</style>
<div id="gs-calc">
    <div class="gc-title">{{ $_calcL['title'] }}</div>
    <div class="gc-label">1. {{ $_calcL['nights'] }}</div>
    <div class="gc-row" data-group="nights">
        @foreach ([7, 10, 14, 21] as $n)
            <button type="button" class="gc-btn{{ $n === 7 ? ' active' : '' }}" data-value="{{ $n }}">{{ $n }}</button>
        @endforeach
    </div>
    <div class="gc-label">2. {{ $_calcL['room'] }}</div>
    <div class="gc-row" data-group="room">
        @foreach (['Standard', 'Superior', 'Suite'] as $r)
            <button type="button" class="gc-btn{{ $r === 'Standard' ? ' active' : '' }}" data-value="{{ $r }}">{{ $_calcL[$r] }}</button>
        @endforeach
    </div>
    <div class="gc-label">3. {{ $_calcL['pack'] }}</div>
    <div class="gc-row" data-group="pack">
        @foreach (['Basic', 'Standard', 'Premium', 'Therapeutic'] as $p)
            <button type="button" class="gc-btn{{ $p === 'Basic' ? ' active' : '' }}" data-value="{{ $p }}">{{ $_calcL[$p] }}</button>
        @endforeach
    </div>
    <div class="gc-result">
        <div class="gc-total" id="gc-total"></div>
        <div class="gc-sub" id="gc-ins"></div>
        <div class="gc-sub" id="gc-season"></div>
        <a class="gc-book" id="gc-book" href="#" target="_blank" rel="noopener">{{ $_calcL['book'] }}</a>
    </div>
    <div class="gc-sub" style="margin-top:6px">{{ $_calcL['note'] }}</div>
</div>
<script>
(function () {
    var slug = @json($_calcSlug);
    var title = @json($_calcTitle);
    var L = @json($_calcL);
    var matrix = @json($_calcPrices);
    var def = @json($_calcDefault);
    var prices = matrix[slug] || def;
    var mult = {Basic: 1.0, Standard: 1.15, Premium: 1.30, Therapeutic: 1.45};
    var month = new Date().getMonth() + 1; // 5..10 = high season
    var high = month >= 5 && month <= 10;
    var state = {nights: 7, room: 'Standard', pack: 'Basic'};
    var root = document.getElementById('gs-calc');
    function fmt(n) { return '$' + Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','); }
    function render() {
        var base = prices[state.room][high ? 1 : 0];
        var total = base * state.nights * mult[state.pack];
        document.getElementById('gc-total').textContent = L.total + ': ' + fmt(total) + ' ' + L['for'] + ' ' + state.nights + ' ' + L.nl + ' ' + L.ppl;
        document.getElementById('gc-ins').textContent = L.ins + ' ' + state.nights + ' ' + L.insn + ' = ' + (2 * 2 * state.nights) + ' AZN';
        document.getElementById('gc-season').textContent = high ? L.season_h : L.season_l;
        var msg = L.wa + ' ' + title + ': ' + state.nights + ' ' + L.nl + ', ' + L[state.room] + ', ' + L[state.pack] + ' — ' + fmt(total);
        document.getElementById('gc-book').href = 'https://wa.me/994519158894?text=' + encodeURIComponent(msg);
    }
    root.addEventListener('click', function (e) {
        var b = e.target.closest('.gc-btn');
        if (!b) return;
        var g = b.parentNode.getAttribute('data-group');
        state[g] = g === 'nights' ? parseInt(b.getAttribute('data-value'), 10) : b.getAttribute('data-value');
        Array.prototype.forEach.call(b.parentNode.children, function (x) { x.classList.toggle('active', x === b); });
        render();
    });
    render();
})();
</script>
