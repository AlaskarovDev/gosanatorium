@extends('layout_inner_page')

@section('title')
    <title>Карта санаториев — Азербайджан и Грузия | GoSanatorium</title>
    <meta name="description" content="Интерактивная карта санаториев Азербайджана и Грузии на GoSanatorium.">
@endsection

@push('style_section')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
<style>
:root{--gsmap-bg:#f7f9fb;--gsmap-surface:#ffffff;--gsmap-text:#1a2a2a;--gsmap-muted:#64748b;--gsmap-border:#e2e8f0;--gsmap-az:#1a9e87;--gsmap-az-soft:rgba(26,158,135,.12);--gsmap-ge:#d4580a;--gsmap-ge-soft:rgba(212,88,10,.12);--gsmap-shadow:0 4px 14px rgba(0,0,0,.08);--gsmap-badge-bg:#eef2f6;--gsmap-tip-bg:#1a2a2a;--gsmap-tip-text:#ffffff}
@media (prefers-color-scheme: dark){:root:not([data-theme="light"]){--gsmap-bg:#111a30;--gsmap-surface:#1a2340;--gsmap-text:#e8edf5;--gsmap-muted:#9aa8c0;--gsmap-border:#2c3a5e;--gsmap-az-soft:rgba(46,196,169,.18);--gsmap-ge-soft:rgba(240,130,60,.18);--gsmap-shadow:0 4px 14px rgba(0,0,0,.4);--gsmap-badge-bg:#26325a;--gsmap-tip-bg:#e8edf5;--gsmap-tip-text:#1a2340}}
:root[data-theme="dark"]{--gsmap-bg:#111a30;--gsmap-surface:#1a2340;--gsmap-text:#e8edf5;--gsmap-muted:#9aa8c0;--gsmap-border:#2c3a5e;--gsmap-az-soft:rgba(46,196,169,.18);--gsmap-ge-soft:rgba(240,130,60,.18);--gsmap-shadow:0 4px 14px rgba(0,0,0,.4);--gsmap-badge-bg:#26325a;--gsmap-tip-bg:#e8edf5;--gsmap-tip-text:#1a2340}
.gsmap-page{background:var(--gsmap-bg)!important;color:var(--gsmap-text)!important;font-family:'Inter',system-ui,sans-serif!important;font-size:14px!important;min-height:60vh!important}
.gsmap-header{background:linear-gradient(135deg,#1a9e87 0%,#2ec4a9 50%,#17b09a 100%)!important;padding:56px 20px 44px!important;text-align:center!important}
.gsmap-label{display:block!important;font-family:'Manrope',system-ui,sans-serif!important;font-weight:700!important;font-size:12px!important;letter-spacing:.14em!important;text-transform:uppercase!important;color:rgba(255,255,255,.7)!important;margin:0 0 10px!important}
.gsmap-title{font-family:'Manrope',system-ui,sans-serif!important;font-weight:800!important;font-size:36px!important;color:#fff!important;margin:0 0 10px!important;line-height:1.2!important}
.gsmap-subtitle{font-size:16px!important;color:rgba(255,255,255,.92)!important;margin:0!important}
.gsmap-tabs{position:sticky!important;top:0!important;z-index:50!important;display:flex!important;justify-content:center!important;gap:8px!important;background:var(--gsmap-surface)!important;border-bottom:1px solid var(--gsmap-border)!important;padding:0 12px!important}
.gsmap-tab{display:flex!important;align-items:center!important;gap:8px!important;background:transparent!important;border:0!important;border-bottom:3px solid transparent!important;padding:14px 20px!important;cursor:pointer!important;font-family:'Manrope',system-ui,sans-serif!important;font-weight:700!important;font-size:15px!important;color:var(--gsmap-muted)!important;margin:0 0 -1px!important}
.gsmap-tab-dot{display:inline-block!important;width:10px!important;height:10px!important;border-radius:50%!important}
.gsmap-tab-az .gsmap-tab-dot{background:var(--gsmap-az)!important}
.gsmap-tab-ge .gsmap-tab-dot{background:var(--gsmap-ge)!important}
.gsmap-tab-pill{display:inline-block!important;font-size:11px!important;font-weight:800!important;padding:2px 9px!important;border-radius:999px!important;background:var(--gsmap-badge-bg)!important;color:var(--gsmap-muted)!important}
.gsmap-tab-az.gsmap-active{color:var(--gsmap-az)!important;border-bottom-color:var(--gsmap-az)!important}
.gsmap-tab-az.gsmap-active .gsmap-tab-pill{background:var(--gsmap-az)!important;color:#fff!important}
.gsmap-tab-ge.gsmap-active{color:var(--gsmap-ge)!important;border-bottom-color:var(--gsmap-ge)!important}
.gsmap-tab-ge.gsmap-active .gsmap-tab-pill{background:var(--gsmap-ge)!important;color:#fff!important}
.gsmap-panel{display:none!important;max-width:1320px!important;margin:0 auto!important;padding:24px 16px 40px!important}
.gsmap-panel.gsmap-active{display:grid!important;grid-template-columns:340px 1fr!important;gap:20px!important;align-items:start!important}
.gsmap-list{max-height:560px!important;overflow-y:auto!important;display:flex!important;flex-direction:column!important;gap:10px!important;padding:2px 6px 2px 2px!important}
.gsmap-card{display:flex!important;gap:12px!important;align-items:flex-start!important;background:var(--gsmap-surface)!important;border:1.5px solid var(--gsmap-border)!important;border-radius:14px!important;padding:14px 16px!important;cursor:pointer!important;transition:border-color .15s,box-shadow .15s,transform .15s!important}
.gsmap-card-az:hover{border-color:var(--gsmap-az)!important;box-shadow:var(--gsmap-shadow)!important;transform:translateY(-1px)!important}
.gsmap-card-ge:hover{border-color:var(--gsmap-ge)!important;box-shadow:var(--gsmap-shadow)!important;transform:translateY(-1px)!important}
.gsmap-card-az.gsmap-selected{border-color:var(--gsmap-az)!important;box-shadow:0 0 0 3px var(--gsmap-az-soft)!important}
.gsmap-card-ge.gsmap-selected{border-color:var(--gsmap-ge)!important;box-shadow:0 0 0 3px var(--gsmap-ge-soft)!important}
.gsmap-num{flex:0 0 28px!important;width:28px!important;height:28px!important;border-radius:8px!important;display:flex!important;align-items:center!important;justify-content:center!important;font-family:'Manrope',system-ui,sans-serif!important;font-weight:800!important;font-size:12px!important}
.gsmap-card-az .gsmap-num{background:var(--gsmap-az-soft)!important;color:var(--gsmap-az)!important}
.gsmap-card-ge .gsmap-num{background:var(--gsmap-ge-soft)!important;color:var(--gsmap-ge)!important}
.gsmap-info{min-width:0!important}
.gsmap-name{font-family:'Manrope',system-ui,sans-serif!important;font-weight:700!important;font-size:13.5px!important;color:var(--gsmap-text)!important;margin:0 0 4px!important;line-height:1.3!important}
.gsmap-addr{display:flex!important;align-items:flex-start!important;gap:5px!important;font-size:12px!important;font-weight:400!important;color:var(--gsmap-muted)!important;margin:0 0 7px!important;line-height:1.35!important}
.gsmap-addr svg{flex:0 0 12px!important;width:12px!important;height:12px!important;margin-top:1px!important}
.gsmap-type{display:inline-block!important;font-family:'Manrope',system-ui,sans-serif!important;font-weight:700!important;font-size:10.5px!important;padding:2px 9px!important;border-radius:999px!important}
.gsmap-card-az .gsmap-type{background:var(--gsmap-az-soft)!important;color:var(--gsmap-az)!important}
.gsmap-card-ge .gsmap-type{background:var(--gsmap-ge-soft)!important;color:var(--gsmap-ge)!important}
.gsmap-mapcol{position:sticky!important;top:70px!important}
.gsmap-canvaswrap{width:100%!important;border-radius:14px!important;overflow:hidden!important;border:1.5px solid var(--gsmap-border)!important;background:var(--gsmap-surface)!important}
.gsmap-canvas{display:block!important;width:100%!important}
.gsmap-tooltip{position:fixed!important;z-index:99999!important;pointer-events:none!important;display:none!important;max-width:260px!important;background:var(--gsmap-tip-bg)!important;color:var(--gsmap-tip-text)!important;border-radius:10px!important;padding:8px 12px!important;font-family:'Inter',system-ui,sans-serif!important;font-size:12px!important;line-height:1.4!important;box-shadow:0 6px 20px rgba(0,0,0,.25)!important}
.gsmap-tooltip b{display:block!important;font-family:'Manrope',system-ui,sans-serif!important;font-weight:700!important;font-size:13px!important}
@media (max-width:768px){
.gsmap-title{font-size:26px!important}
.gsmap-header{padding:40px 16px 32px!important}
.gsmap-tab{padding:12px 12px!important;font-size:14px!important}
.gsmap-panel.gsmap-active{grid-template-columns:1fr!important}
.gsmap-list{max-height:none!important;overflow-y:visible!important}
.gsmap-mapcol{position:static!important}
}
</style>
@endpush

@section('front-content')
<div class="gsmap-page">
    <div class="gsmap-header">
        <span class="gsmap-label">GoSanatorium.com</span>
        <h1 class="gsmap-title">Карта санаториев</h1>
        <p class="gsmap-subtitle">Азербайджан и Грузия — выберите страну для просмотра</p>
    </div>

    <div class="gsmap-tabs" role="tablist">
        <button type="button" class="gsmap-tab gsmap-tab-az gsmap-active" data-country="az" role="tab"><span class="gsmap-tab-dot"></span><span>Азербайджан</span><span class="gsmap-tab-pill">17</span></button>
        <button type="button" class="gsmap-tab gsmap-tab-ge" data-country="ge" role="tab"><span class="gsmap-tab-dot"></span><span>Грузия</span><span class="gsmap-tab-pill">5</span></button>
    </div>

    <div class="gsmap-panel gsmap-active" id="gsmap-panel-az">
        <div class="gsmap-list" id="gsmap-list-az"></div>
        <div class="gsmap-mapcol"><div class="gsmap-canvaswrap" id="gsmap-wrap-az"><canvas class="gsmap-canvas" id="gsmap-canvas-az"></canvas></div></div>
    </div>
    <div class="gsmap-panel" id="gsmap-panel-ge">
        <div class="gsmap-list" id="gsmap-list-ge"></div>
        <div class="gsmap-mapcol"><div class="gsmap-canvaswrap" id="gsmap-wrap-ge"><canvas class="gsmap-canvas" id="gsmap-canvas-ge"></canvas></div></div>
    </div>
</div>
@endsection

@push('js_section')
<script>
(function () {
const AZ_SANATORIUMS = [
  { id:1,  name:"Санаторий Нур Нафталан",           city:"Нафталан",  addr:"ул. Нефтчилар 1, Нафталан",         type:"Нафталан", lat:40.5089, lng:46.8215 },
  { id:2,  name:"Санаторий Голд Нафталан",           city:"Нафталан",  addr:"Квартал 3, Нафталан",               type:"Нафталан", lat:40.5110, lng:46.8240 },
  { id:3,  name:"Санаторий Сан Сити Нафталан",       city:"Нафталан",  addr:"ул. Гасанбекова, Нафталан",         type:"Нафталан", lat:40.5075, lng:46.8195 },
  { id:4,  name:"Гарабаг Резорт & СПА",              city:"Нафталан",  addr:"ул. Гейдара Алиева, Нафталан",      type:"Нафталан", lat:40.5098, lng:46.8230 },
  { id:5,  name:"Санаторий Чинар",                   city:"Нафталан",  addr:"ул. Нефтяников, Нафталан",          type:"Нафталан", lat:40.5060, lng:46.8175 },
  { id:6,  name:"Карван Отель Нафталан",             city:"Нафталан",  addr:"Центральная площадь, Нафталан",     type:"Нафталан", lat:40.5120, lng:46.8255 },
  { id:7,  name:"Санаторий Парк Нафталан",           city:"Нафталан",  addr:"Парковый проспект, Нафталан",       type:"Нафталан", lat:40.5050, lng:46.8160 },
  { id:8,  name:"Санаторий Гашалты",                 city:"Нафталан",  addr:"пос. Гашалты, Нафталан р-н",       type:"Нафталан", lat:40.5140, lng:46.8280 },
  { id:9,  name:"Санаторий Нафта",                   city:"Нафталан",  addr:"ул. Низами, Нафталан",              type:"Нафталан", lat:40.5035, lng:46.8140 },
  { id:10, name:"Санаторий Нафталан Централь",       city:"Нафталан",  addr:"Центр, Нафталан",                  type:"Нафталан", lat:40.5130, lng:46.8265 },
  { id:11, name:"Мариcель Нафталан Центер Баку",     city:"Баку",      addr:"пр. Гейдара Алиева 108, Баку",     type:"Баку",     lat:40.4093, lng:49.8671 },
  { id:12, name:"Санаторий Абшерон",                 city:"Баку",      addr:"Абшеронский п-ов, Баку",           type:"Баку",     lat:40.4520, lng:50.0200 },
  { id:13, name:"Санаторий Гюнешли",                 city:"Баку",      addr:"пос. Гюнешли, Баку",               type:"Баку",     lat:40.4320, lng:49.9810 },
  { id:14, name:"Санаторий Мирас",                   city:"Баку",      addr:"пос. Бузовна, Абшерон",            type:"Баку",     lat:40.5340, lng:50.1120 },
  { id:15, name:"Санаторий Бильгя",                  city:"Баку",      addr:"пос. Бильгях, Абшерон",            type:"Баку",     lat:40.5680, lng:50.0250 },
  { id:16, name:"Санаторий Лянкяран Спрингс",        city:"Лянкяран",  addr:"ул. Нариманова, Лянкяран",         type:"Лянкяран", lat:38.7520, lng:48.8490 },
  { id:17, name:"Санаторий Шихово",                  city:"Баку",      addr:"пос. Шихово (Хазар), Баку",        type:"Баку",     lat:40.3410, lng:49.7850 },
];
const GE_SANATORIUMS = [
  { id:1, name:"Легендс Цхалтубо СПА Резорт",  city:"Цхалтубо", addr:"ул. Руставели 2, Цхалтубо",    type:"Цхалтубо", lat:42.3290, lng:42.6010 },
  { id:2, name:"Санаторий Прометей",            city:"Цхалтубо", addr:"Курортный проспект, Цхалтубо",  type:"Цхалтубо", lat:42.3270, lng:42.5990 },
  { id:3, name:"Боржоми Ликани Хелс & СПА",    city:"Боржоми",  addr:"Ликани, Боржоми р-н",           type:"Боржоми",  lat:41.8540, lng:43.3890 },
  { id:4, name:"Санаторий Боржоми Палас",       city:"Боржоми",  addr:"ул. Руставели 1, Боржоми",      type:"Боржоми",  lat:41.8390, lng:43.4020 },
  { id:5, name:"Sairme Hotels & Resorts",       city:"Саирме",   addr:"Саирме, Багдатский р-н",        type:"Саирме",   lat:41.9820, lng:43.0450 },
];

const COUNTRIES = {
  az: { data: AZ_SANATORIUMS, color: '#1a9e87' },
  ge: { data: GE_SANATORIUMS, color: '#d4580a' }
};
const state = { az: { selected: null, hover: null }, ge: { selected: null, hover: null } };

function esc(s) {
  return String(s).replace(/[&<>"']/g, function (c) {
    return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
  });
}
function isDark() {
  var t = document.documentElement.getAttribute('data-theme');
  if (t === 'dark') return true;
  if (t === 'light') return false;
  return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
}

var tip = document.createElement('div');
tip.className = 'gsmap-tooltip';
document.body.appendChild(tip);

function buildList(country) {
  var c = COUNTRIES[country];
  var el = document.getElementById('gsmap-list-' + country);
  el.innerHTML = c.data.map(function (s) {
    return '<div class="gsmap-card gsmap-card-' + country + '" data-id="' + s.id + '" id="gsmap-card-' + country + '-' + s.id + '">' +
      '<div class="gsmap-num">' + s.id + '</div><div class="gsmap-info">' +
      '<div class="gsmap-name">' + esc(s.name) + '</div>' +
      '<div class="gsmap-addr"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg><span>' + esc(s.addr) + '</span></div>' +
      '<span class="gsmap-type">' + esc(s.type) + '</span></div></div>';
  }).join('');
  el.addEventListener('click', function (e) {
    var card = e.target.closest('.gsmap-card');
    if (card) selectCard(country, parseInt(card.getAttribute('data-id'), 10));
  });
}

function drawMap(canvasId, data, country) {
  var canvas = document.getElementById(canvasId);
  var wrap = canvas.parentNode;
  var w = wrap.clientWidth;
  if (!w) return;
  var h = window.innerWidth <= 768 ? 380 : 560;
  var dpr = window.devicePixelRatio || 1;
  canvas.width = Math.round(w * dpr);
  canvas.height = Math.round(h * dpr);
  canvas.style.height = h + 'px';
  var ctx = canvas.getContext('2d');
  ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

  var dark = isDark();
  var color = COUNTRIES[country].color;
  var st = state[country];

  ctx.fillStyle = dark ? '#1a2340' : '#f0f4f8';
  ctx.fillRect(0, 0, w, h);

  ctx.strokeStyle = dark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.06)';
  ctx.lineWidth = 1;
  for (var i = 1; i < 10; i++) {
    ctx.beginPath(); ctx.moveTo(w * i / 10, 0); ctx.lineTo(w * i / 10, h); ctx.stroke();
    ctx.beginPath(); ctx.moveTo(0, h * i / 10); ctx.lineTo(w, h * i / 10); ctx.stroke();
  }

  var minLat = Infinity, maxLat = -Infinity, minLng = Infinity, maxLng = -Infinity;
  data.forEach(function (s) {
    minLat = Math.min(minLat, s.lat); maxLat = Math.max(maxLat, s.lat);
    minLng = Math.min(minLng, s.lng); maxLng = Math.max(maxLng, s.lng);
  });
  var dLat = (maxLat - minLat) || 0.01, dLng = (maxLng - minLng) || 0.01;
  minLat -= dLat * 0.15; maxLat += dLat * 0.15; minLng -= dLng * 0.15; maxLng += dLng * 0.15;
  dLat = maxLat - minLat; dLng = maxLng - minLng;

  var pins = data.map(function (s) {
    return {
      id: s.id, s: s,
      x: (s.lng - minLng) / dLng * w,
      y: (1 - (s.lat - minLat) / dLat) * h
    };
  });

  var groups = {};
  pins.forEach(function (p) { (groups[p.s.city] = groups[p.s.city] || []).push(p); });

  Object.keys(groups).forEach(function (city) {
    var g = groups[city];
    var cx = 0, cy = 0, minY = Infinity;
    g.forEach(function (p) { cx += p.x; cy += p.y; minY = Math.min(minY, p.y); });
    cx /= g.length; cy /= g.length;
    var r = 40;
    g.forEach(function (p) { r = Math.max(r, Math.hypot(p.x - cx, p.y - cy) + 34); });
    ctx.globalAlpha = dark ? 0.07 : 0.09;
    ctx.fillStyle = color;
    ctx.beginPath(); ctx.arc(cx, cy, r, 0, Math.PI * 2); ctx.fill();
    ctx.globalAlpha = 1;
    g._cx = cx; g._cy = cy; g._r = r;
  });

  ctx.textAlign = 'center';
  ctx.textBaseline = 'middle';
  Object.keys(groups).forEach(function (city) {
    var g = groups[city];
    var minY = Math.min.apply(null, g.map(function (p) { return p.y; }));
    var ly = Math.max(12, minY - 30);
    ctx.font = '700 11px Manrope, system-ui, sans-serif';
    ctx.globalAlpha = 0.75;
    ctx.fillStyle = dark ? '#e8edf5' : '#1a2a2a';
    ctx.fillText(city.toUpperCase(), g._cx, ly);
    ctx.globalAlpha = 1;
  });

  pins.forEach(function (p) {
    var hovered = st.hover === p.id, selected = st.selected === p.id;
    var r = hovered ? 18 : 15;
    ctx.beginPath(); ctx.arc(p.x, p.y, r, 0, Math.PI * 2);
    ctx.fillStyle = selected ? '#ffffff' : color;
    ctx.fill();
    ctx.lineWidth = 2;
    ctx.strokeStyle = selected ? color : '#ffffff';
    ctx.stroke();
    ctx.fillStyle = selected ? color : '#ffffff';
    ctx.font = '800 ' + (hovered ? 13 : 12) + 'px Manrope, system-ui, sans-serif';
    ctx.fillText(String(p.id), p.x, p.y + 0.5);
  });
  canvas._pins = pins;
}

function redraw(country) {
  drawMap('gsmap-canvas-' + country, COUNTRIES[country].data, country);
}

function hitPin(canvas, e) {
  var rect = canvas.getBoundingClientRect();
  var x = e.clientX - rect.left, y = e.clientY - rect.top;
  var pins = canvas._pins || [], found = null;
  pins.forEach(function (p) {
    if (Math.hypot(p.x - x, p.y - y) <= 15 + 4) found = p;
  });
  return found;
}

function showTip(p, e) {
  tip.innerHTML = '<b>' + esc(p.s.name) + '</b>' + esc(p.s.addr);
  tip.style.setProperty('display', 'block', 'important');
  var tw = tip.offsetWidth, th = tip.offsetHeight;
  var left = e.clientX + 14, top = e.clientY + 14;
  if (left + tw > window.innerWidth - 8) left = e.clientX - tw - 14;
  if (top + th > window.innerHeight - 8) top = e.clientY - th - 14;
  tip.style.setProperty('left', Math.max(4, left) + 'px', 'important');
  tip.style.setProperty('top', Math.max(4, top) + 'px', 'important');
}
function hideTip() { tip.style.setProperty('display', 'none', 'important'); }

function selectCard(country, id) {
  state[country].selected = id;
  var list = document.getElementById('gsmap-list-' + country);
  list.querySelectorAll('.gsmap-card').forEach(function (c) { c.classList.remove('gsmap-selected'); });
  var card = document.getElementById('gsmap-card-' + country + '-' + id);
  if (card) {
    card.classList.add('gsmap-selected');
    var top = card.offsetTop - list.offsetTop - (list.clientHeight - card.offsetHeight) / 2;
    if (list.scrollHeight > list.clientHeight + 2) list.scrollTo({ top: top, behavior: 'smooth' });
    else card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }
  redraw(country);
}
window.selectCard = selectCard;

function bindCanvas(country) {
  var canvas = document.getElementById('gsmap-canvas-' + country);
  canvas.addEventListener('mousemove', function (e) {
    var p = hitPin(canvas, e);
    var id = p ? p.id : null;
    canvas.style.cursor = p ? 'pointer' : 'default';
    if (p) showTip(p, e); else hideTip();
    if (state[country].hover !== id) { state[country].hover = id; redraw(country); }
  });
  canvas.addEventListener('click', function (e) {
    var p = hitPin(canvas, e);
    if (p) selectCard(country, p.id);
  });
  canvas.addEventListener('mouseleave', function () {
    hideTip();
    if (state[country].hover !== null) { state[country].hover = null; redraw(country); }
  });
}

function activate(country) {
  document.querySelectorAll('.gsmap-tab').forEach(function (t) {
    t.classList.toggle('gsmap-active', t.getAttribute('data-country') === country);
  });
  Object.keys(COUNTRIES).forEach(function (k) {
    document.getElementById('gsmap-panel-' + k).classList.toggle('gsmap-active', k === country);
  });
  hideTip();
  redraw(country);
}

document.querySelectorAll('.gsmap-tab').forEach(function (t) {
  t.addEventListener('click', function () { activate(t.getAttribute('data-country')); });
});
Object.keys(COUNTRIES).forEach(function (k) { buildList(k); bindCanvas(k); });
activate('az');

var rt;
window.addEventListener('resize', function () {
  clearTimeout(rt);
  rt = setTimeout(function () {
    var active = document.querySelector('.gsmap-tab.gsmap-active').getAttribute('data-country');
    redraw(active);
  }, 100);
});
if (window.matchMedia) {
  var mq = window.matchMedia('(prefers-color-scheme: dark)');
  var onChange = function () {
    var active = document.querySelector('.gsmap-tab.gsmap-active').getAttribute('data-country');
    redraw(active);
  };
  if (mq.addEventListener) mq.addEventListener('change', onChange);
}
})();
</script>
@endpush
