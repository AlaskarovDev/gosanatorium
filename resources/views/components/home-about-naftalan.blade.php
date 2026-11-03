<section class="gs-aboutnaf" aria-labelledby="gsAboutNafTitle">
    <div class="container">
        <div class="gs-aboutnaf__box">
            <h2 class="gs-aboutnaf__title" id="gsAboutNafTitle">Нафталан — уникальный курорт в Азербайджане</h2>
            <button type="button" class="gs-aboutnaf__toggle" id="gsAboutNafToggle" aria-expanded="false" aria-controls="gsAboutNafBody">
                <span>Читать о Нафталане</span> <span class="gs-aboutnaf__arrow">▾</span>
            </button>
            <div class="gs-aboutnaf__body" id="gsAboutNafBody">
                <p class="gs-aboutnaf__text">Нафталанская нефть — единственная в мире, признанная официально лечебной. Санатории Нафталана более 100 лет принимают пациентов с заболеваниями суставов, кожи, нервной и гинекологической систем. Лечение проходит под наблюдением врачей, в комфортных условиях и без предоплаты.</p>
                <ul class="gs-aboutnaf__chips">
                    <li>🏥 16 санаториев</li>
                    <li>👨‍⚕️ Лечение под наблюдением врачей</li>
                    <li>✓ Без предоплаты</li>
                </ul>
                <a class="gs-aboutnaf__link" href="{{ url('/ru/o-naftalane') }}">Читать подробнее →</a>
            </div>
        </div>
    </div>
</section>
<style>
.gs-aboutnaf{background:#f3f6fa;padding:22px 0;text-align:center}
.gs-aboutnaf__box{max-width:860px;margin:0 auto}
.gs-aboutnaf__title{font-size:20px;font-weight:600;color:#33415c;margin:0 0 10px}
.gs-aboutnaf__toggle{display:none;background:none;border:0;color:#4a6fa5;font-size:14px;cursor:pointer;padding:4px 0}
.gs-aboutnaf__text{font-size:15px;line-height:1.6;color:#4a5568;margin:0 0 14px}
.gs-aboutnaf__chips{list-style:none;display:flex;flex-wrap:wrap;justify-content:center;gap:8px;margin:0 0 12px;padding:0}
.gs-aboutnaf__chips li{background:#fff;border:1px solid #dbe3ee;border-radius:999px;padding:5px 14px;font-size:14px;color:#4a5568}
.gs-aboutnaf__link{font-size:14px;color:#4a6fa5;text-decoration:none}
.gs-aboutnaf__link:hover{text-decoration:underline}
@media (max-width:767px){
.gs-aboutnaf{padding:14px 0}
.gs-aboutnaf__title{font-size:17px;margin-bottom:4px}
.gs-aboutnaf__toggle{display:inline-block}
.gs-aboutnaf__body{display:none;margin-top:8px}
.gs-aboutnaf.is-open .gs-aboutnaf__body{display:block}
.gs-aboutnaf.is-open .gs-aboutnaf__arrow{display:inline-block;transform:rotate(180deg)}
}
</style>
<script>
document.getElementById('gsAboutNafToggle').addEventListener('click',function(){
    var s=this.closest('.gs-aboutnaf');var o=s.classList.toggle('is-open');
    this.setAttribute('aria-expanded',o?'true':'false');
});
</script>
