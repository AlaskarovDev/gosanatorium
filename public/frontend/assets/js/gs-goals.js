/*
 * GoSanatorium conversion goals (Task 18).
 * One event per action goes to:
 *   - Yandex Metrika: ym(COUNTER, 'reachGoal', <goal>)   → goal type «JavaScript-событие», identifier = <goal>
 *   - GTM dataLayer: { event: <goal>, goal_label: ... }   → GTM trigger «Custom Event» = <goal> → GA4 event tag
 *   - GA4 directly (only if Admin → Google Analytics is on): gtag('event', <goal>, { send_to: G-... })
 *
 * Goals: whatsapp_click, telegram_click, phone_click, booking_modal_open,
 *        booking_request_sent, contact_form_sent, show_prices_click
 *
 * Other scripts can call window.gsGoal('<goal>', { ...params }).
 * Debug: add ?gs_goals_debug=1 to the URL (or localStorage.gsGoalsDebug = 1) to log events to the console.
 */
(function () {
    'use strict';

    var cfg = window.GS_GOALS || {};
    var COUNTER = cfg.metrika || 109569026;
    var GA4 = cfg.ga4 || '';
    var debug = /[?&]gs_goals_debug=1/.test(location.search);
    try { debug = debug || localStorage.getItem('gsGoalsDebug') === '1'; } catch (e) {}

    var last = {};

    function gsGoal(name, params) {
        params = params || {};
        // Same goal twice within 1.5 s = one action (double click, two listeners)
        var now = Date.now();
        if (last[name] && now - last[name] < 1500) return;
        last[name] = now;

        try {
            if (typeof window.ym === 'function') window.ym(COUNTER, 'reachGoal', name, params);
        } catch (e) {}

        try {
            window.dataLayer = window.dataLayer || [];
            var ev = { event: name, goal_page: location.pathname };
            for (var k in params) if (Object.prototype.hasOwnProperty.call(params, k)) ev['goal_' + k] = params[k];
            window.dataLayer.push(ev);
        } catch (e) {}

        try {
            if (GA4 && typeof window.gtag === 'function') {
                var gp = { send_to: GA4 };
                for (var p in params) if (Object.prototype.hasOwnProperty.call(params, p)) gp[p] = params[p];
                window.gtag('event', name, gp);
            }
        } catch (e) {}

        if (debug && window.console) console.info('[gs-goal]', name, params);
    }
    window.gsGoal = gsGoal;

    // ---------- Clicks: WhatsApp / Telegram / phone / «Показать цены» ----------
    var WA = /^(https?:\/\/)?(wa\.me|api\.whatsapp\.com|web\.whatsapp\.com|chat\.whatsapp\.com)\b|^whatsapp:/i;
    var TG = /^(https?:\/\/)?(t\.me|telegram\.me|telegram\.dog)\b|^tg:/i;

    function placeOf(el) {
        if (el.closest('footer, .gsfooter, .site-footer')) return 'footer';
        if (el.closest('header, .td_site_header, .gs-header')) return 'header';
        if (el.closest('.modal')) return 'modal';
        if (el.closest('.whatsapp-float, .wa-float, [class*="whatsapp-float"]')) return 'float';
        if (el.closest('.mobile-bottom-nav, [class*="bottom-nav"]')) return 'bottom_nav';
        return 'content';
    }

    document.addEventListener('click', function (e) {
        var t = e.target;
        if (!t || !t.closest) return;

        var custom = t.closest('[data-goal]');
        if (custom) gsGoal(custom.getAttribute('data-goal'), { place: placeOf(custom) });

        var a = t.closest('a[href]');
        if (a) {
            var href = (a.getAttribute('href') || '').trim();
            if (WA.test(href)) gsGoal('whatsapp_click', { place: placeOf(a) });
            else if (TG.test(href)) gsGoal('telegram_click', { place: placeOf(a) });
            else if (/^tel:/i.test(href)) gsGoal('phone_click', { place: placeOf(a) });
        }

        if (t.closest('#rsSubmit')) gsGoal('show_prices_click', { place: 'room_search' });
    }, true);

    // ---------- Booking modal opened ----------
    var BOOKING_MODALS = ['roomBookingModal', 'roomSelectionModal', 'guestBookingModal'];
    document.addEventListener('shown.bs.modal', function (e) {
        var id = e.target && e.target.id;
        if (id && BOOKING_MODALS.indexOf(id) !== -1) gsGoal('booking_modal_open', { modal: id });
    });

    // ---------- Booking request sent (AJAX booking endpoints) ----------
    var BOOKING_URL = /\/guest-booking(\?|$)/;
    if (typeof window.fetch === 'function') {
        var origFetch = window.fetch;
        window.fetch = function (input, init) {
            var url = typeof input === 'string' ? input : (input && input.url) || '';
            var method = ((init && init.method) || (input && input.method) || 'GET').toUpperCase();
            var p = origFetch.apply(this, arguments);
            if (method === 'POST' && BOOKING_URL.test(url.split('#')[0])) {
                p.then(function (res) {
                    if (!res || !res.ok) return;
                    res.clone().json().then(function (data) {
                        if (data && data.success) gsGoal('booking_request_sent', { source: 'popup' });
                    }).catch(function () {});
                }).catch(function () {});
            }
            return p;
        };
    }

    // ---------- Server-side marks (booking confirmation page etc.) ----------
    // <span data-gs-goal-once="booking_request_sent" data-gs-goal-key="ABC123" hidden></span>
    function fireOnce(goal, key, source) {
        var k = 'gsGoal:' + goal + ':' + key;
        var seen = false;
        try { seen = localStorage.getItem(k) === '1'; localStorage.setItem(k, '1'); } catch (e) {}
        if (!seen) gsGoal(goal, { source: source || 'page' });
    }

    function fireMarks() {
        (cfg.once || []).forEach(function (o) { if (o && o.goal) fireOnce(o.goal, o.key, o.source); });
        var marks = document.querySelectorAll('[data-gs-goal-once]');
        for (var i = 0; i < marks.length; i++) {
            var m = marks[i];
            fireOnce(m.getAttribute('data-gs-goal-once'), m.getAttribute('data-gs-goal-key') || location.pathname, m.getAttribute('data-gs-goal-source'));
        }
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', fireMarks);
    else fireMarks();
})();
