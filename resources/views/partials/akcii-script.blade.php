@once
<script>
(function () {
    function plural(n, f) { var m = n % 10, h = n % 100; return f[(h > 10 && h < 20) ? 2 : m === 1 ? 0 : (m > 1 && m < 5) ? 1 : 2]; }
    function tick() {
        var now = Date.now();
        document.querySelectorAll('[data-gsp-deadline]').forEach(function (card) {
            var left = new Date(card.getAttribute('data-gsp-deadline')).getTime() - now;
            if (isNaN(left)) return;
            if (left <= 0) { card.classList.add('gsp-card--expired'); return; }
            var m = Math.floor(left / 60000), d = Math.floor(m / 1440), h = Math.floor((m % 1440) / 60), mi = m % 60;
            var el = card.querySelector('[data-gsp-timer]');
            if (el) el.textContent = d + ' ' + plural(d, ['день', 'дня', 'дней']) + ' ' + h + ' ' + plural(h, ['час', 'часа', 'часов']) + ' ' + mi + ' ' + plural(mi, ['минута', 'минуты', 'минут']);
        });
    }
    function init() {
        tick(); setInterval(tick, 1000);
        document.querySelectorAll('[data-gsp-carousel]').forEach(function (c) {
            var track = c.querySelector('.gsp-track');
            c.querySelectorAll('[data-gsp-dir]').forEach(function (b) {
                b.addEventListener('click', function () { track.scrollBy({ left: track.clientWidth * 0.8 * b.getAttribute('data-gsp-dir'), behavior: 'smooth' }); });
            });
        });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();
</script>
@endonce
