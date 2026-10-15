@extends('layout_inner_page')

@section('title')
    <title>Вход</title>
@endsection

@section('front-content')
    @include('breadcrumb')

    <!-- login-area-start -->
    <div class="tg-login-area pt-130 pb-130">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-8 col-md-10">
                    <div class="tg-login-wrapper">
                        <div class="tg-login-top text-center mb-30">
                            <h2>Войдите в свой аккаунт</h2>
                            <p>Введите свои данные для доступа к аккаунту.</p>
                        </div>
                        <div class="tg-login-form">
                            <div class="tg-tour-about-review-form">
                                <form method="POST" action="{{ route('user.store-login') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12 mb-25">
                                            <input class="input" type="email" name="email" required
                                                placeholder="Электронная почта">
                                        </div>
                                        <div class="col-lg-12 mb-25">
                                            <input class="input" type="password" name="password" required
                                                placeholder="Пароль">
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="review-checkbox d-flex align-items-center mb-25">
                                                    <input class="tg-checkbox" type="checkbox" id="australia">
                                                    <label for="australia" class="tg-label">Запомнить меня</label>
                                                </div>
                                                <div class="tg-login-navigate mb-25">
                                                    <a href="{{ route('user.register') }}">Зарегистрироваться</a>
                                                </div>
                                            </div>

                                            @if ($general_setting->recaptcha_status == 1)
                                                <div class="mb-25">
                                                    <div class="g-recaptcha"
                                                        data-sitekey="{{ $general_setting->recaptcha_site_key }}">
                                                    </div>
                                                </div>
                                            @endif

                                            <button type="submit" class="tg-btn w-100">Войти</button>

                                            <div class="text-center mt-4">
                                                <a href="{{ route('user.forget-password') }}" class="td_semibold td_accent_color">Забыли пароль?</a>
                                            </div>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- login-area-end -->
@endsection

@push('js_section')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush