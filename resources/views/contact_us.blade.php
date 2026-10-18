@extends('layout_inner_page')

@section('title')
    <title>{{ $seo_setting->seo_title }}</title>
    <meta name="title" content="{{ $seo_setting->seo_title }}">
    <meta name="description" content="{!! strip_tags(clean($seo_setting->seo_description)) !!}">
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
      <a href="https://wa.me/994519158894?text=Здравствуйте!%20Хочу%20подобрать%20санаторий." class="ct-card__btn ct-card__btn--green" target="_blank" rel="noopener">Написать</a>
    </div>
    <div class="ct-card">
      <div class="ct-card__icon">✈️</div>
      <h3 class="ct-card__title">Telegram</h3>
      <p class="ct-card__text">Удобно писать из любой страны</p>
      <a href="https://t.me/gosanatorium" class="ct-card__btn ct-card__btn--blue" target="_blank" rel="noopener">Написать</a>
    </div>
    <div class="ct-card">
      <div class="ct-card__icon">📞</div>
      <h3 class="ct-card__title">Баку (Азербайджан)</h3>
      <p class="ct-card__text">+994 51 915 88 94</p>
      <a href="tel:+994519158894" class="ct-card__btn ct-card__btn--teal">Позвонить</a>
    </div>
    <div class="ct-card">
      <div class="ct-card__icon">📞</div>
      <h3 class="ct-card__title">Тбилиси (Грузия)</h3>
      <p class="ct-card__text">+995 568 10 27 44</p>
      <a href="tel:+995568102744" class="ct-card__btn ct-card__btn--teal">Позвонить</a>
    </div>
  </div>
</div>

{{-- CONSULTANT --}}
<div class="ct-consultant">
  <div class="ct-consultant__wrap">
    <div class="ct-consultant__avatar">👩‍⚕️</div>
    <div class="ct-consultant__text">
      <p class="ct-consultant__name">Специалист GoSanatorium</p>
      <p class="ct-consultant__desc">Подберём санаторий под ваш диагноз бесплатно. Работаем 24/7 — перезвоним и проконсультируем.</p>
    </div>
    <a href="https://wa.me/994519158894?text=Здравствуйте!%20Хочу%20получить%20консультацию." class="ct-consultant__btn" target="_blank" rel="noopener">Получить консультацию</a>
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
          <p class="ct-office__detail">GoSanatorium MMC</p>
          <p class="ct-office__detail">📞 <a href="tel:+994519158894">+994 51 915 88 94</a></p>
          <p class="ct-office__detail">⏰ Работаем 24/7</p>
        </div>
      </div>
      <div class="ct-office ct-office--mt">
        <div class="ct-office__flag">🇬🇪</div>
        <div>
          <h4 class="ct-office__city">Тбилиси, Грузия</h4>
          <p class="ct-office__detail">GoSanatorium MMC</p>
          <p class="ct-office__detail">📞 <a href="tel:+995568102744">+995 568 10 27 44</a></p>
          <p class="ct-office__detail">⏰ Работаем 24/7</p>
        </div>
      </div>
      <div class="ct-social">
        <p class="ct-social__label">Мы в мессенджерах:</p>
        <a href="https://wa.me/994519158894" class="ct-social__link" target="_blank" rel="noopener">WhatsApp</a>
        <a href="https://t.me/gosanatorium" class="ct-social__link" target="_blank" rel="noopener">Telegram</a>
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
        <li>✅ Работаем 24/7</li>
      </ul>
    </div>
    <form class="ct-form" id="contact-form" action="{{ route('store-contact-message') }}" method="POST">
      @csrf
      <input type="hidden" name="instructor_id" value="0">
      <input type="hidden" name="subject" value="Заявка на подбор санатория">
      <div class="ct-form__row">
        <div class="ct-form__field">
          <label class="ct-form__label">Ваше имя *</label>
          <input class="ct-form__input" type="text" name="name" placeholder="Иван Иванов" required>
          <span class="text-danger error-name"></span>
        </div>
        <div class="ct-form__field">
          <label class="ct-form__label">Телефон *</label>
          <input class="ct-form__input" type="tel" name="phone" placeholder="+7 999 123 45 67" required>
          <span class="text-danger error-phone"></span>
        </div>
      </div>
      <div class="ct-form__field">
        <label class="ct-form__label">Email *</label>
        <input class="ct-form__input" type="email" name="email" placeholder="example@mail.com" required>
        <span class="text-danger error-email"></span>
      </div>
      <div class="ct-form__field">
        <label class="ct-form__label">Ваш диагноз или вопрос *</label>
        <textarea class="ct-form__textarea" name="message" rows="4" placeholder="Опишите ваш диагноз, желаемые даты и бюджет..." required></textarea>
        <span class="text-danger error-message"></span>
      </div>
      @if ($general_setting->recaptcha_status == 1)
        <div class="ct-form__field">
          <div class="g-recaptcha" data-sitekey="{{ $general_setting->recaptcha_site_key }}"></div>
          <span class="text-danger error-g-recaptcha-response"></span>
        </div>
      @endif
      <button type="submit" class="ct-form__submit">
        <span class="loader-text d-none">{{ __('translate.Please wait') }}</span>
        <span class="button-text">Отправить заявку →</span>
      </button>
    </form>
  </div>
</div>

{{-- WHATSAPP CTA --}}
<div class="ct-cta">
  <div class="ct-cta__wrap">
    <h2 class="ct-cta__title">Предпочитаете WhatsApp?</h2>
    <p class="ct-cta__sub">Напишите нам прямо сейчас — подберём санаторий бесплатно</p>
    <a href="https://wa.me/994519158894?text=Здравствуйте!%20Хочу%20подобрать%20санаторий." class="ct-cta__btn" target="_blank" rel="noopener">Написать в WhatsApp</a>
  </div>
</div>

@endsection

@push('js_section')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#contact-form').on('submit', function(e) {
                e.preventDefault();

                let form = $(this)[0];
                let formData = new FormData(form);

                // Clear previous errors
                $('.text-danger').text('');
                $('.ct-form__submit').attr('disabled', 'disabled');
                $('.ct-form__submit .button-text').addClass('d-none');
                $('.ct-form__submit .loader-text').removeClass('d-none');

                axios.post("{{ route('store-contact-message') }}", formData)
                    .then(function(response) {
                        if (response.data.alert_type == 'success') {
                            toastr.success(response.data.message);
                            form.reset(); // Reset form
                        }
                    })
                    .catch(function(error) {
                        if (error.response && error.response.data && error.response.data.errors) {
                            let errors = error.response.data.errors;
                            $.each(errors, function(field, messages) {
                                $('.error-' + field).text(messages[0]);
                            });
                        } else {
                            $('.ajax-response').html(
                                '<span style="color:red;">An error occurred. Please try again.</span>'
                            );
                        }
                    }).finally(function() {
                        $('.ct-form__submit').removeAttr('disabled');
                        $('.ct-form__submit .button-text').removeClass('d-none');
                        $('.ct-form__submit .loader-text').addClass('d-none');
                    });
            });
        });
    </script>
@endpush
