@extends('layout_inner_page')

@section('title')
    <title>{{ $seo_setting->seo_title }}</title>
    <meta name="title" content="{{ $seo_setting->seo_title }}">
    <meta name="description" content="{!! strip_tags(clean($seo_setting->seo_description)) !!}">
@endsection

@section('front-content')

{{-- HERO --}}
<section class="au-hero">
  <div class="au-wrap">
    <div class="au-hero__left">
      <span class="au-badge">О компании</span>
      <h1 class="au-hero__h1">Подбираем санаторий<br>под ваш диагноз — бесплатно</h1>
      <p class="au-hero__p">С 2018 года помогаем жителям России, Казахстана, Узбекистана и стран СНГ найти правильный санаторий в Азербайджане и Грузии. Без предоплаты, консультация врача бесплатно.</p>
      <a href="https://wa.me/994519158894?text=Здравствуйте!%20Хочу%20забронировать%20санаторий." class="au-btn-white" target="_blank" rel="noopener">Получить консультацию бесплатно</a>
    </div>
    <div class="au-hero__right">
      <div class="au-stat-grid">
        <div class="au-stat-box"><span class="au-stat-num">50+</span><span class="au-stat-lbl">партнёрских<br>санаториев</span></div>
        <div class="au-stat-box"><span class="au-stat-num">8</span><span class="au-stat-lbl">лет в медицинском<br>туризме</span></div>
        <div class="au-stat-box"><span class="au-stat-num">1900+</span><span class="au-stat-lbl">отзывов<br>клиентов</span></div>
        <div class="au-stat-box"><span class="au-stat-num">15+</span><span class="au-stat-lbl">специалистов<br>в команде</span></div>
      </div>
    </div>
  </div>
</section>

{{-- STORY --}}
<section class="au-sec au-sec--white">
  <div class="au-wrap au-two-col">
    <div>
      <span class="au-badge au-badge--teal">Наша история</span>
      <h2 class="au-h2">Как появился GoSanatorium</h2>
      <p class="au-p">GoSanatorium появился в 2018 году — после того, как наши основатели сами прошли лечение в Нафталане. Увидев, как сложно людям без медицинских знаний выбрать правильный санаторий, мы решили это изменить.</p>
      <p class="au-p">Сегодня мы помогаем клиентам из России, Казахстана, Узбекистана и других стран СНГ находить санаторий под их диагноз — бесплатно, без предоплаты и с поддержкой на каждом шагу.</p>
    </div>
    <div class="au-trust-list">
      <div class="au-trust-item">
        <div class="au-trust-icon">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div><strong>Каждый санаторий проверен лично</strong><p>Мы посещаем объекты и работаем только с проверенными партнёрами.</p></div>
      </div>
      <div class="au-trust-item">
        <div class="au-trust-icon">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <div><strong>Врач помогает с подбором</strong><p>Консультирующий врач подбирает санаторий под ваш диагноз бесплатно.</p></div>
      </div>
      <div class="au-trust-item">
        <div class="au-trust-icon">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <div><strong>Поддержка 24/7</strong><p>На связи до, во время и после поездки. Ответим за 15 минут.</p></div>
      </div>
    </div>
  </div>
</section>

{{-- SERVICES --}}
<section class="au-sec au-sec--gray">
  <div class="au-wrap">
    <div class="au-sec-head">
      <span class="au-badge au-badge--teal">Что мы делаем</span>
      <h2 class="au-h2">Всё для правильного лечения — в одном месте</h2>
    </div>
    <div class="au-cards4">
      <div class="au-card"><span class="au-card-num">01</span><h3>Подбор под диагноз</h3><p>Рассказываете о диагнозе — мы предлагаем санатории именно для вашего случая. Бесплатно, ~15 минут.</p></div>
      <div class="au-card"><span class="au-card-num">02</span><h3>Без предоплаты</h3><p>Бронируете место — платите по приезду. Никаких данных карты и скрытых комиссий.</p></div>
      <div class="au-card"><span class="au-card-num">03</span><h3>Организация трансфера</h3><p>Поможем организовать трансфер от аэропорта прямо до санатория.</p></div>
      <div class="au-card"><span class="au-card-num">04</span><h3>Без визы для СНГ</h3><p>Гражданам России, Казахстана, Узбекистана виза в Азербайджан и Грузию не нужна.</p></div>
    </div>
  </div>
</section>

{{-- CTA --}}
<section class="au-cta-sec">
  <div class="au-wrap au-cta-inner">
    <div>
      <h2 class="au-cta-h2">Готовы подобрать санаторий под ваш диагноз?</h2>
      <p class="au-cta-p">Напишите нам — ответим в течение 15 минут и подберём варианты бесплатно.</p>
    </div>
    <a href="https://wa.me/994519158894?text=Здравствуйте!%20Хочу%20забронировать%20санаторий." class="au-btn-white" target="_blank" rel="noopener">Написать в WhatsApp</a>
  </div>
</section>

@endsection
