<section class="gscurrate-section">
<style>
.gscurrate-section{background:#1a2340!important;padding:40px 20px!important;text-align:center!important}
.gscurrate-title{color:#fff!important;font-size:20px!important;font-weight:700!important;letter-spacing:1px!important;text-transform:uppercase!important;margin:0 0 24px!important}
.gscurrate-input-row{margin-bottom:24px!important}
.gscurrate-input-row label{color:rgba(255,255,255,0.7)!important;font-size:14px!important;display:block!important;margin-bottom:8px!important}
.gscurrate-amount{background:#fff!important;border:none!important;border-radius:8px!important;padding:12px 16px!important;font-size:20px!important;font-weight:700!important;width:180px!important;text-align:center!important;color:#1a2340!important}
.gscurrate-select{background:#fff!important;border:none!important;border-radius:8px!important;padding:12px 16px!important;font-size:16px!important;font-weight:600!important;margin-left:8px!important;color:#1a2340!important;cursor:pointer!important}
.gscurrate-table{max-width:500px!important;margin:0 auto!important}
.gscurrate-row{display:flex!important;align-items:center!important;gap:12px!important;padding:10px 0!important;border-bottom:1px solid rgba(255,255,255,0.08)!important}
.gscurrate-flag{font-size:22px!important;width:28px!important}
.gscurrate-code{color:#fff!important;font-weight:700!important;font-size:15px!important;width:48px!important;text-align:left!important}
.gscurrate-name{color:rgba(255,255,255,0.6)!important;font-size:14px!important;flex:1!important;text-align:left!important}
.gscurrate-val{color:#2ec4a9!important;font-size:18px!important;font-weight:700!important;min-width:80px!important;text-align:right!important}
.gscurrate-updated{color:rgba(255,255,255,0.3)!important;font-size:12px!important;margin-top:16px!important}
</style>
<h3 class="gscurrate-title">Конвертер валют</h3>
<div class="gscurrate-input-row">
  <label for="gcAmount">Введите сумму:</label>
  <input type="number" class="gscurrate-amount" id="gcAmount" value="1" min="0">
  <select class="gscurrate-select" id="gcFrom">
    <option value="AZN">AZN</option>
    <option value="RUB">RUB</option>
    <option value="USD">USD</option>
    <option value="EUR">EUR</option>
    <option value="TRY">TRY</option>
    <option value="KZT">KZT</option>
    <option value="GEL">GEL</option>
  </select>
</div>
<div class="gscurrate-table" id="gcTable"></div>
<div class="gscurrate-updated" id="gcUpdated"></div>
<script>
var gcRates={};
var gcCurrencies=[
  {code:'AZN',flag:'🇦🇿',name:'Азербайджанский манат'},
  {code:'RUB',flag:'🇷🇺',name:'Российский рубль'},
  {code:'USD',flag:'🇺🇸',name:'Доллар США'},
  {code:'EUR',flag:'🇪🇺',name:'Евро'},
  {code:'TRY',flag:'🇹🇷',name:'Турецкая лира'},
  {code:'KZT',flag:'🇰🇿',name:'Казахстанский тенге'},
  {code:'GEL',flag:'🇬🇪',name:'Грузинский лари'}
];
function gcCalc(){
  var amount=parseFloat(document.getElementById('gcAmount').value)||0;
  var from=document.getElementById('gcFrom').value;
  var table=document.getElementById('gcTable');
  if(!gcRates[from]){table.innerHTML='<div style="color:rgba(255,255,255,0.5);padding:20px">Загрузка...</div>';return;}
  table.innerHTML=gcCurrencies.filter(function(c){return c.code!==from;}).map(function(c){
    var result=gcRates[c.code]?(amount/gcRates[from]*gcRates[c.code]).toFixed(2):'—';
    return '<div class="gscurrate-row"><span class="gscurrate-flag">'+c.flag+'</span><span class="gscurrate-code">'+c.code+'</span><span class="gscurrate-name">'+c.name+'</span><span class="gscurrate-val">'+result+'</span></div>';
  }).join('');
}
gcCalc();
fetch('https://open.er-api.com/v6/latest/AZN')
  .then(function(r){return r.json();})
  .then(function(data){
    gcRates=data.rates;
    gcRates['AZN']=1;
    gcCalc();
    var now=new Date();
    document.getElementById('gcUpdated').textContent='Обновлено: '+now.toLocaleString('ru-RU');
  })
  .catch(function(){
    document.getElementById('gcTable').innerHTML='<div style="color:rgba(255,255,255,0.5);padding:20px">Не удалось загрузить курсы</div>';
  });
document.getElementById('gcAmount').addEventListener('input',gcCalc);
document.getElementById('gcFrom').addEventListener('change',gcCalc);
</script>
</section>
