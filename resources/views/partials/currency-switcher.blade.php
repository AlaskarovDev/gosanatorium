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
            <h3>{{ $csRu ? 'Выберите валюту' : 'Select currency' }}</h3>
            <button type="button" class="currency-modal__close" onclick="closeCurrencyModal()" aria-label="Close">✕</button>
        </div>
        <p class="currency-modal__note">
            {{ $csRu
                ? 'Цены отображаются в выбранной валюте. Пересчёт ориентировочный; валюта и условия оплаты указываются при подтверждении бронирования.'
                : 'Prices are shown in the selected currency. Conversion is approximate; the currency and payment terms are confirmed when the booking is confirmed.' }}
        </p>
        <div class="currency-modal__section-title">{{ $csRu ? 'Рекомендовано для вас' : 'Recommended for you' }}</div>
        <div class="currency-modal__grid" id="recommendedCurrencies"></div>
        <div class="currency-modal__section-title">{{ $csRu ? 'Все валюты' : 'All currencies' }}</div>
        <div class="currency-modal__grid" id="allCurrencies"></div>
    </div>
</div>

<style>
.currency-btn{display:inline-flex;align-items:center;gap:6px;background:#1a56db;border:2px solid #1a56db;border-radius:8px;padding:8px 12px;font-size:14px;font-weight:600;font-family:'Poppins',sans-serif;color:#fff;cursor:pointer;line-height:1.2;white-space:nowrap;transition:all .3s ease}
.currency-btn:hover,.currency-btn:focus{background:#1648c0;border-color:#1648c0;outline:none}
.currency-modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.6);z-index:100000;align-items:flex-start;justify-content:center;padding:40px 16px;overflow-y:auto}
.currency-modal{background:#fff;border-radius:20px;width:100%;max-width:900px;padding:28px 32px 32px;box-shadow:0 20px 60px rgba(0,0,0,.3);color:#1e293b;font-family:'Poppins',sans-serif}
.currency-modal__header{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px}
.currency-modal__header h3{margin:0;font-size:28px;font-weight:700}
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
@media (max-width:520px){.currency-modal{padding:20px 16px}.currency-modal__header h3{font-size:22px}.currency-modal__grid{grid-template-columns:1fr}.currency-btn{padding:6px 8px;font-size:12px}}
</style>

<script>
(function () {
    var RU = {{ $csRu ? 'true' : 'false' }};
    var CURRENCIES = [
        { code: 'AZN', ru: 'Азербайджанский манат', en: 'Azerbaijani manat', flag: '🇦🇿', recommended: true },
        { code: 'RUB', ru: 'Российский рубль', en: 'Russian ruble', flag: '🇷🇺', recommended: true },
        { code: 'USD', ru: 'Доллар США', en: 'US dollar', flag: '🇺🇸', recommended: true },
        { code: 'EUR', ru: 'Евро', en: 'Euro', flag: '🇪🇺', recommended: true },
        { code: 'KZT', ru: 'Казахстанский тенге', en: 'Kazakhstani tenge', flag: '🇰🇿', recommended: true },
        { code: 'GEL', ru: 'Грузинский лари', en: 'Georgian lari', flag: '🇬🇪' },
        { code: 'UZS', ru: 'Узбекский сум', en: 'Uzbekistani som', flag: '🇺🇿' },
        { code: 'BYN', ru: 'Белорусский рубль', en: 'Belarusian ruble', flag: '🇧🇾' },
        { code: 'KGS', ru: 'Кыргызский сом', en: 'Kyrgyzstani som', flag: '🇰🇬' },
        { code: 'TJS', ru: 'Таджикский сомони', en: 'Tajikistani somoni', flag: '🇹🇯' },
        { code: 'TRY', ru: 'Турецкая лира', en: 'Turkish lira', flag: '🇹🇷' },
        { code: 'AED', ru: 'Дирхам ОАЭ', en: 'UAE dirham', flag: '🇦🇪' },
        { code: 'ILS', ru: 'Израильский шекель', en: 'Israeli shekel', flag: '🇮🇱' },
        { code: 'CNY', ru: 'Китайский юань', en: 'Chinese yuan', flag: '🇨🇳' }
    ];
    var SYMBOLS = { USD: '$', EUR: '€', RUB: '₽', AZN: '₼', KZT: '₸', GEL: '₾', TRY: '₺', AED: 'د.إ', ILS: '₪', CNY: '¥' };

    var exchangeRates = {};
    var selectedCurrency = 'USD';
    try {
        var saved = localStorage.getItem('currency');
        if (saved && CURRENCIES.some(function (c) { return c.code === saved; })) selectedCurrency = saved;
    } catch (e) {}

    function convertPrice(usd, code) {
        if (code === 'USD') return usd;
        if (!exchangeRates[code]) return null;
        return usd * exchangeRates[code];
    }

    function formatPrice(amount, code) {
        var twoDec = (code === 'USD' || code === 'EUR');
        var num = Number(amount).toLocaleString(code === 'USD' ? 'en-US' : 'ru-RU', {
            minimumFractionDigits: twoDec ? 2 : 0, maximumFractionDigits: twoDec ? 2 : 0
        });
        var sym = SYMBOLS[code];
        return sym ? (code === 'USD' || code === 'EUR' ? sym + num : sym + ' ' + num) : num + ' ' + code;
    }

    function updateAllPrices() {
        document.querySelectorAll('[data-price-usd]').forEach(function (el) {
            var usd = parseFloat(el.dataset.priceUsd);
            if (isNaN(usd)) return;
            var conv = convertPrice(usd, selectedCurrency);
            if (conv === null) return; // rates not loaded yet: keep server text
            el.textContent = formatPrice(conv, selectedCurrency);
        });
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
        renderGrid('allCurrencies', CURRENCIES);
    }

    function selectCurrency(code) {
        selectedCurrency = code;
        try { localStorage.setItem('currency', code); } catch (e) {}
        updateButton();
        renderModal();
        updateAllPrices();
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

    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') window.closeCurrencyModal(); });

    function loadRates() {
        fetch('/api/exchange-rates', { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (data) { exchangeRates = data || {}; updateAllPrices(); })
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
