@once
<script>
(function () {
    // Countdown text is rendered on the server («Осталось N дней» / «Последний день акции»).
    // Here we only mark a card as finished if the page stays open past the end of the offer (Baku time).
    function tick() {
        var now = Date.now();
        document.querySelectorAll('[data-gsp-deadline]').forEach(function (card) {
            if (card.classList.contains('gsp-card--expired')) return;
            var end = new Date(card.getAttribute('data-gsp-deadline')).getTime();
            if (isNaN(end) || end > now) return;
            card.classList.add('gsp-card--expired');
            var body = card.querySelector('.gsp-body');
            if (body && !body.querySelector('.gsp-expired')) {
                var el = document.createElement('div');
                el.className = 'gsp-expired';
                el.textContent = 'Акция завершена';
                body.appendChild(el);
            }
        });
    }
    function init() {
        tick(); setInterval(tick, 60000);
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
