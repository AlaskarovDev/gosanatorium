@extends('layout_inner_page')

@section('title')
    <title>{{ $seo_setting->seo_title }}</title>
    <meta name="title" content="{{ $seo_setting->seo_title }}">
    <meta name="description" content="{!! strip_tags(clean($seo_setting->seo_description)) !!}">
@endsection

@section('front-content')

<section class="au-hero">
  <div class="au-hero__inner">
    <div class="au-hero__text">
      <p class="au-hero__label">О нас</p>
      <h1 class="au-hero__title">Подбираем санаторий<br>под ваш диагноз — бесплатно</h1>
      <p class="au-hero__desc">С 2018 года помогаем жителям России, Казахстана, Узбекистана и других стран СНГ найти правильный санаторий в Азербайджане и Грузии. Бронирование без предоплаты, консультация врача бесплатно.</p>
      <a href="https://wa.me/994519158894?text=Здравствуйте!%20Хочу%20забронировать%20санаторий." class="au-hero__btn" target="_blank" rel="noopener">Получить бесплатную консультацию</a>
    </div>
    <div class="au-hero__stats">
      <div class="au-stat"><span class="au-stat__num">50+</span><span class="au-stat__label">партнёрских санаториев</span></div>
      <div class="au-stat"><span class="au-stat__num">8</span><span class="au-stat__label">лет в медицинском туризме</span></div>
      <div class="au-stat"><span class="au-stat__num">1900+</span><span class="au-stat__label">отзывов клиентов</span></div>
      <div class="au-stat"><span class="au-stat__num">15+</span><span class="au-stat__label">специалистов в команде</span></div>
    </div>
  </div>
</section>

<section class="au-section">
  <div class="au-container">
    <div class="au-who">
      <div class="au-who__content">
        <span class="au-section__tag">Наша история</span>
        <h2 class="au-section__title">Как мы появились</h2>
        <p class="au-who__text">GoSanatorium появился в 2018 году — после того, как наши основатели сами прошли лечение в Нафталане. Увидев, как сложно людям выбрать правильный санаторий без медицинских знаний, мы решили это изменить.</p>
        <p class="au-who__text">Сегодня мы помогаем клиентам из России, Казахстана, Узбекистана и других стран СНГ находить санаторий точно под их диагноз — бесплатно, без предоплаты и с поддержкой на каждом шагу.</p>
      </div>
      <div class="au-who__cards">
        <div class="au-value-card">
          <div class="au-value-card__icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
          <div><h3 class="au-value-card__title">Каждый санаторий проверен лично</h3><p class="au-value-card__text">Мы посещаем объекты лично и работаем только с теми, кому доверяем.</p></div>
        </div>
        <div class="au-value-card">
          <div class="au-value-card__icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></div>
          <div><h3 class="au-value-card__title">Врач помогает с выбором</h3><p class="au-value-card__text">Консультирующий врач подбирает санаторий под ваш диагноз — бесплатно.</p></div>
        </div>
        <div class="au-value-card">
          <div class="au-value-card__icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
          <div><h3 class="au-value-card__title">Поддержка 24/7</h3><p class="au-value-card__text">На связи до, во время и после поездки. Ответим в течение 15 минут.</p></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="au-section au-section--gray">
  <div class="au-container">
    <span class="au-section__tag">Что мы делаем</span>
    <h2 class="au-section__title">Всё для правильного лечения — в одном месте</h2>
    <div class="au-services">
      <div class="au-service"><div class="au-service__num">01</div><div class="au-service__body"><h3 class="au-service__title">Подбор санатория под диагноз</h3><p class="au-service__text">Рассказываете о своём диагнозе — мы предлагаем подходящие санатории. Бесплатно, около 15 минут.</p></div></div>
      <div class="au-service"><div class="au-service__num">02</div><div class="au-service__body"><h3 class="au-service__title">Бронирование без предоплаты</h3><p class="au-service__text">Бронируете место — платите только по приезду. Никаких данных карты, никаких скрытых комиссий.</p></div></div>
      <div class="au-service"><div class="au-service__num">03</div><div class="au-service__body"><h3 class="au-service__title">Организация трансфера</h3><p class="au-service__text">Поможем организовать трансфер от аэропорта до санатория — удобно и без лишних хлопот.</p></div></div>
      <div class="au-service"><div class="au-service__num">04</div><div class="au-service__body"><h3 class="au-service__title">Без визы для граждан СНГ</h3><p class="au-service__text">Гражданам России, Казахстана, Узбекистана виза в Азербайджан и Грузию не нужна.</p></div></div>
    </div>
  </div>
</section>

<section class="au-cta">
  <div class="au-container">
    <div class="au-cta__inner">
      <h2 class="au-cta__title">Готовы подобрать санаторий под ваш диагноз?</h2>
      <p class="au-cta__sub">Напишите нам — ответим в течение 15 минут и подберём варианты бесплатно.</p>
      <a href="https://wa.me/994519158894?text=Здравствуйте!%20Хочу%20забронировать%20санаторий." class="au-cta__btn" target="_blank" rel="noopener">Написать в WhatsApp</a>
    </div>
  </div>
</section>

@endsection
