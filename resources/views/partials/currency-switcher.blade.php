@php $csRu = app()->getLocale() === 'ru'; @endphp
<div class="tg-currency-switcher d-flex align-items-center ml-15 notranslate" translate="no">
    <button type="button" class="currency-btn" id="currencyBtn" onclick="openCurrencyModal()" aria-label="{{ $csRu ? 'Выберите валюту' : 'Select currency' }}">
        <span id="currencyFlag">🇺🇸</span>
        <span id="currencyCode">USD</span>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
    </button>
</div>

@once
<div class="currency-modal-overlay notranslate" translate="no" id="currencyModal" style="display:none" onclick="if(event.target===this)closeCurrencyModal()">
    <div class="currency-modal" role="dialog" aria-modal="true">
        <div class="currency-modal__header">
            <div class="currency-modal__title">{{ $csRu ? 'Выберите валюту' : 'Select currency' }}</div>
            <button type="button" class="currency-modal__close" onclick="closeCurrencyModal()" aria-label="Close">✕</button>
        </div>
        <p class="currency-modal__note">
            {{ $csRu
                ? 'Цены отображаются в выбранной валюте. Пересчёт ориентировочный; валюта и условия оплаты указываются при подтверждении бронирования.'
                : 'Prices are shown in the selected currency. Conversion is approximate; the currency and payment terms are confirmed when the booking is confirmed.' }}
        </p>
        <p class="currency-modal__note" data-gs-rate-note hidden></p>
        <div class="currency-modal__section-title">{{ $csRu ? 'Популярные валюты' : 'Popular currencies' }}</div>
        <div class="currency-modal__grid" id="recommendedCurrencies"></div>
        <div class="currency-modal__section-title">{{ $csRu ? 'Другие валюты' : 'Other currencies' }}</div>
        <div class="currency-modal__grid" id="allCurrencies"></div>
    </div>
</div>

<style>
.currency-btn{display:inline-flex;align-items:center;gap:6px;background:#1a56db;border:2px solid #1a56db;border-radius:8px;padding:8px 12px;font-size:14px;font-weight:600;font-family:'Poppins',sans-serif;color:#fff;cursor:pointer;line-height:1.2;white-space:nowrap;transition:all .3s ease}
.currency-btn:hover,.currency-btn:focus{background:#1648c0;border-color:#1648c0;outline:none}
.currency-modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.6);z-index:100000;align-items:flex-start;justify-content:center;padding:40px 16px;overflow-y:auto}
.currency-modal{background:#fff;border-radius:20px;width:100%;max-width:900px;padding:28px 32px 32px;box-shadow:0 20px 60px rgba(0,0,0,.3);color:#1e293b;font-family:'Poppins',sans-serif}
.currency-modal__header{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px}
.currency-modal__header h3,.currency-modal__title{color:#1e293b;line-height:1.1;margin:0;font-size:28px;font-weight:700}
.currency-modal__close{width:40px;height:40px;border:1px solid #e2e8f0;background:#fff;border-radius:8px;font-size:18px;cursor:pointer;color:#1e293b}
.currency-modal__note{color:#64748b;font-size:15px;line-height:1.5;margin:0 0 20px}
.currency-modal__section-title{font-weight:700;font-size:16px;margin:18px 0 10px}
.currency-modal__grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:8px}
.currency-item{display:flex;justify-content:space-between;align-items:center;text-align:left;padding:12px 16px;border:1px solid transparent;border-radius:10px;background:transparent;cursor:pointer;font-family:inherit}
.currency-item:hover{background:#f1f5f9}
.currency-item__name{display:block;font-size:15px;color:#1e293b}
.currency-item__code{display:block;font-size:13px;color:#94a3b8;margin-top:4px}
.currency-item.is-active{background:#eff6ff;border-color:#bfdbfe}
.currency-item.is-active .currency-item__name{color:#1a56db}
.currency-item__check{display:none;color:#1a56db;font-size:20px;font-weight:700}
.currency-item.is-active .currency-item__check{display:block}
@media (max-width:900px){.currency-modal__grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media (max-width:520px){.currency-modal{padding:20px 16px}.currency-modal__header h3,.currency-modal__title{color:#1e293b;line-height:1.1;font-size:22px}.currency-modal__grid{grid-template-columns:1fr}.currency-btn{padding:6px 8px;font-size:12px}}
</style>

<script>
(function () {
    var RU = {{ $csRu ? 'true' : 'false' }};
    // Task 33: header selector — USD, AZN, RUB, KZT, UZS, EUR, GEL.
    var CURRENCIES = [
        { code: 'USD', ru: 'Доллар США', en: 'US dollar', flag: '🇺🇸', recommended: true },
        { code: 'AZN', ru: 'Азербайджанский манат', en: 'Azerbaijani manat', flag: '🇦🇿', recommended: true },
        { code: 'RUB', ru: 'Российский рубль', en: 'Russian ruble', flag: '🇷🇺', recommended: true },
        { code: 'KZT', ru: 'Казахстанский тенге', en: 'Kazakhstani tenge', flag: '🇰🇿', recommended: true },
        { code: 'UZS', ru: 'Узбекский сум', en: 'Uzbekistani som', flag: '🇺🇿', recommended: true },
        { code: 'EUR', ru: 'Евро', en: 'Euro', flag: '🇪🇺' },
        { code: 'GEL', ru: 'Грузинский лари', en: 'Georgian lari', flag: '🇬🇪' }
    ];
    var SYMBOLS = { USD: '$', EUR: '€', RUB: '₽', AZN: '₼', KZT: '₸', UZS: 'сум', GEL: '₾' };
    var ZERO_DEC = { KZT: 1, UZS: 1, RUB: 1 };
    var COOKIE = 'gs_currency';

    function known(code) { return CURRENCIES.some(function (c) { return c.code === code; }); }
    function readCookie(name) {
        var m = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
        return m ? decodeURIComponent(m[1]) : null;
    }
    function saveChoice(code) {
        try { document.cookie = COOKIE + '=' + code + ';path=/;max-age=' + (365 * 86400) + ';SameSite=Lax'; } catch (e) {}
        try { localStorage.setItem('currency', code); } catch (e) {}
    }
    // Default by visitor country: KZ → KZT, UZ → UZS, RU → RUB, others → USD.
    // Country: server header (if the host/CDN sends one), otherwise the browser time zone.
    function defaultCurrency() {
        var cc = (document.documentElement.getAttribute('data-visitor-country') || '').toUpperCase();
        if (!cc) {
            var tz = '';
            try { tz = Intl.DateTimeFormat().resolvedOptions().timeZone || ''; } catch (e) {}
            if (/^Asia\/(Almaty|Qostanay|Qyzylorda|Aqtobe|Aqtau|Atyrau|Oral)$/.test(tz)) cc = 'KZ';
            else if (/^Asia\/(Tashkent|Samarkand)$/.test(tz)) cc = 'UZ';
            else if (/^(Europe\/(Moscow|Kaliningrad|Samara|Volgograd|Saratov|Ulyanovsk|Astrakhan|Kirov)|Asia\/(Yekaterinburg|Omsk|Novosibirsk|Barnaul|Tomsk|Novokuznetsk|Krasnoyarsk|Irkutsk|Chita|Yakutsk|Khandyga|Vladivostok|Ust-Nera|Magadan|Sakhalin|Srednekolymsk|Kamchatka|Anadyr))$/.test(tz)) cc = 'RU';
        }
        return { KZ: 'KZT', UZ: 'UZS', RU: 'RUB' }[cc] || 'USD';
    }

    var exchangeRates = {};
    var ratesDate = null;
    var selectedCurrency = null;
    var savedChoice = readCookie(COOKIE);
    if (!savedChoice) { try { savedChoice = localStorage.getItem('currency'); } catch (e) {} }
    if (savedChoice && known(savedChoice)) {
        selectedCurrency = savedChoice;
        if (!readCookie(COOKIE)) saveChoice(savedChoice);
    } else {
        selectedCurrency = defaultCurrency();
    }

    function convertPrice(usd, code) {
        if (code === 'USD') return usd;
        if (!exchangeRates[code]) return null;
        return usd * exchangeRates[code];
    }

    // 12 345 ₸ · 1 234 567 сум · 82 000 ₽ · $1 234.50 · €99.00 · 170.00 ₼ · 259.00 ₾
    function formatPrice(amount, code) {
        var dec = ZERO_DEC[code] ? 0 : 2;
        var parts = Number(amount).toFixed(dec).split('.');
        var num = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + (dec ? '.' + parts[1] : '');
        var sym = SYMBOLS[code] || code;
        return (code === 'USD' || code === 'EUR') ? sym + num : num + ' ' + sym;
    }

    var PAY_NOTES = {
        AZ: RU ? 'Оплата в санатории: AZN, USD или картой.' : 'Payment at the sanatorium: AZN, USD or card.',
        GE: RU ? 'Оплата в санатории: GEL, USD или картой.' : 'Payment at the sanatorium: GEL, USD or card.',
        ALL: RU ? 'Оплата в санатории: в Азербайджане — AZN, USD или картой; в Грузии — GEL, USD или картой.'
                : 'Payment at the sanatorium: Azerbaijan — AZN, USD or card; Georgia — GEL, USD or card.'
    };
    function rateNoteText(country) {
        if (!ratesDate) return '';
        var d = ratesDate.split('-');
        var date = d.length === 3 ? d[2] + '.' + d[1] + '.' + d[0] : ratesDate;
        var pay = PAY_NOTES[country] || PAY_NOTES.ALL;
        return (RU ? 'Курс на ' + date + ', ориентировочно. ' : 'Exchange rate as of ' + date + ', approximate. ') + pay;
    }
    function updateRateNotes() {
        document.querySelectorAll('[data-gs-rate-note]').forEach(function (el) {
            var txt = rateNoteText(el.getAttribute('data-pay-country'));
            el.textContent = txt;
            el.hidden = !txt || selectedCurrency === 'AZN' && el.hasAttribute('data-hide-azn');
        });
    }

    function updateAllPrices() {
        document.querySelectorAll('[data-price-usd]').forEach(function (el) {
            var usd = parseFloat(el.dataset.priceUsd);
            if (isNaN(usd)) return;
            var conv = convertPrice(usd, selectedCurrency);
            if (conv === null) return; // rates not loaded yet: keep server text
            el.textContent = formatPrice(conv, selectedCurrency);
        });
        updateRateNotes();
    }

    function updateButton() {
        var cur = CURRENCIES.find(function (c) { return c.code === selectedCurrency; });
        document.querySelectorAll('#currencyFlag').forEach(function (e) { e.textContent = cur ? cur.flag : ''; });
        document.querySelectorAll('#currencyCode').forEach(function (e) { e.textContent = selectedCurrency; });
    }

    function renderGrid(id, list) {
        var box = document.getElementById(id);
        if (!box) return;
        box.innerHTML = '';
        list.forEach(function (c) {
            var b = document.createElement('button');
            b.type = 'button';
            b.className = 'currency-item' + (c.code === selectedCurrency ? ' is-active' : '');
            b.innerHTML = '<span><span class="currency-item__name"></span><span class="currency-item__code"></span></span><span class="currency-item__check">✓</span>';
            b.querySelector('.currency-item__name').textContent = RU ? c.ru : c.en;
            b.querySelector('.currency-item__code').textContent = c.code;
            b.onclick = function () { selectCurrency(c.code); };
            box.appendChild(b);
        });
    }

    function renderModal() {
        renderGrid('recommendedCurrencies', CURRENCIES.filter(function (c) { return c.recommended; }));
        renderGrid('allCurrencies', CURRENCIES.filter(function (c) { return !c.recommended; }));
    }

    function selectCurrency(code) {
        selectedCurrency = code;
        saveChoice(code);
        updateButton();
        renderModal();
        updateAllPrices();
        notifyChange();
        closeCurrencyModal();
    }

    window.openCurrencyModal = function () {
        renderModal();
        document.getElementById('currencyModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    };
    window.closeCurrencyModal = function () {
        document.getElementById('currencyModal').style.display = 'none';
        document.body.style.overflow = '';
    };
    window.updateAllPrices = updateAllPrices;
    window.selectCurrency = selectCurrency;
    // Shared formatter for JS-rendered prices (e.g. booking modal). Returns null until rates are loaded.
    window.gsFormatUsd = function (usd) {
        var conv = convertPrice(parseFloat(usd) || 0, selectedCurrency);
        return conv === null ? null : formatPrice(conv, selectedCurrency);
    };
    window.gsRateNote = rateNoteText;
    window.gsCurrencyCode = function () {
        return (selectedCurrency === 'USD' || exchangeRates[selectedCurrency]) ? selectedCurrency : 'USD';
    };
    function notifyChange() {
        try { document.dispatchEvent(new CustomEvent('gs:currency-change', { detail: { code: selectedCurrency } })); } catch (e) {}
    }

    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') window.closeCurrencyModal(); });

    function loadRates() {
        fetch('/api/exchange-rates', { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                data = data || {};
                ratesDate = data._date || null;
                delete data._date; delete data._source;
                exchangeRates = data;
                updateAllPrices(); notifyChange();
            })
            .catch(function () {});
    }

    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('currencyModal');
        if (modal && modal.parentNode !== document.body) document.body.appendChild(modal);
        updateButton();
        renderModal();
        loadRates();
    });
})();
</script>
@endonce
