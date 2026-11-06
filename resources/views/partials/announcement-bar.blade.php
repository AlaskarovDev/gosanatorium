<style>
.announcement-bar{background:linear-gradient(90deg,#1a7c2f 0%,#2e9e47 100%);color:#fff;padding:10px 20px;display:flex;align-items:center;justify-content:center;gap:12px;position:relative;z-index:10000;font-size:13px}
.announcement-bar__content{display:flex;align-items:center;gap:10px;flex-wrap:wrap;justify-content:center;padding-right:30px}
.announcement-bar__fire{font-size:16px}
.announcement-bar__text{color:rgba(255,255,255,.95);font-weight:500}
.announcement-bar__link{color:#fff;font-weight:700;text-decoration:underline;white-space:nowrap}
.announcement-bar__link:hover{color:#ffe082}
.announcement-bar__close{position:absolute;right:16px;top:50%;transform:translateY(-50%);background:none;border:none;color:#fff;font-size:16px;cursor:pointer;opacity:.7;padding:4px 8px}
.announcement-bar__close:hover{opacity:1}
/* Fixed header sits below the bar; offset is set by JS and shrinks as the page scrolls */
.tg-header__area,.tg-header__area.header-sticky{top:var(--ann-offset,0px) !important}
.ann-closing .tg-header__area{transition:top .3s ease}
.announcement-bar{overflow:hidden;box-sizing:border-box;transition:max-height .3s ease,padding .3s ease,opacity .3s ease}
@media (max-width:600px){.announcement-bar__text{display:none}}
</style>
<div class="announcement-bar" id="announcementBar">
  <div class="announcement-bar__content">
    <span class="announcement-bar__fire">🔥</span>
    <span class="announcement-bar__text">Специальные акции: Gold Naftalan — 5 ночей + 2 в подарок · Гашалты — скидка 10% · Sun City — скидка до 10% · Парк Нафталан — скидка 15%</span>
    <a href="/ru/akcii" class="announcement-bar__link">Смотреть все акции →</a>
  </div>
  <button type="button" class="announcement-bar__close" onclick="closeAnnouncementBar()" aria-label="Закрыть">✕</button>
</div>
<script>
(function () {
  var root = document.documentElement;
  function bar() { return document.getElementById('announcementBar'); }
  function update() {
    var b = bar();
    var h = (b && b.style.display !== 'none') ? b.offsetHeight : 0;
    var y = window.pageYOffset || document.documentElement.scrollTop || 0;
    root.style.setProperty('--ann-offset', Math.max(0, h - y) + 'px');
  }
  try {
    if (sessionStorage.getItem('announcementClosed') === 'true') {
      bar().style.display = 'none';
    }
  } catch (e) {}
  update();
  window.addEventListener('scroll', update, { passive: true });
  window.addEventListener('resize', update);
  window.addEventListener('load', update);
  document.addEventListener('DOMContentLoaded', update);
  window.closeAnnouncementBar = function () {
    var b = bar();
    if (!b) return;
    try { sessionStorage.setItem('announcementClosed', 'true'); } catch (e) {}
    root.classList.add('ann-closing');
    b.style.maxHeight = b.offsetHeight + 'px';
    void b.offsetHeight;
    b.style.maxHeight = '0px';
    b.style.paddingTop = b.style.paddingBottom = '0px';
    b.style.opacity = '0';
    root.style.setProperty('--ann-offset', '0px');
    setTimeout(function () { b.style.display = 'none'; update(); root.classList.remove('ann-closing'); }, 320);
  };
})();
</script>
