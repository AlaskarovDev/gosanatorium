@extends('layout_inner_page')

@section('title')
    <title>{{ $seo_setting->seo_title }}</title>
    <meta name="title" content="{{ $seo_setting->seo_title }}">
    <meta name="description" content="{!! strip_tags(clean($seo_setting->seo_description)) !!}">
@endsection

@section('front-content')

{{-- HERO --}}
<div class="ab-hero">
  <div class="ab-hero__inner">
    <h1 class="ab-hero__title">О нас</h1>
    <p class="ab-hero__sub">GoSanatorium — бесплатный сервис подбора санаториев для жителей СНГ</p>
  </div>
</div>

{{-- MIDDLE: boxes left + center text + boxes right --}}
<div class="ab-mid">
  <div class="ab-mid__wrap">

    {{-- LEFT BOXES --}}
    <div class="ab-col ab-col--left">
      <div class="ab-box">
        <div class="ab-box__icon">🕐</div>
        <h3 class="ab-box__title">Работаем 24/7</h3>
        <p class="ab-box__text">На связи до, во время и после поездки. Ответим за 15 минут в любое время.</p>
      </div>
      <div class="ab-box">
        <div class="ab-box__icon">🩺</div>
        <h3 class="ab-box__title">Врач бесплатно</h3>
        <p class="ab-box__text">Консультирующий врач подбирает санаторий точно под ваш диагноз.</p>
      </div>
      <div class="ab-box">
        <div class="ab-box__icon">✅</div>
        <h3 class="ab-box__title">Без предоплаты</h3>
        <p class="ab-box__text">Бронируете бесплатно — платите только по прибытии в санаторий.</p>
      </div>
    </div>

    {{-- CENTER TEXT --}}
    <div class="ab-col ab-col--center">
      <span class="ab-tag">Кто мы</span>
      <h2 class="ab-center__title">Помогаем найти<br>правильный санаторий</h2>
      <p class="ab-center__text">GoSanatorium появился в 2018 году после того, как наши основатели сами прошли лечение в Нафталане. Мы увидели, как сложно людям без медицинских знаний выбрать подходящий санаторий — и решили это изменить.</p>
      <p class="ab-center__text">Сегодня мы помогаем клиентам из России, Казахстана, Узбекистана и других стран СНГ находить санаторий под их диагноз — бесплатно и с поддержкой на каждом шагу.</p>
      <div class="ab-stats">
        <div class="ab-stat"><span class="ab-stat__n">50+</span><span class="ab-stat__l">санаториев</span></div>
        <div class="ab-stat"><span class="ab-stat__n">8 лет</span><span class="ab-stat__l">на рынке</span></div>
        <div class="ab-stat"><span class="ab-stat__n">1900+</span><span class="ab-stat__l">отзывов</span></div>
      </div>
    </div>

    {{-- RIGHT BOXES --}}
    <div class="ab-col ab-col--right">
      <div class="ab-box">
        <div class="ab-box__icon">🌍</div>
        <h3 class="ab-box__title">Страны</h3>
        <p class="ab-box__text">Работаем с Россией, Казахстаном, Узбекистаном, Беларусью и другими странами СНГ.</p>
      </div>
      <div class="ab-box">
        <div class="ab-box__icon">🏥</div>
        <h3 class="ab-box__title">Наши курорты</h3>
        <p class="ab-box__text">Нафталан (Азербайджан), Боржоми, Цхалтубо, Саирме (Грузия).</p>
      </div>
      <div class="ab-box">
        <div class="ab-box__icon">🚗</div>
        <h3 class="ab-box__title">Трансфер</h3>
        <p class="ab-box__text">Организуем трансфер от аэропорта до санатория — удобно и без хлопот.</p>
      </div>
    </div>

  </div>
</div>

{{-- CTA --}}
<div class="ab-cta">
  <div class="ab-cta__wrap">
    <h2 class="ab-cta__title">Готовы подобрать санаторий под ваш диагноз?</h2>
    <p class="ab-cta__sub">Ответим в течение 15 минут — бесплатно.</p>
    <a href="https://wa.me/994519158894?text=Здравствуйте!%20Хочу%20забронировать%20санаторий." class="ab-cta__btn" target="_blank" rel="noopener">Написать в WhatsApp</a>
  </div>
</div>

@endsection
