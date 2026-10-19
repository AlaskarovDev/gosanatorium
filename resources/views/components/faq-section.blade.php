{{-- FAQ SECTION --}}
<div class="hfaq-wrap">
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
