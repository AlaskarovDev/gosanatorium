{{-- FAQ SECTION --}}
<section class="gs-faq">
  <div class="gs-faq__inner">
    <div class="gs-faq__header">
      <span class="gs-faq__label">Часто задают</span>
      <h2 class="gs-faq__title">Ответы на ваши вопросы</h2>
      <p class="gs-faq__desc">Всё, что нужно знать перед бронированием санатория</p>
    </div>
    <div class="gs-faq__grid">
      <div class="gs-faq__item" id="faq1">
        <button class="gs-faq__btn" type="button" onclick="gsFaqToggle('faq1')">
          <span class="gs-faq__num">01</span>
          <span class="gs-faq__q">Нужно ли платить заранее при бронировании?</span>
          <svg class="gs-faq__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="gs-faq__answer"><p>Нет. Мы работаем по принципу <strong>«Бронируй сейчас — плати по прибытии»</strong>. Вы подтверждаете бронирование бесплатно, а оплачиваете уже в санатории при заселении. Никаких предоплат и скрытых комиссий.</p></div>
      </div>
      <div class="gs-faq__item" id="faq2">
        <button class="gs-faq__btn" type="button" onclick="gsFaqToggle('faq2')">
          <span class="gs-faq__num">02</span>
          <span class="gs-faq__q">Как подобрать санаторий под мой диагноз?</span>
          <svg class="gs-faq__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="gs-faq__answer"><p>Напишите нам в WhatsApp или оставьте заявку — наш специалист <strong>бесплатно</strong> подберёт санаторий именно под ваш диагноз. Работаем 24/7, ответим за 15 минут.</p></div>
      </div>
      <div class="gs-faq__item" id="faq3">
        <button class="gs-faq__btn" type="button" onclick="gsFaqToggle('faq3')">
          <span class="gs-faq__num">03</span>
          <span class="gs-faq__q">Что входит в стоимость путёвки?</span>
          <svg class="gs-faq__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="gs-faq__answer"><p>Проживание, питание (завтрак или 3-разовое), <strong>лечебные процедуры и нафталановые ванны</strong>. Трансфер и авиабилеты — по запросу.</p></div>
      </div>
      <div class="gs-faq__item" id="faq4">
        <button class="gs-faq__btn" type="button" onclick="gsFaqToggle('faq4')">
          <span class="gs-faq__num">04</span>
          <span class="gs-faq__q">Можно ли получить консультацию врача до поездки?</span>
          <svg class="gs-faq__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="gs-faq__answer"><p>Да. Наш консультирующий врач <strong>бесплатно</strong> изучит ваши диагнозы и подберёт программу лечения до приезда. Напишите нам — свяжем с врачом.</p></div>
      </div>
      <div class="gs-faq__item" id="faq5">
        <button class="gs-faq__btn" type="button" onclick="gsFaqToggle('faq5')">
          <span class="gs-faq__num">05</span>
          <span class="gs-faq__q">Вы организуете трансфер из аэропорта?</span>
          <svg class="gs-faq__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="gs-faq__answer"><p>Да. Организуем <strong>трансфер из аэропорта Баку или Гянджи</strong> до санатория в Нафталане. Также помогаем с авиабилетами.</p></div>
      </div>
      <div class="gs-faq__item" id="faq6">
        <button class="gs-faq__btn" type="button" onclick="gsFaqToggle('faq6')">
          <span class="gs-faq__num">06</span>
          <span class="gs-faq__q">Какой минимальный срок лечения?</span>
          <svg class="gs-faq__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="gs-faq__answer"><p>Минимальный срок — <strong>7 ночей</strong>. Для стойкого эффекта врачи рекомендуют <strong>10–14 дней</strong>. При артрозе, псориазе и грыжах — не менее 10 дней.</p></div>
      </div>
    </div>
    <div class="gs-faq__footer">
      <p>Остались вопросы? Пишите — ответим за 15 минут</p>
      <a href="https://wa.me/994519158894?text=Здравствуйте!%20У%20меня%20есть%20вопрос." class="gs-faq__wa" target="_blank" rel="noopener">Написать в WhatsApp</a>
    </div>
  </div>
</section>
<script>
function gsFaqToggle(id){var item=document.getElementById(id);var isOpen=item.classList.contains('gs-faq__item--open');document.querySelectorAll('.gs-faq__item').forEach(function(el){el.classList.remove('gs-faq__item--open');});if(!isOpen){item.classList.add('gs-faq__item--open');}}
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
