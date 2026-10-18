@extends('layout_inner_page')

@section('title')
    <title>{{ $seo_setting->seo_title }}</title>
    <meta name="title" content="{{ $seo_setting->seo_title }}">
    <meta name="description" content="{!! strip_tags(clean($seo_setting->seo_description)) !!}">
@endsection

@section('front-content')

<section class="au-hero">
  <div class="au-wrap">
    <div class="au-hero__left">
      <span class="au-badge">О компании</span>
      <h1 class="au-h1">Подбираем санаторий<br>под ваш диагноз — бесплатно</h1>
      <p class="au-p">С 2018 года помогаем жителям России, Казахстана, Узбекистана найти правильный санаторий в Азербайджане и Грузии. Без предоплаты, консультация врача бесплатно.</p>
      <a href="https://wa.me/994519158894?text=Здравствуйте!%20Хочу%20забронировать%20санаторий." class="au-btn-w" target="_blank" rel="noopener">Получить консультацию бесплатно</a>
    </div>
    <div class="au-stat-grid">
      <div class="au-stat-box"><span class="au-stat-num">50+</span><span class="au-stat-lbl">партнёрских санаториев</span></div>
      <div class="au-stat-box"><span class="au-stat-num">8</span><span class="au-stat-lbl">лет в медицинском туризме</span></div>
      <div class="au-stat-box"><span class="au-stat-num">1900+</span><span class="au-stat-lbl">отзывов клиентов</span></div>
      <div class="au-stat-box"><span class="au-stat-num">15+</span><span class="au-stat-lbl">специалистов в команде</span></div>
    </div>
  </div>
</section>

<section class="au-sec au-sec--white">
  <div class="au-wrap">
    <div class="au-sec-head">
      <span class="au-tag">Почему нам доверяют</span>
      <h2 class="au-h2">Три причины выбрать GoSanatorium</h2>
    </div>
    <div class="au-cards3">
      <div class="au-win">
        <div class="au-win__icon">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#1a9e87" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <h3 class="au-win__title">Каждый санаторий проверен лично</h3>
        <p class="au-win__text">Мы посещаем каждый объект и работаем только с партнёрами, которым доверяем сами.</p>
      </div>
      <div class="au-win">
        <div class="au-win__icon">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#1a9e87" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <h3 class="au-win__title">Врач помогает с выбором</h3>
        <p class="au-win__text">Консультирующий врач подбирает санаторий под ваш диагноз — бесплатно, около 15 минут.</p>
      </div>
      <div class="au-win">
        <div class="au-win__icon">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#1a9e87" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <h3 class="au-win__title">Поддержка 24/7</h3>
        <p class="au-win__text">На связи до, во время и после поездки. Ответим в течение 15 минут.</p>
      </div>
    </div>
  </div>
</section>

<section class="au-sec au-sec--gray">
  <div class="au-wrap">
    <div class="au-sec-head">
      <span class="au-tag">Наша история</span>
      <h2 class="au-h2">Как появился GoSanatorium</h2>
    </div>
    <div class="au-story-win">
      <p>GoSanatorium появился в 2018 году — после того, как наши основатели сами прошли лечение в Нафталане. Увидев, как сложно людям без медицинских знаний выбрать правильный санаторий, мы решили это изменить.</p>
      <p>Сегодня мы помогаем клиентам из России, Казахстана, Узбекистана и других стран СНГ находить санаторий точно под их диагноз — бесплатно, без предоплаты и с поддержкой на каждом шагу.</p>
    </div>
  </div>
</section>

<section class="au-sec au-sec--white">
  <div class="au-wrap">
    <div class="au-sec-head">
      <span class="au-tag">Что мы делаем</span>
      <h2 class="au-h2">Всё для правильного лечения — в одном месте</h2>
    </div>
    <div class="au-cards4">
      <div class="au-win"><span class="au-win__num">01</span><h3 class="au-win__title">Подбор под диагноз</h3><p class="au-win__text">Рассказываете о диагнозе — предлагаем подходящие санатории. Бесплатно, ~15 минут.</p></div>
      <div class="au-win"><span class="au-win__num">02</span><h3 class="au-win__title">Без предоплаты</h3><p class="au-win__text">Бронируете место — платите по приезду. Без данных карты и скрытых комиссий.</p></div>
      <div class="au-win"><span class="au-win__num">03</span><h3 class="au-win__title">Трансфер</h3><p class="au-win__text">Поможем организовать трансфер от аэропорта прямо до санатория.</p></div>
      <div class="au-win"><span class="au-win__num">04</span><h3 class="au-win__title">Без визы для СНГ</h3><p class="au-win__text">Гражданам России, Казахстана, Узбекистана виза в Азербайджан и Грузию не нужна.</p></div>
    </div>
  </div>
</section>

<section class="au-cta-sec">
  <div class="au-wrap au-cta-flex">
    <div>
      <h2 class="au-cta-h2">Готовы подобрать санаторий под ваш диагноз?</h2>
      <p class="au-cta-p">Ответим в течение 15 минут и подберём варианты бесплатно.</p>
    </div>
    <a href="https://wa.me/994519158894?text=Здравствуйте!%20Хочу%20забронировать%20санаторий." class="au-btn-w" target="_blank" rel="noopener">Написать в WhatsApp</a>
  </div>
</section>

@endsection
