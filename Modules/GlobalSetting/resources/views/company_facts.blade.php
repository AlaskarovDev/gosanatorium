@extends('admin.master_layout')
@section('title')
    <title>Факты компании</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">Факты компании</h3>
    <p class="crancy-header__text">{{ __('translate.Website Setup') }} >> Факты компании</p>
@endsection

@section('body-content')
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">
                            <form action="{{ route('admin.company-facts-update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-12 mg-top-30">
                                        <div class="crancy-product-card">
                                            <h4 class="crancy-product-card__title">Факты и условия — одни для всего сайта</h4>
                                            <p class="mg-top-10" style="color:#64748b">
                                                Эти значения показываются на главной, «О компании», «Контакты», в подвале, FAQ,
                                                на странице «Без предоплаты», на страницах санаториев и в мета-описаниях.
                                            </p>

                                            <div class="row">
                                                @foreach ($fields as $key => [$label, $default, $type])
                                                    <div class="{{ $type === 'textarea' ? 'col-12' : 'col-md-6' }}">
                                                        <div class="crancy__item-form--group mg-top-form-20">
                                                            <label class="crancy__item-label" for="cf-{{ $key }}">{{ $label }}</label>
                                                            @if ($type === 'textarea')
                                                                <textarea class="crancy__item-input crancy__item-textarea" id="cf-{{ $key }}" name="{{ $key }}" rows="4">{{ old($key, $values[$key]) }}</textarea>
                                                            @else
                                                                <input class="crancy__item-input" type="{{ $type === 'number' ? 'number' : 'text' }}" id="cf-{{ $key }}" name="{{ $key }}" value="{{ old($key, $values[$key]) }}">
                                                            @endif
                                                            @error($key)<span class="text-danger">{{ $message }}</span>@enderror
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <h4 class="crancy-product-card__title mg-top-30">Считается автоматически</h4>
                                            <table class="table mg-top-15" style="max-width:640px">
                                                <tbody>
                                                    <tr><td>Лет опыта (текущий год − год основания)</td><td><strong>{{ $facts->experienceText() }}</strong></td></tr>
                                                    <tr><td>Санаториев-партнёров (активные в каталоге)</td><td><strong>{{ $facts->partnersCount() }}</strong></td></tr>
                                                    @foreach ($facts->sanatoriumsByCountry() as $country => $count)
                                                        <tr><td>— {{ $country }}</td><td><strong>{{ $count }}</strong></td></tr>
                                                    @endforeach
                                                    <tr><td>Отзывов (опубликованные)</td><td><strong>{{ $facts->reviewsCount() }}</strong></td></tr>
                                                </tbody>
                                            </table>

                                            <button class="crancy-btn mg-top-25" type="submit">{{ __('translate.Update') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
