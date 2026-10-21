<style>
.gscurrate-section{background:#1a2340!important;padding:48px 20px!important}
.gscurrate-container{max-width:600px!important;margin:0 auto!important}
.gscurrate-title{color:#fff!important;font-size:22px!important;font-weight:700!important;text-align:center!important;margin:0 0 24px!important;letter-spacing:1px!important;text-transform:uppercase!important}
.gscurrate-row{display:flex!important;align-items:center!important;gap:12px!important;padding:12px 0!important;border-bottom:1px solid rgba(255,255,255,0.08)!important}
.gscurrate-flag{font-size:22px!important;width:28px!important}
.gscurrate-code{color:#fff!important;font-weight:700!important;font-size:15px!important;width:48px!important}
.gscurrate-name{color:rgba(255,255,255,0.6)!important;font-size:14px!important;flex:1!important}
.gscurrate-val{color:#2ec4a9!important;font-size:18px!important;font-weight:700!important;min-width:60px!important;text-align:right!important}
.gscurrate-loading{color:rgba(255,255,255,0.5)!important;text-align:center!important;padding:20px!important}
.gscurrate-updated{color:rgba(255,255,255,0.35)!important;font-size:12px!important;text-align:center!important;margin-top:16px!important}
</style>
<section class="gscurrate-section">
  <div class="gscurrate-container">
    <h3 class="gscurrate-title">Курс валют</h3>
    <div class="gscurrate-table" id="gscurrate-table">
      <div class="gscurrate-loading">Загрузка...</div>
    </div>
    <div class="gscurrate-updated" id="gscurrate-updated"></div>
  </div>
</section>
<script>
fetch('https://open.er-api.com/v6/latest/AZN')
  .then(r => r.json())
  .then(data => {
    const rates = data.rates;
    const currencies = [
      {code:'RUB', flag:'🇷🇺', name:'Российский рубль'},
      {code:'USD', flag:'🇺🇸', name:'Доллар США'},
      {code:'EUR', flag:'🇪🇺', name:'Евро'},
      {code:'TRY', flag:'🇹🇷', name:'Турецкая лира'},
      {code:'KZT', flag:'🇰🇿', name:'Казахстанский тенге'},
      {code:'GEL', flag:'🇬🇪', name:'Грузинский лари'},
    ];
    const table = document.getElementById('gscurrate-table');
    table.innerHTML = currencies.map(c => {
      const val = rates[c.code] ? rates[c.code].toFixed(2) : '—';
      return `<div class="gscurrate-row"><span class="gscurrate-flag">${c.flag}</span><span class="gscurrate-code">${c.code}</span><span class="gscurrate-name">${c.name}</span><span class="gscurrate-val">${val}</span></div>`;
    }).join('');
    document.getElementById('gscurrate-updated').textContent = 'Обновлено: ' + new Date().toLocaleString('ru-RU');
  })
  .catch(() => {
    document.getElementById('gscurrate-table').innerHTML = '<div class="gscurrate-loading">Не удалось загрузить курсы</div>';
  });
</script>
