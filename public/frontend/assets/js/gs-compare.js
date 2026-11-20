(function () {
    'use strict';
    var KEY = 'gosanatorium_compare';
    var MAX = 3;
    var MSG_MAX = 'Можно сравнивать не более 3 санаториев одновременно';

    function load() {
        try {
            var v = JSON.parse(localStorage.getItem(KEY) || '[]');
            return Array.isArray(v) ? v.filter(function (i) { return i && typeof i.slug === 'string'; }).slice(0, MAX) : [];
        } catch (e) { return []; }
    }
    function save(list) {
        try { localStorage.setItem(KEY, JSON.stringify(list)); } catch (e) {}
    }
    function lang() {
        var m = location.pathname.match(/^\/(ru|az|en)(\/|$)/);
        return m ? m[1] : 'ru';
    }
    function baseUrl(path) { return '/' + lang() + path; }
    function compareUrl(list) {
        return baseUrl('/sravnenie') + '?ids=' + list.map(function (i) { return encodeURIComponent(i.slug); }).join(',');
    }

    /* ---- Favorites (Task 29): localStorage, no login ---- */
    var FKEY = 'gosanatorium_favorites';
    var FMAX = 50;
    function loadFav() {
        try {
            var v = JSON.parse(localStorage.getItem(FKEY) || '[]');
            return Array.isArray(v) ? v.filter(function (i) { return i && typeof i.slug === 'string' && /^[a-z0-9-]+$/.test(i.slug); }).slice(0, FMAX) : [];
        } catch (e) { return []; }
    }
    function saveFav(list) {
        try { localStorage.setItem(FKEY, JSON.stringify(list.slice(0, FMAX))); } catch (e) {}
    }
    function favUrl(list) {
        return baseUrl('/izbrannoe') + (list.length ? '?ids=' + list.map(function (i) { return encodeURIComponent(i.slug); }).join(',') : '');
    }
    function syncFav() {
        var list = loadFav();
        var btns = document.querySelectorAll('.gs-fav-btn');
        for (var i = 0; i < btns.length; i++) {
            var b = btns[i], on = has(list, b.getAttribute('data-slug'));
            b.classList.toggle('active', on);
            b.classList.toggle('is-active', on);
            b.setAttribute('aria-pressed', on ? 'true' : 'false');
            var t = on ? 'В избранном' : 'В избранное';
            b.setAttribute('aria-label', t);
            b.setAttribute('title', t);
            var lbl = b.querySelector('.gs-fav-label');
            if (lbl) lbl.textContent = on ? '♥ В избранном' : '♡ В избранное';
        }
        var links = document.querySelectorAll('.gs-fav-link');
        for (var j = 0; j < links.length; j++) {
            links[j].setAttribute('href', favUrl(list));
            var c = links[j].querySelector('.gs-fav-count');
            if (c) { c.textContent = list.length ? String(list.length) : ''; c.style.display = list.length ? '' : 'none'; }
        }
        // /izbrannoe without ids: open the visitor's own list
        if (window.GS_FAV_PAGE && !/[?&]ids=/.test(location.search) && list.length) {
            location.replace(favUrl(list));
        }
    }
    function toggleFav(btn) {
        var slug = btn.getAttribute('data-slug'), list = loadFav();
        if (!slug) return;
        if (has(list, slug)) {
            list = list.filter(function (i) { return i.slug !== slug; });
        } else {
            list.unshift({ slug: slug, title: btn.getAttribute('data-title') || slug });
        }
        saveFav(list);
        syncFav();
        if (window.GS_FAV_PAGE && !has(list, slug)) {
            var card = btn.closest('.gscard-item');
            if (card) card.remove();
            history.replaceState(null, '', favUrl(list));
            if (!document.querySelector('.gscard-item')) location.href = favUrl(list);
        }
    }
    function esc(s) {
        return String(s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; });
    }
    function has(list, slug) { return list.some(function (i) { return i.slug === slug; }); }

    var onComparePage = !!window.GS_COMPARE_PAGE;

    function showTip(anchor) {
        var old = document.querySelector('.gs-compare-tip');
        if (old) old.remove();
        var tip = document.createElement('div');
        tip.className = 'gs-compare-tip';
        tip.setAttribute('role', 'alert');
        tip.textContent = MSG_MAX;
        document.body.appendChild(tip);
        var r = anchor.getBoundingClientRect();
        var w = tip.offsetWidth;
        var left = Math.max(8, Math.min(window.innerWidth - w - 8, r.left + r.width / 2 - w / 2));
        tip.style.left = (left + window.pageXOffset) + 'px';
        tip.style.top = (r.top + window.pageYOffset - tip.offsetHeight - 10) + 'px';
        setTimeout(function () { tip.remove(); }, 3500);
    }

    function syncButtons(list) {
        var btns = document.querySelectorAll('.gs-compare-btn');
        for (var i = 0; i < btns.length; i++) {
            var b = btns[i], on = has(list, b.getAttribute('data-slug'));
            b.classList.toggle('is-active', on);
            b.setAttribute('aria-pressed', on ? 'true' : 'false');
            var label = b.querySelector('.gs-compare-label');
            if (label) label.textContent = on ? 'В сравнении' : 'Сравнить';
            var card = b.closest('.gscard-card, .tg-listing-card-item');
            if (card) card.classList.toggle('gs-compare-active', on);
        }
    }

    function renderBar(list) {
        var bar = document.getElementById('gs-compare-bar');
        if (onComparePage || list.length < 1) {
            if (bar) bar.remove();
            document.body.classList.remove('gs-compare-bar-on');
            return;
        }
        if (!bar) {
            bar = document.createElement('div');
            bar.id = 'gs-compare-bar';
            document.body.appendChild(bar);
        }
        var html = '<div class="gs-cb-inner"><span class="gs-cb-label">Сравниваете:</span><div class="gs-cb-chips">';
        list.forEach(function (i) {
            html += '<span class="gs-cb-chip"><span class="gs-cb-name" title="' + esc(i.title) + '">' + esc(i.title) + '</span>' +
                '<button type="button" class="gs-cb-x" data-slug="' + esc(i.slug) + '" aria-label="Убрать">✕</button></span>';
        });
        if (list.length < MAX) {
            html += '<a class="gs-cb-add" href="' + baseUrl('/sanatoriyi') + '">+<span class="gs-cb-add-t"> добавить</span></a>';
        }
        html += '</div>' + (list.length < 2
            ? '<span class="gs-cb-go gs-cb-go--off" aria-disabled="true">Сравнить (' + list.length + ')</span>'
            : '<a class="gs-cb-go" href="' + compareUrl(list) + '">Сравнить (' + list.length + ') →</a>') + '</div>';
        bar.innerHTML = html;
        document.body.classList.add('gs-compare-bar-on');
        // keep above the mobile booking bar on sanatorium pages (Task 30)
        var sb = document.getElementById('gsStickyBook');
        var sbh = sb && getComputedStyle(sb).display !== 'none' ? sb.offsetHeight : 0;
        bar.style.bottom = sbh ? sbh + 'px' : '';
    }

    function refresh() {
        var list = load();
        syncButtons(list);
        renderBar(list);
        syncFav();
    }

    function remove(slug) {
        save(load().filter(function (i) { return i.slug !== slug; }));
        refresh();
    }

    document.addEventListener('keydown', function (e) {
        if ((e.key === 'Enter' || e.key === ' ') && e.target.classList && e.target.classList.contains('gs-fav-btn')) {
            e.preventDefault();
            toggleFav(e.target);
        }
    });

    document.addEventListener('click', function (e) {
        var fav = e.target.closest ? e.target.closest('.gs-fav-btn') : null;
        if (fav) {
            e.preventDefault();
            e.stopPropagation();
            toggleFav(fav);
            return;
        }
        var btn = e.target.closest ? e.target.closest('.gs-compare-btn') : null;
        if (btn) {
            e.preventDefault();
            e.stopPropagation();
            var slug = btn.getAttribute('data-slug'), list = load();
            if (has(list, slug)) {
                list = list.filter(function (i) { return i.slug !== slug; });
            } else if (list.length >= MAX) {
                showTip(btn);
                return;
            } else {
                list.push({ slug: slug, title: btn.getAttribute('data-title') || slug });
            }
            save(list);
            refresh();
            return;
        }
        var x = e.target.closest ? e.target.closest('.gs-cb-x') : null;
        if (x) { remove(x.getAttribute('data-slug')); return; }

        var rm = e.target.closest ? e.target.closest('[data-gs-cmp-remove]') : null;
        if (rm) {
            var s = rm.getAttribute('data-gs-cmp-remove');
            save(load().filter(function (i) { return i.slug !== s; }));
            var rest = (window.GS_COMPARE_PAGE || []).filter(function (i) { return i.slug !== s; });
            location.href = rest.length ? compareUrl(rest) : baseUrl('/sravnenie');
            return;
        }
        if (e.target.id === 'gs-cmp-share') {
            var url = location.href;
            var done = function () { e.target.textContent = 'Ссылка скопирована ✓'; };
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(done, function () { window.prompt('Ссылка:', url); });
            } else { window.prompt('Ссылка:', url); }
        }
    });

    window.addEventListener('storage', function (e) { if (e.key === KEY || e.key === FKEY) refresh(); });
    window.addEventListener('pageshow', refresh);
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', refresh);
    else refresh();
})();
