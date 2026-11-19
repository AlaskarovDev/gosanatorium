{{-- Fixed header sits below the top bars (New Year bar, promotions bar); offset shrinks as the page scrolls --}}
<style>html.gs-has-topbar .tg-header__area,html.gs-has-topbar .tg-header__area.header-sticky{top:var(--ann-offset,0px) !important}.ann-closing .tg-header__area{transition:top .3s ease}</style>
<script>
(function () {
  if (!document.querySelector('.gs-topbar')) return;
  var root = document.documentElement;
  root.classList.add('gs-has-topbar');
  function bars() {
    return [].filter.call(document.querySelectorAll('.gs-topbar'), function (b) { return b.style.display !== 'none'; });
  }
  function scrollY() { return window.pageYOffset || root.scrollTop || 0; }
  function update() {
    var h = 0, y = scrollY(), ny = document.getElementById('nyBar');
    bars().forEach(function (b) { h += b.offsetHeight; });
    root.style.setProperty('--ann-offset', Math.max(0, h - y) + 'px');
    // visible part of the New Year bar (it is the first bar): elements fixed under the header move down by it
    root.style.setProperty('--ny-offset', (ny && ny.style.display !== 'none' ? Math.max(0, ny.offsetHeight - y) : 0) + 'px');
  }
  window.gsTopbarUpdate = update;
  window.gsTopbarClose = function (b, key) {
    if (!b) return;
    try { sessionStorage.setItem(key, 'true'); } catch (e) {}
    var rest = 0;
    bars().forEach(function (x) { if (x !== b) rest += x.offsetHeight; });
    root.classList.add('ann-closing');
    b.style.maxHeight = b.offsetHeight + 'px';
    void b.offsetHeight;
    b.style.maxHeight = '0px';
    b.style.paddingTop = b.style.paddingBottom = '0px';
    b.style.opacity = '0';
    root.style.setProperty('--ann-offset', Math.max(0, rest - scrollY()) + 'px');
    if (b.id === 'nyBar') root.style.setProperty('--ny-offset', '0px');
    setTimeout(function () { b.style.display = 'none'; update(); root.classList.remove('ann-closing'); }, 320);
  };
  update();
  window.addEventListener('scroll', update, { passive: true });
  window.addEventListener('resize', update);
  window.addEventListener('load', update);
  document.addEventListener('DOMContentLoaded', update);
})();
</script>
