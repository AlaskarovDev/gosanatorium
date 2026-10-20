{{-- FAQ SECTION --}}
<style>
.hfaq-wrap{background:#ffffff;padding:60px 0 100px!important;width:100%;display:block!important}
.hfaq-container{max-width:860px;margin:0 auto;padding:0 32px!important;box-sizing:border-box;display:block!important}
.hfaq-head{text-align:center;margin-bottom:40px}
.hfaq-subtitle{font-size:14px;color:#6a4fc8;font-style:italic;margin:0 0 8px;font-weight:500;display:block}
.hfaq-title{font-size:30px;font-weight:800;color:#1a1a2e;margin:0 0 0 0;display:block}
.hfaq-list{display:flex!important;flex-direction:column;gap:12px;padding:0;margin:0;list-style:none}
.hfaq-item{background:#ffffff;border:1px solid #e5e7eb!important;border-radius:10px!important;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.06)!important;display:block!important}
.hfaq-question{width:100%!important;display:flex!important;justify-content:space-between!important;align-items:center!important;padding:20px 24px!important;background:#ffffff!important;border:none!important;border-radius:0!important;cursor:pointer;font-size:16px!important;font-weight:700!important;color:#111827!important;text-align:left!important;gap:12px;box-sizing:border-box;outline:none!important;box-shadow:none!important;letter-spacing:-.01em;line-height:1.4!important}
.hfaq-question:hover{background:#f9fafb!important}
.hfaq-icon{width:20px!important;height:20px!important;min-width:20px!important;max-width:20px!important;color:#6b7280!important;transition:transform .3s;flex-shrink:0;display:inline-block!important}
.hfaq-answer{max-height:0!important;overflow:hidden!important;transition:max-height .35s ease;display:block!important}
.hfaq-answer p{padding:14px 24px 20px!important;font-size:15px!important;color:#374151!important;line-height:1.8!important;margin:0!important;border-top:1px solid #f0f0f0!important;display:block!important}
.hfaq-item--open .hfaq-answer{max-height:400px!important}
.hfaq-item--open .hfaq-icon{transform:rotate(180deg)!important}
.hfaq-item--open .hfaq-question{color:#1a9e87!important}
@media(max-width:768px){
  .hfaq-wrap{padding:36px 0!important}
  .hfaq-container{padding:0 16px!important}
  .hfaq-title{font-size:22px!important}
  .hfaq-question{font-size:14px!important;padding:14px 16px!important}
  .hfaq-icon{width:18px!important;height:18px!important;min-width:18px!important;max-width:18px!important}
  .hfaq-answer p{padding:10px 16px 14px!important;font-size:13px!important}
}
</style>
<div id="section-faq" class="hfaq-wrap">
  <div class="hfaq-container">
    <div class="hfaq-head">
      <p class="hfaq-subtitle">У Вас Есть Вопросы? Мы Собрали Ответы</p>
      <h2 class="hfaq-title">Часто Задаваемые Вопросы</h2>
    </div>
    <div class="hfaq-list">

      <div class="hfaq-item">
        <button class="hfaq-question" type="button" onclick="hfaqOpen(this)">
          Нужно ли платить заранее при бронировании?
          <svg class="hfaq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="hfaq-answer">
          <p>Нет. Мы работаем по принципу <strong>«Бронируй сейчас — плати по прибытии»</strong>. Вы подтверждаете бронирование бесплатно, а оплачиваете уже в санатории при заселении. Никаких предоплат.</p>
        </div>
      </div>

      <div class="hfaq-item">
        <button class="hfaq-question" type="button" onclick="hfaqOpen(this)">
          Как подобрать санаторий под мой диагноз?
          <svg class="hfaq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="hfaq-answer">
          <p>Напишите нам в WhatsApp или оставьте заявку — наш специалист <strong>бесплатно</strong> подберёт санаторий именно под ваш диагноз. Работаем 24/7, ответим за 15 минут.</p>
        </div>
      </div>

      <div class="hfaq-item">
        <button class="hfaq-question" type="button" onclick="hfaqOpen(this)">
          Что входит в стоимость путёвки?
          <svg class="hfaq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="hfaq-answer">
          <p>Проживание, питание (завтрак или 3-разовое), <strong>лечебные процедуры и нафталановые ванны</strong>. Трансфер и авиабилеты — по запросу, помогаем организовать.</p>
        </div>
      </div>

      <div class="hfaq-item">
        <button class="hfaq-question" type="button" onclick="hfaqOpen(this)">
          Можно ли получить консультацию врача до поездки?
          <svg class="hfaq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="hfaq-answer">
          <p>Да. Наш консультирующий врач <strong>бесплатно</strong> изучит ваши диагнозы и подберёт программу лечения до приезда. Напишите нам — свяжем с врачом.</p>
        </div>
      </div>

      <div class="hfaq-item">
        <button class="hfaq-question" type="button" onclick="hfaqOpen(this)">
          Вы организуете трансфер из аэропорта?
          <svg class="hfaq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="hfaq-answer">
          <p>Да. Организуем <strong>трансфер из аэропорта Баку или Гянджи</strong> до санатория в Нафталане. Также помогаем с поиском авиабилетов.</p>
        </div>
      </div>

      <div class="hfaq-item">
        <button class="hfaq-question" type="button" onclick="hfaqOpen(this)">
          Какой минимальный срок лечения?
          <svg class="hfaq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="hfaq-answer">
          <p>Минимальный срок — <strong>7 ночей</strong>. Для стойкого эффекта рекомендуется <strong>10–14 дней</strong>. При артрозе, псориазе и грыжах — не менее 10 дней.</p>
        </div>
      </div>

    </div>
    <div style="height:40px"></div>
  </div>
</div>
<script>
function hfaqOpen(btn){var item=btn.parentElement;var isOpen=item.classList.contains('hfaq-item--open');document.querySelectorAll('.hfaq-item').forEach(function(el){el.classList.remove('hfaq-item--open');});if(!isOpen){item.classList.add('hfaq-item--open');}}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Нужно ли платить заранее при бронировании?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Нет. Мы работаем по принципу «Бронируй сейчас — плати по прибытии». Вы подтверждаете бронирование бесплатно, а оплачиваете уже в санатории при заселении."
      }
    },
    {
      "@type": "Question",
      "name": "Как подобрать санаторий под мой диагноз?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Напишите нам в WhatsApp или оставьте заявку — наш специалист бесплатно подберёт санаторий именно под ваш диагноз. Работаем 24/7, ответим за 15 минут."
      }
    },
    {
      "@type": "Question",
      "name": "Что входит в стоимость путёвки?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "В зависимости от пакета: проживание, питание (завтрак или 3-разовое), лечебные процедуры и нафталановые ванны. Трансфер и авиабилеты — отдельно."
      }
    },
    {
      "@type": "Question",
      "name": "Можно ли получить консультацию врача до поездки?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Да. Наш консультирующий врач бесплатно изучит ваши диагнозы и подберёт программу лечения до приезда."
      }
    },
    {
      "@type": "Question",
      "name": "Вы организуете трансфер из аэропорта?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Да. Организуем трансфер из аэропорта Баку или Гянджи до санатория в Нафталане."
      }
    },
    {
      "@type": "Question",
      "name": "Какой минимальный срок лечения?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Минимальный срок — 7 ночей. Для стойкого эффекта рекомендуется 10–14 дней."
      }
    }
  ]
}
</script>
