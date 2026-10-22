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
  <div class="gsmapblock-canvaswrap"><div id="gsmapblock-map-az" style="width:100%; height:500px; border-radius:12px; overflow:hidden; z-index:1;"></div></div>
</div>
<div class="gsmapblock-panel" id="gsmapblock-panel-ge">
  <div class="gsmapblock-list" id="gsmapblock-list-ge"></div>
  <div class="gsmapblock-canvaswrap"><div id="gsmapblock-map-ge" style="width:100%; height:500px; border-radius:12px; overflow:hidden; z-index:1;"></div></div>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
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
var maps = { az: null, ge: null };
var markers = { az: {}, ge: {} };

function esc(s) {
  return String(s).replace(/[&<>"']/g, function (c) {
    return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
  });
}

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

function popupHtml(s) {
  return '<div style="font-family:Manrope,sans-serif;min-width:160px"><b style="font-size:13px">' + esc(s.name) + '</b><br><span style="font-size:11px;color:#666">' + esc(s.addr) + '</span></div>';
}

function createMarker(country, sanatorium) {
  var color = COUNTRIES[country].color;
  var pinSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36">' +
    '<circle cx="18" cy="18" r="16" fill="' + color + '" stroke="white" stroke-width="2.5"/>' +
    '<text x="18" y="23" text-anchor="middle" font-family="Manrope,Arial,sans-serif" font-size="13" font-weight="bold" fill="white">' + sanatorium.id + '</text>' +
    '</svg>';
  var icon = L.divIcon({ html: pinSvg, className: '', iconSize: [36, 36], iconAnchor: [18, 18], popupAnchor: [0, -20] });
  var marker = L.marker([sanatorium.lat, sanatorium.lng], { icon: icon, title: sanatorium.name })
    .addTo(maps[country])
    .bindPopup(popupHtml(sanatorium));
  marker.on('click', function () { select(country, sanatorium.id, true); });
  markers[country][sanatorium.id] = marker;
}

function initMap(country) {
  if (maps[country] || !window.L) return;
  var cfg = country === 'az' ? { zoom: 7, center: [40.5, 48.2] } : { zoom: 8, center: [42.0, 43.0] };
  maps[country] = L.map('gsmapblock-map-' + country, { zoomControl: true }).setView(cfg.center, cfg.zoom);
  L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
    attribution: 'Tiles &copy; Esri &mdash; Source: Esri, USGS, NOAA',
    maxZoom: 18
  }).addTo(maps[country]);
  COUNTRIES[country].data.forEach(function (s) { createMarker(country, s); });
}

function select(country, id, fromMarker) {
  var list = document.getElementById('gsmapblock-list-' + country);
  list.querySelectorAll('.gsmapblock-card').forEach(function (c) { c.classList.remove('gsmapblock-selected'); });
  var card = document.getElementById('gsmapblock-card-' + country + '-' + id);
  if (card) {
    card.classList.add('gsmapblock-selected');
    if (list.scrollHeight > list.clientHeight + 2) {
      list.scrollTo({ top: card.offsetTop - list.offsetTop - (list.clientHeight - card.offsetHeight) / 2, behavior: 'smooth' });
    }
  }
  var marker = markers[country][id];
  if (marker && !fromMarker) {
    maps[country].panTo(marker.getLatLng());
    marker.openPopup();
  }
}

function gsmapSwitch(country) {
  Object.keys(COUNTRIES).forEach(function (k) {
    document.getElementById('gsmapblock-tab-' + k).classList.toggle('gsmapblock-active', k === country);
    document.getElementById('gsmapblock-panel-' + k).classList.toggle('gsmapblock-active', k === country);
  });
  setTimeout(function () { initMap(country); if (maps[country]) maps[country].invalidateSize(); }, 50);
}
window.gsmapSwitch = gsmapSwitch;

Object.keys(COUNTRIES).forEach(function (k) { buildList(k); });
gsmapSwitch('az');
})();
</script>
</section>
