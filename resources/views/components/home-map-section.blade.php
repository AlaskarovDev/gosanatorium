<section id="section-map" class="gsmapblock-section">
<style>
.gsmapblock-section{display:block!important;background:#f7f9fb!important;font-family:'Inter',system-ui,sans-serif!important;font-size:14px!important;color:#1a2a2a!important;margin:0!important;padding:0!important}
.gsmapblock-header{display:block!important;background:linear-gradient(135deg, #1a9e87 0%, #2ec4a9 50%, #17b09a 100%)!important;padding:48px 20px 36px!important;text-align:center!important}
.gsmapblock-title{font-family:'Manrope',system-ui,sans-serif!important;font-weight:800!important;font-size:34px!important;color:#fff!important;margin:0!important;line-height:1.2!important}
.gsmapblock-tabs{display:flex!important;justify-content:center!important;gap:8px!important;background:#fff!important;border-bottom:1px solid #e2e8f0!important;padding:0 12px!important}
.gsmapblock-tab{display:flex!important;align-items:center!important;gap:8px!important;background:transparent!important;border:0!important;border-bottom:3px solid transparent!important;padding:14px 20px!important;cursor:pointer!important;font-family:'Manrope',system-ui,sans-serif!important;font-weight:700!important;font-size:15px!important;color:#64748b!important;margin:0 0 -1px!important;border-radius:0!important}
.gsmapblock-pill{display:inline-block!important;font-size:11px!important;font-weight:800!important;padding:2px 9px!important;border-radius:999px!important;background:#eef2f6!important;color:#64748b!important}
.gsmapblock-tab-az.gsmapblock-active{color:#1a9e87!important;border-bottom-color:#1a9e87!important}
.gsmapblock-tab-az.gsmapblock-active .gsmapblock-pill{background:#1a9e87!important;color:#fff!important}
.gsmapblock-tab-ge.gsmapblock-active{color:#d4580a!important;border-bottom-color:#d4580a!important}
.gsmapblock-tab-ge.gsmapblock-active .gsmapblock-pill{background:#d4580a!important;color:#fff!important}
.gsmapblock-panel{display:none!important;max-width:1320px!important;margin:0 auto!important;padding:24px 16px 40px!important}
.gsmapblock-panel.gsmapblock-active{display:grid!important;grid-template-columns:340px 1fr!important;gap:20px!important;align-items:start!important}
.gsmapblock-list{max-height:500px!important;overflow-y:auto!important;display:flex!important;flex-direction:column!important;gap:10px!important;padding:2px 6px 2px 2px!important}
.gsmapblock-card{display:flex!important;gap:12px!important;align-items:flex-start!important;background:#fff!important;border:1.5px solid #e2e8f0!important;border-radius:14px!important;padding:14px 16px!important;cursor:pointer!important;transition:border-color .15s,box-shadow .15s!important}
.gsmapblock-card-az:hover,.gsmapblock-card-az.gsmapblock-selected{border-color:#1a9e87!important;box-shadow:0 0 0 3px rgba(26,158,135,.12)!important}
.gsmapblock-card-ge:hover,.gsmapblock-card-ge.gsmapblock-selected{border-color:#d4580a!important;box-shadow:0 0 0 3px rgba(212,88,10,.12)!important}
.gsmapblock-num{flex:0 0 28px!important;width:28px!important;height:28px!important;border-radius:8px!important;display:flex!important;align-items:center!important;justify-content:center!important;font-family:'Manrope',system-ui,sans-serif!important;font-weight:800!important;font-size:12px!important}
.gsmapblock-card-az .gsmapblock-num,.gsmapblock-card-az .gsmapblock-type{background:rgba(26,158,135,.12)!important;color:#1a9e87!important}
.gsmapblock-card-ge .gsmapblock-num,.gsmapblock-card-ge .gsmapblock-type{background:rgba(212,88,10,.12)!important;color:#d4580a!important}
.gsmapblock-info{min-width:0!important}
.gsmapblock-name{font-family:'Manrope',system-ui,sans-serif!important;font-weight:700!important;font-size:13.5px!important;color:#1a2a2a!important;margin:0 0 4px!important;line-height:1.3!important}
.gsmapblock-addr{font-size:12px!important;font-weight:400!important;color:#64748b!important;margin:0 0 7px!important;line-height:1.35!important}
.gsmapblock-type{display:inline-block!important;font-family:'Manrope',system-ui,sans-serif!important;font-weight:700!important;font-size:10.5px!important;padding:2px 9px!important;border-radius:999px!important}
.gsmapblock-canvaswrap{width:100%!important;height:500px!important;border-radius:14px!important;overflow:hidden!important;border:1.5px solid #e2e8f0!important;background:#f0f4f8!important}
.gsmapblock-canvas{display:block!important;width:100%!important;height:500px!important}
.gsmapblock-tooltip{position:fixed!important;z-index:99999!important;pointer-events:none!important;display:none!important;max-width:260px!important;background:#1a2a2a!important;color:#fff!important;border-radius:10px!important;padding:8px 12px!important;font-size:12px!important;line-height:1.4!important;box-shadow:0 6px 20px rgba(0,0,0,.25)!important}
.gsmapblock-tooltip b{display:block!important;font-weight:700!important;font-size:13px!important}
@media (max-width:768px){
.gsmapblock-title{font-size:26px!important}
.gsmapblock-panel.gsmapblock-active{grid-template-columns:1fr!important}
.gsmapblock-tab{padding:12px 12px!important;font-size:14px!important}
}
</style>
<div class="gsmapblock-header"><h2 class="gsmapblock-title">Карта санаториев</h2></div>
<div class="gsmapblock-tabs" role="tablist">
  <button type="button" class="gsmapblock-tab gsmapblock-tab-az gsmapblock-active" id="gsmapblock-tab-az" onclick="gsmapSwitch('az')"><span>Азербайджан</span><span class="gsmapblock-pill">17</span></button>
  <button type="button" class="gsmapblock-tab gsmapblock-tab-ge" id="gsmapblock-tab-ge" onclick="gsmapSwitch('ge')"><span>Грузия</span><span class="gsmapblock-pill">5</span></button>
</div>
<div class="gsmapblock-panel gsmapblock-active" id="gsmapblock-panel-az">
  <div class="gsmapblock-list" id="gsmapblock-list-az"></div>
  <div class="gsmapblock-canvaswrap"><canvas class="gsmapblock-canvas" id="gsmapblock-canvas-az"></canvas></div>
</div>
<div class="gsmapblock-panel" id="gsmapblock-panel-ge">
  <div class="gsmapblock-list" id="gsmapblock-list-ge"></div>
  <div class="gsmapblock-canvaswrap"><canvas class="gsmapblock-canvas" id="gsmapblock-canvas-ge"></canvas></div>
</div>
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

var COUNTRIES = { az: { data: AZ_SANATORIUMS, color: '#1a9e87' }, ge: { data: GE_SANATORIUMS, color: '#d4580a' } };
var state = { az: { selected: null, hover: null }, ge: { selected: null, hover: null } };
var current = 'az';
var H = 500;

function esc(s) {
  return String(s).replace(/[&<>"']/g, function (c) {
    return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
  });
}

var tip = document.createElement('div');
tip.className = 'gsmapblock-tooltip';
document.body.appendChild(tip);

function buildList(country) {
  var el = document.getElementById('gsmapblock-list-' + country);
  el.innerHTML = COUNTRIES[country].data.map(function (s) {
    return '<div class="gsmapblock-card gsmapblock-card-' + country + '" data-id="' + s.id + '" id="gsmapblock-card-' + country + '-' + s.id + '">' +
      '<div class="gsmapblock-num">' + s.id + '</div><div class="gsmapblock-info">' +
      '<div class="gsmapblock-name">' + esc(s.name) + '</div>' +
      '<div class="gsmapblock-addr">' + esc(s.addr) + '</div>' +
      '<span class="gsmapblock-type">' + esc(s.type) + '</span></div></div>';
  }).join('');
  el.addEventListener('click', function (e) {
    var card = e.target.closest('.gsmapblock-card');
    if (card) select(country, parseInt(card.getAttribute('data-id'), 10));
  });
}

function gsmapDraw(canvasId, data, country) {
  var canvas = document.getElementById(canvasId);
  var w = canvas.parentNode.clientWidth;
  if (!w) return;
  var h = H, dpr = window.devicePixelRatio || 1;
  canvas.width = Math.round(w * dpr);
  canvas.height = Math.round(h * dpr);
  canvas.style.setProperty('height', h + 'px', 'important');
  var ctx = canvas.getContext('2d');
  ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  var color = COUNTRIES[country].color, st = state[country];

  ctx.fillStyle = '#1a3a2a';
  ctx.fillRect(0, 0, w, h);
  ctx.strokeStyle = 'rgba(255,255,255,0.07)';
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
    return { id: s.id, s: s, x: (s.lng - minLng) / dLng * w, y: (1 - (s.lat - minLat) / dLat) * h };
  });
  var groups = {};
  pins.forEach(function (p) { (groups[p.s.city] = groups[p.s.city] || []).push(p); });

  Object.keys(groups).forEach(function (city) {
    var g = groups[city], cx = 0, cy = 0, r = 40;
    g.forEach(function (p) { cx += p.x; cy += p.y; });
    cx /= g.length; cy /= g.length;
    g.forEach(function (p) { r = Math.max(r, Math.hypot(p.x - cx, p.y - cy) + 34); });
    ctx.globalAlpha = 0.15;
    ctx.fillStyle = color;
    ctx.beginPath(); ctx.arc(cx, cy, r, 0, Math.PI * 2); ctx.fill();
    ctx.globalAlpha = 1;
    g._cx = cx; g._cy = cy;
  });

  ctx.textAlign = 'center';
  ctx.textBaseline = 'middle';
  Object.keys(groups).forEach(function (city) {
    var g = groups[city];
    var minY = Math.min.apply(null, g.map(function (p) { return p.y; }));
    ctx.font = '700 11px Manrope, system-ui, sans-serif';
    ctx.globalAlpha = 1;
    ctx.fillStyle = 'rgba(255,255,255,0.7)';
    ctx.fillText(city.toUpperCase(), g._cx, Math.max(12, minY - 30));
    ctx.globalAlpha = 1;
  });

  pins.forEach(function (p) {
    var hovered = st.hover === p.id, selected = st.selected === p.id;
    ctx.beginPath(); ctx.arc(p.x, p.y, hovered ? 18 : 15, 0, Math.PI * 2);
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
window.gsmapDraw = gsmapDraw;

function redraw(country) {
  gsmapDraw('gsmapblock-canvas-' + country, COUNTRIES[country].data, country);
}

function hitPin(canvas, e) {
  var rect = canvas.getBoundingClientRect();
  var x = e.clientX - rect.left, y = e.clientY - rect.top, found = null;
  (canvas._pins || []).forEach(function (p) { if (Math.hypot(p.x - x, p.y - y) <= 19) found = p; });
  return found;
}

function showTip(p, e) {
  tip.innerHTML = '<b>' + esc(p.s.name) + '</b>' + esc(p.s.addr);
  tip.style.setProperty('display', 'block', 'important');
  var left = e.clientX + 14, top = e.clientY + 14;
  if (left + tip.offsetWidth > window.innerWidth - 8) left = e.clientX - tip.offsetWidth - 14;
  if (top + tip.offsetHeight > window.innerHeight - 8) top = e.clientY - tip.offsetHeight - 14;
  tip.style.setProperty('left', Math.max(4, left) + 'px', 'important');
  tip.style.setProperty('top', Math.max(4, top) + 'px', 'important');
}
function hideTip() { tip.style.setProperty('display', 'none', 'important'); }

function select(country, id) {
  state[country].selected = id;
  var list = document.getElementById('gsmapblock-list-' + country);
  list.querySelectorAll('.gsmapblock-card').forEach(function (c) { c.classList.remove('gsmapblock-selected'); });
  var card = document.getElementById('gsmapblock-card-' + country + '-' + id);
  if (card) {
    card.classList.add('gsmapblock-selected');
    if (list.scrollHeight > list.clientHeight + 2) {
      list.scrollTo({ top: card.offsetTop - list.offsetTop - (list.clientHeight - card.offsetHeight) / 2, behavior: 'smooth' });
    }
  }
  redraw(country);
}

function bindCanvas(country) {
  var canvas = document.getElementById('gsmapblock-canvas-' + country);
  canvas.addEventListener('mousemove', function (e) {
    var p = hitPin(canvas, e), id = p ? p.id : null;
    canvas.style.cursor = p ? 'pointer' : 'default';
    if (p) showTip(p, e); else hideTip();
    if (state[country].hover !== id) { state[country].hover = id; redraw(country); }
  });
  canvas.addEventListener('click', function (e) {
    var p = hitPin(canvas, e);
    if (p) select(country, p.id);
  });
  canvas.addEventListener('mouseleave', function () {
    hideTip();
    if (state[country].hover !== null) { state[country].hover = null; redraw(country); }
  });
}

function gsmapSwitch(country) {
  current = country;
  Object.keys(COUNTRIES).forEach(function (k) {
    document.getElementById('gsmapblock-tab-' + k).classList.toggle('gsmapblock-active', k === country);
    document.getElementById('gsmapblock-panel-' + k).classList.toggle('gsmapblock-active', k === country);
  });
  hideTip();
  setTimeout(function () { redraw(country); }, 10);
}
window.gsmapSwitch = gsmapSwitch;

Object.keys(COUNTRIES).forEach(function (k) { buildList(k); bindCanvas(k); });
gsmapSwitch('az');
var rt;
window.addEventListener('resize', function () { clearTimeout(rt); rt = setTimeout(function () { redraw(current); }, 100); });
})();
</script>
</section>
