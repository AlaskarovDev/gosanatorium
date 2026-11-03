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
        return baseUrl('/sravnenie') + '?' + list.map(function (i, n) { return 's' + (n + 1) + '=' + encodeURIComponent(i.slug); }).join('&');
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
        if (onComparePage || list.length < 2) {
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
        html += '</div><a class="gs-cb-go" href="' + compareUrl(list) + '">Сравнить →</a></div>';
        bar.innerHTML = html;
        document.body.classList.add('gs-compare-bar-on');
    }

    function refresh() {
        var list = load();
        syncButtons(list);
        renderBar(list);
    }

    function remove(slug) {
        save(load().filter(function (i) { return i.slug !== slug; }));
        refresh();
    }

    document.addEventListener('click', function (e) {
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

    window.addEventListener('storage', function (e) { if (e.key === KEY) refresh(); });
    window.addEventListener('pageshow', refresh);
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', refresh);
    else refresh();
})();
