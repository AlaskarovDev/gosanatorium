{{-- HOW TO BOOK SECTION --}}
<style>
.hbook-wrap{background:linear-gradient(135deg,#1a9e87 0%,#2ec4a9 50%,#17b09a 100%);padding:72px 0;width:100%}
.hbook-container{max-width:1000px;margin:0 auto;padding:0 32px;box-sizing:border-box}
.hbook-head{text-align:center;margin-bottom:52px}
.hbook-label{display:inline-block;font-size:11px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:#fff;background:rgba(255,255,255,.2);padding:5px 16px;border-radius:50px;margin-bottom:14px}
.hbook-title{font-size:34px;font-weight:900;color:#fff;margin:0 0 12px;line-height:1.2}
.hbook-desc{font-size:16px;color:rgba(255,255,255,.85);margin:0}
.hbook-steps{display:flex;gap:24px;align-items:stretch}
.hbook-step{flex:1;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);border-radius:16px;padding:32px 24px;text-align:center;position:relative;backdrop-filter:blur(4px)}
.hbook-step__num{width:52px;height:52px;border-radius:50%;background:#fff;color:#1a9e87;font-size:22px;font-weight:900;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;box-shadow:0 4px 14px rgba(0,0,0,.12)}
.hbook-step__icon{font-size:28px;margin-bottom:14px;display:block}
.hbook-step__title{font-size:17px;font-weight:800;color:#fff;margin:0 0 10px;line-height:1.3}
.hbook-step__text{font-size:14px;color:rgba(255,255,255,.82);line-height:1.7;margin:0}
.hbook-arrow{font-size:28px;color:rgba(255,255,255,.4);align-self:center;flex-shrink:0;margin-top:-20px}
.hbook-cta{text-align:center;margin-top:44px}
.hbook-cta__btn{display:inline-flex;align-items:center;gap:10px;background:#fff;color:#1a9e87!important;font-size:16px;font-weight:800;padding:16px 36px;border-radius:50px;text-decoration:none!important;transition:transform .2s,box-shadow .2s;box-shadow:0 6px 20px rgba(0,0,0,.15)}
.hbook-cta__btn:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(0,0,0,.2)}
.hbook-cta__sub{margin-top:14px;font-size:13px;color:rgba(255,255,255,.7)}
@media(max-width:768px){
  .hbook-wrap{padding:52px 0}
  .hbook-container{padding:0 20px}
  .hbook-title{font-size:26px}
  .hbook-steps{flex-direction:column;gap:16px}
  .hbook-arrow{transform:rotate(90deg);margin:0 auto;display:block;text-align:center}
  .hbook-step{padding:24px 20px}
}
</style>
<div id="section-howtobook" class="hbook-wrap">
  <div class="hbook-container">
    <div class="hbook-head">
      <span class="hbook-label">Просто и быстро</span>
      <h2 class="hbook-title">Как забронировать санаторий?</h2>
      <p class="hbook-desc">Всего 3 шага — и ваш отдых и лечение подтверждены</p>
    </div>
    <div class="hbook-steps">

      <div class="hbook-step">
        <div class="hbook-step__num">1</div>
        <span class="hbook-step__icon">💬</span>
        <h3 class="hbook-step__title">Напишите нам</h3>
        <p class="hbook-step__text">Напишите в WhatsApp или оставьте заявку на сайте. Укажите ваш диагноз, даты и бюджет. Консультация бесплатна.</p>
      </div>

      <div class="hbook-arrow">›</div>

      <div class="hbook-step">
        <div class="hbook-step__num">2</div>
        <span class="hbook-step__icon">🏥</span>
        <h3 class="hbook-step__title">Получите подборку</h3>
        <p class="hbook-step__text">Наш специалист подберёт санатории под ваш диагноз и пришлёт варианты с ценами в течение 15 минут.</p>
      </div>

      <div class="hbook-arrow">›</div>

      <div class="hbook-step">
        <div class="hbook-step__num">3</div>
        <span class="hbook-step__icon">✅</span>
        <h3 class="hbook-step__title">Подтвердите бронь</h3>
        <p class="hbook-step__text">Выберите санаторий, мы подтвердим бронирование. Оплата — только по прибытии. Никакой предоплаты.</p>
      </div>

    </div>
    <div class="hbook-cta">
      <a href="https://wa.me/994519158894?text=Здравствуйте!%20Хочу%20забронировать%20санаторий." class="hbook-cta__btn" target="_blank" rel="noopener">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.118 1.524 5.855L0 24l6.335-1.499A11.955 11.955 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.891 0-3.667-.497-5.207-1.367l-.374-.217-3.762.89.944-3.659-.243-.386A9.934 9.934 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
        Забронировать за 15 минут
      </a>
      <p class="hbook-cta__sub">Бесплатная консультация · Без предоплаты · Работаем 24/7</p>
    </div>
  </div>
</div>
