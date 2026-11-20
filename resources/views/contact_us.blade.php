@extends('layout_inner_page')

@section('title')
    <title>{{ $seo_setting->seo_title }}</title>
    <meta name="title" content="{{ $seo_setting->seo_title }}">
    <meta name="description" content="{!! strip_tags(clean(company_text($seo_setting->seo_description))) !!}">
@endsection

@section('front-content')

{{-- HERO --}}
<div class="ct-hero">
  <div class="ct-hero__inner">
    <h1 class="ct-hero__title">Свяжитесь с нами</h1>
    <p class="ct-hero__sub">Бесплатно подберём санаторий под ваш диагноз — ответим за 15 минут</p>
  </div>
</div>

{{-- CONTACT CHANNELS --}}
<div class="ct-channels">
  <div class="ct-channels__wrap">
    <div class="ct-card">
      <div class="ct-card__icon">💬</div>
      <h3 class="ct-card__title">WhatsApp</h3>
      <p class="ct-card__text">Напишите нам — ответим сразу</p>
      <a href="{{ company_fact('whatsapp_url') }}?text=Здравствуйте!%20Хочу%20подобрать%20санаторий." class="ct-card__btn ct-card__btn--green" target="_blank" rel="noopener">Написать</a>
    </div>
    <div class="ct-card">
      <div class="ct-card__icon">✈️</div>
      <h3 class="ct-card__title">Telegram</h3>
      <p class="ct-card__text">Удобно писать из любой страны</p>
      <a href="{{ company_fact('telegram_url') }}" class="ct-card__btn ct-card__btn--blue" target="_blank" rel="noopener">Написать</a>
    </div>
    <div class="ct-card">
      <div class="ct-card__icon">📞</div>
      <h3 class="ct-card__title">Баку (Азербайджан)</h3>
      <p class="ct-card__text">{{ company_fact('phone_main') }}</p>
      <a href="tel:{{ company_fact('phone_main_tel') }}" class="ct-card__btn ct-card__btn--teal">Позвонить</a>
    </div>
    <div class="ct-card">
      <div class="ct-card__icon">📞</div>
      <h3 class="ct-card__title">Тбилиси (Грузия)</h3>
      <p class="ct-card__text">{{ company_fact('phone_second') }}</p>
      <a href="tel:{{ company_fact('phone_second_tel') }}" class="ct-card__btn ct-card__btn--teal">Позвонить</a>
    </div>
  </div>
</div>

{{-- CONSULTANT --}}
<div class="ct-consultant">
  <div class="ct-consultant__wrap">
    <div class="ct-consultant__avatar">👩‍⚕️</div>
    <div class="ct-consultant__text">
      <p class="ct-consultant__name">Специалист GoSanatorium</p>
      <p class="ct-consultant__desc">Подберём санаторий под ваш диагноз бесплатно. {{ company_fact('working_hours_text') }} — перезвоним и проконсультируем.</p>
    </div>
    <a href="{{ company_fact('whatsapp_url') }}?text=Здравствуйте!%20Хочу%20получить%20консультацию." class="ct-consultant__btn" target="_blank" rel="noopener">Получить консультацию</a>
  </div>
</div>

{{-- OFFICES + MAP --}}
<div class="ct-offices">
  <div class="ct-offices__wrap">
    <div class="ct-offices__info">
      <h2 class="ct-offices__title">Наши офисы</h2>
      <div class="ct-office">
        <div class="ct-office__flag">🇦🇿</div>
        <div>
          <h4 class="ct-office__city">Баку, Азербайджан</h4>
          <p class="ct-office__detail">{{ company_fact('legal_name') }}</p>
          <p class="ct-office__detail">📞 <a href="tel:{{ company_fact('phone_main_tel') }}">{{ company_fact('phone_main') }}</a></p>
          <p class="ct-office__detail">⏰ {{ company_fact('working_hours_text') }}</p>
        </div>
      </div>
      <div class="ct-office ct-office--mt">
        <div class="ct-office__flag">🇬🇪</div>
        <div>
          <h4 class="ct-office__city">Тбилиси, Грузия</h4>
          <p class="ct-office__detail">{{ company_fact('legal_name') }}</p>
          <p class="ct-office__detail">📞 <a href="tel:{{ company_fact('phone_second_tel') }}">{{ company_fact('phone_second') }}</a></p>
          <p class="ct-office__detail">⏰ {{ company_fact('working_hours_text') }}</p>
        </div>
      </div>
      <div class="ct-social">
        <p class="ct-social__label">Мы в мессенджерах:</p>
        <a href="{{ company_fact('whatsapp_url') }}" class="ct-social__link" target="_blank" rel="noopener">WhatsApp</a>
        <a href="{{ company_fact('telegram_url') }}" class="ct-social__link" target="_blank" rel="noopener">Telegram</a>
      </div>
    </div>
    <div class="ct-offices__map">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d97244.96!2d49.7529!3d40.4093!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40307d6bd67d0f5d%3A0x43aed59ba4f5c77c!2sBaku%2C%20Azerbaijan!5e0!3m2!1sen!2s!4v1"
        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade" title="GoSanatorium Baku office map">
      </iframe>
    </div>
  </div>
</div>

{{-- FORM --}}
<div class="ct-form-section">
  <div class="ct-form-section__wrap">
    <div class="ct-form-section__text">
      <h2 class="ct-form-section__title">Оставьте заявку</h2>
      <p class="ct-form-section__sub">Укажите ваш диагноз или пожелания — мы подберём подходящий санаторий и свяжемся с вами в течение 15 минут.</p>
      <ul class="ct-form-section__list">
        <li>✅ Бесплатная консультация</li>
        <li>✅ Без предоплаты</li>
        <li>✅ Ответим за 15 минут</li>
        <li>✅ {{ company_fact('working_hours_text') }}</li>
      </ul>
    </div>
    <div class="ct-form ct-form--lead">
      @include('components.front.lead-form', ['lfType' => 'contact', 'lfId' => 'contact-lead', 'lfButton' => 'Отправить заявку →'])
    </div>
  </div>
</div>

{{-- WHATSAPP CTA --}}
<div class="ct-cta">
  <div class="ct-cta__wrap">
    <h2 class="ct-cta__title">Предпочитаете WhatsApp?</h2>
    <p class="ct-cta__sub">Напишите нам прямо сейчас — подберём санаторий бесплатно</p>
    <a href="{{ company_fact('whatsapp_url') }}?text=Здравствуйте!%20Хочу%20подобрать%20санаторий." class="ct-cta__btn" target="_blank" rel="noopener">Написать в WhatsApp</a>
  </div>
</div>

@endsection


